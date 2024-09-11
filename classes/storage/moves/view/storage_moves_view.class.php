<?php

class storage_moves_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $rec['fields'] = array();
        $date=(!empty($rec['edit'])?date("d.m.Y",mktime(0,0,0,ceil((float)substr($rec["ddate"],5,2)),ceil((float)substr($rec["ddate"],8,2)),ceil((float)substr($rec["ddate"],1,4))))
                                                        :date("d.m.Y"));
        array_push($rec['fields'],
                array(
                    "type" => AJAXFORM_TYPE_HIDDEN,
                    "name" => "spr_id",
                    "value" => $rec["idstr"],
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "ddate",
                    "label" => 'Дата:',
                    "value" => $date,
                    "options" => array("html" => ' datepicker=1 '),
                ),
                array(
                    "type" => AJAXFORM_TYPE_SELECT,
                    "name" => "type",
                    "label" => "Тип документа:",
                    "values" => array(
                        "1" => "Приход",
                        "0" => "Расход",
                    ),
                    "value" => $rec["type"],
                    "options" => array("html"=>" autohide=1 myid=prselect "),
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "numd",
                    "label" => 'Номер документа:',
                    "value" => $rec["numd"],
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "quant",
                    "label" => 'Количество:',
                    "value" => isset($rec['quant'])?$rec['quant']:0,
                ),
                array(
                    "type" => AJAXFORM_TYPE_HIDDEN, //AJAXFORM_TYPE_SELECT,
                    "name" => "supply_id",
                    "label" => "Поставщик:",
                    "values" => $rec['supply'],
                    "value" => $rec["supply_id"],
                    "options" => array("html"=>" autohide=1 myid=supply "),
                ),
                array(
                    "type" => AJAXFORM_TYPE_HIDDEN, //AJAXFORM_TYPE_TEXT,
                    "name" => "supply",
                    "label" => 'Новый:',
                    "value" => "",
                ),
                array(
                    "type" => AJAXFORM_TYPE_HIDDEN, //AJAXFORM_TYPE_TEXT,
                    "name" => "price",
                    "label" => 'Стоимость:',
                    "value" => isset($rec['price'])?$rec['price']:0.0,
                ),
                array(
                    "type" => AJAXFORM_TYPE_TEXTAREA,
                    "name" => "comment",
                    "label" => 'Комментарий:',
                    "value" => $rec["comment"],
                //"options"	=>	array( "html" => "size=70", ),
                )
        );
        $out = parent::showrec($rec);
        $out .= "<script>$('select[autohide=1]').trigger('change');</script>";
        return $out;
    }

}

?>
