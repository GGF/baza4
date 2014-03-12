<?php


/**
 * Description of orders_posintz_view
 *
 * @author igor
 */
class orders_posintz_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }
    
    public function showbutton($rec) {
        extract($rec);
        if (empty($rasslink)) {
            Output::assign('createlink', $createlink);
            return $this->fetch('createbutton.tpl');
        } else {
            Output::assign('rasslink', fileserver::sharefilelink($rasslink));
            return $this->fetch('rasslink.tpl');
        }
        
    }
    
    public function showrec($rec) {
        extract($rec);
        $fields = array();
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_HIDDEN,
            "name" => "tz_id",
            "value" => $rec["tz_id"],
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_SELECT,
            "name" => "block_id",
            "label" => "Плата:",
            "values" => $rec["blocks"],
            "value" => '',
            "options" => array("html" => " boardid=1 "),
        ));
        array_push($fields, array(
            "type" => AJAXFORM_TYPE_TEXT,
            "name" => "board_num",
            "label" => "Плат",
            "value" => '',
            "obligatory"    =>  true,
                //"options"	=>	array( "html" => "size=10", ),
        ));        
        $rec["fields"] = $fields;

        return parent::showrec($rec);
    }

    public function getRasschet($rec) {
        extract($rec);
        $filelink = fileserver::createdironserver($filename);
        $excel = file_get_contents($this->getDir() . "/" . $template );
        if (fileserver::savefile($filelink.".txt", $rec)) {
            // сохранить 
            if (fileserver::savefile($filelink, $excel)) {
                Output::assign('rlink', fileserver::sharefilelink($filelink));
                $out = $this->fetch('rlink.tpl');
                $res[filename] = $filename;
                $res[result] = true;
            } else {
                $out = "Не удалось записать файл xls";
                $res[result] = false;
            }
        } else {
            $out = "Не удалось создать файл txt";
            $res[result] = false;
        }
        $res[out] = $out;
        return $res;
    }
}

?>
