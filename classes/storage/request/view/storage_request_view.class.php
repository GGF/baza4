<?php

/**
 * Description of storage_request_view
 *
 * @author igor
 */
class storage_request_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function getTitle($data) {
        $out = 'Требования за:';
        $out.= '<select name=ddate id=selectrequestdate>';
        foreach ($data[dates] as $rs) {
            $out.="<option value='{$rs[ddate]}'";
            if (!empty($data[ddate])) {
                $out.= $data[ddate] == $rs["ddate"] ? "SELECTED" : "";
            }
            $out.=">" . sprintf("%s", $rs["ddate"]) . "</option>";
        }
        $out.="</select>";
        $out.="<table width=100%><tr><td style='border:0'>Через кого:<td style='border:0'>
                <select name=cherezkogo>
                <option value=''></option>
                <optgroup label='Красная группа'>
                <option value='Балуков А.Н.' style='color:red;'>Балуков А.Н.</option>
                <option value='Куренков Л.Е.' style='color:red;'>Куренков Л.Е.</option>
                <option value='Тимофеев В.В.' style='color:red;'>Тимофеев В.В.</option>
                </optgroup>
                <optgroup label='Синяя группа'>
                <option value='Грималовская Г.А.' style='color:blue;'>Грималовская Г.А.</option>
                <option value='Егорычева Т.В.' style='color:blue;'>Егорычева Т.В.</option>
                <option value='Курочкина М.А.' style='color:blue;'>Курочкина М.А.</option>
                <option value='Левитская Н.П.' style='color:blue;'>Левитская Н.П.</option>
                <option value='Разина Е.П.' style='color:blue;'>Разина Е.П.</option>
                <option value='Угдыжекова И.В.' style='color:blue;'>Угдыжекова И.В.</option>
                <option value='Ходина Е.А.' style='color:blue;'>Ходина Е.А.</option>
                <option value='Чистякова И.Н.' style='color:blue;'>Чистякова И.Н.</option>
                <option value='Шамарина В.В.' style='color:blue;'>Шамарина В.В.</option>
                </optgroup>
                <optgroup label='Зеленая группа'>
                <option value='Власова Т.В.' style='color:green;'>Власова Т.В.</option>
                <option value='Полушкин В.Ю.' style='color:green;'>Полушкин В.Ю.</option>
                <option value='Фёдоров И.Ю.' style='color:green;'>Фёдоров И.Ю.</option>
                </optgroup>
                <optgroup label='Черная группа'>
                <option value='Большакова А.В.' style='color:black;'>Большакова А.В.</option>
                <option value='Васильев С.Б.' style='color:black;'>Васильев С.Б.</option>
                <option value='Владимирова Н.В.' style='color:black;'>Владимирова Н.В.</option>
                <option value='Власова И.Ф.' style='color:black;'>Власова И.Ф.</option>
                <option value='Евдокимов Д.А.' style='color:black;'>Евдокимов Д.А.</option>
                <option value='Игнатьев С.Н.' style='color:black;'>Игнатьев С.Н.</option>
                <option value='Китуничев Д.С.' style='color:black;'>Китуничев Д.С.</option>
                <option value='Легоньков В.А.' style='color:black;'>Легоньков В.А.</option>
                <option value='Орлова Н.Н.' style='color:black;'>Орлова Н.Н.</option>
                <option value='Потапова Л.В.' style='color:black;'>Потапова Л.В.</option>
                <option value='Салангина И.Г.' style='color:black;'>Салангина И.Г.</option>
                <option value='Соковнин С.А.' style='color:black;'>Соковнин С.А.</option>
                </optgroup>
                <optgroup label='Светлозеленая группа'>
                <option value='Жинкин А.И.' style='color:lightgreen;'>Жинкин А.И.</option>
                </optgroup>
                </select>
                <td style='border:0'>Разрешил:<td style='border:0'>
                <select name=razresh>
                <option value='Китуничев Д.С.' style='color:black;'>Китуничев Д.С.</option>
                <option value='Николайчук И.И.' style='color:black;'>Николайчук И.И.</option>
                <option value='' style='color:black;'></option>
                </select>
                <td style='border:0'>Затребовал:<td style='border:0'>
                <select name=zatreb>
                <option value=''></option>
                <optgroup label='Красная группа'>
                <option value='Мещанинов В.Ф.' style='color:red;'>Мещанинов В.Ф.</option>
                <option value='Тимофеев В.В.' style='color:red;'>Тимофеев В.В.</option>
                </optgroup>
                <optgroup label='Синяя группа'>
                <option value='Соколова В.М.' style='color:blue;'>Соколова В.М.</option>
                <option value='Угдыжекова И.В.' style='color:blue;'>Угдыжекова И.В.</option>
                </optgroup>
                <optgroup label='Зеленая группа'>
                <option value='Смирнов В.А.' style='color:green;'>Смирнов В.А.</option>
                <option value='Фёдоров И.Ю.' style='color:green;'>Фёдоров И.Ю.</option>
                </optgroup>
                <optgroup label='Черная группа'>
                <option value='Михайлов В.Н.' style='color:black;'>Михайлов В.Н.</option>
                <option value='Макарова Т.Л.' style='color:black;'>Макарова Т.Л.</option>
                </optgroup>
                </select>
                </table>";
        $out.="<input type=button id=requestbutton value='Печать' >";
        return $out;
    }

    public function showrec($rec) {
        extract($rec);
        $top = file_get_contents($this->getDir() . "/treb.tpl");
        $middle = file_get_contents($this->getDir() . "/row.tpl");
        $bot = file_get_contents($this->getDir() . "/bottom.tpl");

        $buffer = $top;
        $buffer = str_replace("_nomer_", '', $buffer);
        $buffer = str_replace("_date_", date("d.m.Y", mktime(0, 0, 0, ceil(substr($ddate, 5, 2)), ceil(substr($ddate, 8, 2)), ceil(substr($ddate, 0, 4)))), $buffer);
        $buffer = str_replace("_cherezkogo_", multibyte::UTF_decode($cherezkogo), $buffer);
        $buffer = str_replace("_zatreb_", multibyte::UTF_decode($zatreb), $buffer);
        $buffer = str_replace("_razresh_", multibyte::UTF_decode($razresh), $buffer);
        //echo $buffer;
        foreach ($positions as $rs) {
            $buffer .= $middle;
            $buffer = str_replace("_nazv_", $rs["nazv"], $buffer);
            $buffer = str_replace("_edizm_", $rs["edizm"], $buffer);
            $buffer = str_replace("_otp_", $rs["quant"], $buffer);
        }
        $buffer .= $bot;
        $buffer = str_replace("_cherezkogo_", multibyte::UTF_decode($cherezkogo), $buffer);
        return $buffer;
    }

}

?>
