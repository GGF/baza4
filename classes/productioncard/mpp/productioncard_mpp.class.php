<?php

class productioncard_dpp extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function init() {
        parent::init();
        //$this->addbutton = false;
    }
}

?>
