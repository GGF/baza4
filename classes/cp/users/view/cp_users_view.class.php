<?php

class cp_users_view extends sqltable_view {

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
                "name" => "nik",
                "label" => "���:",
                "value" => $rec["nik"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "fullname",
                "label" => "������ ���:",
                "value" => $rec["fullname"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "position",
                "label" => "���������:",
                "value" => $rec["position"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "password1",
                "label" => "������:",
                "value" => $rec["password"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "password2",
                "label" => "������ ������",
                "value" => $rec["password"],
            ),
            array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "action",
                "value" => $rec['do'],
            ),
        );
        $form->addFields($fields);

        return $form->getOutput();
    }

    public function showrigths($rec) {
        $uid = $rec[edit];
        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
        foreach ($rec[types] as $key => $val) {
            $label = sprintf("<span id='rrr' rtype='{$val["type"]}'>[%-10s]</span>:", $val["type"]);
            $form->addFields(array(
                array(
                    "type" => AJAXFORM_TYPE_CHECKBOXES,
                    "name" => $val[name],
                    "label" => $label,
                    "value" => $val[value],
                    "values" => $val[values],
                    "options" => array("nobr" => true, "html" => " rtype=" . $val["type"] . " "),),
            ));
        }
        $form->addFields(array(
            array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "userid",
                "value" => $uid,
            ),
        ));
        $form->addFields(array(
            array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "action",
                "value" => $rec['do'],
            ),
        ));
        $out = $form->getOutput();
        $out .= "<script>\$('#rrr').live('click',function(){\$(':checkbox[rtype='+\$(this).attr('rtype')+']').attr('checked',true);});</script>";
        $out .= "<script>\$('#rrr').live('dblclick',function(){\$(':checkbox[rtype='+\$(this).attr('rtype')+']').attr('checked',false);});</script>";
        return $out;
    }

}

?>
