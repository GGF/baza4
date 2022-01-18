<?php
class bashcite_model {
	/*
	* @var Хранит текущую выбранную цитату
	*/
	private $currentid;
	
	public function __construct() {
		$currentid = false;
	}
	
	public function getRandom() {
		$sql = "SELECT * FROM bash WHERE rate>=0 ORDER BY RAND() LIMIT 1";
		$rs = sql::fetchOne($sql);
		$this->currentid = $rs["id"];
		return html_entity_decode($rs["quote"]);
	}
	
	public function plus() {
		$sql="UPDATE bash SET rate=rate+1 WHERE id='{$this->currentid}'";
		sql::query($sql);
	}
	
	public function minus() {
		$sql="UPDATE bash SET rate=rate-1 WHERE id='{$this->currentid}'";
		sql::query($sql);
	}
}
?>