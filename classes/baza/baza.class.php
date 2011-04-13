<?php

class baza extends firstlevel {

    public function getIndexMenu() {
        $this->menu->add('lanch', 'Запуски');
        $this->menu->add('orders', 'Заказы');
        $this->menu->add('storages', 'Склады');
        $this->menu->add('cp', 'ПУ');
        $this->menu->add('wiki', 'База знаний',false);
        $this->menu->add('docs', 'Документы',false);
        $this->menu->add('help', 'Помощь',false);
        $this->menu->add('back', 'Выход',false);
        $this->menu->run();
        return $this->menu->getOutput();
    }
    
//    public function action_lanch() {
//        $this->_goto('/lanch.php');
//    }
//    public function action_cp() {
//        $this->_goto('/cp.php');
//    }
//
//    protected function action_orders() {
//        $this->_goto('/orders.php');
//    }
//
//    public function action_sklads() {
//        $this->_goto('http://baza/sklads/');
//    }

    public function action_wiki() {
        $this->_goto('http://mppwiki.mpp/');
    }

    public function action_docs() {
        $this->_goto('http://igor.mpp/');
    }

}

?>