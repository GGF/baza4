<?

/*
 * Перекодирует файловые ссылки в нужные
 * $filelink имеет вид z:\dir\file.ext
 */

class fileserver {

    static public function normalizefile($filename) {
        $count=0;
        do {
            $filename = str_replace("\\\\", "\\", $filename, $count);
        } while ($count!=0);
        $count=0;
        do {
            $filename = str_replace("//", "/", $filename, $count);
        } while ($count!=0);
        return $filename;
    }
    static public function sharefilelink($filename) {
        $filename = str_replace(SHARE_ROOT_DIR, "", $filename);
        $filename = self::normalizefile(str_replace(":", "", str_replace("\\", "/", $filename)));
        $filename = multibyte::UTF_decode("file://" . NETBIOS_SERVERNAME . "/{$filename}");
        return $filename;
    }

    static public function serverfilelink($filename) {
        return multibyte::UTF_encode(self::normalizefile(SHARE_ROOT_DIR . str_replace(":", "", str_replace("\\", "/", $filename))));
    }

    static public function removeOSsimbols($filename) {
        // для удаления из имен заказов спецсимволов ОС
        return str_replace("'", "-", str_replace("`", "-", str_replace("?", "-", str_replace(":", "-", str_replace("\'", "-", str_replace("\"", "-", str_replace("*", "-", str_replace("/", "-", str_replace("\\", "-", $filename)))))))));
    }

    static public function createdironserver($filelink) {
        $filelink = self::normalizefile($filelink);
        list($disk, $path) = explode(":", $filelink);
        $serpath = SHARE_ROOT_DIR . strtolower($disk) . "/";
        $dirs = explode("\\", $path);
        $filename = $dirs[count($dirs) - 1];
        unset($dirs[count($dirs) - 1]);
        $dir = $serpath;
        $cats = '';
        foreach ($dirs as $cat) {
            if (!empty($cat)) {
                $cats .= $cat . "/";
                $dir = multibyte::UTF_encode($serpath . $cats);
                if (!is_dir($dir)) {
                    mkdir($dir);
                    chmod($dir, 0777);
                }
            }
        }
        return $dir . multibyte::UTF_encode($filename);
    }

    static public function savefile($filename, $content) {
        // записать файл
        $file = @fopen($filename, "w");
        if ($file) {
            fwrite($file, $content);
            fclose($file);
            chmod($filename, 0777);
            return true;
        } else {
            return false;
        }
    }

}

?>