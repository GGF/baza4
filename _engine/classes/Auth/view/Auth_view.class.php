<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Отображение для Аутентификации
 *
 * @author Игорь
 */
class Auth_view extends views {

    public function getDir() {
	return __DIR__;
    }

    public function showform($date) {
	return $this->fetch('form.tpl', $date);
    }

}

?>
