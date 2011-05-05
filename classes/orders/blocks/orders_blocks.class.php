<?php

class orders_blocks extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        //$customer = $this->model->getCustomer($_SESSION[customer_id]);
        //$customer = $customer[customer];
        //$this->title = empty($_SESSION[customer_id]) ? "Выберите заказчика" : "Заказчик - {$customer}";
        return parent::action_index($all, $order, $find, $idstr);
    }

}

?>
