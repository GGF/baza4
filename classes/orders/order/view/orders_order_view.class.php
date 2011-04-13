<?php

class orders_order_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
        $fields = array();
        if (empty($rec[edit]) && empty($_SESSION[customer_id])) {
            array_push($fields,
                    array(
                        "type" => AJAXFORM_TYPE_SELECT,
                        "name" => "customerid",
                        "label" => "Заказчик:",
                        "values" => $rec[customers],
            ));
        } else {
            array_push($fields,
                    array(
                        "type" => AJAXFORM_TYPE_HIDDEN,
                        "name" => "customerid",
                        "value" => !empty($_SESSION[customer_id]) ? $_SESSION[customer_id] : $rec["customer_id"],
            ));
        }
        array_push($fields,
                array(
                    "type" => AJAXFORM_TYPE_HIDDEN,
                    "name" => "fileid",
                    "value" => $rec["filelink"],
        ));
        array_push($fields,
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "orderdate",
                    "label" => 'Дата:',
                    "value" => sql::date2datepicker($rec[orderdate]),
                    "options" => array("html" => ' datepicker=1 '),
                    "check" => array("type" => AJAXFORM_CHECK_NUMERIC),
                    "format" => array("type" => AJAXFORM_FORMAT_CUSTOM, "pregPattern" => "/[0-9][0-9]\.[0-9][0-9]\.[0-9][0-9][0-9][0-9]/"),
                    "obligatory" => true,
        ));
        array_push($fields,
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "number",
                    "label" => "Номер письма:",
                    "value" => $rec["number"],
                    "options" => array("html" => "size=30",),
                    "obligatory" => true,
        ));
        array_push($fields,
                array(
                    "type" => AJAXFORM_TYPE_FILE,
                    "name" => "order_file",
                    "label" => "Файл письма:",
        ));
        array_push($fields,
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "curfile",
                    "label" => "Текущий файл:",
                    "value" => basename($this->owner->model->getFileNameById($rec["filelink"])),
                    "options" => array("html" => "readonly",),
        ));

        $form->addFields($fields);

        return $form->getOutput();
    }

}

?>
