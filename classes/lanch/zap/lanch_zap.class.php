<?php

/**
 * Description of lanch_zap
 *
 * @author igor
 */
class lanch_zap extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }
    public function  init() {
        parent::init();
        $this->addbutton = false;
    }
    public function action_open($id) {
        $rec=array();
        $rec[path]=$this->model->getPath($id);
        $rec[sl] = $this->model->getSL($id);
        $rec[tz] = $this->model->getTZ($id);
        $rec[letter] = $this->model->getLetter($id);
        $rec[dozaplink] = $this->uri()->clear()->set('lanch','nzap')->set('lanch_nzap', 'dozap', $id)->url();
        $rec[id] = $id;
        return $this->getMessage($this->view->showrec($rec));
    }
}
?>
