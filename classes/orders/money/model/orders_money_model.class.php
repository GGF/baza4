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
        $sql = "SELECT *,
                    SUM(matcost) AS summatcost,
                    SUM(matras) AS summatras,
                    SUM(trudcost) AS sumtrudcost,
                    SUM(trudem) AS sumtrudem
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
        $cols[matedizm] = "ед.изм.";
        $cols[summatras] = "Расход";
        $cols[summatcost] = "Стоимость";
        $cols[trud] = "Операция";
        $cols[sumtrudem] = "Трудоемкость";
        $cols[sumtrudcost] = "Стоимость";
        return $cols;
    }

}

?>
