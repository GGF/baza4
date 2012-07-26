<?php

class lanch_conduct_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $fields = array();
        $customers = $rec[customers];
        if (!$rec[isnew]) {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "customer_id",
                "value" => $rec["cusid"],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "plate_id",
                "value" => $rec["plid"],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "customer",
                "label" => "Заказчик:",
                "value" => $rec["customer"],
                "options" => array("readonly"=>true,),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "plate",
                "label" => "Плата:",
                "value" => $rec["board_name"],
                "options" => array("readonly"=>true,),
            ));
        } else {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "customer_id",
                "label" => "Заказчик:",
                "values" => $customers,
                "value" => '',
                "options" => array("html" => " autoupdate-link='{$rec[boardlink]}' autoupdate=boardid ",),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "board_id",
                "label" => "Плата:",
                "values" => '',
                "value" => '',
                "options" => array("html" => " boardid=1 "),
            ));
        }
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "pib",
            "label" => "Плат в блоке",
            "value" => $rec["pib"],
            "obligatory"    =>  true,
                //"options"	=>	array( "html" => "size=10", ),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_SELECT,
            "name" => "lays",
            "label" => "Пластин",
            "values" => array("3" => "3", "5" => "5"),
            "value" => $rec["lays"],
                //"options"	=>	array( "html" => "size=10", ),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_SELECT,
            "name" => "side",
            "label" => "Сторона:",
            "values" => array(
                "TOP" => "TOP",
                "BOT" => "BOT",
                "TOPBOT" => "TOPBOT",
            ),
            "value" => $rec["side"],
                //"options"	=>	array( "html" => " side ", ),
        ));

        $rec["fields"] = $fields;

        return parent::showrec($rec);
    }

}

?>
