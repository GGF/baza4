<?php

    
class storages extends firstlevel {

    public function init() {
        parent::init();
        CTitle::addSection('������');
    }
    public function getIndexMenu() {
        $this->menu->add('himiya', '���������',false);
        $this->menu->add('materials', '���������',false);
        $this->menu->add('himiya2', '�����������',false);
        $this->menu->add('sverla', '������ 3.0',false);
        $this->menu->add('halaty', '����������',false);
        $this->menu->add_newline();
        $this->menu->add('instr', '���.��������',false);
        $this->menu->add('nepon', '������ 3.175',false);
        $this->menu->add('maloc', '���������',false);
        $this->menu->add('stroy', '��������������',false);
        $this->menu->add('zap', '�������� �����������',false);
        $this->menu->add('test', '�������',false);
        $this->menu->add('back', '�����',false);
        $this->menu->run();
        return $this->menu->getOutput();        
    }
    public function  __call($name, $arguments) {
        $_SESSION[storagetype] = str_replace('action_', '', $name);
        $name = 'action_storage';
        parent::__call($name, $arguments);
    }
    public function  action_back() {
        parent::action_home();
    }
}
?>
