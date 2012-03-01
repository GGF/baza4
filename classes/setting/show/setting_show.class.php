<?php

/**
 * Показывает типы пользовательских настроек
 * На данный момент предполагается, что 
 * настройки сохраняются только для компьютера
 * Потому этот класс будет показывать только типы, а форма будет 
 * сохранять в localStorage
 *
 * @author igor
 */
class setting_show extends sqltable {
    
    public function init() {
//        Auth::getInstance()->setRights('setting_show','edit');
//        Auth::getInstance()->setRights('setting_show','del');
//        Auth::getInstance()->setRights('setting_show','show');
        //делал  всем, но на деле нужно только админам
        parent::init();
    }

    public function action_edit($id) {
        if(!empty($id)){
            $rec = $this->model->getRecord($id);
            $out = $this->view->showrec($rec);
            return $this->view->getMessage($out);
        } else {
            return parent::action_edit($id);
        }
    }
}

?>
