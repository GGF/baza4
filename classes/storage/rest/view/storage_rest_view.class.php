<?php

class storage_rest_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $rec[fields] = array();
        //console::getInstance()->out(print_r($rec));
        array_push($rec[fields],
			array(
				"type"		=> AJAXFORM_TYPE_TEXT,
				"name"		=> "nazv",
				"label"			=>'Наименование:',
				"value"		=> $rec["nazv"],
				"options"	=>	array( "html" => "size=70", ),
			),
			array(
				"type"		=> AJAXFORM_TYPE_TEXT,
				"name"		=> "edizm",
				"label"			=>'Единица измерения:',
				"value"		=> $rec["edizm"],
				"options"	=>	array( "html" => "size=10", ),
			),
			array(
				"type"		=> AJAXFORM_TYPE_TEXT,
				"name"		=> "krost",
				"label"			=>'Критический остаток:',
				"value"		=> $rec["krost"],
				"options"	=>	array( "html" => "size=10", ),
			)
        );
        return parent::showrec($rec);
    }

}

?>
