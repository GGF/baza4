<?php

class lanch_mp_model extends sqltable_model {
    
    /** 
     * обязательно определять для модуля 
    */
    public function getDir() {
        return __DIR__;
    }

    /**
     * Конструктор
     */
    public function __construct() {
        parent::__construct();
        $this->maintable = 'masterplate';
    }

    /**
     * Получение данных для таблицы
     */
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

    /**
     * Получение имен колонок
     */
    public function getCols() {
        $cols = array();
        $cols['mpid']="ID";
        $cols['mpdate']="Дата";
        $cols['nik']="Кто запустил";
        $cols['customer']="Заказчик";
        $cols['blockname']="Плата";
        return $cols;
    }

    /**
     * Удаление записи
     */
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
     * @return array - массив данных
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
     * @param array $data - массив сохранения данных полученных из формы
     */
    public function setRecord($data) {
        return true; // закроем в любом случае
    }
    /**
     * Создает файл мастерплаты
     * @param array $rec данные из модели для формирования файла
     * @return array - та же запись с удачностью
    */
    public function createMPFile($rec) {
        $excel = file_get_contents($this->getDir() . "/mp.xls");
        if (fileserver::savefile($rec['filename'],$excel)) {
            $mp['_date_'] = date("d.m.Y");
            if (is_numeric($rec['mp_id'])) {
                // для числовых идентификаторов расширим нулями
                $mp['_number_'] = sprintf("%08d\n",$rec['mp_id']);
            } else {
                $mp['_number_'] = $rec['mp_id'];
            }
            if (fileserver::savefile($rec['filename'].".txt",$mp)) {
                $rec['success'] = true;
            } else {
                $rec['success'] = false;
                $rec['error_string'] = Lang::getString('error.cantcreatefile') . ' txt';
            }
        } else {
            $rec['success'] = false;
            $rec['error_string'] = Lang::getString('error.cantcreatefile') . ' xls' . print_r($rec,true);
        }

        return $rec;
    }

}
