<?php

class orders_posintz extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        extract($_SESSION[Auth::$lss]);
        $customer = $this->model->getCustomer($customer_id);
        $customer = $customer[customer];
        $orderarr = $this->model->getOrder($order_id);
        $date = $orderarr[orderdate];
        $orderstr = $orderarr[number];
        $this->title = 'Позиции в ';
        $this->title .= empty($customer_id) ? " Выберите заказчика" : " Заказчик - {$customer}";
        $this->title .= empty($order_id) ? "" : " Заказ - {$orderstr} от {$date} ";
        $this->title .= empty($tz_id) ? "" : " #{$tz_id}";
        return parent::action_index($all, $order, $find, $idstr);
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(), 'edit'))
            return $this->view->getMessage('Нет прав на редактирование');
        extract($_SESSION[Auth::$lss]);
        $rec = $this->model->getRecord($id);
        $rec[createlink] = $this->actUri('createrass', $id)->url();
        return $this->getMessage($this->view->showrec($rec));
    }

    public function action_createrass($id) {
        $rec[rasslink] = 'http://habr.ru';
        return $this->view->showrec($rec);
    }

}

?>
