<?php

class cp extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('������ ����������');
    }

    public function getIndexMenu() {
        $this->menu->add('users', 'Users');
        $this->menu->add('todo', 'TODO');
        $this->menu->add('back', '�����', false);
        $this->menu->run();
        return $this->menu->getOutput();
    }
}

?>
