<?php

class storage_movereport extends sqltable {

    // ����������� ���������� ��� ������
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
            $this->title .= '����� �� �����:<select id=month name=selectmonth>';
            $res = $this->model->getMonths();
            foreach ($res as $rs) {
                $this->title .= "<option value=" . ($rs["dmonth"] * 10000 + $rs["dyear"]) . " " .
                        ((floor($month / 10000) == $rs["dmonth"] && ($month % 10000) == $rs["dyear"]) ? "SELECTED" : "") . ">" .
                        sprintf("%02d", $rs["dmonth"]) . "-" . $rs["dyear"] . "</option>";
            }
            $this->title.="</select><input type=button id=monthbutton value='�����'></form>";
        }

        if (empty($idstr) || !empty($sdate)) {
            if (empty($sdate))
                $sdate = date("d.m.Y");
            if (empty($edate))
                $edate = date("d.m.Y");

            $this->title.="<form method=get name=peroidreport id=form_peroidreport action='" . $this->actUri('index')->url() . "'>";
            $this->title.="����� �� ������: � ";
            $this->title.="<input size=10 id='datepicker1'  name='sdate' value='{$sdate}' type=text >";
            $this->title.=" �� ";
            $this->title.="<input size=10 id='datepicker2'  name='edate' value='{$edate}' type=text >";
            $this->title.="<input type=button id=rangebutton value='�����'>";
            $this->title.="</form>";
            $this->title.="<script>$('#datepicker1').datepicker();$('#datepicker2').datepicker();</script>";
        }
        return parent::action_index($all, $order, $find, $idstr);
    }

}

?>
