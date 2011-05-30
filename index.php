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
    if (!Ajax::isAjaxRequest())
        echo $m->getOutput();

if ($_SERVER[debug][report] && !($_REQUEST[level] == 'update' || $_REQUEST[level] == 'getdata')) {
    if (Ajax::isAjaxRequest()) {
        echo console::getInstance()->getScripts(); 
    } else
        echo console::getInstance()->run()->getOutput();
}

?>