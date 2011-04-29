<?php

/**
 * Description of orders_blocks_view
 *
 * @author igor
 */
class orders_blocks_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        $fields = array();
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "blockname",
            "label" => "Наименование блока",
            "value" => $rec[blockname],
            "options" => array( "readonly" => true ),
        ));

        $rec[fields] = $fields;
        return parent::showrec($rec);
    }

}

?>
