<?php

/*
 * nzap model class
 */

class lanch_nzap_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'posintz';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT *,orders.number AS ordernum,SUM(zadel.number) AS zadelnum, posintz.id AS nzid,posintz.id,
	    IF(boards.layers>2,'МПП','ДПП') AS boardtype,
        IF(first=1,'Новая','') AS new
        FROM posintz
        LEFT JOIN (lanched) ON (posintz.block_id=lanched.block_id)
        JOIN (blocks,tz,filelinks,customers,orders,blockpos,boards)
            ON (tz.order_id=orders.id
            AND blocks.id=posintz.block_id
            AND posintz.tz_id=tz.id
            AND tz.file_link_id=filelinks.id
            AND blocks.customer_id=customers.id
            AND blocks.id=blockpos.block_id
            AND blockpos.board_id = boards.id
            )
        LEFT JOIN (zadel) ON zadel.board_id = boards.id
        WHERE posintz.ldate = '0000-00-00' "
                . (!empty($find) ? "AND (blocks.blockname LIKE '%{$find}%'
            OR board_name LIKE '%{$find}%'
            OR filelinks.file_link LIKE '%{$find}%'
            OR orders.number LIKE '%{$find}%') " : "")
                . " GROUP BY posintz.id,blocks.blockname "
                . (!empty($order) ? "ORDER BY {$order} " :
                        "ORDER BY customers.customer,tz.id,posintz.id ")
                . ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols["№"] = "№";
        $cols["nzid"] = "ID";
        $cols["customer"] = "Заказчик";
        $cols["ordernum"] = "Заказ";
        $cols["blockname"] = "Плата";
        $cols["boardtype"] = "Тип";
        $cols["numbers"] = "Кол-во";
        $cols["new"] = "Новая";
        $cols["lastdate"] = "Посл. зап";
        $cols["zadelnum"] = "Взаделе";
        return $cols;
    }

    public function delete($id) {
        //$sql = "DELETE FROM posintz WHERE id='{$id}'";
        $sql = "UPDATE posintz SET ldate=NOW(),luser_id='" . Auth::getInstance()->getUser('id') . "' WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }

    public function getRecord($id) {
        $sql = "SELECT *,tz.id AS tzid, blocks.sizex AS bsizex,
            blocks.sizey AS bsizey,
            blocks.id AS bid
            FROM posintz JOIN (tz,filelinks)
                            ON (tz.id=posintz.tz_id
                                    AND tz.file_link_id=filelinks.id )
                         LEFT JOIN (blocks,customers) ON (blocks.id=block_id AND blocks.customer_id=customers.id)
                WHERE posintz.id='{$id}'";
        $rs = sql::fetchOne($sql);
        //$rec[block][filelink] = Auth::getInstance()->getRights("nzap","edit")?fileserver::sharefilelink($rs["file_link"]):"";
        $rec[block][filelink] = fileserver::sharefilelink($rs["file_link"]);
        $rec[block][zpath] = "z:\\Заказчики\\{$rs['customer']}\\{$rs['blockname']}";
        $rec[block][blockname] = $rs[blockname];
        $rec[block][boardinorder] = $rs[numbers];
        $rec[block][blocksizex] = ceil($rs[bsizex]);
        $rec[block][blocksizey] = ceil($rs[bsizey]);
        $rec[block][phtemplates] = ($rs["template_make"] == '0' ?
                        $rs["template_check"] : $rs["template_make"]);
        // файлы  для заказа
        $files = $this->getFilesForId('orders', $rs[order_id]);
        $rec[block][orderfiles] = $files[link];
        //
        $sql = "SELECT *,
                    SUM(zadel.number) AS zadelnum,
                    boards.sizex AS psizex,
                    boards.sizey AS psizey,
                    boards.id AS bid
        FROM blockpos
        JOIN (customers,blocks,boards)
        ON (customers.id=boards.customer_id
            AND blocks.id=block_id
            AND boards.id=board_id)
        LEFT JOIN (zadel) ON (zadel.board_id = boards.id)
        WHERE block_id='{$rs["bid"]}' GROUP BY boards.id ORDER BY blockpos.id";
        $res = sql::fetchAll($sql);
        $nz = 0; // максимальное количество заготовок по количеству плат в блоке
        $nl = 0; // максимальное количество слоев на плате в блоке, хотя бред
        $cl = 0; // класс платы, наибольший по позициям
        $piz = 0; // число плат на заготовке (сумма по блоку)
        if (count($res) > 1 ) {
            // боольше одной платы в блоке, не получится использовать задел
            $rec[zadel] = 0;
            foreach ($res as $rs1) {
                $board[name] = $rs1["board_name"];
                $board[sizex] = $rs1["psizex"];
                $board[sizey] = $rs1["psizey"];
                $board[numberinblock] = $rs1["nib"];
                $board[numberinblockx] = $rs1["nx"];
                $board[numberinblocky] = $rs1["ny"];
                $board[layers] = $rs1["layers"];
                $board[mask] = $rs1["mask"];
                $board[mark] = $rs1["mark"];
                $sql = "SELECT numbers FROM posintz WHERE tz_id='{$rs["tzid"]}'
                        AND board_id='{$rs1["bid"]}'";
                $rs2 = sql::fetchOne($sql);
                $nz = max($nz, ceil($rs2["numbers"] / $rs1["nib"]));
                $nl = max($nl, $rs1["layers"]);
                $cl = max($cl, $rs1["class"]);
                $piz += $rs1["nib"];
                $customer = $rs1["customer"];
                $rec[boards][] = $board;
            }
        } else {
            // тольько одна позиция в блоке, съэкономим на обработке массива for each
                $rs1 = $res[0];
                $board[name] = $rs1["board_name"];
                $board[sizex] = $rs1["psizex"];
                $board[sizey] = $rs1["psizey"];
                $board[numberinblock] = $rs1["nib"];
                $board[numberinblockx] = $rs1["nx"];
                $board[numberinblocky] = $rs1["ny"];
                $board[layers] = $rs1["layers"];
                $board[mask] = $rs1["mask"];
                $board[mark] = $rs1["mark"];
                $sql = "SELECT numbers FROM posintz WHERE tz_id='{$rs["tzid"]}'
                        AND board_id='{$rs1["bid"]}'";
                $rs2 = sql::fetchOne($sql);
                $nz = ceil($rs2["numbers"] / $rs1["nib"]);
                $nl = $rs1["layers"];
                $cl = $rs1["class"];
                $piz = $rs1["nib"];
                $customer = $rs1["customer"];
                $rec[boards][] = $board;
                $rec[zadel] = $rs1["zadelnum"];
         }
        //if (Auth::getInstance()->getRights("nzap","edit")) {
        if ($nl > 2) {
            // многослойку радара партии по одной
            if ($rs["customer_id"] == '8') // радар
                $zip = 1;
            else
                $zip = 5;
            // если первичный запуск - мастерплата
            if ($rs["first"] == '1' || $rs["template_make"] > 0) {
                $sql = "SELECT * FROM masterplate WHERE posid='{$id}'";
                $mp = sql::fetchOne($sql);
                if (empty($mp)) {
                    $rec[mp] = array(mplink => true);
                }
            }
            $dpp = false;
        } else {
            // одно-двухстороняя
            $zip = 25;
            $dpp = true;
            // если больше пяти заготовок - мастерплата,
            // хотя обойдутся без соповодительного листа
        }
        if (0 == $nz) {
            $nz = $rs[numbl];
        }
        $rec[parties] = ceil($nz / $zip);
        for ($i = 1; $i <= $rec[parties]; $i++) {
            $party = false;
            $sql = "SELECT lanch.id as lid,file_link FROM lanch
                        JOIN filelinks ON (file_link_id=filelinks.id)
                        WHERE tz_id='{$rs["tz_id"]}'
                            AND pos_in_tz='{$rs["posintz"]}' AND part='{$i}'";
            $rs3 = sql::fetchOne($sql);
            if (!empty($rs3)) {
                $party[sllink] = fileserver::sharefilelink($rs3["file_link"]);
                $party[slid] = $rs3[lid];
            } else {
                $party[party] = $i;
                $party[type] = $dpp ? "dpp" : "mpp";
            }
            $rec[party][] = $party;
        }
        //}
        $rec["edit"] = $id;
        return $rec;
    }

    public function getMasterplate($tzposid) {
        $sql = "SELECT * FROM posintz JOIN (tz,orders) ON (tz.id=tz_id AND orders.id=tz.order_id) WHERE posintz.id='{$tzposid}'";
        $rs = sql::fetchOne($sql);
        $posintz = $rs[posintz];
        $tzid = $rs[tz_id];
        $block_id = $rs[block_id];
        $customer_id = $rs[customer_id];
        $sql = "SELECT * FROM masterplate WHERE tz_id='{$tzid}' AND posintz='{$posintz}'";
        $res = sql::fetchOne($sql);
        if (empty($res)) {
            $sql = "INSERT INTO masterplate (tz_id,posintz,mpdate,user_id,posid,customer_id,block_id)
                    VALUES ('{$tzid}','{$posintz}',Now(),'" . Auth::getInstance()->getUser('userid') . "','{$tzposid}','{$customer_id}','{$block_id}')";
            sql::query($sql);
            $rec[mp_id] = sql::lastId();
        } else {
            $sql = "UPDATE masterplate SET mpdate=NOW(), user_id='" . Auth::getInstance()->getUser('userid') . "' WHERE id='{$res[id]}'";
            $rs = sql::fetchOne($res);
            $rec[mp_id] = $res["id"];
        }
        $sql = "SELECT * FROM blocks JOIN customers ON blocks.customer_id=customers.id WHERE blocks.id='{$block_id}'";
        $rs = sql::fetchOne($sql);
        $rec[customer] = $rs[customer];
        $rec[blockname] = $rs[blockname];
        $rec[sizex] = $rs[sizex];
        $rec[sizey] = $rs[sizey];
        $rec[drlname] = $rs[drlname];
        $rec[date] = date("Y-m-d");
        return $rec;
    }

    public function getPartyfile($rec) {
        extract($rec);
        if ($dozap===true) {
            $sql = "SELECT pos_in_tz_id AS posid
            FROM lanch
            WHERE lanch.id='{$posid}'";
            //echo $sql;
            $rs = sql::fetchOne($sql);
            sql::error(true);
            $rec[posid] = $posid = $rs[posid];
            $rec[dozapnumbers] = $party; // тут было записано сколько при дозапуске
            $rec[party] = $party = -2;
        } elseif($dozap=="zadel") {
            $rec[dozapnumbers] = $party;
            $rec[party] = $party = -1;
        }
        // Получим идентификатор запуска, нового или уже сущечтвующего
        // почти всегда, кажется, нового
        // давай посмотрим варианты
        // 1-запуск
        // 2-дозапуск
        // 3-использование задела
        // при запуске могло использоваться, но теперь при удалении будет другое значение
        // дозапуск не получится если не удален предыдущий запуск
        // делаем всегда новое
        /*
        $sql = "SELECT * FROM lanch
            WHERE pos_in_tz_id='{$posid}' AND part='{$party}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO lanch
            (ldate, user_id,pos_in_tz_id)
            VALUES (NOW(),'" . Auth::getInstance()->getUser('userid') . "','{$posid}')";
            sql::query($sql);
            $lanch_id = sql::lastId();
        } else {
            $lanch_id = $rs["id"];
        }
        */
            // Определим идентификатор коментария
            $comment_id = 1; //пустой            
            $sql = "INSERT INTO lanch
            (ldate, user_id,pos_in_tz_id,comment_id)
            VALUES (NOW(),'" . Auth::getInstance()->getUser('userid') . "','{$posid}','{$comment_id}')";
            sql::query($sql);
            $lanch_id = sql::lastId();

        // вернем вызывальщику
        $rec[lanch_id] = $lanch_id;
        // заказчик и имя блока
        $sql = "SELECT *
                FROM posintz
                JOIN (blocks,customers)
                ON (blocks.id=posintz.block_id AND blocks.customer_id=customers.id)
                WHERE posintz.id='{$posid}'";
        $rs = sql::fetchOne($sql);
        $rec[customer] = $customer = $rs[customer];
        $rec[blockname] = $blockname = $rs[blockname];
        $rec[block_id] = $rs[block_id];

        return $rec;
    }

    public function getParty($rec) {
        extract($rec);
        $rec = $this->getPartyfile($rec);
        // определимся с типом ДПП или МПП
        $sql = "SELECT * FROM blockpos WHERE block_id='{$rec[block_id]}'";
        $res = sql::fetchOne($sql);
        $sql = "SELECT * FROM boards WHERE id='{$res[board_id]}'";
        $res = sql::fetchOne($sql);
        $rec[dpp] = $res[layers] <= 2;
        $rec[date] = date("d-m-Y");
        return $rec;
    }

    public function getSl($rec) {
        if ($rec[dpp]) {
            $rec = $this->getSlDpp($rec);
        } else {
            $rec = $this->getSlMpp($rec);
        }
        extract($rec);
        // Определим идентификатор файловой ссылки
        $rec["l_date"] = $l_date = date("Y-m-d");
        $file_link = 
                "z:\\Заказчики\\{$customer}\\{$blockname}\\запуски\\" .
                "СЛ-{$l_date}-{$lanch_id}.xml";
        $rec["template"] = ($dpp ? 
                    ((stristr($mater,"TLX") || stristr($mater,"ro") || stristr($mater,"ФАФ")) ? 
                        "/slro.xml" : 
                        "/sldpp4.xls"
//                        ($class==3?
//                            ($aurum=="+"?"/sl3a.xml":"/sl3.xml"):
//                            ($aurum=="+"?"/sl4a.xml":"/sl4.xml")
//                        )
                    ):"/slmpp{$class}.xls");
        $fileext = explode(".",$rec["template"]);
        $rec["fileext"] = $fileext[1];
        $rec["file_link"] = str_replace("xml", $rec["fileext"], $file_link);
        $rec["filename"] = fileserver::createdironserver($rec["file_link"]);
        $rec[file_id] = $file_id = $this->getFileId($rec["file_link"]);
        $sql = "UPDATE lanch
                SET file_link_id='{$file_id}'
                WHERE id='{$lanch_id}'";
        sql::query($sql);

        return $rec;
    }

    public function getSlDpp($rec) {
        extract($rec);
        $ek = $boardname1 = $boardname2 = $boardname3 = $boardname4 = $boardname5 = $boardname6 =
                $nib1 = $nib2 = $nib3 = $nib4 = $nib5 = $nib6 =
                $psizex1 = $niz1 = $pio1 = $ppart1 = $psizex2 = $niz2 = $pio2 = $ppart2 = $psizex3 = $niz3 = $pio3 = $ppart3 =
                $psizex4 = $niz4 = $pio4 = $ppart4 = $psizex5 = $niz5 = $pio5 = $ppart5 = $psizex6 = $niz6 = $pio6 = $ppart6 = '';
// получить данные в переменные
        $sql = "SELECT
        orderdate AS ldate,
        orders.number AS letter,
        fullname AS custom,
        drlname,
        scomp,
        ssolder AS ssold,
        blocks.sizex,
        blocks.sizey,
        numpl1,numpl2,numpl3,numpl4,numpl5,numpl6,
        blockname,
        blocks.id AS block_id,
        blocks.comment_id AS comment_id1,
        posintz.comment_id AS comment_id2,
        pitz_mater AS pmater,
        pitz_psimat AS ppsimat,
        blocks.thickness AS tolsh,
        posintz.numbers AS numbers,
        posintz.priem AS priem,
        tz.id as tzid,
        numbl,
        posintz.posintz AS posintz
        FROM posintz
        JOIN (customers,blocks,tz,orders)
        ON (blocks.id=posintz.block_id
            AND customers.id=orders.customer_id
            AND posintz.tz_id=tz.id
            AND tz.order_id=orders.id)
        WHERE posintz.id='{$posid}'";
        $rs = sql::fetchOne($sql);
        $rs = multibyte::UTF_encode($rs);
        $rec = array_merge($rec, $rs);
        extract($rs);
        // коментарий к блоку, содержится с остальными данными
        $param = json_decode(multibyte::Unescape(sqltable_model::getComment($comment_id1)),true);
        if(empty($param)) $param = array(); // если коментарий не JSON или неправильный JSON
        $rec[custom]=  html_entity_decode($rec[custom]); // кавычки в названии
        $rec[comment1] = multibyte::UTF_encode($param["coment"]);
        // комментарий к запуску
        $rec[comment2] = multibyte::UTF_encode(sqltable_model::getComment($comment_id2));
        $rec[comment2] = $rec[comment2] == 0?"":$rec[comment2] ;
// собрать данные о платах в блоке
        $sql = "SELECT *, board_name AS boardname, sizex AS psizex, sizey AS psizey
                FROM blockpos
                JOIN (boards)
                ON (boards.id=blockpos.board_id)
                WHERE blockpos.block_id='{$block_id}'
                ORDER BY blockpos.id"; // для правильного количества в запуске
        $res = sql::fetchAll($sql);
        $i = 0; // счетчик
        $platonblock = $numlam = $rmark = $immer = 0;
        $commentp = $mask = $layers = $class = $mark = '';
        foreach ($res as $rs) {
            $platonblock = max($platonblock, $rs[nib]);
            $numlam+=$rs[numlam];
            $rmark = max($rmark, $rs[rmark]);
            $immer = max($immer, $rs[immer]);
            $class = max($class, $rs['class']);
            $layers = max($layers, $rs['layers']);
            $i++;
            $sql = "SELECT comment FROM coments WHERE id='{$rs[comment_id]}'";
            $com = sql::fetchOne($sql);
            $commentp .= empty($com["comment"]) ? '' : multibyte::UTF_encode($com["comment"]);
            foreach ($rs as $key => $val) {
                $rec[$key . $i] = multibyte::UTF_encode($val);
            }
            $mask = $rec["mask{$i}"];
            $mark = $rec["mark{$i}"];
        }
        $rec = array_merge($rec, compact('platonblock', 'numlam', 'rmark', 'immer', 'mask', 'layers', 'class', 'mark', 'commentp'));
        // сделать собственно сопроводительный
        $rec[zagotinparty] = $zagotinparty = 25;
        if ($dozap===true) {
            //
            $zagotovokvsego = ceil($dozapnumbers / $platonblock);
            $zag = $zagotovokvsego;
            $ppart1 = $dozapnumbers;
            $ppart1 = $ppart = $zag * $platonblock;
            $numpl1 = $numbers = $dozapnumbers;
            $part = -2;//$party;
        } elseif ($dozap=="zadel") {
            $zagotovokvsego = $numbl != 0 ? $numbl : ceil($numbers / $platonblock); // общее количество заготовок
            $zag = ceil($dozapnumbers["use"] / $platonblock);
            $ppart1 = $ppart = $dozapnumbers["use"];
            $numpl1 = $numbers = $dozapnumbers["use"];
            $part = -1;
        } else {
            $zagotovokvsego = $numbl != 0 ? $numbl : ceil($numbers / $platonblock); // общее количество заготовок
            $zag = ($party * $zagotinparty >= $zagotovokvsego) ? ($zagotovokvsego - ($party - 1) * $zagotinparty) : $zagotinparty; //заготовок в партии
            /* плат в партии */
            $ppart = $zag * $platonblock;
            /* партия */
            $part = (ceil($zagotovokvsego / $zagotinparty) > 1) ?
                    $party . "(" . ceil($zagotovokvsego / $zagotinparty) . ")" : $party;
        }
        $rec[last] = ceil($zagotovokvsego / $zagotinparty) <= $party;
        // реорганизуем для запонения сл одной строчкой
        $rec[type] = multibyte::UTF_encode($layers == '1' ? 'ОПП' : 'ДПП');
        $rec[number] = sprintf("%08d", $lanch_id);
        $rec[zagotovokvsego] = $rec[zzak] = $zagotovokvsego;
        $rec[zag] = $rec[zppart] = $zag;
        $rec[fm1] = (strstr($rec[mark], '1') || strstr($rec[mark], '2') ? "+" : "-");
        $rec[fm2] = (strstr($rec[mark], '2') ? "+" : "-");
        $rec[rmark] = ($rec[rmark] == '1' ? "+" : "-");
        $rec[sizex] = ceil($rec[sizex]);
        $rec[sizey] = ceil($rec[sizey]);
        for ($i = 1; $i <= 6; $i++) {
            $rec["psizex{$i}"] = $rec["psizex{$i}"] == 0 ? "" : $rec["psizex{$i}"] . 'x' . $rec["psizey{$i}"];
            $rec["pio{$i}"] = $rec["numpl{$i}"] == 0 ? "" : $rec["numpl{$i}"];
            $rec["ppart{$i}"] = $rec["nib{$i}"] * $rec[zag] == 0 ? "" : $rec["nib{$i}"] * $rec[zag];
            $rec["niz{$i}"] = $rec["nib{$i}"];
            $rec["boardname{$i}"] = $rec["boardname{$i}"];
        }
        $rec[pio1] = $numpl1 == 0 ? $numbers : $numpl1; // позже возможно можно будет удалить если numpl будет из ТЗ заполнятся
        $rec[datez] = $rec[date];
        if ($rec[tolsh]>0) {
            $tolsh = preg_split('/[\.±]/', $rec[tolsh]);
            $tolsh[0] = empty($tolsh[0]) ? 0 : $tolsh[0];
            $tolsh[1] = empty($tolsh[1]) ? 0 : $tolsh[1];
            $tolsh = '-' . sprintf("%-d.%-d", $tolsh[0], $tolsh[1]);
        } else {
            $tolsh = '';
            preg_match('/-(\d+\.\d+)/', $rec[mater], $matches);
            $rec[tolsh] = $matches[1];
        }
        // коментарии о упаковке при свелении и фрезеровании
        $pack = $rec[tolsh]<0.5?4:($rec[tolsh]<1?3:($rec[tolsh]<1.6?2:1));
        $shpin = ceil($zagotovokvsego/$pack);
        $shpin = $shpin>3?3:$shpin;
        $drillcomment = "По $pack в пакете на $shpin шпинделях";
        $shpin = $zagotovokvsego>4?2:1;
        $millcomment = "По $pack в пакете на $shpin шпинделях";

        $rec[mater] = ($rec[pmater] == '' ? $rec[mater] : $rec[pmater]) . $tolsh;
        $rec[tolsh] = $tolsh;
        $rec[smask] = strstr($rec[mask], multibyte::UTF_encode('КМ')) ? "+" : "-";
        $rec[zmask] = strstr($rec[mask], multibyte::UTF_encode('ЖМ')) ? "+" : "-";
        $rec[aurum] = ($rec[immer] == '1' ? "+" : "-");
        $rec[priemz] = strstr($priem, multibyte::UTF_encode('ПЗ')) ? "+" : "-";
        $rec[priemotk] = '+'; // всегда
        $rec[scomp] = sprintf("%3.2f", $rec[scomp] / 10000);
        $rec[ssold] = sprintf("%3.2f", $rec[ssold] / 10000);
        $rec[lamel] = $rec[numlam] > 0 ? "+" : "-";
        $rec[psimat] = (empty($rec[ppsimat]) ? (empty($rec[psimat]) ? "" : $rec[psimat] . '-' . trim(sprintf("%5.1f", $rec[tolsh]))) :
                        ($rec[ppsimat] . $tolsh)
                ) . $rec[commentp];
        // проокоментируем сопроаводительный лист
        $rec[dozapcoment] = $dozap===true ? multibyte::UTF_encode('ДОЗАПУСК') :
                        ($dozap=="zadel"?multibyte::UTF_encode('ИЗ ЗАДЕЛА'):'');

        $rec = array_merge($rec, compact('ppart', 'part','millcomment','drillcomment','ek'));
        // для xls
        $rec[phm1] = $rec[fm1];
        $rec[phm2] = $rec[fm2];
        $rec[sizez] = "{$rec[sizex]}x{$rec[sizey]}";
        $rec[frzname] = $rec[drlname];
        $rec[comment] = $rec[psimat]." ".$rec[comment1]." ".$rec[dozapcoment];
        $rec[letterldate] = "{$rec[letter]} от {$rec[ldate]}";
        return $rec;
    }

    public function getSlMpp($rec) {
        extract($rec);
        // получить данные в переменные
        $sql = "SELECT
                    blockname AS board_name,
                    CONCAT(orders.number,CONCAT(' от ',CONCAT(DATE_FORMAT(orderdate,'%d.%m.%Y'),CONCAT(' - ',CONCAT(posintz.numbers,' шт.'))))) AS letter,
                    fullname AS customerfullname,
                    posintz.numbers AS numbers,
                    drlname AS frezfile,
                    scomp AS copperc,
                    ssolder AS coppers,
                    CONCAT(CEIL(blocks.sizex),CONCAT('x',CEIL(blocks.sizey))) AS sizez,
                    priem,
                    tz.id as tzid,
                    blocks.id AS block_id,
                    customers.id AS customer_id,
                    blocks.comment_id AS comment_id1,
                    posintz.comment_id AS comment_id2,
                    posintz.posintz AS posintz

                    FROM posintz
                    JOIN (customers,blocks,tz,
                            orders)
                    ON (blocks.id=posintz.block_id
                        AND customers.id=orders.customer_id
                        AND posintz.tz_id=tz.id
                        AND tz.order_id=orders.id
                        )
                    WHERE posintz.id='{$posid}'";
        $rs = sql::fetchOne($sql);
        $rs = multibyte::UTF_encode($rs);
        $rec = array_merge($rec, $rs);
        extract($rs);
        $param = json_decode(multibyte::Unescape(sqltable_model::getComment($comment_id1)),true);
        if(empty($param)) $param = array(); // если коментарий не JSON или неправильный JSON
        $rec[customerfullname]=  html_entity_decode($rec[customerfullname]); // кавычки в названии
        $rec = array_merge($rec, $param);
        
        $rec[mkrfile]="{$rec[frezfile]}.{$param[filext]}";
        
        $rec[comment1] = multibyte::UTF_encode($param["coment"]);
        $rec[comment2] = sqltable_model::getComment($comment_id2);

        $sql = "SELECT *, board_name AS boardname, sizex AS psizex,
                        sizey AS psizey
                FROM blockpos
                JOIN (boards)
                ON (boards.id=blockpos.board_id)
                WHERE blockpos.block_id='{$block_id}'";
        $res = sql::fetchOne($sql);
        extract($res);
        $platonblock = $res[nib];
        $tolsh = $res[thickness];
        $rec[sizep] = sprintf("%5.1fx%5.1f",$res[sizex],$res[sizey]);
        
        $mask = $res[mask];
        $mark = $res[mark];
        

        $rec = array_merge($rec, compact('platonblock', 'numlam', 'rmark', 'immer', 'mask', 'layers', 'class', 'mark', 'commentp'));
        
        if ($customer_id == '8') // радар
            $zagotinparty = 1;
        else
            $zagotinparty = 5;
        $rec[zagotinparty] = $zagotinparty;

        if ($dozap===true) {
            $zagotovokvsego = ceil($dozapnumbers / $platonblock);
            $zag = $zagotovokvsego;
            $ppart = $dozapnumbers;
            $numpl1 = $numbers = $dozapnumbers;
            $part = -2;//$party;
        } elseif ($dozap=="zadel") {
            $part = -1;
            $zagotovokvsego = ceil($dozapnumbers["use"] / $platonblock);
            $zag = $zagotovokvsego;
            $ppart = $dozapnumbers["use"];
            $numpl1 = $numbers = $dozapnumbers["use"];
        } else {
            // в дозапуске указано сколько запускать в реале
            $zagotovokvsego = ceil($dozap / $platonblock);
            // общее количество заготовок + 15% потом может быть
            $zag = ($party * $zagotinparty >= $zagotovokvsego) ? ($zagotovokvsego - ($party - 1) * $zagotinparty) : $zagotinparty;
            $ppart = (ceil($zagotovokvsego / $zagotinparty) > 1) ? (isset($last) ? ($numbers - (ceil($numbers / $platonblock / $zagotinparty) - 1) * $platonblock * $zagotinparty) . "($numbers)" : $zag * $platonblock . "($numbers)") : $numbers;
        }

        $rec[last] = ceil($zagotovokvsego / $zagotinparty) <= $party;
        // реорганизуем для запонения сл одной строчкой
        $rec[number] = sprintf("%08d", $lanch_id);
        
        $platonblock = $zagotovokvsego*$platonblock;
        $rec[numonzag] = "{$rec[platonblock]} ({$platonblock})";
        
        $rec[znumbers] = "{$zag} ({$zagotovokvsego})";
        
        $rec[phm1] = (strstr($rec[mark], '1') || strstr($rec[mark], '2') ? "+" : "-");
        $rec[phm2] = (strstr($rec[mark], '2') ? "+" : "-");
        $rec[rmark] = ($rec[rmark] == '1' ? "+" : "-");
        $rec[sizex] = ceil($rec[sizex]);
        $rec[sizey] = ceil($rec[sizey]);

        $rec[dataz] = date("d.m.Y");

        $rec[tolsh] = $tolsh;

        $rec[spm] = strstr($rec[mask], multibyte::UTF_encode('КМ')) ? "+" : "-";
        $rec[zhpm] = strstr($rec[mask], multibyte::UTF_encode('ЖМ')) ? "+" : "-";

        $rec[immer] = ($rec[immer] == '1' ? "+" : "-");
        $rec[priempz] = strstr($priem, multibyte::UTF_encode('ПЗ')) ? "+" : "-";
        $rec[priemotk] = '+'; // всегда
        $rec[copperc] = sprintf("%3.2f", $rec[copperc] / 10000);
        $rec[coppers] = sprintf("%3.2f", $rec[coppers] / 10000);
        $rec[lamel] = $rec[numlam] > 0 ? "+" : "-";

        // прокоментируем сопроводительный лист
        $rec[dozapcoment] = $rec[dozap]===true ? multibyte::UTF_encode('ДОЗАПУСК') :
                        ($rec[dozap]=="zadel"?multibyte::UTF_encode('ИЗ ЗАДЕЛА'):'');
        $rec[comment] = $rec[comment1]." ".$rec[comment2]." ".$rec[dozapcoment]; 


        $rec = array_merge($rec, compact('ppart', 'part', 'zagotovokvsego', 'zag'));
        return $rec;
    }

    public function lanchsl($rec) {
        extract($rec);
        if ($dozap === true) {
            $numbp = $ppart;
        } elseif ($dozap=="zadel") {
            $numbp = $dozapnumbers["use"];
        } else {
            $numbp = $zagotovokvsego <= $zagotinparty ? $numbers :
                    ($last ? ($numbers - ($party - 1) * $zagotinparty * $platonblock) : $zagotinparty * $platonblock);
        }
        $userid = Auth::getInstance()->getUser("userid");
        $sql = "UPDATE lanch
        SET ldate=NOW(), block_id='{$block_id}',
            numbz='{$zag}', numbp='{$numbp}',
            user_id='{$userid}', part='{$party}',
            tz_id='{$tzid}', pos_in_tz='{$posintz}'
        WHERE id='{$lanch_id}'";
        sql::query($sql);

        // если все запущены - исключить из запуска
        // если из задела тоже
        if ($dozap!==true) {
            // обновим таблицу запусков

            $sql = "DELETE FROM lanched WHERE block_id='{$block_id}'";
            sql::query($sql);
            $sql = "INSERT INTO lanched (block_id,lastdate) VALUES ('{$block_id}',NOW())";
            sql::query($sql);


            $sql = "SELECT SUM(numbz) AS snumbz FROM lanch WHERE pos_in_tz_id='{$posid}'
            GROUP BY pos_in_tz_id";
            $rs = sql::fetchOne($sql);
            if ($rs[snumbz] >= $zagotovokvsego) {
                $sql = "UPDATE posintz SET ldate=NOW(), luser_id='{$userid}'
         WHERE id='{$posid}'";
                sql::query($sql);
            }
        }
        return true;
    }

    /**
     * Использует задел, снимает с задела
     * @param int $rec массив из запроса getZadelByPosintzId
     * @return int колличество обработаных записей
     */
    public function usezadel($rec) {
        $res=0;
        $zds = explode(',',$rec["zds"]);
        foreach ($zds as $value) {
            list($id,$count) = explode('-',$value);
            if ($rec["use"]<$count) {
                $sql = "UPDATE zadel SET number=number-{$rec["use"]} WHERE id='{$id}'";
            } else {
                $sql = "DELETE FROM zadel WHERE id='{$id}'";
            }
            sql::query($sql);
            $res += sql::affected();
            $rec["use"] -= $count;
            if ($rec["use"] <= 0) break;
        }
        return $res;
    }

    /**
     * Возвращает по номеру позиции в ТЗ количество использованого задела
     * @param int $id
     * @return int
     */
    public function getZadelByPosintzId($id) {
        $rec=array();
        $sql = "SELECT
                    zadel.number AS zadel,
                    posintz.numbers AS zakaz,
                    zadel.id AS zadelid
                FROM posintz
                LEFT JOIN (zadel,blocks,blockpos,boards)
                ON
                    posintz.block_id=blocks.id
                    AND blockpos.block_id=blocks.id
                    AND boards.id=blockpos.board_id
                    AND zadel.board_id=boards.id
                WHERE posintz.id='{$id}'
                ORDER BY zadel ASC";
                // сорртирован по количеству чтоб удалять сначала те что меньше
        $res=sql::fetchAll($sql);
        foreach ($res as $zd) {
            $rec[zadel] += $zd["zadel"];
            $rec[zds] .= "{$zd[zadelid]}-{$zd["zadel"]},";
            $rec[zakaz] = $zd[zakaz];
        }
        $rec["use"] = min($rec[zadel],$rec[zakaz]);
        return $rec;
    }
}

?>
