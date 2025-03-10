<?php

class update extends lego_abstract {

    function __construct($name = false) {
        parent::__construct($name);
    }
    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }
    
    public function __call($name, $arguments) {
        $classname = get_class($this).'_model';
        $model = new $classname();
        $act = str_replace('action_', '', $name);
        return $model->$act($_REQUEST);
    }

    public function action_index() {
        return "Ok";
    }

}
?>
