<?php

/**
 * Description of lanch_pt_view
 *
 * @author Игорь
 */
class lanch_pt_view extends sqltable_view {

    public function showrec($rec) {
        $out = $rec["filenames"];
        $out .= $this->addComments($rec["id"],'phototemplates');
        return $out;
    }
}

?>
