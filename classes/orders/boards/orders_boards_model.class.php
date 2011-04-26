<?php

/*
 * model class
 */

class orders_boards_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        if (empty($_SESSION[customer_id])) {
            $customer = "Выберите заказчика!!!";
            $sql = "SELECT *, CONCAT(boards.sizex,'x',boards.sizey) AS size, 
                    boards.id AS boardid
                    FROM boards
                    JOIN (customers)
                    ON (customers.id=boards.customer_id ) " .
                    (!empty($find) ? "WHERE board_name LIKE '%{$find}%' " : "") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY board_name  DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        } else {
            $cusid = $_SESSION[customer_id];
            $customer = $_SESSION[customer];
            // sql
            $sql = "SELECT *, CONCAT(boards.sizex,'x',boards.sizey) AS size, 
                    boards.id AS boardid
                    FROM boards
                    JOIN (customers)
                    ON (customers.id=boards.customer_id ) " .
                    (!empty($find) ? "WHERE board_name LIKE '%{$find}%' AND customers.id='{$_SESSION[customer_id]}' " : " WHERE customers.id='{$_SESSION[customer_id]}' ") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY board_name DESC ") .
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
        $cols[boardid] = "ID";
        $cols[board_name] = "Название платы";
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

        return $ret;
    }

}

?>
