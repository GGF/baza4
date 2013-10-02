<?php

/*
 * model class
 */

class orders_blocks_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'blocks';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $order = strstr($order, 'files') ? '' : $order; // не удается отсортировать по файлам
        if (empty($_SESSION[customer_id])) {
            $customer = "Выберите заказчика!!!";
            $sql = "SELECT *, CONCAT(blocks.sizex,'x',blocks.sizey) AS size, 
                    blocks.id AS blockid,blocks.id
                    FROM blocks
                    JOIN (customers,boards,blockpos)
                    ON (customers.id=blocks.customer_id AND blockpos.block_id=blocks.id AND blockpos.board_id=boards.id ) " .
                    (!empty($find) ? "WHERE blockname LIKE '%{$find}%' OR board_name LIKE '%{$find}%' " : "") .
                    " GROUP BY blockid " .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY blockname  DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        } else {
            $cusid = $_SESSION[customer_id];
            $customer = $_SESSION[customer];
            // sql
            $sql = "SELECT *, CONCAT(blocks.sizex,'x',blocks.sizey) AS size, 
                    blocks.id AS blockid,blocks.id
                    FROM blocks
                    JOIN (customers,boards,blockpos) 
                    ON (customers.id=blocks.customer_id AND blockpos.block_id=blocks.id AND blockpos.board_id=boards.id ) " .
                    (!empty($find) ? "WHERE (blockname LIKE '%{$find}%'  OR board_name LIKE '%{$find}%') AND customers.id='{$_SESSION[customer_id]}' " : " WHERE customers.id='{$_SESSION[customer_id]}' ") .
                    " GROUP BY blockid " .
                    (!empty($order) ? "ORDER BY {$order} " : "ORDER BY blockname DESC ") .
                    ($all ? "LIMIT 50" : "LIMIT 20");
        }
        $ret = sql::fetchAll($sql);
        foreach ($ret as &$value) {
            $files = $this->getFilesForId($this->maintable, $value[blockid]);
            $value[files] = $files[link];
        }
        return $ret;
    }

    public function getCols() {
        $cols = array();
        if (empty($_SESSION[customer_id])) {
            $cols[customer] = "Заказчик";
        }
        $cols[blockid] = "ID";
        $cols[blockname] = "Название блока";
        $cols[size] = "Размер";
        $cols[scomp] = 'COMP';
        $cols[ssolder] = 'SOLDER';
        $cols[auarea] = 'Au';
        $cols[smalldrill] = '<=0.6';
        $cols[bigdrill] = '>0.6';
        $cols[files] = 'Файлы';
        return $cols;
    }

    public function delete($delete) {
        $sql = "DELETE FROM blocks WHERE id='{$delete}'";
        sql::query($sql);
        $sql = "DELETE FROM blockpos WHERE block_id='{$delete}'";
        sql::query($sql);
        return sql::affected();
    }

    public function getRecord($edit) {
        $rec = parent::getRecord($edit);
        $rec[customer] = $this->getCustomer($rec[customer_id]);
        $rec[customer] = $rec[customer][customer];
        $sql = "SELECT * 
                FROM blockpos 
                JOIN boards ON boards.id=blockpos.board_id 
                WHERE blockpos.block_id='{$edit}'";
        $rec[blockpos] = sql::fetchAll($sql);
        $param = json_decode($this->getComment($rec[comment_id]),true);
        $rec["comment"] = $param["coment"];
        $wideandgaps = $param["wideandgaps"];
        // если слои еще не заполнены заполним из wideandgaps
        for($i=1;$i<11;$i++) {
            $sl1=$wideandgaps[2*$i-2][0];$sl2=$wideandgaps[2*$i-1][0];
            $pr1=$wideandgaps[2*$i-2][2];$pr2=$wideandgaps[2*$i-1][2];
            if(empty($param["sl{$i}"])){
                if(!empty($sl1)) {
                    $param["sl{$i}"] = $sl1."-".$sl2;
                    $param["pr{$i}"] = sprintf("%5.3f/%5.3f",$pr1,$pr2);
                }
            }
        }
        $rec["param"] = $param;
        $rec[files] = $this->getFilesForId('blocks', $edit);
        return $rec;
    }

    public function setRecord($data) {
        extract($data);
        // в скрытых параметрах формы есть идентификатор коментария, заберем текущий и заменим в нем собственно коментарий
        $param = json_decode($this->getComment($comment_id),true);
        $param[basemat]=$data["basemat"];
        $param[sttkan]=$data["sttkan"];
        $param[sttkankl]=$data["sttkankl"];
        $param[sttkanrasp]=$data["sttkanrasp"];
        $param[rtolsh]=$data["rtolsh"];
        $param[filext]=$data["filext"];
        $param[elkon]=$data["elkon"];
        for($i=1;$i<11;$i++) {
            $param["sl{$i}"]=$data["sl{$i}"];
            $param["pr{$i}"]=$data["pr{$i}"];
            $param["mat{$i}"]=$data["mat{$i}"];
        }
        $param["coment"] = $comment;
        $comment_id = $this->getCommentId(json_encode($param));
        $sql = "UPDATE blocks SET comment_id='{$comment_id}' WHERE id='{$edit}'";
        sql::query($sql);
        return parent::setRecord($data);
    }

}

?>
