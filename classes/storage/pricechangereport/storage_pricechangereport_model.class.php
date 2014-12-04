<?php

/**
 * Модель для отчета по изменению цен
 *
 * @author Игорь
 */
class storage_pricechangereport_model extends storage_movereport_model {

    /*
     * public function getData($all=false, $order='', $find='', $idstr='')
     * 
     * Будет вызываться родительская потому что там в зависимости от запрошенных
     *  данных вызывается getRangePeriod
     */
    
    /**
     * В этом классе другие колонки в отличие от того отчета
     */
    public function getCols() {
        $cols = array();
        $cols[nazv] = "Наименование";
        $cols[oldprice] = "Старая цена";
        $cols[newprice] = "Новая цена";
        $cols[ratio] = "Процентное изменение";
        return $cols;
    }

    /**
     * Получет две даты и выдает данные за период
     */
    public function getRangePeriod($sdate, $edate) {
        $ret = array();
        //console::getInstance()->out("$sdate nnn $edate");
        $sdate = date("Y-m-d", mktime(0, 0, 0, substr($sdate, 3, 2), substr($sdate, 0, 2), substr($sdate, 6, 4))); //$dyear."-".$dmonth."-".$dday;
        $edate = date("Y-m-d", mktime(0, 0, 0, substr($edate, 3, 2), substr($edate, 0, 2), substr($edate, 6, 4))); //$dyear."-".$dmonth."-".$dday;
        
        // выбор всех непустых названий
        $sql = "SELECT *,sk_{$this->sklad}_spr.id FROM {$this->db}sk_{$this->sklad}_spr
				JOIN {$this->db}sk_{$this->sklad}_ost ON sk_{$this->sklad}_ost.spr_id=sk_{$this->sklad}_spr.id
				WHERE nazv<>''
				ORDER BY nazv";
        $res = sql::fetchAll($sql);
        
        foreach ($res as $rs) {
            
            // Запрос тот же что и для прихода, но берем последний и смотрим только последний и его цену
            $sql = "SELECT price as newprice FROM ({$this->db}sk_{$this->sklad}_dvizh)
					JOIN {$this->db}sk_{$this->sklad}_spr ON (sk_{$this->sklad}_spr.id=sk_{$this->sklad}_dvizh.spr_id)
					WHERE ddate >= '{$sdate}'
							AND ddate <= '{$edate}'
							AND sk_{$this->sklad}_spr.id='{$rs[id]}'
							AND type='1'
							AND numd<>'9999'
					ORDER BY ddate DESC LIMIT 1";
            //echo $sql;
            $res1 = sql::fetchOne($sql);
            $newprice = $res1["newprice"];
            
            // почти такой же запрос, толь до первой даты даст старую цену
            $sql = "SELECT price as oldprice FROM ({$this->db}sk_{$this->sklad}_dvizh)
					JOIN {$this->db}sk_{$this->sklad}_spr ON (sk_{$this->sklad}_spr.id=sk_{$this->sklad}_dvizh.spr_id)
					WHERE ddate < '{$sdate}'
							AND sk_{$this->sklad}_spr.id='{$rs[id]}'
							AND type='1'
							AND numd<>'9999'
					ORDER BY ddate DESC LIMIT 1";
            //echo $sql;
            $res1 = sql::fetchOne($sql);
            $oldprice = $res1["oldprice"];
            
            // если цены отичаются добавим в вывод
            if ( $newprice!=$oldprice && $oldprice!=0 && $newprice!=0 ) { // проверка на ноль, чтобы не делить на него при вычислении соотношения
                $col[nazv] = $rs[nazv];
                $col[newprice] = sprintf("%10.2f", $newprice);
                $col[oldprice] = sprintf("%10.2f", $oldprice);
                $col[ratio] = sprintf("%+10.2f", ($newprice-$oldprice)*100/$oldprice );
                $ret[] = $col;
            }
        }
        return $ret;
    }
  
}
