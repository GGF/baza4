<?php

class lanch extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('�������');
    }

    public function getIndexMenu() {
        $this->menu->add('nzap', '�� ����������');
        $this->menu->add('zap', '� ������������');
        $this->menu->add('conduct', '����������');
        $this->menu->add('mp', '�����������');
        $this->menu->add('zad', '�����');
        $this->menu->add('pt', '�������');
        $this->menu->add('boards', '�����');
        $this->menu->add('blocks', '���������');
        $this->menu->add('back', '�����', false);
        $this->menu->run();
        return $this->menu->getOutput();
    }
}

?>
