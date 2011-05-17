<?

abstract class JsCSS implements IJsCSS {

    static protected $all_jscss = array();
    protected $dir;

    abstract public function getDir();

    static public function staticConsturct() {
        
    }

    public function __construct() {
        $this->dir = $this->getDir();
        self::$all_jscss[] = $this;
    }

    private function getVersion() {
        return 0;
    }

    public function getWebDir($dir=false) {
        $dir = $dir ? $dir : str_replace('\\', '/', $this->dir);
        return str_ireplace($_SERVER['DOCUMENT_ROOT'], "", $dir);
    }

    public function getJavascripts() {
        return self::getDirDeep($this->getDir() . "/js/", "/(\.js|\.js\.php)$/i", true);
//        $js = array();
//        $h = @opendir($this->getDir() . "/js");
//        while ($file = @readdir($h)) {
//            if ($file{0} == '.')
//                continue;
//            if (preg_match("/(\.js|\.js\.php)$/i", $file))
//                $js[] = $this->getWebDir() . '/js/' . $file;
//        }
//        return $js;
    }

    public function getStylesheets() {
        return self::getDirDeep($this->getDir() . "/view/css/", "/(\.css|\.css\.php)$/i", true);
//        $css = array();
//        $dir = @opendir($this->getDir() . "/view/css");
//        while ($file = @readdir($dir))
//            if (preg_match("/(\.css|\.css\.php)$/i", $file))
//                $css[] = $this->getWebDir() . "/view/css/" . $file;
//        return $css;
    }

    public function getHeaderBlock() {
        $csses = $this->getStylesheets();
        $jses = $this->getJavascripts();
        $ret = "";
        foreach ($csses as $one) {
            $one = $one{0}=='/'?$one:'/'.$one;
            $ret .= "<style media='all' type='text/css' >@import url({$one}?{$this->getVersion()});</style> \n";
        }
        foreach ($jses as $one) {
            $one = $one{0}=='/'?$one:'/'.$one;
            $ret .= "<script type='text/javascript' src='{$one}?{$this->getVersion()}'></script>\n";
        }
        return $ret;
    }

    public function getAllHeaderBlock() {
        $csses = $this->getAllStylesheets();
        $jses = $this->getAllJavascripts();
        $ret = "";
        foreach ($csses as $one) {
            $one = $one{0}=='/'?$one:'/'.$one;
            $ret .= "<style media='all' type='text/css' >@import url({$one}?{$this->getVersion()});</style> \n";
        }
        foreach ($jses as $one) {
            $one = $one{0}=='/'?$one:'/'.$one;
            $ret .= "<script type='text/javascript' src='{$one}?{$this->getVersion()}'></script>\n";
        }
        return $ret;
    }

    static public function getAllJavascripts() {
        $js = array();
        $js[] = self::getWebDir(__DIR__) . '/js/jquery.js';
        $js[] = self::getWebDir(__DIR__) . '/js/log.js';
        foreach (self::$all_jscss as $one) {
            $js = array_merge($js, $one->getJavascripts());
        }
        $js = array_unique($js);
        if (!$_SERVER[debug][noCache][js]) {
            $js = array(cache::buildScript($js, 'js'));
        }
        return $js;
    }

    static public function getAllStylesheets() {
        $css = array();
        foreach (self::$all_jscss as $one) {
            $css = array_merge($css, $one->getStylesheets());
        }
        $css = array_unique($css);
        if (!$_SERVER[debug][noCache][css]) {
            $css = array(cache::buildScript($css, 'css'));
        }
        return $css;
    }

    static public function getDirDeep($dir=false, $mask=false, $webdir=false) {
        if (!$dir)
            $dir = __DIR__;
        if ($dir{strlen($dir) - 1} != '/')
            $dir .= '/';
        $files = array();
        $h = @opendir($dir);
        while ($filename = @readdir($h)) {
            if ($filename{0} == '.')
                continue;
            if (is_dir($dir . "{$filename}/"))
                $files = array_merge($files, self::getDirDeep($dir . "{$filename}/", $mask, $webdir));
            elseif (!$mask || preg_match($mask, $filename)) {
                if (!$webdir)
                    $files[] = $dir . "{$filename}";
                else
                    $files[] = str_ireplace($_SERVER['DOCUMENT_ROOT'], "", $dir . "{$filename}");
            }
        }
        return $files;
    }

    public function getDeepAllHeaderBlock($dir=false) {
        $csses = $this->getAllStylesheets();
        $csses = $csses + self::getDirDeep($dir ? $dir : $this->getDir(),"/(\.css|\.css\.php)$/i",true);
        $csses = array_unique($csses);
        $jses = $this->getAllJavascripts();
        $jses = $jses + self::getDirDeep($dir ? $dir : $this->getDir(), "/(\.js|\.js\.php)$/i", true);
        $jses = array_unique($jses);
        $ret = "";
        foreach ($csses as $one)
            $ret .= "<style media='all' type='text/css' >@import url(/{$one}?{$this->getVersion()});</style> \n";
        foreach ($jses as $one)
            $ret .= "<script type='text/javascript' src='/{$one}?{$this->getVersion()}'></script>\n";
        return $ret;
    }

}

JsCSS::staticConsturct();
?>