<?php

class possibility_boards_model extends sqltable_model {

    public function __construct() {
        parent::__construct();
        $this->maintable = 'possibility_boards';
    }

    public function getCols() {
        $cols = array();
        $cols['board'] = "Плата";
        $cols['possibility'] = "Можем";
        $cols['reason'] = "Почему";
        return $cols;
    }

    public function getData($all = false, $order = '', $find = '', $idstr = '')
    {
        $sql = "SELECT * FROM {$this->maintable} ";
        $sql .=
        (!empty($find) ? "WHERE board LIKE '%{$find}%' " : "") .
        (!empty($order) ? "ORDER BY {$order} " : 'ORDER BY board DESC ') .
        ($all ? 'LIMIT 50' : 'LIMIT 20');

        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function setRecord($data) {
        if (empty($data['possibility']))
            $data['possibility'] = 0; // пропадал параметр если снят флажок, надо проверить другие места TODO
        return parent::setRecord($data);
    }

}


?>