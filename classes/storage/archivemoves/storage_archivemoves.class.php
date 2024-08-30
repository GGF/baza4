<?php

class storage_archivemoves extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        $idstr = $_SESSION[Auth::$lss]['arctovarid'];
        $tovar = $this->model->getTovar($idstr);
        $ost = $this->model->getOst($idstr);
        $ost=$ost['ost'];
        $edizm = $tovar['edizm'];
        $nazv = $tovar['nazv'];
        $this->title = empty($idstr) ? "" : "Движения - {$nazv} - остаток - {$ost} {$edizm}";
        return parent::action_index($all, $order, $find, $idstr);
    }
    
    public function action_open($id) {
        return false;
    }
}

?>
