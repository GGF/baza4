<?php

/*
 * conduct model class
 */

class cp_todo_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        //console::getInstance()->out("all - {$all}, order - {$order}, find - {$find}, idstr - {$idstr}");
        $ret = array();
        $sql = "SELECT *, todo.id
                FROM todo
                JOIN users
                ON users.id=u_id " .
                (!empty($find) ? "WHERE (what LIKE '%$find%' ) " : "") .
                ($all ? "" : (isset($find) ? " AND rt='0000-00-00 00:00:00' " : " WHERE rts='0000-00-00 00:00:00' ")) .
                (!empty($order) ? "ORDER BY " . $order . " " : "ORDER BY cts ") . ((isset($all)) ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $value[what] = html_entity_decode($value[what]);
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[id] = "ID";
        $cols[nik] = "Кто";
        $cols[cts] = "Задан";
        $cols[rts] = "Закончен";
        $cols[what] = "Что сделать";
        return $cols;
    }

    public function delete($id) {
        $sql = "SELECT what FROM todo WHERE id='{$id}'";
        $rs = sql::fetchOne($sql);
        $sql = "UPDATE todo SET rts=NOW(), what='<del>{$rs["what"]}</del>' WHERE id='{$id}'";
        sql::query($sql);
        sql::query($sql);
        return sql::affected();
    }

    public function getRecord($id) {
        $sql = "SELECT * FROM todo WHERE id='{$id}'";
        $rec = sql::fetchOne($sql);
        return $rec;
    }

    public function setRecord($data) {
        extract($data);
        //console::getInstance()->out(print_r($data, true));
        if (!empty($edit)) {
            $sql = "UPDATE todo SET what='" . addslashes($what) . "', cts=NOW(), rts='0', u_id='" . Auth::getInstance()->getUser('userid') . "' WHERE id='{$edit}'";
        } else {
            $sql = "INSERT INTO todo (what,cts,rts,u_id) VALUES ('" . addslashes($what) . "',NOW(),'0'," . Auth::getInstance()->getUser('userid') . ")";
        }
        sql::query($sql);
        return sql::affected();
    }

}

?>
