<?php

class orders_customers_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
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
        $form->addFields($fields);

        return $form->getOutput();
    }

}

?>
