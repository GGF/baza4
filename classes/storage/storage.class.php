<?php

class storage extends secondlevel {

    public function getDir() {
        return __DIR__;
        /*
         * это строка должна быть в каждом лего, но фишка в том, что шаблоны
         * отрисовки у первого левела. А скрипты прямо тут. Нужно наследовать шаблоны.
         */
    }

    private $need_yaer_arc;

    public function init() {
        parent::init();
        CTitle::addSection("Склады | " . storages::$storages[$_SESSION[Auth::$lss][storagetype]][title]);
        $sql = "SELECT COUNT(*) FROM `{$_SERVER[storagebase]}`.`sk_" .
                storages::$storages[$_SESSION[Auth::$lss][storagetype]][sklad] .
                "_spr`";
        if (!sql::query($sql)) {
            $this->dir = __DIR__; // это позволит для install использовать каталог класса, а для шаблонов предыдущий уровень
            $replace = array(
                "storagebase" => $_SERVER["storagebase"],
                "storage" => storages::$storages[$_SESSION[Auth::$lss][storagetype]][sklad],
            );
            $this->install($replace); // если не получилось прочитать комментарии нужно создать базу и таблицы
        }
    }

    public function __call($name, $arguments) {
        $arguments[nooutput] = true;
        parent::__call($name, $arguments);
        $this->need_yaer_arc = $this->table->model->getNeedArc();
        //console::getInstance()->out("lss=".Auth::$lss." ".print_r($_SESSION,true));

        if ($this->table->run()) {
            if (Ajax::isAjaxRequest()) {
                return $this->table->getOutput();
            }
            $this->setOutputAssigns();
            Output::assign('menu', $this->getIndexMenu());
            Output::setContent($this->table->getOutput());
            return $this->fetch("body_base.tpl");
        }
    }

    public function getIndexMenu() {
        $this->menu->add('rest', 'Склад', false);
        $this->menu->add('moves', 'Движения', false);
        $this->menu->add('archive', 'Архив', false);
        $this->menu->add('archivemoves', 'Движения Архив', false);
        $this->menu->add('movereport', 'Движение отчет', false, true);
        $this->menu->add('request', 'Требование', false, true);
        if ($this->need_yaer_arc)
            $this->menu->add('year', 'Годовая архивация', false);
        $this->menu->add('back', 'назад', false);
        if ($this->menu->run())
            return $this->menu->getOutput();
    }

    public function action_back() {
        $_SESSION[Auth::$lss][storagetype] = '';
        parent::action_back('storages');
    }

}

?>
