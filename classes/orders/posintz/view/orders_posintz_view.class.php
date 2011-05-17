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
        $view = new orders_blocks_view($this->owner);
        $out = $view->showrec($rec);
        return $out;
    }

}

?>
