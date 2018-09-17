<?php

/* 
 * класс как для незапущенных, но показывает, только те, что не надо запускать
 */

class lanch_onlycalc_model extends lanch_nzap_model {
    
    public function __construct() {
        parent::__construct();
        $this->onlycalc = 1; // тут имеется ввиду показывать только расчитываемые
    }
}

