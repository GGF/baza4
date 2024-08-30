<?php
/**
 * Представление для типов пользовательских настроек
 *
 * @author igor
 */
class setting_show_view extends sqltable_view {
    
    public function showrec($rec) {
        //$out = print_r($rec,true);
        $out = '';
        if (!empty($rec['id'])) {
            $out .= "{$rec['description']} : <input type=text name='{$rec['key']}' id='{$rec['key']}' size='20'>";
            $out .= "<input type='button' value='save' id='storebutton'>";
            $out .= '<script>';
            $out .= "$('#{$rec['key']}').val(localStorage.getItem('{$rec['key']}'));";
            $out .= "$('#storebutton').click(function(){ localStorage.setItem('{$rec['key']}',$('#{$rec['key']}').val());});";
            $out .= '</script>';
            return $out;
        } else {
            $fields = array();
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "key",
                "label" => "Имя ключа:",
                "value" => "",
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "description",
                "label" => "Описание:",
                "value" => "",
            ));
            $rec['fields'] = $fields;
            $rec['files'] = false;
            return parent::showrec($rec);
        }
    }
    
}

?>
