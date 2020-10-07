<?php

/**
 * Description of lanch_mp_view
 *
 * @author Игорь
 */
class lanch_mp_view extends sqltable_view {

    /**
     * Показ записи
     * @var array $rec - передает данные из модели
     * 
     */
    public function showrec($rec) {
        $fields = array(); // пустые поля
        if (!$rec['isnew']) { //если запись не новая то просто пропишем заказчика и плату
            //тут бы выдать ссылку на файл мастерплаты, но он не записан
            $rec = $rec['customer']['customer'] . " - " . $rec['block']['blockname'];
        } else { // иначе выберем
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "customer_id",
                "label" => "Заказчик:",
                "values" => $rec['customers'],
                "value" => '',
                "options" => array("html" => " autoupdate-link='{$rec['boardlink']}' autoupdate=boardid ",),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "board_id",
                "label" => "Плата:",
                "values" => $rec['boards'],
                "value" => '',
                "options" => array("html" => " boardid "),
            ));
            $rec['fields'] = $fields;
        }
       
        return parent::showrec($rec);
    }

    /**
     * Создает файл мастерплаты и возвращает на него ссылку
     * @param array $rec данные из модели для формирования файла
     * @return string html с ссылкой или неудачным результатом
    */
    public function createMPFile($rec) {
        $filename = "z:\\Заказчики\\{$rec['customer']}\\{$rec['blockname']}\\Мастерплаты\\МП-{$rec['date']}-{$rec['mp_id']}.xls";
        $filename = fileserver::createdironserver($filename);
        $excel = file_get_contents($this->getDir() . "/mp.xls");
        if (fileserver::savefile($filename,$excel)) {
            $mp['_date_'] = date("d.m.Y");
            $mp['_number_'] = sprintf("%08d\n",$rec['mp_id']);
            if (fileserver::savefile($filename.".txt",$mp)) {
                Output::assign('mplink', fileserver::sharefilelink($filename));
                Output::assign('mpid', $rec['mp_id']);
                $out = $this->fetch('mplink.tpl');
            } else {
                $out = "Не удалось создать файл txt";
            }
        } else {
            $out = "Не удалось создать файл xls" . print_r($rec,true);
        }

        return $out;
    }
}

?>
