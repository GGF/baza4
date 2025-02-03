<?php

class storage_rest extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_open($id) {
        $_SESSION[Auth::$lss]['tovarid'] = $id;
        $this->_goto($this->uri()->clear()->set('storage', 'moves')->url().'&lss='.Auth::$lss);
//        $this->_goto($this->uri()->clear()->set('storage', 'moves')->
//                set('storage_moves','index',false,'','',$id)->url());
    }

}

?>
