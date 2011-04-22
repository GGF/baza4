<?php

abstract class lego_abstract extends JsCSS {

    static protected $runned_legos = array();
    static protected $all_legos = array();
    private $name;
    private $default_action = "index";
    private $lego_runner;
    private $output_handler;
    private $current_action;
    protected $output = "";
    protected $dir;
    static public $sitemap;
    static public $get;

    static public function staticConsturct() {
        if (class_exists("SiteMap"))
            self::$sitemap = new SiteMap();
        self::$get = new QueryString($_GET);
    }
    public function __construct($name = false) {
        parent::__construct();
        self::$all_legos[] = $this;
        $this->lego_runner = lego_abstract::current();
        self::$runned_legos[] = $this;
        if (!$name)
            $name = get_class($this);
        $this->name = $name;
        $this->dir = $this->getDir();
        $this->init();
    }
    public function init() {
        
    }
    public function getVersion() {
        return 0;
    }

    public function getSitemap() {
        return self::$sitemap;
    }

    public function setDefaultAction($default_action) {
        $this->default_action = $default_action;
    }

    public function getDefaultAction() {
        return $this->default_action;
    }

    function getActions() {
        $methods = get_class_methods(get_class($this));
        $ret = array();
        foreach ($methods as $one)
            if (strpos($one, "action_") === 0 && strpos($one, "action_submit") !== 0)
                $ret[] = substr($one, strlen("action_"));
        return $ret;
    }

    public function run() {
        $this->beforeRun();
        Output::assign("lego", $this);
        $act = $this->getAction();
        $method_name = "action_$act";
//        if (!method_exists($this, $method_name))
//            $method_name = "action_default";
        //if (method_exists($this, $method_name)) {
            try {
                $this->current_action = $act;
                $this->output = call_user_func_array(array($this, $method_name), $this->getActionParams());
                //$this->output = $this->{$method_name}();
            } catch (Exception $e) {
                if (preg_match("/Table .*? doesn't exist/i", $e->getMessage())) {
                    $this->install();
                }
                $this->output = $this->_404("wrong action ($method_name) [class=" . get_class($this) . "]" . $e->getMessage());
                //throw $e;
            }
        //}
        //else
        //  $this->output = $this->_404("wrong action ($method_name) [class=" . get_class($this) . "]");
        Output::assign("lego", $this->runner());
        $this->afterRun();
        return $this;
    }

    public function getAction() {
        $act = $this->_get($this->name, $this->default_action);
        if (!is_array($act))
            return $act;
        if (isset($act["act"]))
            return $act["act"];
        return $this->default_action;
    }

    private function beforeRun() {
        /* if(self::isLegoRunned($this->getName()))
          throw new Exception("Р›РµРіРѕ СЃ РёРјРµРЅРµРј '{$this->getName()}' Р·Р°РїСѓС‰РµРЅРѕ РґРІР°Р¶РґС‹"); */
        //console::getInstance()->out($this->getName());
    }

    private function afterRun() {
        array_pop(self::$runned_legos);
        if ($this->_get("ajax") == $this->getName()) {
            echo $this->output;
            echo console::getInstance()->getScripts();
            die;
        }
    }

    static public function isLegoRunned($name) {

        foreach (self::$runned_legos as $lego)
            if ($lego->getName() == $name)
                return true;
        return false;
    }

    static public function isLegoExist($name) {
        foreach (self::$all_legos as $lego)
            if ($lego->getName() == $name)
                return true;
        return false;
    }

    static public function getAllNames() {
        $ret = array();
        foreach (self::$all_legos as $lego) {
            $ret[] = $lego->getName();
        }
        return array_unique($ret);
    }

    public function _404($text) {
        return($text);
    }

    public function _goto($url) {
        $ajax_element = $this->_get("ajax");
        if ($ajax_element) {
            $info = parse_url($url);
            $get = array();
            if (!empty($info['query']))
                parse_str($info['query'], $get);
            //$info['query'] = http_build_query(array_merge($get, array(Ajax::key() => $ajax_element)));
            $url = (isset($info['scheme']) ? $info['scheme'] . "://" : "http://")
                    . (isset($info['host']) ? $info['host'] : $_SERVER[HTTP_HOST])
                    . (isset($info['path']) ? $info['path'] : "")
                    . (isset($info['query']) ? "?" . $info['query'] : "");
            echo "<script>";
            //echo "alert('{$url}');";
            echo "window.location = '{$url}'";
            echo "</script>";
            exit;
        } else
            header("Location: {$url}");
        exit;
    }

