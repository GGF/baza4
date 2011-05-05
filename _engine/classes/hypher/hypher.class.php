<?

// Подключение библиотеки.
require_once dirname(__FILE__) . '/includes/hypher.php';

class hypher {

    private static $hy_ru;

    static public function staticConstruct() {
        // Загрузка файла описания и набора правил.
        self::$hy_ru = hypher_load(dirname(__FILE__) . '/includes/hyph_ru_RU.conf');
    }

    // "перегрузка" я собираюсь вызывать только с одним словарём. зачем якаждый раз учитыват буду
    static function addhypher($text) {
        $text = iconv($_SERVER[cmsEncoding],'CP1251',$text);
        return iconv('CP1251',$_SERVER[cmsEncoding],hypher(self::$hy_ru, $text));
    }

}

hypher::staticConstruct();
?>