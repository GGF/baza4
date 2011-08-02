<?php

class orders_order_view extends sqltable_view {

//    // обязательно определять для модуля
//    public function getDir() {
//        return __DIR__;
//    }

    public function showrec($rec) {

        extract($rec);
        list($customer_id,$order_id,$tz_id,$posintzid) = explode(':',$idstr);
        $fields = array();
        if (empty($rec[edit]) && empty($customer_id)) {
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
        ));
        $rec[fields] = $fields;
        //$rec[files]=$this->owner->model->getFiles;
        return parent::showrec($rec);
    }

}

?>
