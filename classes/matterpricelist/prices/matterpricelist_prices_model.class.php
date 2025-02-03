<?php

/*
 * model class
 */

class matterpricelist_prices_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'calc__matter_pricelist';
    }

    private function getMatters($id='') {
        $sql = "SELECT id,matter_name FROM calc__matters " .
                "ORDER BY matter_name";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $matters[$rs['id']] = $rs['matter_name'];
        }
        return $matters;
    }

    private function getMatterSuppliers($id='') {
        $sql = "SELECT id,supplier_shortname FROM calc__suppliers " .
                "ORDER BY supplier_shortname";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $suppliers[$rs['id']] = $rs['supplier_shortname'];
        }
        return $suppliers;
    }

    private function getMatterTypes($id='') {
        $sql = "SELECT id,`type` FROM calc__types " .
                "ORDER BY `type`";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $types[$rs['id']] = $rs['type'];
        }
        return $types;
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = parent::getData($all, $order, $find, $idstr);
        if (!empty($_SESSION[Auth::$lss])) {
            extract($_SESSION[Auth::$lss]);
        }

        $sql = "SELECT calc__matter_pricelist.id AS id,`type`,`matter_name`,`matter_unit`,`matter_price`,`invoice`,`supplier_name`,`change_act`,`coment`,`discharge_norm_in`,`discharge_norm_out`
                FROM calc__matter_pricelist
                JOIN (calc__types,calc__matters,calc__suppliers)
                ON (calc__matter_pricelist.matter_type_id = calc__types.id 
                    AND calc__matter_pricelist.matter_name_id = calc__matters.id
                    AND calc__matter_pricelist.supplier_id = calc__suppliers.id
                    )
                " .
                " WHERE record_date IN (SELECT MAX(record_date) FROM calc__matter_pricelist GROUP BY matter_name_id) " .
                (!empty($find) ? "AND (matter_name LIKE '%{$find}%') OR (invoice LIKE '%{$find}%') OR (change_act LIKE '%{$find}%') OR (supplier_name LIKE '%{$find}%')" : " ") .
                (!empty($order) ? " ORDER BY {$order} " : " ORDER BY type,matter_name DESC ") .
                ($all ? "" : "LIMIT 20");

        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols['type'] = 'Тип';
        $cols['matter_name'] = 'Материал';
        $cols['matter_unit'] = 'ед.изм.';
        $cols['discharge_norm_in'] = 'вн';
        $cols['discharge_norm_out'] = 'нар';
        $cols['matter_price'] = 'Цена';
        $cols['invoice'] = 'Накладная/Приходный ордер';
        $cols['supplier_name'] = 'Постащик';
        $cols['change_act'] = 'Акт';
        $cols['coment'] = 'Коментарий';
        return $cols;
    }

    public function getRecord($id)
    {
        $rec = parent::getRecord($id);
        $rec['types'] = $this->getMatterTypes();
        $rec['type'] = $rec['types'][$rec['matter_type_id']];
        $rec['suppliers'] = $this->getMatterSuppliers();
        $rec['supplier'] = $rec['suppliers'][$rec['supplier_id']];
        $rec['matters'] = $this->getMatters();
        $rec['matter'] = $rec['matters'][$rec['matter_name_id']];
        return $rec;
    }
    public function delete($id) {
        $sql = "DELETE FROM calc__matter_pricelist WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }
}

?>
