<?php

/**
 * Description of matterpricelist_prices_view
 *
 * @author igor
 */
class matterpricelist_prices_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        extract($rec);
        $fields = array();
        if ($rec['isnew']) {
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "matter_type_id",
                "label" => "Тип:",
                "values" => $rec['types'],
                "value" => '',
                "options" => array('html' => ' size=60 '),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "matter_name_id",
                "label" => "Наименование:",
                "values" => $rec['matters'],
                "value" => '',
                "options" => array('html' => ' size=60 '),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "supplier_id",
                "label" => "Поставщик:",
                "values" => $rec['suppliers'],
                "value" => '',
                "options" => array('html' => ' size=60 '),
            ));
        } else {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "matter_type_id",
                "value" => $rec["matter_type_id"],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "type",
                "label" => "Тип:",
                "value" => $rec['type'],
                "options" => array('readonly' => true, 'html' => ' size=40 '),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "matter_name_id",
                "value" => $rec["matter_name_id"],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "matter",
                "label" => "Наименование:",
                "value" => $rec['matter'],
                "options" => array('readonly' => true, 'html' => ' size=40 '),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "supplier_id",
                "value" => $rec["supplier_id"],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "supplier",
                "label" => "Поставщик:",
                "value" => $rec['supplier'],
                "options" => array('readonly' => true, 'html' => ' size=40 '),
            ));
        }
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "discharge_norm_in",
            "label" => "Норма расхода для внутренних слоев:",
            "value" => $rec['discharge_norm_in'],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "discharge_norm_out",
            "label" => "Норма расхода для наружных слоев:",
            "value" => $rec['discharge_norm_out'],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "matter_price",
            "label" => "Цена:",
            "value" => $rec['matter_price'],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "invoice",
            "label" => "Накладная/Приходный ордер:",
            "value" => $rec['invoice'],
            "options" => array('html' => ' size=40 '),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "change_act",
            "label" => "Акт перевода:",
            "value" => $rec['change_act'],
            "options" => array('html' => ' size=40 '),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "coment",
            "label" => "Коментарий:",
            "value" => $rec['coment'],
            "options" => array('html' => ' size=40 '),
        ));

        $rec['edit'] = 0; // будем каждый раз записывать новую запись чтобы была история
        $rec['fields'] = $fields;
        return parent::showrec($rec);
    }

}

?>
