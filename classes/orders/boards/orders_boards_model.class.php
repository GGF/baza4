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
        $order = strstr($order, 'files') ? '' : $order; // �� ������� ������������� �� ������
        if (empty($_SESSION[customer_id])) {
            $customer = "�������� ���������!!!";
            $sql = "SELECT *, CONCAT(boards.sizex,'x',boards.sizey) AS size, 
                    boards.id AS boardid,boards.id
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
                    boards.id AS boardid,boards.id
                    FROM boards
                    JOIN (customers)
                    ON (customers.id=boards.customer_id ) " .
                    (!empty($find) ? "WHERE board_name LIKE '%{$find}%' AND customers.id='{$_SESSION[customer_id]}' " : " WHERE customers.id='{$_SESSION[customer_id]}' ") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY board_name DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        }
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $files = $this->getFilesForId('boards', $value[boardid]);
            $value[files] = $files[link];
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        if (empty($_SESSION[customer_id])) {
            $cols[customer] = "��������";
        }
        $cols[boardid] = "ID";
        $cols[board_name] = "�������� �����";
        $cols[size] = "������";
        $cols[files] = "�����";
        return $cols;
    }

    public function getRecord($edit) {
        $rec = parent::getRecord($edit);
        $rec[customer] = $this->getCustomer($rec[customer_id]);
        $rec[customer] = $rec[customer][customer];
        $rec[comment] = $this->getComment($rec[comment_id]);
        return $rec;
    }
    
    public function setRecord($data) {
        extract($data);
        $comment_id = $this->getCommentId($comment);
        $sql="UPDATE boards SET comment_id='{$comment_id}' WHERE id='{$edit}'";
        sql::query($sql);
        return parent::setRecord($data);
    }
}

?>
