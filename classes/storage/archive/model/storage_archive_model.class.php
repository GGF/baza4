<?php

class storage_archive_model extends storage_rest_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT *,if((krost>ost),'<span style=\"color:red\"><b>мало</b></span>','') as malo,sk_arc_{$this->sklad}_spr.id
                FROM {$this->db}`sk_arc_{$this->sklad}_spr`
                JOIN {$this->db}sk_arc_{$this->sklad}_ost
                ON sk_arc_{$this->sklad}_ost.spr_id=sk_arc_{$this->sklad}_spr.id
                WHERE nazv!='' " .
                (!empty($find) ? " AND nazv LIKE '%{$find}%' " : "") .
                (!empty($order) ? "ORDER BY {$order} " : "ORDER BY nazv ") .
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


}

?>
