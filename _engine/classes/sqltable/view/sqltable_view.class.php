<?php

class sqltable_view extends views {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function showrec($rec) {
        if (empty($rec[fields])) return false; // заглушка для нерадактируемых
        $form = new ajaxform_edit($this->owner->getName(), $rec[action]);
        $form->init($rec[edit]);
        $form->addFields($rec[fields]);
        return $form->getOutput();
    }

    public function show() {
        $out = $this->get_header();
        $out .= $this->get_table();
        $out .= $this->get_footer();
        return $out;
    }

    private function get_header() {
        $ret = "<table class='listtable lego' id='{$this->owner->tid}' " .
                " name='{$this->owner->type}' loaded='{$this->owner->uri()->url()}'" .
                ">";
        $ret .= "<thead>";
        if ($this->owner->title != '') {
            $ret .= "<tr><th colspan=100 align=center>{$this->owner->title}";
        }
        $ret .= "<tr>";
        $cfind = (Ajax::isAjaxRequest() ? urldecode($this->owner->find) : $this->owner->find);
        $cfind = urlencode($this->owner->find);
        $cidstr = (Ajax::isAjaxRequest() ? urldecode($this->owner->idstr) : $this->owner->idstr);
        $ccord = urldecode($this->owner->order);
        $cols = $this->owner->cols;
        if (!empty($cols)) {
            reset($cols);

            while (list($key, $val) = each($cols)) {
                if ($this->owner->buttons) {
                    $cord = ($this->owner->order == $key ? ($key . " DESC") : $key);
                    $url = $this->owner->actUri('index', $this->owner->all, $cord, $cfind, $cidstr)->url();
                    $ret .= "<th>" .
                            (($key == 'check' or $key == "№") ? $val :
                                    "<a " .
                                    "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='replace' " .
                                    "href='{$url}' " .
                                    ">" .
                                    $val .
                                    (($key == 'check' or $key == "№") ? "" : ($this->owner->order == $key ? "&darr;" : (($this->owner->order == $key . ' DESC') ? "&uarr;" : ""))) .
                                    "</a>");
                } else {
                    $ret .= "<th>" . $val;
                }
            }
        }
        // пустое поле см.ниже)
        //echo "<th width='100%' >&nbsp;"; //получилось интересно, но нафиг
        if ($this->owner->edit) {
            $ret .= "<th>&nbsp;";
        }
        if ($this->owner->del) {
            $ret .= "<th>&nbsp;";
        }
        $ret .= "<tbody>";
        if ($this->owner->buttons) {
            $url = $this->owner->actUri('index', !$this->owner->all)->url();
            $ret .= "<tr><td colspan=100 class='buttons'>" .
                    "<input hotkey='Ctrl + a' class='" .
                    (($this->owner->addbutton && $this->owner->edit) ? "half" : "full") .
                    "button' type=button " .
                    "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='replace' " .
                    "href='{$url}' " .
                    "value='" . ($this->owner->all ? "Последние 20" : "Все") . "' " .
                    "title='" . ($this->owner->all ? "Последние 20" : "Все") . "' " .
                    "id=allbutton>";

            if ($this->owner->addbutton && $this->owner->edit)
                $ret .= "<input hotkey='Ctrl + e' class='halfbutton' type='button' " .
                        "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='append' " .
                        "href='{$this->owner->actUri('add')->url()}' " .
                        "value='Добавить' title='Добавить' id=addbutton>";
            $findurl = $this->owner->actUri('index', $this->owner->all, $ccord, $cfind, $cidstr)->url();
            $ret .= "<tr><td colspan=100 class='search'>" .
                    "<form name='find' method='post' action='{$findurl}'>" .
                    "<input type=text class='find' " .
                    "placeholder='" . (!empty($this->owner->find) ? $this->owner->find : "Искать...") . "' " .
                    "name='find' id='findtext{$this->owner->tid}' " .
                    "></form>";
        }
        return $ret;
    }

