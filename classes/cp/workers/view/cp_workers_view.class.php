<?php

class cp_workers_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $rec['fields'] = array(
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "f",
                "label" => "Фамилия:",
                "value" => $rec["f"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "i",
                "label" => "Имя:",
                "value" => $rec["i"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "o",
                "label" => "Отчество:",
                "value" => $rec["o"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "dolz",
                "label" => "Должность:",
                "value" => $rec["dolz"],
            ),
            array(
                "type" => AJAXFORM_TYPE_DATE,
                "name" => "dr",
                "label" => "День рождения:",
                "value" => $rec["dr"],
            ),
        );

        return parent::showrec($rec);
    }

}

?>
