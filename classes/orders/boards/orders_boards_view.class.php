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
            "label" => "Заказчик:",
            "value" => $rec["customer"],
            "options" => array("readonly" => true,),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "board_name",
            "label" => "Наименование блока",
            "value" => $rec[board_name],
            "options" => array("readonly" => true),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "class",
            "label" => "Класс",
            "value" => $rec["class"],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "layers",
            "label" => "Слоев",
            "value" => $rec[layers],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "size",
            "label" => "Размер",
            "value" => "{$rec[sizex]}x{$rec[sizey]}",
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "complexity_factor",
            "label" => "Коэф. сложности",
            "value" => $rec[complexity_factor],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "textolite",
            "label" => "Материал",
            "value" => $rec[textolite],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "thickness",
            "label" => "Толщина",
            "value" => $rec[thickness],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "mask",
            "label" => "Маска",
            "value" => $rec[mask],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "mark",
            "label" => "Маркировка",
            "value" => $rec[mark],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "rmark",
            "label" => "Ручная маркировка",
            "value" => $rec[rmark],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "frezcorner",
            "label" => "Фрезеровка углов",
            "value" => $rec[frezcorner],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "frez_factor",
            "label" => "Коэф. сложности фрезеровки",
            "value" => $rec[frez_factor],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "razr",
            "label" => "Разрубка",
            "value" => $rec[razr],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "pallad",
            "label" => "Паладирование",
            "value" => $rec[pallad],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_CHECKBOX,
            "name" => "immer",
            "label" => "Иммерсионное покрытие",
            "value" => $rec[immer],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "lamel",
            "label" => "Ламели (количество-размер)",
            "value" => "{$rec[numlam]}-{$rec[lsizex]}x{$rec[lsizey]}",
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXTAREA,
            "name" => "comment",
            "label" => "Коментарий",
            "value" => $rec[comment],
            "options" => array("rows"=>3),
        ));
        $rec[fields] = $fields;
        $rec[files]=true;
        return parent::showrec($rec);
    }

}

?>
