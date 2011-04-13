<?php

class firstlevel extends lego_abstract {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    protected $bashcite;
    protected $birthdays;
    protected $menu;
    protected $adminhere;
    protected $console;
    protected $maincontent;
    protected $maintarget;

    function __construct($name = false) {
        parent::__construct($name);
    }

    public function __call($name, $arguments) {
        $act = str_replace('action_', '', $name);
        if (class_exists($act)) {
            $_SESSION[level] = $act;
        }
        return $this->_goto('/');
    }

    // todo: не знаю надо ли
    public function getMainTarget() {
        return $this->maintarget;
    }
    // todo: не знаю надо ли
    public function getMainContent() {
        return $this->maincontent;
    }

    public function init() {
        if ($_SERVER[debug][report])
            $this->console = console::getInstance();
        CTitle::setTitle("База данных ЗАО МПП");
        $this->bashcite = new bashcite();
        $this->birthdays = new birthdays();
        $this->adminhere = new adminhere();
        $this->menu = new menu($this);
        $this->maintarget = "#maindiv";
        $this->maincontent = $this;
        //$this->maincontent = new 
    }

    public function setOutputAssigns() {
        Output::assign('title', CTitle::get());
        Output::assign('adminhere', $this->adminhere->run()->getOutput());
        Output::assign('bashcite', $this->bashcite->run()->getOutput());
        Output::assign('birthdays', $this->birthdays->run()->getOutput());
        Output::assign('linkbase', $this->actUri('home')->url()); //'http://' . $_SERVER['HTTP_HOST']); //$this->actUri('index')->url());
        if ($_SERVER[debug][report])
            Output::assign('console', $this->console->run()->getOutput());
    }

    public function action_index() {
        $this->setOutputAssigns();
        Output::assign('menu', $this->getIndexMenu());
        Output::setContent("Нажми чтонить!");
        return $this->fetch("body_base.tpl");
    }

    public function action_help() {
        if (Ajax::isAjaxRequest()) {
            return $this->fetch("help.tpl");
        } else {
            CTitle::addSection('Помощь');
            $this->setOutputAssigns();
            Output::assign('menu', $this->getIndexMenu());
            Output::setContent($this->fetch("help.tpl"));
            return $this->fetch("body_base.tpl");
        }
    }

    public function action_back() {
        $_SESSION["user"]->logout();
    }

    public function action_home() {
        $_SESSION[level] = '';
        $this->_goto('/');
    }

}

?>