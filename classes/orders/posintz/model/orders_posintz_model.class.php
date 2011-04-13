<?php

/*
 * model class
 */

class orders_posintz_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        //console::getInstance()->out("all - {$all}, order - {$order}, find - {$find}, idstr - {$idstr}");
        $ret = array();
        if (!empty($_SESSION[tz_id])) {
            $tzid = $_SESSION[tz_id];
            $sql="SELECT *,posintz.id as posid,posintz.id
                    FROM `posintz`
                    JOIN (blocks)
                    ON ( posintz.block_id = blocks.id ) " .
                    (!empty($find)?"WHERE (blocks.blockname LIKE '%{$find}%') AND tz_id='{$tzid}' ":" WHERE tz_id='{$tzid}' ") .
                    (!empty($order) ? " ORDER BY {$order} " : " ORDER BY posintz.id DESC ") .
                    ($all ? "" : "LIMIT 20");
        } else {
            return $ret;
        }
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        if (empty($_SESSION[customer_id])) {
            $cols[customer] = "Заказчик";
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

}

?>
