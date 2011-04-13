<?php

class storage_movereport_model extends storage_rest_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $idstr = explode(':', $idstr);
        switch ($idstr[0]) {
            case 'month':
                $month = $idstr[1];
                $sql = "(SELECT  nazv,FORMAT(ost,3) as ost,edizm,
                    FORMAT(SUM(IF(type=1,quant,0)),3) as prihod,
                    FORMAT(SUM(IF(type=0,quant,0)),3) as rashod,sk_{$this->sklad}_spr.id
                    FROM {$this->db}sk_{$this->sklad}_spr
                    RIGHT JOIN {$this->db}sk_{$this->sklad}_ost
                    ON sk_{$this->sklad}_ost.spr_id=sk_{$this->sklad}_spr.id
                    RIGHT JOIN {$this->db}sk_{$this->sklad}_dvizh
                    ON sk_{$this->sklad}_dvizh.spr_id=sk_{$this->sklad}_spr.id
                    WHERE nazv<>'' AND MONTH(ddate)=(FLOOR({$month}/10000)) AND YEAR(ddate)=({$month}%10000) " .
                        (!empty($find) ? "AND nazv LIKE '%{$find}%' " : "") .
                        " GROUP BY nazv)
                    UNION (
                    SELECT  nazv,FORMAT(ost,3) as ost,edizm,
                            FORMAT(SUM(IF(type=1,quant,0)),3) as prihod,
                            FORMAT(SUM(IF(type=0,quant,0)),3) as rashod,sk_{$this->sklad}_spr.id
                    FROM {$this->db}sk_{$this->sklad}_spr
                    RIGHT JOIN {$this->db}sk_{$this->sklad}_ost
                    ON sk_{$this->sklad}_ost.spr_id=sk_{$this->sklad}_spr.id
                    RIGHT JOIN {$this->db}sk_{$this->sklad}_dvizh_arc
                    ON sk_{$this->sklad}_dvizh_arc.spr_id=sk_{$this->sklad}_spr.id
                    WHERE nazv<>'' AND MONTH(ddate)=(FLOOR({$month}/10000)) AND YEAR(ddate)=({$month}%10000) " .
                        (!empty($find) ? "AND nazv LIKE '%{$find}%' " : "") . " GROUP BY nazv) " .
                        (!empty($order) ? "ORDER BY {$order} " : "ORDER BY nazv ");
                $ret = sql::fetchAll($sql);
                return $ret;
            case 'range':
                $sdate = $idstr[1];
                $edate = $idstr[2];
                return $this->getRangePeriod($sdate, $edate);

            default : return array();
        }
    }

    public function getCols() {
        $cols = array();
        $cols[nazv] = "Наименование";
        $cols[prihod] = "Приход";
        $cols[rashod] = "Расход";
        $cols[ost] = "Остаток на сегодня";
        $cols[edizm] = "Ед.Изм.";
        return $cols;
    }

    public function getMonths() {
        $sql = "(SELECT MONTH(ddate) as dmonth, YEAR(ddate) as dyear
                FROM ({$this->db}sk_{$this->sklad}_dvizh)
                GROUP BY MONTH(ddate))
                UNION
                (SELECT MONTH(ddate) as dmonth, YEAR(ddate) as dyear
                FROM ({$this->db}sk_{$this->sklad}_dvizh_arc)
                GROUP BY YEAR(ddate),MONTH(ddate))
                ORDER BY dyear DESC, dmonth DESC";
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getRangePeriod($sdate, $edate) {
        $ret = array();
        $sdate = date("Y-m-d", mktime(0, 0, 0, substr($sdate, 3, 2), substr($sdate, 0, 2), substr($sdate, 6, 4))); //$dyear."-".$dmonth."-".$dday;
        $edate = date("Y-m-d", mktime(0, 0, 0, substr($edate, 3, 2), substr($edate, 0, 2), substr($edate, 6, 4))); //$dyear."-".$dmonth."-".$dday;
        $sql = "SELECT *,sk_{$this->sklad}_spr.id FROM {$this->db}sk_{$this->sklad}_spr
				JOIN {$this->db}sk_{$this->sklad}_ost ON sk_{$this->sklad}_ost.spr_id=sk_{$this->sklad}_spr.id
				WHERE nazv<>''
				ORDER BY nazv";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $prih = 0;
            $rash = 0;
            $sql = "SELECT SUM(quant) as prihod FROM ({$this->db}sk_{$this->sklad}_dvizh)
					JOIN {$this->db}sk_{$this->sklad}_spr ON (sk_{$this->sklad}_spr.id=sk_{$this->sklad}_dvizh.spr_id)
					WHERE ddate >= '{$sdate}'
							AND ddate <= '{$edate}'
							AND sk_{$this->sklad}_spr.id='{$rs[id]}'
							AND type='1'
							AND numd<>'9999'
					GROUP BY sk_{$this->sklad}_spr.id";
            //echo $sql;
            $res1 = sql::fetchAll($sql);
            foreach ($res1 as $rs1) {
                $prih += $rs1["prihod"];
            }
            $sql = "SELECT SUM(quant) as prihod FROM ({$this->db}sk_{$this->sklad}_dvizh_arc)
					JOIN {$this->db}sk_{$this->sklad}_spr ON (sk_{$this->sklad}_spr.id=sk_{$this->sklad}_dvizh_arc.spr_id)
					WHERE ddate >= '{$sdate}'
							AND ddate <= '{$edate}'
							AND sk_{$this->sklad}_spr.id='{$rs[id]}'
							AND type='1'
							AND numd<>'9999'
							GROUP BY sk_{$this->sklad}_spr.id";
            $res1 = sql::fetchAll($sql);
            foreach ($res1 as $rs1) {
                $prih += $rs1["prihod"];
            }
            $sql = "SELECT SUM(quant) as prihod FROM ({$this->db}sk_{$this->sklad}_dvizh)
					JOIN {$this->db}sk_{$this->sklad}_spr ON (sk_{$this->sklad}_spr.id=sk_{$this->sklad}_dvizh.spr_id)
					WHERE ddate >= '{$sdate}'
							AND ddate <= '{$edate}'
							AND sk_{$this->sklad}_spr.id='{$rs[id]}'
							AND type='0'
							AND numd<>'9999'
					GROUP BY sk_{$this->sklad}_spr.id";
            $rs1 = sql::fetchOne($sql);
            if (!empty($rs1)) {
                $rash += $rs1["prihod"];
            }
            $sql = "SELECT SUM(quant) as prihod FROM ({$this->db}sk_{$this->sklad}_dvizh_arc)
					JOIN {$this->db}sk_{$this->sklad}_spr ON (sk_{$this->sklad}_spr.id=sk_{$this->sklad}_dvizh_arc.spr_id)
					WHERE ddate >= '{$sdate}'
							AND ddate <= '{$edate}'
							AND sk_{$this->sklad}_spr.id='{$rs[id]}'
							AND type='0'
							AND numd<>'9999'
					GROUP BY sk_{$this->sklad}_spr.id";
            $res1 = sql::fetchAll($sql);
            foreach ($res1 as $rs1) {
                $rash += $rs1["prihod"];
            }
            if (!empty($prih) || !empty($rash)) {
                $cols[nazv] = $rs[nazv];
                $cols[prihod] = sprintf("%10.2f", $prih);
                $cols[rashod] = sprintf("%10.2f", $rash);
                $cols[ost] = sprintf("%10.2f", $rs["ost"]);
                $cols[edizm] = $rs[edizm];
                $ret[] = $cols;
            }
        }
        return $ret;
    }

}

?>
