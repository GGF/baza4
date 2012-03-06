<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'i18n.php';

class Auth extends lego_abstract {

    public $success;
    public $user;
    public $rigths;
    public $settings;
    public static $lss;
    static private $instance;

    public function getDir() {
        return __DIR__;
    }

    public function __construct($name=false, $directCall = true) {
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
        // Почистим устаревшие сессии
        $sql = "DELETE FROM session " .
                "WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(ts) > 3600*8";
        if (sql::query($sql) === false) {
            // неправильный запрос - видимо изза отсутствия таблиц
            //$this->install();
        }
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
        if (!empty($sessionid)) {
            $sql1 = "SELECT * FROM session " .
                    "WHERE session ='{$sessionid}'";
            $rs = sql::fetchOne($sql1);
            if (!empty($rs)) {
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
                    // насстройки
//                    $sql="SELECT users__settings_types.key,value FROM users__settings JOIN users__settings_types ON users__settings_types.id=type_id WHERE user_id='{$this->user["id"]}'";
//                    $res = sql::fetchAll($sql);
//                    $_SESSION["user_setting"] = $res;
//                    $this->settings = $res;
                    $this->success = true;
                    // определимся с сессией окна
                    Auth::$lss = !empty($_REQUEST["lss"])?$_REQUEST["lss"]:"lss";
                    return true;
                } else {
                    $mes .= Lang::getString('Auth.session.cantfinduser');
                }
            } else {
                $mes .= Lang::getString('Auth.session.old');
            }
        }

        // пустая сессия, не восстановлена по базе, не найден пользователь

        Output::assign('css', $this->getAllHeaderBlock());
        Output::assign('mes', $mes);
        Output::assign('title', 'Авторизация');
        Output::assign('actionlink', $this->actUri('login')->url());
        return $this->fetch('form.tpl');
    }

    public function action_login() {

        // ------------------------------------
        $sql = "SELECT * FROM users " .
                "WHERE password='{$_REQUEST["password"]}'";
        $res = sql::fetchOne($sql);
        if ($res) {
            $sql = "INSERT INTO session (session,u_id) " .
                    "VALUES ('" . session_id() . "','{$res[id]}')";
            sql::query($sql);
        }
        //return print_r($_REQUEST,true);
        $_SESSION["cache"] = array();
        $_SESSION["rights"] = array();
        $_SESSION["user_setting"]=array();
        $this->gohome();
    }

    public function action_logout() {
        $sql = "DELETE FROM session WHERE session='" . session_id() . "'";
        echo "<script>localStorage.clear();</script>";
        sql::query($sql);
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
