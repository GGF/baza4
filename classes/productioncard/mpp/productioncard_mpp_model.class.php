<?php

class productioncard_mpp_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'move_in_production';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT * FROM move_in_production GROUP BY lanch_id " .
                ($all ? "LIMIT 50" : "LIMIT 20");
        $res = sql::fetchAll($sql);
        foreach ($res as $val) {
            $lid = $val[lanch_id];
            $ret[$lid][slnumber] = $lid;
            $sql = "SELECT * FROM move_in_production WHERE lanch_id='{$lid}' ORDER BY operation_id";
            $res1 = sql::fetchAll($sql);
            foreach ($res1 as $val1) {
                $ret[$lid]["oper{$val1[operation_id]}"] = $val1["action_date"];
                $sql = "SELECT * FROM coments WHERE id='{$val1["coment_id"]}'";
                $coment = sql::fetchOne($sql);
                $coment = $coment["comment"];
                $ret[$lid]["oper{$val1[operation_id]}"] .= "<br>{$coment}";
            }
            $sql = "SELECT * " .
                    "FROM lanch " .
                    "JOIN (blocks,customers,coments) " .
                    "ON ( " .
                    "lanch.block_id=blocks.id " .
                    "AND blocks.customer_id=customers.id " .
                    "AND lanch.comment_id=coments.id " .
                    ") " .
                    " WHERE lanch.id = '{$lid}' ";
            $res1 = sql::fetchAll($sql);
            foreach ($res1[0] as $key => $val1) {
                $ret[$lid][$key] = $val1; 
            }
            //$ret[$lid][released] = print_r($res1,true);
        }
        return $ret;
    }

    public function getCols() {
        $sql = "SELECT * FROM operations WHERE block_type='mpp' OR block_type='both' ORDER BY id";
        $cols = array();
        $cols[released] = array("V","Готово");
        $cols[customer] = "Заказчик";
        $cols[order] = "Заказ";
        $cols[slnumber] = "# СЛ";
        $cols[blockname] = "Плата";
        $cols[ldate] = "Дата запуска";
        $cols[numbz] = "Заготовок";
        $cols[numbp] = "Плат";
        $res = sql::fetchAll($sql);
        foreach ($res as $key => $value) {
            //$cols["oper{$value[id]}"] = "<span id='verticalText'>{$value[operation]}</span>";
            $cols["oper{$value[id]}"] = $value[operation];
        }
        $cols[comment] = "Примечание";
        $cols[put] = "Выдача";
        $cols[put1] = "Выдача1";
        return $cols;
    }

    public function delete($id) {
//        $sql = "DELETE FROM masterplate WHERE id='{$id}'";
//	sql::query($sql);
//        return sql::affected();
    }

}

?>
