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
        $this->menu->add('boards', '�����',false);
        $this->menu->add('blocks', '���������',false);
        $this->menu->add('back', '�����', false);
        $this->menu->run();
        return $this->menu->getOutput();
    }
    
    public function action_boards() {
        $_SESSION[customer_id]='';
        $this->table = new orders_boards();
        $this->table->run();
        if (Ajax::isAjaxRequest()) {
            return $this->table->getOutput();
        }
        $this->setOutputAssigns();
        Output::assign('menu', $this->getIndexMenu());
        Output::setContent($this->table->getOutput());
        return $this->fetch("body_base.tpl");
    }
    
    public function action_blocks() {
        $_SESSION[customer_id]='';
        $this->table = new orders_blocks();
        $this->table->run();
        if (Ajax::isAjaxRequest()) {
            return $this->table->getOutput();
        }
        $this->setOutputAssigns();
        Output::assign('menu', $this->getIndexMenu());
        Output::setContent($this->table->getOutput());
        return $this->fetch("body_base.tpl");
    }  
    
}

?>
