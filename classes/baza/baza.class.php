<?php

/*
 * Класс основного окна
 */

class baza extends firstlevel {

    /**
     * Задает меню
     * @return string
     */
    public function getIndexMenu() {
        $this->menu->add('lanch', 'Запуски');
        $this->menu->add('orders', 'Заказы');
        $this->menu->add('productioncard', 'Производство');
        $this->menu->add('possibility', 'Можем');
        $this->menu->add('storages', 'Склады');
        $this->menu->add('cp', 'ПУ');
        $this->menu->add('matterpricelist', 'Цены для расчетов', false);
        $this->menu->add('wiki', 'База знаний', false);
        //$this->menu->add('docs', 'Документы',false);
        $this->menu->add('help', 'Помощь', false);
        $this->menu->add('setting', 'Настройки', false);
        $this->menu->add('back', 'Выход', false);
        if ($this->menu->run())
            return $this->menu->getOutput();
        else
            return '';
    }

    /**
     * Реакция на кнопку "База знаний"
     */
    public function action_wiki() {
        $this->_goto('http://mppwiki.mpp/');
    }

    /**
     * Реакция на кнопку "Документы"
     */
    public function action_docs() {
        $this->_goto('http://igor.mpp/');
    }

}

?>