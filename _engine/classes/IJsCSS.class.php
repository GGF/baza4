<?php
interface IJsCSS {    
    public function getWebDir($dir=false);
    public function getJavascripts();
    public function getStylesheets();
    public function getHeaderBlock();
    public function getAllHeaderBlock();
    static public function getAllJavascripts();
    static public function getAllStylesheets();
}
?>