<?php

/**
 * Description of lanch_pt_model
 *
 * @author igor
 */
class lanch_pt_model extends sqltable_model {
    public function getData($all=false,$order='',$find='',$idstr='') {
        $ret = array();
	$sql="SELECT *,unix_timestamp(ts) AS uts,phototemplates.id AS ptid,phototemplates.id 
              FROM phototemplates
              JOIN users
              ON phototemplates.user_id=users.id " .
              (!empty($find)?"WHERE filenames LIKE '%{$find}%'":"") .
              (!empty($order)?"ORDER BY {$order} ":"ORDER BY ts DESC ") .
              ($all?"LIMIT 50":"LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
	$cols[ptid]="ID";
	$cols[ts]="����";
	$cols[nik]="��� ��������";
	$cols[filenames]="����� � �������";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM phototemplates WHERE id='${delete}'";
	sql::query($sql);
        return sql::affected();
    }
}
?>