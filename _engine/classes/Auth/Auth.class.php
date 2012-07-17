<?php

class Auth extends lego_abstract {

    public $success;
    public $user;
    public $rigths;
    public $settings;
    private $_model;
    private $_view;
    public static $lss;
    static private $instance;

    public function getDir() {
        return __DIR__;
    }

    public function __construct($name=false, $directCall = true) {
	// Интернационализируем
	require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'i18n.php';
        if ($directCall) {
            trigger_error(Lang::getString('Auth.errors.noconstructor'), E_USER_ERROR);
        }
        parent::__construct($name);
    }

    static public function getInstance() {
        return (self::$instance === null) ?
                self::$instance = new self('Auth', false) :
                self::$instance;
    }

    public function init() {
        parent::init();
        // проверка на существование
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
    public function getRights($type=false, $action=false) {
        if ($type) {
            if ($action) {
                return $this->rights[$type][$action];
            } else {
                return $this->rights[$type];
            }
        }
        return $this->rights;
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

    public function action_index() {
        $mes = '';
        $sessionid = session_id();
        if (!empty($sessionid)) { // не бывает, сессия создается раньше
            $sql1 = "SELECT * FROM session " .
                    "WHERE session ='{$sessionid}'";
            $rs = sql::fetchOne($sql1);
            if (!empty($rs)) { // не бывает, в авторизации записываем 0 на неудаче
                $uuu = $rs["u_id"];
                if ($rs["u_id"]!=0) {
                    $sql = "SELECT * FROM users " .
                            "WHERE id='{$rs["u_id"]}'";
                    $rs = sql::fetchOne($sql);
                    if (!empty($rs)) {
                        $this->user["username"] = $rs["nik"];
                        $this->user["userid"] = $rs["id"];
                        $this->user["user_id"] = $rs["id"];
                        $this->user["u_id"] = $rs["id"];
                        $this->user["id"] = $rs["id"];
                        $sql = "UPDATE session SET ts=NOW() WHERE session='{$sessionid}'";
                        sql::query($sql);
                        // права
                        if (empty($_SESSION["rights"])) {
                            $sql = "SELECT rights.right,type,rtype FROM rights " .
                                    "JOIN (rtypes,rrtypes) " .
                                    "ON (rtypes.id=type_id " .
                                    "AND rrtypes.id=rtype_id) " .
                                    "WHERE u_id='{$rs["id"]}'";
                            $res = sql::fetchAll($sql);
                            foreach ($res as $rs) {
                                if ($rs["right"] == '1') {
                                    $_SESSION["rights"][$rs["type"]][$rs["rtype"]] = true;
                                }
                            }
                        }
                        $this->rights = $_SESSION["rights"];
                        $this->success = true;
                        // определимся с сессией окна
                        Auth::$lss = !empty($_REQUEST["lss"])?$_REQUEST["lss"]:"lss";
                        return true;
                    } else {
                        $mes .= Lang::getString('Auth.session.cantfinduser');
                    }
                } else {
                    // нет пользователя
                    $mes .= Lang::getString('Auth.session.wrongpassword');
                }
            } else {
                // сессия не верна или устарела, но если первый вход все равно сюда попадет
                // закоментирую
                //$mes .= Lang::getString('Auth.session.old');
            }
        }

        // пустая сессия, не восстановлена по базе, не найден пользователь
        $mes = '<script>localStorage.removeItem("remember")</script>'.$mes;
        //исправляем глюк с зацикливанием неправильно запомненого пароля

        Output::assign('css', $this->getAllHeaderBlock());
        Output::assign('mes', $mes);
        Output::assign('title', 'Авторизация');
        Output::assign('actionlink', $this->actUri('login')->url());
        return $this->fetch('form.tpl');
    }

    public function action_login() {
	$rec= array();
	$rec["password"] = $_REQUEST["password"];
	$rec["session_id"] = session_id();
        $this->_model->setsession($rec);
        $_SESSION["cache"] = array();
        $_SESSION["rights"] = array();
        $this->gohome();
    }

    public function action_logout() {
	$this->_model->resetsession(session_id());
        echo "<script>localStorage.clear();</script>";
        // Unset all of the session variables.
        session_unset();
        // Finally, destroy the session.
        session_destroy();
        $this->success = false;
        $this->gohome();
    }

    public function logout() {
        $this->action_logout();
    }

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
