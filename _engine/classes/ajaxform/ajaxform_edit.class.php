<?

class ajaxform_edit_field {

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

    public function __construct($type, $action='') {

        $this->type = $type;
        $this->actionurl = $action;
        $this->unids = array();
    }

    public function init($edit) {

        $this->form = new ajaxform($this->type, $this->actionurl);
        $this->form->addFields(
            array(
                array(
                    "type" => AJAXFORM_TYPE_SUBMIT,
                    "name" => "submit",
                    "value" => 'Послать',
                )
            ));
        $this->addFieldAsArray(
                array(
                    "type" => AJAXFORM_TYPE_HIDDEN,
                    "name" => "edit",
                    "value" => $edit,
        ));
    }
    
    public function restore() {
        $this->form = new ajaxform($this->type);
        $this->form->init();
    }

    public function addField($label, $name, $type) {
        $this->fields[$name] = new ajaxform_edit_field($label, $name, $type);
        return $this;
    }

    public function addFieldAsArray($field, $unid=false) {
        $this->addField($field["label"], $field["name"], $field["type"]);
        $nunid = $unid;
        if ($field["type"] == AJAXFORM_TYPE_TEXT or $field["type"] == AJAXFORM_TYPE_SELECT) {
            $nunid = uniqid('fld');
            $field["options"]["html"] .= " " . $field["type"] . " fieldid='" . $unid . "' fieldnext='" . $nunid . "'";
            array_push($this->unids, $unid);
        }
        $this->form->addFields(array($field));
        if ($field["obligatory"])
            $this->form->addObligatory($field["obligatory"]);
        if ($field["format"])
            $this->form->addFormat($field["format"]["name"], $field["format"]["type"], $field["format"]["pregPattern"]);
        if ($field["check"])
            $this->form->addChecker($field["check"]["name"], $field["check"]["type"], $field["check"]["pregPattern"], $field["check"]["pregReplace"]);
        return $nunid;
    }

    public function addFields($fields) {
        $this->unidfirst = $first = $unid = uniqid('fld');
        foreach ($fields as $field) {
            $unid = $this->addFieldAsArray($field, $unid);
        }
    }

    public function getOutput() {
        $out = '<div class="editdiv">';
        $this->form->init();
        $out .= $this->form->form();
        $out .= $this->form->add("edit");
        $out .= "<table id=editformtable>";
        foreach ($this->fields as $field) {
            $out .= $this->getFieldOut($field);
        }
        $out .= "</table>";
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
        if (!empty($lastunid))
            $out .= "<script>\$('[fieldid={$lastunid}]').keyboard('enter',function(){\$('[fieldid={$this->unidfirst}]').focus();});</script>";
        return $out;
    }

    public function getFieldOut($field) {
        $out = '';
        if ($field->type != AJAXFORM_TYPE_HIDDEN) {
            $out .= "<tr><td><label>$field->label</label>";
            $out .= "<td>" . $this->form->add($field->name);
        } else {
            $out .= "<tr><td colspan=2 class='hidden'>" . $this->form->add($field->name);
        }
        return $out;
    }

}

?>