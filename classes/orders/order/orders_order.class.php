<?php

class orders_order extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        list($customer_id,$order_id,$tz_id,$posintzid) = explode(':',$idstr);
        $customer = $this->model->getCustomer($customer_id);
        $customer = $customer[customer];
        $this->title = empty($customer_id) ? "Выберите заказчика" : "Заказчик - {$customer} ";
        return parent::action_index($all, $order, $find, $idstr);
    }

    public function action_open($id) {
        $order_id = $id;
        $order = $this->model->getOrder($id);
        $customer_id = $order[customer_id];
        $tz_id = '';
        $idstr = "{$customer_id}:{$order_id}:{$tz_id}:{$posintzid}";
        $this->_goto($this->uri()->clear()->set('orders', 'tz')->
                set('orders_tz','index', false,'','',$idstr)->url());
    }

}

?>
