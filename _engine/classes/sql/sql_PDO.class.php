<?php

// Класс подключения и работы с MySQL сервером через PDO (http://ru.php.net/manual/ru/book.pdo.php)

define("SQL_CONNECTION_LANG", "LANG");
define("SQL_CONNECTION_SHARED", "SHARED");

define("SQL_REPORT_ARRAY", false);
define("SQL_REPORT_HTML", true);
define("SQL_REPORT_CLEAN", true);

define("SQL_FETCH_ID", "fetch-id");
define("SQL_FETCH", false);
define("SQL_FETCHID", "SQL_FETCH_ID");

define("SQL_TYPE_NORMAL", "normal");
define("SQL_TYPE_QUERY", "query");
define("SQL_TYPE_ERROR", "error");
define("SQL_TYPE_WARNING", "warning");
define("SQL_TYPE_NOTICE", "notice");

/**
 * Раньше было block — null, log - true, nolog - false
 * Теперь block — блокирует совсем, log — по-умолчанию, 
 * force — логгит в любом случае
 */
define("SQL_LOG", false);  // default — log, but not display
define("SQL_LOG_BLOCK", null);  // block
define("SQL_LOG_FORCE", true);  // force

class sql_PDO {
    /*
     * CONSTRUCTOR : VARIABLE INITIALIZATION     
     */

    public $_type = "";
    public $_connection = null;
    public $_statement = null;
    public $_affected = 0;
    public $_persistent = false;
    public $_encoding = "";
    public $_base = "";
    public $_host = "";
    public $_maxPacket = false;
    public $_queries = 0;
    public $_errors = 0;
    public $_errorsArray = array();
    public $_warnings = 0;
    public $_log = array();
    public $_logLevel = array();
    public $_logForce = false;
    public $_execTime = 0;
    public $_queryTime = 0;
    public $_fetchTime = 0;
    public $_lastQuery = "";
    public $_tokens = array("AS", "BY", "IF", "IN", "IS", "ON", "OR", "TO",
        "ADD", "ALL", "AND", "ASC", "DEC", "DIV", "FOR", "INT", "KEY", "MOD",
        "NOT", "OUT", "SET", "SSL", "USE", "XOR", "BLOB", "BOTH", "CALL",
        "CASE", "CHAR", "DESC", "DROP", "DUAL", "EACH", "ELSE", "EXIT", "FROM",
        "GOTO", "INTO", "JOIN", "KEYS", "KILL", "LEFT", "LIKE", "LOAD", "LOCK",
        "LONG", "LOOP", "NULL", "READ", "REAL", "SHOW", "THEN", "TRUE", "UNDO",
        "WHEN", "WITH", "ALTER", "CHECK", "CROSS", "FALSE", "FETCH", "FLOAT",
        "FORCE", "FOUND", "GRANT", "GROUP", "INDEX", "INNER", "INOUT", "LEAVE",
        "LIMIT", "LINES", "MATCH", "ORDER", "OUTER", "PURGE", "RIGHT", "RLIKE",
        "TABLE", "UNION", "USAGE", "USING", "WHERE", "WHILE", "WRITE", "BEFORE",
        "BIGINT", "BINARY", "CHANGE", "COLUMN", "CREATE", "CURSOR", "DELETE",
        "DOUBLE", "ELSEIF", "EXISTS", "FIELDS", "HAVING", "IGNORE", "INFILE",
        "INSERT", "OPTION", "REGEXP", "RENAME", "REPEAT", "RETURN", "REVOKE",
        "SCHEMA", "SELECT", "SONAME", "TABLES", "UNIQUE", "UNLOCK", "UPDATE",
        "VALUES", "ANALYZE", "BETWEEN", "CASCADE", "COLLATE", "COLUMNS",
        "CONVERT", "DECIMAL", "DECLARE", "DEFAULT", "DELAYED", "ESCAPED",
        "EXPLAIN", "FOREIGN", "INTEGER", "ITERATE", "LEADING", "NATURAL",
        "NUMERIC", "OUTFILE", "PRIMARY", "REPLACE", "REQUIRE", "SCHEMAS",
        "SPATIAL", "TINYINT", "TRIGGER", "VARCHAR", "VARYING", "CONTINUE",
        "DATABASE", "DAY_HOUR", "DESCRIBE", "DISTINCT", "ENCLOSED", "FULLTEXT",
        "INTERVAL", "LONGBLOB", "LONGTEXT", "OPTIMIZE", "RESTRICT", "SMALLINT",
        "SPECIFIC", "SQLSTATE", "STARTING", "TINYBLOB", "TINYTEXT", "TRAILING",
        "UNSIGNED", "UTC_DATE", "UTC_TIME", "ZEROFILL", "CHARACTER",
        "CONDITION", "DATABASES", "LOCALTIME", "MEDIUMINT", "MIDDLEINT",
        "PRECISION", "PROCEDURE", "SENSITIVE", "SEPARATOR", "VARBINARY",
        "ASENSITIVE", "CONNECTION", "CONSTRAINT", "DAY_MINUTE", "DAY_SECOND",
        "MEDIUMBLOB", "MEDIUMTEXT", "OPTIONALLY", "PRIVILEGES", "REFERENCES",
        "SQLWARNING", "TERMINATED", "YEAR_MONTH", "DISTINCTROW", "HOUR_MINUTE",
        "HOUR_SECOND", "INSENSITIVE", "CURRENT_DATE", "CURRENT_TIME",
        "CURRENT_USER", "LOW_PRIORITY", "SQLEXCEPTION", "VARCHARACTER",
        "DETERMINISTIC", "HIGH_PRIORITY", "MINUTE_SECOND", "STRAIGHT_JOIN",
        "UTC_TIMESTAMP", "LOCALTIMESTAMP", "SQL_BIG_RESULT", "DAY_MICROSECOND",
        "HOUR_MICROSECOND", "SQL_SMALL_RESULT", "CURRENT_TIMESTAMP",
        "MINUTE_MICROSECOND", "NO_WRITE_TO_BINLOG", "SECOND_MICROSECOND",
        "SQL_CALC_FOUND_ROWS"); //, "SQL"
    public $_PREGtokens = array();

