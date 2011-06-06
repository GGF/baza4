<?php

class Auth extends lego_abstract {

    public $success;
    public $user;
    public $rigths;
    static private $instance;

    public function getDir() {
        return __DIR__;
    }

    public function __construct($name=false, $directCall = true) {
        if ($directCall) {
            trigger_error("Нельзя использовать конструктор для" .
                    "создания класса Auth." .
                    "Используйте статический метод getInstance()", E_USER_ERROR);
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
        if (sql::query($sql, array(), SQL_LOG_BLOCK) === false) {
            // неправильный запрос - видимо изза отсутствия таблиц
            $this->install();
        }
    }

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
            $rs = sql::fetchOne($sql1, array(), SQL_LOG_BLOCK);
            if (!empty($rs)) {
                $sql = "SELECT * FROM users " .
                        "WHERE id='{$rs["u_id"]}'";
                $rs = sql::fetchOne($sql, array(), SQL_LOG_BLOCK);
                if (!empty($rs)) {
                    $this->user["username"] = $rs["nik"];
                    $this->user["userid"] = $rs["id"];
                    $this->user["user_id"] = $rs["id"];
                    $this->user["u_id"] = $rs["id"];
                    $this->user["id"] = $rs["id"];
                    $sql = "UPDATE session SET ts=NOW() WHERE session='{$sessionid}'";
                    sql::query($sql, array(), SQL_LOG_BLOCK);
                    // права
                    if (empty($_SESSION["rights"])) {
                        $sql = "SELECT rights.right,type,rtype FROM rights " .
                                "JOIN (rtypes,rrtypes) " .
                                "ON (rtypes.id=type_id " .
                                "AND rrtypes.id=rtype_id) " .
                                "WHERE u_id='{$rs["id"]}'";
                        $res = sql::fetchAll($sql, array(), SQL_LOG_BLOCK);
                        foreach ($res as $rs) {
                            if ($rs["right"] == '1') {
                                $_SESSION["rights"][$rs["type"]][$rs["rtype"]] = true;
                            }
                        }
                    } 
                    $this->rights = $_SESSION["rights"];
                    $this->success = true;
                    return true;
                } else {
                    $mes .= "Не могу найти пользователя по сессии." .
                            "Обратитесь к разработчику!";
                }
            } else {
                $mes .= "Сессия не верна или устарела!";
            }
        }

        // пустая сессия, не восстановлена о базе, не найден пользователь
        if ($_SERVER["SCRIPT_NAME"] != '/index.php') {
            $this->gohome();
            // показать начало чтоб не подменю показывать
        }
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
        $res = sql::fetchOne($sql, array(), SQL_LOG_BLOCK);
        if ($res) {
            $sql = "INSERT INTO session (session,u_id) " .
                    "VALUES ('" . session_id() . "','{$res[id]}')";
            sql::query($sql, array(), SQL_LOG_BLOCK);
        }
        //return print_r($_REQUEST,true);
        $_SESSION["cache"] = array();
        $_SESSION["rights"] = array();
        $this->gohome();
    }

    public function action_logout() {
        $sql = "DELETE FROM session WHERE session='" . session_id() . "'";
        sql::query($sql, array(), SQL_LOG_BLOCK);
        // Unset all of the session variables.
        session_unset();
        // Finally, destroy the session.
        session_destroy();
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
