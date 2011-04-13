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
    require_once "errorhandler.php";
}
?>