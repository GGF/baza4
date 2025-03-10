<?php

class lanch_nzap_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {

        $boardsinfo = '';
        foreach ($rec['boards'] as $board) {
            foreach ($board as $key => $val) {
                Output::assign($key, $val);
            }
            $boardsinfo .= $this->fetch('boardinfo.tpl');
        }
        Output::assign("boardsinfo", $boardsinfo);
        foreach ($rec['block'] as $key => $block) {
            Output::assign($key, $block);
        }
        Output::assign('onlycalclink', $rec['onlycalclink']);
        $out = $this->fetch('record.tpl');
        if ( isset($rec['mp']) ) {
            Output::assign('mplink', $rec['mp']['mplink']);
            $out .= $this->fetch('mp.tpl');
        }
        if ($rec['zadel']>=(int)($rec['block']['boardinorder']) && (int)($rec['block']['boardinorder'])>0 ) {
            // показать кнопку использования задела
            Output::assign('zadellink', $rec['zadellink']);
            $out .= $this->fetch('zadel.tpl');
        } else {
            $out .= '<br>';
            foreach ($rec['party'] as $party) {
                foreach ($party as $key => $val) {
                    Output::assign($key, $val);
                }
                if (Auth::getInstance()->getRights('lanch_nzap', 'edit')) {
                    if (isset($party['slid'])) {
                        $out .= $this->fetch('partylink.tpl');
                    } else {
                        $out .= $this->fetch('partybutton.tpl');
                    }
                }
                //$out .= $party[party] % 5 == 0 ? "<br>" : "";
            }
        }
        //$out .= print_r($rec,true);
        // комментарии покажем
        $out .= $this->addComments($rec['edit'],'posintz');
        return $out;
    }

    public function createsl($rec) {
        extract($rec);
        $excel = file_get_contents($this->getDir() . $template );
        if ($fileext == "xml" ) {
            // усли шаблон xml файл
            // заменяем в xml файле данные попадающие под шаблон _имя_ на переменную имя из $rec
            preg_match_all('/_([0-9a-z]+)_/', $excel, $matchesarray);
            for ($i = 0; $i < count($matchesarray[0]); $i++) {
                $excel = str_replace($matchesarray[0][$i], ${$matchesarray[1][$i]}, $excel);
            }
        } elseif ($fileext == "xls") {
            // а шаблон может быть и xls файлом, 
            if (fileserver::savefile("{$filename}.txt", $rec)) {
                $url="http://baza3.mpp/?level=getdata&getdata[act]=checksl&slid={$number}";
                try {
                    $barcode = new QR_Code(-1,QR_ErrorCorrectLevel::H);
                    $barcode->addData($url);
                    $barcode->make();
                    
                    $imgbarcode = new QR_CodeImage($barcode,150,150,10);
                    $imgbarcode->draw();
                    $imgbarcode->store("{$filename}.jpg");
                    $imgbarcode->finish();
                    @chmod("{$filename}.jpg", 0777);
                } catch (Exception $ex) {
                    console::getInstance()->out($ex->getMessage());
                    console::getInstance()->out($ex->getTraceAsString());
                }
                /*$barcode = new BarcodeQR();
                $barcode->url($url);
                $barcode->draw(150, "{$filename}.png");
                */
            } else {
                $out = "Не удалось создать файл txt";
                return false;
            }
            // а сам xls запишитеся ниже
        } else {
            // вернем ошибку или в будущем как-то обработаем файл
            return false;
        }
        // сохранить 
        if (fileserver::savefile($filename, $excel)) {
            Output::assign('party', $party);
            Output::assign('sllink', fileserver::sharefilelink($filename));
            Output::assign('slid', $lanch_id);
            $out = $this->fetch('partylink.tpl') . ($last ? '<script>reload_table();</script>' : '');
        } else {
            $out = "Не удалось записать файл";
            $out = false;
        }
        // вернуть ссылку на файл
        return $out;
    }

    /**
     * Создает лист запуска из раздела и возвращает текст для кнопки использования раздела
     * @param mixed $rec масссив с данными для создани СЛ
     * @return string текст для кнопки
     */

    public function showzadel($rec) {
        return "<b>Задел использован! Отккройте плату снова!</b>"; //заглушка
    }

}

?>
