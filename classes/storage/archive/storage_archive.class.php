<?php

class storage_archive extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_open($id) {
        $_SESSION[Auth::$lss]['arctovarid'] = $id;
        $this->_goto($this->uri()->clear()->set('storage', 'archivemoves')->url().'&lss='.Auth::$lss);
//        $this->_goto($this->uri()->clear()->set('storage', 'archivemoves')->
//                set('storage_archivemoves','index',false,'','',$id)->url());
    }
}

?>
