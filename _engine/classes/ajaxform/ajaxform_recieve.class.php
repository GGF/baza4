<?

class ajaxform_recieve {

    public static $entityDecode = true;
    public static $result = array();
    public static $ajaxform_recieve = false;

    public static function init() {
        header("HTTP/1.0 200 OK");

        $json = json::Json_decode($_REQUEST['json']);

        if ($json) {

            unset($_REQUEST[json]);

            $_REQUEST = array_merge($_REQUEST, $json);
            self::$ajaxform_recieve = true;

            //header("CONTENT-TYPE: TEXT/X-JSON; CHARSET={$_SERVER[Encoding]}");
            header("CONTENT-TYPE: APPLICATION/JSON; CHARSET={$_SERVER[Encoding]}");
        }
        self::$ajaxform_recieve = true;
        ob_start("ajaxform_recieve::process");
    }

    public static function parseTokens($options = array()) {

        if (is_array(self::$result)) {

            foreach (self::$result as $k => $v)
                if (is_string($v))
                    self::$result[$k] = tokens::parse($v, $options);
        } else
            self::$result = tokens::parse(self::$result, $options);

        return true;
    }

    /**
     * 	Функция возвращает обработанный для фронтенда контент
     * 	@param	string	$content	Текущий контент (то, что вывелось на страницу тем или иным образом)
     */
    public static function process($content) {

        /*
         */

        // в контенте есть скрипты для вывода в консоль
        // получается неправильный json
        //$content = trim(html_entity_decode($content));
        return multibyte::Json_encode(array(
            "js" => self::$result,
            "text" => "{$content}",
                ), self::$entityDecode);
    }

    /**
     * 	Функция перекодирует переменную
     */
    public static function decode(&$var, $key) {

        $var = mulibyte::UTF_decode($var);
    }

    /**
     * 	Функция выключает разбор входящей переменной и буферизацию
     */
    public static function raw() {

        array_walk_recursive($_REQUEST, "ajaxform_recieve::decode");

        $content = ob_get_contents();
        ob_end_clean();

        print $content;
    }

}

?>