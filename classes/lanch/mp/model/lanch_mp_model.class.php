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
        $cols['mpid']="ID";
        $cols['mpdate']="Дата";
        $cols['nik']="Кто запустил";
        $cols['customer']="Заказчик";
        $cols['blockname']="Плата";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM masterplate WHERE id='{$id}'";
	    sql::query($sql);
        return sql::affected();
    }

    /**
     * Получает данные для создания мастерплаты по идентификатору позиции в техзадании
     * @param integer $tzposid идентификатор позиции в техзадании к которому относится мастерплата
     * @return array данные
     */
    public function newByTZposid($tzposid) {
        $sql = "SELECT * FROM posintz JOIN (tz,orders) ON (tz.id=tz_id AND orders.id=tz.order_id) WHERE posintz.id='{$tzposid}'";
        $rs = sql::fetchOne($sql);
        $block_id = $rs['block_id'];
        $sql = "SELECT * FROM masterplate WHERE posid='{$tzposid}'";
        $res = sql::fetchOne($sql);
        if (empty($res)) {
            $sql = "INSERT INTO masterplate (mpdate,user_id,posid,block_id)
                    VALUES (Now(),'" . Auth::getInstance()->getUser('userid') . "','{$tzposid}','{$block_id}')";
            sql::query($sql);
            $rec['mp_id'] = sql::lastId();
        } else {
            $sql = "UPDATE masterplate SET mpdate=NOW(), user_id='" . Auth::getInstance()->getUser('userid') . "' WHERE id='{$res['id']}'";
            $rs = sql::fetchOne($res);
            $rec['mp_id'] = $res['id'];
        }
        $sql = "SELECT * FROM blocks JOIN customers ON blocks.customer_id=customers.id WHERE blocks.id='{$block_id}'";
        $rs = sql::fetchOne($sql);
        $rec['customer'] = $rs['customer'];
        $rec['blockname'] = $rs['blockname'];
        $rec['sizex'] = $rs['sizex'];
        $rec['sizey'] = $rs['sizey'];
        $rec['drlname'] = $rs['drlname'];
        $rec['date'] = date("Y-m-d");
        return $rec;
    }    
    /**
     * Получение записи
     * @param int $id - идентификатор 
     */
    public function getRecord($id) {
        $rec = parent::getRecord($id);
        $rec['block'] = $this->getBlock($rec['block_id']);
        $rec['customer'] = $this->getCustomer($rec['block']['customer_id']);
        $rec['mp_id'] = $rec['id'];
        $rec['customer'] = $rec['customer']['customer'];
        $rec['date'] = $rec['mpdate'];
        $rec['blockname'] = $rec['block']['blockname'];
        return $rec;
    }

    /**
     * Сохранение записи
     * 
     * @var array $data - массив сохранения данных полученные из формы
     */
    public function setRecord($data) {
        extract($data);
        $sql = "INSERT INTO masterplate (mpdate,user_id,posid,block_id)
                VALUES (Now(),'" . Auth::getInstance()->getUser('userid') . "','0','{$block_id}')";
        sql::query($sql);
        //$rec['mp_id'] = sql::lastId();
        return sql::error();
    }
    
}
