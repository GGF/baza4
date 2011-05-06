<?php

class cp_users_view extends sqltable_view {

    // обязательно определять для модуля
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
                "label" => "Ник:",
                "value" => $rec["nik"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "fullname",
                "label" => "Полное имя:",
                "value" => $rec["fullname"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "position",
                "label" => "Должность:",
                "value" => $rec["position"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "password1",
                "label" => "Пароль:",
                "value" => $rec["password"],
            ),
            array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "password2",
                "label" => "Повтор пароля",
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
            $label = sprintf("<span id='rrr' rtype='{$val["type"]}'>[%-25s]</span>:", $val["type"]);
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
        $out .= "<script>\$('#rrr').live('click',function(){\$(':checkbox[rtype='+\$(this).attr('rtype')+']').prop('checked',true);});</script>";
        $out .= "<script>\$('#rrr').live('contextmenu',function(){\$(':checkbox[rtype='+\$(this).attr('rtype')+']').prop('checked',false);return false;});</script>";
        return $out;
    }

}

?>
