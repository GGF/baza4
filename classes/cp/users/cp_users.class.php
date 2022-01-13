<?php

class cp_users extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(),'edit'))
            return $this->view->getMessage('Нет прав на редактирование');
        $rec['isnew'] = true;
        if (!empty($id)) {
            $rec = $this->model->getRecord($id);
            $rec['isnew'] = false;
            $rec['edit'] = $id;
        } else {
            $rec['edit'] = 0;
        }
        $rec['do'] = 'users';
        $rec['action'] = $this->actUri('processingform')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        return $this->view->getForm($out);
    }

    public function action_open($id) {
        $rec = $this->model->getRights($id); // переопределить view чтобы PHP видел эти функции
        $rec['action'] = $this->actUri('processingform')->ajaxurl($this->getName());
        $rec['edit'] = $id;
        $rec['do'] = 'rights';
        $out = $this->view->showrigths($rec); // переопределить view чтобы PHP видел эти функции
        return $this->view->getForm($out);
    }

}

?>
