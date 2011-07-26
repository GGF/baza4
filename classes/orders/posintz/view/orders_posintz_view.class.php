<?php


/**
 * Description of orders_posintz_view
 *
 * @author igor
 */
class orders_posintz_view extends sqltable_view {

    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        extract($rec);
        if (empty($rasslink)) {
            Output::assign('createlink', $createlink);
            return $this->fetch('createbutton.tpl');
        } else {
            Output::assign('rasslink', fileserver::sharefilelink($rasslink));
            return $this->fetch('rasslink.tpl');
        }
        
    }

}

?>
