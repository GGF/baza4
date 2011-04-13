<?php

class storage extends secondlevel {

    public $type;
    private $need_yaer_arc;

    public function init() {
        $storages = array(
            himiya => array(
                sklad => 'him_',
                title => '���������'
            ),
            materials => array(
                sklad => 'mat_',
                title => '���������'
            ),
            himiya2 => array(
                sklad => 'him1_',
                title => '�����������'
            ),
            sverla => array(
                sklad => 'sver_',
                title => '������ 3.0'
            ),
            halaty => array(
                sklad => 'hal_',
                title => '����������'
            ),
            instr => array(
                sklad => 'inst_',
                title => '�������� ��������'
            ),
            himiya => array(
                sklad => 'him_',
                title => '���������'
            ),
            nepon => array(
                sklad => 'nepon_',
                title => '������ 3.175'
            ),
            maloc => array(
                sklad => 'maloc_',
                title => '���������'
            ),
            stroy => array(
                sklad => 'stroy_',
                title => '��������������'
            ),
            zap => array(
                sklad => 'zap_',
                title => '��������'
            ),
            test => array(
                sklad => 'test_',
                title => '����� ��� �������'
            ),
        );
        parent::init();
        $this->type = $storages[$_SESSION[storagetype]];
        CTitle::addSection("������ | {$this->type[title]}");
        $this->table = new storage_rest(); // ��� ��������
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
        $this->menu->add('rest', '�����', false);
        $this->menu->add('moves', '��������', false);
        $this->menu->add('archive', '�����', false);
        $this->menu->add('archivemoves', '�������� �����', false);
        $this->menu->add('movereport', '�������� �����', false);
        $this->menu->add('request', '����������', false);
        if ($this->need_yaer_arc)
            $this->menu->add('year', '������� ���������', false);
        $this->menu->add('back', '�����', false);
        $this->menu->run();
        return $this->menu->getOutput();
    }

    public function action_back() {
        $_SESSION[storagetype] = '';
        parent::action_back('storages');
    }

}

?>
