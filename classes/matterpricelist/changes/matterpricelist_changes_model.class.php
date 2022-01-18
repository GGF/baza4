<?php

/*
 * model class
 */

class matterpricelist_changes_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'calc__matter_pricelist';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = parent::getData($all, $order, $find, $idstr);
        if (!empty($_SESSION[Auth::$lss])) {
            extract($_SESSION[Auth::$lss]);
        }
        // найдем имя материала сначала, потому что идентификато показывает только последнее изменение
        $sql = "SELECT `matter_name` 
                    FROM calc__matter_pricelist 
                    JOIN calc__matters 
                    ON calc__matter_pricelist.matter_name_id = calc__matters.id 
                    WHERE calc__matter_pricelist.id = '{$matter_id}'";
        $res = sql::fetchOne($sql);

        // а тперь весь список изменений
        $sql = "SELECT calc__matter_pricelist.id AS id,`record_date`,`type`,`matter_name`,`matter_unit`,`matter_price`,`invoice`,`supplier_name`,`change_act`,`coment`
                FROM calc__matter_pricelist
                JOIN (calc__types,calc__matters,calc__suppliers)
                ON (calc__matter_pricelist.matter_type_id = calc__types.id 
                    AND calc__matter_pricelist.matter_name_id = calc__matters.id
                    AND calc__matter_pricelist.supplier_id = calc__suppliers.id
                    )
                " .
                " WHERE matter_name = '{$res['matter_name']}' " .
                (!empty($find) ? "AND (matter_name LIKE '%{$find}%') OR (invoice LIKE '%{$find}%') OR (change_act LIKE '%{$find}%') OR (supplier_name LIKE '%{$find}%')" : " ") .
                (!empty($order) ? " ORDER BY {$order} " : " ORDER BY type,matter_name DESC ") .
                ($all ? "" : "LIMIT 20");

        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols['id'] = 'ID';
        $cols['record_date'] = 'Дата';
        $cols['type'] = 'Тип';
        $cols['matter_name'] = 'Материал';
        $cols['matter_unit'] = 'ед.изм.';
        $cols['matter_price'] = 'Цена';
        $cols['invoice'] = 'Накладная/Приходный ордер';
        $cols['supplier_name'] = 'Постащик';
        $cols['change_act'] = 'Акт';
        $cols['coment'] = 'Коментарий';
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM calc__matter_pricelist WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }
}

?>
