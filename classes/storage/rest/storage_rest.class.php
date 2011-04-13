<?php

class storage_rest extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_open($id) {
        $_SESSION[spr_id] = $id;
        $this->_goto($this->uri()->clear()->set('storage', 'moves')->url());
    }

    public function  action_index($all = '', $order = '', $find = '', $idstr = '') {
        $_SESSION[spr_id] = '';
        return parent::action_index($all, $order, $find, $idstr);
    }
}

?>
