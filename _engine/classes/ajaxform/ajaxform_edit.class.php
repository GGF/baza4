<?

class Field {

    public $name;
    public $label;
    public $type;

    public function __construct($label, $name, $type) {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
    }

}

class ajaxform_edit {

    public $type;
    public $fields;
    public $form;
    public $unids;
    public $unidfirst;
    public $actionurl;

    public function __construct($type,$action='') {

        $this->type = $type;
        $this->actionurl = $action;
        $this->unids = array();
    }

    public function init($edit) {

        $this->form = new ajaxform($this->type, $this->actionurl);
        $this->form->addFields(array(
            array(
                "type" => AJAXFORM_TYPE_HIDDEN,
                "name" => "edit",
                "value" => $edit,
            ),
            //array("type"		=> AJAXFORM_TYPE_CODE,	),
            array(
                "type" => AJAXFORM_TYPE_SUBMIT,
                "name" => "submit",
                "value" => "Послать",
            ),
        ));
    }

    public function addfield($label, $name, $type) {
        $this->fields[$name] = new Field($label, $name, $type);
        return $this;
    }

    public function addFields($fields) {
        $lfields = array();
        $obligatory = array();
        $formats = array();
        $checkers = array();

        $this->unidfirst = $first = $unid = uniqid('fld');
        foreach ($fields as $field) {
            $this->addfield($field["label"], $field["name"], $field["type"]);
            if ($field["type"] == AJAXFORM_TYPE_TEXT or $field["type"] == AJAXFORM_TYPE_SELECT) {
                $nunid = uniqid('fld');
                $field["options"]["html"] .= " " . $field["type"] . " fieldid='" . $unid . "' fieldnext='" . $nunid . "'";
                array_push($this->unids, $unid);
                $unid = $nunid;
            }
            $last = array_push($lfields, $field);
            // проверка на облигаторы, форматы и чекеры
            if ($field["obligatory"])
                array_push($obligatory, $field["name"]);
            if ($field["format"])
                array_push($formats, array("name" => $field["name"], "type" => $field["format"]["type"], "pregPattern" => $field["format"]["pregPattern"]));
            if ($field["check"])
                array_push($checkers, array("name" => $field["name"], "type" => $field["check"]["type"], "pregPattern" => $field["check"]["pregPattern"], "pregReplace" => $field["check"]["pregReplace"]));
        }
        $this->form->addFields($lfields);
        foreach ($obligatory as $name)
            $this->form->addObligatory($name);
        foreach ($formats as $name)
            $this->form->addFormat($name["name"], $name["type"], $name["pregPattern"]);
        foreach ($checkers as $name)
            $this->form->addChecker($name["name"], $name["type"], $name["pregPattern"], $name["pregReplace"]);
    }

    public function getOutput() {
        $out = '<div class="editdiv">';
        //$out .= $this->form->getHeaderBlock();
        $this->form->init();
        $out .= $this->form->form();
        $out .= $this->form->add("edit");
        // скрытые в начало
        foreach ($this->fields as $field) {
            if ($field->type == AJAXFORM_TYPE_HIDDEN) {
                $out .= "" . $this->form->add($field->name) . "";
            }
        }
        // остальные в таблице
        $out .= "<table>";
        foreach ($this->fields as $field) {
            if ($field->type != AJAXFORM_TYPE_HIDDEN) {
                $out .= "<tr><td><label>$field->label</label>";
                $out .= "<td>" . $this->form->add($field->name) . "";
            }
        }
        $out .= "</table>";
        //$out .= '<div >'.$this->form->add("confirm").'</div>';
        //$out .= '<div style="display:none" >' . $this->form->add("submit") . '</div>';
        $out .= $this->form->end();
        $out .= $this->form->destroy();
        $out .= '</div>'; //для прокрутки
        $out .= "<script>\$('select').combobox();</script>";
        $lastunid = '';
        foreach ($this->unids as $unid) {
            $out .= "<script>\$('[fieldid=" . $unid . "]').keyboard('enter',function(){\$('[fieldid='+\$(this).attr('fieldnext')+']').focus();});</script>";
            $lastunid = $unid;
        }
        if (!empty ($lastunid))
            $out .= "<script>\$('[fieldid={$lastunid}]').keyboard('enter',function(){\$('[fieldid={$this->unidfirst}]').focus();});</script>";
        return $out;
    }

}

//function checkbox2array($val, $key) {
//    if (strstr($key, "|")) {
//        $tmp = preg_match_all("/([^|]+)/", $key, $matches); //$key=substr($key,0,$pos)."[";
//        $matches = $matches[0];
//        $key = $matches[0];
//        global ${$key};
//        switch (count($matches)) {
//            case 2:
//                ${$key}[$matches[1]] = $val;
//                break;
//            case 3:
//                ${$key}[$matches[1]][$matches[2]] = $val;
//                break;
//            default:
//                break;
//        }
//    }
//}

?>