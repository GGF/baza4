<?php

class cp extends secondlevel {

    public function init() {
        parent::init();
        CTitle::addSection('Панель управления');
	// TODO: перенести в модели
//        $sql = "SELECT COUNT(*) FROM `rights`";
//        if (!sql::query($sql)) {
//            $this->dir = __DIR__; // это позволит для install использовать каталог класса, а для шаблонов предыдущий уровень
//            $this->install();
//        }
    }

    public function getIndexMenu() {
        $this->menu->add('users', 'Users');
        $this->menu->add('rights', 'Rights');
        $this->menu->add('todo', 'TODO', false);
        $this->menu->add('operations', 'Ops', false);
        $this->menu->add('workers', 'Workers', false);
        $this->menu->add('back', 'Назад', false);
        if ($this->menu->run())
            return $this->menu->getOutput();
        else
            return '';
    }
}

?>
