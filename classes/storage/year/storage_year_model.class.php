<?php

/**
 * Description of storage_request_model
 *
 * @author igor
 */
class storage_year_model extends storage_model {

    public function arc() {
        // годовая архивация
        // перенести движения
        $sql = "INSERT INTO {$this->db}sk_{$this->sklad}_dvizh_arc
                (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price)
                SELECT sk_{$this->sklad}_dvizh.type,sk_{$this->sklad}_dvizh.numd,sk_{$this->sklad}_dvizh.numdf,sk_{$this->sklad}_dvizh.docyr,sk_{$this->sklad}_dvizh.spr_id,sk_{$this->sklad}_dvizh.quant,sk_{$this->sklad}_dvizh.ddate,sk_{$this->sklad}_dvizh.post_id,sk_{$this->sklad}_dvizh.comment_id,sk_{$this->sklad}_dvizh.price
                FROM {$this->db}sk_{$this->sklad}_dvizh";
        if (!sql::query($sql))
            return false;

        // очистить движения
        $sql = "TRUNCATE TABLE {$this->db}sk_{$this->sklad}_dvizh";
        if (!sql::query($sql))
            return false;


        $sql = "SELECT * FROM {$this->db}sk_{$this->sklad}_spr";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $id = $rs["id"];
            // получить остатки
            $sql = "SELECT * FROM {$this->db}sk_{$this->sklad}_ost WHERE sk_{$this->sklad}_ost.spr_id='$id'";
            $ost = mysql_fetch_array(mysql_query($sql));
            $ost = $ost["ost"];
            // создать архивное движение
            // поставщик
            $sql = "SELECT id FROM {$this->db}sk_{$this->sklad}_postav WHERE supply=''";
            $rs1 = sql::fetchOne($sql);
            if (!empty($rs1)) {
                $post_id = $rs1["id"];
            }
            // коментарий
            $sql = "SELECT id FROM {$this->db}coments WHERE comment='Передача остатка'";
            $rs1 = sql::fetchOne($sql);
            if (!empty($rs1)) {
                $comment_id = $rs1["id"];
            } else {
                $sql = "INSERT INTO {$this->db}coments (comment) VALUES ('Передача остатка')";
                sql::query($sql) or die(sql::error(true));
                $comment_id = sql::lastId();
            }
            $numd = "9999";
            $numdf = "9999";
            $docyr = date("Y") - 1;
            $ddate = date("Y-m-d", mktime(0, 0, 0, 12, 31, $docyr));
            $sql = "INSERT INTO {$this->db}sk_{$this->sklad}_dvizh_arc (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price)
                    VALUES ('0','$numd','$numdf','$docyr','$id','$ost','$ddate','$post_id','$comment_id','0')";
            echo $sql . "<br>";
            if (!sql::query($sql))
                return false;
            // создадим первое движение года
            // коментарий
            $sql = "SELECT id FROM {$this->db}coments WHERE comment='Остаток на 31.12.$docyr'";
            $rs1 = sql::fetchOne($sql);
            if (!empty($rs1)) {
                $comment_id = $rs1["id"];
            } else {
                $sql = "INSERT INTO {$this->db}coments (comment) VALUES ('Остаток на 31.12.$docyr')";
                sql::query($sql) or die(sql::error(true));
                $comment_id = sql::lastId();
            }
            $docyr = date("Y");
            $ddate = date("Y-m-d", mktime(0, 0, 0, 1, 1, $docyr));
            $sql = "INSERT INTO {$this->db}sk_{$this->sklad}_dvizh (type,numd,numdf,docyr,spr_id,quant,ddate,post_id,comment_id,price)
                    VALUES ('1','$numd','$numdf','$docyr','$id','$ost','$ddate','$post_id','$comment_id','0')";
            if (!sql::query($sql))
                return false;
        }
        return true;
    }

}

?>
