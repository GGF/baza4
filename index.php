<?php

if (!isset($_SESSION)) {
    session_start();
}

if (empty($_SESSION["level"]))
    $_SESSION["level"] = 'baza';
//TODO: костыль
$_REQUEST["level"] = $_SESSION["level"]; // для правильного кэша

include "_setup.php";


$classname = $_SESSION["level"];
$m = new $classname();
$m->run();
echo $m->getOutput();

//echo console::getInstance()->run()->getOutput();
// сохранить кэш автовставки
if (!$_SERVER[debug][noCache][php] || !file_exists($_SERVER['SYSCACHE'] . '/autoexec_' . md5(implode($_REQUEST, '')) . '.php')) {
    cache::buildPHP(md5(implode($_REQUEST, '')), array_unique($_SESSION[cache]));
}
?>