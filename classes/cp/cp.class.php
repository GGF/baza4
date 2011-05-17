<?php

class cp extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Панель управления');
    }

    public function getIndexMenu() {
        $this->menu->add('users', 'Users');
        $this->menu->add('todo', 'TODO');
        $this->menu->add('back', 'Назад', false);
        if ($this->menu->run())
            return $this->menu->getOutput();
        else 
            return '';
    }
}

?>
