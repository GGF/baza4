<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT );


// КОНФИГУРАЦИЯ DEBUG РЕЖИМА

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? 
                                  getenv('APPLICATION_ENV') : 
                                  'production'));

if  (APPLICATION_ENV === "development") {

    $_SERVER["debug"] = array(
        "report" => true,
        "noCache" => array(
            "php" => true,
            "js" => true,
            "css" => true,
        ),
        "showNotices" => true,
        "checkReverse" => false,
    );
} else {
    $_SERVER["debug"] = false;
}

$_SERVER["Auth"] = true;

if ($_REQUEST["level"] == 'update' || $_REQUEST["level"] == 'getdata') { //update лучше не выводить отладочный текст. как нить так отлажу
    $_SERVER["debug"]["noCache"]["php"] = true;
    $_SERVER["debug"]["report"] = false;
    $_SERVER["Auth"] = false;
}

$_SERVER['SYSCACHE'] = $_SERVER['DOCUMENT_ROOT'] . '/tmp';
$_SERVER['CACHE'] = $_SERVER['DOCUMENT_ROOT'] . '/tmp';

// База данных
$_SERVER["mysql"] = array(
    "lang" => array(
        "host" => getenv('APPLICATION_DBHOST') ,
        "base" => getenv('APPLICATION_DB') ,
        "name" => getenv('APPLICATION_DBUSER') ,
        "pass" => getenv('APPLICATION_DBPASS') ,
        "log" => array(
            "query" => true,
            "notice" => true,
            "warning" => true,
            "error" => true,
        ),
        "noCollation" => false,
        "persistent" => true,
    ),
);

$_SERVER["storagebase"] = getenv('APPLICATION_DB2');

// Временная зона
date_default_timezone_set("Europe/Moscow");

// Кодировки
$_SERVER["Encoding"] = "UTF-8";     // HTML
$_SERVER["EncodingSQL"] = 'utf8';   // SQL
$_SERVER["EncodingCP"] = 'UTF-8';   // 
$_SERVER["EncodingFS"] = "UTF-8";   // File system

// настройки файлового сервера
// на каком сервере файлы шарятся
define("NETBIOS_SERVERNAME", "servermpp");
// коренвой катлог  для share [z] и [t]
define("SHARE_ROOT_DIR", "/home/common/");
// каталог сохранения файлов относительно DOCUMENT_ROOT
define("UPLOAD_FILES_DIR", "/files/");

ob_start();


    //правильно использовать автозагрузку следует с использованием библиотеки SPL
    //Для этого нужно включить в include_path нужные нам пути
    set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . // <editor-fold defaultstate="collapsed" desc="Длинный путь">
            DIRECTORY_SEPARATOR// </editor-fold>
            .'_engine/classes' . PATH_SEPARATOR . __DIR__ . DIRECTORY_SEPARATOR . 'classes' );
    // потом зарегистрировать расширения
    spl_autoload_extensions ('.php,.class.php');
    // при правильном использовании namespace достаточно только (PSR-0  https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
    spl_autoload_register();
    // а так придестя зарегить функцию вслед за дефолтной
    spl_autoload_register('Autoloader::loadPackages');
    // сам класс 'Autoloader' описан по PSR-0
    // очень внимательно файлы будут искаться в МАЛЕНЬКИМИ буквами autoloader.php - Это известный БАГ и скоро может быть пофиксен
    
/*
 * Строки ниже  есть в файле autoload
 * НО с использованием SPL приишлось вставить сюда потому, что
 * auttoload включался  каждый раз, а теперь используется include_once
 */    
if (!$_SERVER["debug"]["noCache"]["php"]) {
    if(!empty($_SESSION["cache"]) && is_array($_SESSION["cache"])) {
        require_once realpath($_SERVER['DOCUMENT_ROOT']) . 
            cache::buildScript($_SESSION["cache"], 'php');
    }
}
/*
 * пожалуй уберу строчку из файла autoload и поставлю включение сюда
 */
//include_once __DIR__ . '/_engine/autoload.php'; // инклудим автозагрузку модулей

ob_get_clean();
/*
 * Делать хоть один инстанс нужно для включения скриптов в заголовки
 */
Lang::getInstance()->setLang('ru');

// перехватим ошибки
if ($_SERVER["debug"]["report"]) {
    console::getInstance();//->out(print_r($_REQUEST, true));
    profiler::add('Autoexec', 'Выполнение начальных установок');
}


?>