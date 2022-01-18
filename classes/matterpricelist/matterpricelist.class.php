<?php

class matterpricelist extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Цены на материалы');
    }
    public function getIndexMenu() {
        $this->menu->add('prices', 'Список цен', false);
        $this->menu->add('changes', 'Изменения', false);
        $this->menu->add('types', 'Типы', false);
        $this->menu->add('suppliers', 'Поставщики', false);
        $this->menu->add('matters', 'Материалы', false);
        $this->menu->add('back', 'Назад',false);
        if ($this->menu->run())
            return $this->menu->getOutput();        
    }

}
?>
