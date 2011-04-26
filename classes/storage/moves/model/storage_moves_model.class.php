<?php

class storage_moves_model extends storage_rest_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $spr_id = $_SESSION[spr_id];
        if (empty($spr_id))
            return array();
        if ($all) {
            $sql = "(SELECT *,if(type='1','Приход','Расход') AS prras,
                        sk_{$this->sklad}_dvizh.id
                     FROM {$this->db}sk_{$this->sklad}_dvizh
                     JOIN ({$this->db}sk_{$this->sklad}_postav,{$this->db}coments)
                     ON (sk_{$this->sklad}_postav.id=sk_{$this->sklad}_dvizh.post_id
                        AND coments.id=sk_{$this->sklad}_dvizh.comment_id) WHERE spr_id='{$spr_id}' " .
                    (!empty($find) ? "AND comment LIKE '%{$find}%' OR supply LIKE '%{$find}%' OR numd LIKE '%{$find}%' " : "") .
                    ") UNION (
                    SELECT *,if(type='1','Приход','Расход') AS prras, sk_{$this->sklad}_dvizh_arc.id
                    FROM {$this->db}sk_{$this->sklad}_dvizh_arc
                    JOIN ({$this->db}sk_{$this->sklad}_postav,{$this->db}coments)
                    ON (sk_{$this->sklad}_postav.id=sk_{$this->sklad}_dvizh_arc.post_id
                        AND coments.id=sk_{$this->sklad}_dvizh_arc.comment_id) WHERE spr_id='{$spr_id}' " .
                    (!empty($find) ? "AND comment LIKE '%{$find}%' OR supply LIKE '%{$find}%' OR numd LIKE '%{$find}%' " : "") .
                    ") " .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY ddate ");
        } else {
            $sql = "SELECT *,if(type='1','Приход','Расход') AS prras, sk_{$this->sklad}_dvizh.id 
                    FROM {$this->db}sk_{$this->sklad}_dvizh
                    JOIN ({$this->db}sk_{$this->sklad}_postav,{$this->db}coments)
                    ON (sk_{$this->sklad}_postav.id=sk_{$this->sklad}_dvizh.post_id
                        AND coments.id=sk_{$this->sklad}_dvizh.comment_id)
                    WHERE spr_id='{$spr_id}' " .
                    (!empty($find) ? " AND comment LIKE '%{$find}%' OR supply LIKE '%{$find}%' OR numd LIKE '%{$find}%' " : "") .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY ddate ");
        }
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[id] = "ID";
        $cols[ddate] = "Дата";
        $cols[prras] = "+/-";
        $cols[numd] = "№ док.";
        $cols[supply] = "Поставщик";
        $cols[quant] = "Кол-во";
        $cols[comment] = "Примечание";
        $cols[price] = "Цена";
        return $cols;
    }

    public function delete($delete) {
        $affected = 0;
        $sql = "SELECT * FROM {$this->db}sk_{$this->sklad}_dvizh
                WHERE id='{$delete}'";
        $rs = sql::fetchOne($sql);
        $sql = "UPDATE {$this->db}sk_{$this->sklad}_ost
                SET ost=ost" . ($rs["type"] ? "-" : "+") . abs($rs["quant"]) . " " .
                "WHERE spr_id='{$rs["spr_id"]}'";
        sql::query($sql);
        $affected += sql::affected();
        $sql = "DELETE FROM {$this->db}sk_{$this->sklad}_dvizh 
                WHERE id='{$delete}'";
        sql::query($sql);
        $affected += sql::affected();
        return $affected;
    }

    public function getRecord($edit) {
        // поставщики список для выбора
        $supply["0"] = "Новый";
        $sql = "SELECT * FROM {$this->db}sk_{$this->sklad}_postav";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $supply[$rs["id"]] = $rs["supply"];
        }
        $sql = "SELECT *,sk_{$this->sklad}_dvizh.id,sk_{$this->sklad}_postav.id AS supply_id
             FROM {$this->db}sk_{$this->sklad}_dvizh
             JOIN ({$this->db}sk_{$this->sklad}_postav,{$this->db}coments,{$this->db}sk_{$this->sklad}_spr)
             ON (sk_{$this->sklad}_postav.id=sk_{$this->sklad}_dvizh.post_id
                AND coments.id=sk_{$this->sklad}_dvizh.comment_id
                AND {$this->db}sk_{$this->sklad}_spr.id={$this->db}sk_{$this->sklad}_dvizh.spr_id)
             WHERE sk_{$this->sklad}_dvizh.id='{$edit}'";
        $rec = sqltable_model::getRecord($sql);
        $rec[supply] = $supply;
        $rec[spr_id] = $_SESSION[spr_id];
        return $rec;
    }

    public function setRecord($data) {
        extract($data);
        // отредактировано
        // найдем поставщика
        if (!empty($supply_id)) {
            $post_id = $supply_id;
        } else {
            $sql = "SELECT id FROM {$this->db}sk_{$this->sklad}_postav WHERE supply='$supply'";
            $rs = sql::fetchOne($sql);
            if (!empty($rs)) {
                $post_id = $rs[id];
            } else {
                $sql = "INSERT INTO {$this->db}sk_{$this->sklad}_postav (supply) VALUES ('$supply')";
                sql::query($sql);
                if (sql::error(true) != null)
                    return false;
                $post_id = sql::lastId();
            }
        }
        // Определим идентификатор коментария
        $sql = "SELECT id FROM {$this->db}coments WHERE comment='$comment'";
        $rs = sql::fetchOne($sql);
        if (!empty ($rs)) {
            $comment_id = $rs["id"];
        } else {
            $sql = "INSERT INTO {$this->db}coments (comment) VALUES ('$comment')";
            sql::query($sql);
            sql::error();
            $comment_id = sql::lastId();
        }
        list($numdf, $numyr) = explode("/", $numd);
        if (empty($edit)) {
            //добавление нового
            $ddate = date("Y-m-d", mktime(0, 0, 0, substr($ddate, 3, 2), substr($ddate, 0, 2), substr($ddate, 6, 4))); //$dyear."-".$dmonth."-".$dday;
            $sql = "INSERT INTO {$this->db}sk_{$this->sklad}_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price) VALUES ('$type','$numd','$numdf','$numyr','$spr_id','$quant','$ddate','$post_id','$comment_id','$price')";
            sql::query($sql);
            sql::error(true);
            $sql = "UPDATE {$this->db}sk_{$this->sklad}_ost SET ost=ost" . ($type ? "+" : "-") . abs($quant) . " WHERE spr_id='$spr_id'";
            sql::query($sql);
            sql::error(true);
        } else {
            // удалить  старое движенеи
            $sql = "SELECT * FROM {$this->db}sk_{$this->sklad}_dvizh WHERE id='$edit'";
            $rs = sql::fetchOne($sql);
            $sql = "UPDATE {$this->db}sk_{$this->sklad}_ost SET ost=ost" . ($rs["type"] ? "-" : "+") . abs($rs["quant"]) . " WHERE spr_id='" . $rs["spr_id"] . "'";
            sql::query($sql);
            $ddate = date("Y-m-d", mktime(0, 0, 0, substr($ddate, 3, 2), substr($ddate, 0, 2), substr($ddate, 6, 4))); //$dyear."-".$dmonth."-".$dday;
            $sql = "UPDATE {$this->db}sk_{$this->sklad}_dvizh SET type='$type',numd='$numd',numdf='$numdf',docyr='$numyr',spr_id='" . $rs["spr_id"] . "',quant='$quant',ddate='$ddate',post_id='$post_id',comment_id='$comment_id',price='$price' WHERE id='$edit'";
            sql::query($sql);
            $sql = "UPDATE {$this->db}sk_{$this->sklad}_ost SET ost=ost" . ($type ? "+" : "-") . abs($quant) . " WHERE spr_id='" . $rs["spr_id"] . "'";
            sql::query($sql);
        }
        return true;//sql::affected();
    }

    public function getTovar($id) {
        $sql = "SELECT * FROM {$this->db}sk_{$this->sklad}_spr WHERE id='$id'";
        return sql::fetchOne($sql);
    }

    public function getOst($id) {
        $sql = "SELECT * FROM {$this->db}sk_{$this->sklad}_ost WHERE spr_id='$id'";
        return sql::fetchOne($sql);
    }

}

?>
