<?
class birthdays extends lego_abstract {
	// ����������� ���������� ��� ������
	public function getDir(){ return __DIR__; }
	public function __construct($name=false) {
		parent::__construct($name);
	}
	
	public function action_index() {
		$m = new birthdays_model();
		Output::assign('birthdays',$m->getToday());
		return $this->fetch("birthdays.tpl");
	}
} 

?>