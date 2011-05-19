<?php

    
class storages extends firstlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Склады');
    }
    public function getIndexMenu() {
        foreach (storage::$storages as $key => $value) {
            $this->menu->add($key, $value[title],false);
        }
        $this->menu->add('back', 'назад',false);
        if ($this->menu->run())
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
