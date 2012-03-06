<?php

/**
 * Модель для показа типов настроек
 *
 * @author igor
 */
class setting_show_model extends sqltable_model {
    
    public function __construct() {
        parent::__construct();
        $this->maintable = 'users__settings_types';
    }
    
    /**
     * Воззвращает массив данных из базы
     * @param boolean $all Покказывать все
     * @param string $order Наззвание столбца по которому сортировать
     * @param string $find Подстрока поиска
     * @param string $idstr строка  идентификаторов, специальное использование, очень специальное
     * @return array 
     */
    public function getData($all=false,$order='',$find='',$idstr='') {
        $ret = array();
        $sql = "SELECT * " .
                "FROM users__settings_types " .
                (!empty($find) ? "WHERE description LIKE '%{$find}%'
                            OR key LIKE '%{$find}%'":"") .
                (!empty($order)?"ORDER BY {$order} ":
                                        "ORDER BY users__settings_types.id DESC ") .
                ($all?"":"LIMIT 20");

        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
	$cols[description]="Название насстройки";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM users__settings_types WHERE id='{$id}'";
	sql::query($sql);
        return sql::affected();
    }
    
    public function setRecord($data) {
        extract($data);
        $sql = "INSERT INTO {$this->maintable} (`key`,`description`) VALUES ('{$key}','{$description}')";
        sql::query($sql);
        $ret[affected] = true;
        return $ret;
    }
    
}

?>
