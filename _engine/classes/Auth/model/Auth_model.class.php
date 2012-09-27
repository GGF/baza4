<?php
/**
 * Класс работы с базой аутентификации
 *
 * @author Игорь
 */
class Auth_model extends model {

    /**
     * Конструктор
     * @return int
     */
    public function __construct() {
	return 0;
    }

    /**
     * Замена абстрактной для правильной инсталяции
     * @return system
     */
    public function getDir() {
	return __DIR__;
    }

    /**
     * Инициализация
     */
    public function init() {
        // Почистим устаревшие сессии
        $sql = "DELETE FROM session " .
                "WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(ts) > 3600*8";
        if (sql::query($sql) === false) {
            // неправильный запрос - видимо изза отсутствия таблиц
            $this->install();
        }

    }

    /**
     * Записать в базу данных структуру для работы именно этого лего, вызывается
     * из init прии ошибке работы с базой
     * TODO:Следует убрать, перенеся это дело в модель
     * @param array $replace
     */
    public function install($replace=array()) {
        if (sql::queryfile($this->dir . "/install.sql",$replace)) {
               echo "install complete";
        }
    }

    /**
     * Установить сессию
     * @param array $rec
     */
    public function setsession($rec) {
	$session_id = $rec["session_id"];
	$password = $rec["password"];
        // ------------------------------------
        $sql = "DELETE FROM session WHERE session='{$session_id}'";
        sql::query($sql);
        $sql = "SELECT * FROM users " .
                "WHERE password='{$password}'";
        $res = sql::fetchOne($sql);
        if ($res) {
            $sql = "INSERT INTO session (session,u_id) " .
                    "VALUES ('{$session_id}','{$res[id]}')";
            sql::query($sql);
        } else {
            // неудачная авторизация запишем минус один и в главной сообщим
            // не минус один, а ноль. у нас ансигнед ИД
            $sql = "INSERT INTO session (session,u_id) " .
                    "VALUES ('{$session_id}','0')";
            sql::query($sql);
        }
    }

    /**
     * Очистить сессию
     * @param string $session Идентификатор сессии
     */
    public function resetsession($session) {
	$sql = "DELETE FROM session WHERE session='{$session}'";
	sql::query($sql);
    }

    /**
     * Проверка сессии получене данных о пользователе
     * @var string $sessionid Идентификатор  сессии
     * @return array
     */
    public function checksession($sessionid) {
	$rec = array();
        $sql1 = "SELECT * FROM session " .
                    "WHERE session ='{$sessionid}'";
        $rs = sql::fetchOne($sql1);
	if (!empty($rs)) { // не бывает, в авторизации записываем 0 на неудаче
	    if ($rs["u_id"]!=0) {
		$sql = "SELECT * FROM users " .
			"WHERE id='{$rs["u_id"]}'";
		$rs = sql::fetchOne($sql);
		if (!empty($rs)) {
		    $rec["user"]["username"] = $rs["nik"];
		    $rec["user"]["userid"] = $rs["id"];
		    $rec["user"]["user_id"] = $rs["id"];
		    $rec["user"]["u_id"] = $rs["id"];
		    $rec["user"]["id"] = $rs["id"];
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
		    $rec["rights"] = $_SESSION["rights"];
		    $rec["success"] = 'Auth.session.success';
		    // определимся с сессией окна
		    Auth::$lss = !empty($_REQUEST["lss"])?$_REQUEST["lss"]:"lss";
		} else {
		    $rec["success"] = 'Auth.session.cantfinduser';
		}
	    } else {
		// нет пользователя
		$rec["success"] = 'Auth.session.wrongpassword';
	    }
        } else {
	    // не  оказалось сессии в базе
	    $rec["success"] = 'Auth.session.nosession';
	}
	$rec["mes"] .= Lang::getString($rec["success"]);
	return $rec;
    }


}

?>
