<?php

class orders_order extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        if(is_array($_SESSION[Auth::$lss])) 
            extract($_SESSION[Auth::$lss]);
        $customer = $this->model->getCustomer($customer_id);
        $customer = $customer['customer'];
        $this->title = empty($customer_id) ? "Выберите заказчика" : "Заказчик - {$customer} ";
        return parent::action_index($all, $order, $find, $idstr);
    }

    public function action_open($id) {
        $_SESSION[Auth::$lss]['order_id'] = $id;
        $order = $this->model->getOrder($id);
        $_SESSION[Auth::$lss]['customer_id'] = $order['customer_id'];
        $_SESSION[Auth::$lss]['tz_id'] = '';
        $this->_goto($this->uri()->clear()->set('orders', 'tz')->url().'&lss='.Auth::$lss);
    }

}

?>
