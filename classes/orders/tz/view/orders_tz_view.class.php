<?php

class orders_tz_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        Output::assign('tzlink', fileserver::sharefilelink($rec[tzlink]));
        Output::assign('tzid', $rec[id]);
        return $this->fetch('tzlink.tpl').'<script>reload_table()</script>';;
    }

    public function savefiletz($rec) {
        extract($rec);
        $excel = file_get_contents($this->getDir() .($typetz == "mpp" ? "/tzmpp.xls" : ($typetz == "dpp" ? "/tzdpp.xls" : "/tzdppm.xls")));
        $file = @fopen($filename, "w");
        if ($file) {
            fwrite($file, $excel);
            fclose($file);
            @chmod($filename, 0777);
            $file = @fopen($filename . ".txt", "w");
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
            } else {
                return "Не удалось создать файл txt";
            }
        } else {
            return "Не удалось создать файл xls" . print_r($rec,true);
        }
        return $this->showrec($rec);
    }

    public function selecttype($data) {
        Output::assign('mpplink', $data[mpplink]);
        Output::assign('dppblink', $data[dppblink]);
        Output::assign('dpplink', $data[dpplink]);
        $out = $this->fetch('seltype.tpl');
        return $out;
    }

}

?>
