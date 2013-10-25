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
        if (fileserver::savefile($filename, $excel)) {
            if (!fileserver::savefile($filename . ".txt", $rec)) {
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
