<?php

/*
 * model class
 */

class orders_order_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $order = strstr($order, 'files') ? '' : $order; // �� ������� ������������� �� ������
        if (empty($_SESSION[customer_id])) {
            $customer = "�������� ���������!!!";
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
            // todo: ����� ������� ��������� ���������� � orders? �� ���� �� ������� ����
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        if (empty($_SESSION[customer_id])) {
            $cols[customer] = "��������";
        }
        $cols[id] = "ID";
        $cols[number] = "����� ������";
        $cols[orderdate] = "���� ������";
        $cols[files] = "�����";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "DELETE FROM orders WHERE id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        // �������� ������
        $sql = "DELETE FROM files WHERE `table`='orders' AND rec_id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "SELECT * FROM tz WHERE order_id='{$delete}'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            // ��������
            $delete = $rs["id"];
            $sql = "DELETE FROM tz WHERE id='{$delete}'";
            sql::query($sql);
            $affected += sql::affected();
            // �������� ������
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

        // ���� ���� ���� ��������
        foreach ($files as $file) {
            if (!empty($file[size])) {
                $filename = $_SERVER["DOCUMENT_ROOT"] . UPLOAD_FILES_DIR . "/customers/" . multibyte::UTF_encode($file["name"]);
                $i = 0;
                while (file_exists($filename)) {
                    $i++;
                    $filename = $_SERVER["DOCUMENT_ROOT"] . UPLOAD_FILES_DIR . "/customers/{$i}_" . multibyte::UTF_encode($file["name"]);
                }
                if (@move_uploaded_file($file["tmp_name"], $filename)) {
                    // ������������� ������
                    $filename = multibyte::UTF_decode($filename);
                    $curfile[$this->getFileId($filename)] = 1; // ������� ��������� ��� ��� ������������
                } else {
                    $ret[affected] = false;
                    $ret[alert] = "�� ������� ��������� ����! ���������� ���.";
                    return $ret;
                }
            }
        }

        if ($edit) {
            // ��������������
            $sql = "UPDATE orders
                    SET customer_id='{$customerid}',
                    orderdate='{$orderdate}',
                    number='{$number}',
                    filelink='{$fileid}'
                    WHERE id='{$edit}'";
            sql::query($sql);
//            $ret[affected] = false;
//            $ret[alert] = "update {$sql} " . print_r($data, true);
        } else {
            // ����������
            $sql = "INSERT INTO orders
                    (customer_id,orderdate,number,filelink)
                    VALUES ('{$customerid}','{$orderdate}','{$number}','{$fileid}')";
            //$ret[alert] = 'insert';
            sql::query($sql);
            $edit = sql::lastId();
        }
        $ret[affected] = true;

        // �������� ������� files
        $sql = "DELETE FROM files WHERE `table`='orders' AND rec_id='{$edit}'";
        sql::query($sql);
        foreach ($curfile as $key => $value) {
            $sql = "INSERT INTO files (`table`,rec_id,fileid) VALUES ('orders','{$edit}','{$key}')";
            sql::query($sql);
        }

        return $ret;
    }

}

?>
