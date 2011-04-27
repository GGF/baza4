<?php

/**
 * Description of orders_blocks_view
 *
 * @author igor
 */
class orders_blocks_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
        $fields = array();
        if ($rec[files][file]) {
            foreach ($rec[files][file] as $file) {
                $values[$file[id]] = basename($file[file_link]);
                $value[$file[id]] = 1;
            }
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_CHECKBOXES,
                "name" => "curfile",
                "label" => 'Текущие файлы:',
                "value" => $value,
                "values" => $values,
                    //"options" => array("html" => "readonly",),
            ));
        }
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_FILE,
            "name" => "file",
            "label" => "Добавить файл:",
        ));

        $form->addFields($fields);
        $out = $form->getOutput();
        $out .= $this->addFileButton();
        return $out;
    }

}

?>
