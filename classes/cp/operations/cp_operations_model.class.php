<?php

class cp_operations_model extends sqltable_model {
    
    public function __construct() {
        parent::__construct();
        $this->maintable = 'operations';
    }

    public function getCols() {
        $cols = array();
        $cols['block_type'] = "Тип";
        $cols['shortname'] = "Заголовок";
        $cols['operation'] = "Операция";
        $cols['priority'] = "Приоритет";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM ".$this->maintable." WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }

}

?>
