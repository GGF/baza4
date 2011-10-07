<?php

/*
 * class
 */

class cp_workers_model extends sqltable_model {
    
    public function __construct() {
        parent::__construct();
        $this->maintable = 'workers';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT *
                FROM workers " .
                (!empty($find) ? "WHERE (fio LIKE '%{$find}%' OR dr LIKE '%{$find}%') " : "") .
                (!empty($order) ? "ORDER BY " . $order . " " : "ORDER BY fio ") .
                ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[id] = "ID";
        $cols[fio] = "ФИО";
        $cols[dolz] = "Должность";
        $cols[dr] = "День рождения";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM workers WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }

    public function  setRecord($data) {
        extract($data);
        $fio = "{$f} {$i} {$o}";
        $storedata = array(
            array(
                "id" => $edit,
                "f" => $f,
                "i" => $i,
                "o" => $o,
                "fio" => $fio,
                "dolz" => $dolz,
                "dr" => $dr,
                ),
        );
        sql::insertUpdate($this->maintable,$storedata);
        return parent::setRecord($data);
        

    }


}

?>
