<?php

class secondlevel extends firstlevel {

    protected $table;

    public function init() {
        parent::init();
        new sqltable(); // ��� ��������� css � ��������
    }

    public function  __call($name, $arguments) {
        
        try {
            $replaced = 0;
            $act = str_replace('action_', '',$name,$replaced);
            if ($replaced==0) throw new Exception("��� ������ ������ {$name}");
            $act = $this->getName() . "_" . $act;
            if (class_exists($act)) {
                //console::getInstance()->out("���� ����� {$act}".  print_r($arguments,true));
                $this->table = new $act();
            } else {
                //console::getInstance()->out("�� ���� ����� {$act}".print_r($arguments,true));
                $this->table = new sqltable($act);
            }
            
            if ($this->table==null) {
                throw new Exception("�� �������� ������� ����� ������� {$act}");
            }
        } catch (Exception $e) {
            return $this->_404("[class=" . get_class($this) . "] : " . $e->getMessage());
        }
        if ($arguments[nooutput]) return;
        $this->table->run();
        if (Ajax::isAjaxRequest()) {
            return $this->table->getOutput();
        }
        $this->setOutputAssigns();
        Output::assign('menu', $this->getIndexMenu());
        Output::setContent($this->table->getOutput());
        return $this->fetch("body_base.tpl");
    }

    public function action_back($parent='') {
        $_SESSION[level] = $parent;
        $this->_goto('/');
    }

}

?>
