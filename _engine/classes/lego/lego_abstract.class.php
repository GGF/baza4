<?php

abstract class lego_abstract extends JsCSS {

    /**
     * Свойство класса
     *
     * @var array список запускаемых лего
     * Соддержит необходимые для запуска лего
     * После запуска каждый себя выталкивает
     */
    static protected $runned_legos = array();
    /**
     * Свойство класса
     *
     * @var array Спиисок всех лего
     */
    static protected $all_legos = array();
    /**
     * Свойство класса
     * @var string Имяя лего, используется в частности в имени модуля куда его выводить
     */
    private $name;
    /**
     *
     * @var string Дествие по умолчанию, можно задать и другое при инициализации
     * получать только через геттер
     */
    private $default_action = "index";
    /**
     *
     * @var object кто запускает, сслка на лего
     */
    private $lego_runner;
    /**
     *
     * @var object xtv выводить  собственно, объект должен иметь интерфейс IOutput
     */
    private $output_handler;
    /**
     *
     * @var string Ntreitt испполняемое дейтвие
     */
    private $current_action;
    /**
     *
     * @var string то  что будет выдано в вывод
     */
    protected $output = "";
    /**
     *
     * @var string катаалог
     */
    protected $dir;
    /**
     *
     * @var object
     */
    static public $sitemap;
    /**
     *
     * @var object строка  переданная через GET
     */
    static public $get;

    /**
     * Конструктор статического класса лего
     *
     * вызывается в конце объявления, заполняет статические свойства класса
     * @return none
     */
    static public function staticConsturct() {
        if (class_exists("SiteMap",false))
            self::$sitemap = new SiteMap();
        self::$get = new QueryString($_GET);
    }
    /**
     * Коннструктор
     * @param string $name
     * Имя лего
     * @return none
     */
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
    /**
     * Инициализатор, обычно перезагружается потомками, вызывается из конструктора
     */
    public function init() {

    }
    /*
     * Возвращает версию текущего файла, теоретически помогает иметь несколько
     * версий и с каждой работать по воему. На деле не использовался
     * @return int собсственно вверсия
     */
    public function getVersion() {
        return 0;
    }

    /**
     * Возвращает карту сайта
     * @return object Собственно карта сайта
     */
    public function getSitemap() {
        return self::$sitemap;
    }

    /**
     * Установка дейтсвия по умолчанию
     * @param string $default_action
     */
    public function setDefaultAction($default_action) {
        $this->default_action = $default_action;
    }

    /**
     * Воззвращает текущее действие по умолчанию
     * @return string
     */
    public function getDefaultAction() {
        return $this->default_action;
    }

    /**
     * Воззвращает возможные действия
     * @return array of strings
     */
    function getActions() {
        $methods = get_class_methods(get_class($this));
        $ret = array();
        foreach ($methods as $one)
            if (strpos($one, "action_") === 0 && strpos($one, "action_submit") !== 0)
                $ret[] = substr($one, strlen("action_"));
        return $ret;
    }

    /**
     * Оснновной поток исполнения
     * @return this сам текущий лего, для сквозных вызовов
     */
    public function run() {
        $this->beforeRun();
        Output::assign("lego", $this);
        $act = $this->getAction();
        $method_name = "action_$act";
        try {
            $this->current_action = $act;
            $this->output = call_user_func_array(array($this, $method_name), $this->getActionParams());
        } catch (Exception $e) {
            $this->output = $this->_404("wrong action ($method_name) [class=" . get_class($this) . "]" . $e->getMessage());
        }
        Output::assign("lego", $this->runner());
        return $this->afterRun();
    }

    /**
     * Воззвращает текущее действие, берет из запроса или умолчальное
     * @return string
     */
    public function getAction() {
        $act = $this->_get($this->name, $this->default_action);
        if (!is_array($act))
            return $act;
        if (isset($act["act"]))
            return $act["act"];
        return $this->default_action;
    }

    /**
     * Выполняется до запуска
     */
    private function beforeRun() {
        //console::getInstance()->out($this->getName());
    }

    /**
     * Выполняется после отработки
     * @return this для сквозных  вызовов
     */
    private function afterRun() {
        array_pop(self::$runned_legos);
        if ($this->_get("ajax") == $this->getName()) {
            echo $this->output;
            //echo console::getInstance()->getScripts();
            //die;
        }
        return $this;
    }

