<?php

class lanch_zap_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        Output::assign('type', 'СЛ');
        Output::assign('sllink', $rec[sl][link]);
        Output::assign('slid', $rec[sl][id]);
        $out = $this->fetch('link.tpl');
        $out .= '<br>';
        Output::assign('type', 'ТЗ');
        Output::assign('sllink', $rec[tz][link]);
        Output::assign('slid', $rec[tz][id]);
        $out .= $this->fetch('link.tpl');
        $out .= '<br>';
        Output::assign('dozaplink', $rec[dozaplink]);
        $out .= $this->fetch('dozap.tpl');
        $out .= "<script>$('form[name=dozap]').submit(function(){
        $().lego.load('lanch_nzap', $(this).attr('action'),$(this).serialize());
        return false;
    });</script>";
        return $out;
    }

}

?>
