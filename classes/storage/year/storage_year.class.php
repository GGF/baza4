<?php

class storage_year extends storage_rest {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function  action_index($all = '', $order = '', $find = '', $idstr = '') {
        //parent::action_index();
        $out='����� ������ ������������?';
        return $this->view->getConfirm($out,'arc','noarc');
    }

    public function action_noarc(){
        return '��� � ������, ����-���-���';
    }
    public function action_arc(){
        if ($this->model->arc())
            return '��������������';
        else
            return '����� �����-��. �� ����������������';
    }
}

?>
