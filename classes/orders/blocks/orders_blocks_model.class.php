<?php

/*
 * model class
 */

class orders_blocks_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        //console::getInstance()->out("all - {$all}, order - {$order}, find - {$find}, idstr - {$idstr}");
        $ret = array();
        if (empty($_SESSION[customer_id])) {
            $customer = "Выберите заказчика!!!";
            $sql = "SELECT *, CONCAT(blocks.sizex,'x',blocks.sizey) AS size, 
                    blocks.id AS blockid
                    FROM blocks
                    JOIN (customers,boards,blockpos)
                    ON (customers.id=blocks.customer_id AND blockpos.block_id=blocks.id AND blockpos.board_id=boards.id ) " .
                    (!empty($find) ? "WHERE blockname LIKE '%{$find}%' " : "") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY blockname  DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        } else {
            $cusid = $_SESSION[customer_id];
            $customer = $_SESSION[customer];
            // sql
            $sql = "SELECT *, CONCAT(blocks.sizex,'x',blocks.sizey) AS size, 
                    blocks.id AS blockid
                    FROM blocks
                    JOIN (customers,boards,blockpos) 
                    ON (customers.id=blocks.customer_id AND blockpos.block_id=blocks.id AND blockpos.board_id=boards.id ) " .
                    (!empty($find) ? "WHERE blockname LIKE '%{$find}%' AND customers.id='{$_SESSION[customer_id]}' " : " WHERE customers.id='{$_SESSION[customer_id]}' ") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY blockname DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        }
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        if (empty($_SESSION[customer_id])) {
            $cols[customer] = "Заказчик";
        }
        $cols[blockid] = "ID";
        $cols[blockname] = "Название блока";
        $cols[size] = "Размер";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        return $affected;
    }

    public function getRecord($edit) {
        if (empty($edit))
            return array();
        $sql = "SELECT * FROM blocks WHERE id='$edit'";
        $rec = sql::fetchOne($sql);
        return $rec;
    }

    public function setRecord($data) {
        extract($data);
        //console::getInstance()->out(print_r($data, true));return;

        return $ret;
    }

}

?>
