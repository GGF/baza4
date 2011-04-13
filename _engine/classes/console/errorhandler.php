<?

/*
 * ERROR HANDLING 
 */

define("CMSBACKTRACE_RAW", true);
define("CMSBACKTRACE_PLAIN", true);

function cmsBacktrace($format = false) {

    ob_start();

    if ($format != CMSBACKTRACE_PLAIN) {
        print "<div><strong>Backtrace:</strong></div>\n";
        print "<div class='trace'>\n";
    }

    $trace = debug_backtrace();

    $n = 0;

    foreach ($trace as $f) {

        if ($f ["function"] != "cmsErrorHandler" 
                && $f ["function"] != "cmsBacktrace") {

            $n++;

            $func = "";
            isset($f ["class"]) and $func .= $f ["class"];
            //isset($f["object"])		and $func .= $f["object"];
            isset($f ["type"]) and $func .= $f ["type"];
            $func .= $f ["function"];

            $file = (isset($f ["file"]) && isset($f ["line"])) ? 
                "in {$f["file"]} on line {$f["line"]}" : "internal";

            if ($format != CMSBACKTRACE_PLAIN)
                print "		<div>";
            print "{$n}. <strong>{$func}()</strong> {$file}";
            if ($format != CMSBACKTRACE_PLAIN)
                print "</div>";
            print "\n";
        }
    }

    if ($format != CMSBACKTRACE_PLAIN) {

        print "	</div>\n";
        print "</div>\n";
    }

    if ($format == CMSBACKTRACE_RAW || $format == CMSBACKTRACE_PLAIN)
        return ob_get_clean();
    else
        cmsWarning(ob_get_clean());
}

function cmsErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
    // if not an error has been supressed with an @
    if (error_reporting() == 0 && ($errno != E_ERROR || E_USER_ERROR))
        return;
    switch ($errno) {
        case E_ERROR :
            $type = "ERROR";
            break;
        case E_WARNING :
            $type = "WARNING";
            break;
        case E_PARSE :
            $type = "PARSE ERROR";
            break;
        case E_NOTICE :
            $type = "NOTICE";
            break;
        case E_STRICT :
            $type = "STRICT ERROR";
            break;
        case E_USER_ERROR :
            $type = "ERROR";
            break;
        case E_USER_WARNING :
            $type = "WARNING";
            break;
        case E_USER_NOTICE :
            $type = "NOTICE";
            break;
        default :
            $type = "X-WARNING";
            break;
    }

    if (striStr($errmsg, "Use of undefined constant")
            || striStr($errmsg, "Undefined index")
            || striStr($errmsg, "Uninitialized string offset")
            || striStr($errmsg, "Undefined offset"))
        $type = false;
    if ($type
            && (
            (isset($_SERVER ["debug"] ["showNotices"])
            && $_SERVER ["debug"] ["showNotices"]
            && $type == "NOTICE") || $type != "NOTICE")
    ) {

        $cls = ($type == "NOTICE") ? "cmsNotice" : "cmsError";

        // ��������������� ���� ������
        // ��� ������ ������, ����� � ������ AJAX ������� �� ������� ����������
        //while (ob_get_level() > 0) { ob_end_flush(); }
        if (is_callable("cmsBacktrace")) {
            $backtrace = cmsBacktrace(CMSBACKTRACE_PLAIN);
        } else {
            ob_start();
            debug_print_backtrace();
            $backtrace = ob_get_clean();
        }

        console::getInstance()->out("\n<b>{$type}:</b> {$errmsg} in file:" .
                "<b>{$filename}</b> on line <b>{$linenum}</b>", "", "notice");
        console::getInstance()->out("<pre style='margin: 0px; padding: 0px'>" .
        "{$backtrace}</pre></div>","", "notice");

        /* echo "\n<div class='{$cls}'><b>{$type}:</b> {$errmsg} 
          in file: <b>{$filename}</b> on line <b>{$linenum}</b>.
          <pre style='margin: 0px; padding: 0px'>{$backtrace}</pre></div>";
         */
    }
}

set_error_handler("cmsErrorHandler", E_ALL);
?>