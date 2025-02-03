<?php

class lanch_zap_view extends sqltable_view {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        $out='';
        Output::assign('linkclass', 'path');
        if (isset($rec['path'])) {
            Output::assign('sllink', "file://".$rec['path']);
            Output::assign('type', 'Путь к блоку');
            Output::assign('slid', '');
            $out .= $this->fetch('link.tpl');
        }
        if(isset($rec['sl'])) {
            Output::assign('type', 'СЛ-');
            Output::assign('sllink', $rec['sl']['link']);
            Output::assign('slid', $rec['sl']['id']);
            Output::assign('linkclass', 'filelink');
            $out .= $this->fetch('link.tpl');
        }
        if (isset($rec['tz'])) {
            Output::assign('type', 'ТЗ-');
            Output::assign('sllink', $rec['tz']['link']);
            Output::assign('slid', $rec['tz']['id']);
            $out .= $this->fetch('link.tpl');
        }
        if (isset($rec['letter'])) 
            $out .= "Письма:&nbsp;".$rec['letter']['link'];
        if (isset($rec['zadel']) && $rec['zadel']>0) $out .= "В&nbsp;заделе&nbsp;{$rec['zadel']}&nbsp;шт.";
        // Добавить информацию о платах в блоке
        foreach ($rec['boards'] as $value) {
            $files = $value['filelinks']['link'];
            $out .= $this->fetch('board.tpl',array( 'boardname' => $value["board_name"], 'filelinks' => $files ));
        }
        
        if(isset($rec['dopzaplink'])) 
            Output::assign('dozaplink', $rec['dozaplink']);
        else
            Output::assign('dozaplink', '');
        Output::assign('filelinks',$out);
        
        $out = $this->fetch('dozap.tpl');
        $out .= "<script>$('form[name=dozap]').submit(function(){
        $().lego.load('lanch_nzap', $(this).attr('action'),$(this).serialize());
        reload_table();
        return false;
    });</script>";
        $out .= $this->addComments($rec['id'],'lanch');
        return $out;
    }

}

?>
