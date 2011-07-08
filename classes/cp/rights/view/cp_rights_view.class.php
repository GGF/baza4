<?php

class cp_rights_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        $rec[fields] = array(
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "type",
                "label" => 'Право на что',
                "value" => $rec["type"],
            ),
        );
        $rec[files] = false;
        return parent::showrec($rec);
    }

}

?>
