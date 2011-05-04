<?php

class lanch_zad_view extends sqltable_view {

    // ����������� ���������� ��� ������
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
                "label" => "��������:",
                "value" => $rec["customer"],
                "options" => array("readonly" => true,),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "plate",
                "label" => "�����:",
                "value" => $rec["board_name"],
                "options" => array("readonly" => true,),
            ));
        } else {
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "customer_id",
                "label" => "��������:",
                "values" => $rec[customers],
                "value" => '',
                "options" => array("html" => " customerid "),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "board_id",
                "label" => "�����:",
                "values" => $rec[boards],
                "value" => '',
                "options" => array("html" => " autoupdate-link='{$rec[boardlink]}' autoupdate=customerid ",),
            ));
        }
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "number",
                "label" => '����������:',
                "value" => $rec["number"],
            //"options"	=>	array( "html" => "size=10", ),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "niz",
                "label" => '� ���������:',
                "value" => $rec["niz"],
            //"options"	=>	array( "html" => "size=10", ),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_DATE,
                "name" => "ldate",
                "label" => '����:',
                "value" => $rec[ldate],
            ));

        $form->addFields($fields);

        return $form->getOutput();
    }

}

?>
