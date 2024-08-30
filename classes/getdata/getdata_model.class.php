<?php

/**
 * Получене различных данных из базы для интеактивных действий в ТЗ на excel 
 * или САМ-файлах (скриптами)
 *
 * @author igor
 */
class getdata_model extends sqltable_model {

    /**
     * Для ТЗ или уже не помню
     * @param array $rec Массив REQUEST
     * @return json
     */
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
        $res['blockpos'] = sql::fetchAll($sql);
        $rec['blockcomment'] = $this->getComment($rec['comment_id']);
        $rec['boardcomment'] = $this->getComment($rec['blockpos'][0]['comment_id']);
        $out .= json_encode($res);
        return $out;
    }

    /**
     * Текстолит возвращает для ТЗ
     * @param array $rec Массив $REQUEST из контроллера передается
     * @return json
     */
    public function textolite($rec) {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        $out = '';
        $sql = "SELECT * FROM `zaomppsklads`.`sk_mat__spr` ORDER BY nazv";
        $res['textolite'] = sql::fetchAll($sql);
        $out .= json_encode($res,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
        return $out;
    }
    
    /**
     * moneyfororder возвращает данные для расчетов на одну плату
     * @param array $req Массив $REQUEST из контроллера передается
     * @return json
     */
    public function moneyfororder($req) {
        $rec = multibyte::cp1251_to_utf8($req);
        extract($req);
        $out = '';
        $sql = "SELECT count(*) as records, `customer`, `order` as ordernumber FROM `moneyfororder`  ";
        $rs = sql::fetchAll($sql);
        $resarr['orderdata'] = $rs;
        $sql = "SELECT board,trud,mater, matedizm AS edizm,SUM(matcost) AS summatcost,SUM(matras) AS summatras,
                    SUM(trudcost) AS sumtrudcost,
                    SUM(trudem) AS sumtrudem 
                        FROM `moneyfororder` GROUP BY `board`,`mater`,`trud` ";//WHERE `customer` LIKE '%{$customer}%' AND `order` LIKE '%{$order}%' ";
        $rs = sql::fetchAll($sql);
        $resarr['datas'] = $rs;
        $sql = "SELECT board FROM `moneyfororder` GROUP BY `board`";//WHERE `customer` LIKE '%{$customer}%' AND `order` LIKE '%{$order}%' ";
        $rs = sql::fetchAll($sql);
        $resarr['boards'] = $rs;
        
        //return print_r($rs);
        /*$board = $rs[0][board];
        foreach ($rs as $res) {
            if ($res[board] != $board ) {
                $resarr[$board] = $arr;
                $arr = array();
                $board = $res[board];     
            }
            if ($res[trud] == '' ) {
                $arr[$res[mater]][ras] = $res[summatras];
                $arr[$res[mater]][cost] = $res[summatcost];
            } else {
                $arr[$res[trud]][ras] = $res[sumtrudem];
                $arr[$res[trud]][cost] = $res[sumtrudcost];
            }
        }
        $resarr1[boards] = $resarr;*/
        $out .= json_encode($resarr,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
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
     * @return  variable в зависимости
     * rem tear "http://baza4/?level=getdata&getdata[act]=uniget&table=customers&id=5" > res
     * rem tear "http://baza4/?level=getdata&getdata[act]=uniget&object=orders_customers_model&id=5" > res
     * rem tear "http://baza4/?level=getdata&getdata[act]=uniget&object=orders_customers_model&id=5&format=json" > res
     * rem tear "http://baza4/?level=getdata&getdata[act]=uniget&table=customers&str=%%D0%%90%%D0%%B7%%D0%%B8%%D0%%BC%%D1%%83%%D1%%82&field=customer" >res
     * tear "http://baza4/?level=getdata&getdata[act]=uniget&table=boards&field=boardname&getfield=extinfo&str=GGFF.758725.148" >res
     */
    public function uniget($rec) {
        //if(!multibyte::is_utf($rec)) { // чтото оно плохо работает. А! если хоть ктото из массива попадает под utf, то весь массив считается
            $rec = multibyte::cp1251_to_utf8($rec);
        //}
        extract($rec);
        if (!isset($getfield)) {
            $getfield = '*';
        } else {
            $getfield = "`{$getfield}`"; 
        }
        if(!isset($format)) {
            $format = "keyline";
        }
        if (isset($id)) {
            if (isset($table)) {
                $sql = "SELECT {$getfield} FROM `{$table}` WHERE id='{$id}'";
                $res = sql::fetchOne($sql);
            } elseif (isset($object)) {
                if(class_exists($object)) {
                    $object = new $object();
                    if(method_exists($object, "getRecord")) {
                        $res = $object->getRecord($id);
                    }
                }
            }
        } elseif (isset($str)) {
            if (isset($field)) {
                if (!isset($like)) { // вид поиска
                    $sql = "SELECT {$getfield} FROM `{$table}` WHERE `{$field}`='{$str}'";
                } else {
                    $sql = "SELECT {$getfield} FROM `{$table}` WHERE `{$field}` LIKE '%{$str}%'";
                }
                $res = sql::fetchOne($sql);
            } else {
                // не определить
                return;
            }
        }
        // вывод
        if (!empty($res)) {
            if ($format == "json") {
                $res = json_encode($res,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
                //echo $res;
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


    /**
     * Получить данные по матералу для расчета стоимость, нормы расхода
     */
    public function getcalcmatter(Array $rec)
    {
        $rec = multibyte::cp1251_to_utf8($rec);
        extract($rec);
        $sql = "SELECT calc__matter_pricelist.id AS matter_id,`matter_name`,`matter_unit`,`matter_price`,`discharge_norm_in`,`discharge_norm_out`
        FROM calc__matter_pricelist
        JOIN (calc__types,calc__matters,calc__suppliers)
        ON (calc__matter_pricelist.matter_type_id = calc__types.id 
            AND calc__matter_pricelist.matter_name_id = calc__matters.id
            AND calc__matter_pricelist.supplier_id = calc__suppliers.id
            ) WHERE record_date IN (SELECT MAX(record_date) FROM calc__matter_pricelist GROUP BY matter_name_id) AND matter_name like '%{$matter}%' ORDER BY `record_date` DESC";
            // ORDER BY `record_date` DESC - изза одинаковых дат, добавлял группой получилась неразбериха
        $res = sql::fetchOne($sql);
        $res = json_encode($res,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
        return $res;
    }

}

?>
