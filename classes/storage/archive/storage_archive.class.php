<?php

class storage_archive extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_open($id) {
        $_SESSION[arc_spr_id] = $id;
        $this->_goto($this->uri()->clear()->set('storage', 'archivemoves')->url());
    }
    public function  action_index($all = '', $order = '', $find = '', $idstr = '') {
        $_SESSION[arc_spr_id] = '';
        return parent::action_index($all, $order, $find, $idstr);
    }
}

?>
