<?php

include __DIR__ . '/_engine/autoload.php'; // инклудим автозагрузку модулей
//также тут всякие настройки базы данных, и вообще любые настройки проекта

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
$_SERVER['SYSCACHE'] = $_SERVER['DOCUMENT_ROOT'] . '/tmp';
$_SERVER[CACHE] = $_SERVER['DOCUMENT_ROOT'] . '/tmp';
//$_SERVER["debug"] = false;

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

// перехватим ошибки
if ($_SERVER[debug][report]) {
    console::getInstance();
    profiler::add('Autoexec', 'Выполнение начальных установок');
}


// Временная зона
date_default_timezone_set("Europe/Moscow");

$_SERVER[cmsEncoding] = "windows-1251";
$_SERVER[cmsEncodingSQL] = 'CP1251';
$_SERVER[cmsEncodingCP] = 'CP1251';
$_SERVER[cmsEncodingFS] = "UTF-8";   // File system

// настройки файлового сервера
// на каком сервере файлы шарятся
define("NETBIOS_SERVERNAME","servermpp"); 
// коренвой катлог  для share [z] и [t]
define("SHARE_ROOT_DIR","/home/common/"); 
// каталог сохранения файлов относительно DOCUMENT_ROOT
define("UPLOAD_FILES_DIR","/files"); 

header("Content-Type: text/html; charset={$_SERVER[cmsEncoding]}");

if (!Auth::getInstance()->run()->success){
       echo Auth::getInstance()->getOutput();
       echo console::getInstance()->run()->getOutput();
       exit;
}

?>