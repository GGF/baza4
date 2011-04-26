<?php

/*
 * model class
 */

class orders_order_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        if (empty($_SESSION[customer_id])) {
            $customer = "Выберите заказчика!!!";
            $sql = "SELECT *,
                    CONCAT(\"<a target=_blank href='http://" . $_SERVER["HTTP_HOST"] .
                    UPLOAD_FILES_DIR . "/customers/\",SUBSTRING_INDEX(filelinks.file_link,'/',-1),\"'>\",SUBSTRING_INDEX(filelinks.file_link,'/',-1),\"</a>\") AS filename,
                        orders.id
                        FROM orders
                        JOIN customers
                        ON customers.id=customer_id LEFT JOIN filelinks ON orders.filelink=filelinks.id " .
                    (!empty($find) ? "WHERE (number LIKE '%{$find}%' OR orderdate LIKE '%{$find}%' ) " : "") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY orders.orderdate DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        } else {
            $cusid = $_SESSION[customer_id];
            $customer = $_SESSION[customer];
            // sql
            $link = '<a target=_blank href="http://' . $_SERVER["HTTP_HOST"] . UPLOAD_FILES_DIR .
                    '/customers/",SUBSTRING_INDEX(filelinks.file_link,"/",-1),">",SUBSTRING_INDEX(filelinks.file_link,"/",-1),"</a>"';
            $sql = "SELECT *,CONCAT(\"<a target=_blank href='http://" .
                    $_SERVER["HTTP_HOST"] . UPLOAD_FILES_DIR .
                    "/customers/\",SUBSTRING_INDEX(filelinks.file_link,'/',-1),\"'>\",SUBSTRING_INDEX(filelinks.file_link,'/',-1),\"</a>\") AS filename,
                            orders.id
                            FROM orders
                            JOIN customers ON customers.id=customer_id
                            LEFT JOIN filelinks
                            ON orders.filelink=filelinks.id " .
                    (!empty($find) ? "WHERE (number LIKE '%{$find}%' OR orderdate LIKE '%{$find}%' ) AND customer_id='{$cusid}' " : "WHERE customer_id='{$cusid}' ") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY orders.orderdate DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        }
        $ret = sql::fetchAll($sql);
        //$ret[title] = "Заказчик - {$ret[customer]} ";
        if ($all) {
            $_SESSION[order_id] = '';
            $_SESSION[tz_id]='';
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
        $cols[filename] = "Файл";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "DELETE FROM orders WHERE id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        // удаление связей
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
        return $rec;
    }

    public function  setRecord($data) {
        extract($data);
        $orderdate = sql::datepicker2date($orderdate);

        // файл если есть сохраним
        if (!empty($files["order_file"]["size"])) {
            $filename = $_SERVER["DOCUMENT_ROOT"] . UPLOAD_FILES_DIR . "/customers/" . multibyte::UTF_encode($files["order_file"]["name"]);
            $i = 0;
            while (file_exists($filename)) {
                $i++;
                $filename = $_SERVER["DOCUMENT_ROOT"] . UPLOAD_FILES_DIR . "/customers/{$i}_" . multibyte::UTF_encode($files["order_file"]["name"]);
            }
            if (@move_uploaded_file($files["order_file"]["tmp_name"], $filename)) {
                //$form->alert('перекинул');
                $filename = multibyte::UTF_decode($filename);
                $fileid = $this->getFileId($filename);
            } else {
                $ret[affected] = false;
                $ret[alert] = "Не удалось сохранить файл! Попробуйте еще.";
                return $ret;
            }
        } else {
            // файл не менялся
//            if ($curfile != 'None')
//                $fileid = getFileId($_SERVER["DOCUMENT_ROOT"] . "/customers/ordersfile/" . $curfile);
        }

        if ($edit) {
            // редактирование
            $sql = "UPDATE orders
                    SET customer_id='{$customerid}',
                    orderdate='{$orderdate}',
                    number='{$number}',
                    filelink='{$fileid}'
                    WHERE id='{$edit}'";
//            $ret[affected] = false;
//            $ret[alert] = "update {$sql} " . print_r($data, true);
        } else {
            // добавление
            $sql = "INSERT INTO orders
                    (customer_id,orderdate,number,filelink)
                    VALUES ('{$customerid}','{$orderdate}','{$number}','{$fileid}')";
            //$ret[alert] = 'insert';
        }
        sql::query($sql);
        $ret[affected] = sql::affected();

        return $ret;
    }

}

?>
