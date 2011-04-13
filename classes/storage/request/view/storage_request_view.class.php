<?php

/**
 * Description of storage_request_view
 *
 * @author igor
 */
class storage_request_view extends sqltable_view {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function getTitle($data) {
        $out = '���������� ��:';
        $out.= '<select name=ddate id=selectrequestdate>';
        foreach ($data[dates] as $rs) {
            $out.="<option value='{$rs[ddate]}'";
            if (!empty($data[ddate])) {
                $out.= $data[ddate] == $rs["ddate"] ? "SELECTED" : "";
            }
            $out.=">" . sprintf("%s", $rs["ddate"]) . "</option>";
        }
        $out.="</select>";
        $out.="<table width=100%><tr><td style='border:0'>����� ����:<td style='border:0'>
                <select name=cherezkogo>
                <option value=''></option>
                <optgroup label='������� ������'>
                <option value='������� �.�.' style='color:red;'>������� �.�.</option>
                <option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
                <option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
                </optgroup>
                <optgroup label='����� ������'>
                <option value='������������ �.�.' style='color:blue;'>������������ �.�.</option>
                <option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
                <option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
                <option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
                <option value='������ �.�.' style='color:blue;'>������ �.�.</option>
                <option value='���������� �.�.' style='color:blue;'>���������� �.�.</option>
                <option value='������ �.�.' style='color:blue;'>������ �.�.</option>
                <option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
                <option value='�������� �.�.' style='color:blue;'>�������� �.�.</option>
                </optgroup>
                <optgroup label='������� ������'>
                <option value='������� �.�.' style='color:green;'>������� �.�.</option>
                <option value='�������� �.�.' style='color:green;'>�������� �.�.</option>
                <option value='Ը����� �.�.' style='color:green;'>Ը����� �.�.</option>
                </optgroup>
                <optgroup label='������ ������'>
                <option value='���������� �.�.' style='color:black;'>���������� �.�.</option>
                <option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
                <option value='����������� �.�.' style='color:black;'>����������� �.�.</option>
                <option value='������� �.�.' style='color:black;'>������� �.�.</option>
                <option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
                <option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
                <option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
                <option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
                <option value='������ �.�.' style='color:black;'>������ �.�.</option>
                <option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
                <option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
                <option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
                </optgroup>
                <optgroup label='������������� ������'>
                <option value='������ �.�.' style='color:lightgreen;'>������ �.�.</option>
                </optgroup>
                </select>
                <td style='border:0'>��������:<td style='border:0'>
                <select name=razresh>
                <option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
                <option value='���������� �.�.' style='color:black;'>���������� �.�.</option>
                <option value='' style='color:black;'></option>
                </select>
                <td style='border:0'>����������:<td style='border:0'>
                <select name=zatreb>
                <option value=''></option>
                <optgroup label='������� ������'>
                <option value='��������� �.�.' style='color:red;'>��������� �.�.</option>
                <option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
                </optgroup>
                <optgroup label='����� ������'>
                <option value='�������� �.�.' style='color:blue;'>�������� �.�.</option>
                <option value='���������� �.�.' style='color:blue;'>���������� �.�.</option>
                </optgroup>
                <optgroup label='������� ������'>
                <option value='������� �.�.' style='color:green;'>������� �.�.</option>
                <option value='Ը����� �.�.' style='color:green;'>Ը����� �.�.</option>
                </optgroup>
                <optgroup label='������ ������'>
                <option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
                <option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
                </optgroup>
                </select>
                </table>";
        $out.="<input type=button id=requestbutton value='������' >";
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
