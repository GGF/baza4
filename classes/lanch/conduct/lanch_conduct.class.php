<?php

class lanch_conduct extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(),'edit'))
            return $this->view->getMessage('Нет прав на редактирование');
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
