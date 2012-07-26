<?php
abstract class views {
    /**
     * Каталог расположения потомка, в нем хранятся шаблоны и css
     * @var type
     */
    protected $dir;
    /**
     * Имя класса, может понадобится
     * @var type
     */
    private $name;
    /**
     * Кто создавал, контроллер
     * @var type
     */
    protected $owner;

    /**
     * Конструктор
     * @param Object $owner
     * @param String $name
     */
    public function __construct($owner=null,$name = false) {
        if (!$name)
            $name = get_class($this);
        $this->name = $name;
        $this->dir = $this->getDir();
        $this->owner = $owner;
    }

    /**
     * Функция определения каталога расположения потомка абстрактного класса,
     * чаще всего возвращает __DIR__, но у потомков потомков этого абстрактного
     * класса может не переписываться и шаблоны будут браться предыдущие
     * @return String
     */
    abstract public function getDir();

    /**
     * Отрисовать по шаблону
     * @param String $template
     * @param array $date
     * @return String
     */
    public function fetch($template,$date=false) {
	// Если переданы данные, сделать обозначения
	if ($date!==false) {
	    foreach ($date as $key => $value) {
		Output::assign($key, $value);
	    }
	}
        $templatedir = Output::getTemplateCompiler()->getTemplateDir();
        Output::getTemplateCompiler()->setTemplateDir($this->dir);
        $content = Output::fetch($template);
        Output::getTemplateCompiler()->setTemplateDir($templatedir);
        return $content;
    }
}

?>
