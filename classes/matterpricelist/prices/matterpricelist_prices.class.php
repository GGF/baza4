<?php

class matterpricelist_prices extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_open($id)
    {
        $_SESSION[Auth::$lss]['matter_id'] = $id;
        $this->_goto($this->uri()->clear()->set('matterpricelist', 'changes')->url().'&lss='.Auth::$lss);        
    }
}

?>
