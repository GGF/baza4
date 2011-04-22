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

    public function add($type, $text, $checkright=true, $link='') {
        array_push($this->items, array(
            "type" => $type,
            "text" => $text,
            "link" => $link,
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
            $text = $type = $link = $picture = $right = '';
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
                $menuitems .= "<li class='menu-item_outerWrap'>" .
                "<a " . ($fkey++<11?"hotkey='Ctrl + f{$fkey }' ":" ") .
                "title='{$text}' " .
                "data-silent='{$this->parent->getMainTarget()}' legotarget='{$this->parent->getName()}'" .
//                "href='" . $this->parent->actUri("{$type}")->url() . "'" .
                "href='" . $uri->set($this->parent->getName(),$type)->url() . "'" .
                "class='menu-item' id='{$type}'>" .
                "<div id='{$type}' class='menu-item" .
                ($this->parent->getAction()==$type?" menu-item-sel":"") .
                "' " . 
                (empty($picture) ? "" : "style='background-image: URL(\"/picture/{$picture}\");'") .
                "><span class='menutext'>" . hypher::addhypher($text) . "</span>" .
                "</div>" .
                "</a>" .
                "</li>";
            }
        }
        Output::assign('menuitems',$menuitems);
        return $this->fetch('menu.tpl');
    }
}

?>