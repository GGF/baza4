<?php

class orders_customers_view extends sqltable_view {

    public function showrec($rec) {

        $fields = array(
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "customer",
                "label" => "Краткое название (имя каталога):",
                "value" => $rec["customer"],
            //"options"	=>	array( "html" => "size=10", ),
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "fullname",
                "label" => "Полное название (для теззаданий):",
                "value" => $rec["fullname"],
                "options" => array("html" => "size=60",),
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "kdir",
                "label" => "Каталог на диске К (для сверловок):",
                "value" => $rec["kdir"],
            ),
        );
        $rec[fields] = $fields;
        return parent::showrec($rec);
    }

}

?>
