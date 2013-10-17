<?php

class multibyte {

    static public function is_utf($t) {
        if (@preg_match('/.+/u', $t))
            return true;
        else
            return false;
    }

    static public function utf8_to_cp1251($var) {
        $newVar = array();

        if (is_array($var)) {
            foreach ($var as $k => $v)
                $newVar[$k] = self::utf8_to_cp1251($v);
        } else {
            $newVar = iconv("UTF-8", "CP1251", $var);
        }
        return $newVar;
    }

    static public function cp1251_to_utf8($var) {
        $newVar = array();

        if (is_array($var)) {
            foreach ($var as $k => $v)
                $newVar[$k] = self::cp1251_to_utf8($v);
        } else {
            $newVar = iconv("CP1251", "UTF-8", $var);
        }
        return $newVar;
    }

    static public function UTF($var, $action = 'ENCODE') {

        $newVar = array();

        if (is_array($var)) {

            foreach ($var as $k => $v)
                $newVar[self::UTF($k, $action)] = self::UTF($v, $action);
        } else {
            if ($action == 'ENCODE') {
                $newVar = (!self::is_utf($var)) ? iconv($_SERVER[EncodingCP], "UTF-8", $var) : $var;
            } else {
                $newVar = (self::is_utf($var)) ? iconv("UTF-8", $_SERVER[EncodingCP], $var) : $var;
            }
        }

        return $newVar;
    }

    static public function UTF_encode($var) {
        return self::UTF($var, "ENCODE");
    }

    static public function UTF_decode($var) {
        return self::UTF($var, "DECODE");
    }

    static public function recursiveEscape($var) {
        array_walk_recursive($var, create_function('&$item,$key', '{$item=multibyte::Escape($item);;}'));
        return $var;
    }

    static public function Json_encode($var, $removeEntities = false) {

        // изза html сущностей не получается json_encode
        //array_walk_recursive($var, create_function('&$item,$key', '{$item=multibyte::Escape($item);;}'));
//        array_walk_recursive($var, create_function('&$item,$key', '{$item=htmlentities($item);}'));
        
        if ($_SERVER [Encoding] != "UTF-8")
            $var = self::UTF_encode($var);
        $json = json_encode($var,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);

//        if ($removeEntities)
//            $json = self::UTF_entityDecode($json);

        return $json;
    }

    // FROM JSON TO ARRAY
    static public function Json_decode($json) {

        if ($_SERVER [Encoding] != "UTF-8")
            $json = self::UTF_encode($json);

        $var = json_decode($json, true);

        if ($_SERVER [Encoding] != "UTF-8")
            $var = self::UTF_decode($var);

        return $var;
    }

