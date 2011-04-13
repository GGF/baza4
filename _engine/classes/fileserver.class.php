<?

/*
 * Перекодирует файловые ссылки в нужные
 * $filelink имеет вид z:\dir\file.ext
 */

class fileserver {

    static function sharefilelink($filelink) {
        $filelink = str_replace(SHARE_ROOT_DIR, "", $filelink);
        $filelink = str_replace(":", "", str_replace("\\", "/", $filelink));
        $filelink = multibyte::UTF_decode("file://" . NETBIOS_SERVERNAME . "/{$filelink}");
        return $filelink;
    }

    static function serverfilelink($filelink) {
        return multibyte::UTF_encode(SHARE_ROOT_DIR . str_replace(":", "", str_replace("\\", "/", $filelink)));
    }

    static function removeOSsimbols($filename) {
        // для удаления из имен заказов спецсимволов ОС
        return str_replace("'", "-", str_replace("`", "-", str_replace("?", "-", str_replace(":", "-", str_replace("\'", "-", str_replace("\"", "-", str_replace("*", "-", str_replace("/", "-", str_replace("\\", "-", $filename)))))))));
    }

    static function createdironserver($filelink) {
        list($disk, $path) = explode(":", $filelink);
        $serpath = SHARE_ROOT_DIR . strtolower($disk) . "/";
        $path = str_replace("\\\\", "\\", $path);
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

    public function savefile($filename, $content) {
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