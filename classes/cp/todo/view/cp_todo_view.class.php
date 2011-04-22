<?php

class cp_todo_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
        $fields = array();
        $form->addFields(array(
            array(
                "type" => AJAXFORM_TYPE_TEXTAREA,
                "name" => "what",
                "label" => '',
                "value" => $rec["what"],
                "options" => array("rows" => "10", "html" => " cols=50 onfocus='$(this).wysiwyg();' ",),
            ),
        ));
        $form->addFields($fields);
        return $form->getOutput();
    }

}

?>
