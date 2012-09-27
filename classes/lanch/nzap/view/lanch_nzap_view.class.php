<?php

class lanch_nzap_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        foreach ($rec[block] as $key => $block) {
            Output::assign($key, $block);
        }
        $out = $this->fetch('record.tpl');
        foreach ($rec[boards] as $board) {
            foreach ($board as $key => $val) {
                Output::assign($key, $val);
            }
            $out .= $this->fetch('board.tpl');
        }
        if ($rec[mp]) {
            Output::assign('mplink', $rec[mp][mplink]);
            $out .= $this->fetch('mp.tpl');
        }
        if ($rec[zadel]>=$rec[block][boardinorder]) {
            // показать кнопку использования задела
            Output::assign('zadellink', $rec[zadellink]);
            $out .= $this->fetch('zadel.tpl');
        } else {
            $out .= '<br>';
            foreach ($rec[party] as $party) {
                foreach ($party as $key => $val) {
                    Output::assign($key, $val);
                }
                if (Auth::getInstance()->getRights('lanch_nzap', 'edit')) {
                    if ($party[slid]) {
                        $out .= $this->fetch('partylink.tpl');
                    } else {
                        $out .= $this->fetch('partybutton.tpl');
                    }
                }
                $out .= $party[party] % 5 == 0 ? "<br>" : "";
            }
        }
        //$out .= print_r($rec,true);
        // комментарии покажем
        $out .= $this->addComments($rec["edit"]);
        return $out;
    }

    public function createsl($rec) {
        extract($rec);
        $excel = ($dpp ? ((stristr($mater,"TLX") || stristr($mater,"ro") || stristr($mater,"фаф")) ? "/slro.xml" : ($class==3?($aurum=="+"?"/sl3a.xml":"/sl3.xml"):($aurum=="+"?"/sl4a.xml":"/sl4.xml"))) : "/slmpp.xml");
        $excel = file_get_contents($this->getDir() . $excel );
        preg_match_all('/_([0-9a-z]+)_/', $excel, $matchesarray);
        for ($i = 0; $i < count($matchesarray[0]); $i++) {
            $excel = str_replace($matchesarray[0][$i], ${$matchesarray[1][$i]}, $excel);
        }
        if (fileserver::savefile($filename, $excel)) {
            Output::assign('sllink', fileserver::sharefilelink($filename));
            Output::assign('slid', $lanch_id);
            $out = $this->fetch('partylink.tpl') . ($last ? '<script>reload_table();</script>' : '');
        } else {
            $out = "Не удалось записать файл";
            $out = false;
        }
        return $out;
    }

    public function showmplink($rec) {
        $filename = "z:\\Заказчики\\{$rec[customer]}\\{$rec[blockname]}\\Мастерплаты\\МП-{$rec[date]}-{$rec[mp_id]}.xml";
        $filename = fileserver::createdironserver($filename);
        $date = date("d-m-Y");
        $excel = file_get_contents($this->getDir() . "/mp.xml");
        $excel = str_replace("_number_", sprintf("%08d", $rec[mp_id]), $excel);
        $customer = multibyte::UTF_encode($rec[customer]);
        $excel = str_replace("_customer_", $customer, $excel);
        $excel = str_replace("_date_", $date, $excel);
        $blockname = multibyte::UTF_encode($rec[blockname]);
        $excel = str_replace("_blockname_", $blockname, $excel);
        $excel = str_replace("_sizex_", ceil($rec[sizex]), $excel);
        $excel = str_replace("_sizey_", ceil($rec[sizey]), $excel);
        $excel = str_replace("_drlname_", $rec[drlname], $excel);

        if (fileserver::savefile($filename, $excel)) {
            Output::assign('mplink', fileserver::sharefilelink($filename));
            Output::assign('mpid', $rec[mp_id]);
            $out = $this->fetch('mplink.tpl');
        } else {
            $out = "Не удалось записать файл";
        }
        return $out;
    }

    /**
     * Создает лист запуска из раздела и возвращает текст для кнопки использования раздела
     * @param mixed $rec масссив с данными для создани СЛ
     * @return string текст для кнопки
     */

    public function showzadel($rec) {
        return "<b>Заддел использован! Отккройте плату снова!</b>"; //заглушка
    }

}

?>
