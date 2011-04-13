<?php

/*
 * model class
 */

class orders_tz_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        //console::getInstance()->out("all - {$all}, order - {$order}, find - {$find}, idstr - {$idstr}");
        $ret = array();
        if (!empty($_SESSION[customer_id])) {
            if (empty($_SESSION[order_id])) {
                $sql = "SELECT *,IF(instr(file_link,'���')>0, '���', IF(instr(file_link,'����')>0,'���(����)','���')) AS type,
                            tz.id as tzid,tz.id
                        FROM `tz`
                        JOIN (orders, customers, users,filelinks)
                        ON ( tz.order_id = orders.id AND orders.customer_id = customers.id
                            AND tz.user_id = users.id AND filelinks.id=tz.file_link_id)
                        WHERE customer_id='{$_SESSION[customer_id]}'" .
                        (!empty($find) ? "WHERE (number LIKE '%{$find}%')" : "") .
                        (!empty($order) ? " ORDER BY {$order} " : " ORDER BY tz.id DESC ") .
                        ($all ? "LIMIT 50" : "LIMIT 20");
            } else {
                $orderid = $_SESSION[order_id];
                $sql = "SELECT *,IF(instr(file_link,'���')>0, '���', IF(instr(file_link,'����')>0,'���(����)','���')) AS type,
                        tz.id as tzid,tz.id FROM `tz`
                        JOIN (orders, customers, users,filelinks)
                        ON ( tz.order_id = orders.id AND orders.customer_id = customers.id
                        AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) " .
                        (!empty($find) ? "WHERE (number LIKE '%{$find}%') AND order_id='$orderid' " : " WHERE order_id='$orderid' ") .
                        (!empty($order) ? " ORDER BY {$order} " : " ORDER BY tz.id DESC ") .
                        ($all ? "" : "LIMIT 20");
            }
        } else {
            $sql = "SELECT *,IF(instr(file_link,'���')>0, '���', IF(instr(file_link,'����')>0,'���(����)','���')) AS type,
                    tz.id as tzid,tz.id
                    FROM `tz`
                    JOIN (orders, customers, users,filelinks)
                    ON ( tz.order_id = orders.id AND orders.customer_id = customers.id
                    AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) " .
                    (!empty($find) ? "WHERE (number LIKE '%{$find}%' OR tz.id LIKE '%{$find}%')" : "") .
                    (!empty($order) ? " ORDER BY {$order} " : " ORDER BY tz.id DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        }
        if ($all)
            $_SESSION[tz_id] = '';
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        if (empty($_SESSION[customer_id])) {
            $cols[customer] = "��������";
        }
        if (empty($_SESSION[order_id])) {
            $cols[number] = "�����";
        }
        $cols[tzid] = "ID";
        $cols[type] = "���";
        $cols[tz_date] = "����";
        $cols[nik] = "��� ��������";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;

        $sql = "DELETE FROM tz WHERE id='$delete'";
        sql::query($sql);
        $affected += sql::affected();
        // �������� ������
        $sql = "SELECT * FROM posintz WHERE tz_id='$delete'";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $delete = $rs["id"];
            $sql = "DELETE FROM posintz WHERE id='$delete'";
            sql::query($sql);
            $affected += sql::affected();
        }
        return $affected;
    }

    public function getRecord($edit) {
        if (empty($edit))
            return array();
        $sql = "SELECT file_link AS tzlink,tz.id FROM tz JOIN filelinks ON filelinks.id=tz.file_link_id WHERE tz.id='{$edit}'";
        $rec = sql::fetchOne($sql);
        return $rec;
    }

    public function  setRecord($data) {
        extract($data);
        //console::getInstance()->out(print_r($data, true));return;

        sql::query($sql);

        return sql::affected();
    }

    public function createTZ($typetz) {
        // np �� ���� ������������� - ������ ��������� � ������� ����� � �������������
        // ��������� ������� � ������
        $orderid = $_SESSION[order_id];
        $sql = "SELECT COUNT(*)+1 AS next FROM tz WHERE order_id='{$orderid}'";
        $rs = sql::fetchOne($sql);
        $pos_in_order = $rs[next];

        // ����������
        // ������� ���� � ���������
        // ��������� ���������
        $sql = "SELECT number,orderdate,customer, fullname
                FROM orders
                JOIN customers
                ON customers.id=customer_id
                WHERE orders.id='{$orderid}'";
        //echo $sql;
        $rs = sql::fetchOne($sql);
        $order = $rs["number"];
        $customer = $rs["customer"];
        $fullname = $rs["fullname"];
        $odate = $rs["orderdate"];
        $cdate = date("m-d-Y");

        $sql = "INSERT INTO tz (order_id,tz_date,user_id) VALUES ('{$orderid}',NOW(),'{$_SESSION[userid]}')";
        sql::query($sql);

        $tzid = sql::lastId();

        do {
            $filetype = $typetz == "mpp" ? "���" : ($typetz == "dpp" ? "���" : "���-����");
            $orderstring = fileserver::removeOSsimbols($rs["number"]);
            $file_link = "t:\\\\������ ��������� ����\\\\������\\\\{$customer}\\\\{$tzid}-{$filetype}-{$pos_in_order}-{$orderstring} �� {$rs["orderdate"]}.xls";
            $filename = fileserver::createdironserver($file_link);
            $fe = file_exists($filename);
            if ($fe)
                $pos_in_order++;
        } while ($fe);
        // ��������� ������������� �������� ������
        $file_id = $this->getFileId($file_link);
        // �������� ���� �
        $sql = "UPDATE tz SET file_link_id='{$file_id}', pos_in_order='{$pos_in_order}' WHERE id='{$tzid}'";
        sql::query($sql);

        $rec = array('id' => $tzid, 'tzlink' => $file_link, 'success' => true);
        $rec = array_merge($rec,  compact('cdate','filename','order','odate','typetz','fullname','tzid'));
        return $rec;
    }

}

?>
