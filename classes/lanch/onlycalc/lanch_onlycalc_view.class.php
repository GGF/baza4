<?php

class lanch_onlycalc_view extends lanch_nzap_view
{
  // обязательно определять для модуля
  public function getDir()
  {
    return __DIR__;
  }

  /**
   * Как у родителя, только кнопки нужно вырезать
   */
  public function showrec($rec) {
    return preg_replace("|<input[^>]*>|i","",parent::showrec($rec));

  }
}
?>