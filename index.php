<?php

if (!isset($_SESSION)) {
    session_start();
}

if (empty($_REQUEST["level"]))
    $_REQUEST["level"] = 'baza';

include "_setup.php";


$classname = $_REQUEST["level"];
$m = new $classname();
if ($m->run())
    echo $m->getOutput();

if ($_SERVER[debug][report]) {
    echo console::getInstance()->run()->getOutput();
}
// сохранить кэш автовставки
if (!$_SERVER["debug"]["noCache"]["php"]) {
    cache::buildScript($_SESSION[cache],'php');
}
?>