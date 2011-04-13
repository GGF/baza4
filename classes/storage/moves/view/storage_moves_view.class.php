<?php

class storage_moves_view extends sqltable_view {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $rec[fields] = array();
        //console::getInstance()->out(print_r($rec));
        $date=(!empty($rec[edit])?date("d.m.Y",mktime(0,0,0,ceil(substr($rec["ddate"],5,2)),ceil(substr($rec["ddate"],8,2)),ceil(substr($rec["ddate"],1,4)))):date("d.m.Y"));
        array_push($rec[fields],
                array(
                    "type" => AJAXFORM_TYPE_HIDDEN,
                    "name" => "spr_id",
                    "value" => $rec["spr_id"],
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "ddate",
                    "label" => '����:',
                    "value" => $date,
                    "options" => array("html" => ' datepicker=1 '),
                ),
                array(
                    "type" => AJAXFORM_TYPE_SELECT,
                    "name" => "type",
                    "label" => "��� ���������:",
                    "values" => array(
                        "1" => "������",
                        "0" => "������",
                    ),
                    "value" => $rec["type"],
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "numd",
                    "label" => '����� ���������:',
                    "value" => $rec["numd"],
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "quant",
                    "label" => '����������:',
                    "value" => $rec["quant"],
                ),
                array(
                    "type" => AJAXFORM_TYPE_SELECT,
                    "name" => "supply_id",
                    "label" => "���������:",
                    "values" => $rec[supply],
                    "value" => $rec["supply_id"],
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "supply",
                    "label" => '�����:',
                    "value" => "",
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "price",
                    "label" => '���������:',
                    "value" => $rec["price"],
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXTAREA,
                    "name" => "comment",
                    "label" => '�����������:',
                    "value" => $rec["comment"],
                //"options"	=>	array( "html" => "size=70", ),
                )
        );
        return parent::showrec($rec);
    }

}

?>
