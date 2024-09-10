<?php

class Auth extends lego_abstract {

    /**
     * Удачность авторизации
     * @var boolean
     */
    public $success;
    /**
     * Пользователь
     * @var array
     */
    public $user;
    /**
     * Права
     * @var array
     */
    public $rights;
    /**
     * Настройки, типа путь к коммандеру
     * @var type
     */
    public $settings;
    /**
     * Модель
     * @var Auth_model
     */
    private $_model;
    /**
     * Отображение
     * @var Auth_view
     */
    private $_view;
    /**
     * Оконная сессия, отдельныйразговор, но позволяет делать разные сесси в окнах при одной авторизации
     * @var string
     */
    public static $lss;
    /**
     * Инстанс Singleton
     * @var Auth
     */
    static private $instance;

    /**
     * Перекрытие абстракции из lego
     * @return string
     */
    public function getDir() {
        return __DIR__;
    }

    /**
     * Конструктор Singleton
     * @param type $name
     * @param type $directCall
     */
    public function __construct($name=false, $directCall = true) {
	// Интернационализируем
	require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'i18n.php';
        if ($directCall) {
            trigger_error(Lang::getString('Auth.errors.noconstructor'), E_USER_ERROR);
        }
        parent::__construct($name);
    }

    /**
     * Получение инстанса Singleton
     * @return Auth
     */
    static public function getInstance() {
        return (self::$instance === null) ?
                self::$instance = new self('Auth', false) :
                self::$instance;
    }

    /**
     * Инициальзиция переменных, когда объект уже есть
     */
    public function init() {
        parent::init();
        $this->success = false;
	$this->_model = new Auth_model();
	$this->_view = new Auth_view();

    }

    /**
     * Получение прав пользователя на чтото
     * @param string $type Тип прав на которые запрашивается
     * @param string $action Деййствие на которое запрашиваются права
     * @return boolean
     */
    public function getRights($type='', $action='') {
        if (!empty($type)) {
            if (!empty($action)) {
                if (isset($this->rights[$type][$action]))
                    return $this->rights[$type][$action]; // если определены права то вернем
            } else {
                if (isset($this->rights[$type]))
                    return $this->rights[$type];
            }
        }
        return false; // а уж если не определены то не разрешим //$this->rights;
    }

    /**
     * Обрратная предыдущей функция установки прав на что-то
     * В базу получено право не пишет, используется для того чтоб в порожденных
     * классах разрешать чтото для всех, ну или соответственно запрещать
     * @param string $type
     * @param string $action
     * @param boolean $val
     */
    public function setRights($type,$action,$val=true) {
        $this->rights[$type][$action] = $val;
    }


    /**
     * Поллучить данные о пользователе
     * @param string $field Какое поле нужно получить
     * @return mixed Либо  весь массив, либо выбраное поле
     */
    public function getUser($field=false) {
        if ($field) {
            return $this->user[$field];
        }
        return $this->user;
    }

    /**
     * Обработчик действия по умолчанию
     * @return boolean or string
     */
    public function action_index() {
	$rec = $this->_model->checksession(session_id());
	$this->user = $rec["user"];
	$this->rights = $rec["rights"];
	$mes = $rec["mes"];
	$this->success = $rec["success"];
	//console::getInstance()->out($this->success == 'Auth.session.success'.'...'.print_r($rec,true));
        if($this->success == 'Auth.session.success') {
	    $this->success = true; // именно  по не success рисуется  окно
	    return true;
	} else {
	    if($this->success == 'Auth.session.wrongpassword')
		$mes = '<script>localStorage.removeItem("remember")</script>'.$mes;
	    /**
	     * Данные для шаблона
	     */
	    $this->success = false; // именно  по не success рисуется  окно
	    $date= array();
	    $date['css'] = $this->getAllHeaderBlock();
	    $date['mes'] = $mes;
	    $date['actionlink'] =  $this->actUri('login')->url();
	    return $this->_view->showform($date);
	}
    }

    /**
     * Обрработчик действия вход
     */
    public function action_login() {
	$rec= array();
	$rec["password"] = $_REQUEST["password"];
	$rec["session_id"] = session_id();
        $this->_model->setsession($rec);
        $_SESSION["cache"] = array();
        $_SESSION["rights"] = array();
        $this->gohome();
    }

    /**
     * Обработчик действия "выход"
     */
    public function action_logout() {
	$this->_model->resetsession(session_id());
        echo "<script>localStorage.removeItem('remember');</script>";
        // Unset all of the session variables.
        session_unset();
        // Finally, destroy the session.
        session_destroy();
        $this->success = false;
        $this->gohome();
    }

    /**
     * алиас для действия выход
     */
    public function logout() {
        $this->action_logout();
    }

    /**
     * Переход на главную страницу
     */
    private function gohome() {
        if (Ajax::isAjaxRequest()) {
            echo "<script>";
            echo "window.location = 'http://{$_SERVER['HTTP_HOST']}'";
            echo "</script>";
        } else
            header("Location: http://{$_SERVER['HTTP_HOST']}");
        exit;
    }

}

?>
