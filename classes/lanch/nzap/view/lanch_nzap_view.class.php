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
            $out .= $this->fetch('boardinfo.tpl');
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
                //$out .= $party[party] % 5 == 0 ? "<br>" : "";
            }
        }
        //$out .= print_r($rec,true);
        // комментарии покажем
        $out .= $this->addComments($rec["edit"],'posintz');
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
            $file = @fopen("{$filename}.txt", "w");
            if ($file) {
                foreach ($rec as $key => $value) {
                    // сохраним все переменные в файл txt, чтоб xls потом сам оттуда забрал
                    // использем в качестве разделителя вертикальную черту, скорее всего её не будет в данных
                    // если же, паче чаяния, она там окажется придется использовать тройную, скажем, и
                    //  переписывать скрипты в xls файле
                    fwrite($file, sprintf("%s|%s\n",multibyte::utf8_to_cp1251($key),multibyte::utf8_to_cp1251($value)));
                }
                fclose($file);
                @chmod("{$filename}.txt", 0777);
                $url="http://baza3.mpp/?level=getdata&getdata[act]=checksl&slid={$number}";
                $barcode = new BarcodeQR();
                $barcode->url($url);
                $barcode->draw(150, "{$filename}.png");
                @chmod("{$filename}.png", 0777);
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

    public function showmplink($rec) {
        $filename = "z:\\Заказчики\\{$rec[customer]}\\{$rec[blockname]}\\Мастерплаты\\МП-{$rec[date]}-{$rec[mp_id]}.xls";
        $filename = fileserver::createdironserver($filename);
        $excel = file_get_contents($this->getDir() . "/mp.xls");
        $file = @fopen($filename, "w");
        if ($file) {
            fwrite($file, $excel);
            fclose($file);
            @chmod($filename, 0777);
            $file = @fopen($filename.".txt", "w");
            if ($file) {
                $date = date("d.m.Y");
                fwrite($file, sprintf("%08d\n",$rec[mp_id]));
                fwrite($file, multibyte::utf8_to_cp1251($rec[customer]) . "\n");
                fwrite($file, multibyte::utf8_to_cp1251($date) . "\n");
                fwrite($file, multibyte::utf8_to_cp1251($rec[blockname]) . "\n");
                fwrite($file, multibyte::utf8_to_cp1251($rec[sizex]) . "\n");
                fwrite($file, multibyte::utf8_to_cp1251($rec[sizey]) . "\n");
                fwrite($file, multibyte::utf8_to_cp1251($rec[drlname]) . "\n");
                fclose($file);
                @chmod($filename.".txt", 0777);
                Output::assign('mplink', fileserver::sharefilelink($filename));
                Output::assign('mpid', $rec[mp_id]);
                $out = $this->fetch('mplink.tpl');
            } else {
                $out = "Не удалось создать файл txt";
            }
        } else {
            $out = "Не удалось создать файл xls" . print_r($rec,true);
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
