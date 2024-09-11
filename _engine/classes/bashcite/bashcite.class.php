<?php
class bashcite extends lego_abstract {
	private $bash;
	// обязательно определять для модуля
	public function getDir(){ return __DIR__; }
	public function __construct($name=false) {
		parent::__construct($name);
		$this->bash = new bashcite_model();
	}
	
	public function action_index($show=false) {
		Output::assign('linkshowbash',$this->actUri('showbash')->url());
		Output::assign('linkhidebash',$this->actUri('hidebash')->url());
		Output::assign('linkplusbash',$this->actUri('plusbash')->url());
		Output::assign('linkminusbash',$this->actUri('minusbash')->url());

                if (isset($_SESSION['bashcite']) ) {
					if($_SESSION['bashcite']) {
						$ret = $this->_getcite();
						Output::assign('bashcite',$ret);
						$ret = $this->fetch("bashcite.tpl");
					} else {
						$ret = $this->fetch("bashcitehide.tpl");
					}
				}
                if (Ajax::isAjaxRequest())
                    return $ret;
                else
                    return "<div id='bashcite'>{$ret}</div>";
	}
	public function action_plusbash() {
		$this->bash->plus();
		return $this->action_index();
	}
	public function action_minusbash() {
		$this->bash->minus();
		return $this->action_index();
	}
	public function action_hidebash() {
            $_SESSION['bashcite'] = false;
//                $this->_cookieSet('bashcite','hide');
            return $this->action_index();
	}
	public function action_showbash() {
            $_SESSION['bashcite'] = true;
//		$cookie = $this->_cookie("bashcite");
//		if ($cookie!= 'show')
//			$this->_cookieSet('bashcite','show');
            return $this->action_index();
	}
	private function _getcite() {
		
		return $this->bash->getRandom();
	}
} 

?>