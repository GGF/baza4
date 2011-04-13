<?php

class orders_customers extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_edit($id) {
        if (!$_SESSION[rights][$this->getName()][edit])
            return $this->view->getMessage('Нет прав на редактирование');
        $rec = $this->model->getRecord($id);
        $rec[edit] = $id;
        $rec[action] = $this->actUri('processingform')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        return $this->view->getForm($out);
    }

    public function action_open($id) {
        $_SESSION[customer_id] = $id;
        $_SESSION[order_id] = '';
        $_SESSION[tz_id] = '';
        $this->_goto($this->uri()->clear()->set('orders', 'order')->url());
    }

}

?>
