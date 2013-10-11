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
    public function fetch($template,$data=false) {
        // найти шаблон, он может быть и у любого потомка этого класса, они же и друг от друга наследуют
        $dir = $this->dir;
        $class = get_class($this);
        while (!file_exists($dir.'/'.$template) && !file_exists($template)) {
            $class = get_parent_class($class);
            if ($class) {
                $ref = new ReflectionClass($class);
                if (!$ref->isAbstract()) {
                    $obj = new $class;
                    $dir = $obj->getDir();
                }
            } else {
                return '';
            }
        }
	// Если переданы данные, сделать обозначения
	if ($data!==false) {
	    foreach ($data as $key => $value) {
		Output::assign($key, $value);
	    }
	}
        $templatedir = Output::getTemplateCompiler()->getTemplateDir();
        Output::getTemplateCompiler()->setTemplateDir($dir);
        $content = Output::fetch($template);
        Output::getTemplateCompiler()->setTemplateDir($templatedir);
        return $content;
    }
}

?>
