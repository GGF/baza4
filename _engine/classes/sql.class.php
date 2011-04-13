<?

class sql {

    /**
     * @var cmsSQL $db
     */
    static $db;
    /**
     * @var cmsSQL $lang
     */
    static $lang;
    /**
     * @var cmsSQL $shared
     */
    static $shared;
    /**
     * @var cmsSQL $sh
     */
    static $sh;

    static function init() {

        profiler::add("Autoexec", "MySQL: Выполнение скриптов до подключения");

        self::$db = &self::$lang;
        self::$sh = &self::$shared;

        $_SERVER[mysql][lang][encoding] = $_SERVER[cmsEncodingSQL];

        self::$lang = new cmsSQL(
                        CMSSQL_CONNECTION_LANG,
                        $_SERVER[mysql][lang]
        );

        profiler::add("Autoexec", "MySQL: Подключение языковой БД");

        // REVERSE

        self::$errors = &sql::$lang->_errors;
        self::$lastQuery = &sql::$lang->_lastQuery;
    }

    // REVERSE

    static $errors;
    static $lastQuery;

    static function error() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "error"), $args);
    }

    static function result() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "result"), $args);
    }

    static function check() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "check"), $args);
    }

    static function insert() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "insert"), $args);
    }

    static function update() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "update"), $args);
    }

    static function nextId() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "nextId"), $args);
    }

    static function lastId() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "lastId"), $args);
    }

    static function query($sql='') {
        profiler::add("Выполнение", "MySQL: Начало выполения запроса");
        $args = func_get_args();
        $ret = call_user_func_array(array(&sql::$lang, "query"), $args);
        profiler::add("Выполнение", "MySQL: Конец выполнения запроса");
        return $ret;
    }

    static function fetch($sql='') {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "fetch"), $args);
    }

    static function fetchAll($sql='') {
        profiler::add("Выполнение", "MySQL(fetchAll): Начало выполения запроса");
        $args = func_get_args();
        $ret = call_user_func_array(array(&sql::$lang, "fetchAll"), $args);
        profiler::add("Выполнение", "MySQL(fetchAll): Конец выполения запроса");
        return $ret;
    }

    static function fetchOne($sql='') {
        profiler::add("Выполнение", "MySQL(fetchOne): Начало выполения запроса");
        $args = func_get_args();
        $ret = call_user_func_array(array(&sql::$lang, "fetchOne"), $args);
        profiler::add("Выполнение", "MySQL(fetchOne): Конец выполения запроса");
        return $ret;
    }

    static function logForce() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "logForce"), $args);
    }

    static function affected() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "affected"), $args);
    }

    static function insertUpdate() {
        $args = func_get_args();
        return call_user_func_array(array(&sql::$lang, "insertUpdate"), $args);
    }

// 2 функции преобразования даты для пикера и базы
    static public function date2datepicker($date) {
        return!empty($date) ? date("d.m.Y", mktime(0, 0, 0, ceil(substr($date, 5, 2)), ceil(substr($date, 8, 2)), ceil(substr($date, 1, 4)))) : date("d.m.Y");
    }

    static public function datepicker2date($date) {
        return substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
    }

}

sql::init();
?>