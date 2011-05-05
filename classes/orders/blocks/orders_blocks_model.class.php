<?php

/*
 * model class
 */

class orders_blocks_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'blocks';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $order = strstr($order, 'files') ? '' : $order; // не удается отсортировать по файлам
        if (empty($_SESSION[customer_id])) {
            $customer = "Выберите заказчика!!!";
            $sql = "SELECT *, CONCAT(blocks.sizex,'x',blocks.sizey) AS size, 
                    blocks.id AS blockid,blocks.id
                    FROM blocks
                    JOIN (customers,boards,blockpos)
                    ON (customers.id=blocks.customer_id AND blockpos.block_id=blocks.id AND blockpos.board_id=boards.id ) " .
                    (!empty($find) ? "WHERE blockname LIKE '%{$find}%' OR board_name LIKE '%{$find}%' " : "") .
                    " GROUP BY blockid " .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY blockname  DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        } else {
            $cusid = $_SESSION[customer_id];
            $customer = $_SESSION[customer];
            // sql
            $sql = "SELECT *, CONCAT(blocks.sizex,'x',blocks.sizey) AS size, 
                    blocks.id AS blockid,blocks.id
                    FROM blocks
                    JOIN (customers,boards,blockpos) 
                    ON (customers.id=blocks.customer_id AND blockpos.block_id=blocks.id AND blockpos.board_id=boards.id ) " .
                    (!empty($find) ? "WHERE (blockname LIKE '%{$find}%'  OR board_name LIKE '%{$find}%') AND customers.id='{$_SESSION[customer_id]}' " : " WHERE customers.id='{$_SESSION[customer_id]}' ") .
                    " GROUP BY blockid " .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY blockname DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        }
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $files = $this->getFilesForId($this->maintable, $value[blockid]);
            $value[files] = $files[link];
        }
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
        $cols[scomp] = 'COMP';
        $cols[ssolder] = 'SOLDER';
        $cols[files] = 'Файлы';
        return $cols;
    }

    public function delete($delete) {
        $sql = "DELETE FROM blocks WHERE id='{$delete}'";
        sql::query($sql);
        $sql = "DELETE FROM blockpos WHERE block_id='{$delete}'";
        sql::query($sql);
        return sql::affected();
    }

    public function getRecord($edit) {
        $rec = parent::getRecord($edit);
        $rec[customer] = $this->getCustomer($rec[customer_id]);
        $rec[customer] = $rec[customer][customer];
        $sql = "SELECT * 
                FROM blockpos 
                JOIN boards ON boards.id=blockpos.board_id 
                WHERE blockpos.block_id='{$edit}'";
        $rec[blockpos] = sql::fetchAll($sql);
        $rec[comment] = $this->getComment($rec[comment_id]);
        return $rec;
    }

    public function setRecord($data) {
        extract($data);
        $comment_id = $this->getCommentId($comment);
        $sql = "UPDATE blocks SET comment_id='{$comment_id}' WHERE id='{$edit}'";
        sql::query($sql);
        return parent::setRecord($data);
    }

}

?>
