<?php

/*
 * nzap model class
 */

class lanch_zap_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'lanch';
    }

    public function getData($all=false,$order='',$find='',$idstr='') {
        $ret = array();
        // еще убрал forign ключи в таблице blockpos, а то там удалялось
        if (!empty ($find)) {
            $sql = "SELECT blockpos.block_id FROM blockpos JOIN boards ON blockpos.board_id=boards.id WHERE board_name LIKE '%{$find}%' GROUP BY blockpos.block_id";
            $res = sql::fetchAll($sql);
            if (!empty($res)) {
                foreach ($res as $value) {
                    $ids[] = $value[block_id];
                }
                $ids = "blocks.id IN (".join(',', $ids).") OR ";
                
            }
        }
	$sql="SELECT *,
                IF(part=0,'<span style=\'color:red\'>Удалена</span>',
                    IF(part=-1,'<span style=\'color:green\'>Из задела</span>',
                    IF(part=-2,'<span style=\'color:blue\'>Дозапуск</span>',part))) AS part,
                IF(boards.layers>2,'МПП','ДПП') AS boardtype,
                lanch.id AS lanchid,
                lanch.id
                FROM lanch
                JOIN (users,filelinks,coments,blocks,customers,tz,orders)
                ON (lanch.user_id=users.id AND
                    lanch.file_link_id=filelinks.id AND
                    lanch.comment_id=coments.id AND
                    lanch.block_id=blocks.id AND
                    blocks.customer_id=customers.id AND
                    lanch.tz_id=tz.id AND
                    orders.id=tz.order_id)
                 LEFT JOIN (blockpos,boards) ON blockpos.block_id=blocks.id AND blockpos.board_id = boards.id " .
                (!empty ($find)?"WHERE ({$ids} blocks.blockname LIKE '%{$find}%' OR file_link LIKE '%{$find}%'
                    OR orders.number LIKE '%{$find}%')":"") .
                    " GROUP BY lanch.id,blocks.blockname " .
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
	$cols[blockname]="Блок";
	$cols[boardtype]="Тип";
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
	//$sql = "DELETE FROM lanch WHERE id='{$id}'";
        // не хочу удалять. пусть все остаются
        $sql = "UPDATE lanch SET part='0' WHERE id='{$id}'";
        // todo: воссстановление задела
	sql::query($sql);
    }

    
    public function getRecord($edit) {
        $rec = parent::getRecord($edit);
        $sql = "SELECT * FROM blockpos JOIN boards ON boards.id=blockpos.board_id WHERE block_id={$rec[block_id]}";
        $rec[boards] = sql::fetchAll($sql);
        foreach ($rec[boards] as &$value) {
            $value[filelinks] = $this->getFilesForId('boards', $value[board_id]);
        }
        return $rec;
    }

    public function getSL($id) {
        $rec=array();
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
        $rec=array();
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

    public function getLetter($id) {
        $rec=array();
        $sql = "SELECT * FROM lanch WHERE id='{$id}'";
        $res = sql::fetchOne($sql);
        if (empty ($res))
            return false;
        $sql = "SELECT * FROM tz WHERE id='{$res[tz_id]}'";
        $res = sql::fetchOne($sql);
        if (empty ($res))
            return false;
                // файлы  для заказа
        $files = $this->getFilesForId('orders', $res[order_id]);
        $rec[link] = $files[link];
        return $rec;
    }

    public function getPath($id) {
        $sql = "SELECT customer,blockname
            FROM lanch JOIN (blocks,customers)
                            ON (blocks.id=lanch.block_id
                                    AND customers.id=blocks.customer_id )
                WHERE lanch.id='{$id}'";
        $rs = sql::fetchOne($sql);
        return "z:\\Заказчики\\{$rs['customer']}\\{$rs['blockname']}";
    }

}

?>
