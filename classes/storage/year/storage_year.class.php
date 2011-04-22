<?php

class storage_year extends storage_rest {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function  action_index($all = '', $order = '', $find = '', $idstr = '') {
        //parent::action_index();
        $out='Точно хотите архивировать?';
        return $this->view->getConfirm($out,'arc','noarc');
    }

    public function action_noarc(){
        return 'Вот и славно, трам-пам-пам';
    }
    public function action_arc(){
        if ($this->model->arc())
            return 'Заархивировано';
        else
            return 'Фигня какая-то. Не заррхивировалось';
    }
}

?>
