<?php

/**
 * Description of lanch_zad_model
 *
 * @author igor
 */
class lanch_zad_model extends sqltable_model {
    /* @var $all boolean */
    /* @var $order string */
    /* @var $find string */

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT *,zadel.id AS zid,zadel.id, board_name AS plate
                FROM zadel
                JOIN (boards,customers)
                ON (zadel.board_id=boards.id
                   AND boards.customer_id=customers.id) " .
                (!empty($find) ? "WHERE (board_name LIKE '%{$find}%'
                                OR customers.customer LIKE '%{$find}%')" : "") .
                (!empty($order) ? " ORDER BY {$order} " : " ORDER BY zadel.id DESC ") .
                ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }
    public function getCols() {
        $cols = array(
            "№" => "№",
            "zid" => "ID",
            "customer" => "Заказчик",
            "plate" => "Плата",
            "niz" => "№ изв.",
            "ldate" => "Дата запуска",
            "number" => "Кол-во",
        );
        return $cols;
    }
    public function delete($id) {
        $sql = "DELETE FROM zadel WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }
    public function getRecord($id) {
        $sql = "SELECT *, customers.id AS cusid, boards.id AS board_id
                FROM zadel
                JOIN (customers,boards)
                ON (zadel.board_id=boards.id AND boards.customer_id=customers.id)
                WHERE zadel.id='{$id}'";
        $rec = sql::fetchOne($sql);
        return $rec;
    }

    public function  setRecord($data) {
        extract($data);
        if (!empty($edit)) {
            $sql = "UPDATE zadel SET number = '{$number}', ldate='{$ldate}', niz='{$niz}' WHERE id='{$edit}'";
        } else {
            $sql = "INSERT INTO zadel (board_id,ldate,number,niz) VALUES('{$board_id}','{$ldate}','{$number}','{$niz}')";
        }
	sql::query($sql);
        return true;//sql::affected();
    }

}

?>
