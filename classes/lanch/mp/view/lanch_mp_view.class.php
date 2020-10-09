<?php

/**
 * Description of lanch_mp_view
 *
 * @author Игорь
 */
class lanch_mp_view extends sqltable_view {


    /** 
     * обязательно определять для модуля 
    */
    public function getDir() {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'i18n.php'; //всё равноперреопределяется и вызывается, почему не тут вставлять
        return __DIR__;
    }

    /**
     * Показ записи
     * @var array $rec - передает данные из модели
     * 
     */
    public function showrec($rec) {
        $fields = array(); // пустые поля
        if (!$rec['isnew']) { //если запись не новая то просто пропишем заказчика и плату
            $rec['filename'] = $this->getMPFlieName($rec);
            $rec = $this->getMPLink($rec); // пишу в ту же переменную, но так уж получилось что родитель может принимать mixed
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
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_BUTTON,
                'value' => Lang::getString('masterboard.create'),
            ));
            $rec['fields'] = $fields;
        }
       
        $out = parent::showrec($rec);
        return $out;
        
    }

    /**
     * Создает файл мастерплаты и возвращает на него ссылку
     * @param array $rec данные из модели для формирования файла
     * @return string html с ссылкой или неудачным результатом
    */
    public function createMPFile($rec) {
        $rec['filename'] = $this->getMPFlieName($rec);
        $excel = file_get_contents($this->getDir() . "/mp.xls");
        if (fileserver::savefile($rec['filename'],$excel)) {
            $mp['_date_'] = date("d.m.Y");
            $mp['_number_'] = sprintf("%08d\n",$rec['mp_id']);
            if (fileserver::savefile($rec['filename'].".txt",$mp)) {
                $out = $this->getMPLink($rec);
            } else {
                $out = Lang::getString('error.cantcreatefile') . ' txt';
            }
        } else {
            $out = Lang::getString('error.cantcreatefile') . ' xls' . print_r($rec,true);
        }

        return $out;
    }

    /**
     * Возвращает имя файла мастерплаты по данным из модели, вспомогательная для создания мастерплаты
     * @param array $rec - данные из модели
     * @return string - имя файла
     */
    private function getMPFlieName(array $rec) {
        $filename = "z:\\".Lang::getString('folders.customer')."\\{$rec['customer']}\\{$rec['blockname']}\\".Lang::getString('folders.masterboard')."\\МП-{$rec['date']}-{$rec['mp_id']}.xls";
        return fileserver::createdironserver($filename);
    }

    /**
     * Возвращает ссылку на файл мастерплаты
     * @param array $rec - данные из модели
     * @return string - HTML ссылки на файл
     */
    private function getMPLink($rec) {
        Output::assign('mplink', fileserver::sharefilelink($rec['filename']));
        Output::assign('mpid', $rec['mp_id']);
        $out = $this->fetch('mplink.tpl');
        if (empty($out)) $out = Lang::getString('error.nofoundtemplate');
        return $out;
    }

}

?>
