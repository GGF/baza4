<?php

class orders_tz_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        Output::assign('tzlink', fileserver::sharefilelink($rec[tzlink]));
        Output::assign('tzid', $rec[id]);
        return $this->fetch('tzlink.tpl');;
    }

    public function savefiletz($rec) {
        extract($rec);
        $excel = file_get_contents($this->getDir() .($typetz == "mpp" ? "/tzmpp.xls" : ($typetz == "dpp" ? "/tzdpp.xls" : "/tzdppm.xls")));
        if ($file = @fopen($filename, "w")) {
            fwrite($file, $excel);
            fclose($file);
            @chmod($filename, 0777);
            if ($file = @fopen($filename . ".txt", "w")) {
                fwrite($file, $cdate . "\n");
                fwrite($file, $fullname . "\n");
                fwrite($file, $order . "\n");
                fwrite($file, $odate . "\n");
                fwrite($file, sprintf("%08d\n", $tzid));
                fclose($file);
                @chmod($filename . ".txt", 0777);
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
