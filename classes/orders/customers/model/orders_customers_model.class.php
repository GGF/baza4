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
        $cols[customer] = "��������";
        $cols[fullname] = "������ ��������";
        $cols[kdir] = "���������";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "DELETE FROM customers WHERE id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        // �������� ������
        // ������� � ����� ���������
        $sql = "SELECT * FROM plates WHERE customer_id='{$delete}'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $sql = "DELETE FROM plates WHERE id='{$rs["id"]}'";
            sql::query($sql);
            $affected += sql::affected();
            // ���� �� ������� � ����� �.�.
        }
        // ������� �������� ������ � ��
        $sql = "SELECT * FROM orders WHERE customer_id='{$delete}'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            // ��������
            $delete = $rs["id"];
            $sql = "DELETE FROM orders WHERE id='{$delete}'";
            sql::query($sql);
            $affected += sql::affected();
            // �������� ������
            $sql = "SELECT * FROM tz WHERE order_id='{$delete}'";
            $res1 = sql::fetchAll($sql);
            foreach ($res1 as $rs1) {
                // ��������
                $delete = $rs1["id"];
                $sql = "DELETE FROM tz WHERE id='{$delete}'";
                sql::query($sql);
                $affected += sql::affected();
                // �������� ������
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
            // ��������������
            $sql = "UPDATE customers SET customer='{$customer}', fullname='{$fullname}', kdir='{$kdir}'
                    WHERE id='{$edit}'";
        } else {
            // ����������
            $sql = "INSERT INTO customers (customer,fullname,kdir) VALUES ('{$customer}','{$fullname}','{$kdir}')";
        }
        sql::query($sql);

        return sql::affected();
    }

}

?>
