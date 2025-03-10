<?php

define("AJAXFORM_TYPE_CODE", "code");
define("AJAXFORM_TYPE_HIDDEN", "hidden");
define("AJAXFORM_TYPE_TEXT", "text");
define("AJAXFORM_TYPE_PASSWORD", "password");
define("AJAXFORM_TYPE_TEXTAREA", "textarea");
define("AJAXFORM_TYPE_BUTTON", "button");
define("AJAXFORM_TYPE_LINKBUTTON", "linkbutton");
define("AJAXFORM_TYPE_SUBMIT", "submit");
define("AJAXFORM_TYPE_RESET", "reset");
define("AJAXFORM_TYPE_IMAGE", "image");
define("AJAXFORM_TYPE_CHECKBOX", "checkbox");
define("AJAXFORM_TYPE_CHECKBOXES", "checkboxes");
define("AJAXFORM_TYPE_RADIO", "radio");
define("AJAXFORM_TYPE_SELECT", "select");
define("AJAXFORM_TYPE_FILE", "file");
define("AJAXFORM_TYPE_DATE", "date");

define("AJAXFORM_ERROR", "error"); // general error
define("AJAXFORM_ERROR_CHECK", "check");
define("AJAXFORM_ERROR_FORMAT", "format");
define("AJAXFORM_ERROR_OBLIGATORY", "obligatory");
define("AJAXFORM_ERROR_CONFIRM", "confirm");
define("AJAXFORM_ERROR_LENGTH", "length");
define("AJAXFORM_ERROR_CUSTOM", "custom"); // Специальный тип для отлова во фронтенде ошибок не формы, а дополнительных
define("AJAXFORM_ERROR_CRITICAL", "critical");

define("AJAXFORM_CHECK_CUSTOM", "check_custom");
define("AJAXFORM_CHECK_DEFAULT", "check_default");
define("AJAXFORM_CHECK_NUMERIC", "check_numeric");
define("AJAXFORM_CHECK_LOGIN", "check_login");
define("AJAXFORM_CHECK_CODE", "check_code");
define("AJAXFORM_CHECK_PHONE", "check_phone");
define("AJAXFORM_CHECK_MAIL", "check_mail");
define("AJAXFORM_CHECK_URL", "check_url");

define("AJAXFORM_FORMAT_CUSTOM", "format_custom");
define("AJAXFORM_FORMAT_PHONE", "format_phone");
define("AJAXFORM_FORMAT_MAIL", "format_mail");
define("AJAXFORM_FORMAT_URL", "format_url");
define("AJAXFORM_FORMAT_LOGIN", "format_login");

define("AJAXFORM_SESSION_PARTIAL", true);
define("AJAXFORM_TEMP", "temp");
define("AJAXFORM_CACHE", isset($_SERVER['modForm']['cachePath']) ? $_SERVER['modForm']['cachePath'] : $_SERVER['CACHE'] . "/form_ajax");
define("AJAXFORM_CACHE_LIFETIME", isset($_SERVER['modForm']['cacheLifetime']) ? $_SERVER['modForm']['cacheLifetime'] : 60 * 60 * 12); // half day

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'i18n.php';


class ajaxform extends JsCSS {

