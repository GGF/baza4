<?php

class productioncard_dpp_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'move_in_production';
    }

    public function getData($all=false, $order='', $find='', $idstr='') {
        $res = parent::getData($all, $order, $find, $idstr);
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
        $sql = "SELECT * FROM operations WHERE block_type='dpp' OR block_type='both' ORDER BY priority,id";
        $res = sql::fetchAll($sql);
        foreach ($res as $key => $value) {
            //$cols["oper{$value[id]}"] = "<span id='verticalText'>{$value[operation]}</span>";
            $cols["oper{$value[id]}"] = array(short => $value[shortname],title => $value[operation]);
        }
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->maintable} WHERE id='{$id}'";
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
        if (empty($data[coment_id])) {
            $data[coment_id] = sqltable_model::getCommentId(multibyte::Json_encode($operation));
        } else {
            $coment = multibyte::Json_decode(sqltable_model::getComment($data[coment_id]));
            $coment[$data[operation_id]] = $operation[$data[operation_id]]; // заменить старый по ключу
            sql::insertUpdate('coments',array(array("id"=>$data[coment_id],"comment"=>  multibyte::Json_encode($coment))));
            //$sql = "UPDATE coments SET comment = '".multibyte::Json_encode($coment)."' WHERE id='{$data[coment_id]}'";
        }
        parent::setRecord($data);
        return true;
    }

}

?>
