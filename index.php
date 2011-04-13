<?php
include "_setup.php";
if (empty($_SESSION[level])) $_SESSION[level]='baza';
$classname = $_SESSION[level];
$m = new $classname();
$m->run();
echo $m->getOutput();
?>