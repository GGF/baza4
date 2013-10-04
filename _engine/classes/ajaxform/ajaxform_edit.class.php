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
        $this->form->initConfirm();
    }

    public function addField($label, $name, $type) {
        $this->fields[$name] = new ajaxform_edit_field($label, $name, $type);
        return $this;
    }

    public function addFieldAsArray($field, $unid=false) {
        if ($field[type]==AJAXFORM_TYPE_CHECKBOX)
            $this->addField('', $field["name"], $field["type"]);
        else 
            $this->addField($field["label"], $field["name"], $field["type"]);
        $nunid = $unid;
        if ($field["type"] == AJAXFORM_TYPE_TEXT or $field["type"] == AJAXFORM_TYPE_SELECT) {
            $nunid = uniqid('fld');
            $field["options"]["html"] .= " " . $field["type"] . " fieldid='" . $unid . "' fieldnext='" . $nunid . "'";
            array_push($this->unids, $unid);
        }
        $this->form->addFields(array($field));
        if ($field["obligatory"])
            $this->form->addObligatory($field["name"]);
        if ($field["format"])
            $this->form->addFormat($field["name"], $field["format"]["type"], $field["format"]["pregPattern"]);
        if ($field["check"])
            $this->form->addChecker($field["name"], $field["check"]["type"], $field["check"]["pregPattern"], $field["check"]["pregReplace"]);
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
        $out .= "<div id=editformtable><div class=editformtable>";
        $count = 0;
        foreach ($this->fields as $field) {
            $out .= $this->getFieldOut($field);
            if ($count++ > 9) {
                $count = 0;
                $out .= "</div><div class=editformtable>";
            }
        }
        $out .= "</div><div style='float:none;clear:both;'></div></div>";
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
            $out .= "<div id='tr{$field->name}'>";
            $out .= "<div style='float: right;' class=nobreak>" . $this->form->add($field->name) . "</div><label class=nobreak>{$field->label}:</label><div style='float:none;clear:both;'></div></div>";
        } else {
            $out .= "<div class='hidden'>" . $this->form->add($field->name)."</div>";
        }
        return $out;
    }

}

?>