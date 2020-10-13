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
                "value" => '', // новая же
                "options" => array("html" => " autoupdate-link='{$rec['blocklink']}' autoupdate=blockid ",),
            ));
            array_push($fields,array(
                "type" => AJAXFORM_TYPE_SELECT,
                "name" => "block_id",
                "label" => "Плата:",
                "values" => '', //  новая же, при выборе заказчика запишутся
                "value" => '',
                "options" => array("html" => " blockid "),
            ));
            /*
            array_push($fields,array(
                'name' => 'create_button',
                'type' => AJAXFORM_TYPE_BUTTON,
                'value' => Lang::getString('masterboard.create'),
                'options' => array('html'=>"data-silent='self' legotarget='lanch_mp' href='".$this->owner->actUri('getmp')->url()."' "),
            ));
            */

            $rec['fields'] = $fields;
        }
       
        return parent::showrec($rec);
        
    }

    /**
     * Возвращает имя файла мастерплаты по данным из модели, вспомогательная для создания мастерплаты
     * @param array $rec - данные из модели
     * @return string - имя файла
     */
    public function getMPFlieName(array $rec) {
        $filename = "z:\\".Lang::getString('folders.customer')."\\{$rec['customer']}\\{$rec['blockname']}\\".Lang::getString('folders.masterboard')."\\МП-{$rec['date']}-{$rec['mp_id']}.xls";
        return fileserver::createdironserver($filename);
    }

    /**
     * Возвращает ссылку на файл мастерплаты
     * @param array $rec - данные из модели
     * @return string - HTML ссылки на файл
     */
    public function getMPLink($rec) {
        Output::assign('mplink', fileserver::sharefilelink($rec['filename']));
        Output::assign('mpid', $rec['mp_id']);
        $out = $this->fetch('mplink.tpl');
        if (empty($out)) $out = Lang::getString('error.nofoundtemplate');
        return $out;
    }

}

?>
