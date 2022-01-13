<?php

/**
 * Description of orders_blocks_view
 *
 * @author igor
 */
class matterpricelist_types_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        extract($rec);
        $fields = array();
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "type",
            "label" => "Тип:",
            "value" => $rec["type"],
        ));
        $rec['fields'] = $fields;
        return parent::showrec($rec);
    }

}

?>
