<?php

/**
 * Возвращает строку по индексу и текущему языку
 *
 * @author ggf <gggf@ggf.name>
 */
class Lang extends JsCSS {

    /**
     * @var string - выбранный язык
     */
    static private $lang;

    /**
     * @var array - массив с корпусом используемых строк
     */
    static private $langtext;

    /**
     * Соддержит инстанс единственный
     * @var Lang 
     */
    static private $instance;

    /**
     * Конструктор, используется для создания синглтона
     */
    public function __construct($name=false, $directCall = true) {
        if ($directCall) {
            trigger_error(Lang::getString('Lang.errors.noconstructor'), E_USER_ERROR); // вызов в конструкторе самого себя?..
        }
        parent::__construct($name);
    }

    /**
     * Стаандартное получение инстанса
     * @return Object собственно инстанс
     */
    static public function getInstance() {
        return (self::$instance === null) ?
                self::$instance = new self('Auth', false) :
                self::$instance;
    }

    /**
     * Статический конструктор
     */
    static public function staticConsturct() {
        parent::staticConsturct();
        self::$langtext = array();
    }

    /**
     * Преопределять для модуля чтоб знал откуда брать свои файлы
     * @return string - каталог установки этого потомка
     */
    public function getDir() {
        return __DIR__;
    }

    /**
     * Возвращает имя JS скрипта для вставки в страницу
     * @return string
     */
    public function getJavascripts() {
        return array($this->getWebDir(__DIR__).'/js/'.self::$lang.'.js',$this->getWebDir(__DIR__).'/js/lang.js',);
    }

    /**
     * Добавляет массив текстов на разных языках
     * @param array $array
     * @return none - TODO: Возможно нужно себя же возвращать для цепочек
     */
    static public function add($array) {
        self::$langtext = array_replace_recursive(self::$langtext, $array);
    }

    /**
     * Возвращает строку из масива интернационализации на установленном языке
     * @param string $var - строка индекс массива с нужным текстом
     * @return string - строка на нужном языке
     */
    static public function getString($var) {
        $var = explode(".", $var);
        $tmp = self::$langtext;

        foreach ($var as $v)
            if (isset($tmp[$v]))
                $tmp = $tmp[$v]; else
                return implode(".", $var);

        $tmp = $tmp[self::$lang];

        return $tmp;
    }

    /**
     * Устанавливает рабочий язык
     * @var string - короткое именование языка
     * @return none
     */
    public function setLang($lang='en') {
        self::$lang = $lang;
        $_SERVER["lang"] = $lang;
    }

}

Lang::staticConsturct();
Lang::add(array(
    "alerts" => array(
        "sent" => array(
            "ru" => "СООБЩЕНИЕ ОТПРАВЛЕНО.\\n\\nВаше сообщение было успешно отправлено.\\n\\nСпасибо.",
            "en" => "MESSAGE SENT.\\n\\nYour message has been successfully sent.\\n\\nThank you."
        ),
        "code" => array(
            "ru" => "Неверный код подтверждения.",
            "en" => "Specified confirmation code is wrong."
        ),
        "time" => array(
            "ru" => "ОШИБКА. СООБЩЕНИЕ НЕ ОТПРАВЛЕНО.\\n\\nНедавно Вы уже отправляли сообщение.\\n\\nНа сайте действует ограничение по количеству отсылаемых\\nсообщений: одно сообщение раз в 10 минут.\\n\\nЭто ограничение установлено в целях борьбы со спамом.",
            "en" => "ERROR. MESSAGE IS NOT SENT.\\n\\nYou have sent the message already.\\n\\nYou can send only one message in 10 minutes due to\\n\\nanti-spam restrictions."
        ),
        "oblg" => array(
            "ru" => "Заполнены не все обязательные поля...",
            "en" => "Obligatory fields aren't filled..."
        ),
    ),
    "mail" => array(
        "generated" => array(
            "ru" => "Это письмо было сформировано автоматически.",
            "en" => "This letter is automatically-generated."
        ),
        "robot" => array(
            "ru" => "{$_SERVER["project"]["name"]}",
            "en" => "{$_SERVER["project"]["name"]}",
        ),
    ),
    "paging" => array(
        "pages" => array(
            "ru" => "Страницы",
            "en" => "Pages",
        ),
        "showed" => array(
            "ru" => "Показано",
            "en" => "Showed",
        ),
        "of" => array(
            "ru" => "из",
            "en" => "of",
        ),
    ),
    "submit" => array(
        "ru" => "Продолжить",
        "en" => "Submit",
    ),
    "reset" => array(
        "ru" => "Отмена",
        "en" => "Reset",
    ),
    "close" => array(
        "ru" => "Закрыть",
        "en" => "Close",
    ),
    "no-flash" => array(
        "ru" => "Вы должны установить Flash™ плеер чтобы увидеть ролик.",
        "en" => "You have to install Flash™ player in order to watch this movie.",
    ),
    "highlighted" => array(
        "ru" => "В тексте выделены слова, которые Вы искали",
        "en" => "Keywords you searched for are highlighted",
    ),
    "tags" => array(
        "ru" => "Тэги",
        "en" => "Tags",
    ),
    "sitemap" => array(
        "ru" => "Карта сайта",
        "en" => "Site map",
    ),
    "status" => array(
        "enabled" => array(
            "ru" => "включен",
            "en" => "enabled",
        ),
        "disabled" => array(
            "ru" => "выключен",
            "en" => "disabled",
        ),
    ),
));
?>
