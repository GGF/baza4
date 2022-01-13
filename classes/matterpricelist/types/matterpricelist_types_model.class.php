<?php

/*
 * model class
 */

class matterpricelist_types_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'calc__types';
    }

    public function getCols() {
        $cols = array();
        $cols['id'] = 'ID';
        $cols['type'] = 'Название платы';
        return $cols;
    }

}

?>
