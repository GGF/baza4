<?php

class productioncard_dpp_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        $rec[fields] = array(
            array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "coment_id",
                "label" => 'Сопроводительный лист',
                "value" => $rec["coment_id"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "lanch_id",
                "label" => 'Сопроводительный лист',
                "value" => $rec["lanch_id"],
            ),
            array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "operation_id",
                "label" => 'Тип',
                "values" => $rec[operations],
                "options" => array("html" => " autoupdate-link='{$rec[commetnpupdatelink]}' autoupdate=commentid ",),
                "value" => '',
            ),
            array(
                "type" => AJAXFORM_TYPE_DATE,
                "name" => "action_date",
                "label" => 'Дата',
                "options" => array("html" => ' datepicker=1 '),
                "value" => '',
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXTAREA,
                "name" => "comment",
                "label" => 'Коментарий',
                "value" => '',
                "options" => array("html" => " commentid=1 "),
            ),

        );
        $rec[files] = false;
        return parent::showrec($rec);
    }

}

?>
