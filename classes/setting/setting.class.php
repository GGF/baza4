<?php

class setting extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Настройки');
    }
    
    public function getIndexMenu() {
        $this->menu->add('show', 'Показать', false);
        $this->menu->add('back', 'Назад', false);
        if ($this->menu->run())
            return $this->menu->getOutput();
        else 
            return '';
    }
}

?>
