<?php

class orders_customers_view extends sqltable_view {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
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
        $form->addFields($fields);

        return $form->getOutput();
    }

}

?>
