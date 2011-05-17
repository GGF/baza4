<?php

    
class storages extends firstlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Склады');
    }
    public function getIndexMenu() {
        $this->menu->add('himiya', 'Материалы',false);
        $this->menu->add('materials', 'Текстолит',false);
        $this->menu->add('himiya2', 'Лаборатория',false);
        $this->menu->add('sverla', 'Сверла 3.0',false);
        $this->menu->add('halaty', 'Спецодежда',false);
        $this->menu->add_newline();
        $this->menu->add('instr', 'Осн.Средства',false);
        $this->menu->add('nepon', 'Сверла 3.175',false);
        $this->menu->add('maloc', 'Малоценка',false);
        $this->menu->add('stroy', 'Стройматериалы',false);
        $this->menu->add('zap', 'Запчасти инструменты',false);
        $this->menu->add('test', 'Отладка',false);
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
