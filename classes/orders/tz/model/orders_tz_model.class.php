<?php

/*
 * model class
 */

class orders_tz_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = parent::getData($all, $order, $find, $idstr);
        extract($_SESSION[Auth::$lss]);
        if (!empty($customer_id)) {
            if (empty($order_id)) {
                $sql = "SELECT *,IF(instr(file_link,'МПП')>0, 'МПП', IF(instr(file_link,'Блок')>0,'ДПП(Блок)','ДПП')) AS type,
                            tz.id as tzid,tz.id
                        FROM `tz`
                        JOIN (orders, customers, users,filelinks)
                        ON ( tz.order_id = orders.id AND orders.customer_id = customers.id
                            AND tz.user_id = users.id AND filelinks.id=tz.file_link_id)
                        WHERE customer_id='{$customer_id}'" .
                        (!empty($find) ? "WHERE (number LIKE '%{$find}%')" : "") .
                        (!empty($order) ? " ORDER BY {$order} " : " ORDER BY tz.id DESC ") .
                        ($all ? "LIMIT 50" : "LIMIT 20");
            } else {
                $orderid = $order_id;
                $sql = "SELECT *,IF(instr(file_link,'МПП')>0, 'МПП', IF(instr(file_link,'Блок')>0,'ДПП(Блок)','ДПП')) AS type,
                        tz.id as tzid,tz.id FROM `tz`
                        JOIN (orders, customers, users,filelinks)
                        ON ( tz.order_id = orders.id AND orders.customer_id = customers.id
                        AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) " .
                        (!empty($find) ? "WHERE (number LIKE '%{$find}%') AND order_id='$orderid' " : " WHERE order_id='$orderid' ") .
                        (!empty($order) ? " ORDER BY {$order} " : " ORDER BY tz.id DESC ") .
                        ($all ? "" : "LIMIT 20");
            }
        } else {
            $sql = "SELECT *,IF(instr(file_link,'МПП')>0, 'МПП', IF(instr(file_link,'Блок')>0,'ДПП(Блок)','ДПП')) AS type,
                    tz.id as tzid,tz.id
                    FROM `tz`
                    JOIN (orders, customers, users,filelinks)
                    ON ( tz.order_id = orders.id AND orders.customer_id = customers.id
                    AND tz.user_id = users.id AND filelinks.id=tz.file_link_id) " .
                    (!empty($find) ? "WHERE (number LIKE '%{$find}%' OR tz.id LIKE '%{$find}%')" : "") .
                    (!empty($order) ? " ORDER BY {$order} " : " ORDER BY tz.id DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        }
        $ret = sql::fetchAll($sql);
        if ($all)
            $_SESSION[Auth::$lss][tz_id] = '';
        return $ret;
    }

    public function getCols() {
        $cols = array();
        extract($_SESSION[Auth::$lss]);
        if (empty($customer_id)) {
            $cols[customer] = "Заказчик";
        }
        if (empty($order_id)) {
            $cols[number] = "Заказ";
        }
        $cols[tzid] = "ID";
        $cols[type] = "Тип";
        $cols[tz_date] = "Дата";
        $cols[nik] = "Кто заполнил";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;

        $sql = "DELETE FROM tz WHERE id='$delete'";
        sql::query($sql);
        $affected += sql::affected();
        // удаление связей
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
        // получить уже существующиепозиции ТЗ и данные для формы заполнения
        return $rec;
    }


    public function createTZ($rec) {
        extract($rec);
        // np не надо редактировать - только добавлять с текущей датой и пользователем
        // определим позицию в письме
        extract($_SESSION[Auth::$lss]);//list($customer_id,$order_id,$tz_id,$posintzid) = explode(':',$idstr);
        $orderid = $order_id;
        $sql = "SELECT COUNT(*)+1 AS next FROM tz WHERE order_id='{$orderid}'";
        $rs = sql::fetchOne($sql);
        $pos_in_order = $rs[next];

        // добавление
        // создать файл с табличкой
        // определим заказчика
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

        $sql = "INSERT INTO tz (order_id,tz_date,user_id) VALUES ('{$orderid}',NOW(),'" . Auth::getInstance()->getUser('id') . "')";
        sql::query($sql);

        $tzid = sql::lastId();

        do {
            $filetype = $typetz == "mpp" ? "МПП" : ($typetz == "dpp" ? "ДПП" : ($typetz == "mppb" ? "МПП-Блок" : "ДПП-Блок"));
            $orderstring = fileserver::removeOSsimbols($rs["number"]);
            $file_link = "t:\\\\Расчет стоимости плат\\\\ТехЗад\\\\{$customer}\\\\{$tzid}-{$filetype}-{$pos_in_order}-{$orderstring} от {$rs["orderdate"]}.xls";
            $filename = fileserver::createdironserver($file_link);
            $fe = file_exists($filename);
            if ($fe)
                $pos_in_order++;
        } while ($fe);
        // Определим идентификатор файловой ссылки
        $file_id = $this->getFileId($file_link);
        // добавить поля в
        $sql = "UPDATE tz SET file_link_id='{$file_id}', pos_in_order='{$pos_in_order}' WHERE id='{$tzid}'";
        sql::query($sql);

        $rec = array('id' => $tzid, 'tzlink' => $file_link, 'success' => true);
        $tzid = sprintf("%08s",$tzid); // для отображения в заказе
        $rec = array_merge($rec, compact('cdate', 'filename', 'order', 'odate', 'typetz', 'fullname', 'tzid'));
        return $rec;
    }

}

?>
