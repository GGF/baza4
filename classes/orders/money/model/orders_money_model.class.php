<?php

/*
 * model class
 */

class orders_money_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'moneyfororder';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = parent::getData($all, $order, $find, $idstr);
        $sql = "SELECT * 
                    FROM moneyfororder " .
                (!empty($find) ? "WHERE (`customer` LIKE '%{$find}%' OR `order` LIKE '%{$find}%' ) " : "") .
                        "GROUP BY `customer`, `order`,`mater`,`trud` ".
                (!empty($order) ? "ORDER BY {$order} " : "ORDER BY customer DESC ") .
                ($all ? "LIMIT 500" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[customer] = "Заказчик";
        $cols[order] = "Заказ";
        $cols[mater] = "Материал";
        $cols[matras] = "Расход";
        $cols[matcost] = "Стоимость";
        $cols[trud] = "Операция";
        $cols[trudem] = "Трудоемкость";
        $cols[trudcost] = "Стоимость";
        return $cols;
    }

}

?>
