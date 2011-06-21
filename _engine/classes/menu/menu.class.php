<?

class Menu extends lego_abstract {

    protected $items;
    protected $parent;
	
    public function getDir(){ return __DIR__; }
    public function __construct($parent,$name=false) {
            parent::__construct($name);
    $this->items = array();
    $this->parent = $parent;
    }

    public function add($type, $text, $checkright=true, $noajax=false) {
        array_push($this->items, array(
            "type" => $type,
            "text" => $text,
            "noajax" => $noajax,
            "picture" => '',
            "right" => $checkright,
                )
        );
            
    }

    public function adds($arr) {
        foreach ($arr as $item)
            array_push($this->items, $item);
    }

    public function add_newline() {
        array_push($this->items, array("type" => "newline",));
    }

    public function action_index() {
        $menuitems = "";
                $fkey = 0;
        foreach ($this->items as $item) {
            $text = $type = $noajax = $picture = $right = '';
            extract($item);
            //echo $type."_".$right;
            if ($this->parent) {
                $righttype = get_class($this->parent) . '_' . $type;
            } else {
                $righttype = $type;
            }
            if ($right and !Auth::getInstance()->getRights($righttype,'view'))
                continue;

            if ($type == "newline") {
                //$menuitems .= "</tr><tr>";
            } else {
                $uri = new UriConstructor();
                $uri->clear();
                Output::assign('hotkey', $fkey++<11?"Ctrl + f{$fkey }":"");
                Output::assign('text',hypher::addhypher($text));
                Output::assign('ajax',($item[noajax]?'':"data-silent='{$this->parent->getMainTarget()}' legotarget='{$this->parent->getName()}'"));
                Output::assign('type',$type);
                Output::assign('url',$uri->set($this->parent->getName(),$type)->url());
                Output::assign('selected',($this->parent->getAction()==$type?" menu-item-sel":""));
                Output::assign('picture', (empty($picture) ? "" : "style='background-image: URL(\"/picture/{$picture}\");'"));
                $menuitems .= $this->fetch('menu_item.tpl');
            }
        }
        Output::assign('menuitems',$menuitems);
        return $this->fetch('menu.tpl');
    }
}

?>