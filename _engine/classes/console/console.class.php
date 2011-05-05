<?

define("CMSCONSOLE_DEFAULT", "");
define("CMSCONSOLE_ERROR", "error");
define("CMSCONSOLE_WARNING", "warning");
define("CMSCONSOLE_NOTICE", "notice");
define("CMSCONSOLE_PLAIN", "plain");

// сделаем консоль синглтоном

class console extends lego_abstract {

    protected static $instance; // object instance
    private static $html;

    public function getDir() {
        return __DIR__;
    }

    public function __construct($name=false, $directCall = true) {
        if ($directCall) {
            trigger_error("Нельзя использовать конструктор для" .
                    "создания класса console." .
                    "Используйте статический метод getInstance()", E_USER_ERROR);
        }
        parent::__construct($name);
        $this->html = '';
    }

    public static function getInstance() {
        return (self::$instance === null) ?
                self::$instance = new self('console', false) :
                self::$instance;
    }

    public function clear() {
        $this->html = '';
    }

    public function notype($msg, $pane = "", $print = false) {
        return $this->out($msg, $pane, "", $print);
    }

    public function error($msg, $pane = "", $print = false) {
        return $this->out($msg, $pane, "error", $print);
    }

    public function warning($msg, $pane = "", $print = false) {
        return $this->out($msg, $pane, "warning", $print);
    }

    public function notice($msg, $pane = "", $print = false) {
        return $this->out($msg, $pane, "notice", $print);
    }

    public function plain($msg, $pane = "", $print = false) {
        return $this->out($msg, $pane, "plain", $print);
    }

    public function out($msg, $pane = "", $type = "", $print = false) {

        if ($type)
            $type = "_{$type}";

        if (!$msg)
            $msg = "&nbsp;";

        $this->instanse->html .= "<script> cmsConsole{$type}('" .
                sql::check($msg) .
                "', '{$pane}'); </script>";

        if ($print)
            echo $this->html; else
            return $this->html;
    }

    public function action_index() {
        if ($_SERVER[debug][report]) {

            profiler::add("Завершение", "Вывод кешированной страницы");

            $this->out(profiler::export(), "time");

            foreach (sql::$lang->logOut(CMSSQL_REPORT_ARRAY) as $line)
                $this->out($line[0], "mysql", $line[1]);

            $this->out("Сжатие <b>отключено</b>.", "", "notice");
            $this->out("<b>Полное время выполнения: <u>" .
                    floor(profiler::$full * 1000) .
                    " мс</u>.</b>", "", "notice");
            $this->out("");
            Output::assign('scripts', $this->instanse->html);
            return $this->fetch('console.tpl');
        } else {
            return '';
        }
    }

    public function getScripts() {
        if ($_SERVER[debug][report]) {

            profiler::add("Завершение", "Вывод кешированной страницы");

            $this->out(profiler::export(), "time");

            foreach (sql::$lang->logOut(CMSSQL_REPORT_ARRAY) as $line)
                $this->out($line[0], "mysql", $line[1]);

            $this->out("Сжатие <b>отключено</b>.", "", "notice");
            $this->out("<b>Полное время выполнения: <u>" .
                    floor(profiler::$full * 1000) .
                    " мс</u>.</b>", "", "notice");
            $this->out("");
            return $this->instanse->html;
        } else {
            return '';
        }
    }

}

if ($_SERVER[debug][report]) {

    /*
     * ERROR HANDLING
     */

    define("CMSBACKTRACE_RAW", true);
    define("CMSBACKTRACE_PLAIN", true);

    function cmsBacktrace($format = false) {

        ob_start();

        if ($format != CMSBACKTRACE_PLAIN) {
            print "<div><strong>Backtrace:</strong></div>\n";
            print "<div class='trace'>\n";
        }

        $trace = debug_backtrace();

        $n = 0;

        foreach ($trace as $f) {

            if ($f ["function"] != "cmsErrorHandler"
                    && $f ["function"] != "cmsBacktrace") {

                $n++;

                $func = "";
                isset($f ["class"]) and $func .= $f ["class"];
                //isset($f["object"])		and $func .= $f["object"];
                isset($f ["type"]) and $func .= $f ["type"];
                $func .= $f ["function"];

                $file = (isset($f ["file"]) && isset($f ["line"])) ?
                        "in {$f["file"]} on line {$f["line"]}" : "internal";

                if ($format != CMSBACKTRACE_PLAIN)
                    print "		<div>";
                print "{$n}. <strong>{$func}()</strong> {$file}";
                if ($format != CMSBACKTRACE_PLAIN)
                    print "</div>";
                print "\n";
            }
        }

        if ($format != CMSBACKTRACE_PLAIN) {

            print "	</div>\n";
            print "</div>\n";
        }

        if ($format == CMSBACKTRACE_RAW || $format == CMSBACKTRACE_PLAIN)
            return ob_get_clean();
        else
            cmsWarning(ob_get_clean());
    }

    function cmsErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
        // if not an error has been supressed with an @
        if (error_reporting() == 0 && ($errno != E_ERROR || E_USER_ERROR))
            return;
        switch ($errno) {
            case E_ERROR :
                $type = "ERROR";
                break;
            case E_WARNING :
                $type = "WARNING";
                break;
            case E_PARSE :
                $type = "PARSE ERROR";
                break;
            case E_NOTICE :
                $type = "NOTICE";
                break;
            case E_STRICT :
                $type = "STRICT ERROR";
                break;
            case E_USER_ERROR :
                $type = "ERROR";
                break;
            case E_USER_WARNING :
                $type = "WARNING";
                break;
            case E_USER_NOTICE :
                $type = "NOTICE";
                break;
            default :
                $type = "X-WARNING";
                break;
        }

        if (striStr($errmsg, "Use of undefined constant")
                || striStr($errmsg, "Undefined index")
                || striStr($errmsg, "Uninitialized string offset")
                || striStr($errmsg, "Undefined offset"))
            $type = false;
        if ($type
                && (
                (isset($_SERVER ["debug"] ["showNotices"])
                && $_SERVER ["debug"] ["showNotices"]
                && $type == "NOTICE") || $type != "NOTICE")
        ) {

            $cls = ($type == "NOTICE") ? "cmsNotice" : "cmsError";

            // Гарантированный флаш буфера
            // Его нельзя делать, иначе в случае AJAX бакенда он завалит обработчик
            //while (ob_get_level() > 0) { ob_end_flush(); }
            if (is_callable("cmsBacktrace")) {
                $backtrace = cmsBacktrace(CMSBACKTRACE_PLAIN);
            } else {
                ob_start();
                debug_print_backtrace();
                $backtrace = ob_get_clean();
            }

            console::getInstance()->out("\n<b>{$type}:</b> {$errmsg} in file:" .
                    "<b>{$filename}</b> on line <b>{$linenum}</b>", "", "notice");
            console::getInstance()->out("<pre style='margin: 0px; padding: 0px'>" .
                    "{$backtrace}</pre></div>", "", "notice");

            /* echo "\n<div class='{$cls}'><b>{$type}:</b> {$errmsg}
              in file: <b>{$filename}</b> on line <b>{$linenum}</b>.
              <pre style='margin: 0px; padding: 0px'>{$backtrace}</pre></div>";
             */
        }
    }

    set_error_handler("cmsErrorHandler", E_ALL);
}
?>