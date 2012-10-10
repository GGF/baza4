<?php

class storage_model extends sqltable_model {

    public $db;
    public $sklad;

    public function __construct() {
        parent::__construct();
        $this->db = "`$_SERVER[storagebase]`.";
        $this->sklad = storages::$storages[$_SESSION[Auth::$lss][storagetype]][sklad];
        $this->maintable = "{$this->db}sk_{$this->sklad}_spr";
    }

    public function getNeedArc() {
	return false; //чтото  глючит или ктото. архивнули движения
        $sql = "SELECT YEAR(NOW())>(YEAR(sk_{$this->sklad}_dvizh_arc.ddate)+1) AS need
                FROM {$this->db}sk_{$this->sklad}_dvizh_arc
                ORDER BY ddate DESC LIMIT 1";
        $rs = sql::fetchOne($sql);
        return $rs[need];
    }

}

?>
