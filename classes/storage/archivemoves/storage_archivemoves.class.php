<?php

class storage_archivemoves extends sqltable {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function action_index($all = '', $order = '', $find = '', $idstr = '') {
        $tovar = $this->model->getTovar($_SESSION[arc_spr_id]);
        $ost = $this->model->getOst($_SESSION[arc_spr_id]);
        $ost=$ost[ost];
        $edizm = $tovar[edizm];
        $nazv = $tovar[nazv];
        $this->title = empty($_SESSION[arc_spr_id]) ? "" : "�������� - {$nazv} - ������� - {$ost} {$edizm}";
        return parent::action_index($all, $order, $find, $idstr);
    }

}

?>