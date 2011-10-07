<?php

class productioncard extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Производство');
    }

    public function getIndexMenu() {
        $this->menu->add('dpp', 'ДПП');
        $this->menu->add('mpp', 'МПП');
        $this->menu->add('back', 'Назад', false);
        if ($this->menu->run())
            return $this->menu->getOutput();
    }

}

?>
