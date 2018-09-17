<?php

/*
 * model class
 */

class orders_order_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'orders';
    }

 
    public function getData($all=false, $sort_order='', $find='', $idstr='') {
        //$ret = parent::getData($all, $sort_order, $find, $idstr); // родительский несипользуется
        
        $sort_order = strstr($sort_order, 'files') ? '' : $sort_order; // не удается отсортировать по файлам
        extract($_SESSION[Auth::$lss]);
        if (empty($customer_id)) {
            $sql = "SELECT *, orders.id AS oid, IF(onlycalc,'&#8730;','') as onlycalc,
                        orders.id
                        FROM orders
                        JOIN customers
                        ON customers.id=customer_id " .
                    (!empty($find) ? "WHERE (number LIKE '%{$find}%' OR orderdate LIKE '%{$find}%' ) " : "") .
                    (!empty($sort_order) ? "ORDER BY {$sort_order} " : "ORDER BY orders.orderdate DESC ") .
                    ($all ? "LIMIT 150" : "LIMIT 20");
        } else {
            // sql
            $sql = "SELECT *, orders.id AS oid, IF(onlycalc,'&#8730;','') as onlycalc,
                        orders.id
                        FROM orders
                        JOIN customers ON customers.id=customer_id " .
                    (!empty($find) ? "WHERE (number LIKE '%{$find}%' OR orderdate LIKE '%{$find}%' ) AND customer_id='{$customer_id}' " : "WHERE customer_id='{$customer_id}' ") .
                    (!empty($sort_order) ? "ORDER BY {$sort_order} " : "ORDER BY orders.orderdate DESC ") .
                    ($all ? "LIMIT 150" : "LIMIT 20");
        }
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $files = $this->getFilesForId('orders', $value[id]);
            $value[files] = $files[link];
        }
        if ($all) {
            $_SESSION[Auth::$lss][order_id] = '';
            $_SESSION[Auth::$lss][tz_id]='';
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        extract($_SESSION[Auth::$lss]);
        if (empty($customer_id)) {
            $cols[customer] = "Заказчик";
        }
        $cols[oid] = "ID";
        $cols[orderdate] = "Дата заказа";
        $cols[number] = "Номер заказа";
        $cols[onlycalc] = "Только считать";
        $cols[files] = "Файлы";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "DELETE FROM orders WHERE id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        // удаление связей
        $sql = "DELETE FROM files WHERE `table`='orders' AND rec_id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "SELECT * FROM tz WHERE order_id='{$delete}'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            // удаление
            $delete = $rs["id"];
            $sql = "DELETE FROM tz WHERE id='{$delete}'";
            sql::query($sql);
            $affected += sql::affected();
            // удаление связей
            $sql = "SELECT * FROM posintz WHERE tz_id='{$delete}'";
            $res1 = sql::fetchAll($sql);
            foreach ($res1 as $rs1) {
                $delete = $rs1["id"];
                $sql = "DELETE FROM posintz WHERE id='{$delete}'";
                sql::query($sql);
                $affected += sql::affected();
            }
        }
        return $affected;
    }

    public function getRecord($edit) {
        if (empty($edit)) {
            $rec[customers] = $this->getCustomers();
            return $rec;
        }
        $sql = "SELECT * FROM orders WHERE id='{$edit}'";
        $rec = sql::fetchOne($sql);
        $rec[files] = $this->getFilesForId('orders', $edit);
        return $rec;
    }

      public function setRecord($data) {
          //console::getInstance()->out(var_dump($data, false));
          // onlycalc если не отмечен, то вообще сюда не пеедается
          $data["onlycalc"] = empty($data["onlycalc"])?0:1;
        return parent::setRecord($data);
    }

}

?>
