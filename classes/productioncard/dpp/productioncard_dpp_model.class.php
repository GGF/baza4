<?php

class productioncard_dpp_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'move_in_production';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        //$res = parent::getData($all, $order, $find, $idstr);
        $sql = "SELECT *,{$this->maintable}.id FROM {$this->maintable} "
        . "JOIN (lanch, blocks, customers) "
                . "ON lanch.id=lanch_id AND blocks.id=lanch.block_id AND customers.id=blocks.customer_id " .
                    (!empty($find) ? "WHERE (lanch_id LIKE '%{$find}%' OR blockname LIKE '%{$find}%' OR customer LIKE '%{$find}%') " 
                                        . ($all?"":" AND lastoperation<>'-1' ")
                            : " WHERE lastoperation<>'-1' ") .
                    (!empty($order) ? " ORDER BY {$order} " : " ORDER BY {$this->maintable}.id DESC ") .
                    ($all ? "" : "LIMIT 20");;
        $res = sql::fetchAll($sql);
        foreach ($res as &$val) {
            $coment = multibyte::Json_decode(sqltable_model::getComment($val[coment_id]));
            foreach ($coment as $key => $value) {
                $val["oper{$key}"] = $value[date];
            }
        }
        return $res;
    }

    public function getCols() {
        $cols = array();
        $cols[lanch_id] = array("ID","Номер сопроводительного листа");
        $cols[customer] = array("Заказчик","Заказчик");
        $cols[blockname] = array("Блок","Номер блока");
        $sql = "SELECT * FROM operations WHERE block_type='dpp' OR block_type='both' ORDER BY priority,id";
        $res = sql::fetchAll($sql);
        foreach ($res as $key => $value) {
            //$cols["oper{$value[id]}"] = "<span id='verticalText'>{$value[operation]}</span>";
            $cols["oper{$value[id]}"] = array(short => $value[shortname],title => $value[operation], nosort=>true);
        }
        return $cols;
    }

    public function delete($id) {
        $sql = "UPDATE {$this->maintable} SET lastoperation='-1', action_date=NOW() WHERE id='{$id}'";
	sql::query($sql);
        return sql::affected();
    }
    
    public function getRecord($edit) {
        $rec = parent::getRecord($edit);
        $sql = "SELECT id,operation FROM operations WHERE block_type='dpp' OR block_type='both' ORDER BY priority,id";
        $res = sql::fetchAll($sql);
        foreach ($res as $value) {
                $rec[operations][$value[id]] = $value[operation];
        }
        $operation = array_shift($res);
        $operations = multibyte::Json_decode(sqltable_model::getComment($rec[coment_id]));
        $coment_id = $operations[$operation[id]][comment_id];
        $rec[comment] = sqltable_model::getComment($coment_id);
        $rec[action_date] = $operations[$operation[id]][date];
        return $rec;
    }
    
    public function setRecord($data) {
        $operation[$data[operation_id]]=array(
                    'date' => $data[action_date],
                    'comment_id' =>  sqltable_model::getCommentId($data[comment]),
                       );
        // если в поле для нового ввели номер сопроводиловки уже существующий в журнале
        // то будем править его
        $sql = "SELECT * FROM {$this->maintable} WHERE lanch_id='{$data[lanch_id]}'";
        $res = sql::fetchOne($sql);
        if (empty($res)) {
            // гадство! тут нужен уникальный, а без коментариев будет получаться один
            sql::insert('coments',array(array("comment" => multibyte::Json_encode($operation))));
            $data[coment_id] = sql::lastId();
        } else {
            $coment = multibyte::Json_decode(sqltable_model::getComment($res[coment_id]));
            $coment[$data[operation_id]] = $operation[$data[operation_id]]; // заменить старый по ключу
            sql::insertUpdate('coments',array(array("id"=>$res[coment_id],"comment"=>  multibyte::Json_encode($coment))));
            $data[edit] = $res[id]; // если был такой его и правим
            $data[coment_id]=$res[coment_id];
        }
        $data[lastoperation] = $data[operation_id];
        parent::setRecord($data);
        return true;
    }

}

?>
