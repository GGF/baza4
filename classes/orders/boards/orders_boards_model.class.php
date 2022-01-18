<?php

/*
 * model class
 */

class orders_boards_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'boards';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $order = strstr($order, 'files') ? '' : $order; // не удается отсортировать по файлам
        if (empty($_SESSION['customer_id'])) {
            $customer = 'Выберите заказчика!!!';
            $sql = "SELECT *, CONCAT(boards.sizex,'x',boards.sizey) AS size, 
                    boards.id AS boardid,boards.id
                    FROM boards
                    JOIN (customers)
                    ON (customers.id=boards.customer_id ) " .
                    (!empty($find) ? "WHERE board_name LIKE '%{$find}%' " : '') .
                    (!empty($order) ? "ORDER BY {$order} " : 'ORDER BY board_name  DESC ') .
                    ($all ? 'LIMIT 50' : 'LIMIT 20');
        } else {
            $cusid = $_SESSION['customer_id'];
            $customer = $_SESSION['customer'];
            // sql
            $sql = "SELECT *, CONCAT(boards.sizex,'x',boards.sizey) AS size, 
                    boards.id AS boardid,boards.id
                    FROM boards
                    JOIN (customers)
                    ON (customers.id=boards.customer_id ) " .
                    (!empty($find) ? "WHERE board_name LIKE '%{$find}%' AND customers.id='{$_SESSION[customer_id]}' " : " WHERE customers.id='{$_SESSION[customer_id]}' ") .
                    (!empty($order) ? "ORDER BY {$order} " : 'ORDER BY board_name DESC ') .
                    ($all ? 'LIMIT 50' : 'LIMIT 20');
        }
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $files = $this->getFilesForId('boards', $value['boardid']);
            $value['files'] = $files['link'];
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        if (empty($_SESSION['customer_id'])) {
            $cols['customer'] = 'Заказчик';
        }
        $cols['boardid'] = 'ID';
        $cols['board_name'] = 'Название платы';
        $cols['size'] = 'Размер';
        $cols['files'] = 'Файлы';
        return $cols;
    }

    public function delete($delete) {
        $sql = "DELETE FROM boards WHERE id='{$delete}'";
        sql::query($sql);
        return sql::affected();
    }

    public function getRecord($edit) {
        $rec = parent::getRecord($edit);
        $rec['customer'] = $this->getCustomer($rec['customer_id']);
        $rec['customer'] = $rec['customer']['customer'];
        $rec['comment'] = $this->getComment($rec['comment_id']);
        $rec['files'] = $this->getFilesForId('boards', $edit);
        return $rec;
    }

    public function setRecord($data) {
        $data['comment_id'] = $this->getCommentId($data['comment']);
        return parent::setRecord($data);
    }

}

?>
