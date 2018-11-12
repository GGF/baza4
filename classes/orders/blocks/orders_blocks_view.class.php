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
            "name" => "drlname",
            "label" => "Имя сверловки",
            "value" => $rec[drlname],
            //"options" => array("readonly" => true),
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
        $layers=0;
        foreach ($rec[blockpos] as $pos) {
            $i++;
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "poss{$i}",
                "label" => "Плата {$i}",
                "value" => $pos[board_name],
                "options" => array("readonly" => true),
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "nibb{$i}",
                "label" => "На блоке",
                "value" => sprintf("%d %gx%g",$pos[nib],$pos[sizex],$pos[sizey]),
                "options" => array("readonly" => true),
            ));
            $layers = max(array($layers,$pos["layers"]));
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
        // если многослойка добавляем параметры
        if ($layers>2) {
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "class",
                "label" => "Класс",
                "value" => $rec["param"]["class"],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "basemat",
                "label" => "Базовый материал",
                "value" => $rec[param][basemat],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "sttkan",
                "label" => "Стеклоткань",
                "value" => $rec[param][sttkan],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "sttkankl",
                "label" => "Кол-во листов",
                "value" => $rec[param][sttkankl],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "sttkanrasp",
                "label" => "Распределение",
                "value" => $rec[param][sttkanrasp],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "rtolsh",
                "label" => "Расчетная толщ.",
                "value" => $rec[param][rtolsh],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "filext",
                "label" => "Расш файла сверл.",
                "value" => $rec[param][filext],
            ));
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_TEXT,
                "name" => "elkon",
                "label" => "Электроконтроль",
                "value" => $rec[param][elkon],
            ));
            // слои
            for($i=1;$i<11;$i++) {
                array_push($fields, array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "sl{$i}",
                    "label" => "Слои{$i}",
                    "value" => $rec[param]["sl{$i}"],
                ));
                array_push($fields, array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "mat{$i}",
                    "label" => "Материал слоев",
                    "value" => $rec[param]["mat{$i}"],
                ));
                array_push($fields, array(
                    "type" => AJAXFORM_TYPE_TEXT,
                    "name" => "pr{$i}",
                    "label" => "Проводники",
                    "value" => $rec[param]["pr{$i}"],
                ));
                if ($i==4 || $i==7) {
                    array_push($fields, array(
                        "type" => AJAXFORM_TYPE_TEXT,
                        "name" => "foo{$i}",
                        "label" => "Еще параметр для выравнивания формы",
                        "value" => "",
                        "options" => array("readonly" => true, "html" => 'size=2'),
                    ));            
                    array_push($fields, array(
                        "type" => AJAXFORM_TYPE_TEXT,
                        "name" => "bar{$i}",
                        "label" => "Еще параметр для выравнивания формы",
                        "value" => "",
                        "options" => array("readonly" => true, "html" => 'size=2'),
                    ));            

                }
            }

                        
        }
        $rec[fields] = $fields;
        return parent::showrec($rec);
    }

}

?>
