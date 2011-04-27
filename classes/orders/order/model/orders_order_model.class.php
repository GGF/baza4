<?php

/*
 * model class
 */

class orders_order_model extends sqltable_model {
    
    public function __construct() {
        parent::__construct();
        $this->maintable = 'orders';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $order = strstr($order, 'files') ? '' : $order; // не удается отсортировать по файлам
        if (empty($_SESSION[customer_id])) {
            $customer = "Выберите заказчика!!!";
            $sql = "SELECT *,
                        orders.id
                        FROM orders
                        JOIN customers
                        ON customers.id=customer_id " .
                    (!empty($find) ? "WHERE (number LIKE '%{$find}%' OR orderdate LIKE '%{$find}%' ) " : "") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY orders.orderdate DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        } else {
            $cusid = $_SESSION[customer_id];
            $customer = $_SESSION[customer];
            // sql
            $sql = "SELECT *, orders.id
                            FROM orders
                            JOIN customers ON customers.id=customer_id " .
                    (!empty($find) ? "WHERE (number LIKE '%{$find}%' OR orderdate LIKE '%{$find}%' ) AND customer_id='{$cusid}' " : "WHERE customer_id='{$cusid}' ") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY orders.orderdate DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        }
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $files = $this->getFilesForId('orders', $value[id]);
            $value[files] = $files[link];
        }
        if ($all) {
            $_SESSION[order_id] = '';
            $_SESSION[tz_id] = '';
            // todo: может вынести обнуление сессионных в orders? то есть на уровень выше
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        if (empty($_SESSION[customer_id])) {
            $cols[customer] = "Заказчик";
        }
        $cols[id] = "ID";
        $cols[number] = "Номер заказа";
        $cols[orderdate] = "Дата заказа";
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
        if (empty($edit))
            return array();
        $sql = "SELECT * FROM orders WHERE id='$edit'";
        $rec = sql::fetchOne($sql);
        $rec[files] = $this->getFilesForId('orders', $edit);
        return $rec;
    }

    public function setRecord($data) {
        extract($data);
        $orderdate = sql::datepicker2date($orderdate);

        if ($edit) {
            // редактирование
            $sql = "UPDATE orders
                    SET customer_id='{$customerid}',
                    orderdate='{$orderdate}',
                    number='{$number}'
                    WHERE id='{$edit}'";
            sql::query($sql);
        } else {
            // добавление
            $sql = "INSERT INTO orders
                    (customer_id,orderdate,number)
                    VALUES ('{$customerid}','{$orderdate}','{$number}')";
            sql::query($sql);
            $edit = sql::lastId();
        }
        $ret[affected] = true;

        $curfile = !empty($curfile)?array_merge($curfile,$this->storeFiles($files, $this->maintable)):$this->storeFiles($files, $this->maintable);
        $this->storeFilesInTable($curfile, $this->maintable, $edit);
 

        return $ret;
    }

}

?>
