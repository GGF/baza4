<?php

class lanch_zad extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_edit($id) {
        if (!$_SESSION[rights][$this->getName()][edit])
            return $this->view->getMessage('Нет прав на редактирование');

        $rec[isnew] = true;
        if (!empty($id)) {
            $rec = $this->model->getRecord($id);
            $rec[isnew] = false; 
            $rec[edit]=$id;
        } else {
            $rec[edit]=0;
            $rec[customers] = $this->model->getCustomers();
        }
        $rec[action] = $this->actUri('processingform')->ajaxurl($this->getName());
        $rec[boardlink] = $this->actUri('getboards')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        return $this->view->getForm($out);
    }

}

?>
