<?php

class cp_operations_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        $rec[fields] = array(
            array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "block_type",
                "label" => 'Тип',
                "values" => array(dpp => 'dpp',mpp => 'mpp', both => 'both'),
                "value" => $rec["block_type"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "operation",
                "label" => 'Операция',
                "value" => $rec["operation"],
            ),
        );
        $rec[files] = false;
        return parent::showrec($rec);
    }

}

?>
