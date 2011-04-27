<?php

class multibyte {

    static public function is_utf($t) {
        if (@preg_match('/.+/u', $t))
            return true;
        else
            return false;
    }
    static public function utf8_to_cp1251($t) {
        return iconv("UTF-8", "CP1251", $t);
    }
    static public function cp1251_to_utf8($t) {
        return iconv("CP1251", "UTF-8", $t);
    }
    static public function UTF($var, $action = 'ENCODE') {

        $newVar = array();

        if (is_array($var)) {

            foreach ($var as $k => $v)
                $newVar[self::UTF($k, $action)] = self::UTF($v, $action);
        } else {
            if ($action == 'ENCODE') {
                $newVar = (!self::is_utf($var)) ? iconv($_SERVER[cmsEncodingCP], "UTF-8", $var) : $var;
            } else {
                $newVar = (self::is_utf($var)) ? iconv("UTF-8", $_SERVER[cmsEncodingCP], $var) : $var;
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
    static public function Json_encode($var, $removeEntities = true) {

        if ($_SERVER [cmsEncoding] != "UTF-8")
            $var = self::UTF_encode($var);
        $json = json_encode($var);

        if ($removeEntities)
            $json = self::UTF_entityDecode($json);

        return $json;
    }
    // FROM JSON TO ARRAY
    static public function Json_decode($json) {
	
	if ($_SERVER [cmsEncoding] != "UTF-8")
		$json = self::UTF_encode ( $json );
	
	$var = json_decode ( $json, true );
	
	if ($_SERVER [cmsEncoding] != "UTF-8")
		$var = self::UTF_decode ( $var );
	
	return $var;

}
    static public function UTF_entityEncode($text) {

        $res = "";

        foreach (mb_splitChars($text) as $t) {

            $ord = mb_ord($t);

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
        return $encode ? iconv("UTF-8", $_SERVER[cmsEncodingCP], $str) : $str;
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

    static public function Escape($string, $reverse=false) {

        $encTable = array(// ���� ������ ��� WINDOWS
            "�" => "%u0410", "�" => "%u0411", "�" => "%u0412", "�" => "%u0413",
            "�" => "%u0414", "�" => "%u0415", "�" => "%u0401", "�" => "%u0416",
            "�" => "%u0417", "�" => "%u0418", "�" => "%u0419", "�" => "%u041A",
            "�" => "%u041B", "�" => "%u041C", "�" => "%u041D", "�" => "%u041E",
            "�" => "%u041F", "�" => "%u0420", "�" => "%u0421", "�" => "%u0422",
            "�" => "%u0423", "�" => "%u0424", "�" => "%u0425", "�" => "%u0426",
            "�" => "%u0427", "�" => "%u0428", "�" => "%u0429", "�" => "%u042A",
            "�" => "%u042B", "�" => "%u042C", "�" => "%u042D", "�" => "%u042E",
            "�" => "%u042F", "�" => "%u0430", "�" => "%u0431", "�" => "%u0432",
            "�" => "%u0433", "�" => "%u0434", "�" => "%u0435", "�" => "%u0451",
            "�" => "%u0436", "�" => "%u0437", "�" => "%u0438", "�" => "%u0439",
            "�" => "%u043A", "�" => "%u043B", "�" => "%u043C", "�" => "%u043D",
            "�" => "%u043E", "�" => "%u043F", "�" => "%u0440", "�" => "%u0441",
            "�" => "%u0442", "�" => "%u0443", "�" => "%u0444", "�" => "%u0445",
            "�" => "%u0446", "�" => "%u0447", "�" => "%u0448", "�" => "%u0449",
            "�" => "%u044A", "�" => "%u044B", "�" => "%u044C", "�" => "%u044D",
            "�" => "%u044E", "�" => "%u044F"
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

        $str = preg_replace('/%u([a-z0-9]{4})/si', '\u$1', $str);
        $str = preg_replace('/%([a-z0-9]{2})/si', '\u00$1', $str);

        return cmsUTF_entityDecode($str, false);
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

}

?>