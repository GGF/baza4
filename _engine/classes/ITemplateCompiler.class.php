<?
interface ITemplateCompiler{
	public function assign($var_name, $var_value);
	public function fetch($resource_name);
    public function setTemplateDir($template_dir);
    public function setCompileDir($compile_dir);
    public function getTemplateDir();
}
?>