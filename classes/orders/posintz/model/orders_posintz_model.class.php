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
                            (!empty($find) ? "WHERE (blocks.blockname LIKE '%{$find}%') AND orders.customer_id='{$_SESSION[customer_id]}' " : " WHERE orders.customer_id='{$_SESSION[customer_id]}' ") .
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
                            (!empty($find) ? "WHERE (blocks.blockname LIKE '%{$find}%') AND orders.id='{$_SESSION[order_id]}' " : " WHERE orders.id='{$_SESSION[order_id]}' ") .
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
        $sql = "DELETE FROM posintz WHERE id='$delete'";
        $affected += sql::affected();
        return $affected;
    }
    public function getRecord($edit) {
        $rec = parent::getRecord($edit);
        $blockmodel = new orders_blocks_model();
        $rec = $blockmodel->getRecord($rec[block_id]);
        return $rec;
    }
    
}

?>
