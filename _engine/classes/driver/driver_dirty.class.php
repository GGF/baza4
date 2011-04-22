<?
class driver_dirty implements ITemplateCompiler{
	private $tpl;
	function __construct(){
		$this->tpl = new dirtytpl();
	}
	
    public function assign($varname, $value){
        $this->tpl->assign($varname,$value);
    }
    
    public function fetch($resource_name){
        $ret = $this->tpl->fetch($resource_name);
        return $ret;
    }
    
    public function setTemplateDir($template_dir){
    	$this->tpl->setTemplateDir($template_dir);
	}
    
    public function setCompileDir($compile_dir){
    	$this->tpl->setCompileDir($compile_dir);
	}
	
    public function getTemplateDir(){
    	return $this->tpl->getTemplateDir();
	}
}

?>