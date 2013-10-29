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
    
    /**
     * Универсальный запрос и возврат
     * @param array $rec содержит массив REQUEST с параметрами
     * должны быть $id, $table, $format, соответственно идентификатор в таблице, таблица и формат возврата
     * Так как используется для запросов из скриптов (САМ, cmd, bash) форматы 
     * line - посторочно без ключа
     * keyline - посторочно ключ|значение (default)
     * array - в массиве PHP на всякий случай
     * json - ну понятно
     * @return variable в зависимости
     * rem tear "http://baza4/?level=getdata&getdata[act]=uniget&table=customers&id=5" > res
     * rem tear "http://baza4/?level=getdata&getdata[act]=uniget&object=orders_customers_model&id=5" > res
     * rem tear "http://baza4/?level=getdata&getdata[act]=uniget&object=orders_customers_model&id=5&format=json" > res
     * rem tear "http://baza4/?level=getdata&getdata[act]=uniget&table=customers&id=%%D0%%90%%D0%%B7%%D0%%B8%%D0%%BC%%D1%%83%%D1%%82&field=customer" >res
     * tear "http://baza4/?level=getdata&getdata[act]=uniget&table=customers&id=Аврора&field=customer" >res
     */
    public function uniget($rec) {
        if(!multibyte::is_utf($rec)) {
            $rec = multibyte::cp1251_to_utf8($rec);
        }
        extract($rec);
        if(!isset($format)) {
            $format = "keyline";
        }
        if (isset($id)) {
            if (isset($table)) {
                if(1*$id!=0) {
                    $sql = "SELECT * FROM `{$table}` WHERE id='{$id}'";
                } else { 
                    if (isset($field)) {
                        $sql = "SELECT * FROM `{$table}` WHERE `{$field}`='{$id}'";
                    } else {
                        // не определить
                        return;
                    }
                }
                $res = sql::fetchOne($sql);
            } elseif (isset($object)) {
                if(class_exists($object)) {
                    $object = new $object();
                    if(method_exists($object, "getRecord")) {
                        $res = $object->getRecord($id);
                    }
                }
            }
            // вывод
            if ($format == "json") {
                $res = json_encode($res,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
                echo $res;
                return $res;
            } elseif ($format == "array") {
                return $res;
            } else {
                foreach ($res as $key => $value) {
                    if ($format == "line") {
                        echo "{$value}\n";
                    } else {
                        echo "{$key}|{$value}\n";
                    }
                }
                return;
            }
        }
    }

}

?>
