<?php

if (!isset($_SESSION)) {
    session_start();
}

if (empty($_REQUEST["level"]))
    $_REQUEST["level"] = 'baza';

include "_setup.php";


$classname = $_REQUEST["level"];
$m = new $classname();
$m->run();
echo $m->getOutput();

if ($_SERVER[debug][report]) {
    echo console::getInstance()->run()->getOutput();
}
// сохранить кэш автовставки
if (!$_SERVER["debug"]["noCache"]["php"] && !file_exists($_SERVER['SYSCACHE'] . '/autoexec_' . md5(implode($_REQUEST, '')) . '.php')) {
    cache::buildPHP(md5(implode($_REQUEST, '')), array_unique($_SESSION[cache]));
}
?>