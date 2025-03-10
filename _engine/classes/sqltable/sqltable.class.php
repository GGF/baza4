<?php

/**
 *  Класс Таблица для вывода данных из базы
 */

class sqltable extends lego_abstract {
    /**
     * @var string тип таблицы, то есть тип отображаемых данных
     */
    public $type;

    /**
     * @var $tid string уникальный идентификатор для замены AJAX
     */
    public $tid;

    /**
     * @var $data array массив данных, ключ - колонка, значение клетка
     */
     protected $data;

     /**
     * @var string Заголовок таблицы
     */
     protected $title;

     /**
     * @var bool можно ли удалять записи
     */
     protected $del;

     /**
     * @var bool можно ли редактировать записи
     */
    protected $edit;

    /**
     * @var bool Есть ли кнопки, делать ли сортировку в заголовках
     */
    protected $buttons;

    /**
     * @var bool нужна ли кнопка добавления записи
     */
    protected $addbutton;

    /**
     * @var bool нужна ли кнопка поиска
     */
    protected $findbutton;

    /**
     * @var array массив названий колонок имя - имя поля, значение - загголовок
     */
    protected $cols;

    /**
     * @var int - идентификатор первой строки таблицы
     */
    public $firsttrid;

    /**
     * @var int - идентификатор послендей строки таблицы
     */
    public $lasttrid;

    /**
     * @var string - стока поиска
     */
    public $find;

    /**
     * @var string - дорядок сортировки
     */
    public $order;

    /**
     * @var string - дополнительные идентификаторы
     */
    public $idstr;

    /**
     * @var string - Показывать ли все записи
     */
    public $all;

    /**
     * @var sqltable_model
     */
    protected $model;
    
    /**
     * @var sqltable_view
     */
    protected $view;

    /**
     * @var ajaxform - форма
     */
    protected $form;

    /*
     * Initialization
     */

    /**
     * Наследуется от абстрактного, нужно переопределять чтобы объект знал где брать дополнительные файлы
     * @return string каталог расположения класса
     */
    public function getDir() {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'i18n.php'; //всё равноперреопределяется и вызывается, почему не тут вставлять
        return __DIR__;
    }

    /**
     * Конструктор
     */
    public function __construct($name=false) {
        parent::__construct($name);
    }

