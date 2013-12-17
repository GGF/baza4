<?php

class storage_movereport extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        $month = $_REQUEST[selectmonth];
        $sdate = $_REQUEST[sdate];
        $edate = $_REQUEST[edate];

        $idstr = !empty($month) ? ("month:{$month}") :
                (!empty($sdate) ? "range:{$sdate}:{$edate}" : '');

        $this->title = "";
        if (empty($idstr) || !empty($month)) {
            $this->title .= '<form method=get action=' . $this->actUri('index')->url() . ' name="monthform">';
            $this->title .= 'Отчет за месяц:<select id=month name=selectmonth>';
            $res = $this->model->getMonths();
            foreach ($res as $rs) {
                $this->title .= "<option value=" . ($rs["dmonth"] * 10000 + $rs["dyear"]) . " " .
                        ((floor($month / 10000) == $rs["dmonth"] && ($month % 10000) == $rs["dyear"]) ? "SELECTED" : "") . ">" .
                        sprintf("%02d", $rs["dmonth"]) . "-" . $rs["dyear"] . "</option>";
            }
            $this->title.="</select><input type=button id=monthbutton class='noprint' value='Отчет'></form>";
        }

        if (empty($idstr) || !empty($sdate)) {
            if (empty($sdate))
                $sdate = date("d.m.Y");
            if (empty($edate))
                $edate = date("d.m.Y");

            $this->title.="<form method=get name=peroidreport id=form_peroidreport action='" . $this->actUri('index')->url() . "'>";
            $this->title.="Отчет за период: с ";
            $this->title.="<input size=10 datepicker=1  name='sdate' value='{$sdate}' type=text >";
            $this->title.=" по ";
            $this->title.="<input size=10 datepicker=1 name='edate' value='{$edate}' type=text >";
            $this->title.="<input type=button id=rangebutton class='noprint' value='Отчет'>";
            $this->title.="</form>";
        }
        if (!empty($idstr)) $this->title .= '<input type="button" class="noprint" value="Скопировать для  excel" id="copytable" onclick="copytable();$(\'#copytable\').val(\'Готово\')">';
        //$this->title.= $this->getHeaderBlock();
        return parent::action_index($all, $order, $find, $idstr);
    }

}

?>
