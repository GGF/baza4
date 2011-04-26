<?php

/*
 * nzap model class
 */

class lanch_zap_model extends sqltable_model {

    public function getData($all=false,$order='',$find='',$idstr='') {
        $ret = array();
	$sql="SELECT *,lanch.id AS lanchid,lanch.id
                FROM lanch
                JOIN (users,filelinks,coments,blocks,customers,tz,orders)
                ON (lanch.user_id=users.id AND lanch.file_link_id=filelinks.id
                    AND lanch.comment_id=coments.id AND lanch.block_id=blocks.id
                    AND blocks.customer_id=customers.id AND lanch.tz_id=tz.id
                    AND orders.id=tz.order_id) " .
                (!empty ($find)?"AND (blocks.blockname LIKE '%{$find}%' OR file_link LIKE '%{$find}%'
                    OR orders.number LIKE '%{$find}%')":"") .
                (!empty($order)?" ORDER BY ".$order." ":" ORDER BY lanch.id DESC ") .
                ($all?"LIMIT 50":"LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
	$cols["№"]="№";
	$cols[ldate]="Дата";
	$cols[lanchid]="ID";
	$cols[nik]="Запустил";
	$cols[customer]="Заказчик";
	$cols[number]="Заказ";
	$cols[blockname]="Плата";
	$cols[part]="Партия";
	$cols[numbz]="Заг.";
	$cols[numbp]="Плат";
        return $cols;
    }

    public function delete($id) {
	$sql="SELECT pos_in_tz_id FROM lanch WHERE id='{$id}'";
        // уберем признак запуска
	$rs=sql::fetchOne($sql);
	$sql="UPDATE posintz SET ldate='0000-00-00' WHERE id='{$rs["pos_in_tz_id"]}'";
	sql::query($sql);
	// удаление
	$sql = "DELETE FROM lanch WHERE id='{$id}'";
	sql::query($sql);
    }

    public function getSL($id) {
        $sql = "SELECT * FROM lanch WHERE id='{$id}'";
        $res = sql::fetchOne($sql);
        if (empty ($res))
            return false;
        $sql = "SELECT * FROM filelinks WHERE id='{$res[file_link_id]}'";
        $res = sql::fetchOne($sql);
        if (empty ($res))
            return false;
        $rec[link] = fileserver::sharefilelink($res[file_link]);
        $rec[id] = $id;
        return $rec;
    }
    
    public function getTZ($id) {
        $sql = "SELECT * FROM lanch WHERE id='{$id}'";
        $res = sql::fetchOne($sql);
        if (empty ($res))
            return false;
        $rec[id] = $res[tz_id];
        $sql = "SELECT * FROM tz WHERE id='{$res[tz_id]}'";
        $res = sql::fetchOne($sql);
        if (empty ($res))
            return false;
        $sql = "SELECT * FROM filelinks WHERE id='{$res[file_link_id]}'";
        $res = sql::fetchOne($sql);
        if (empty ($res))
            return false;
        $rec[link] = fileserver::sharefilelink($res[file_link]);
        return $rec;
    }
}

?>
