<?php

class orders_customers_view extends sqltable_view {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $fields = array(
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "customer",
                "label" => "������� �������� (��� ��������):",
                "value" => $rec["customer"],
            //"options"	=>	array( "html" => "size=10", ),
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "fullname",
                "label" => "������ �������� (��� ����������):",
                "value" => $rec["fullname"],
                "options" => array("html" => "size=60",),
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "kdir",
                "label" => "������� �� ����� � (��� ���������):",
                "value" => $rec["kdir"],
            ),
        );
        $rec[fields] = $fields;

        return parent::showrec($rec);
    }

}

?>
