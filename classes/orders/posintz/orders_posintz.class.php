<?php

class orders_posintz extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }
    public function  action_index($all = '', $order = '', $find = '', $idstr = '') {
        $customer = $this->model->getCustomer($_SESSION[customer_id]);
        $customer = $customer[customer];
        $orderarr = $this->model->getOrder($_SESSION[order_id]);
        $date = $orderarr[orderdate];
        $orderstr = $orderarr[number];
        $this->title = 'Позиции в ';
        $this->title .= empty($_SESSION[customer_id])?"Выберите заказчика":"Заказчик - {$customer}";
        $this->title .= empty($_SESSION[order_id]) ? "" : "Заказ - {$orderstr} от {$date} ";
        $this->title .= empty($_SESSION[tz_id]) ? "" : "#{$_SESSION[tz_id]} ";
        return parent::action_index($all, $order, $find, $idstr);
    }
}

?>
