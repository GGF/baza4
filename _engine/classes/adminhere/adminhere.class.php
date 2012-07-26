<?
/**
 * Клаасс "АдминЗдесь" показывает что администратор системы присутствует в ней
 * И админу коечто показывает - количество пользователей и как давно они регились
 */
class adminhere extends lego_abstract {

    /**
     * Хранит  модель
     * @var adminhere_model
     */
    private $m;

    /**
     * Переодпределение абстрактного для скриптов
     * @return system
     */
    public function getDir() {
        return __DIR__;
    }

    /**
     * Конструктор переписан для интернационализации
     * @param type $name
     */
    public function __construct($name=false) {
	require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'i18n.php';
        parent::__construct($name);
    }

    /**
     * Инициализатор
     */
    public function init() {
        $this->m = new adminhere_model();
    }

    /**
     * Действие по умолчанию
     * @return string Тест выводимый в страницу
     */
    public function action_index() {
        if ($this->m->yes()) {
	    if (Auth::getInstance()->getUser('id') == 1) // только админу
		Output::assign('adminlink', $this->actUri('userwin')->url());
	    Output::assign('users', '');
	    Output::assign('linktitle', Lang::getString('Adminhere'));
            return $this->fetch('adminhere.tpl');
        }
        else
            return "";
    }

    /**
     * Действие по клику по окну "показать пользователей"
     * @return string теккст окна с данными
     */
    public function action_userwin() {
        Output::assign('users', $this->m->userwin());
        return $this->fetch('adminhere.tpl');
    }

}
?>