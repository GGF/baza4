<?php

class lanch_conduct extends sqltable {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function action_edit($id) {
        if (!$_SESSION[rights][$this->getName()][edit])
            return $this->view->getMessage('��� ���� �� ��������������');
        $rec[isnew] = true;
        $rec[customers] = $this->model->getCustomers();
        if (!empty($id)) {
            $rec = $this->model->getRecord($id);
            $rec[isnew] = false; 
            $rec[edit]=$id;
            $rec[blocks] = $this->model->getBlocks($rec[cusid]);
        } else {
            $rec[edit]=0;
        }
        $rec[action] = $this->actUri('processingform')->ajaxurl($this->getName());
        $rec[boardlink] = $this->actUri('getboards')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        return $this->view->getForm($out);
    }

}

?>
