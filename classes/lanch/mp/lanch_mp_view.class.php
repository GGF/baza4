<?php

/**
 * Description of lanch_mp_view
 *
 * @author Игорь
 */
class lanch_mp_view extends sqltable_view {

    /**
     * Показ записи
     * @var array $rec - передает данные из модели
     * 
     */
    public function showrec($rec) {
        $fields = array(); // пустые поля
        if (!$rec[isnew]) { //если запись не новая то просто пропише заказчика и плату
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
                "label" => "Заказчик:",
                "value" => $rec["customer"]["customer"],
                "options" => array("readonly" => true,),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "plate",
                "label" => "Плата:",
                "value" => $rec["block"]["blockname"],
                "options" => array("readonly" => true,),
            ));
            $rec = $rec["customer"]["customer"] . " - " . $rec["block"]["blockname"];

        } else { // иначе выберем
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "customer_id",
                "label" => "Заказчик:",
                "values" => $rec[customers],
                "value" => '',
                "options" => array("html" => " autoupdate-link='{$rec[boardlink]}' autoupdate=boardid ",),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "board_id",
                "label" => "Плата:",
                "values" => $rec[boards],
                "value" => '',
                "options" => array("html" => " boardid "),
            ));
            $rec["fields"] = $fields;
        }
       
        return parent::showrec($rec);
    }}

?>
