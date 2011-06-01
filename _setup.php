<?php

Error_Reporting(E_ALL & ~E_NOTICE);

// КОНФИГУРАЦИЯ DEBUG РЕЖИМА

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
$_SERVER["debug"] = false;

if ($_REQUEST[level] == 'update') { //update лучше не выводить отладочный текст. как нить так отлажу
    $_SERVER["debug"]["noCache"]["php"] = true;
    $_SERVER["debug"]["report"] = false;
}

$_SERVER['SYSCACHE'] = $_SERVER['DOCUMENT_ROOT'] . '/tmp';
$_SERVER[CACHE] = $_SERVER['DOCUMENT_ROOT'] . '/tmp';

// База данных
$_SERVER["mysql"] = array(
    "lang" => array(
        "host" => "servermpp.mpp",
        "base" => "zaompp",
        "name" => "root",
        "pass" => "MMnnHs",
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

// Временная зона
date_default_timezone_set("Europe/Moscow");

// Кодировки
$_SERVER[Encoding] = "UTF-8";     // HTML
$_SERVER[EncodingSQL] = 'utf8';   // SQL
$_SERVER[EncodingCP] = 'UTF-8';   // 
$_SERVER[EncodingFS] = "UTF-8";   // File system

// настройки файлового сервера
// на каком сервере файлы шарятся
define("NETBIOS_SERVERNAME", "servermpp");
// коренвой катлог  для share [z] и [t]
define("SHARE_ROOT_DIR", "/home/common/");
// каталог сохранения файлов относительно DOCUMENT_ROOT
define("UPLOAD_FILES_DIR", "/files/");

ob_start();
include __DIR__ . '/_engine/autoload.php'; // инклудим автозагрузку модулей
$tmp = ob_get_clean();

// перехватим ошибки
if ($_SERVER[debug][report]) {
    console::getInstance();//->out(print_r($_REQUEST, true));
    profiler::add('Autoexec', 'Выполнение начальных установок');
}

header("Content-Type: text/html; charset={$_SERVER[Encoding]}");

if (!($_REQUEST[level] == 'update' || $_REQUEST[level] == 'getdata')) { // update делается без авторизации
    if (!Auth::getInstance()->run()->success) {
        echo Auth::getInstance()->getOutput();
        echo console::getInstance()->run()->getOutput();
        exit;
    }
}
?>