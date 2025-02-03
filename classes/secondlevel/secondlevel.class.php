<?php

class secondlevel extends firstlevel {

    protected $table;

    public function getDir() {
        return __DIR__;
        /*
         * это строка должна быть в каждом лего, но фишка в том, что шаблоны
         * отрисовки у первого левела. А скрипты прямо тут. Нужно наследовать шаблоны.
         */
    }
    public function init() {
        parent::init();
        new sqltable(); // для включения css и скриптов
        new firstlevel();// для включения css и скриптов (в основном css) там понимаешь хранится шаблон для всего)
    }

    public function __call($name, $arguments) {

        try {
            $replaced = 0;
            $act = str_replace('action_', '', $name, $replaced);
            if ($replaced == 0)
                throw new Exception("Нет такого метода {$name}");
            $act = $this->getName() . "_" . $act;
            if (class_exists($act)) {
                $this->table = new $act();
            } else {
                $this->table = new sqltable($act);
            }

            if ($this->table == null) {
                throw new Exception("Не возможно создать такую таблицу {$act}");
            }
        } catch (Exception $e) {
            return $this->_404("[class=" . get_class($this) . "] : " . $e->getMessage());
        }
        if (isset($arguments['nooutput']))
            return;
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

    public function action_back($parent='') {
        $this->_goto("/?level={$parent}");
    }

}

?>
