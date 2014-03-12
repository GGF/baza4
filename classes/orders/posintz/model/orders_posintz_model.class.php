<?php

/*
 * model class
 */

class orders_posintz_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'posintz';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = parent::getData($all, $order, $find, $idstr);
//        list($customer_id,$order_id,$tz_id,$posintzid) = explode(':',$idstr);
        extract($_SESSION[Auth::$lss]);
        if (!empty($tz_id)) {
            $tzid = $tz_id;
            $sql = "SELECT *,posintz.id as posid,posintz.id
                    FROM `posintz`
                    JOIN (blocks)
                    ON ( posintz.block_id = blocks.id ) " .
                    (!empty($find) ? "WHERE (blocks.blockname LIKE '%{$find}%') AND tz_id='{$tzid}' " : " WHERE tz_id='{$tzid}' ") .
                    (!empty($order) ? " ORDER BY {$order} " : " ORDER BY posintz.id DESC ") .
                    ($all ? "" : "LIMIT 20");
        } else {
            if (!empty($customer_id)) {
                if (empty($order_id)) {

                    $sql = "SELECT *,tz.id AS tzid,posintz.id as posid,posintz.id
                    FROM `posintz`
                    JOIN (blocks,customers,orders,tz)
                    ON ( posintz.block_id = blocks.id
                         AND posintz.tz_id = tz.id
                         AND orders.id = tz.order_id
                         AND customers.id = orders.customer_id
                        ) " .
                            (!empty($find) ? "WHERE (blocks.blockname LIKE '%{$find}%') AND orders.customer_id='{$customer_id}' " : " WHERE orders.customer_id='{$customer_id}' ") .
                            (!empty($order) ? " ORDER BY {$order} " : " ORDER BY posintz.id DESC ") .
                            ($all ? "" : "LIMIT 20");
                } else {
                    $sql = "SELECT *,tz.id AS tzid,posintz.id as posid,posintz.id
                    FROM `posintz`
                    JOIN (blocks,customers,orders,tz)
                    ON ( posintz.block_id = blocks.id
                         AND posintz.tz_id = tz.id
                         AND orders.id = tz.order_id
                         AND customers.id = orders.customer_id
                        ) " .
                            (!empty($find) ? "WHERE (blocks.blockname LIKE '%{$find}%') AND orders.id='{$order_id}' " : " WHERE orders.id='{$order_id}' ") .
                            (!empty($order) ? " ORDER BY {$order} " : " ORDER BY posintz.id DESC ") .
                            ($all ? "" : "LIMIT 20");
                }
            } else {
                $sql = "SELECT *,tz.id AS tzid,posintz.id as posid,posintz.id
                    FROM `posintz`
                    JOIN (blocks,customers,orders,tz)
                    ON ( posintz.block_id = blocks.id
                         AND posintz.tz_id = tz.id
                         AND orders.id = tz.order_id
                         AND customers.id = orders.customer_id
                        ) " .
                        (!empty($find) ? "WHERE (blocks.blockname LIKE '%{$find}%') " : " ") .
                        (!empty($order) ? " ORDER BY {$order} " : " ORDER BY posintz.id DESC ") .
                        ($all ? "" : "LIMIT 20");
            }
        }
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
//        list($customer_id,$order_id,$tz_id,$posintzid) = explode(':',$this->idstr);
        extract($_SESSION[Auth::$lss]);
        $cols["№"] = "№";
        if (empty($customer_id)) {
            $cols[customer] = "Заказчик";
        }
        if (empty($order_id)) {
            $cols[number] = "Заказ";
        }
        if (empty($tz_id)) {
            $cols[tzid] = "ТЗ";
        }
        $cols[posid] = "ID";
        $cols[blockname] = "Плата";
        $cols[numbers] = "Количество";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "DELETE FROM posintz WHERE id='{$delete}'";
	sql::query($sql);
        $affected += sql::affected();
        return $affected;
    }
   
    public function getRecord($id) {
        extract($_SESSION[Auth::$lss]); // тут данные выбранных до сих пор заказа и тз
        $rec[blocks] = $this->getBlocks($customer_id);
        $rec[tz_id] = $tz_id;
        return $rec;
    }
    
    public function setRecord($rec) {
        extract($rec);
        /*
        Поля в ТЗ для дпп

        blockname               blocks
        numbers                 posintz
        first                   posintz
        srok                    posintz
        type                    boards
        class                   boards
        priem                   posintz
        complexity_factor       boards
        boardsizex              boards
        boardsizey              boards
        blocksizex              blocks
        blocksizey              blocks
        numonblock              blocks
        numblock                valuetion
        constr                  posintz
        template_check          posintz
        template_make           posintz
        drills(smalldrill/bigdrill)     blocks
        textolite               boards
        thickness               boards

        mask                    boards
        mark                    boards
        rmark                   boards
        razr                    boards
        frezcorner              boards
        frez_factor

        lamel                   boards
        numlam                  boards
        lsizex                  boards
        lsizey                  boards
        immer                   boards
        auarea                  blocks
        boardcomment            blocks
        posintcomment           posintz
        */
        $rec[first] = '0'; // если вставляем старую плату, а сейчас мы так и делаем
        $sql="SELECT * FROM posintz WHERE block_id='{$block_id}' ORDER BY id DESC LIMIT 1";
        $pos = sql::fetchOne($sql);
        if(empty($pos)) {
            $res[alert] = "Не запускалось такого Новая наверное. Ручками давай.";
            $res[affected] = false;
            return $res;
        }
        // определим позицию добавленного в тз
        $posintz = array(1,2,3);
        $sql = "SELECT posintz FROM posintz WHERE tz_id='{$tz_id}'";
        $rsn = sql::fetchAll($sql);
        if(!empty($rsn)) {
            foreach ($rsn as $value) {
                // тут может быть 1 2 или 3
                $posintz = array_diff($posintz, array($value["posintz"]));
            }
            if (empty($posintz)) {
                $res[alert] = "Не лезет. Уже три позиции запихал";
                $res[affected] = false;
                return $res;
            } else {
                $posintz = reset($posintz);
            }
        } else {
            $posintz = 1;
        }
        // получить данные блока
        $block = new orders_blocks_model();
        $block = $block->getRecord($block_id);
        // получить данны плат в блоке
        $board = $block[blockpos][0];
        // добавить позицию в таблицу posintz
        $newpos[tz_id] = $tz_id;
        $newpos[posintz] = $posintz;
        $newpos[block_id] = $block_id;
        $newpos[numpl1] = $newpos[numbers] = $board_num;
        $newpos[numbl] = $newpos[numblock] = ceil($board_num/$board[nib]);
        $newpos[first] = 0; // так как мы добавляем из старой
        $newpos[srok] = $pos[srok];
        $newpos[priem] = $pos[priem];
        $newpos[constr] = 1;//$pos[constr]; //один час при повторе
        $newpos[template_check] = max(array($pos[template_check],$pos[template_make]));
        $newpos[template_make] = 0;
        $newpos[comment_id] = $pos[comment_id];
        
        sql::insertUpdate($this->maintable,array($newpos));
        // дозаполнить newpos
        $newpos[blockname] = $block[blockname];
        $newpos[type] = $board[layers]==1?"ОПП":"ДПП"; // пока будет так
        $newpos[layers] = $board[layers];
        $newpos["class"] = $board["class"];
        $newpos[complexity_factor] = $board[complexity_factor]>0?$board[complexity_factor]:"";
        $newpos[boardsizex] = $board[sizex];
        $newpos[boardsizey] = $board[sizey];
        $newpos[blocksizex] = ceil($block[sizex]);
        $newpos[blocksizey] = ceil($block[sizey]);
        $newpos[numonblock] = $board[nib];
        $newpos[drills] = "{$block[smalldrill]}/{$block[bigdrill]}";
        $newpos[textolite] = $board[textolite];
        $newpos[glasscloth] = $board[glasscloth];
        $newpos[thickness] = $board[thickness];
        preg_match('/(?P<nummask>[+0-9]*)(?P<mask>.*)/i', $board[mask], $matches);
        $newpos[mask]=$matches[mask];
        $newpos[nummask]=empty($matches[nummask])?2:$matches[nummask];
        $newpos[mark] = $board[mark];
        $newpos[rmark] = $board[rmark];
        $newpos[razr] = $board[razr];
        $newpos[frezcorner] = $board[frezcorner];
        $newpos[frez_factor] = $board[frez_factor]>0?$board[frez_factor]:"";
        $newpos[lamel] = $board[numlam]>0?"{$board[numlam]} {$board[lsizex]}x{$board[lsizey]}":"0 0.0x0.0";
        $newpos[numlam] = $board[numlam];
        $newpos[lsizex] = $board[lsizex];
        $newpos[lsizey] = $board[lsizex];
        $newpos[immer] = $board[immer];
        $newpos[auarea] = $board[immer]==1?$block[auarea]:"";
        // блоки с JSON
        $params = json_decode(multibyte::Unescape(sqltable_model::getComment($block["comment_id"])),true); //получим текщий комент из блока
        $newpos[boardcomment] = $params[coment];
        $newpos[eltest] = $params[eltest];
        $newpos[etpib] = $params[etpib];
        $newpos[etpoints] = $params[etpoints];
        $newpos[etcompl] = $params[etcompl];
        $newpos[posintcomment] = $this->getComment($pos[comment_id]);
        // сохранить данные в файл
        $tz = new orders_tz_model();
        $tz = $tz->getRecord($tz_id);
        $filelink = $tz[tzlink];
        $filename = fileserver::createdironserver($filelink);
        fileserver::savefile("{$filename}.{$posintz}.txt", $newpos);
        // вывести ссылочку или не надо...
        $res[affected] = true;
        return $res;
    }

    public function getDataForCalc($id) {
        $rec = array();
        $sql = "SELECT *,CONCAT (number,' от ',DATE_FORMAT(orderdate,'%d.%m.%Y')) as letter, "
                . "boards.sizex/100.0 as psizex, "
                . "boards.sizey/100.0 as psizey, "
                . "blocks.sizex/100.0 as bsizex, "
                . "blocks.sizey/100.0 as bsizey, "
                . "immer*auarea as gold, "
                . "CONCAT(numlam,' ',lsizex,'x',lsizey) as lamel, "
                . "blocks.comment_id as bcid "
                . "FROM posintz JOIN (tz,orders,customers,blocks,blockpos,boards) "
                . "ON (posintz.tz_id=tz.id "
                . "AND tz.order_id=orders.id "
                . "AND posintz.block_id=blocks.id "
                . "AND orders.customer_id=customers.id "
                . "AND blockpos.block_id=blocks.id "
                . "AND blockpos.board_id=boards.id )  "
                . "WHERE posintz.id='{$id}'";
        $rec = sql::fetchOne($sql);
        $params = json_decode(multibyte::Unescape(sqltable_model::getComment($rec["bcid"])),true); //получим текщий комент из блока
        $rec[eltest] = $params[eltest];
        $rec[etpib] = $params[etpib];
        $rec[etpoints] = $params[etpoints];
        $rec[etcompl] = $params[etcompl];
        $rec[thickness] = (float)$rec[thickness];
        $rec[type] = $rec[layers]>2 ? 'mpp' : 'dpp';
        $rec[template] = "r{$rec[type]}.xls";
        preg_match('/(?P<nummask>[+0-9]*)(?P<mask>.*)/i', $rec[mask], $matches);
        $rec[mask]=$matches[mask];
        $rec[nummask]=empty($matches[nummask])?2:$matches[nummask];
        $orderstring = fileserver::removeOSsimbols($rec[letter]." tz{$rec[tz_id]}");
        $rec[filename] = "t:\\\\Расчет стоимости плат\\\\{$rec[customer]}\\\\{$rec[blockname]}\\\\{$orderstring}.xls";
        return $rec;
    }
    
    public function saveFileLinkForRaschet($rec) {
        // сохранить ссылку на расчет в posint
        extract($rec);
        $filelink = $this->getFileId($filename);
        $this->storeFilesInTable(array($filelink=>$filename), 'posintz', $id);
        return;
    }
    
    public function getFileLinkForRaschet($rec) {
        extract($rec);
        $res = $this->getFilesForId('posintz', $id);
        if (empty($res[link])) {
            return false;
        } else {
            return $res[file][0][file_link];
        }
    }
}

?>