    public function _gotoAct($action) {
        $this->_goto($this->actUri($action)->url());
    }

    public function getName() {
        return $this->name;
    }

    public function getOutput() {
        if (is_callable($this->output_handler))
            return call_user_func($this->output_handler, $this->output, $this);
        return $this->output;
    }

    public function setOutputHandler($func) {
        $this->output_handler = $func;
    }

    public function actUri($action) {
        $params = func_get_args();
        array_unshift($params, $this->getName());
        return call_user_func_array(
                array($this->uri(), "set"), $params
        );
    }

    public function uri() {
        return new UriConstructor();
    }

    public function getLegoParams($lego_name = false) {
        if (!$lego_name)
            $lego_name = $this->getName();
        return $this->_get($lego_name, array());
    }

    public function getLegoParam($param_name, $lego_name = false) {
        $lego_params = $this->getLegoParams($lego_name);
        if (!is_array($lego_params))
            return false;
        if (isset($lego_params[$param_name]))
            return $lego_params[$param_name];
        return false;
    }

    public function getParam($action_name, $index, $default_value = false) {
        $lego_params = $this->getLegoParams();
        if (!isset($lego_params[$action_name]))
            return $default_value;
        if (!isset($lego_params[$action_name][$index]))
            return $default_value;
        return $lego_params[$action_name][$index];
    }

    public function getActionParams($action = false) {
        if (!$action)
            $action = $this->current_action;
        $lego_params = $this->getLegoParams();
        if (!isset($lego_params[$action]))
            return array();
        if (!is_array($lego_params[$action]))
            return array();
        return $lego_params[$action];
    }

    public function setActionParams($value, $action = false) {
        if (!$action)
            $action = $this->current_action;
        $this->_set($action,$value);
    }

    public function setParam($value, $index=0, $action=false) {
        if (!$action)
            $action = $this->current_action;
        $value = array( $index => $value);
        $this->setActionParams($value, $action);

    }


    public function getActionParam($index, $default_value = false) {
        return $this->getParam($this->current_action, $index, $default_value);
    }

    public function runner() {
        return $this->lego_runner;
    }

    public function root() {
        return reset(self::$runned_legos);
    }

    public function getViewDir() {
        return static::getDir() . "/view";
    }

    static public function current() {
        $arr = self::$runned_legos;
        return array_pop($arr);
    }

    static public function _is_post() {
        return !empty($_POST);
    }

    static public function _set($key_name, $value = false) {
        return $_GET[$key_name] = $value;
    }
    static public function _get($key_name, $default_value = false) {
        return self::__get_from_array($_GET, $key_name, $default_value);
    }

    static public function _post($key_name, $default_value = false) {
        return self::__get_from_array($_POST, $key_name, $default_value);
    }

    static public function _sess($key_name, $default_value = false) {
        return self::__get_from_array($_SESSION, $key_name, $default_value);
    }

    static public function _sessSet($key_name, $value) {
        $_SESSION[$key_name] = $value;
    }

    static public function _cookie($key_name, $default_value = false) {
        return self::__get_from_array($_COOKIE, $key_name, $default_value);
    }

    static public function _cookieSet($key_name, $value, $expire = false) {
        setcookie($key_name, $value, $expire);
    }

    static private function __get_from_array($array, $key_name, $default_value = false) {
        if (!isset($array[$key_name]))
            return $default_value;
        return $array[$key_name];
    }

    public function install() {
        if (file_exists($this->dir . "/install.sql"))
            sql::query(file_get_contents($this->dir . "/install.sql"));
        echo "install complete";
    }

    public function fetch($template) {
        profiler::add("Выполнение", $this->name . ": начало отрисовки");
        $templatedir = Output::getTemplateCompiler()->getTemplateDir();
        Output::getTemplateCompiler()->setTemplateDir($this->getViewDir());
        $content = Output::fetch($template);
        Output::getTemplateCompiler()->setTemplateDir($templatedir);
        profiler::add("Выполнение", $this->name . ": конец отрисовки");
        return $content;
    }

    public function getJavascripts() {
        $js[] = $this->getWebDir(__DIR__) . '/js/jquery.lego.js';
        $js[] = $this->getWebDir(__DIR__) . '/js/hotkeys.js';
        $js = array_merge(parent::getJavascripts(),$js);
        return $js;

    }

}

lego_abstract::staticConsturct();

?>