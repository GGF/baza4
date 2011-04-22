<?php

class storage_rest_model extends sqltable_model {

    public $db;
    public $sklad;

    public function __construct() {
        parent::__construct();
        $this->db = '`zaomppsklads`.';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT *,if((krost>ost),'<span style=\'color:red\'><b>мало</b></span>','') AS malo,
                    sk_{$this->sklad}_spr.id
              FROM {$this->db}sk_{$this->sklad}_spr
              JOIN {$this->db}sk_{$this->sklad}_ost
              ON sk_{$this->sklad}_ost.spr_id=sk_{$this->sklad}_spr.id
              WHERE nazv!='' " .
                (!empty($find) ? " AND nazv LIKE '%{$find}%' " : "") .
                (!empty($order) ? " ORDER BY {$order} " : "ORDER BY nazv ") .
                ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[nazv] = "Название";
        $cols[edizm] = "Ед.Изм.";
        $cols[ost] = "Остаток на складе";
        $cols[krost] = "Крит. кол-во";
        $cols[malo] = "Внимание";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "INSERT INTO {$this->db}sk_arc_{$this->sklad}_spr (nazv,edizm,krost)
                SELECT sk_{$this->sklad}_spr.nazv,sk_{$this->sklad}_spr.edizm,sk_{$this->sklad}_spr.krost
                FROM {$this->db}sk_{$this->sklad}_spr
                WHERE sk_{$this->sklad}_spr.id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $id = sql::lastId();
        $sql = "DELETE FROM {$this->db}sk_{$this->sklad}_spr WHERE id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "INSERT INTO {$this->db}sk_arc_{$this->sklad}_ost (spr_id,ost)
                SELECT $id,sk_{$this->sklad}_ost.ost
                FROM {$this->db}sk_{$this->sklad}_ost
                WHERE sk_{$this->sklad}_ost.spr_id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "DELETE FROM {$this->db}sk_{$this->sklad}_ost WHERE spr_id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "INSERT INTO {$this->db}sk_arc_{$this->sklad}_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price)
                SELECT sk_{$this->sklad}_dvizh.type,sk_{$this->sklad}_dvizh.numd,sk_{$this->sklad}_dvizh.numdf,sk_{$this->sklad}_dvizh.docyr,{$id},sk_{$this->sklad}_dvizh.quant,sk_{$this->sklad}_dvizh.ddate,sk_{$this->sklad}_dvizh.post_id,sk_{$this->sklad}_dvizh.comment_id,sk_{$this->sklad}_dvizh.price
                FROM {$this->db}sk_{$this->sklad}_dvizh
                WHERE sk_{$this->sklad}_dvizh.spr_id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "DELETE FROM {$this->db}sk_{$this->sklad}_dvizh WHERE spr_id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "INSERT INTO {$this->db}sk_arc_{$this->sklad}_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price)
                SELECT sk_{$this->sklad}_dvizh_arc.type,sk_{$this->sklad}_dvizh_arc.numd,sk_{$this->sklad}_dvizh_arc.numdf,sk_{$this->sklad}_dvizh_arc.docyr,{$id},sk_{$this->sklad}_dvizh_arc.quant,sk_{$this->sklad}_dvizh_arc.ddate,sk_{$this->sklad}_dvizh_arc.post_id,sk_{$this->sklad}_dvizh_arc.comment_id,sk_{$this->sklad}_dvizh_arc.price
                FROM {$this->db}sk_{$this->sklad}_dvizh_arc
                WHERE sk_{$this->sklad}_dvizh_arc.spr_id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "DELETE FROM {$this->db}sk_{$this->sklad}_dvizh_arc WHERE spr_id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        return $affected;
    }

    public function getRecord($edit) {
        $sql = "SELECT * FROM {$this->db}sk_{$this->sklad}_spr WHERE id='{$edit}'";
        return parent::getRecord($sql);
    }

    public function setRecord($data) {
        extract($data);
        if (empty($edit)) {
            $sql = "INSERT INTO {$this->db}sk_{$this->sklad}_spr (nazv,edizm,krost) VALUES ({$nazv},{$edizm},{$krost})";
            sql::query($sql);
            $sprid = sql::lastId();
            $sql = "INSERT INTO {$this->db}sk_{$this->sklad}_ost (spr_id,ost) VALUES ('{$sprid}','0')";
            sql::query($sql);
        } else {
            $sql = "UPDATE {$this->db}sk_{$this->sklad}_spr SET nazv='{$nazv}', edizm='{$edizm}', krost='{$krost}' WHERE id='{$edit}'";
            sql::query($sql);
        }
        return sql::affected();
    }

    public function getNeedArc() {
        $sql = "SELECT YEAR(NOW())>(YEAR(sk_{$this->sklad}_dvizh_arc.ddate)+1) AS need
                FROM {$this->db}sk_{$this->sklad}_dvizh_arc
                ORDER BY ddate DESC LIMIT 1";
        $rs = sql::fetchOne($sql);
        return $rs[need];
    }

}

?>
