<?php

/**
 * Description of productioncard_mpp_model
 *
 * @author Игорь
 */
class productioncard_mpp_model extends productioncard_dpp_model {
    public function __construct() {
        parent::__construct();
        $this->blocktype = 'mpp';
    }
    
}
