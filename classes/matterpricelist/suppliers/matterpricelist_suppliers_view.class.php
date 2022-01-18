<?php

/**
 * Description of matterpricelist_suppiers_view
 *
 * @author igor
 */
class matterpricelist_suppliers_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        extract($rec);
        $fields = array();
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "supplier_shortname",
            "label" => "Nick:",
            "value" => $rec["supplier_shortname"],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "supplier_name",
            "label" => "Полное имя:",
            "value" => $rec["supplier_name"],
        ));
        $rec['fields'] = $fields;
        return parent::showrec($rec);
    }

}

?>
