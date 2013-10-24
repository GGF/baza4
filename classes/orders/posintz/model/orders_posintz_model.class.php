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
        $rs = sql::fetchOne($sql);
        if(empty($rs)) {
            $res[affected] = false;
            return $res;
        }
        // определим позицию добавленного в тз
        $posintz = array(1,2,3);
        $sql = "SELECT posintz FROM posintz WHERE id='{$tz_id}'";
        $rsn = sql::fetchAll($sql);
        if(!empty($rsn)) {
            foreach ($rsn as $value) {
                // тут может быть 1 2 или 3
                $posintz = array_diff($posintz, array($value["posintz"]));
            }
            if (empty($posintz)) {
                $res[affected] = false;
                return $res;
            } else {
                $posintz = $posintz[0];
            }
        } else {
            $posintz = 1;
        }
        $res[affected] = true;
        return $res;
    }

}

?>