    /**
     * Проверка запущен ли лего.
     * @param string $name
     * @return boolean
     */
    static public function isLegoRunned($name) {

        foreach (self::$runned_legos as $lego)
            if ($lego->getName() == $name)
                return true;
        return false;
    }

    /**
     * Прооверяет существует ли лего по имени
     * @param string $name
     * @return boolean
     */
    static public function isLegoExist($name) {
        foreach (self::$all_legos as $lego)
            if ($lego->getName() == $name)
                return true;
        return false;
    }

    /**
     * Поллучить имена всех лего
     * @return array
     */
    static public function getAllNames() {
        $ret = array();
        foreach (self::$all_legos as $lego) {
            $ret[] = $lego->getName();
        }
        return array_unique($ret);
    }

    /**
     * Сгененрить  404 страницу? проосто чтоб было ясно, что 404
     * @param string $text
     * @return string
     */
    public function _404($text) {
        return($text);
    }

    /**
     * Перейти. Заставить броузер перейти куда сказано.
     * @param string $url
     */
    public function _goto($url) {
        $ajax_element = $this->_get("ajax");
        if ($ajax_element) {
            $info = parse_url($url);
            $get = array();
            if (!empty($info['query']))
                parse_str($info['query'], $get);
            //$info['query'] = http_build_query(array_merge($get, array(Ajax::key() => $ajax_element)));
            $url = (isset($info['scheme']) ? $info['scheme'] . "://" : "http://")
                    . (isset($info['host']) ? $info['host'] : $_SERVER['HTTP_HOST'])
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

    /**
     * Заставить перейти к выполнению действия
     * @param string $action
     */
    public function _gotoAct($action) {
        $this->_goto($this->actUri($action)->url());
    }

    /**
     * Получить имя лего
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Поллучть вывод накопившийся
     * @return string
     */
    public function getOutput() {
        if (is_callable($this->output_handler))
            return call_user_func($this->output_handler, $this->output, $this);
        return $this->output;
    }

    /**
     * Установить обработчик вывода. Шаблонизатор
     * @param function_handler $func
     */
    public function setOutputHandler($func) {
        $this->output_handler = $func;
    }

    /**
     * Поллучить URL деййствия
     * @return uri_object
     */
    public function actUri() {
        $params = func_get_args();
        array_unshift($params, $this->getName());
        return call_user_func_array(
                array($this->uri(), "set"), $params
        );
    }

    /**
     * Получить пустой  uri
     * @return uri_object
     */
    public function uri() {
        return new UriConstructor();
    }

    /**
     * Поллучить параметры
     * @param string $lego_name
     * @return array
     */
    public function getLegoParams($lego_name = false) {
        if (!$lego_name)
            $lego_name = $this->getName();
        return $this->_get($lego_name, array());
    }

    /**
     * Получить коннкретный параметр
     * @param string $param_name Имя параметра
     * @param string $lego_name
     * @return boolean
     */
    public function getLegoParam($param_name, $lego_name = false) {
        $lego_params = $this->getLegoParams($lego_name);
        if (!is_array($lego_params))
            return false;
        if (isset($lego_params[$param_name]))
            return $lego_params[$param_name];
        return false;
    }

    /**
     * Поллучить параметр действия
     * @param string $action_name
     * @param int $index
     * @param boolean $default_value
     * @return var
     */
    public function getParam($action_name, $index, $default_value = false) {
        $lego_params = $this->getLegoParams();
        if (!isset($lego_params[$action_name]))
            return $default_value;
        if (!isset($lego_params[$action_name][$index]))
            return $default_value;
        return $lego_params[$action_name][$index];
    }

    /**
     * Поллучить все параметры действия
     * @param string $action
     * @return array
     */
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

    /**
     * Поллучить один параметр действия
     * @param type $index
     * @param type $default_value
     * @return type
     */
    public function getActionParam($index, $default_value = false) {
        return $this->getParam($this->current_action, $index, $default_value);
    }


    /**
     * Установить  параметры действия
     * @param mixed $value
     * @param string $action
     */
    public function setActionParams($value, $action = false) {
        if (!$action)
            $action = $this->current_action;
        $this->_set($action,$value);
    }

    /**
     * Установить  один параметр
     * @param mixed $value
     * @param int $index
     * @param string $action
     */
    public function setParam($value, $index=0, $action=false) {
        if (!$action)
            $action = $this->current_action;
        $value = array( $index => $value);
        $this->setActionParams($value, $action);

    }


    /**
     * Геттер для запускателя
     * @return object
     */
    public function runner() {
        return $this->lego_runner;
    }

    /**
     * Сбросить список запускаемых к началу
     * @return array
     */
    public function root() {
        return reset(self::$runned_legos);
    }

    /**
     * Получение каталога с представлениями
     * @return string
     */
    public function getViewDir() {
        return static::getDir() . "/view";
    }

    /**
     *
     * @return object
     */
    static public function current() {
        $arr = self::$runned_legos;
        return array_pop($arr);
    }

    /**
     * Передача данных по POST?
     * @return boolean
     */
    static public function _is_post() {
        return !empty($_POST);
    }

    /**
     * Установить  в GET парраметр
     * хакерская штучка
     * @param string $key_name
     * @param mixed $value
     * @return array
     */
    static public function _set($key_name, $value = false) {
        return $_GET[$key_name] = $value;
    }

    /**
     * Поллучить элемент GET
     * @param string $key_name
     * @param mixed $default_value
     * @return mixed
     */
    static public function _get($key_name, $default_value = false) {
        return self::__get_from_array($_GET, $key_name, $default_value);
    }

    /**
     * Поллучить элемент POST
     * @param string $key_name
     * @param mixed $default_value
     * @return mixed
     */
    static public function _post($key_name, $default_value = false) {
        return self::__get_from_array($_POST, $key_name, $default_value);
    }

    /**
     * Получение элемента _SESSION
     * @param string $key_name
     * @param mixed $default_value
     * @return mixed
     */
    static public function _sess($key_name, $default_value = false) {
        return self::__get_from_array($_SESSION, $key_name, $default_value);
    }

    /**
     * Установить  элемент SESSION
     * @param string $key_name
     * @param mixed $value
     */
    static public function _sessSet($key_name, $value) {
        $_SESSION[$key_name] = $value;
    }

    /**
     * Поллучить печеньку
     * @param string $key_name
     * @param mixed $default_value
     * @return mixed
     */
    static public function _cookie($key_name, $default_value = false) {
        return self::__get_from_array($_COOKIE, $key_name, $default_value);
    }

    /**
     * Дать  печеньку
     * @param string $key_name
     * @param mixed $value
     * @param string $expire
     */
    static public function _cookieSet($key_name, $value, $expire = false) {
        setcookie($key_name, $value, $expire);
    }

    /**
     * Получить элемент массива
     * @param array $array
     * @param string $key_name
     * @param mixed $default_value
     * @return mixed
     */
    static private function __get_from_array($array, $key_name, $default_value = false) {
        if (!isset($array[$key_name]))
            return $default_value;
        return $array[$key_name];
    }

    /**
     * Отрработать вывод по шаблону
     * @param string $template
     * @return string
     */
    public function fetch($template) {
        /* TODO: тут проблемы с отрисовкой, нужно наследовать шаблоны
        */
        if ($_SERVER['debug']['report'])
            profiler::add("Выполнение", "{$this->name}: {$template} начало отрисовки");
        // найдем шаблон
        $obj = $this;
        while($obj) {
            if(file_exists($obj->getViewDir().'/'.$template)) {
                $templatedir = Output::getTemplateCompiler()->getTemplateDir();
                Output::getTemplateCompiler()->setTemplateDir($obj->getViewDir());
                $content = Output::fetch($template);
                Output::getTemplateCompiler()->setTemplateDir($templatedir);
                break;
            }
            $parentclass = get_parent_class($obj);
            if ($parentclass) {
                $obj = new $parentclass();
            } else {
                $obj = false;
            }
        }
        if ($_SERVER['debug']['report'])
            profiler::add("Выполнение", "{$this->name}: {$template} конец отрисовки");
        return $content;
    }

    /**
     * Перрегрузка получения всех скриптов, для включения библиотек для работы
     * с лего
     * @return array
     */
    public function getJavascripts() {
        $js[] = $this->getWebDir(__DIR__) . '/js/jquery.lego.js';
        $js[] = $this->getWebDir(__DIR__) . '/js/hotkeys.js';
        $js = array_merge(parent::getJavascripts(),$js);
        return $js;

    }

}

/**
 * Выззов для инициализации статических переменных
 */
lego_abstract::staticConsturct();

?>