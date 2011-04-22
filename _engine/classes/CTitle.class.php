<?

class CTitle {

    private static $sections = array();

    static public function addSection($title_section) {
        self::$sections[] = $title_section;
    }

    static public function get() {
        return join(" | ", self::$sections);
    }

    static public function setTitle($title) {
        self::$sections = null;
        self::$sections[] = $title;
    }

}

?>