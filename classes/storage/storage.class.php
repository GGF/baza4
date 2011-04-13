<?php

class storage extends secondlevel {

    public $type;
    private $need_yaer_arc;

    public function init() {
        $storages = array(
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
                title => 'Основные средства'
            ),
            himiya => array(
                sklad => 'him_',
                title => 'Материалы'
            ),
            nepon => array(
                sklad => 'nepon_',
                title => 'Сверла 3.175'
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
                title => 'Запчасти'
            ),
            test => array(
                sklad => 'test_',
                title => 'Склад для отладки'
            ),
        );
        parent::init();
        $this->type = $storages[$_SESSION[storagetype]];
        CTitle::addSection("Склады | {$this->type[title]}");
        $this->table = new storage_rest(); // для скриптов
    }

    public function __call($name, $arguments) {
        $arguments[nooutput] = true;
        parent::__call($name, $arguments);
        $this->table->model->sklad = $this->type[sklad];
        if ($this->table->model)
            $this->need_yaer_arc = $this->table->model->getNeedArc();

        $this->table->run();
        if (Ajax::isAjaxRequest()) {
            return $this->table->getOutput();
        }
        $this->setOutputAssigns();
        Output::assign('menu', $this->getIndexMenu());
        Output::setContent($this->table->getOutput());
        return $this->fetch("body_base.tpl");
    }

    public function getIndexMenu() {
        $this->menu->add('rest', 'Склад', false);
        $this->menu->add('moves', 'Движения', false);
        $this->menu->add('archive', 'Архив', false);
        $this->menu->add('archivemoves', 'Движения Архив', false);
        $this->menu->add('movereport', 'Движение отчет', false);
        $this->menu->add('request', 'Требование', false);
        if ($this->need_yaer_arc)
            $this->menu->add('year', 'Годовая архивация', false);
        $this->menu->add('back', 'назад', false);
        $this->menu->run();
        return $this->menu->getOutput();
    }

    public function action_back() {
        $_SESSION[storagetype] = '';
        parent::action_back('storages');
    }

}

?>
