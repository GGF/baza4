<?php

/**
 * Description of orders_blocks_view
 *
 * @author igor
 */
class matterpricelist_matters_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        extract($rec);
        $fields = array();
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "matter_name",
            "label" => "Наименование:",
            "value" => $rec["matter_name"],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "matter_unit",
            "label" => "Ед.изм.:",
            "value" => $rec["matter_unit"],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "matter_factor",
            "label" => "Коэф. перевода:",
            "value" => is_double($rec["matter_factor"])?1:$rec["matter_factor"], // по умолчанию коэф перевода равен 1
        ));
        $rec['fields'] = $fields;
        return parent::showrec($rec);
    }

}

?>