    /*
     * CONSTRUCTOR : CLASS INITIALIZATION
     * @param  string  $type  Тип поключаемой базы языковая или разделяемая
     * @param  array  $array  Даннные базы
     */
    function __construct($type, $array) {

        $logLevel = $array["log"];
        $encoding = $array["encoding"] ? $array["encoding"] :
                $_SERVER["EncodingSQL"];

        if (!is_array($logLevel) || !count($logLevel))
            $logLevel = array("SQL_TYPE_ERROR" => true);

        // always log normal
        $logLevel["SQL_TYPE_NORMAL"] = true;

        $this->_type = $type;

        $this->_tokens = array_reverse($this->_tokens);
        foreach ($this->_tokens as $t) {
            $this->_PREGtokens["patterns"][] = "$t ";
            $this->_PREGtokens["replaces"][] = "<b>{$t}</b> ";
            $this->_PREGtokens["patterns"][] = " $t";
            $this->_PREGtokens["replaces"][] = " <b>{$t}</b>";
        }

        $this->_logLevel = $logLevel;

        $this->_persistent = $array["persistent"] ? true : false;

        try {
            // соединение одновременно с выбором базы
            $this->_connection = new PDO("mysql:host={$array["host"]};dbname={$array["base"]}",
                            $array["name"], $array["pass"],
                            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$encoding}",
                                PDO::ATTR_PERSISTENT => $this->_persistent));
        } catch (PDOException $e) {
            // На деле нужно вернуть ошибку туда откуда все началось
            throw(new Exception("Ошибка подключения: " . $e->getMessage()));
        }
          $this->_base = $array["base"];
          $this->_host = $array["host"];
          $this->_encoding = $encoding;

    }

    function maxPacket() {

        if (!$this->_maxPacket) {

            // Определение максимальной длины пакета
            $r =
                    $this->fetch("SHOW GLOBAL VARIABLES LIKE 'max_allowed_packet';");
            $this->_maxPacket = $r['Value'];
        }

        return $this->_maxPacket;
    }

    /**
     * 	Возвращает UNIX TIMESTAMP с учетом микросекунд
     * 	@return		float
     */
    function time() {

        return array_sum(explode(" ", microTime()));
    }

    /**
     * 	Возвращает форматированное время в миллисекундах
     *  @param  int  $time
     * 	@return  float
     */
    function timeFormat($time) {

        return number_format($time * 1000, 0, ".", " ") . " мс";
    }

    /**
     * Включает/выключает принудительный немедленный вывод ошибок
     * @param  bool  $force Соответственно включить или выключить
     * 
     * @return string
     */
    function logForce($force = true) {

        $this->_logForce = $force;
        return ($force) ? "<b>{$this->_type}:</b> 
        Включен режим вывода ошибок MySQL." : "<b>{$this->_type}:</b>
        Выключен режим вывода ошибок MySQL.</b>";
    }

    /**
     * 	Записывает новый элемент в массив отчета
     * 	@param  string  $message    Сообщение
     * 	@param  string  $type       Тип сообщения 
     *                  (SQL_TYPE_[NORMAL|QUERY|ERROR|WARNING|NOTICE])
     * 	@param  bool    $logOverride    Выводить ли сообщение 
     *          при выключенном режиме отображения сообщений этого типа
     */
    function log($message, $type = SQL_TYPE_NORMAL, $logOverride = false) {

        if ($type == SQL_TYPE_WARNING)
            $this->_warnings++;
        if ($type == SQL_TYPE_ERROR) {

            $this->_errors++;
            $this->_errorsArray[] = $message;
        }

        $this->_log[$this->_queries][] = array($type, $message, $logOverride);

        // Если стоит форсированный режим вывода ошибок — выврдим сразу
        if ($type == "error" && $this->_logForce)
            $this->error(true);
    }

    /**
     * 	Возвращает отформатированный запрос
     * 	@param  string  $query	Запрос
     * 	@return string
     */
    function format($query) {

        $regs = array();
        $query = str_replace("\r", "", $query);
        preg_match_all('/^([\t]+)/sim', $query, $regs, PREG_PATTERN_ORDER);
        if (count($regs[0])) {
            $a = array();
            foreach ($regs[0] as $v) {
                $a[] = mb_strlen($v);
            }

            $a = '/^' . str_repeat('\t', min($a)) . '/sim';
            $xquery = array();
            foreach (explode("\n", $query) as $q)
                $xquery[] = preg_replace($a, '', $q);

            $query = "\n" . implode("\n", $xquery);
        }

        return str_replace($this->_PREGtokens['patterns'], $this->_PREGtokens['replaces'], $query);
    }

    /**
     * 	Выводит лог
     * 	@param	bool	$html   Выводить в виде html (иначе в виде массива) 
     *                          [SQL_REPORT_ARRAY|SQL_REPORT_HTML]
     * 	@param	bool	$clean	
     *                  Очищать ли лог после вывода [SQL_REPORT_CLEAN]
     * 	@return	mixed
     */
    function logOut($html = SQL_REPORT_HTML, $clean = false) {

        $array = array();
        $array[] = array("<b>Отчет для MySQL соединения «{$this->_type}».</b>",
            CMSCONSOLE_NOTICE);
        $array[] = array("Успешное " .
            ($this->_persistent ? "постоянное" : "обычное") .
            " подключение к <b>{$this->_base}@{$this->_host}</b>," .
            "кодировка соединения: <b>{$this->_encoding}</b>.",
            CMSCONSOLE_NOTICE);
        foreach ($this->_log as $logLine) {
            // По-умолчанию считаем, что все ок :)
            $hasErrors = false;
            $hasWarnings = false;
            $hasNotices = false;
            $hasOverrides = false;

            // Первый цикл, выясняем, что внутри
            if (count($logLine)) {

                foreach ($logLine as $l) {

                    list($type, $msg, $override) = $l;

                    if ($override == SQL_LOG_FORCE)
                        $hasOverrides = true;
                    if ($type == SQL_TYPE_WARNING)
                        $hasWarnings = true;
                    if ($type == SQL_TYPE_ERROR)
                        $hasErrors = true;
                    if ($type == SQL_TYPE_NOTICE)
                        $hasNotices = true;
                }

                $out = false;

                // Второй цикл и контроль вывода
                foreach ($logLine as $l) {

                    list($type, $msg, $override) = $l;
                    $msg = trim($msg);

                    $consoleType = ($type == SQL_TYPE_WARNING
                            || $type == SQL_TYPE_ERROR
                            || $type == SQL_TYPE_NOTICE) ?
                            $type : "";

                    // ошибки с SQL_LOG_BLOCK до сюда даже не дойдут
                    if (
                    // Есть оверрайд
                            $hasOverrides ||
                            // Нормальный вывод
                            $type == SQL_TYPE_NORMAL ||
                            // Является запросом и их можно логгить, 
                            // или внутри есть ошибки/предупреждения 
                            // и их можно логгить
                            (
                            ($type == SQL_TYPE_QUERY && array_key_exists(SQL_TYPE_QUERY, $this->_logLevel)) || (
                            ($hasErrors && array_key_exists(SQL_TYPE_ERROR, $this->_logLevel)) ||
                            ($hasWarnings && array_key_exists(SQL_TYPE_WARNING, $this->_logLevel))
                            )
                            ) ||
                            // Является ошибкой/предупреждением/напоминанием 
                            // и его можно логгить
                            ($type == SQL_TYPE_ERROR && array_key_exists(SQL_TYPE_ERROR, $this->_logLevel)) ||
                            ($type == SQL_TYPE_WARNING && array_key_exists(SQL_TYPE_WARNING, $this->_logLevel)) ||
                            ($type == SQL_TYPE_NOTICE && array_key_exists(SQL_TYPE_NOTICE, $this->_logLevel))
                    ) {

                        if ($type == SQL_TYPE_QUERY)
                            $msg = $this->format($msg);

                        $cssType = "mysql_{$type}";

                        if ($hasNotices)
                            $cssType .= " mysql_" . SQL_TYPE_NOTICE;
                        if ($hasWarnings)
                            $cssType .= " mysql_" . SQL_TYPE_WARNING;
                        if ($hasErrors)
                            $cssType .= " mysql_" . SQL_TYPE_ERROR;

                        $array[] = array("<pre class='mysql {$cssType}'><b>" .
                            UCFirst($type) .
                            ":</b> {$msg}</pre>", $consoleType);

                        $out = true;
                    }
                }
            }
        }

        $array[] = array(print_r($this->_logLevel, true), CMSCONSOLE_NOTICE);
        $array[] = array("Запросов: <b>{$this->_queries}</b>," .
            "Предупреждений: <b>{$this->_warnings}</b>," .
            "Ошибок: <b>{$this->_errors}</b>.", CMSCONSOLE_NOTICE);
        $array[] = array("<b>Полное время выполнения: <u>" .
            $this->timeFormat($this->_execTime) . "</u>, запросов:  <u>" .
            $this->timeFormat($this->_queryTime) .
            "</u>, разбора данных: <u>" .
            $this->timeFormat($this->_fetchTime) .
            "</u>.</b>", CMSCONSOLE_NOTICE);

        if ($clean)
            $this->_log = array();

        if ($html) {

            ob_start();
            foreach ($array as $a)
                echo "<div>{$a[0]}</div>";
            return ob_get_clean();
        } else
            return $array;
    }

    /**
     * 	Функция возвращает или выводит отчет о ПОСЛЕДНЕЙ ошибке
     * 	@param	bool  $print	Печатать или возвращать
     * 	@return  mixed[string|void]
     */
    function error($print = false) {

        $error = $this->_connection->errorCode(); // вызов PDO 

        if ($error==="00000") {
            return null;
        }

        $return = "<b>MYSQL ERROR #{$error}:</b> " .
                $this->_connection->errorInfo();

        if ($print) {
            echo "<div class='cmsError'>{$return}<br><b>" .
            "IN QUERY:</b><br><pre class='mysql'>" .
            $this->format(htmlSpecialChars($this->_lastQuery)) .
            "\n" . cmsBacktrace(CMSBACKTRACE_RAW) .
            "</pre></div>";
        } else {
            return $return;
        }
    }

    /**
     * 	Функция производит escape переменной
     * 	@param	string  $text		Строка
     * 	@return	string
     */
    function check($text) {

        if (is_array($text)) {

            foreach ($text as $k => $v)
                $text[$k] = $this->check($v);

            return $text;
        } else {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"),
                       array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z') , $text);
        }
    }

    /**
     * Функция возвращает будущий ID — поле c AUTO_INCREMENT, 
     * функция не безопасна, т.к. сразу после возврата другой процесс 
     * может что-либо вставить и изменить поле
     * @return	int
     */
    function nextId($table) {

        $res = $this->fetch("SHOW TABLE STATUS LIKE '{$table}'");
        return $res['Auto_increment'];
    }

    /**
     * 	Функция возвращает последний вставленный ID — поле c AUTO_INCREMENT
     * 	@return	int
     */
    function lastId() {
        return $this->_connection->lastInsertId();
    }

    /**
     * Функция возвращает кол-во строк, которые были затронуты 
     * последним действием
     * @return	int
     */
    function affected() {
        return $this->_affected;
    }

    /**
     * 	Функция производит замену токенов %ххх% на переменные массива
     * 	@param	string	$SQL		Запрос
     * 	@param	array		$array	Массив для подстановки
     *  @return  string
     */
    function prepare($SQL, $array) {

        if (count($array))
            foreach ($array as $k => $v) {

                $SQL = str_replace("%{$k}%", $this->check(strval($v)), $SQL);
            }
        $this->_statement = $this->_connection->prepare($SQL);
        return $SQL;
    }

    /**
     * 	Функция осуществляет разбор массива подстановки и выполняет запрос
     * 	@param	string  $SQL    Запрос
     * 	@param	array|bool  $array	Массив для постановки, 
     * для совместимости может принять сразу лог
     * 	@param	bool    $log    Режим лога: SQL_LOG — пишем, 
     * но не выводим, _FORCE — пишем и выводим всегда, 
     * _BLOCK — блокируем запись, но если будет ошибка — 
     * все равно выведется в консоль, но вместо запроса 
     * будет фраза о блокировке
     *  @return bool
     */
    function query($SQL, $array = array(), $log = SQL_LOG) {

        $time = $this->time();

        $stack = is_callable("cmsBacktrace") ? "\n" .
                cmsBacktrace(CMSBACKTRACE_RAW) :
                "Функция cmsBacktrace еще не зарегистрирована.";

        $SQL = $this->prepare($SQL, $array);
        $SQLlog = ($log !== SQL_LOG_BLOCK) ? $SQL :
                "Запись запроса заблокирована через LOG_BLOCK.";
        // exec
        $r = $this->_statement->execute();
        $this->_affected = $this->_statement->rowCount();
        // suppress or not suppress ;)
        $this->_queries++;
        $this->_lastQuery = $SQLlog;

        $this->log($SQLlog, SQL_TYPE_QUERY, $log);

        if (!$r) {
            $this->log($this->error() .
                    $stack, SQL_TYPE_ERROR, SQL_LOG_FORCE);
            // ошибки в лог идут вне зависимости от параметра
        }

        $time = $this->time() - $time;
        $this->_queryTime += $time;
        $this->_execTime += $time;

        return $r;
    }

    /**
     * 	Функция выполняет result() либо как обычный result(), 
     * либо делает запрос и возвращает первую строку ответа
     * 	@param	resource|string  $res		
     * Если передана строка — делает запрос, если передан ресурс — 
     * возвращает пустой ответ
     * 	@param	array  $array	
     * 	@param	int  $i			Номер row для возврата
     * 	@param	bool  $log		Тип лога, см. функцию query()
     * 	@return  array
     */
    function result($res, $array = array(), $i = 0, $log = SQL_LOG) {

        $time = $this->time();

        if ($res && !is_resource($res)) {

            $return = $this->resultOne($res, $array, $i, $log);
        } else {

            $return = ''; // @mysql_result($res, $i); // $i — обязательный параметр!
            // C мускулем  использовался mysql_result, с PDO веррнем пустой ответ 
            // вообще не знаю где использовать
        }

        $time = $this->time() - $time;
        $this->_execTime += $time;

        return $return;
    }

    /**
     * 	Функция выполняет запрос и возвращает первую строку
     * 	@param	string  $query	Запрос
     * 	@param	array  $array	Массив для подстановки в запрос 
     * 	@param	int  $i	Номер row для возврата
     * 	@param	bool  $log	Тип лога, см. функцию query()
     * 	@return  array
     */
    function resultOne($query, $array = array(), $i = 0, $log = SQL_LOG) {

        $this->query($query, $array, $log);
        $res = $this->_statement->fetchAll();
        if ( is_array($res) && count($res)>0 )
            return $res[$i];
        else
            return array();
    }

    /**
     * 	Функция выполняет фетч либо как обычный fetch_assoc(), 
     * либо делает запрос и возвращает первую строку ответа
     * 	@param	resource|string  $res	Если передана строка — 
     * делает запрос, если передан ресурс — воззвращает пустой массив
     * 	@param	array  $array	Массив для подстановки в запрос 
     * 	@param	bool  $log		Тип лога, см. функцию query()
     * 	@return	array
     */
    function fetch($res, $array = array(), $log = SQL_LOG) {

        $time = $this->time();

        if (is_string($res)) {

            $return = $this->resultOne($res, $array, $log);
        } else {

            $return = array();
        }

        $time = $this->time() - $time;
        $this->_fetchTime += $time;
        $this->_execTime += $time;

        return $return;
    }

    /**
     * 	Функция выполняет запрос и возвращает все строки
     * 	@param  string  $query	Запрос
     * 	@param  array  $array	Массив для подстановки в запрос 
     * 	@param  bool  $log	Тип лога, см. функцию query()
     * 	@param  bool  $id	Если передана константа 
     * SQL_FETCH_ID — вернет ассоциативный массив с полем ID из 
     * строки ответа в качестве ключей [SQL_FETCH|SQL_FETCH_ID]
     * 	@return	array
     */
    function fetchAll($query, $array = array(), $log = SQL_LOG, $id = SQL_FETCH) {

        $time = $this->time();
        $output = array();

        if ($this->query($query, $array, $log)) {
            $res = $this->_statement->fetchAll();
            if (!empty($res[0]["id"]) && $id == SQL_FETCH_ID) {
                foreach ($res as $value) {
                    $output[$value["id"]][] = $value;
                }
            } else {
                $output = $res;
            }
        }

        $time = $this->time() - $time;
        $this->_execTime += $time;

        return $output;
    }

    /**
     * 	Функция выполняет запрос и возвращает первую строку
     * 	@param	string  $query  Запрос
     * 	@param	array  $array  Массив для подстановки в запрос 
     * 	@param	bool  $log  Тип лога, см. функцию query()
     * 	@return	array
     * */
    function fetchOne($query, $array = array(), $log = SQL_LOG) {
        return $this->fetch($query, $array, $log);
    }

    /**
     * 	Функция возвращает имена полей на основе массива для вставки, 
     * делает она это по первому подмассиву
     * используется в insert() и insertUpdate(), проверка на существование 
     * полей в таблице не производится
     * 	@param  array  $data       
     * Массив для вставки, сделан ссылкой для экономии памяти
     * 	@param  bool  $returnArray	
     * Возвращать массив или сразу делать implode
     * 	@return  mixed[array|string]
     */
    function insert_getFields(&$data, $returnArray = false) {

        $fields = array();
        foreach ($data as $k => $v) {
            $fields[] = "`{$k}`";
            $v=$v; // блокирую ворнинг неиспользования переменной $v
        }

        return $returnArray ? $fields : implode(", ", $fields);
    }

    /**
     * 	Функция подготавливает входной массив для вставки
     * 	@param  array  $data  Массив для вставки, 
     * 	@param  array  $keys  Только избранные ключи, 
     * 	@return  string
     */
    function insert_prepare($data,$keys=array()) {
        $sql = array();
        if (empty($keys)) {
            foreach ($data as $k => $v)
                $sql[$k] = "'" . $this->check($v) . "'";
        } else {
            foreach ($keys as $value) {
                $sql[$value] = "'".$this->check($data[str_replace('`', '', $value)])."'";
            }
        }
        return "(" . implode(", ", $sql) . ")";
    }

    /**
     * 	Функция разбирает и подготавливает массив для вставки, 
     * и производит непосредственно вставку в таблицу, 
     * с последующей оптимизацией, возвращает affected rows
     * 	@param  string  $table  Имя таблицы
     * 	@param  array  $data  Массив для вставки. 
     * Формат может быть одним из следующих: сразу массив элементов, 
     * массив из массивов элементов — либо линейный, без ключей, 
     * либо с ключами (ключи — имена полей, в таком случае может быть 
     * неполная вставка, в произвольном порядке). Порядок следования 
     * элементов должен быть один на все подмассивы.
     * 	@param	bool  $log		Тип лога, см. функцию query()
     * 	@return	int
     */
    function insert($table, $data, $log = SQL_LOG) {

        if (isset($data[0]) && is_array($data[0])) { // Множественная вставка
            $time = $this->time();

            // Определяем, является ли вставка «именованной»
            $fields = isset($data[0][0]) ? "" : "(" .
                    $this->insert_getFields($data[0]) . ") ";
            $values = array();

            $SQL = array();
            $SQLn = 0;
            $SQLbase = "INSERT INTO `{$table}` {$fields} VALUES ";

            foreach ($data as $dataRow)
                $values[] = $this->insert_prepare($dataRow);

            $SQL[0] = $SQLbase;
            foreach ($values as $SQLrow) {
                if (mb_strlen($SQL . $SQLrow . ", ") > $this->maxPacket()) {
                    $SQLn++;
                    $SQL[$SQLn] = $SQLbase;
                    $this->log("insert(): Новый подзапрос №{$SQLn}", SQL_TYPE_WARNING);
                }
                $SQL[$SQLn] .= $SQLrow . ", ";
            }

            $time = $this->time() - $time;
            $this->_execTime += $time;

            foreach ($SQL as $query)
                $this->query(mb_substr($query, 0, -2), array(), $log);
        } elseif (isset($data[0]) && !is_array($data[0])) { // plain
            $values = $this->insert_prepare($data);

            $this->query("INSERT INTO `{$table}` VALUES {$values}", array(), $log);
        } else { // named
            $fields = $this->insert_getFields($data);
            $values = $this->insert_prepare($data);

            $this->query("INSERT INTO `{$table}` ({$fields}) VALUES {$values}", array(), $log);
        }

        unset($data);
        unset($SQL);
        unset($query);
        unset($values);

        return $this->affected();
    }

    /**
     * 	Функция обновляет с вставкой таблицу, можно использовать для 
     * массового update, возвращает affected rows
     * 	@param	string	$table		Таблица для обновления
     * 	@param	array		$data			Массив из массивов 
     * с данными, важно заметить, что имена полей для запроса берутся из 
     * первого подмассива
     * 	@param	array		$exclude	Список полей, не участвующих 
     * в обновлении (обычно ID — те поля, по которым будет контролироваться 
     * уникальность записей, как-то так)
     * 	@param	bool		$log	Тип лога, см. функцию query()
     * 	@return	int
     */
    function insertUpdate($table, $data, $exclude = array("id"), $log = SQL_LOG) {

        if (!is_array($data) || count($data) < 1) {
            return false;
        }

        $fieldsintable = sql::fetchAll("SHOW COLUMNS FROM `{$table}`");
        foreach ($fieldsintable as $value) {
            $fields[]="`{$value["Field"]}`";
        }
        $fieldsintable = $fields;
        $data_first_elem = reset($data);
        $fields = $this->insert_getFields($data_first_elem, true);
        sort($fields);
        sort($fieldsintable);
        $fields = array_intersect((array)$fields,$fieldsintable);
        $values = array();

        foreach ($data as $dataRow) {
            $values[] = $this->insert_prepare($dataRow,$fields);
        }

        $upd = array();
        $sql = "INSERT INTO `{$table}` (" . implode(", ", $fields) .
                ") VALUES ";
        foreach ($fields as $field) {
            if (@!in_array(substr($field, 1, -1), $exclude)) {
                $upd[] = "{$field} = VALUES({$field})";
            }
        }
        $upd = " ON DUPLICATE KEY UPDATE " . implode(", ", $upd);

        $SQLs = array();
        $n = 0;

        foreach ($values as $value) {

            if (mb_strlen($sql) + mb_strlen($SQLs[$n] . $value . ", ") +
                    mb_strlen($upd) > $this->maxPacket()) {
                $n++;
                $this->log("insertUpdate(): Новый подзапрос №{$n}", SQL_TYPE_WARNING);
            }

            $SQLs[$n] .= $value . ", ";
        }

        foreach ($SQLs as $SQLn) {
            $this->query($sql . substr($SQLn, 0, -2) . $upd, array(), $log);
        }

        // Оптимизируем, если нет ошибок
        //if (!$this->error()) 
        //$this->query("OPTIMIZE TABLE {$table}", array(), SQL_LOG_BLOCK);
        // Cleanup
        unset($data);
        unset($SQLs);
        unset($upd);

        return $this->affected();
    }

    /**
     * 	Функция обновляет таблицу — обычный update по условию, 
     * возвращает affected rows
     * 	@param	string  $table		Таблица для обновления
     * 	@param	array  $where		Подстрока запроса для WHERE, 
     * может содержать токены %xxx%
     * 	@param	array  $array		Массив для подстановки 
     * в запрос (также тут может быть и сразу тип лога, 
     * см. функцию query() — это DEPRECATED!)
     * 	@param	array  $data			Массив из массивов 
     * с данными, важно заметить, что имена полей для запроса берутся 
     * из первого подмассива
     * 	@param	bool  $log    Тип лога, см. функцию query()
     * 	@return	int
     */
    function update($table, $where, $array, $data = false, $log = SQL_LOG) {

        // Раньше не было массивов, поэтому сейчас для обратной 
        // совместимости массив можно не указывать
        // backward compatibility — DEPRECATED!!!
        if (!$data) {

            $data = $array;
            $array = array();
        }

        $time = $this->time();
        $sql = array();

        foreach ($data as $k => $v)
            $sql[] = $this->check($k) . " = '" . $this->check($v) . "'";
        $this->query("UPDATE `{$table}` SET " . implode(", ", $sql) .
                " WHERE {$where}", $array, $log);

        $time = $this->time() - $time;
        $this->_execTime += $time;
        
        return $this->affected();
    }

}

?>