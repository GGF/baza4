<?php

class sqltable_model {

    public function __construct() {
    }
    public function init() {
    }
    public function getData($all=false, $order='', $find='', $idstr='') {
        return array();
    }
    public function getCols() {
        return array();
    }
    public function delete($delete) {
        return true;
    }
    public function getRecord($edit) {
        if (is_numeric($edit)) return false;
        $ret = sql::fetchOne($edit);
        return $ret;
    }
    public function setRecord($data) {
        extract($data);
        console::getInstance()->out(print_r($data, true));
        return true;
    }

    /*
     * Возвращает список заказчиков для форм
     */

    public function getCustomers($type='array') {
        $sql = "SELECT id,customer FROM customers ORDER BY customer";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $customers[$rs[id]] = $rs[customer];
        }
        return $customers;
    }

    /*
     * Возвращает блоки заказчика для форм
     */

    public function getBlocks($customerid='', $type='array') {
        if (empty($customerid))
            return '';
        $sql = "SELECT id,blockname,customer_id FROM blocks " .
                (empty($customerid) ? "" : "WHERE customer_id='{$customerid}' ") .
                "ORDER BY blockname";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $blocks[$rs[id]] = $rs[plate];
        }
        return $blocks;
    }

    /*
     * Возвращает платы заказчика для форм
     */

    public function getBoards($customerid='', $type='array') {
        if (empty($customerid))
            return '';
        $blocks = array();
        $sql = "SELECT id,board_name FROM boards " .
                (empty($customerid) ? "" : "WHERE customer_id='{$customerid}' ") .
                "ORDER BY board_name";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $blocks[$rs[id]] = $rs[board_name];
        }
        return $blocks;
    }

    public function getCustomer($id) {
        $sql = "SELECT * FROM customers WHERE id='{$id}'";
        return sql::fetchOne($sql);
    }

    public function getOrder($id) {
        $sql = "SELECT * FROM orders WHERE id='{$id}'";
        return sql::fetchOne($sql);
    }

    public function getTZ($id) {
        $sql = "SELECT * FROM tz WHERE id='{$id}'";
        return sql::fetchOne($sql);
    }

    public function getFileId($filename) {
        $sql = "SELECT id FROM filelinks WHERE file_link='{$filename}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            return $rs["id"];
        } else {
            $sql = "INSERT INTO filelinks (file_link) VALUES ('{$filename}')";
            sql::query($sql) or die(sql::error(true));
            return sql::lastId();
        }
    }

    public function getFileNameById($fileid) {
        $sql = "SELECT file_link FROM filelinks WHERE id='{$fileid}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            return $rs["file_link"];
        } else {
            return "None";
        }
    }

}

?>