$(document).ready(function () 
{
	var html = "<applet code='zaompp.bazaApplet' archive='<?php echo str_ireplace($_SERVER['DOCUMENT_ROOT'], "", __DIR__);?>/BazaApplet.jar' width=1 height=1 name='bazaapplet'><param name='cmd' value='cmd.exe /c'>Applet for open files and clipboard (if you see it java-plugin not started)</applet>";
	$('body').append(html);
});
