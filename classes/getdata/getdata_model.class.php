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
}

?>