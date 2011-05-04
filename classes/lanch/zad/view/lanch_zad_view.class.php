<?php

class lanch_zad_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
        $fields = array();
        if (!$rec[isnew]) {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "customer_id",
                "value" => $rec["cusid"],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "board_id",
                "value" => $rec["board_id"],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "customer",
                "label" => "Заказчик:",
                "value" => $rec["customer"],
                "options" => array("readonly" => true,),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "plate",
                "label" => "Плата:",
                "value" => $rec["board_name"],
                "options" => array("readonly" => true,),
            ));
        } else {
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "customer_id",
                "label" => "Заказчик:",
                "values" => $rec[customers],
                "value" => '',
                "options" => array("html" => " customerid "),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "board_id",
                "label" => "Плата:",
                "values" => $rec[boards],
                "value" => '',
                "options" => array("html" => " autoupdate-link='{$rec[boardlink]}' autoupdate=customerid ",),
            ));
        }
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "number",
                "label" => 'Количество:',
                "value" => $rec["number"],
            //"options"	=>	array( "html" => "size=10", ),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "niz",
                "label" => '№ извещения:',
                "value" => $rec["niz"],
            //"options"	=>	array( "html" => "size=10", ),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_DATE,
                "name" => "ldate",
                "label" => 'Дата:',
                "value" => $rec[ldate],
            ));

        $form->addFields($fields);

        return $form->getOutput();
    }

}

?>
