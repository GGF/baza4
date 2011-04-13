<?php

class lanch extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Запуски');
    }

    public function getIndexMenu() {
        $this->menu->add('nzap', 'Не запущенные');
        $this->menu->add('zap', 'В производстве');
        $this->menu->add('conduct', 'Кондукторы');
        $this->menu->add('mp', 'Мастерплаты');
        $this->menu->add('zad', 'Задел');
        $this->menu->add('pt', 'Шаблоны');
        $this->menu->add('boards', 'Платы');
        $this->menu->add('blocks', 'Заготовки');
        $this->menu->add('back', 'Назад', false);
        $this->menu->run();
        return $this->menu->getOutput();
    }
}

?>
