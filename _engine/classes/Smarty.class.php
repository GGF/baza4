<?
require_once(dirname(__FILE__)."/smarty/Smarty.class.php");

class Smarty extends _Smarty {
	function __construct(){
		//parent::__construct();
		$this->assign("root", Root::getInstance());
		$this->compile_dir = 'tmp';
	}
}
?>