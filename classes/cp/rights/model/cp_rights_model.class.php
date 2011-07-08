<?php

class cp_rights_model extends sqltable_model {
    
    public function __construct() {
        parent::__construct();
        $this->maintable = 'rtypes';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT *
                FROM rtypes
                " .
                (!empty($find) ? "WHERE (type LIKE '%$find%' ) " : "") .
                (!empty($order) ? "ORDER BY " . $order . " " : "ORDER BY type ") . 
                ((isset($all)) ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $value[what] = html_entity_decode($value[what]);
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[id] = "ID";
        $cols[type] = "На что";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM rtypes WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }

    public function setRecord($data) {
        extract($data);
        if (!empty($edit)) {
            $sql = "UPDATE rtypes SET type='{$type}' WHERE id='{$edit}'";
        } else {
            $sql = "INSERT INTO `rtypes` (`type`) VALUES ('{$type}')";
        }
        sql::query($sql);
        return sql::affected();
    }

}

?>
