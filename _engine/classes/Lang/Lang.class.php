<?php

/**
 * Возвращает строку по индексу и текущему языку
 *
 * @author igor
 */

class Lang extends JsCSS {
    
    static public $text;
    
    public function staticConsturct() {
        parent::staticConsturct();
        self::$text = array();
    }


    public function getDir() {
        return __DIR__;
    }
    
    static public function add($index,$text='') {
        self::$text[$_SERVER["language"]][$index] = empty($text)?$index:$text;
    }
    
    static public function getString($index='') {
        if (empty ($index)) {
            if (empty ($_SERVER["language"])) {
                return 'EN';
            } else {
                return $_SERVER["language"];
            }
        } else {
            if (!empty (self::$text[$_SERVER["language"]][$index]))
                return self::$text[$_SERVER["language"]][$index];
            else
                return $index;
        }
    }
}

Lang::staticConsturct();
?>
