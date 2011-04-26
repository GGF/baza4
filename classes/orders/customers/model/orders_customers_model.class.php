<?php

/*
 * conduct model class
 */

class orders_customers_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT * FROM customers " .
                (!empty($find) ? "WHERE (customers.customer LIKE '%{$find}%'
                                OR customers.fullname LIKE '%{$find}%' ) " : "") .
                (!empty($order) ? "ORDER BY {$order} " : "ORDER BY customers.customer ") .
                ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        if($all) {
            $_SESSION[customer_id]='';
            $_SESSION[order_id]='';
            $_SESSION[tz_id]='';
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[id] = "ID";
        $cols[customer] = "Заказчик";
        $cols[fullname] = "Полное название";
        $cols[kdir] = "Сверловки";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "DELETE FROM customers WHERE id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        // удаление связей
        // удалить и платы заказчика
        $sql = "SELECT * FROM plates WHERE customer_id='{$delete}'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $sql = "DELETE FROM plates WHERE id='{$rs["id"]}'";
            sql::query($sql);
            $affected += sql::affected();
            // надо бы удалить и блоки т.п.
        }
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

    public function getRecord($id) {
        if (empty($id))
            return array();
        $sql = "SELECT * FROM customers WHERE id='{$id}'";
        $rec = sql::fetchOne($sql);
        return $rec;
    }

    public function  setRecord($data) {
        extract($data);
        if (!empty($edit)) {
            // редактирование
            $sql = "UPDATE customers SET customer='{$customer}', fullname='{$fullname}', kdir='{$kdir}'
                    WHERE id='{$edit}'";
        } else {
            // добавление
            $sql = "INSERT INTO customers (customer,fullname,kdir) VALUES ('{$customer}','{$fullname}','{$kdir}')";
        }
        sql::query($sql);

        return sql::affected();
    }

}

?>
