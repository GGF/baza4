<?php

class storage_request extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        $data[dates] = $this->model->getDates();
        $idstr = empty($_REQUEST[ddate])?$data[dates][0][ddate]:$_REQUEST[ddate];
        $data[ddate] = $idstr;
        $this->title = $this->view->getTitle($data);
        $out = '<form class="lego" name=requestform action="'.$this->actUri('print')->ajaxurl($this->getName()).'">';
        $out.=parent::action_index($all, $order, $find, $idstr);
        $out.='</form>';
        return $out;
    }

    public function action_print() {
        $_REQUEST[positions] = $this->model->getRecord($_REQUEST);
        return $this->view->showrec($_REQUEST);
    }

}

?>
