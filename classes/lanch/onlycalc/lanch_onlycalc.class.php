<?php

class lanch_onlycalc extends lanch_nzap {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function init() {
        parent::init();
        $this->addbutton = false;
    }

}

?>