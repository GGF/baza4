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
    
    public function action_wiki() {
        $this->_goto('http://mppwiki.mpp/');
    }

    public function action_docs() {
        $this->_goto('http://igor.mpp/');
    }

}

?>