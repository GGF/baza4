<?php

class orders_posintz extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }
    public function  action_index($all = '', $order = '', $find = '', $idstr = '') {
        list($customer_id,$order_id,$tz_id,$posintzid) = explode(':',$idstr);
        $customer = $this->model->getCustomer($customer_id);
        $customer = $customer[customer];
        $orderarr = $this->model->getOrder($order_id);
        $date = $orderarr[orderdate];
        $orderstr = $orderarr[number];
        $this->title = 'Позиции в ';
        $this->title .= empty($customer_id)?" Выберите заказчика":" Заказчик - {$customer}";
        $this->title .= empty($order_id) ? "" : " Заказ - {$orderstr} от {$date} ";
        $this->title .= empty($tz_id) ? "" : " #{$tz_id}";
        return parent::action_index($all, $order, $find, $idstr);
    }
}

?>
