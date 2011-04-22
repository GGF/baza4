<?php

/**
 * Description of storage_request_model
 *
 * @author igor
 */
class storage_request_model extends storage_rest_model {

    public function getData($all = false, $order = '', $find = '', $idstr = '') {
        $ddate = $idstr;
        $sql = "SELECT CONCAT('<input type=checkbox value=',sk_{$this->sklad}_spr.id,' name=id[',sk_{$this->sklad}_spr.id,'] class=check-me >')
                AS `check`,nazv,FORMAT(SUM(quant),3) as rashod,ost,ddate,edizm,{$this->db}sk_{$this->sklad}_spr.id
                FROM {$this->db}sk_{$this->sklad}_spr
                JOIN ({$this->db}sk_{$this->sklad}_dvizh,{$this->db}sk_{$this->sklad}_ost)
                ON (sk_{$this->sklad}_ost.spr_id=sk_{$this->sklad}_spr.id
                    AND sk_{$this->sklad}_dvizh.spr_id=sk_{$this->sklad}_spr.id)
                WHERE type='0' AND ddate='{$ddate}' AND numd<>'9999' " .
                (!empty($find) ? "AND nazv LIKE '%{$find}%' " : "") .
                " GROUP BY nazv " .
                (!empty($order) ? "ORDER BY {$order} " : "ORDER BY nazv ");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols[check] = "<input type=checkbox id='ucuc' onclick=\"if ($('#ucuc').attr('checked')) $('.check-me').attr({checked:true}); else $('.check-me').attr({checked:false});\">";
        $cols[nazv] = "Наименование";
        $cols[rashod] = "Расход";
        $cols[ost] = "Остаток на сегодня";
        $cols[edizm] = "Ед.Изм.";
        return $cols;
    }

    public function getDates() {
        $sql = "SELECT ddate
                FROM ({$this->db}sk_{$this->sklad}_dvizh)
                WHERE type='0' AND numd<>'9999'
                GROUP BY ddate
                ORDER BY ddate DESC";
        return sql::fetchAll($sql);
    }

    public function getRecord($edit) {
        extract($edit);
        foreach ($id as $key => $value) {
            $sql = "SELECT *,sk_{$this->sklad}_spr.id
                FROM {$this->db}sk_{$this->sklad}_dvizh
                JOIN {$this->db}sk_{$this->sklad}_spr
                ON sk_{$this->sklad}_dvizh.spr_id=sk_{$this->sklad}_spr.id
                WHERE ddate='{$ddate}' AND {$this->db}sk_{$this->sklad}_spr.id='{$value}'";
            $ret[] = sql::fetchOne($sql);
        }
        return $ret;
    }

}

?>
