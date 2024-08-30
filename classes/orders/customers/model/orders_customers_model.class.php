<?php

/*
 * conduct model class
 */

class orders_customers_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'customers';
    }
    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT * FROM customers " .
                (!empty($find) ? "WHERE (customers.customer LIKE '%{$find}%'
                                OR customers.fullname LIKE '%{$find}%' ) " : "") .
                (!empty($order) ? "ORDER BY {$order} " : "ORDER BY customers.customer ") .
                ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $files = $this->getFilesForId($this->maintable, $value['id']);
            $value['files'] = $files['link'];
        }
        if($all) {
            $_SESSION[Auth::$lss]['customer_id']='';
            $_SESSION[Auth::$lss]['order_id']='';
            $_SESSION[Auth::$lss]['tz_id']='';
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols['id'] = "ID";
        $cols['customer'] = "Заказчик";
        $cols['fullname'] = "Полное название";
        $cols['kdir'] = "Сверловки";
        $cols['files'] = "Файлы";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "DELETE FROM customers WHERE id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        // удаление связей
        // TODO: Удаллить блоки и позиции в блоках, а также платы
        // удалить вязанные заказы и тз
        $sql = "SELECT * FROM orders WHERE customer_id='{$delete}'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            // удаление
            $delete = $rs["id"];
            $sql = "DELETE FROM orders WHERE id='{$delete}'";
            sql::query($sql);
            $affected += sql::affected();
            // удаление связей
            $sql = "SELECT * FROM tz WHERE order_id='{$delete}'";
            $res1 = sql::fetchAll($sql);
            foreach ($res1 as $rs1) {
                // удаление
                $delete = $rs1["id"];
                $sql = "DELETE FROM tz WHERE id='{$delete}'";
                sql::query($sql);
                $affected += sql::affected();
                // удаление связей
                $sql = "SELECT * FROM posintz WHERE tz_id='{$delete}'";
                $res2 = sql::fetchAll($sql);
                foreach ($res2 as $rs2)
                    $delete = $rs2["id"];
                $sql = "DELETE FROM posintz WHERE id='{$delete}'";
                sql::query($sql);
                $affected += sql::affected();
            }
        }
        return $affected;
    }


}

?>
