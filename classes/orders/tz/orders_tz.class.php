<?php

class orders_tz extends sqltable {

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
        $this->title = empty($customer_id) ? "" : "Заказчик - {$customer} ";
        $this->title .= empty($order_id) ? "" : "Заказ - {$orderstr} от {$date} ";
        return parent::action_index($all, $order, $find, $idstr);
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(), 'edit')) {
            return $this->view->getMessage('Нет прав на редактирование');
        }
        extract($_SESSION[Auth::$lss]); // тут хранятся выбранные данные заказ и т.п.
        if (empty($id)) {
            if (empty($order_id)) {
                return $this->getMessage("Не известно куда добавлять. Выбери заказ!");
            } else {
                //не известен тип задания - спросим
                $data[idstr] = $this->idstr;
                $data[mpplink] = $this->actUri('addtz', 'mpp')->url();
                $data[mppblink] = $this->actUri('addtz', 'mppb')->url();
                $data[dpplink] = $this->actUri('addtz', 'dpp')->url();
                $data[dppblink] = $this->actUri('addtz', 'dppb')->url();
                return $this->getMessage($this->view->selecttype($data));
            }
        } else {
            $rec = $this->model->getRecord($id);
            return $this->getMessage($this->view->showrec($rec));
        }
    }

    public function action_open($id) {
        $_SESSION[Auth::$lss][tz_id] = $id;
        $tz = $this->model->getTZ($id);
        $_SESSION[Auth::$lss][order_id] = $tz[order_id];
        $order = $this->model->getOrder($_SESSION[Auth::$lss][order_id]);
        $_SESSION[Auth::$lss][customer_id] = $order[customer_id];
        $this->_goto($this->uri()->clear()->set('orders', 'posintz')->url().'&lss='.Auth::$lss);
    }

    public function action_addtz($type) {
        $rec[idstr] = $this->idstr;
        $rec[typetz] = $type;
        $rec = $this->model->createTZ($rec);
        if ($rec[success])
            return $this->view->savefiletz($rec);
        else
            return $rec[error];
    }

}

?>
