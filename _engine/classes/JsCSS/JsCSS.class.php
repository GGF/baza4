<?php

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
        /* @var $gwddir string */
        $gwddir = $dir ? $dir : str_replace('\\', '/', $this->dir);
        return str_ireplace($_SERVER['DOCUMENT_ROOT'], "", $gwddir);
    }

    public function getJavascripts() {
        return self::getDirDeep($this->getDir() . "/js/", "/(\.js|\.js\.php)$/i", true);
    }

    public function getStylesheets() {
        return self::getDirDeep($this->getDir() . "/view/css/", "/(\.css|\.css\.php)$/i", true);
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
    
    public function getAllHeaderJavascripts() {
        $jses = $this->getAllJavascripts();
        $ret = "";
        foreach ($jses as $one) {
            $one = $one{0}=='/'?$one:'/'.$one;
            $ret .= "<script type='text/javascript' src='{$one}?{$this->getVersion()}'></script>\n";
        }
        return $ret;
    }

    public function getAllHeaderStylesheets() {
        $csses = $this->getAllStylesheets();
        $ret = "";
        foreach ($csses as $one) {
            $one = $one{0}=='/'?$one:'/'.$one;
            $ret .= "<style media='all' type='text/css' >@import url({$one}?{$this->getVersion()});</style> \n";
        }
        return $ret;
    }

    public function getAllHeaderBlock() {
        return $this->getAllHeaderStylesheets().$this->getAllHeaderJavascripts();
    }

    static public function getAllJavascripts() {
        $js = array();
        // Список нужно начинать в определенном порядке: сначала саму джейквери, потом ядро
        $js[] = str_ireplace($_SERVER['DOCUMENT_ROOT'], "", __DIR__) . '/js/jquery-3.3.1.js';
        $js[] = str_ireplace($_SERVER['DOCUMENT_ROOT'], "", __DIR__) . '/js/jquery-ui.js';
        $files = self::getDirDeep(__DIR__ . '/js/' , "/(\.js|\.js\.php)$/i", true);
        $js = array_merge($js,$files);
        foreach (self::$all_jscss as $one) {
            $js = array_merge($js, $one->getJavascripts());
        }
        $js = array_unique($js); // не работает. по разным каталогам. надо смотреть хэш файлов
        if (!$_SERVER[debug][noCache][js]) {
            $js = array(cache::buildScript($js, 'js'));
        }
        return $js;
    }

    static public function getAllStylesheets() {
        $css = array();
        $css = self::getDirDeep(__DIR__ . '/view/css/',"/(\.css|\.css\.php)$/i",true);
        foreach (self::$all_jscss as $one) {
            $css = array_merge($css, $one->getStylesheets());
        }
        $css = array_unique($css); // не работает. по разным каталогам. надо смотреть хэш файлов
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
        $nucsses = $this->getAllStylesheets();
        $nucsses += self::getDirDeep($dir ? $dir : $this->getDir(),"/(\.css|\.css\.php)$/i",true);
        $csses = array_unique($nucsses);
        $nujses = $this->getAllJavascripts();
        $nujses += self::getDirDeep($dir ? $dir : $this->getDir(), "/(\.js|\.js\.php)$/i", true);
        $jses = array_unique($nujses);
        $ret = "";
        foreach ($csses as $one) {
            $ret .= "<style media='all' type='text/css' >@import url(/{$one}?{$this->getVersion()});</style> \n";
        }
        foreach ($jses as $one) {
            $ret .= "<script type='text/javascript' src='/{$one}?{$this->getVersion()}'></script>\n";
        }
        return $ret;
    }

}

JsCSS::staticConsturct();
?>