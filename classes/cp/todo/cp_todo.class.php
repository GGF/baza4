<?php

class cp_todo extends sqltable {

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(),'edit'))
            return $this->view->getMessage('��� ���� �� ��������������');
        $rec[isnew] = true;
        if (!empty($id)) {
            $rec = $this->model->getRecord($id);
            $rec[isnew] = false;
            $rec[edit] = $id;
        } else {
            $rec[customers] = $this->model->getCustomers();
            $rec[blocks] = $this->model->getBlocks($rec[cusid]);
            $rec[edit] = 0;
        }
        $rec[action] = $this->actUri('processingform')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        return $this->view->getForm($out);
    }

}

?>
