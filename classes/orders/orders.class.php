<?php

class orders extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('������');
    }
    public function getIndexMenu() {
        $this->menu->add('customers', '���������');
        $this->menu->add('order', '������');
        $this->menu->add('tz', '��');
        $this->menu->add('posintz', '������� ��');
        $this->menu->add('blocks', '���������', false);
        $this->menu->add('boards', '�����',false);
        $this->menu->add('back', '�����',false);
        $this->menu->run();
        return $this->menu->getOutput();        
    }

}
?>