    /**
     * Инициализация объекта
     */
    public function init() {
        $this->type = $this->getName();
        $this->tid = uniqid($this->type);


        if (!empty($_POST['find'])) {
            $_GET[$this->getName()]['index'][2] = multibyte::UTF_decode($_POST['find']);
        }

        $param = $this->getLegoParam('index');
        if (is_array($param)) {
            $this->all = (bool) $param[0];
            $this->order = $param[1];
            $this->find = $param[2]; //empty($_REQUEST['find'])?$param[2]:  multibyte::UTF_decode($_REQUEST['find']);
            $this->idstr = $param[3];
        }

        $this->del = Auth::getInstance()->getRights($this->type, 'del');
        $this->edit = Auth::getInstance()->getRights($this->type, 'edit');
        $this->buttons = true;
        $this->addbutton = Auth::getInstance()->getRights($this->type, 'edit');
        $this->findbutton = true;

        try {
            $classname = "{$this->getName()}_model";
            if (!class_exists($classname)) {
                $classname = get_class($this) . "_model";
            }
            if (!class_exists($classname)) {
                throw new Exception("Нет класса {$classname}");
            }
            $this->model = new $classname();
            $this->model->init();
        } catch (Exception $e) {
            console::getInstance()->out("[class=" . get_class($this) . "] : " . $e->getMessage());
        }
        try {
            $classname = "{$this->getName()}_view";
            if (!class_exists($classname)) {
                $classname = get_class($this) . "_view";
            }
            if (!class_exists($classname)) {
                $classname = "sqltable_view";
            }
            if (!class_exists($classname)) {
                throw new Exception("Нет класса {$classname}");
            }
            $this->view = new $classname($this);
        } catch (Exception $e) {
            console::getInstance()->out("[class=" . get_class($this) . "] : " . $e->getMessage());
        }
        try {
            $this->form = new ajaxform(''); // для подключения скриптов
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

     /**
      * Обработка основного события
      * @param string $all - показывать ли все
      * @param string $order - сортировка
      * @param string $find - строка поиска
      * @param string $idstr - строка с идентификаторами, тут сложнее
      * @return string - HTML
      */
    public function action_index($all='', $order='', $find='', $idstr='') {
        $all = empty($all) ? $this->all : $all;
        $order = empty($order) ? $this->order : $order;
        $find = empty($_REQUEST['find']) ? (empty($find) ? $this->find : $find) : multibyte::UTF_decode($_REQUEST['find']);

        $idstr = empty($idstr) ? $this->idstr : $idstr;

        $this->all = $all;
        $this->order = $order;
        $this->find = $find;
        $this->idstr = $idstr;
        if ($this->model != null) {
            // порядок получения важен. в получении даты инициализируется idstr
            if (empty($this->data)) {
                $this->data = $this->model->getData($all, $order, $find, $idstr);
            }
            if (empty($this->cols)) {
                $this->cols = $this->model->getCols();
            }
        }

        return $this->view == null ? "" : $this->view->show();
    }

    /**
     * Удаление записи
     * @param int $id - идентификатор записи
     * @param bool $confirmed - подтвержденая ли уже АЯКСом
     * @param string $delstr - Строка запроса
     * @return string HTML
     */

    public function action_delete($id, $confirmed=false, $delstr='') {
        if (Ajax::isAjaxRequest() || $confirmed) {
            // в этом случае уже подтверждено иначе надо проверить
        } else {
            $out = Lang::getString('question.deleteit') . (empty($delstr) ? " {$id}?" : " {$delstr}?");
            return $this->view->getConfirm($out, 'delete', $id, true);
        }
        $this->model->delete($id);
        return $this->action_index();
    }

    /**
     * Обрабатывает редактирование 
     * @param int $id - идентификатор редактируемой записи, если новая то 0
     * @return string - HTML для диалогового окна
     */
    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(), 'edit')) {
            return $this->view->getMessage(Lang::getString('message.hasntright'));
        }
        $rec = $this->model->getRecord($id);
        $rec['idstr'] = $this->idstr;
        $rec['isnew'] = empty($id);
        $rec['edit'] = $id;
        if ($rec['isnew']) {
            $rec['customers'] = $this->model->getCustomers();
            $rec['boardlink'] = $this->actUri('getboards')->ajaxurl($this->getName());
            $rec['blocklink'] = $this->actUri('getblocks')->ajaxurl($this->getName());
        }
        $rec['action'] = $this->actUri('processingform')->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        if ($out) {
            if (stristr($out,'<div class="editdiv">')) { //жутко криво определять форма это или сообщение, но пусть пока так
                return $this->view->getForm($out);    
            } else {
                return $this->view->getMessage($out);
            }
        } else {
            $out = Lang::getString('message.uneditable');
            return $this->view->getMessage($out);
        }
    }

    /**
     * Обработка открытия записи
     * @param int $id - идентификатор записи
     * @return string - HTML для диалогового окна
     */
    public function action_open($id) {
        return $this->action_edit($id);
    }

    /**
     * Обработка добавления записи
     * @return string - HTML для диалогового окна
     */
    public function action_add() {
        return $this->action_edit(0);
    }

    /**
     * Обработка формы
     */
    public function action_processingform() {
        // хидер переключим
        ajaxform_recieve::init();
        $form = new ajaxform($this->getName());
        $form->initBackend();

        if (!$form->errors) {
            // сохранение
            $res = $this->model->setRecord(array_merge($form->request, array('files' => $form->files)));
            if ((!is_array($res) && $res == 0) || (is_array($res) && !$res['affected'])) {
                $alert = empty($res['alert']) ? "Не обработано ни одной записи" : $res['alert'];
                $form->alert($alert);
                $form->processed();
            } else {
                // удачное завернение с закрытием диалога
                $form->processed("$('#dialog').dialog('close').remove();reload_table();");
            }
        } else {
            // в случае ошибок обработка без закрытия
            $form->processed('');
        }
        return '';
    }

    public function getMessage($message) {
        return $this->view->getMessage($message);
    }

    /**
     * Обработка получения списка плат для комбобоксов выбора
     * @return string - HTML
     */
    public function action_getboards() {
        $customerid = $_REQUEST['idstr'];
        $data = $this->model->getBoards($customerid);
        return $this->view->getSelect($data);
    }

    /**
     * Обработка получения списка блоков для комбобоксов
     * @return string - HTML
     */
    public function action_getblocks() {
        $customerid = $_REQUEST['idstr'];
        $data = $this->model->getBlocks($customerid);
        return $this->view->getSelect($data);
    }

    /**
     * Обработка добаления в форму поля
     * @return string - HTML 
     */
    public function action_addfilefield() {
        $edit = new ajaxform_edit($this->getName());
        $edit->restore();
        $filename = "file" . rand();
        $field =
                array(
                    "type" => AJAXFORM_TYPE_FILE,
                    "name" => $filename,
                    "label" => $filename,
        );
        $edit->addFieldAsArray($field);
        $out = $edit->getFieldOut($edit->fields[$field['name']]);
        return $out;
    }

    /**
     * Обработка добавения в форму ссылки на файл
     * @return string - HTML
     */
    public function action_addfilelink() {
        $edit = new ajaxform_edit($this->getName());
        $edit->restore();
        $filename = multibyte::UTF_decode($_REQUEST['filename']);
        $id = $this->model->getFileId($filename);
        $values[$id] = basename($this->model->getFileNameById($id));
        $value[$id] = 1;
        $field =
                array(
                    "type" => AJAXFORM_TYPE_CHECKBOXES,
                    "name" => "linkfile",
                    "label" => '',
                    "value" => $value,
                    "values" => $values,
        );
        $edit->addFieldAsArray($field);
        $edit->form->SessionSet();
        $out = $edit->getFieldOut($edit->fields[$field['name']]);
        return $out;
    }

    /**
     * Обработка записи комментария
     * @return string - HTML
     */
    public function action_savecomment() {
        $rec = $this->model->saveComment();
        $out = $this->view->addComments($rec['record_id'],$rec['table']);
        return $out;
    }

    /**
     * Обработка удаления коментария
     * @return string - HTML
     */
    public function action_deletecomment($id) {
        $this->model->deleteComment($id);
        return '';
    }

    /**
     * Похоже надо перектыить getactionparams, а то неправильно получает
     */
    public function getActionParams($action = false)
    {
        $par = parent::getActionParams($action);
                // возникла ошибка что не было нулевого элемента массива (в таблице - это $all) и смещались параметры - 
        // так что проверим нулевой и если нет вставим нуль, хотя там может быть другие ... ну посмотрим
        if (!isset($par[0])) {
            $ret[0]=0;
            foreach($par as $one) {
                $ret[]=$one;
            }
        } else {
            $ret = $par;
        }
        return $ret;

    }

}

?>
