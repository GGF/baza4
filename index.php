<?php

if (!isset($_SESSION)) {
    session_start();
}

if (empty($_REQUEST['level'])) {
    $_REQUEST['level'] = 'baza';
}

include "_setup.php";

header("Content-Type: text/html; charset={$_SERVER['Encoding']}");

if ($_SERVER['Auth']) { // update и getdata делается без авторизации
    if (!Auth::getInstance()->run()->success) {
        echo Auth::getInstance()->getOutput();
        echo console::getInstance()->run()->getOutput();
        exit;
    }
}

$classname = $_REQUEST['level'];
$m = new $classname();
if ($m->run()) {
    if (!Ajax::isAjaxRequest()) {
        echo $m->getOutput();
    }
}

//    echo "<script>";
//    echo "storeSetting(".multibyte::Json_encode($_SESSION['user_setting']).");";
//    echo "</script>";

if ($_SERVER['debug']['report']) {
    if (Ajax::isAjaxRequest()) {
        echo console::getInstance()->getScripts();
    } else {
        echo console::getInstance()->run()->getOutput();
    }
}
?>