<?php

/**
 * Description of update_model
 *
 * @author igor
 */
class getdata_model extends sqltable_model {

    public function block($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        $out = '';
        $sql = "SELECT * FROM blocks WHERE blockname='{$blockname}' ORDER BY id DESC";
        $res = sql::fetchOne($sql);
        $sql = "SELECT * 
                FROM blockpos 
                JOIN boards ON boards.id=blockpos.board_id 
                WHERE blockpos.block_id='{$res["id"]}'";
        $res[blockpos] = sql::fetchAll($sql);
        $rec[blockcomment] = $this->getComment($rec[comment_id]);
        $rec[boardcomment] = $this->getComment($rec[blockpos][0][comment_id]);
        $out .= json_encode($res);
        return $out;
    }

    public function textolite($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        $out = '';
        $sql = "SELECT * FROM `zaomppsklads`.`sk_mat__spr` ORDER BY nazv";
        $res[textolite] = sql::fetchAll($sql);
        $out .= json_encode($res,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
        return $out;
    }
    
    /**
     * Возвращает данные по сопроводительному листу
     * @param array $rec массив $REQUEST а в нем нас интересует параметр slid
     * @return string html код с информацией
     */
    public function checksl($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        if ( 1*$slid != 0 ) {
            $lanch = new sqltable;
            $lanch = new lanch_zap;
            $out = $lanch->getAllHeaderStylesheets();
            $out .= $lanch->getAllHeaderJavascripts();
            $out .= $lanch->action_open($slid);
        } else {
            $out = 'Не найден сопровеодительный лист';
        }
        return $out;
    }

}

?>
