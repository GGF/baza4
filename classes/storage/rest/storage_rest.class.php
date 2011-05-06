<?php

class storage_rest extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_open($id) {
        $_SESSION[spr_id] = $id;
        $this->_goto($this->uri()->clear()->set('storage', 'moves')->
                set('storage_moves','index',false,'','',$id)->url());
    }

}

?>
