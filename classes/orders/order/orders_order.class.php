<?php

class orders_order extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        $customer = $this->model->getCustomer($_SESSION[customer_id]);
        $customer = $customer[customer];
        $this->title = empty($_SESSION[customer_id]) ? "Выберите заказчика" : "Заказчик - {$customer}";
        return parent::action_index($all, $order, $find, $idstr);
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(), 'edit'))
            return $this->view->getMessage('Нет прав на редактирование');
        $rec = $this->model->getRecord($id);
        $rec[edit] = $id;
        $rec[customers] = $this->model->getCustomers();
        $rec[action] = $this->actUri('processingform')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        return $this->view->getForm($out);
    }

    public function action_open($id) {
        $_SESSION[order_id] = $id;
        $order = $this->model->getOrder($id);
        $_SESSION[customer_id] = $order[customer_id];
        $_SESSION[tz_id] = '';
        //$_SESSION[customer] = $this->model->getCustomer($id);
        $this->_goto($this->uri()->clear()->set('orders', 'tz')->url());
    }

}

?>
