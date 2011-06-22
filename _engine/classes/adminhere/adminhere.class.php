<?

class adminhere extends lego_abstract {

    private $m;

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function __construct($name=false) {
        parent::__construct($name);
    }

    public function init() {
        $this->m = new adminhere_model();
    }

    public function action_index() {
        if (Auth::getInstance()->getUser('id') == 1) // только админу
            Output::assign('adminlink', $this->actUri('userwin')->url());
        Output::assign('users', '');
        if ($this->m->yes()) {
            return $this->fetch('adminhere.tpl');
        }
        else
            return "";
    }

    public function action_userwin() {
        Output::assign('users', $this->m->userwin());
        return $this->fetch('adminhere.tpl');
    }

}
?>