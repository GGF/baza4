<?php

class storages extends firstlevel {

    public static $storages = array(
        himiya => array(
            sklad => 'him_',
            title => 'Материалы'
        ),
        materials => array(
            sklad => 'mat_',
            title => 'Текстолит'
        ),
        himiya2 => array(
            sklad => 'him1_',
            title => 'Лаборатория'
        ),
        sverla => array(
            sklad => 'sver_',
            title => 'Сверла 3.0'
        ),
        halaty => array(
            sklad => 'hal_',
            title => 'Спецодежда'
        ),
        instr => array(
            sklad => 'inst_',
            title => 'ОС'
        ),
        himiya => array(
            sklad => 'him_',
            title => 'Материалы'
        ),
        nepon => array(
            sklad => 'nepon_',
            title => 'Св 3.175'
        ),
        maloc => array(
            sklad => 'maloc_',
            title => 'Малоценка'
        ),
        stroy => array(
            sklad => 'stroy_',
            title => 'Стройматериалы'
        ),
        zap => array(
            sklad => 'zap_',
            title => 'З и И'
        ),
        test => array(
            sklad => 'test_',
            title => 'Склад для отладки'
        ),
        test1 => array(
            sklad => 'test1_',
            title => 'Склад для отладки1'
        ),
    );

    public function __construct($name=false) {
        parent::__construct($name);
    }

    public function init() {
        parent::init();
        $sql = "SELECT COUNT(*) FROM `{$_SERVER[storagebase]}`.`coments`"; // мне не нравится что имя таблицы с ошибкой
        if (!sql::query($sql)) {
            $this->dir = __DIR__; // это позволит для install использовать каталог класса, а для шаблонов предыдущий уровень
            $replace = array(
                "storagebase" => $_SERVER["storagebase"],
            );
            $this->install($replace); // если не получилось прочитать комментарии нужно создать базу и таблицы
        }
        CTitle::addSection('Склады');
    }

    public function getIndexMenu() {
        foreach (storages::$storages as $key => $value) {
            $this->menu->add($key, $value[title], false);
        }
        $this->menu->add('back', 'назад', false);
        if ($this->menu->run())
            return $this->menu->getOutput();
    }

    public function __call($name, $arguments) {
        // тут надо разбить указатель на склад по локальному окну
        // то есть из скрипта получить значение
        $_SESSION[Auth::$lss][storagetype] = str_replace('action_', '', $name);
        $name = 'action_storage';
        parent::__call($name, $arguments);
    }

    public function action_back() {
        parent::action_home();
    }

}

?>
