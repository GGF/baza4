<?php

class lanch_mp extends sqltable {
    
    /**
     * @var lanch_mp_model
     */
    protected  $model;
    /**
     * @var lanch_mp_view
     */
    protected  $view;

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    /**
     * Возвращает ссылку на файл мастерплаты, создавая его
     * @param int $tzposid - идентификатор позиции в техзадании
     * @return string - html с сылкой
     */
    public function getMPLink(int $tzposid = 0)
    {
        $mp = $this->model->newByTZposid($tzposid);
        $link =$this->view->createMPFile($mp);
        return $link;
    }

}

?>
