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
        $this->menu->add('boards', 'Платы',false);
        $this->menu->add('blocks', 'Заготовки',false);
        $this->menu->add('back', 'Назад', false);
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
