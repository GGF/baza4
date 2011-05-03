<?php

class orders_customers extends sqltable {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(),'edit'))
            return $this->view->getMessage('��� ���� �� ��������������');
        $rec = $this->model->getRecord($id);
        $rec[edit] = $id;
        $rec[action] = $this->actUri('processingform')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        return $this->view->getForm($out);
    }

    public function action_open($id) {
        $this->_goto($this->uri()->clear()->set('orders', 'order')->
                set('orders_order','index', false,'','',"$id:::")->url());// idstr ������
    }

}

?>
