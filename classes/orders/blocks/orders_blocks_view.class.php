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
            "label" => "��������:",
            "value" => $rec["customer"],
            "options" => array("readonly" => true,),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "blockname",
            "label" => "������������ �����",
            "value" => $rec[blockname],
            "options" => array("readonly" => true),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "size",
            "label" => "������ �����",
            "value" => "{$rec[sizex]}x{$rec[sizey]}",
            "options" => array("readonly" => true),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "thickness",
            "label" => "������� �����",
            "value" => $rec[thickness],
            "options" => array("readonly" => true),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "copper",
            "label" => "������� �����",
            "value" => sprintf("C=%-6.2f S=%-6.2f", $rec[scomp] / 10000, $rec[ssolder] / 10000),
            "options" => array("readonly" => true),
        ));

        $i=0;
        foreach ($rec[blockpos] as $pos) {
            $i++;
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "pos{$i}",
                "label" => "����� {$i}",
                "value" => $pos[board_name],
                "options" => array("readonly" => true),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "nib{$i}",
                "label" => "�� �� �����",
                "value" => $pos[nib],
                "options" => array("readonly" => true),
            ));
//            array_push($fields, array(
//                "type" => AJAXFORM_TYPE_TEXT,
//                "name" => "n{$i}",
//                "label" => "",
//                "value" => "{$pos[nx]}x{$pos[ny]}",
//            ));
        }
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXTAREA,
            "name" => "comment",
            "label" => "����������",
            "value" => $rec[comment],
        ));
        $rec[fields] = $fields;
        return parent::showrec($rec);
    }

}

?>
