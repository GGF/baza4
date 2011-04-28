<?php

/*
 *  ����� ������� ��� ������ ������ �� ����
 */

class sqltable extends lego_abstract {
    /*
     * @var $type string ��� �������, �� ���� ��� ������������ ������
     */

    public $type;
    /*
     * @var $tid string ���������� ������������� ��� ������ AJAX
     */
    public $tid;

    /*
     * @var $data array ������ ������, ���� - �������, �������� ������
     */
    protected $data;
    /*
     * @var string ��������� �������
     */
    protected $title;
    /*
     * @var bool ����� �� ������� ������
     */
    protected $del;
    /*
     * @var bool ����� �� ������������� ������
     */
    protected $edit;
    /*
     * @var bool ���� �� ������, ������ �� ���������� � ����������
     */
    protected $buttons;
    /*
     * @var bool ����� �� ������ ���������� ������
     */
    protected $addbutton;
    /*
     * @var array ������ �������� ������� ��� - ��� ����, �������� - ����������
     */
    protected $cols;
    public $firsttrid;
    public $lasttrid;
    public $index;
    public $find;
    public $order;
    public $idstr;
    public $all;

    /*
     *
     */
    protected $model;
    protected $view;
    protected $form;

    /*
     * Initialization
     */

    // ����������� ���������� ��� ������
    public function getDir() {
        return __DIR__;
    }

    public function __construct($name=false) {
        parent::__construct($name);
    }

    public function init() {
        $this->type = $this->getName();
        $this->tid = uniqid($this->type);


        if (!empty($_POST[find])) {
            $_GET[$this->getName()][index][2] = multibyte::UTF_decode($_POST[find]);
        }

        $param = $this->getLegoParam('index');
        $this->all = (bool) $param[0];
        $this->order = $param[1];
        $this->find = $param[2];//empty($_REQUEST[find])?$param[2]:  multibyte::UTF_decode($_REQUEST[find]);
        $this->idstr = $param[3];

        $this->del = Auth::getInstance()->getRights($this->type,'del');
        $this->edit = Auth::getInstance()->getRights($this->type,'edit');
        $this->buttons = true;
        $this->addbutton = Auth::getInstance()->getRights($this->type,'edit');

        try {
            profiler::add('����������', $this->getName() . get_class($this) . ': ������� ������� ������');
            $classname = "{$this->getName()}_model";
            if (!class_exists($classname)) {
                $classname = get_class($this) . "_model";
            }
            if (!class_exists($classname))
                throw new Exception("��� ������ {$classname}");
            $this->model = new $classname();
            $this->model->init();
            profiler::add('����������', $this->getName() . ': ������� ������� ������ �������');
        } catch (Exception $e) {
            console::getInstance()->out("[class=" . get_class($this) . "] : " . $e->getMessage());
        }
        try {
            profiler::add('����������', $this->getName() . ': ������� ������� ���');
            $classname = "{$this->getName()}_view";
            if (!class_exists($classname)) {
                $classname = get_class($this) . "_view";
            }
            if (!class_exists($classname)) {
                $classname = "sqltable_view";
            }
            if (!class_exists($classname))
                throw new Exception("��� ������ {$classname}");
            $this->view = new $classname($this);
            profiler::add('����������', $this->getName() . ': ������� ������� ��� �������');
        } catch (Exception $e) {
            console::getInstance()->out("[class=" . get_class($this) . "] : " . $e->getMessage());
        }
        try {
            profiler::add('����������', $this->getName() . ': ������� ������� �����');
            $this->form = new ajaxform(''); // ��� ����������� ��������
            profiler::add('����������', $this->getName() . ': ������� ������� ����� �������');
        } catch (Exception $e) {
            console::getInstance()->out("[class=" . get_class($this) . "] : " . $e->getMessage());
        }
    }

    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    public function __get($name) {
        return $this->{$name};
    }

    /*
     * Controller
     */

    public function action_index($all='', $order='', $find='', $idstr='') {
        $all = empty($all) ? $this->all : $all;
        $order = empty($order) ? $this->order : $order;
        $find = empty($_REQUEST[find]) ? (empty($find) ? $this->find : $find) : multibyte::UTF_decode($_REQUEST[find]); 
        
        $idstr = empty($idstr) ? $this->idstr : $idstr;

        $this->all = $all;
        $this->order = $order;
        $this->find = $find;
        $this->idstr = $idstr;
        if ($this->model != null) {
            if (empty($this->cols))
                $this->cols = $this->model->getCols();
            if (empty($this->data))
                $this->data = $this->model->getData($all, $order, $find, $idstr);
        }

        return $this->view == null ? "" : $this->view->show();
    }

    public function action_delete($id, $confirmed=false, $delstr='') {
        if (Ajax::isAjaxRequest() || $confirmed) {
            // � ���� ������ ��� ������������ ����� ���� ���������
        } else {
            $out = empty($delstr) ? "������� {$id}?" : "������� {$delstr}?";
            return $this->view->getConfirm($out, 'delete', $id, true);
        }
        $this->model->delete($id);
        return $this->action_index();
    }

    public function action_edit($id) {
        $rec = $this->model->getRecord($id);
        $rec[isnew] = empty($id);
        $rec[edit] = $id;
        $rec[action] = $this->actUri('processingform')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        if ($out)
            return $this->view->getForm($out);
        else {
            $out = "�� �������������!";
            return $this->view->getMessage($out);
        }
    }

    public function action_open($id) {
        return $this->action_edit($id);
    }

    public function action_add() {
        return $this->action_edit(0);
    }

    public function action_processingform() {
        // ����� ����������
        ajaxform_recieve::init();
        $form = new ajaxform($this->getName());
        $form->initBackend();

        if (!$form->errors) {
            // ����������
            $res = $this->model->setRecord(array_merge($form->request, array("files" => $form->files)));
            if ((!is_array($res) && $res == 0) || (is_array($res) && !$res[affected])) {
                $alert = empty($res[alert]) ? "�� ���������� �� ����� ������" : $res[alert];
                $form->alert($alert);
                $form->processed();
            } else {
                // ������� ���������� � ��������� �������
                $form->processed("$('#dialog').dialog('close').remove();reload_table();");
            }
        } else {
            foreach ($form->errors as $err) {
                $form->alert(print_r($err, true));
            }
            // � ������ ������ ��������� ��� ��������
            $form->processed();
        }
        return '';
    }

    public function getMessage($message) {
        return $this->view->getMessage($message);
    }

    public function action_getboards() {
        $customerid = $_REQUEST[customerid];
        $data = $this->model->getBoards($customerid);
        return $this->view->getSelect($data);
    }

    public function action_getblocks() {
        $customerid = $_REQUEST[customerid];
        $data = $this->model->getBlocks($customerid);
        return $this->view->getSelect($data);
    }
    
    public function action_addfilefield() {
        $edit = new ajaxform_edit($this->getName());
        $edit->restore();
        $field =
                array(
                    "type" => AJAXFORM_TYPE_FILE,
                    "name" => "file" . rand(),
                    "label" => "",
        );
        $edit->addFieldAsArray($field);
        $out = $edit->getFieldOut($edit->fields[$field[name]]);
        return $out;
    }

}

?>
