<?php

/*
 * model class
 */

class matterpricelist_matters_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'calc__matters';
    }

    public function getCols() {
        $cols = array();
        $cols['id'] = 'ID';
        $cols['matter_name'] = 'Название материала';
        $cols['matter_unit'] = 'единица измерения';
        $cols['matter_factor'] = 'коэф. преобразования';
        return $cols;
    }

}

?>
