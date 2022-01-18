<?php

/*
 * model class
 */

class matterpricelist_suppliers_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'calc__suppliers';
    }

    public function getCols() {
        $cols = array();
        $cols['id'] = 'ID';
        $cols['supplier_shortname'] = 'Короткое имя поставщика';
        $cols['supplier_name'] = 'Наименование поставщика';
        return $cols;
    }

}

?>
