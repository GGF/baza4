<?php

/*
 * conduct model class
 */

class cp_users_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT *
                FROM users " .
                (!empty($find) ? "WHERE (nik LIKE '%{$find}%' OR fullname LIKE '%{$find}%' OR position LIKE '%{$find}%') " : "") .
                (!empty($order) ? "ORDER BY " . $order . " " : "ORDER BY nik ") .
                ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols[id] = "ID";
        $cols[nik] = "Nik";
        $cols[fullname] = "Fullname";
        $cols[position] = "Position";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id='{$id}'";
        sql::query($sql);
        $sql = "DELETE FROM rights WHERE u_id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }

    public function getRecord($id) {
        $sql = "SELECT * FROM users WHERE id='{$id}'";
        $rec = sql::fetchOne($sql);
        return $rec;
    }

    public function  setRecord($data) {
        extract($data);
        if ($action == "users") {
            if (!empty($edit)) {
                // редактирование
                $sql = "UPDATE users
                            SET nik='{$nik}',
                                fullname='{$fullname}',
                                position='{$position}',
                                password='{$password1}'
                            WHERE id='{$edit}'";
            } else {
                // добавление
                $sql = "INSERT INTO users (nik,fullname,position,password)
                            VALUES ('$nik','$fullname','$position','$password1')";
            }
            sql::query($sql);
        } else {
            $sql = "DELETE FROM rights WHERE u_id='{$userid}'";
            sql::query($sql);
            if (!empty($r)) {
                foreach ($r as $key => $val) {
                    foreach ($val as $k => $V) {
                        $sql = "INSERT INTO rights (u_id,type_id,rtype_id,rights.right) VALUES ('{$userid}','{$key}','{$k}','1')";
                        sql::query($sql);
                    }
                }
            }
            // почистить сессию для того чтоб вступили права пользователь должен перезайти
            //$sql = "DELETE FROM session WHERE u_id='{$userid}'";
            //sql::query($sql);
        }
        return sql::affected();
    }

    public function getRights($userid) {
        $sql = "SELECT * FROM rtypes";
        $res = sql::fetchAll($sql);
        $sql = "SELECT * FROM rrtypes";
        $res1 = sql::fetchAll($sql);
        $out = array();
        foreach ($res as $rs) {
            $rec[type] = $rs[type];
            $rec[name] = "r|{$rs["id"]}";
            foreach ($res1 as $rs1) {
                $sql = "SELECT * FROM rights WHERE type_id='{$rs["id"]}' AND u_id='{$userid}' AND rtype_id='{$rs1["id"]}'";
                $rs2 = sql::fetchOne($sql);
                $value[$rs1["id"]] = ($rs2["right"] == 1 ? 1 : 0);
                $values[$rs1["id"]] = $rs1["rtype"];
            }
            $rec[value] = $value;
            $rec[values] = $values;
            $out[types][] = $rec;
        }
        return $out;
    }

}

?>
