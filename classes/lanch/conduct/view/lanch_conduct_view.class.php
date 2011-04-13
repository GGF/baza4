<?php

class lanch_conduct_view extends sqltable_view {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
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
                "label" => "��������:",
                "value" => $rec["customer"],
                "options" => array("html" => " readonly ",),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "plate",
                "label" => "�����:",
                "value" => $rec["board_name"],
                "options" => array("html" => " readonly ",),
            ));
        } else {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "customer_id",
                "label" => "��������:",
                "values" => $customers,
                "value" => '',
                "options" => array("html" => " customerid "),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "board_id",
                "label" => "�����:",
                "values" => '',
                "value" => '',
                "options" => array("html" => " autoupdate-link='{$rec[boardlink]}' autoupdate=customerid ",),
            ));
        }
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "pib",
            "label" => "���� � �����",
            "value" => $rec["pib"],
                //"options"	=>	array( "html" => "size=10", ),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_SELECT,
            "name" => "lays",
            "label" => "�������",
            "values" => array("3" => "3", "5" => "5"),
            "value" => $rec["lays"],
                //"options"	=>	array( "html" => "size=10", ),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_SELECT,
            "name" => "side",
            "label" => "�������:",
            "values" => array(
                "TOP" => "TOP",
                "BOT" => "BOT",
                "TOPBOT" => "TOPBOT",
            ),
            "value" => $rec["side"],
                //"options"	=>	array( "html" => " side ", ),
        ));

        $form->addFields($fields);

        return $form->getOutput();
    }

}

?>
