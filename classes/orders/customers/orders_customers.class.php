<?php

class orders_customers extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }


    public function action_open($id) {
        $_SESSION[Auth::$lss][customer_id] = $id;
        $_SESSION[Auth::$lss][order_id] = '';
        $_SESSION[Auth::$lss][tz_id] = '';
        $this->_goto($this->uri()->clear()->set('orders', 'order')->url().'&lss='.Auth::$lss);
    }

}

?>
