<?php
class Ajax{
	static private $key = 'ajax';
	static public function key(){ return self::$key; }
	
	static public function isAjaxRequest(){ 
		return (bool)self::getTarget();
	}
	
	static public function getTarget(){ 
		if(!empty($_POST[self::$key])) return $_POST[self::$key];
		if(!empty($_GET[self::$key])) return $_GET[self::$key];
		return false;
	}

	static public function pureGet(){
		$get = $_GET;
		unset($get[self::$key]);
		return $get;
	}
	
}
?>