    private function get_table() {

        $i = 0;
        $this->owner->firsttrid = uniqid('tr');
        $trid = $this->owner->firsttrid;
        $prtrid = $this->owner->firsttrid;
        $netrid = $this->owner->firsttrid;
        $curr = 0;
        $out = '';

        $data = $this->owner->data;
        $cols = $this->owner->cols;

        if (!empty($data)) {
            foreach ($data as $rs) {
                $curr++;
                if ($curr == count($data)) {
                    // последний
                    $prtrid = $trid;
                    $trid = $netrid;
                    //$netrid = $netrid;
                } else {
                    // остальные проходы
                    $prtrid = $trid;
                    $trid = $netrid;
                    $netrid = uniqid('tr');
                }
                //$trid = uniqid('tr');
                if (!($i++ % 2))
                    $out .= "<tr class='chettr' parent='{$this->owner->tid}' id='{$trid}'" .
                            " prev='{$prtrid}' next='{$netrid}'>";
                else
                    $out .= "<tr class='nechettr' parent='{$this->owner->tid}' id='{$trid}'" .
                            " prev='$prtrid' next='$netrid'>";
                $rs["№"] = $i;

                $link = "<a alt='Раскрыть' title='Раскрыть' " .
                        "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='append' " .
                        "href='{$this->owner->actUri('open', $rs['id'])->url()}' " .
                        "id='showlink'><div class='fullwidth'>";
                $linkend = "</div></a>";
                $rs["file_link"] = substr($rs["file_link"],
                                strrpos($rs["file_link"], "\\") + 1);
                $delstr = '';
                reset($cols);
                while (list($key, $val) = each($cols)) {
                    $out .= "<td>" . $link .
                            (empty($rs["$key"]) ? "&nbsp;" : $rs["$key"]) .
                            $linkend;
                    $delstr .= $rs["$key"] . ' - ';
                }
                // вставим пустое поле 100% ширины
                //echo "<td width='100%' >&nbsp;"; //получилось интересно, но нафиг
                if ($this->owner->edit) {
                    $out .= "<td class='edit'><a title='Редактировать'" .
                            "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='append' " .
                            "href='{$this->owner->actUri('edit', $rs['id'])->url()}' " .
                            "id=editlink><div>&nbsp;</div></a>";
                }
                if ($this->owner->del) {
                    $out .= "<td class='del'><a title='Удалить' " .
                            "data-need-confirm='Удалить " . addslashes(htmlspecialchars($delstr)) . "' " .
                            "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='replace' " .
                            "href='{$this->owner->actUri('delete', $rs['id'], false, addslashes(htmlspecialchars($delstr)))->url()}' " .
                            "id=dellink><div>&nbsp;</div></a>";
                }
            }
        }
        $this->owner->lasttrid = $trid;
        return $out;
    }

    private function get_footer() {
        $out = '';
        if (!empty($this->owner->firsttrid)) {
            $out .= "<script>firsttr = '{$this->owner->firsttrid}';curtr = firsttr;lasttr = '{$this->owner->lasttrid}';</script>";
        }
        $out .= "</table>";
        return $out;
    }

    public function getMessage($message, $form=false) {
        if (Ajax::isAjaxRequest())
            $out = "<div id=dialog>{$message}</div><script>dialog_modal(" . ($form ? "false" : "true") . ");</script>";
        else {
            Output::assign('message', $message);
            Output::assign('oklink', $this->owner->actUri('index', $this->owner->all, $this->owner->order, $this->owner->find, $this->owner->idstr)->url());
            $out = $this->fetch('message.tpl');
        }
        return $out;
    }

    public function getForm($formcontent) {
        return $this->getMessage($formcontent, true);
    }

    public function getConfirm($message, $action, $cancelaction='index') {
        $params = func_get_args();
        array_shift($params);
        array_shift($params);
        array_shift($params);
        Output::assign('message', $message);
        if ($cancelaction=='index') {
            $params[0] = $this->owner->all;
            $params[1] = $this->owner->order;
            $params[2] = $this->owner->find;
            $params[3] = $this->owner->idstr;
        }
        Output::assign('cancellink', $this->owner->actUri($cancelaction,$params)->url());
        Output::assign('oklink', $this->owner->actUri($action, $params)->url());
        $out = $this->fetch('confirm.tpl');
        return $out;
    }

    public function getJson($data) {
        //header("CONTENT-TYPE: TEXT/X-JSON; CHARSET={$_SERVER[cmsEncoding]}");
        header("CONTENT-TYPE: APPLICATION/JSON; CHARSET={$_SERVER[cmsEncoding]}");
        echo json::Json_encode($ret, true);
    }

    public function getSelect($data) {
        $out = '';
        foreach ($data as $key => $value) {
            $out .= "<option value={$key}>{$value}</option>";
        }
        return $out;
    }

}

?>