    static public function UTF_entityEncode($text) {

        $res = "";

        foreach (self::mb_splitChars($text) as $t) {

            $ord = self::mb_ord($t);

            if (strlen($ord) < 2)
                $ord = "0" . $ord;
            if (strlen($ord) < 3)
                $ord = "0" . $ord;
            if (strlen($ord) < 4)
                $ord = "0" . $ord;

            $res .= "\u" . $ord;
        }

        return $res;
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
    static public function UTF_entityDecode($var, $encode = true) {

        // Split by \uXXXX
        $str = array();
        $var = preg_split('/(\\\u[a-zA-Z0-9]{4})/si', $var, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach ($var as $v) {

            // catch XXXX from \uXXXX
            if (preg_match_all('/\\\u([a-zA-Z0-9]{4})/si', $v, $res, PREG_PATTERN_ORDER))
                $str[] = self::mb_chr(hexdec($res[1][0])); else
                $str[] = $v;
        }

        $str = implode($str);
        return $encode ? iconv("UTF-8", $_SERVER[EncodingCP], $str) : $str;
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

    static public function Escape($string, $reverse=false) {

        $encTable = array(// пока только для WINDOWS
            "А" => "%u0410", "Б" => "%u0411", "В" => "%u0412", "Г" => "%u0413",
            "Д" => "%u0414", "Е" => "%u0415", "Ё" => "%u0401", "Ж" => "%u0416",
            "З" => "%u0417", "И" => "%u0418", "Й" => "%u0419", "К" => "%u041A",
            "Л" => "%u041B", "М" => "%u041C", "Н" => "%u041D", "О" => "%u041E",
            "П" => "%u041F", "Р" => "%u0420", "С" => "%u0421", "Т" => "%u0422",
            "У" => "%u0423", "Ф" => "%u0424", "Х" => "%u0425", "Ц" => "%u0426",
            "Ч" => "%u0427", "Ш" => "%u0428", "Щ" => "%u0429", "Ъ" => "%u042A",
            "Ы" => "%u042B", "Ь" => "%u042C", "Э" => "%u042D", "Ю" => "%u042E",
            "Я" => "%u042F", "а" => "%u0430", "б" => "%u0431", "в" => "%u0432",
            "г" => "%u0433", "д" => "%u0434", "е" => "%u0435", "ё" => "%u0451",
            "ж" => "%u0436", "з" => "%u0437", "и" => "%u0438", "й" => "%u0439",
            "к" => "%u043A", "л" => "%u043B", "м" => "%u043C", "н" => "%u043D",
            "о" => "%u043E", "п" => "%u043F", "р" => "%u0440", "с" => "%u0441",
            "т" => "%u0442", "у" => "%u0443", "ф" => "%u0444", "х" => "%u0445",
            "ц" => "%u0446", "ч" => "%u0447", "ш" => "%u0448", "щ" => "%u0449",
            "ъ" => "%u044A", "ы" => "%u044B", "ь" => "%u044C", "э" => "%u044D",
            "ю" => "%u044E", "я" => "%u044F", 
        );

        if ($reverse) {

            foreach ($encTable as $k => $v) {

                $rEncTable[$v] = $k;
            }

            return strtr($string, $rEncTable);
        } else
            return strtr($string, $encTable);
    }

    static public function Unescape($str) {

        $str = str_replace(chr(13), ' ', $str);
        $str = str_replace(chr(10), ' ', $str);
        $str = preg_replace('/%u([a-z0-9]{4})/si', '\u$1', $str);
        $str = preg_replace('/%([a-z0-9]{2})/si', '\u00$1', $str);

        return self::UTF_entityDecode($str, false);
    }

    static public function mb_splitChars($string) {

        $strlen = mb_strlen($string);

        while ($strlen) {

            $array[] = mb_substr($string, 0, 1, "UTF-8");
            $string = mb_substr($string, 1, $strlen, "UTF-8");
            $strlen = mb_strlen($string);
        }

        return $array;
    }

    static public function mb_chr($c) {

        if ($c <= 0x7F) {
            return chr($c);
        } else if ($c <= 0x7FF) {
            return chr(0xC0 | $c >> 6) . chr(0x80 | $c & 0x3F);
        } else if ($c <= 0xFFFF) {
            return chr(0xE0 | $c >> 12) . chr(0x80 | $c >> 6 & 0x3F)
            . chr(0x80 | $c & 0x3F);
        } else if ($c <= 0x10FFFF) {
            return chr(0xF0 | $c >> 18) . chr(0x80 | $c >> 12 & 0x3F)
            . chr(0x80 | $c >> 6 & 0x3F)
            . chr(0x80 | $c & 0x3F);
        } else {
            return false;
        }
    }

    static public function mb_ord($c, $index = 0, &$bytes = null) {

        $len = strlen($c);
        $bytes = 0;

        if ($index >= $len)
            return false;

        $h = ord($c{$index});

        if ($h <= 0x7F) {
            $bytes = 1;
            return $h;
        } else if ($h < 0xC2) {
            return false;
        } else if ($h <= 0xDF && $index < $len - 1) {
            $bytes = 2;
            return ($h & 0x1F) << 6 | (ord($c{$index + 1}) & 0x3F);
        } else if ($h <= 0xEF && $index < $len - 2) {
            $bytes = 3;
            return ($h & 0x0F) << 12 | (ord($c{$index + 1}) & 0x3F) << 6
            | (ord($c{$index + 2}) & 0x3F);
        } else if ($h <= 0xF4 && $index < $len - 3) {
            $bytes = 4;
            return ($h & 0x0F) << 18 | (ord($c{$index + 1}) & 0x3F) << 12
            | (ord($c{$index + 2}) & 0x3F) << 6
            | (ord($c{$index + 3}) & 0x3F);
        } else
            return false;
    }

}

?>