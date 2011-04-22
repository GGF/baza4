<?php

class orders_tz extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        $customer = $this->model->getCustomer($_SESSION[customer_id]);
        $customer = $customer[customer];
        $orderarr = $this->model->getOrder($_SESSION[order_id]);
        $date = $orderarr[orderdate];
        $orderstr = $orderarr[number];
        $this->title = empty($_SESSION[customer_id]) ? "" : "Заказчик - {$customer} ";
        $this->title .= empty($_SESSION[order_id]) ? "" : "Заказ - {$orderstr} от {$date} ";
        return parent::action_index($all, $order, $find, $idstr);
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(),'edit'))
            return $this->view->getMessage('Нет прав на редактирование');
        if (empty($id)) {
            if (empty($_SESSION[order_id])) {
                return $this->getMessage("Не известно куда добавлять. Выбери заказ!");
            } else {
                //не известен тип задания - спросим
                $data[mpplink] = $this->actUri('addtz', 'mpp')->url();
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
        $_SESSION[tz_id] = $id;
        $tz = $this->model->getTZ($id);
        $_SESSION[order_id] = $tz[order_id];
        $order = $this->model->getOrder($tz[order_id]);
        $_SESSION[customer_id] = $order[customer_id];
        //$_SESSION[customer] = $this->model->getCustomer($id);
        $this->_goto($this->uri()->clear()->set('orders', 'posintz')->url());
    }

    public function action_addtz($type) {
        $rec = $this->model->createTZ($type);
        if ($rec[success])
            return $this->view->savefiletz($rec);
        else
            return $rec[error];
    }

}

?>
