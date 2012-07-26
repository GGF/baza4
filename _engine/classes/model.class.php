<?php

/**
 * Класс для моделей абстрактный, но все мои модели будут наследовать
 *
 * @author Игорь
 */
abstract class model {
    /**
     * Коструктор
     *
     * @return int
     */
    public function __construct() {
	return 0;
    }

    public abstract function getDir() ;

    public function install($replace=array()) {
	sql::queryfile( $this->getDir() . "/install.sql" );
    }
}

?>
