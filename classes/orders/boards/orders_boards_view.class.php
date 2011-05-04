<?php

/**
 * Description of orders_blocks_view
 *
 * @author igor
 */
class orders_boards_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        extract($rec);
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
            "name" => "board_name",
            "label" => "������������ �����",
            "value" => $rec[board_name],
            "options" => array("readonly" => true),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "class",
            "label" => "�����",
            "value" => $rec["class"],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "layers",
            "label" => "�����",
            "value" => $rec[layers],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "size",
            "label" => "������",
            "value" => "{$rec[sizex]}x{$rec[sizey]}",
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "complexity_factor",
            "label" => "����. ���������",
            "value" => $rec[complexity_factor],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "tex�olite",
            "label" => "��������",
            "value" => $rec[tex�olite],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "thickness",
            "label" => "�������",
            "value" => $rec[thickness],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "mask",
            "label" => "�����",
            "value" => $rec[mask],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "mark",
            "label" => "����������",
            "value" => $rec[mark],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "rmark",
            "label" => "������ ����������",
            "value" => $rec[rmark],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "frezcorner",
            "label" => "���������� �����",
            "value" => $rec[frezcorner],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "frez_factor",
            "label" => "����. ��������� ����������",
            "value" => $rec[frez_factor],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "razr",
            "label" => "��������",
            "value" => $rec[razr],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "pallad",
            "label" => "�������������",
            "value" => $rec[pallad],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "immer",
            "label" => "������������ ��������",
            "value" => $rec[immer],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "lamel",
            "label" => "������ (����������-������)",
            "value" => "{$rec[numlam]}-{$rec[lsizex]}x{$rec[lsizey]}",
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXTAREA,
            "name" => "comment",
            "label" => "����������",
            "value" => $rec[comment],
            "options" => array("rows"=>3),
        ));
        $rec[fields] = $fields;
        return parent::showrec($rec);
    }

}

?>
