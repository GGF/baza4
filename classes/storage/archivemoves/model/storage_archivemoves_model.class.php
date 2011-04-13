<?php

class storage_archivemoves_model extends storage_rest_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $spr_id = $_SESSION[arc_spr_id];
        $sql = "SELECT *,sk_arc_{$this->sklad}_dvizh.id
                FROM {$this->db}sk_arc_{$this->sklad}_dvizh
                JOIN ({$this->db}sk_{$this->sklad}_postav,{$this->db}coments)
                ON (sk_{$this->sklad}_postav.id=sk_arc_{$this->sklad}_dvizh.post_id
                    AND coments.id=sk_arc_{$this->sklad}_dvizh.comment_id)
                WHERE spr_id='{$spr_id}'" .
                (!empty($find) ? " AND comment LIKE '%{$find}%' OR supply LIKE '%{$find}%'
                    OR numd LIKE '%{$find}%'" : "") .
                (!empty($order) ? "ORDER BY {$order} " : "ORDER BY ddate DESC ") .
                ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[ddate] = "Дата";
        $cols[prras] = "+/-";
        $cols[numd] = "№ док.";
        $cols[supply] = "Поставщик";
        $cols[quant] = "Кол-во";
        $cols[comment] = "Примечание";
        $cols[price] = "Цена";
        return $cols;
    }

    public function getTovar($id) {
        $sql = "SELECT * FROM {$this->db}sk_arc_{$this->sklad}_spr WHERE id='$id'";
        return sql::fetchOne($sql);
    }

    public function getOst($id) {
        $sql = "SELECT * FROM {$this->db}sk_arc_{$this->sklad}_ost WHERE spr_id='$id'";
        return sql::fetchOne($sql);
    }

}

?>