    public static $captcha = array("width" => 90, "height" => 19);
    public static $registered = array();
    public static $forms = array();
    public $name = "";   // название формы
    public $uid = null;  // уникальный ID формы
    public $action = "";
    public $class = "";
    public $options = array();
    public $_session = null; // это хеш формы для записи на диск
    public $_result = array();
    public $errors = array();
    public $fields = array();
    public $files = array();
    public $request = array();
    public $session = array(); // здесь лежит запрос юзера
    public $obligatory = array();
    public $checkers = array();
    public $formats = array();
    public $debug = false;
    // backend vars
    public $result = array(
        "alert" => array(),
        "html" => array(),
        "htmlReplace" => array(),
    );
    public $htmlReplace = "";
    public $html = "";
    public $alert = "";
    public $redirect = "";

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    //                                                                                                                                                                 //
    //   CONSTRUCTOR : CLASS INITIALIZATION                                                                                                                            //
    //                                                                                                                                                                 //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * Инициализует объект класса. Попутно проверяется, не была ли инициализирована форма с таким ID
     * 
     * @param		string	$id				ID формы
     * @param		string	$action		Адрес скрипта-обработчика
     * @param		string	$class		Класс тега формы
     * @param		array		$options	Массив с опциями формы (например — noAjax)
     */
    function __construct($name, $action = "", $class = "", $options = array()) {

        parent::__construct();

        if (empty($name))
            return;

        if (substr($name, 0, 5) == "form_")
            $name = substr($name, 5);

        if ($action) {

            if (ajaxform::$registered[$name])
                console::getInstance()->warning("FORM_REGISTERED: Форма «{$name}» уже зарегистрирована на этой странице. Скрипт продолжил работу, но работоспособность не гарантируется.");
            ajaxform::$registered['registered'][$name] = true;
        }

        $this->name = "form_" . $name; //$_SERVER['ajaxform'];
        $this->action = $action;
        $this->class = $class;
        $this->options = $options;
        $this->_result = &ajaxform_recieve::$result;
        ;

        $this->options['debug'] = isset($options['debug']);
        $this->options['ajax'] = isset($options['ajax']) ? $options['ajax'] : !isset($options['noAjax']);
        $this->options['raw'] = isset($options['raw']);
        $this->options['method'] = $options['method'] == "get" ? "get" : "post";

        if ($this->options['raw'])
            $this->options['ajax'] = false;

        if ($name != AJAXFORM_TEMP) {

            $session = $this->sessionRead();
            if (!$action && $session)
                $this->errorCritical("Не удалось прочитать файл с сессией ({$session}) — либо файл поврежден, либо в Backend указан неправильный ID формы, либо ID сессии поменялся в процессе вызова.");
        }
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    //                                                                                                                                                                 //
    //   M E T H O D S                                                                                                                                                 //
    //                                                                                                                                                                 //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * Инициализует форму в frontend. Главная задача — разбор сессии и мелкие приготовления
     */
    function init() {

        // Получаем только ошибки и сессию
        $this->sessionGet(AJAXFORM_SESSION_PARTIAL);
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * Инициализует форму в backend для генерации картинки.
     */
    function initConfirm() {

        if (!$this->sessionGet())
            $this->errorCritical("Не удалось найти сессию «{$this->name}».");
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * Инициализует форму в backend и разбирает входящие данные.
     */
    function initBackend() {

        $req = array();

        // Цепляем всю сессию из фронтенда, с описанием чекеров, обязательных полей и т.д.
        // Если такого нет — значит либо нас ломают, либо где-то ошибка.
        if (!$this->sessionGet())
            $this->errorCritical("Не удалось найти сессию «{$this->name}».");
        if (!$this->fields)
            $this->errorCritical("Пустой массив с определениями полей (\$form->fields).");

        // в массив пишем только известные значения, никакой левой фигни
        foreach ($this->fields as $field) {

            if (@isset($_REQUEST[$this->name][$field['name']]))
                $req[$field['name']] = $_REQUEST[$this->name][$field['name']];

            if ($field['type'] == AJAXFORM_TYPE_CHECKBOXES) {

                foreach ($field['values'] as $k => $v)
                    if (@isset($_REQUEST[$this->name][$field['name'] . "|" . $k]))
                        $req[$field['name'] . "|" . $k] = true;
            }
            if ($field['type'] == AJAXFORM_TYPE_DATE) {
                $req[$field['name']] = $this->datepicker2date($_REQUEST[$this->name][$field['name']]);
            }
        }
        //$req = $_REQUEST[$this->name];
        //$this->alert(print_r($req, true));

        if (!empty($_FILES[$this->name]['name'])) { //06-06-2022 был count

            foreach ($_FILES[$this->name]['name'] as $file => $name) {

                $this->files[$file] = array(
                    "name" => $_FILES[$this->name]['name'][$file],
                    "error" => $_FILES[$this->name]['errors'][$file],
                    "errors" => $_FILES[$this->name]['errors'][$file], // compatibility
                    "type" => $_FILES[$this->name]['type'][$file],
                    "size" => $_FILES[$this->name]['size'][$file],
                    "tmp" => $_FILES[$this->name]['tmp_name'][$file],
                    "tmp_name" => $_FILES[$this->name]['tmp_name'][$file], // compatibility
                    "uploaded" => strlen($_FILES[$this->name]['tmp_name'][$file]) > 0 ? true : false,
                );
            }
        }

        $this->uid = $this->checkString($req['uid'], "id");

        // Ошибки обнуляем, чтобы они не пришли из сессии, так, на всякий случай.
        // А также кладем все новое г в сессию. Если что-то провалится —
        // именно это г из сессии высплывет в случае не-аяксового интерфейса
        $this->session = $req;
        $this->errors = array();

        // Проверяем длину
        foreach ($this->fields as $field) {

            if ($field['options']['length'] && mb_strlen($req[$field['name']]) > $field['options']['length']) {

                $this->error(AJAXFORM_ERROR_FORMAT, $field['name'], Lang::getString("Form.error.length") . ": " . $field['options']['length']);
                $req[$field['name']] = mb_substr($req[$field['name']], 0, $field['options']['length']);
            }
        }

        // Проверяем соответствие формату
        if (count($this->formats))
            foreach ($this->formats as $format) {

                $res = $this->checkFormat($req[$format['name']], $format['type'], $format['pregPattern']);

                //$this->alert("FORMAT: " . var_export($format, true) . "\nREQ: " . $req[$format['name']] . "\nSTATUS: " . var_export($res, true));

                if ($res === false && $req[$format['name']]) {

                    $html = ($format['type'] == AJAXFORM_FORMAT_CUSTOM) ? $format['html'] : Lang::getString("Form.error.format.{$format['type']}");
                    $this->error(AJAXFORM_ERROR_FORMAT, $format['name'], $html);
                } elseif ($res === null) {

                    $this->errorCritical("Для поля «{$format['name']}» не найден формат «{$format['type']}».");
                }
            }

        // Проверяем весь $_REQUEST проверкой по-умолчанию
        $req = $this->checkField($req);

        // Проходим по массиву проверок и каждый элемент $_REQUEST проверяем
        // Нужно затем, чтобы потом безопасно использовать данные в сторонних проверках, выполняемых в бакенде
        if (count($this->checkers)) {

            // Проверяем должным образом
            foreach ($this->checkers as $checker) {

                $req[$checker['name']] = $this->checkField($req[$checker['name']], $checker['name'], $checker['type'], $checker['pregPattern'], $checker['pregReplace']);
            }

            $errors = array();

            // Второй проход — смотрим что стало отличаться
            foreach ($this->checkers as $checker) {

                $name = $checker['name'];
                $field = $this->fields[$name];

                //$this->alert("Проверка значения: «mb_strtolower($req[$name]) . "» —> «" . mb_strtolower($this->session[$name]) . "»");
                // после обработки чекером значение стало пустым, или в массиве элемента не нашлось сопоставления значению
                if (mb_strtolower($req[$name]) != mb_strtolower($this->session[$name])) { // || (($field['type'] == AJAXFORM_TYPE_SELECT || $field['type'] == AJAXFORM_TYPE_RADIO) && $this->value($name, $req[$name])) === null
                    // НЕ ЗАПОЛНЕННОЕ ПОЛЕ ДЛЯ ЧЕКЕРА НЕ ВАЖНО — ЗДЕСЬ ГЛАВНОЕ, ЧТОБЫ НЕ ЧЕКЕР ЕГО ТАКИМ СДЕЛАЛ
                    // если стало пустым после чекеров или не нашлось
                    if ($checker['type'] != AJAXFORM_CHECK_DEFAULT)
                        $this->error(AJAXFORM_ERROR_CHECK, $name, ($checker['type'] == AJAXFORM_CHECK_CUSTOM) ? $checker['html'] : Lang::getString ("Form.error.checker." . $checker['type']));
                    $errors[$name] = true;
                }
            }
        }

        //$this->alert(print_r($this->obligatory, 1));
        //$this->alert(print_r($this->files, 1));
        // Проверяем заполненность обязательных полей
        if (count($this->obligatory))
            foreach ($this->obligatory as $name => $tmp) {

                $field = $this->fields[$name];

                //$this->alert("Obligatory check: {$field['name']}, " . var_export($this->value($name, $req[$name]), 1));
                // после обработки чекером значение стало пустым, или в массиве элемента не нашлось сопоставления значению
                if ($this->value($name, $req[$name]) === null) {

                    $html = ($field['type'] == AJAXFORM_TYPE_CHECKBOX 
                            || $field['type'] == AJAXFORM_TYPE_RADIO 
                            || $field['type'] == AJAXFORM_TYPE_SELECT 
                            || $field['type'] == AJAXFORM_TYPE_FILE) ?
                                Lang::getString("Form.error.obligatory." . $field['type']) :
                                Lang::getString("Form.error.obligatory.empty");
//                endif;
//                endif;

                    $this->error(AJAXFORM_ERROR_OBLIGATORY, $name, $html);
                }

                // проверим массив, есть ли там вообще такое значение (достаточно проверить тип —
                // если СЕЛЕКТ или РАДИО, то null в условии только поэтому может появиться
                if (($field['type'] == AJAXFORM_TYPE_SELECT 
                        || $field['type'] == AJAXFORM_TYPE_RADIO) 
                     && $this->value($name, $req[$name]) === null 
                     && !empty($req[$name])) { // было выше в чекерах
                        $this->error(AJAXFORM_ERROR_OBLIGATORY, $name, 
                                Lang::getString("Form.error.obligatory.not-found"));
                    continue; // был break… само собой не работало
                }
            }

        // проверяем код, если таковой есть в массиве
        if ($this->fields['confirm']) {

            $req['confirm'] = $this->confirmReplace($req['confirm']);

            if ($req['confirm'] != $this->fields['confirm']['value']) {

                $this->error(AJAXFORM_ERROR_CONFIRM, "confirm", Lang::getString("Form.error.code"));
                //$this->alert($req['confirm'] . "|" . $this->fields['confirm']['value']);
            }
        }

        //$this->alert($res, true);

        foreach ($this->fields as $field) {


            if (($field['type'] == AJAXFORM_TYPE_TEXT || $field['type'] == AJAXFORM_TYPE_TEXTAREA || $field['type'] == AJAXFORM_TYPE_HIDDEN || $field['type'] == AJAXFORM_TYPE_PASSWORD) && !$field['options']['allowHTML']) {

                if (!$field['options']['raw'])
                    $req[$field['name']] = htmlSpecialChars($req[$field['name']]);
                //$this->alert($field['type'] . " — " . $field['name'] . " — " . $req[$field['name']]);
            }
        }

        //$this->alert(print_r($this->errors, 1));

        $this->request = $req;
        $this->parseRequest();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * Заканчивает обработку формы
     */
    function destroy() {

        $json = array();

        foreach ($this->fields as $name => $array) {

            if ($array['type'] != AJAXFORM_TYPE_BUTTON && $array['type'] != AJAXFORM_TYPE_RESET) {

                $json[$array['name']] = array(
                    "name" => $array['name'],
                    "type" => $array['type'],
                    "values" => is_array($array['values'])?array_keys($array['values']):'',
                );
            }
        }

        $out = "<script> ajaxform.fields[\"{$this->name}\"] = " . multibyte::JSON_encode($json) . ";</script>\n";

        if (!$this->alert) {

            // Обновляем сессию, теперь в сессии должны быть только ошибки и сессия от backend при отключенном JS.
            // При включенном JS это все будет грохнуто в конце работы backend перед отдачей обратно.
            // Логика такая — если у юзера включен JS, то можно работать с формой не покидая страницы, и сессия не нужна.
            // Если JS выключен — то происходят постоянные релоады туда-сюда между frontend и backend, поэтому данные надо сохранять.
            $this->sessionSet();

            // Убиваем в сессии ошибки и сессию, т.о. если юзер нажмет F5 или уйдет со страницы — при следующем показе будет отображена реальная инфа,
            // а не та, которую он ввел и не смог сохранить (допустим)
            $this->sessionEnd(AJAXFORM_SESSION_PARTIAL);
        } else {

            // Если в сессии есть алерт — это значит, что мы находимся в режиме без скриптов
            $text = $this->alert;
            $text = addslashes($text);
            $text = str_replace("\n", '\n', $text);
            $text = str_replace("\r", "", $text);
            $out .= "\n<script> alert(\"{$text}\"); </script>";


            // После показа алерта надо сам алерт из сессии убить, иначе будет показываться постоянно
            // Т.к. остальная сессия без изменений, то после релоада, связанного с кликом по ОК,
            // мы увидим все то же самое, но без алерта
            $this->alert = array();

            $this->sessionSet();
        }
        return $out;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    // -- SESSION FUNCTIONS                                                                                                                                         -- //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * Забирает сессию. При успехе возвращает true
     *
     * @param	$partial    boolean	Забирать ли сессию частично
     * @return	bool
     */
    function sessionGet($partial = false) {

        //TODO: why there is count()?
        if (is_array($this->_session)) {

            $this->errors = $this->_session['errors'];
            $this->session = $this->_session['session'];
            $this->html = $this->_session['html'];
            $this->alert = $this->_session['alert'];

            if (!$partial) {

                $this->fields = $this->_session['fields'];
                $this->obligatory = $this->_session['obligatory'];
                $this->checkers = $this->_session['checkers'];
                $this->formats = $this->_session['formats'];
            }

            return true;
        } else
            return false;
    }

    /**
     * Сохраняет сессию.
     *
     * @return	bool
     */
    function sessionSet() {

        $this->_session = array(
            "uid" => $this->uid,
            "fields" => $this->fields,
            "session" => $this->session,
            "errors" => $this->errors,
            "obligatory" => $this->obligatory,
            "checkers" => $this->checkers,
            "formats" => $this->formats,
            "html" => $this->html,
            "alert" => $this->alert,
        );

        $this->sessionWrite();

        return true;
    }

    /**
     * Удаляет сессию.
     *
     * @param		bool		$partial	Удалять ли сессию частично
     * @return	bool
     */
    function sessionEnd($partial = false) {

        if ($partial) {

            $this->_session['session'] = null;
            $this->_session['errors'] = null;
            $this->_session['html'] = "";
            $this->_session['alert'] = "";
            //$this->_session['uid']					= "";
        } else
            $this->_session = array();

        $this->sessionWrite();
    }

    /**
     * Записывает сессию в файл.
     */
    function sessionWrite() {

        @mkdir(AJAXFORM_CACHE);
        @chmod(AJAXFORM_CACHE, 0777);
        @mkdir(AJAXFORM_CACHE . "/" . session_id());
        @chmod(AJAXFORM_CACHE . "/" . session_id(), 0777);
        file_put_contents(AJAXFORM_CACHE . "/" . session_id() . "/{$this->name}.php", "<?php\n\nreturn " . var_export($this->_session, true) . ";\n\n?>");
        // fuck!!! short tag!!!
    }

    /**
     * Читает сессию из файла и возвращает результат.
     * 
     * @return	bool
     */
    function sessionRead() {

        $file = AJAXFORM_CACHE . "/" . session_id() . "/{$this->name}.php";

        if (@is_file($file)) {

            $this->_session = include($file);
        } else
            return $file;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    // -- BACKEND FUNCTIONS                                                                                                                                         -- //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * Финальная обработка в backend — формируте массив для AJAX и обрабатывает сессию
     */
    function processed($data = array()) {

        $this->alert = implode("\n\n", $this->result['alert']);
        $this->html = implode($this->result['html']);
        $this->htmlReplace = implode($this->result['htmlReplace']);

        if (ajaxform_recieve::$ajaxform_recieve) {

            $this->_result['alert'] = $this->alert;
            $this->_result['html'] = $this->html;
            $this->_result['htmlReplace'] = $this->htmlReplace;
            $this->_result['errors'] = $this->errors;
            $this->_result['redirect'] = $this->redirect;
            $this->_result['data'] = $data; // Кастомная переменная, можно и без нее, но так веселее :)
            // В AJAX backend сессия как таковая не нужна, но нужно хранить ID и CODE, поэтому убиваем не целиком
            $this->sessionEnd(AJAXFORM_SESSION_PARTIAL);
        } else { // if JS disabled, for example
            $this->sessionSet();
       }
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    // -- FORM FUNCTIONS                                                                                                                                            -- //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function form($uid = false) {

        ajaxform::$forms[$this->name]++;
        $this->uid = $uid ? "{$this->name}_{$uid}" : "{$this->name}_" . ajaxform::$forms[$this->name];

        list($optionsHTML, $options) = $this->parseOptions($this->options);

        return "<form class='{$this->class}' name='{$this->name}' id='{$this->uid}' action='{$this->action}' method='{$this->options['method']}' enctype='multipart/form-data'{$optionsHTML}>";
    }

    function end() {

        $out = '';
        $this->field(array(
            "type" => AJAXFORM_TYPE_HIDDEN,
            "name" => "uid",
            "value" => 0,
        ));
        $out .= $this->add("uid", $this->uid);

        $out .= "</form>\n";

        // Для борьбы с SimpleModal и двойной отправкой — сначала unbind(), потом заново вешаем событие.
        // Предполагается, что с событиями формы идет работа через коллбэки.
        //if ($this->options['ajax'])	echo "<script type='text/javascript'> $(\"#{$this->uid}\").unbind('submit').submit(function() {ajaxform.submit(this); return false; }); </script>";
        // заблокируем предыдущий для того чтобы использовать jquery.iframe-post-form и вставим
        $out .= "<script>$(function ()
								{
								    $('form').iframePostForm
								    ({
								         post : function ()
								        {
								         	ajaxform.beforeSend(this);
								        },
								        complete : function (response)
								        {
								            ajaxform.afterSend($('#{$this->uid}'),response);
								        }
								    });
								});</script>";
        //$this->uid = null;
        return $out;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    // -- CONFIRM FUNCTIONS                                                                                                                                         -- //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * 	Функция генерирует, сохраняет массиве полей и возвращает код подтверждения
     * 	@return	string
     */
    function confirmGenerate() {

        $s = "";

        mt_srand(microtime(true));

        for ($i = 0; $i < 6; $i++)
            $s .= mt_rand(0, 9);

        $this->fields['confirm']['value'] = $this->confirmReplace(mb_strtoupper($s));

        return $this->fields['confirm']['value'];
    }

    /**
     * 	Функция производит замену похожих символов в коде подтверждения
     * 	@param	string	$var		Строка для замены
     * 	@return	string
     */
    function confirmReplace($var) {

        $replaces = array(
            // en
            "I" => "1",
            "O" => "0",
            "D" => "O",
            "S" => "5",
            "Z" => "2",
            "B" => "8",
            "G" => "6",
            // ru
            "О" => "0",
            "В" => "8",
        );

        return mb_strToUpper($this->Replace($replaces, $var, true));
    }

    /*
     * 
     */

    function Replace($array, $text, $str = false) {

        $patterns = array();
        $replaces = array();

        foreach ($array as $k => $v) {

            $patterns [''] = $k;
            $replaces [''] = $v;
        }

        if ($str)
            return str_replace($patterns, $replaces, $text);
        else
            return preg_replace($patterns, $replaces, $text);
    }

    /**
     * 	Функция генерирует и выводит хидер PNG и изображение с кодом подтверждения (код берется из массива полей)
     * 	Возвращает результат выполнения imagePNG
     * 	@return	bool
     */
    function confirmImage() {

        $width = ajaxform::$captcha['width'];
        $height = ajaxform::$captcha['height'];

        $multi = 4;
        $xwidth = $width * $multi;
        $xheight = $height * $multi;

        $code = $this->fields['confirm']['value'];

        $im = imageCreateTrueColor($xwidth, $xheight);
        $w = imageColorAllocate($im, 255, 255, 255);
        $b = imageColorAllocate($im, 000, 000, 000);
        $g = imageColorAllocate($im, 100, 100, 100);
        imageFill($im, 1, 1, $w);
        $w = imageColorTransparent($im, $w);

        $r = mt_rand(0, $xheight);
        for ($x = 0; $x < $xwidth + $xheight; $x += 4 * $multi) {

            for ($i = 0; $i < $multi; $i++)
                imageLine($im, $x + $i, 0, $x + $i - $xheight, $xheight + $r, $g);
        }

        $arr = preg_split('//', $code, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < count($arr); $i++) {

            $x = ($xwidth * 0.04) + (floor(($xwidth * 0.97) * 0.167)) * $i; // разрядка
            $y = ($xheight * 0.8);
            $s = ($xheight * 0.5) * (96 / 72); // 96 — res
            $a = mt_rand(-20, 20);

            imageTTFText($im, $s, $a, $x, $y, $b, $_SERVER['DOCUMENT_ROOT'] . "/lib/core/classes/form_ajax/consolas.ttf", $arr[$i]); //
        }

        $temp = imageCreateTrueColor($xwidth, $xheight);
        $w = imageColorAllocate($temp, 255, 255, 255);
        imageFill($temp, 1, 1, $w);
        $w = imageColorTransparent($temp, $w);

        $phase = rand(-M_PI, M_PI);
        $frq = 2 * M_PI / $xheight;
        $amp = $xwidth * 0.02;

        for ($y = 0; $y < $xheight; $y++) {

            $dstx = $amp + sin($y * $frq + $phase) * $amp;

            imagecopy($temp, $im, $dstx, $y, 0, $y, $xwidth, 1);
            //imagesetpixel($im, $dstx, $y, $b);
        }

        $res = imageCreateTrueColor($width, $height);
        $w = imageColorAllocate($res, 255, 255, 255);
        imageFill($res, 1, 1, $w);
        $w = imageColorTransparent($res, $w);

        imageCopyResampled($res, $temp, 0, 0, 0, 0, $width, $height, $xwidth, $xheight);

        imageTrueColorToPalette($res, true, 256);

        header("CONTENT-TYPE: IMAGE/PNG");
        return imagePNG($res);
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    // -- RETURN FUNCTIONS                                                                                                                                          -- //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * 	Функция добавляет в массив алертов новую запись
     * 	@param	string	$text		Текст алерта
     * 	@return	void
     */
    function alert($text) {

        $this->result['alert'][''] = html_entity_decode(strip_Tags($text));
    }

    /**
     * 	Функция добавляет в массив HTML вывода новую запись
     * 	@param	string	$text		HTML для вывода
     * 	@return	void
     */
    function html($html = null) {

        if ($html === null) {

            // В случае не-Ajax формы выводим html, если совпадает UID
            return ($this->uid == $this->_session['uid']) ? $this->html : "";
        } else
            $this->result['html'][''] = $html;
    }

    /**
     * 	Функция добавляет в массив HTML замены формы новую запись
     * 	@param	string	$text		HTML для замены формы
     * 	@return	void
     */
    function htmlReplace($html) {

        $this->result['htmlReplace'][''] = $html;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    // -- MISC HELPER FUNCTIONS                                                                                                                                     -- //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * 	Парсит массив запроса на предмет «|» и разделяет такие поля во вложенные массивы
     * 	@return	void
     */
    function parseRequest() {

        foreach ($this->request as $k => $v) {

            $arr = explode("|", $k);
            if (count($arr) > 1) {

                $tmp = & $this->request;

                foreach ($arr as $n => $var) {

                    if (!$tmp[$var])
                        $tmp[$var] = array();
                    $tmp = & $tmp[$var];

                    if ($n == count($arr) - 1)
                        $tmp = $v;
                }
            }
        }
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * 	Создает критическую ошибку и завершает обработку формы.
     * 	@param	string	$text				Текст ошибки
     * 	@param	string	$redirect		URL для принудительного редиректа
     * 	@return	void
     */
    function errorCritical($text = null, $redirect = false) {

        if ($text)
            $this->alert($text);
        if ($redirect)
            $this->redirect = $redirect;
        $this->error(AJAXFORM_ERROR_CRITICAL, AJAXFORM_ERROR_CRITICAL, $text ? $text : "Экстренное завершение скрипта без уведомления об ошибке");
        $this->processed();
        exit();
    }

    /**
     * 	Создает обычную ошибку
     * 	@param	string	$type		Тип ошибки (см константы класса)
     * 	@param	string	$name		Имя поля
     * 	@param	string	$html		Текст ошибки
     * 	@return	void
     */
    function error($type, $name, $html = "") {

        $options = $this->getOptions($name);
        $id = $this->getID($name);
        $html = print_r($html, true);

        $text = (!$html) ? "" : strip_tags($html); // для title
        $html = (!$html) ? "" : "<span class='block'>{$html}</span>"; // для span

        $this->errors[''] = array(
            "type" => $type,
            "id" => $id,
            "name" => $name,
            "html" => ($options['errors']['noSpan']) ? "" : $html,
            "text" => ($options['errors']['noTitle']) ? "" : $text,
        );
    }

    function getCustomErrors() {

        $return = array();

        if (count($this->errors))
            foreach ($this->errors as $error) {

                if ($error['type'] == AJAXFORM_ERROR_CUSTOM)
                    $return[$error['id']] = $error['html'];
            }

        return $return;
    }

    /**
     * 	Выводит поле с ошибкой, во фронтенде бывает полезно для вывода ошибки в произвольном месте
     * 	@param	string	$name		Имя поля
     * 	@param	string	$html		Текст ошибки
     * 	@param	bool		$full		Выводить ли подчеркивание
     * 	@return	string	$html		HTML код поля с ошибкой
     */
    function errorHTML($name, $html = "", $underline = false) {

        $id = $this->getID($name);

        $return = "";
        $return .= ( $underline) ? "<span class='Form_error' id='{$id}_error' style='display: block'></span>" : "";
        $return .= "<span class='Form_errorText' id='{$id}_errorText' style='display: block'>{$html}</span>"; // для span
        // для растяга…
        $return .= "<script> ajaxform.errorHTML('{$id}', '', ''); </script>"; // уже без " . addSlashes($html) . " — ибо и так выведено

        return $return;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function getName($name) {

        if ($this->options['raw']) {

            $a = explode("|", $name);
            $b = array_shift($a);
            if (count($a)) {

                return $b . "[" . implode("][", $a) . "]";
            } else
                return $b;
        } else
            return "{$this->name}[{$name}]";
    }

    function getID($name) {

        return "{$this->uid}_" . str_replace("|", "__", $name);
    }

    function getNameFromID($id) {
        return str_replace("__", "|", str_replace($this->uid . "_", "", $id));
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function getValue($name, $value) {

        // Если задано значение поля и при этом что-то есть в сессии — это значит, что данные были сохранены,
        // но backend'у что-то не понравилось и при выключенном JS произошел редирект обратно.
        // Естественно при такой схеме, показать надо значение из сессии, а не дефолтовое значение поля.
        // Попутно заменяем символ одинарной кавычки на html-entity
        return str_replace("'", "&#39;", ($this->session[$name] && $this->_session['uid'] == $this->uid) ? $this->session[$name] : $value);
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function getOptions($name) {

        return $this->fields[$name]['options'];
    }

    function parseOptions($options) {

        $html = array();

        // Опции, идущие в optionsHTML
        $html['autocomplete'] = (isset($options['autocomplete'])) ? ($options['autocomplete'] ? "autocomplete='on'" : "autocomplete='off'") : "";
        $html['rel'] = ($options['rel']) ? "rel='{$options['rel']}'" : "";
        $html['style'] = ($options['style']) ? "style='{$options['style']}'" : "";
        $html['disabled'] = ($options['disabled']) ? "disabled" : "";
        $html['readonly'] = ($options['readonly']) ? "readonly" : "";
        $html['length'] = ($options['length']) ? "maxlength='{$options['length']}'" : "";
        $html['html'] = ($options['html']) ? " " . $options['html'] : "";

        // Самостоятельные опции
        $options['nobr'] = ($options['nobr']) ? true : false;

        $html = implode(" ", array($html['rel'], $html['style'], $html['disabled'], $html['readonly'], $html['length'], $html['autocomplete'], $html['html']));
        $html = $html ? " " . $html : "";

        return array($html, $options);
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function getBlock($nobr, $id) {

        $defBegin = ""; // "<span class='group'>"; // чтобы не рвать тэг <P> тэгами <DIV>
        $defEnd = "<br />"; //"</span>";

        $block = array("begin" => "", "end" => " ");
        $line = array("begin" => "", "end" => " ");

        if ($nobr) {

            $block['begin'] = $defBegin;
            $block['end'] = $defEnd;
        } else {

            $line['begin'] = $defBegin;
            $line['end'] = $defEnd;
        }

        $block['begin'] = "<span id='{$id}' class='inlineBlock'>" . $block['begin']; // id=id + _block;
        $block['end'] = $block['end'] . "</span>";

        return array($block, $line);
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    // -- FIELDS                                                                                                                                                    -- //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function file($name, $options = array()) {

        ob_start();

        list($optionsHTML, $options) = $this->parseOptions($options);
        if ($this->options['ajax']) {
            // need
            echo "<input type='file' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "'{$optionsHTML}/>";
        } else {
            echo "<input type='file' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "'{$optionsHTML}>";
        }


        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //


    function hidden($name, $value, $options = array()) {

        ob_start();

        list($optionsHTML, $options) = $this->parseOptions($options);

        echo "<input type='hidden' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "' value='{$value}'{$optionsHTML}>";

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function date($name, $value = "", $options = array()) {

        ob_start();

        $value = $this->getValue($name, $value);
        list($optionsHTML, $options) = $this->parseOptions($options);

        if (!$value)
            $xvalue = ""; 
        else
            $xvalue = $this->value($name, $value);
        $options['html'] .= " datepicker=1 ";

        echo $this->text($name, $xvalue, $options);

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function text($name, $value = "", $options = array()) {

        ob_start();

        $value = $this->getValue($name, $value);
        list($optionsHTML, $options) = $this->parseOptions($options);

        $cls = ($name == "confirm") ? " confirm" : "";
        $value = ($name == "confirm") ? "" : $value; // Если поле для кода — его всегда сбрасываем
        $type = ($options['password']) ? AJAXFORM_TYPE_PASSWORD : AJAXFORM_TYPE_TEXT;
        $type = ($options['date']) ? AJAXFORM_TYPE_TEXT : $type;
        $cls = ($options['date']) ? " date" : $cls;
        $cls = ($options['readonly']) ? "{$cls} readonly" : $cls;

        echo "<input type='{$type}' class='text{$cls}' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "' value='{$value}' {$optionsHTML}>";

        return ob_get_clean();
    }

    function password($name, $value = "", $options = array()) {

        $options['password'] = true;
        return $this->text($name, $value, $options);
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function textarea($name, $value = "", $options = array()) {

        ob_start();

        $options['rows'] = ($options['rows']) ? $options['rows'] : 4;
        $options['style'] .= "; height: " . ($options['rows'] * 16 + 4) . "px";

        if ($options['wysiwyg']) {

            $options['rel'] = $this->getId($name) . "_parent";
            $options['raw'] = true;
        }

        $value = $this->getValue($name, $value);
        list($optionsHTML, $options) = $this->parseOptions($options);

        echo "<textarea name='" . $this->getName($name) . "' id='" . $this->getId($name) . "'{$optionsHTML}>{$value}</textarea>";

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function button($name, $value, $options = array()) {

        ob_start();

        list($optionsHTML, $options) = $this->parseOptions($options);

        $type = $options['submit'] ? AJAXFORM_TYPE_SUBMIT : AJAXFORM_TYPE_BUTTON;
        $type = $options['reset'] ? AJAXFORM_TYPE_RESET : $type;

        $html = $options['submit'] ? "name='" . $this->getName($name) . "'" : "";

        echo "<input type='" . $type . "' class='submit' {$html} id='" . $this->getId($name) . "' value='{$value}' {$optionsHTML}>";
        if ($options['submit'])
            echo "<script> $(\"#" . $this->getId($name) . "\").click(function() { ajaxform.clicked = \"{$name}\"; }); /* вручную ловим клик, чтобы передать value в запрос */ </script>";

        return ob_get_clean();
    }

    function submit($name, $value, $options = array()) {

        $options['submit'] = true;
        return $this->button($name, $value, $options);
    }

    function reset($name, $value, $options = array()) {

        $options['reset'] = true;
        return $this->button($name, $value, $options);
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function code($options = array()) {

        ob_start();


        echo $this->text("confirm", "", array("length" => 6, "errors" => array("noSpan" => true), "html" => "autocomplete='off'"));
        echo " ";
        echo "<img src='/?ajaxform['act']=captcha&ajax=ajaxform&formName={$this->name}&rnd=" . array_sum(explode(" ", microtime())) . "' id='" . $this->getID("captcha") . "' class='captcha Form_captcha' title='CAPTCHA' width='" . ajaxform::$captcha['width'] . "' height='" . ajaxform::$captcha['height'] . "'>";
        echo " ";
        echo "<label><a href='javascript: ajaxform.reloadCaptcha(\"{$this->name}\", \"{$this->uid}\")' class='dashed'>repload...</a></label>";
        echo "<script> $('#" . $this->getID("confirm") . "').keyup(function(e){ ajaxform.cleanCaptcha(e, this); }); </script>";

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function image($name, $src, $options = array()) {

        ob_start();

        // TODO: заменить на fileserver
        //$path = cmsFile_path($src); 
        $info = getImageSize($src); //getImageSize($path);

        $options['style'] .= "width: {$info['0']}px; height: {$info['1']}px; background: transparent url({$src}) no-repeat";
        list($optionsHTML, $options) = $this->parseOptions($options);

        echo "<input type='submit' class='image' id='" . $this->getId($name) . "' value='&nbsp'{$optionsHTML}>";

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * 	@param	bool	$multiple		Является ли этот чекбокс частью типа CHECKBOXES
     */
    function checkbox($name, $label, $value = 0, $options = array(), $multiple = false) {

        ob_start();

        $label = $options['label'] ? $options['label'] : $label;

        // позволяет задать нечто кастомное вместо обычной единички
        $options['value'] = $options['value'] ? $options['value'] : 1;

        $value = $this->getValue($name, $value);
        list($optionsHTML, $options) = $this->parseOptions($options);
        list($block, $line) = $this->getBlock($options['nobr'], $this->getId($name));

        $checked = ($value == $options['value']) ? " checked" : "";

        // Это нужно только затем, чтобы различать свой ID от ID блока
        $id = $this->getId($name) . ($multiple ? "" : "_input");

        if (!$multiple)
            echo $block['begin'] . $line['begin'];
        echo "<label class='checkboxLabel{$options['disabled']}' for='{$id}'>";
        echo "<input type='checkbox' class='checkbox' name='" . $this->getName($name) . "' id='{$id}' value='{$options['value']}'{$checked}{$optionsHTML}>";
        echo $label;
        echo "</label>";
        if (!$multiple)
            echo $line['end'] . $block['end'];

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function checkboxes($name, $values, $value, $options = array()) {

        ob_start();

        $value = $this->getValue($name, $value);
        $value = (@!is_array($value)) ? multibyte::Json_decode($value) : $value;

        list($optionsHTML, $options) = $this->parseOptions($options);
        list($block, $line) = $this->getBlock($options['nobr'], $this->getId($name));

        $options['nobr'] = true;

        echo $block['begin'];
        if (is_array($values))
            foreach ($values as $id => $label) {

                echo $line['begin'];
                // вот следующая строка непонятна совсем, если у меня не пустой, 
                // но нулевой, она всё равно ставит единицу
                // поменяю на своё видение сегодняшнего момента
                $val = !empty($value[$id]) ? $value[$id] : 0;
                echo $this->checkbox($name . "|" . $id, $label, $val, $options, true);
                echo $line['end'];
            }
        echo $block['end'];

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function radio($name, $values, $value = "", $options = array()) {

        ob_start();

        $value = $this->getValue($name, $value);
        list($optionsHTML, $options) = $this->parseOptions($options);
        list($block, $line) = $this->getBlock($options['nobr'], $this->getId($name));

        $i = 0;

        echo $block['begin'];
        if (is_array($values))
            foreach ($values as $id => $label) {

                $i++;
                $checked = ($value == $id) ? " checked" : "        ";

                echo $line['begin'];
                echo "<label class='label{$options['disabled']}' for='" . $this->getId($name) . "_{$id}'>";
                echo "<input type='radio' class='radio' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "_{$id}' value='{$id}'{$checked}{$optionsHTML}>";
                echo $label;
                echo "</label>";
                echo $line['end'];
            }
        echo $block['end'];

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function select($name, $values, $value = "", $options = array()) {

        ob_start();

        $value = $this->getValue($name, $value);
        list($optionsHTML, $options) = $this->parseOptions($options);

        echo "<select class='select' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "'{$optionsHTML}>";

        if (is_array($values))
            foreach ($values as $id => $label) {

                if (is_array($label)) {

                    echo "<optgroup label='{$id}'>";
                    foreach ($label as $k => $v) {

                        $selected = ($value == $k) ? " selected" : "";
                        echo "<option value='{$k}'{$selected}>{$v}</option>";
                    }
                    echo "</optgroup>";
                } else {

                    $selected = ($value == $id) ? " selected" : "";
                    echo "<option value='{$id}'{$selected}>{$label}</option>";
                }
            }

        echo "</select>";

        return ob_get_clean();
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    // -- ARRAY FUNCTIONS                                                                                                                                           -- //
    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function field($array) {

        switch ($array['type']) {

            case AJAXFORM_TYPE_CODE: break;
            case AJAXFORM_TYPE_HIDDEN: break;
            case AJAXFORM_TYPE_TEXT: break;
            case AJAXFORM_TYPE_PASSWORD: break;
            case AJAXFORM_TYPE_TEXTAREA: break;
            case AJAXFORM_TYPE_BUTTON: break;
            case AJAXFORM_TYPE_SUBMIT: break;
            case AJAXFORM_TYPE_RESET: break;
            case AJAXFORM_TYPE_IMAGE: break;
            case AJAXFORM_TYPE_CHECKBOX: break;
            case AJAXFORM_TYPE_CHECKBOXES: break;
            case AJAXFORM_TYPE_RADIO: break;
            case AJAXFORM_TYPE_SELECT: break;
            case AJAXFORM_TYPE_FILE: break;
            case AJAXFORM_TYPE_DATE: break;
            default: trigger_error("Тип «{$array['type']}» для поля {$array['name']} " . print_r($array, 1) . " не зарегистрирован.");
        }

        if ($array['type'] == AJAXFORM_TYPE_CODE) {

            // регистрируем его в проверках
            $this->addObligatory("confirm");
            $this->addChecker("confirm", AJAXFORM_CHECK_CODE);

            // влоб задаем имя тому, что сейчас создаем
            $array['name'] = "confirm";
            $array['value'] = $this->confirmGenerate();
            $array['options'] = array("errors" => array("noSpan" => true));
        }

        // создаем поле
        $this->fields[$array['name']] = $array;
    }

    function addFields($fields) {

        if (is_array($fields) && count($fields) > 0)
            foreach ($fields as $array) {

                $this->field($array);
            }
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function delete($name) {

        unset($this->fields[$name]);
        unset($this->session[$name]);
        unset($this->request[$name]);
        unset($this->obligatory[$name]);

        foreach ($this->checkers as $i => $checker)
            if ($checker['name'] == $name)
                unset($this->checkers[$i]);
        foreach ($this->formats as $i => $format)
            if ($format['name'] == $name)
                unset($this->formats[$i]);

        unset($this->_session['fields'][$name]);
        unset($this->_session['request'][$name]);
        unset($this->_session['obligatory'][$name]);

        foreach ($this->_session['checkers'] as $i => $checker)
            if ($checker['name'] == $name)
                unset($this->_session['checkers'][$i]);
        foreach ($this->_session['formats'] as $i => $format)
            if ($format['name'] == $name)
                unset($this->_session['formats'][$i]);

        $this->_result['fieldsDeleted'][''] = $name;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function add($name, $value = false, $options = array()) {

        $array = $this->fields[$name];
        $errorsHtml = array();
        $errorsText = array();
        $errors = false;

        // возможно здесь следует провести проверку, и если значение установлено в реквесте — ставить всеже его
        if ($value)
            $array['value'] = $value;
        if ($value)
            $array['src'] = $value;

        if (!empty($this->errors)) { //03-06-2022 было count, но на пустой ругалось

            foreach ($this->errors as $error) {

                if ($this->getID($name) == $error['id']) {

                    $errors = true;

                    // если ID совпадают, то враппим в блок текст ошибки
                    $errorsHtml[''] = $error['html']; // для span
                    $errorsText[''] = $error['text']; // для title
                }
            }
        }


        $return = null;
        if (!is_array($array['options']))
            $array['options'] = array();
        $array['options'] = array_merge($array['options'], $options);

        if ($errors) {
            $array['options']['html'] .= " title='" . implode(", ", $errorsText) . "'";
        } // для title

        switch ($array['type']) {

            case AJAXFORM_TYPE_CODE: $return = $this->code($array['options']);
                break;
            case AJAXFORM_TYPE_HIDDEN: $return = $this->hidden($array['name'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_TEXT: $return = $this->text($array['name'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_PASSWORD: $return = $this->password($array['name'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_TEXTAREA: $return = $this->textarea($array['name'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_BUTTON: $return = $this->button($array['name'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_SUBMIT: $return = $this->submit($array['name'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_RESET: $return = $this->reset($array['name'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_IMAGE: $return = $this->image($array['name'], $array['src'], $array['options']);
                break;
            case AJAXFORM_TYPE_CHECKBOX: $return = $this->checkbox($array['name'], $array['label'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_CHECKBOXES: $return = $this->checkboxes($array['name'], $array['values'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_RADIO: $return = $this->radio($array['name'], $array['values'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_SELECT: $return = $this->select($array['name'], $array['values'], $array['value'], $array['options']);
                break;
            case AJAXFORM_TYPE_FILE: $return = $this->file($array['name'], $array['options']);
                break;
            case AJAXFORM_TYPE_DATE: $return = $this->date($array['name'], $array['value'], $array['options']);
                break;
            default:
                console::getInstance()->error("Поле «{$name}» не найдено в массиве или неверно задан тип ({$array['type']}).");
        }

        // если есть ошибки — выводим их под поле
        if ($errors)
            $return .= $this->errorHTML($name, implode($errorsHtml), true);

        return $return;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    /**
     * 	Функиця возвращает «красивое» строковое значение поля — то, что видел юзер при выборе. Если не удалось распарсить значение — будет возвращено NULL.
     * 	@param	string	$name				Название поля
     * 	@param	mixed		$override		Оверрайд значения — если не присвоен, то значение берется из массива ввода, если там не найдено, то из значения по-умолчанию самого поля
     * 	@return	string|null
     */
    function value($name, $override = null) {

        if ($this->fields[$name]) {

            $field = $this->fields[$name];
            $value = $override !== null ? $override : (isset($this->request[$name]) ? $this->request[$name] : $field['value']);

            // Радиокнопки
            if ($field['type'] == AJAXFORM_TYPE_RADIO) {

                if (empty($value))
                    $value = 0;
                return isset($field['values'][$value]) ? $field['values'][$value] : null;

                // Селект
            } elseif ($field['type'] == AJAXFORM_TYPE_SELECT) {

                if (empty($value))
                    $value = 0;
                if (isset($field['values'][$value])) {

                    return $field['values'][$value];
                } else { // у селекта могут быть OPTGROUP — вложенные массивы…
                    foreach ($field['values'] as $val => $label) {
                        if (is_array($label))
                            foreach ($label as $k => $v)
                                if ($k == $value)
                                    return $v;
                    }

                    return null;
                }

                // Чекбокс (один)
            } elseif ($field['type'] == AJAXFORM_TYPE_CHECKBOX) {

                if (empty($value))
                    $value = 0;

                if ($field['options']['value']) {

                    return ($field['options']['value'] == $value) ? $field['label'] : null;
                } else {

                    return (!empty($value)) ? $field['label'] : null; // $value == $field['value'] — неверно, т.к. $field['value'] — это текущее значение, а не возвращаемое
                }

                // Чекбоксы (группа)
            } elseif ($field['type'] == AJAXFORM_TYPE_CHECKBOXES) {

                $r = array();

                if (is_array($value) && count($value))
                    foreach ($value as $k => $v) {

                        if (!empty($v))
                            $r[''] = $field['values'][$k];
                    }

                return implode("; ", $r);

                // Файл
            } elseif ($field['type'] == AJAXFORM_TYPE_FILE) {

                return (isset($this->files[$name]) && $this->files[$name]['uploaded']) ? $this->files[$name]['name'] : null;

                // Дата
            } elseif ($field['type'] == AJAXFORM_TYPE_DATE) {

                return $this->date2datepicker($value);

                // Обычный текст
            } else {

                return (!empty($value)) ? nl2br($value) : null;
            }
        } else {

            $path = array_reverse(explode("|", $name));
            $last = $path['0'];
            unset($path['0']);
            $path = implode("|", array_reverse($path));

            if ($this->fields[$path] && $this->fields[$path]['type'] == AJAXFORM_TYPE_CHECKBOXES) {

                return ($this->fields[$path]['values'][$last]) ? $this->fields[$path]['values'][$last] : null;
            } else {
                return null;
            }
        }
    }

    /**
     * 	Функция возвращает «красивые значения, принимая на входе массив ввода
     * 	@param	array		$element		Массив ввода, или его часть, если часть — требуется указать префикс, если опущено — берется весь массив ввода
     * 	@param	string	$prefix			Префикс массива ввода — если подается не root, то следует указывать
     * 	@return array
     */
    function valueAll($element = null, $prefix = "") {

        if ($element === null && !$prefix)
            $element = $this->request;

        $return = array();

        if (@is_array($element))
            foreach ($element as $k => $v) {

                $name = ($prefix) ? $prefix . "|" . $k : $k;
                $return[$k] = $this->valueAll($element[$k], $name);
            } else {

            return $this->value($prefix, $element);
        }

        return $return;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function checkField($value, $name = false, $type = AJAXFORM_CHECK_DEFAULT, $pregPattern = '//', $pregReplace = '') {

        if (@is_array($value)) {

            foreach ($value as $k => $v)
                $value[$k] = $this->checkField($v, $type, $pregPattern, $pregReplace);
        } else {

            $value = (string) $value;
            //if ($name && !$this->fields[$name]['allowHTML']) $value = htmlSpecialChars($value);

            switch ($type) {

                case AJAXFORM_CHECK_CUSTOM: $value = preg_replace($pregPattern, $pregReplace, $value);
                    break;
                case AJAXFORM_CHECK_NUMERIC: $value = preg_replace('/[^0-9.,]/', '', $value);
                    break;
                case AJAXFORM_CHECK_LOGIN: $value = preg_replace('/[^-_a-z0-9]/sim', '', mb_strtolower($value));
                    break;
                case AJAXFORM_CHECK_CODE: $value = preg_replace('/[^0-9a-z]/i', '', mb_strtoupper($value));
                    break;
                case AJAXFORM_CHECK_PHONE: $value = preg_replace('/[^0-9- +,]/si', '', $value);
                    break;
                case AJAXFORM_CHECK_MAIL: $value = preg_replace('/[^0-9a-z@.+-_]/i', '', $value);
                    break;
                case AJAXFORM_CHECK_URL: $value = preg_replace('#[^0-9a-z%/?&@:.+-]#i', '', $value);
                    break;
                //case AJAXFORM_CHECK_URL:			$value = preg_replace('#['^0-9a-z%/?&@:.-+']#i', ''	, $value); break;
                default: break;
            }

            $value = trim($value);
        }

        return $value;
    }

    function checkFormat($value, $type, $pregPattern = '//') {

        switch ($type) {

            case AJAXFORM_FORMAT_CUSTOM: return (!empty($value) || $value == 0) && preg_match($pregPattern, $value);
            case AJAXFORM_FORMAT_MAIL: return (!empty($value) || $value == 0) && preg_match('/^[a-z0-9-_.+]+@[a-z0-9-.]{2,}\.[a-z0-9]{2,5}$/si', $value);
            case AJAXFORM_FORMAT_URL: return (!empty($value) || $value == 0) && preg_match('%^((http://|https://|www\.))[a-z0-9-.]+\.[a-z0-9]{2,5}.*%i', $value);
            case AJAXFORM_FORMAT_PHONE: return (!empty($value) || $value == 0) && preg_match('/^(?:\+\d \d{3,5} [\d-]{5,9},? ?)*$/si', $value); // only +7 123 456-78-90
            case AJAXFORM_FORMAT_LOGIN: return (!empty($value) || $value == 0) && preg_match('/^[-_a-z0-9]{3,}?$/i', $value);
            //case AJAXFORM_FORMAT_PHONE:	return (!empty($value) || $value == 0) && preg_match('/(\+\d{11})|(\d[ -]?\(?\d{3}\)?[ -]?[\d -]{7,9})/si',	$value);
            default: return null;
        }
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function addChecker($name, $type, $pregPattern = false, $pregReplace = false, $html = "") {

        $this->checkers[''] = array(
            "name" => $name,
            "type" => $type,
            "pregPattern" => $pregPattern,
            "pregReplace" => $pregReplace,
            "html" => $html,
        );
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function addFormat($name, $type, $pregPattern = false, $html = null) {

        $this->formats[''] = array(
            "name" => $name,
            "type" => $type,
            "pregPattern" => $pregPattern,
            "html" => $html,
        );
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    function addObligatory($name) {

        $this->obligatory[$name] = true;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    static function get($prefix) {

        foreach ($_REQUEST as $k => $v)
            if (stristr($k, "form_{$prefix}_"))
                return $k;
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //

    static function cleanSessionCache() {

        $path = AJAXFORM_CACHE;

        fileserver::createdironserver($path);

        $dirs = glob($path . "/*", GLOB_NOSORT | GLOB_ONLYDIR);

        if (is_array($dirs) && count($dirs))
            foreach ($dirs as $e) {

                $files = glob($e . "/*.php", GLOB_NOSORT);

                if (is_array($files) && count($files))
                    foreach ($files as $f) {

                        if (@filemtime($f) < time() - AJAXFORM_CACHE_LIFETIME)
                            @unlink($f);
                    }

                if (fileserver::is_dir_empty($e)) @rmdir($e);
            }
    }

    // --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
    function checkString($subject, $type = 'quotes', $maxLength = 0) {

        if (@is_array($subject)) {

            foreach ($subject as $k => $v)
                $subject[$k] = $this->checkString($v, $type, $maxLength);
        } else {

            $subject = trim($subject);
            if ($maxLength)
                $subject = mb_substr($subject, 0, $maxLength);

            //if ($type=='quotes') $subject = str_replace("'", "\'", $subject);

            if ($type == 'digits')
                $subject = preg_replace('/[^\\d]+/', '', $subject);
            if ($type == 'numeric')
                $subject = preg_replace('/[^\\d]+/', '', $subject);
            if ($type == 'alpha')
                $subject = preg_replace('/[^a-zA-Z]+/', '', $subject);
            if ($type == 'al-num')
                $subject = preg_replace('/[^\\w]+/', '', $subject);
            if ($type == 'mail')
                $subject = mb_substr(preg_replace('/[^-@_.\\w]+/', '', $subject), 0, 100);
            if ($type == 'phone')
                $subject = mb_substr(preg_replace('/[^-,.()\\w\\s]+/', '', $subject), 0, 100);
            if ($type == 'url')
                $subject = mb_substr(preg_replace('/[^-_:\/\/.:\\w]+/', '', $subject), 0, 100);
            if ($type == 'file')
                $subject = mb_strtolower(mb_substr(preg_replace('/[^-_.\\w]/', '', str_replace(" ", "_", $subject)), 0, 100));
            if ($type == 'id')
                $subject = mb_substr(preg_replace('/[^-_a-zA-Z0-9]+/', '', str_replace(" ", "_", $subject)), 0, 32);

            if ($type == 'file' && $subject == '')
                $subject = uniqid('file');
        }

        return $subject;
    }
    
    public function action_captha() {
	$form = new ajaxform($_REQUEST['formName']);
	$form->initConfirm();
	$form->confirmGenerate();
	$out = $form->confirmImage();
	$form->sessionSet();
        return $out;
    }

    // 2 функции преобразования даты для пикера и базы
    public static function date2datepicker($date) {
        return!empty($date) ? date("d.m.Y", mktime(0, 0, 0, ceil(substr($date, 5, 2)), ceil(substr($date, 8, 2)), ceil(substr($date, 0, 4)))) : date("d.m.Y");
    }

    public static function datepicker2date($date) {
        return substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
    }

}

if (!isset($_SERVER['modForm']['cacheNoClean']))
    ajaxform::cleanSessionCache();
?>