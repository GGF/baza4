<?php

class possibility extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Возможность изготовления');
    }

    public function getIndexMenu() {
        $this->menu->add('boards', 'Платы',false);
        $this->menu->add('back', 'Назад', false);
        if ($this->menu->run())
            return $this->menu->getOutput();
    }

}

?>