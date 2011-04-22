<?
class driver_smarty implements ITemplateCompiler{
	private $tpl;
	function __construct(){
		$this->tpl = new Smarty();
	}
	
    public function assign($varname, $value){
        $this->tpl->assign($varname, $value);
    }
    
    public function fetch($resource_name){
        $ret = $this->tpl->fetch($resource_name);
        return $ret;
    }
    
    public function setTemplateDir($template_dir){
    	$this->tpl->template_dir = $template_dir;
	}
	
    public function setCompileDir($compile_dir){
    	$this->tpl->compile_dir = $compile_dir;
	}
    
    public function getTemplateDir(){
    	return $this->tpl->template_dir;
	}
}

?>