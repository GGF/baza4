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
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "customerid",
                "label" => "Заказчик:",
                "values" => $rec[customers],
            ));
        } else {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "customerid",
                "value" => !empty($_SESSION[customer_id]) ? $_SESSION[customer_id] : $rec["customer_id"],
            ));
        }

        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "orderdate",
            "label" => 'Дата:',
            "value" => sql::date2datepicker($rec[orderdate]),
            "options" => array("html" => ' datepicker=1 '),
            "check" => array("type" => AJAXFORM_CHECK_NUMERIC),
            "format" => array("type" => AJAXFORM_FORMAT_CUSTOM, "pregPattern" => "/[0-9][0-9]\.[0-9][0-9]\.[0-9][0-9][0-9][0-9]/"),
            "obligatory" => true,
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "number",
            "label" => "Номер письма:",
            "value" => $rec["number"],
            "options" => array("html" => "size=30",),
            "obligatory" => true,
        ));
        if ($rec[files][file]) {
            foreach ($rec[files][file] as $file) {
                $values[$file[id]] = basename($file[file_link]);
                $value[$file[id]] = 1;
            }
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_CHECKBOXES,
                "name" => "curfile",
                "label" => 'Текущие файлы:',
                "value" => $value,
                "values" => $values,
                    //"options" => array("html" => "readonly",),
            ));
        }
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_FILE,
            "name" => "order_file",
            "label" => "Добавить файл:",
        ));

        $form->addFields($fields);
        $out = $form->getOutput();
        $out .= $this->addFileButton();
        return $out;
    }

}

?>
