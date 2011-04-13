<?php

class orders extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Заказы');
    }
    public function getIndexMenu() {
        $this->menu->add('customers', 'Заказчики');
        $this->menu->add('order', 'Заказы');
        $this->menu->add('tz', 'ТЗ');
        $this->menu->add('posintz', 'Позиции ТЗ');
        $this->menu->add('blocks', 'Заготовки');
        $this->menu->add('boards', 'Платы');
        $this->menu->add('back', 'Назад',false);
        $this->menu->run();
        return $this->menu->getOutput();        
    }

}
?>
