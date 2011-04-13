<?php
abstract class views {
    protected $dir;
    private $name;
    protected $owner;

    public function __construct($owner=null,$name = false) {
        if (!$name)
            $name = get_class($this);
        $this->name = $name;
        $this->dir = $this->getDir();
        $this->owner = $owner;
    }

    abstract public function getDir();

    public function fetch($template) {
        profiler::add("Выполнение", $this->name . ": начало отрисовки");
        $templatedir = Output::getTemplateCompiler()->getTemplateDir();
        Output::getTemplateCompiler()->setTemplateDir($this->dir);
        $content = Output::fetch($template);
        Output::getTemplateCompiler()->setTemplateDir($templatedir);
        profiler::add("Выполнение", $this->name . ": конец отрисовки");
        return $content;
    }
}

?>
