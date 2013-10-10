<?php

/**
 * Description of lanch_mp_view
 *
 * @author Игорь
 */
class lanch_mp_view extends sqltable_view {
    public function showrec($rec) {
        $out = $rec["id"] . " - " . $rec["mpdate"];
        $out .= $this->addComments($rec["id"],'masterplate');
        return $out;
    }
}

?>
