<?php

/*
 * conduct model class
 */

class lanch_conduct_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'conductors';
    }

    /**
     * В классе
     * @return system
     */
    public function getDir() {
	return __DIR__;
    }

    public function getData($all=false,$order='',$find='',$idstr='') {
        $ret = array();
        $sql="SELECT *,conductors.id AS condid,boards.id AS plid,conductors.id
                FROM conductors
                JOIN (boards,customers)
                ON (conductors.board_id=boards.id
                    AND boards.customer_id=customers.id )
                WHERE ready='0' " .
                (!empty($find)?" WHERE (board_name LIKE '%{$find}%')":"") .
                (!empty($order)?" ORDER BY {$order} ":" ORDER BY conductors.id DESC ") .
                ($all?"":"LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }
    public function getCols() {
        $cols = array();
        $cols[condid]="ID";
        $cols[customer]="Заказчик";
        $cols[board_name]="Плата";
        $cols[side]="Сторона";
        $cols[lays]="Пластин";
        $cols[pib]="Плат в блоке";
        return $cols;
    }
    public function delete($id) {
        $sql = "UPDATE conductors SET ts=NOW(), user_id='".Auth::getInstance()->getUser('userid')."', ready='1' WHERE id='{$id}'";
	sql::query($sql);
        return sql::affected();
    }
    public function getRecord($id) {
        $sql="SELECT *, customers.id AS cusid, conductors.board_id AS plid
                FROM conductors
                JOIN (customers,boards)
                ON (
                    conductors.board_id=boards.id
                    AND boards.customer_id=customers.id
                    )
                WHERE conductors.id='$id'";
        $rec = sql::fetchOne($sql);
        return $rec;
    }
    public function  setRecord($data) {
        extract($data);
	if (!empty($edit)) {
		$sql = "UPDATE conductors SET pib='{$pib}', side='{$side}', lays='{$lays}', user_id='".Auth::getInstance()->getUser('userid')."', ts=NOW() WHERE id='{$edit}'";

	} else {
		$sql = "INSERT INTO conductors (board_id,pib,side,lays,user_id,ts) VALUES('{$board_id}','{$pib}','{$side}','{$lays}','".Auth::getInstance()->getUser('userid')."',NOW())";
	}
	sql::query($sql);
        return sql::affected();
    }
}

?>
