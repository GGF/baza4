<?php

/**
 * Description of update_model
 *
 * @author igor
 */
class update_model {

    public function phototemplates($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        $sql = "SELECT id FROM users WHERE nik='{$user}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO users (nik,password) VALUES ('$user',now())";
            sql::query($sql);
            $userid = sql::lastId();
        } else {
            $userid = $rs['id'];
        }
        $sql = "INSERT INTO phototemplates
                (ts,user_id,filenames)
                VALUES
                (NOW(),'{$userid}','{$filenames}')";
        sql::query($sql);
        return sql::lastId();
    }
    /**
     * запись количества отверстий на плату, фрезы отрицательный диаметр имеют
     * drillsostav имеет формат size|count x size|count x size|count x
     * то есть после последнего икса ничего нет
     * только для плат
     * Добавлено поле для плат
     * ALTER TABLE `boards` ADD `extinfo` TEXT NOT NULL COMMENT 'Дополнительная информация в JSON или тексте' AFTER `comment_id`; 
     */
    public function drillcount($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);

        $sql = "SELECT id FROM customers WHERE customer='{$customer}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO customers (customer) VALUES ('{$customer}')";
            sql::query($sql);
            echo $sql;
            $customer_id = sql::lastId();
        } else {
            $customer_id = $rs['id'];
        }
        // получить идентификатор платы всё равно нужно
        $sql = "SELECT id FROM boards WHERE customer_id='{$customer_id}' AND board_name='{$board}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            /*
            пустую не меняем
            */
        } else {
            $board_id = $rs["id"];
        }

        $sql = "UPDATE boards SET extinfo = '{$drillsostav}' WHERE id='{$board_id}'";
        sql::query($sql);
        echo "{$board_id}\r\n";
    }

    public function copper($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        $sql = "SELECT id FROM customers WHERE customer='{$customer}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO customers (customer) VALUES ('{$customer}')";
            sql::query($sql);
            $customer_id = sql::lastId();
        } else {
            $customer_id = $rs['id'];
        }
        $sql = "SELECT id FROM blocks WHERE customer_id='{$customer_id}' AND blockname='{$board}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO blocks
                    (scomp,ssolder,drlname,customer_id,blockname,sizex,sizey,auarea,smalldrill,bigdrill)
                  VALUES
                    ('{$comp}','{$solder}','{$drillname}','{$customer_id}','{$board}','{$sizex}','{$sizey}','{$auarea}','{$smalldrill}','{$bigdrill}')";
            sql::query($sql);
            $block_id = sql::lastId();
            $sql = "SELECT id FROM boards WHERE customer_id='{$customer_id}' AND board_name='{$board}'";
            $rs = sql::fetchOne($sql);
            if (empty($rs)) {
                $sql = "INSERT INTO boards
                            (customer_id,board_name)
                        VALUES
                            ('{$customer_id}','{$board}')";
                //echo $sql;
                sql::query($sql);
                $board_id = sql::lastId();
            } else {
                $board_id = $rs["id"];
            }
            $sql = "INSERT INTO blockpos
                        (block_id,board_id,nib,nx,ny)
                    VALUES
                        ('{$block_id}','{$board_id}','1','1','1')";
            //echo $sql;
            sql::query($sql);
        } else {
            $sql = "UPDATE blocks
                    SET scomp='{$comp}', ssolder='{$solder}', drlname='{$drillname}',
                        sizex='{$sizex}', sizey='{$sizey}',
                        auarea='{$auarea}', smalldrill='{$smalldrill}', bigdrill='{$bigdrill}'
                WHERE id='{$rs['id']}'";
            sql::query($sql);
        }
        // а тепрерь созадидим фал копирования сверловок
        /*
        $sql = "SELECT kdir FROM customers WHERE id='{$customer_id}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            if ($customer == "Импульс") {
                $rs[kdir] .= "\\{$drillname}";
                $mpp = -1;
            }
            $out = "mkdir k:\\{$rs[kdir]}" . ($mpp != -1 ? "\\MPP" : "") . "\\\n";
            $out .= "copy /Y .\\{$drillname}.mk2 k:\\" . $rs[kdir] . ($mpp != -1 ? "\\MPP" : "") . "\\\n";
            $out .= "copy /Y .\\{$drillname}.mk4 k:\\" . $rs[kdir] . ($mpp != -1 ? "\\MPP" : "") . "\\\n";
            $out .= "copy /Y .\\{$drillname}.frz k:\\" . $rs[kdir] . ($mpp != -1 ? "\\MPP" : "") . "\\\n";
            return $out;
        }
        */
    }

    public function addblock($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        // определим заказчика
        $sql = "SELECT orders.customer_id AS id
                FROM tz
                JOIN (orders) ON (tz.order_id=orders.id)
                WHERE tz.id='{$tznumber}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            return -1;
            exit;
        }
        $customer_id = $rs['id'];
        // добавление блока
       
        $sql = "SELECT *
                FROM blocks
                WHERE customer_id='{$customer_id}' AND blockname='{$blockname}'";
        $rs = sql::fetchOne($sql);
        // комментарий к блоку содержит JSON
        $params = json_decode(multibyte::Unescape(sqltable_model::getComment($rs["comment_id"])),true); //получим текщий комент
        if(!empty($comment)) {
            $params['coment'] = $comment;
        } 
        // добавим туда электроконтроль
        $params['eltest'] = $eltest;
        $params['etpib'] = $etpib;
        $params['etpoints'] = $etpoints;
        $params['etcompl'] = $etcompl;
        $rs['comment_id'] = sqltable_model::getCommentId(multibyte::Json_encode(multibyte::recursiveEscape($params)));
        /*
         * логика  исправления такая, в базе связал коментарии forignkey
         * то есть пустой долже быть единицей, если блок новый и передан 
         * пустой комент, то такого поля и не будет
         * в rs, если не пустой комент то получится новый JSON, а если не новый 
         * блок то поменяется в JSON только коментарий и электроконтроль
         */
        $rs['sizex']=$bsizex;
        $rs['sizey']=$bsizey;
        $rs['thickness']=$thickness;
        $rs['customer_id']=$customer_id;
        $rs['blockname']=$blockname;
        $rs['auarea'] = $gold;
        sql::insertUpdate("blocks",array($rs));
        if (empty($rs["id"])) {
            $block_id = sql::lastId();
        } else {
            $block_id = $rs["id"];
        }
        // удалим позиции с блока потому что они будут добавляться из ТЗ
        $sql = "DELETE FROM blockpos WHERE block_id='{$block_id}'";
        sql::query($sql);

        return $block_id;
    }

    public function addblockpos($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        // заказчик по tzid
        $sql = "SELECT orders.customer_id AS id FROM tz JOIN (orders) ON (tz.order_id=orders.id) WHERE tz.id='{$tznumber}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            return -1;
            exit;
        }
        $customer_id = $rs['id'];
        // плату
        // коментарий
        $sql = "SELECT id, comment_id FROM boards WHERE customer_id='{$customer_id}' AND board_name='{$board}'";
        $rs = sql::fetchOne($sql);
        if (!empty($comment)) {
            $rs['comment_id'] = sqltable_model::getCommentId($comment);
        }
        $rs['board_name'] = $board;
        $rs = array_merge($rs,compact('customer_id','sizex','sizey','thickness',
                'textolite','rmark','frezcorner',
                'layers','razr','immer','numlam','lsizex',
                'lsizey','mask','mark','glasscloth','class','complexity_factor',
                'frez_factor'));
        sql::insertUpdate('boards',array($rs));

        $board_id = empty($rs["id"]) ? sql::lastId() : $rs["id"]; // если редактура ластид возвращает нуль

        // позицию к блоку
        $sql = "INSERT INTO blockpos (block_id,board_id,nib,nx,ny) VALUES ('{$block_id}','{$board_id}','{$num}','{$bnx}','{$bny}')";
        sql::query($sql);

        return $board_id;
    }

    public function addposintz($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        // заказчик по tzid
        $sql = "SELECT orders.customer_id AS id FROM tz
                JOIN (orders) ON (tz.order_id=orders.id) WHERE tz.id='{$tznumber}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            return -1;
        }
        $customer_id = $rs['id'];

        // Определим идентификатор пользователя
        $sql = "SELECT id FROM users WHERE nik='{$user}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $user_id = $rs['id'];
        } else {
            $sql = "INSERT INTO users (nik) VALUES ('{$user}')";
            sql::query($sql);
            $user_id = sql::lastId();
        }

        // определим плату
        $sql = "SELECT id FROM boards WHERE customer_id='{$customer_id}' AND board_name='{$board}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $board_id = $rs["id"];
        } else {
            // не буду добавлять - данных нет и это делается в другом месте
        }
        // коментарий
        $comment_id = sqltable_model::getCommentId($comment);

        // добавим МП если есть такое исправим
        $sql = "SELECT * FROM posintz WHERE tz_id='{$tznumber}' AND posintz='{$posintz}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO posintz
                    (tz_id,posintz,block_id,numbers,first,
                        srok,priem,constr,template_check,template_make,
                        eltest,numpl1,numpl2,numpl3,numpl4,numpl5,numpl6,numbl,
                        pitz_mater,comment_id)
                    VALUES
                        ('{$tznumber}','{$posintz}',
                        '{$block_id}','{$numbers}','{$first}','{$srok}','{$priem}',
                        '{$constr}','{$template_check}','{$template_make}',
                        '{$eltest}','{$numpl1}','{$numpl2}','{$numpl3}',
                        '{$numpl4}','{$numpl5}','{$numpl6}','{$numbl}',
                        '{$textolite}','{$comment_id}')";
            sql::query($sql);
            $pit_id = sql::lastId();
        } else {
            $sql = "UPDATE posintz
                        SET numbers='{$numbers}', 
                            block_id='{$block_id}',
                            first='{$first}',srok='{$srok}',priem='{$priem}',
                            constr='{$constr}',template_check='{$template_check}',
                            template_make='{$template_make}', eltest='{$eltest}',
                            numpl1='{$numpl1}', numpl2='{$numpl2}', numpl3='{$numpl3}',
                            numpl4='{$numpl4}', numpl5='{$numpl5}', numpl6='{$numpl6}',
                            numbl='{$numbl}', pitz_mater='{$textolite}',
                            comment_id='{$comment_id}'
                  WHERE id='{$rs['id']}'";
            sql::query($sql);
            $pit_id = $rs['id'];
            // обновить запуски если некоторые позиции уже запускались
            $sql = "SELECT * FROM lanch WHERE pos_in_tz_id='{$pit_id}'";
            $rs = sql::fetchOne($sql);
            if (empty($rs)) {
                $sql = "UPDATE posintz SET ldate='{$rs["ldate"]}' WHERE id='{$pit_id}'";
                sql::query($sql);
            }
        }

        return $pit_id;
    }

    public function update_lanched() {
        $out = '';
        $sql = "TRUNCATE TABLE `lanched`";
        sql::query($sql);
        $out .= sql::error() . "<br>";
        $sql = "SELECT block_id, MAX( ldate ) AS md
                FROM lanch
                WHERE ldate >= DATE_SUB(NOW(),INTERVAL 1 MONTH)
                GROUP BY block_id
                ";
        $rs = sql::fetchAll($sql);
        foreach ($rs as $res) {
            $sql = "INSERT INTO lanched SET block_id='{$res['block_id']}', lastdate='{$res['md']}'";
            sql::query($sql);
            $out .= sql::error() . "<br>";
        }
        return $out;
    }

    /**
     * Удаляет  позицию ТЗ, если  она была уже внесена. Тоесть  сначала ТЗ было на три
     * позиции, а после одну удалили. Надо её стереть.
     * Получает переменные в гет запросе tznumber и posintz. Соотвественно
     * идентификатор и число от 1 до трех
     * Без  проверок и чеголибо. TODO: прооверки добавить.
     */
    public function delposintz($rec) {
	$rec = multibyte::cp1251_to_utf8($rec);
        //extract($rec);
	$tznumber = $rec["tznumber"];
	$posintz = $rec["posintz"];
	$sql = "DELETE FROM posintz WHERE tz_id='{$tznumber}' AND posintz='{$posintz}'";
	sql::query($sql);
	return true;
    }

    /**
     * Для коппирования файлов по версиям - датам
     * При выгоне очередной сверловки вызывается эта функция
     * и вызывается получившийся бат файл
     */
    public function mkrfrz($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        // TODO duble code
        // update block size
        extract($rec);
        $sql = "SELECT id FROM customers WHERE customer='{$customer}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO customers (customer) VALUES ('{$customer}')";
            sql::query($sql);
            $customer_id = sql::lastId();
        } else {
            $customer_id = $rs['id'];
        }
        $sql = "SELECT id FROM blocks WHERE customer_id='{$customer_id}' AND blockname='{$board}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $sql="UPDATE blocks SET sizex='{$sizex}', sizey='{$sizey}' WHERE id='{$rs['id']}'";
            sql::fetchOne($sql);
        }
        
        $mpp = $rec["mpp"];
        $customer = $rec["customer"];
        $drillname = $rec["drillname"];
        $sql = "SELECT id,kdir FROM customers WHERE customer='{$customer}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $date = date("ymd");
            $rs['kdir'] .=  "\\{$drillname}\\{$date}";
            //$rs[kdir] .= ($mpp != -1 ? "\\MPP" : "\\DPP") . "\\{$drillname}\\{$date}";
            $out = "mkdir k:\\{$rs['kdir']}" . "\\\n";
            //$out .= "copy /Y .\\{$drillname}.mk2 k:\\" . $rs['kdir'] . "\\\n";
            //$out .= "copy /Y .\\{$drillname}.mk4 k:\\" . $rs['kdir'] . "\\\n";
            //$out .= "copy /Y .\\{$drillname}.frz k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}.ex2 k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}.sch k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}.mx1 k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}.prl k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}.fx2 k:\\" . $rs['kdir'] . "\\\n";
            //$out .= "copy /Y .\\{$drillname}-2.mk2 k:\\" . $rs['kdir'] . "\\\n";
            //$out .= "copy /Y .\\{$drillname}-2.mk4 k:\\" . $rs['kdir'] . "\\\n";
            //$out .= "copy /Y .\\{$drillname}-2.frz k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}-*.ex2 k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}-*.sch k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}-*.mx1 k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}-*.prl k:\\" . $rs['kdir'] . "\\\n";
            $out .= "copy /Y .\\{$drillname}-*.fx2 k:\\" . $rs['kdir'] . "\\\n";
            return $out;
        }        
    }
    
    /**
     * Разбирает строку типа данныеХданные|данныеХданные|
     * @param string $str
     * @return var
     */
    public function parsexstring($str) {
        $vars = explode("|",$str);
        foreach ($vars as $value) {
            if (!empty($value)) {
                $res=array();
                $array = explode("x", $value);
                foreach ($array as $val) {
                    $res[]=$val;
                }
                $result[]=$res;
            }
        }
        return $result;
    }

    /**
     * Добавляет к блоку данные о проводниках и зазорах
     */
     public function wideandgap($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        // TODO duble code
        // update block size
        extract($rec);
        $sql = "SELECT id FROM customers WHERE customer='{$customer}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO customers (customer) VALUES ('{$customer}')";
            sql::query($sql);
            $customer_id = sql::lastId();
        } else {
            $customer_id = $rs['id'];
        }
        $sql = "SELECT id,comment_id FROM blocks WHERE customer_id='{$customer_id}' AND blockname='{$board}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $params = json_decode(multibyte::Unescape(sqltable_model::getComment($rs["comment_id"])),true); //получим текщий комент
            $params["wideandgaps"] = update_model::parsexstring($wideandgaps);
            $comment_id = sqltable_model::getCommentId(multibyte::Json_encode(multibyte::recursiveEscape($params)));
            $id = $rs["id"];
            $sql="UPDATE blocks SET comment_id='{$comment_id}' WHERE id='{$id}'";
            sql::query($sql);
            echo json_encode($params);
        } 
        /* если не нашелся такой блок, то не понятно как вызывался метод, блок создается раньше вызовом coppers и другими
         * wideandgaps вызывается для готовых блоков, не вижу смысла отрабатывать ситуацию когда блока нет
         */
     }
     
     /**
      * Сохраняет или обновляет данные о расчете цены в таблице orderformoney
     * @param array $rec
     * @return var
      */
     public function moneyfororder($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        if (empty($position)) $position = 0; // для совместимости
        $rec['hash'] = hash('md5',$customer.$order.$board.$position.$mater.$trud);
        $sql = "SELECT * FROM moneyfororder WHERE hash='{$rec['hash']}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $rec['id'] = $rs['id'];
        }
        //echo $sql;
        sql::insertUpdate("moneyfororder", array($rec));
        return true;
     }
     
     /**
      * Обновление размера платы
      */
     public function updatesize($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        $sql = "SELECT id FROM customers WHERE customer='{$customer}'";
        echo $sql;
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO customers (customer) VALUES ('{$customer}')";
            sql::query($sql);
            $customer_id = sql::lastId();
        } else {
            $customer_id = $rs['id'];
        }
        // не заморачиваемся обновлением блоков
        $sql = "SELECT id FROM boards WHERE customer_id='{$customer_id}' AND board_name='{$board}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO boards
                        (customer_id,board_name)
                    VALUES
                        ('{$customer_id}','{$board}')";
            echo $sql;
            sql::query($sql);
            $board_id = sql::lastId();
        } else {
            $board_id = $rs["id"];
        }
        // добавим размеры, ради чего всё и затевалось
        $sql = "UPDATE boards SET sizex='{$sizex}', sizey='{$sizey}' WHERE id='{$board_id}'";
        echo $sql; 
        sql::query($sql);

    }

}

?>
