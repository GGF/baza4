<?php

class orders_posintz extends sqltable {

    /**
     * @var orders_posintz_view
     */
    protected $view;

    /**
     * @var orders_posintz_model
     */
    protected $model;


    // обязательно определять для модуля
    public function getDir() {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'i18n.php'; //всё равноперреопределяется и вызывается, почему не тут вставлять
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        if(is_array($_SESSION[Auth::$lss])) extract($_SESSION[Auth::$lss]);
        $customer = $this->model->getCustomer($customer_id);
        $customer = $customer['customer'];
        $orderarr = $this->model->getOrder($order_id);
        $date = $orderarr['orderdate'];
        $orderstr = $orderarr['number'];
        $this->title = 'Позиции в ';
        $this->title .= empty($customer_id) ? " Выберите заказчика" : " Заказчик - {$customer}";
        $this->title .= empty($order_id) ? "" : " Заказ - {$orderstr} от {$date} ";
        $this->title .= empty($tz_id) ? "" : " #{$tz_id}";
        return parent::action_index($all, $order, $find, $idstr);
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(), 'edit')) {
            return $this->view->getMessage('Нет прав на редактирование');
        }
        if(is_array($_SESSION[Auth::$lss])) extract($_SESSION[Auth::$lss]); // тут данные выбранных до сих пор заказа и тз
        if (empty($id)) {
            // добавить плату в ТЗ
            if (empty($tz_id)) {
                return $this->getMessage('Не известно куда добавлять выбери ТЗ!');
            } else { 
                return parent::action_edit($id);
            }
        } else {
            // выбрана плата - вывести предложение создать рассчет
            $url = $this->model->getFileLinkForRaschet(array('id'=>$id));
            if ($url) {
                $rec['rasslink'] = $url;
            } else {
                $rec['createlink'] = $this->actUri('createras', $id)->url();
                $rec['createalllinks'] = $this->actUri('createallras', $id)->url();
            }
            return $this->getMessage($this->view->showbutton($rec));
        }
    }

    public function action_createras($id) {
        $rec = $this->model->getDataForCalc($id);
        $res = $this->view->getRasschet($rec);
        if ($res['result']) {
            $res['id'] = $id;
            $this->model->saveFileLinkForRaschet($res);
        }
        return $res['out'];
    }

    /**
     * Последовательно вызавыет создание рассчетов для всего заказа
     * @param integer $id - идентификатор одной из позиций в заказе
     * @return string строка об успешности
     */
    public function action_createallras($id) {
        $ids = $this->model->getPosintzIdsByOneId($id);
        $res = '';
        foreach($ids as $posid ) {
            $res .= $this->action_createras($posid[0]);
        }
        return $res;
    }
    
    public function action_addposintz($id) {
        return 'Добавилась!';
    }

}

?>
