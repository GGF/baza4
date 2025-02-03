<?php

/**
 * Description of possibility_boards_view
 *
 * @author ggf
 */
class possibility_boards_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        extract($rec);
        $fields = array();
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "customer",
            "label" => "Заказчик",
            "value" => $rec["customer"],
            "options" => array("html" => "size=100"),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "board",
            "label" => "Наименование платы",
            "value" => $rec['board'],
            "options" => array("html" => "size=100"),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "possibility",
            "label" => "Можем?",
            "value" => $rec['possibility'],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "reason",
            "label" => "Причина",
            "value" => $rec['reason'],
            "options" => array("html" => "size=100"),
        ));
        $rec['fields'] = $fields;
        return parent::showrec($rec);
    }

}

?>
