<?php

class lanch_mp_model extends sqltable_model {
    public function __construct() {
        parent::__construct();
        $this->maintable = 'masterplate';
    }
    public function getData($all=false,$order='',$find='',$idstr='') {
        $ret = array();
        if (!empty($find)) {
            
        }
        $sql = "SELECT *, masterplate.id AS mpid,masterplate.id " .
                "FROM masterplate " .
                "JOIN (users,blocks,customers) " .
                "ON ( " .
                "masterplate.user_id=users.id " .
                "AND blocks.customer_id=customers.id " .
                "AND masterplate.block_id=blocks.id " .
                ") " .
                (!empty($find) ? "WHERE blockname LIKE '%{$find}%'
                            OR customer LIKE '%{$find}%'":"") .
                (!empty($order)?"ORDER BY {$order} ":
                                        "ORDER BY masterplate.id DESC ") .
                ($all?"LIMIT 50":"LIMIT 20");

        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
	$cols[mpid]="ID";
	$cols[mpdate]="Дата";
	$cols[nik]="Кто запустил";
	$cols[customer]="Заказчик";
	$cols[blockname]="Плата";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM masterplate WHERE id='{$id}'";
	sql::query($sql);
        return sql::affected();
    }
}

?>
