<?

class Output {

    private static $teplate_compiler = null;
    private static $templates = array();
    private static $content;

    static public function staticConstruct() {
        self::setTemplateCompiler(new driver_smarty());
    }

    static public function setTemplateCompiler(ITemplateCompiler $tc) {
        self::$teplate_compiler = $tc;
    }

    static public function getTemplateCompiler() {
        return self::$teplate_compiler;
    }

    static public function addTemplate($file_name) {
        self::$templates[] = $file_name;
    }

    static public function setTemplate($file_name) {
        self::$templates = null;
        self::$templates[] = $file_name;
    }

    static public function assign($varname, $value) {
        self::$teplate_compiler->assign($varname, $value);
    }

    static public function setContent($text) {
        self::$content = $text;
        self::assign("content", self::$content);
    }

    static public function getContent() {
        return self::$content;
    }

    static public function appendContent($text) {
        self::$content .= $text;
        self::assign("content", self::$content);
    }

    static public function fetch($resource_name) {
        $ret = "\n<!-- begin $resource_name -->\n";
        $ret = self::$teplate_compiler->fetch($resource_name);
        $ret .= "\n<!-- end $resource_name -->\n";
        return $ret;
    }

    static public function fetchAll() {
        $tpls = array_reverse(self::$templates);
        foreach ($tpls as $one) {
            $content = self::fetch($one);
            self::setContent($content);
        }
        return $content;
    }

    static public function fetchToContent($resource_name) {
        self::setContent(self::fetch($resource_name));
    }

}

Output::staticConstruct();
?>