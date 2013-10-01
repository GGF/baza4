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
        $fields = array();
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "customer",
            "label" => "Заказчик",
            "value" => $rec["customer"],
            "options" => array("readonly" => true,),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "blockname",
            "label" => "Наименование блока",
            "value" => $rec[blockname],
            "options" => array("readonly" => true),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "size",
            "label" => "Размер блока",
            "value" => "{$rec[sizex]}x{$rec[sizey]}",
            "options" => array("readonly" => true),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "thickness",
            "label" => "Толщина блока",
            "value" => $rec[thickness],
            "options" => array("readonly" => true),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "copper",
            "label" => "Площади блока",
            "value" => sprintf("C=%-6.2f S=%-6.2f Au=%-6.2f", $rec[scomp] / 10000, $rec[ssolder] / 10000, $rec[auarea]),
            "options" => array("readonly" => true, "html" => 'size=25'),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "drills",
            "label" => "Отверстия блока",
            "value" => sprintf("%-6d / %-6d ", $rec[smalldrill], $rec[bigdrill]),
            "options" => array("readonly" => true),
        ));

        $i=0;
        foreach ($rec[blockpos] as $pos) {
            $i++;
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "pos{$i}",
                "label" => "Плата {$i}",
                "value" => $pos[board_name],
                "options" => array("readonly" => true),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "nib{$i}",
                "label" => "Шт на блоке",
                "value" => $pos[nib],
                "options" => array("readonly" => true),
            ));
        }
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXTAREA,
            "name" => "comment",
            "label" => "Коментарий",
            "value" => $rec[comment],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_HIDDEN,
            "name" => "comment_id",
            "value" => $rec[comment_id],
        ));
        $rec[fields] = $fields;
        return parent::showrec($rec);
    }

}

?>
