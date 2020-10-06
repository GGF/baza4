<?php

class Root{
	private $vars = array();	

	static private $instance = null;
	static public function getInstance(){
		if(self::$instance == null) self::$instance = new self();
		return self::$instance;
	}

	static public function i(){
		return self::getInstance();
	}

	private function __construct(){}

	public function setVar($key, $value){
		$this->vars[$key] = $value;
	}

	public function getVar($key){
		if(empty($this->vars[$key])) return null;
		return $this->vars[$key];
	}
}

?>