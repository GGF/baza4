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

    /**
     * В остальных удаляет выбранную запись, а здесь чистит всю таблице, потому 
     * как весь этот класс используется для переноса в таблицы эксель и проще 
     * было там написать функции, которые забирают всё и загонять данные по 
     * каждому заказу. Ну будет там 50-70 позиций - займет загнать минут 10.
     * @param type $delete
     * @return bool
     */
    public function delete($delete) {
        $sql = "TRUNCATE TABLE `moneyfororder`";
        return sql::query($sql);
    }
    
    /**
     * Вместо редактирования я просто сделаю у всех записей одинаковые заказчика
     *  и заказ. Редактировать тут нечего, а изза ручного заполнения параметров 
     * они получаются разные и н сгруппировать в выборке.
     * @param type $edit
     */
    public function getRecord($edit) {
        $sql="SELECT `customer`, `order` FROM `moneyfororder` LIMIT 1";
        $rs = sql::fetchOne($sql);
        $sql = "UPDATE `moneyfororder` SET `customer`='{$rs["customer"]}',`order`='{$rs["order"]}'";
        sql::query($sql);
        return true; 
    }
    
}

?>
