<?php

class orders_money_view extends sqltable_view {

    public function showrec($rec) {

        extract($rec);
        if(is_array($_SESSION[Auth::$lss])) extract($_SESSION[Auth::$lss]);//list($customer_id,$order_id,$tz_id,$posintzid) = explode(':',$idstr);
        $fields = array();
/*
        if (empty($rec[edit]) && empty($customer_id)) {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "customer_id",
                "label" => "Заказчик:",
                "values" => $rec[customers],
            ));
        } else {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "customer_id",
                "value" => !empty($customer_id) ? $customer_id : $rec["customer_id"],
            ));
        }

        array_push($fields, array(
            "type" => AJAXFORM_TYPE_DATE,
            "name" => "orderdate",
            "label" => 'Дата:',
            "value" => $rec[orderdate],
            "obligatory" => true,
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "number",
            "label" => "Номер письма:",
            "value" => $rec["number"],
            "options" => array("html" => "size=30",),
            "obligatory" => true,
        ));*/
        $rec['fields'] = $fields;
        //$rec[files]=$this->owner->model->getFiles;
        return parent::showrec($rec);
    }

}

?>
