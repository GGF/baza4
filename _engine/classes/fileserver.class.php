<?php

/**
 * Перекодирует файловые ссылки в нужные. Так уж получилось, что имена не в камелстиле. TODO:Refactor later
 * $filelink имеет вид z:\dir\file.ext
 */

class fileserver {

    /**
     * нормализует имена файлов
     * @param string $filename - имя файла
     * @return string - то же имя файла, но с нормальным количеством разных слешей
     */
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

    /**
     * @param string $filename - имя файла
     * @return string - то же имя файла в виде ссылки на шару
     */
    static public function sharefilelink($filename) {
        $filename = str_replace(SHARE_ROOT_DIR, "", $filename);
        $filename = self::normalizefile(str_replace(":", "", str_replace("\\", "/", $filename)));
        // огнелис хочет три а не две палки 
        $filename = multibyte::UTF_decode("file:///" . NETBIOS_SERVERNAME . "/{$filename}");
        return $filename;
    }

    /**
     * @param string $filename - имя файла
     * @return string - то же имя файла в виде пути  на сервере
     */
    static public function serverfilelink($filename) {
        return multibyte::UTF_encode(self::normalizefile(SHARE_ROOT_DIR . str_replace(":", "", str_replace("\\", "/", $filename))));
    }

    /**
     * @param string $filename - имя файла
     * @return string - то же имя файла без невозможных в винде символов
     */
    static public function removeOSsimbols($filename) {
        // для удаления из имен заказов спецсимволов ОС
        return str_replace("'", "-", str_replace("`", "-", str_replace("?", "-", str_replace(":", "-", str_replace("\'", "-", str_replace("\"", "-", str_replace("*", "-", str_replace("/", "-", str_replace("\\", "-", $filename)))))))));
    }

    /**
     * Проверка каталога на пустость
     * @param string
     * @return bool
     */
    static public function is_dir_empty($dir) {
        return (count(scandir($dir)) == 2);
      }
      
    /**
     * Создает каталог на сервере по переданному файлу, возвращает имя файла в этом каталоге относительно файловой системы сервера
     * @param string $filename - имя файла в любом виде, то есть на сетевом диске z: или /home/common/z
     * @return string - имя файла на в файловой системе 
     */
    static public function createdironserver($filelink) {
        $filelink = self::normalizefile($filelink);
        $disk='';
        $path='';
        if (strstr(':',$filelink))
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
                    chmod($dir, 0775); //security hole
                }
            }
        }
        return $dir . multibyte::UTF_encode($filename);
    }

    /**
     * вызов создания каталога, потому что он возвращает имя на сервере
     * @param string $filename - имя файла в любом виде, то есть на сетевом диске z: или /home/common/z
     * @return string - имя файла на в файловой системе 
     */
    static public function getFileOnServer($filename) {
        return fileserver::createdironserver($filename);
    }

    /**
     * Записывает в файл на сервере переданные данные
     * @param string $filename - ну очевидно же, хотя тут бы надо написать в каком виде, то есть путь относительно сервера или путь с виндовым диском
     * @param mixed $content - массив или строка которые нужно сохранить в файле
     * @return bool - удачность операции
     */
    static public function savefile($filename, $content) {
        // записать файл
        $file = @fopen($filename, "w");
        if ($file) {
            if (is_array($content)) {
                // если передали набор данных сохраним переменные в формате key|var
                foreach ($content as $key => $value) {
                    // сохраним все переменные в файл txt, чтоб xls потом сам оттуда забрал
                    // использем в качестве разделителя вертикальную черту, скорее всего её не будет в данных
                    // если же, паче чаяния, она там окажется придется использовать тройную, скажем, и
                    //  переписывать скрипты в xls файле
                    fwrite($file, sprintf("%s|%s\n",multibyte::utf8_to_cp1251($key),multibyte::utf8_to_cp1251(html_entity_decode($value))));
                }
            } else {
                // иначе просто запишем текст
                fwrite($file, $content);
            }
            fclose($file);
            chmod($filename, 0777); //очевидная дырка в безопастности
            return true;
        } else {
            return false;
        }
    }

}

?>