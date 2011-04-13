<?
class adminhere extends lego_abstract {
	private $m;
	// обязательно определять для модуля
	public function getDir(){ return __DIR__; }
	public function __construct($name=false) {
		parent::__construct($name);
	}
	
	public function init() {
		$this->m = new adminhere_model();
	}
	
	public function action_index() {
		Output::assign('adminlink',$this->actUri('userwin')->url());
		Output::assign('users','');
		Output::assign('divstyle','div {opacity:1;}');
		if ($this->m->yes()) {
			return $this->fetch('adminhere.tpl');
		}
		else 
			return "";
	}
	public function action_userwin() {
		Output::assign('divstyle','div {opacity:1;}');
		Output::assign('users',$this->m->userwin());
		return $this->fetch('adminhere.tpl');
	}
} 

?>