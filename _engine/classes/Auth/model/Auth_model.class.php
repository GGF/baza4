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
	extract($rec);
        // ------------------------------------
        $sql = "DELETE FROM session WHERE session='{$sesion_id}'";
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
                    "VALUES ('{$sesion_id}','0')";
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

}

?>
