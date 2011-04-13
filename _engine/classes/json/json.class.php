<?

class json {

// FROM ARRAY TO JSON
    static public function Json_encode($var, $removeEntities = true) {

        if ($_SERVER [Encoding] != "UTF-8")
            $var = multibyte::UTF_encode($var);
        $json = json_encode($var);

        if ($removeEntities)
            $json = multibyte::UTF_entityDecode($json);

        return $json;
    }

// FROM JSON TO ARRAY
    static public function Json_decode($json) {

        if ($_SERVER [Encoding] != "UTF-8")
            $json = multibyte::UTF_encode($json);

        $var = json_decode($json, true);

        if ($_SERVER [Encoding] != "UTF-8")
            $var = multibyte::UTF_decode($var);

        return $var;
    }

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//


    static public function Json_format($json) {

        $json = preg_split('/("[^\\\"]+")|("(.*?\\\")*?.+")|(}|{|:|\\[|]|,)/si', $json, - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $out = "";
        $s = 0;
        $listOpen = false;
        $nextVal = false;

        foreach ($json as $j) {

            $j = trim($j);

            switch ($j) {

                case "{" :
                    $s++;
                    $out .= "{\n";
                    break;
                case "}" :
                    $s--;
                    $out .= "\n" . str_repeat("  ", $s) . "}";
                    break;

                case "[" :
                    $out .= "[";
                    $listOpen = true;
                    break;
                case "]" :
                    $out .= "]";
                    $listOpen = false;
                    break;

                case "," :
                    $out .= ( !$listOpen) ? ",\n" : ", ";
                    break;

                case ":" :
                    $out .= " : ";
                    $nextVal = 2;
                    break;

                default :
                    $out .= ( $nextVal > 0 ? "" : str_repeat("  ", $s)) . $j;
                    break;
            }

            $nextVal--;
        }

        return $out;
    }

}

/*
  // ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
  //   JSON CLASS IF PHP < 5.2.0                                                                                                                                     //
  // ----------------------------------------------------------------------------------------------------------------------------------------------------------------//


  if (! is_callable ( "json_encode" )) {

  $_SERVER [json] = new Services_JSON ( SERVICES_JSON_LOOSE_TYPE );

  function json_encode($json) {
  return $_SERVER [json]->encode ( $json );
  }
  function json_decode($json) {
  return $_SERVER [json]->decode ( $json );
  }

  }

  // ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
 */
?>