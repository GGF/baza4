<?php

class baza extends firstlevel {

    public function getIndexMenu() {
        $this->menu->add('lanch', '�������');
        $this->menu->add('orders', '������');
        $this->menu->add('storages', '������');
        $this->menu->add('cp', '��');
        $this->menu->add('wiki', '���� ������',false);
        $this->menu->add('docs', '���������',false);
        $this->menu->add('help', '������',false);
        $this->menu->add('back', '�����',false);
        $this->menu->run();
        return $this->menu->getOutput();
    }
    
//    public function action_lanch() {
//        $this->_goto('/lanch.php');
//    }
//    public function action_cp() {
//        $this->_goto('/cp.php');
//    }
//
//    protected function action_orders() {
//        $this->_goto('/orders.php');
//    }
//
//    public function action_sklads() {
//        $this->_goto('http://baza/sklads/');
//    }

    public function action_wiki() {
        $this->_goto('http://mppwiki.mpp/');
    }

    public function action_docs() {
        $this->_goto('http://igor.mpp/');
    }

}

?>