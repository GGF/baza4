<?php

/**
 * Description of update_model
 *
 * @author igor
 */
class update_model {

    public function phototemplates($rec) {
        extract($rec);
        $sql = "SELECT id FROM users WHERE nik='{$user}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO users (nik) VALUES ('$user')";
            sql::query($sql);
            $userid = sql::lastId();
        } else {
            $userid = $rs[id];
        }
        $sql = "INSERT INTO phototemplates 
                (ts,user_id,filenames) 
                VALUES 
                (NOW(),'{$userid}','{$filenames}')";
        sql::query($sql);
        return sql::lastId();
    }

    public function copper($rec) {
        extract($rec);
        $sql = "SELECT id FROM customers WHERE customer='{$customer}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO customers (customer) VALUES ('{$customer}')";
            sql::query($sql);
            $customer_id = sql::lastId();
        } else {
            $customer_id = $rs[id];
        }
        $sql = "SELECT id FROM blocks WHERE customer_id='{$customer_id}' AND blockname='{$board}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO blocks 
                    (scomp,ssolder,drlname,customer_id,blockname,sizex,sizey) 
                  VALUES 
                    ('{$comp}','{$solder}','{$drillname}','{$customer_id}','{$board}','{$sizex}','{$sizey}')";
            sql::query($sql);
        } else {
            $sql = "UPDATE blocks 
                    SET scomp='{$comp}', ssolder='{$solder}', drlname='{$drillname}', 
                        sizex='{$sizex}', sizey='{$sizey}' 
                WHERE id='{$rs[id]}'";
            sql::query($sql);
        }
        // а тепрерь созадидим фал копирования сверловок
        $sql = "SELECT kdir FROM customers WHERE id='{$customer_id}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            if ($customer == "Импульс") {
                $rs[0].="\\{$drillname}";
                $mpp = -1;
            }
            $out = "mkdir k:\\{$rs[kdir]}" . ($mpp != -1 ? "\\MPP" : "") . "\\\n";
            $out .= "copy /Y .\\{$drillname}.mk2 k:\\" . $rs[kdir] . ($mpp != -1 ? "\\MPP" : "") . "\\\n";
            $out .= "copy /Y .\\{$drillname}.mk4 k:\\" . $rs[kdir] . ($mpp != -1 ? "\\MPP" : "") . "\\\n";
            $out .= "copy /Y .\\{$drillname}.frz k:\\" . $rs[kdir] . ($mpp != -1 ? "\\MPP" : "") . "\\\n";
            return $out;
        }
    }

    public function addblock($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        $sql = "SELECT orders.customer_id AS id 
                FROM tz 
                JOIN (orders) ON (tz.order_id=orders.id) 
                WHERE tz.id='{$tznumber}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            return -1;
            exit;
        }
        $customer_id = $rs[id];
        // добавление блока
        $sql = "SELECT id 
                FROM blocks 
                WHERE customer_id='{$customer_id}' AND blockname='{$blockname}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO blocks 
                        (id,customer_id,blockname,sizex,sizey,thickness) 
                    VALUES
                        (NULL,'{$customer_id}','{$blockname}','{$bsizex}','{$bsizey}','{$thickness}')";
            sql::query($sql);
            $block_id = sql::lastId();
        } else {
            $block_id = $rs["id"];
            $sql = "UPDATE blocks 
                    SET customer_id='{$customer_id}',blockname='{$blockname}',
                        sizex='{$bsizex}',sizey='{$bsizey}',thickness='{$thickness}' 
                    WHERE id='{$block_id}'";
            sql::query($sql);
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
        $customer_id = $rs[id];
        // плату
        // коментарий
        $sql = "SELECT id FROM boards WHERE customer_id='$customer_id' AND board_name='$board'";
        $rs = sql::fetchOne($sql);
        if (empty($comment)) {
            $comment_id = $rs[comment_id];
        } else {
            $sql = "SELECT * FROM coments WHERE comment='{$comment}'";
            $com = sql::fetchOne($sql);
            if (empty($com)) {
                $sql = "INSERT INTO coments (comment) VALUES ('{$comment}')";
                sql::query($sql);
                $comment_id = sql::lastId();
            } else {
                $comment_id = $com[id];
            }
        }
        $sql = "REPLACE INTO boards 
        (id,board_name,customer_id,sizex,sizey,thickness,
        texеolite,textolitepsi,thick_tol,rmark,frezcorner,layers,razr,
        pallad,immer,aurum,numlam,lsizex,lsizey,mask,mark,glasscloth,
        class,complexity_factor,frez_factor,comment_id)
        VALUES ('{$rs["id"]}' , '{$board}' ,'{$customer_id}' ,'{$sizex}' ,'{$sizey}' ,
        '{$thickness}' ,'{$textolite}' ,'{$textolitepsi}' ,'{$thick_tol}' ,
        '{$rmark}' ,'{$frezcorner}' ,'{$layers}' ,'{$razr}' ,'{$pallad}' ,'{$immer}' ,
        '{$aurum}' ,'{$numlam}' ,'{$lsizex}' ,'{$lsizey}' ,'{$mask}' ,'{$mark}' ,
        '{$glasscloth}' ,'{$class}' ,'{$complexity_factor}' ,'{$frez_factor}','{$comment_id}')";
        sql::query($sql);

        $plate_id = sql::lastId();

        // позицию к блоку
        $sql = "INSERT INTO blockpos (block_id,board_id,nib,nx,ny) VALUES ('{$block_id}','{$plate_id}','{$num}','{$bnx}','{$bny}')";
        sql::query($sql);

        return $plate_id;
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
        $customer_id = $rs[id];

        // Определим идентификатор пользователя
        $sql = "SELECT id FROM users WHERE nik='{$user}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $user_id = $rs["id"];
        } else {
            $sql = "INSERT INTO users (nik) VALUES ('{$user}')";
            sql::query($sql);
            $user_id = sql::lastId();
        }

        // определим плату
        $sql = "SELECT id FROM plates WHERE customer_id='{$customer_id}' AND plate='{$board}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $plate_id = $rs["id"];
        } else {
            $sql = "INSERT INTO plates (customer_id,plate) VALUES ('{$customer_id}','{$board}')";
            sql::query($sql) or die(sql::error(true));
            $plate_id = sql::lastId();
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
        $sql = "SELECT * FROM coments WHERE comment='{$comment}'";
        $com = sql::fetchOne($sql);
        if (empty($com)) {
            $sql = "INSERT INTO coments (comment) VALUES ('{$comment}')";
            sql::query($sql);
            $comment_id = sql::lastId();
        } else {
            $comment_id = $com[id];
        }

        // добавим МП если есть такое исправим
        $sql = "SELECT * FROM posintz WHERE tz_id='{$tznumber}' AND posintz='{$posintz}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO posintz 
                    (tz_id,posintz,plate_id,board_id,block_id,numbers,first,
                        srok,priem,constr,template_check,template_make,
                        eltest,numpl1,numpl2,numpl3,numpl4,numpl5,numpl6,numbl,
                        pitz_mater,pitz_psimat,comment_id) 
                    VALUES 
                        ('{$tznumber}','{$posintz}','{$plate_id}','{$board_id}',
                        '{$block_id}','{$numbers}','{$first}','{$srok}','{$priem}',
                        '{$constr}','{$template_check}','{$template_make}',
                        '{$eltest}','{$numpl1}','{$numpl2}','{$numpl3}',
                        '{$numpl4}','{$numpl5}','{$numpl6}','{$numbl}',
                        '{$textolite}','{$textolitepsi}','{$comment_id}')";
            sql::query($sql);
            $pit_id = sql::lastId();
        } else {
            $sql = "UPDATE posintz 
                        SET numbers='{$numbers}', plate_id='{$board_id}',
                            plate_id='{$board_id}', block_id='{$block_id}',
                            first='{$first}',srok='{$srok}',priem='{$priem}',
                            constr='{$constr}',template_check='{$template_check}',
                            template_make='{$template_make}', eltest='{$eltest}', 
                            numpl1='{$numpl1}', numpl2='{$numpl2}', numpl3='{$numpl3}', 
                            numpl4='{$numpl4}', numpl5='{$numpl5}', numpl6='{$numpl6}',
                            numbl='{$numbl}', pitz_mater='{$textolite}', 
                            pitz_psimat='{$textolitepsi}', comment_id='{$comment_id}' 
                  WHERE id='{$rs[id]}'";
            sql::query($sql);
            $pit_id = $rs["id"];
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

}

?>
