<?php
/**
 * Класс для представления данных в виде таблицы
 */
class sqltable_view extends views {

    // 
    /**
     * Возвращет текущий каталог для подключения прочих файлов j,]trnf
     * обязательно определять для модуля
     * @return string
     */
    public function getDir() {
        return __DIR__;
    }

    /**
     * Получает данные из базы в массиве и возвращает html для отображения записи.
     * Перегружаемая.
     * @param array $rec - может быть масивом полей формы, а может строкой для отображения сообщения
     * @return string
     */
    public function showrec($rec) {
        if (!is_array($rec)) {
            return $rec; // просто вернем текст, потомки могут вызывать функцию, поэтому возможно такое поведение
        }
        // вытащить из массива в просто переменные
        extract($rec);
        $form = new ajaxform_edit($this->owner->getName(), $action);
        $form->init($edit);
        if ($rec['files']['file']) {
            foreach ($rec['files']['file'] as $file) {
                $values[$file['id']] = basename($file['file_link']);
                $value[$file['id']] = 1;
            }
            array_push($fields, array(
                "type" => AJAXFORM_TYPE_CHECKBOXES,
                "name" => "curfile",
                "label" => 'Текущие файлы:',
                "value" => $value,
                "values" => $values,
                    //"options" => array("html" => "readonly",),
            ));
        }
        $form->addFields($fields);
        $out = $form->getOutput();
        if ($rec['files']) {
            $out .= $this->addFileButton();
        }
        $out .= $this->addComments($edit,$rec['maintable']);
        return $out;
    }

    /**
     * Возвращает таблицу в html
     * @return string
     */
    public function show() {
        $out = $this->get_header();
        $out .= $this->get_table();
        $out .= $this->get_footer();
        return $out;
    }

    /**
     * Возвращает заголовок таблицы
     * @return string
     */
    private function get_header() {
        $ret = "<table class='listtable lego' id='{$this->owner->tid}' " .
                " name='{$this->owner->type}' loaded='{$this->owner->uri()->url()}'" .
                ">";
        $ret .= "<thead>";
        if ($this->owner->title != '') {
            $ret .= "<tr><th colspan=100 align=center>{$this->owner->title}";
        }
        $ret .= "<tr>";
        $cols = $this->owner->cols;
        if (!empty($cols)) {
            $ret .= $this->get_header_cols($cols);
        }

        if ($this->owner->edit) {
            $ret .= "<th>&nbsp;";
        }
        if ($this->owner->del) {
            $ret .= "<th>&nbsp;";
        }
        if ($this->owner->buttons) {
            $ret .= $this->get_header_buttons();
        }
        $ret .= "<tbody>";
        return $ret;
    }

    /**
     * Разбиваем заголовок на подфункции, а то длинный
     * Тут получаем колонки
     * @param array $cols - колонки
     * @return string
     */
    private function get_header_cols($cols) {
        $ret = '';
        $cfind = urlencode($this->owner->find);
        $cidstr = urlencode($this->owner->idstr);

        reset($cols);

        foreach ($cols as $key => $val) {
            if (is_array($val)) {
                $title = empty($val['title'])?$val[1]:$val['title'];
                $shortname   = empty($val['short'])?$val[0]:$val['short'];
                $nosort = empty($val['nosort'])?$val[2]:$val['nosort'];
            } else {
                $title=$val;
                $shortname = $val;
            }
            if ($this->owner->buttons) {
                $cord = ($this->owner->order == $key ? ($key . " DESC") : $key);
                $url = $this->owner->actUri('index', $this->owner->all, $cord, $cfind, $cidstr)->url();
                $ret .= "<th>" .
                        (($key == 'check' or $key == "№" or (isset($nosort) and $nosort) ) ? "<label title='{$title}'>".(new phpHypher('conf/hyph_ru_RU.conf'))->hyphenate($shortname,'UTF-8')."</label>" :
                                "<a " .
                                "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='replace' " .
                                "href='{$url}' title='{$title}' " .
                                ">" .
                                (new phpHypher('conf/hyph_ru_RU.conf'))->hyphenate($shortname,'UTF-8') .
                                (($key == 'check' or $key == "№") ? "" : ($this->owner->order == $key ? "&darr;" : (($this->owner->order == $key . ' DESC') ? "&uarr;" : ""))) .
                                "</a>");
            } else {
                $ret .= "<th>" . $shortname;
            }
        }
        return $ret;
    }
    
    
    /**
     * Возвращает кнопки "редактировать" и "удалить"
     * @return string
     */
    private function get_header_buttons() {
        $ret ='';
        $cfind = urlencode($this->owner->find);
        $cidstr = urlencode($this->owner->idstr);
        $ccord = urlencode($this->owner->order);

        $url = $this->owner->actUri('index', !$this->owner->all)->url();
        $ret .= "<tr><td colspan=100 class='buttons'><div id='buttons'>" .
                "<input class='" .
                (($this->owner->addbutton && $this->owner->edit) ? "half" : "full") .
                "button noprint' type=button " .
                "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='replace' " .
                "href='{$url}' " .
                "value='" . ($this->owner->all ? "Последние 20" : "Все") . "' " .
                "title='" . ($this->owner->all ? "Последние 20" : "Все") . "' " .
                "id=allbutton >";

        if ($this->owner->addbutton && $this->owner->edit) {
            $ret .= "<input hotkey='Ctrl + e' class='halfbutton noprint' type='button' " .
                    "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='append' " .
                    "href='{$this->owner->actUri('add')->url()}' " .
                    "value='Добавить' title='Добавить' id=addbutton >";
        }
        $ret .= "</div>"; // закроем див чтобы потом добавить кнопку копирования, для всех и всегда
        // добавим невидимую кнопку копировать в Excel
        // Из-за безопасности копировать в буффер можно только если пользователь чтото нажал, потому кнопку пидется делать видимой и без хоткея
        $ret .= "<input  class='noprint'  alt='Скопировать в Excel' title='Скопировать в Excel' type='button' " .
                "id='copytablebutton' />";
        $ret .= "</td></tr>"; // закроем строчку кнопок
        $findurl = $this->owner->actUri('index', $this->owner->all, $ccord, $cfind, $cidstr)->url();
        if ($this->owner->findbutton) {
            $ret .= "<tr><td colspan=100 class='search'>" .
                    "<form name='find' method='post' action='{$findurl}'>" .
                    "<input type=text class='find noprint' " .
                    "placeholder='" . (!empty($this->owner->find) ? $this->owner->find : "Искать...") . "' " .
                    "name='find' id='findtext{$this->owner->tid}' " .
                    ">" .
                    "</form>" .
                    "";
        }
        return $ret;
    }


    /**
     * Возвращиет строки данных в html
     * @return string
     */
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
            $cfind = urlencode($this->owner->find);
            $cidstr = urlencode($this->owner->idstr);
            $ccord = urlencode($this->owner->order);
            $urli = $this->owner->actUri('index', $this->owner->all, $ccord, $cfind, $cidstr);
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

                if (!($i++ % 2)) {
                    $out .= "<tr class='chettr' parent='{$this->owner->tid}' id='{$trid}'" .
                            " prev='{$prtrid}' next='{$netrid}'>";
                } else {
                    $out .= "<tr class='nechettr' parent='{$this->owner->tid}' id='{$trid}'" .
                            " prev='$prtrid' next='$netrid'>";
                }
                $rs["№"] = $i;

                $url = $urli->set($this->owner->getName(), 'open', $rs['id'])->url();
                $link = "<a alt='Раскрыть' title='Раскрыть' " .
                        "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='append' " .
                        "href='{$url}' id='showlink'><div class='fullwidth'>";
                $linkend = "</div></a>";
                if (isset($rs["file_link"])) 
                    $rs["file_link"] = substr($rs["file_link"], strrpos($rs["file_link"], "\\") + 1);
                $delstr = '';
                reset($cols);
                foreach ($cols as $key => $val) {
                    $disablelink = (bool) strstr($rs["$key"], 'href=');
                    $disablelink |= ( $key == 'check' or $key == "№");
                    $out .= "<td>" . ($disablelink ? "" : $link ) .
                            (empty($rs["$key"]) ? "&nbsp;" : $rs["$key"]) .
                            ($disablelink ? "" : $linkend);
                    $delstr .= $rs["$key"] . ' - ';
                }
                if ($this->owner->edit) {
                    $out .= "<td class='edit'><a title='Редактировать'" .
                            "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='append' " .
                            "href='{$this->owner->actUri('edit', $rs['id'])->url()}' " .
                            "id=editlink>&nbsp;</a>";
                }
                if ($this->owner->del) {
                    $delstr = addslashes(htmlentities(preg_replace("/&#?[a-z0-9]+;/i","",$delstr)));
                    $out .= "<td class='del'><a title='Удалить' " .
                            "data-need-confirm='Удалить {$delstr}' " .
                            "data-silent='#{$this->owner->tid}' legotarget='{$this->owner->getName()}' data-silent-action='replace' " .
                            "href='{$this->owner->actUri('delete', $rs['id'], false, $delstr)->url()}' " .
                            "id=dellink>&nbsp;</a><div></div>";
                }
            }
        }
        $this->owner->lasttrid = $trid;
        return $out;
    }

    /**
     * Возвращает закрытие таблицы html
     * @return string - HTML
     */
    private function get_footer() {
        $out = '';
        $out .= "</table>";
        if (!empty($this->owner->firsttrid)) {
            $out .= "<script>firsttr = '{$this->owner->firsttrid}';curtr = firsttr;lasttr = '{$this->owner->lasttrid}';</script>";
        }
        $out .="<applet code='zaompp.bazaApplet' archive='".str_ireplace($_SERVER['DOCUMENT_ROOT'], "", __DIR__)."/BazaApplet.jar' width=1 height=1 name='bazaapplet'><param name='cmd' value='cmd.exe /c'><!--Applet for open files and clipboard (if you see it java-plugin not started)--></applet>";
        return $out;
    }

    /**
     * Создает диалоговое окно с текстом, формой
     * @param string $message - HTML для отображения
     * @param bool $form = форма ли это, вывести другие кнопки
     */
    public function getMessage($message, $form = false) {
        if (Ajax::isAjaxRequest()) {
            $out = "<div id=dialog>{$message}</div><script>dialog_modal(" . ($form ? "false" : "true") . ");</script>";
        } else {
            Output::assign('message', $message);
            Output::assign('oklink', $this->owner->actUri('index', $this->owner->all, $this->owner->order, $this->owner->find, $this->owner->idstr)->url());
            // шаблон message.tpl не сможет быть изменен потомками
            //$out = $this->fetch(__DIR__.'message.tpl');
            $out = $this->fetch('message.tpl');
        }
        return $out;
    }

    /**
     * Обертка для вызова формы
     * @param string $formcontent - HTML of form
     */
    public function getForm($formcontent) {
        return $this->getMessage($formcontent, true);
    }

    /**
     * Вывести диалог подтверждения
     * @param string $message - HTML
     * @param string $action - действие у владельца по ОК
     * @param string $cancelaction - действие у владельца по канцель
     * @return string - HTML 
     */
    public function getConfirm($message, $action, $cancelaction = 'index') {
        $params = func_get_args();
        array_shift($params);
        array_shift($params);
        array_shift($params);
        Output::assign('message', $message);
        if ($cancelaction == 'index') {
            $params[0] = $this->owner->all;
            $params[1] = $this->owner->order;
            $params[2] = $this->owner->find;
            $params[3] = $this->owner->idstr;
        }
        Output::assign('cancellink', $this->owner->actUri($cancelaction, $params)->url());
        Output::assign('oklink', $this->owner->actUri($action, $params)->url());
        $out = $this->fetch('confirm.tpl');
        return $out;
    }

    /**
     * Return JSON from array
     * @param array $data - data from model
     * @return none - just print header and JSON
     */
    public function getJson($data) {
        //header("CONTENT-TYPE: TEXT/X-JSON; CHARSET={$_SERVER['Encoding']}");
        header("CONTENT-TYPE: APPLICATION/JSON; CHARSET={$_SERVER['Encoding']}");
        echo multibyte::Json_encode($data, true);
    }

    /**
     * Return "option" for select from data
     * @param array $data - data from model
     * @return string - HTML
     */
    public function getSelect($data) {
        $out = '';
        foreach ($data as $key => $value) {
            $out .= "<option value={$key}>{$value}</option>";
        }
        return $out;
    }

    /**
     * Show control for add files
     * @return string - HTML
     */
    public function addFileButton() {
        // тут не используем шаблон изза неправильного наследования шаблонов
        $name = $this->owner->getName();
        $out = '<a  data-silent="#editformtable" legotarget="' . $name .
                '" data-silent-action="append" href="' . $this->owner->actUri('addfilefield')->ajaxurl($name) .
                '"><input type="button" id="addfilebutton" value="Добавить файлов" ></a>';
        $out .= '<a  data-silent="#editformtable" legotarget="' . $name .
                '" data-silent-action="append" href="' . $this->owner->actUri('addfilelink')->ajaxurl($name) .
                '" id=addfilelinkbutton><input type="button" id="addfilebutton" value="Добавить линки на файлы" ></a>';
        $out .= "<script>
                $('#addfilelinkbutton').click(function(){
                    var filename=document.bazaapplet.addFile();
                    if (filename=='nullnull') return false; // это иззатого что в апплете плюсуются путь и имя, а переписывать лень
                    if(filename.substring(0,1).search(/[tzTZ]/)!= -1) {
                        $(this).attr('href',$(this).attr('href')+'&filename='+filename);
                        return true;
                    } else {
                        alert('Только на дисках Т и Z!!!');
                        return false;
                    }
                });
                </script>";
        return $out;
    }

    /**
     * Show control for add comment
     * @param int $id - ID of record
     * @param string $table - maintable for record
     * @return string - HTML
     */
    public function addComments($id,$table='') {
        $out='';
        $rec = $this->owner->model->getCommentsForId($id,$table);
        foreach ($rec['comments'] as $coment) {
            $out .= $this->showcoment($coment);
        }
        Output::assign('comments', $out);
        Output::assign('table', $rec["table"]);
        Output::assign('userid', Auth::getInstance()->getUser('id'));
        Output::assign('recordid', $rec["id"]);
        Output::assign('savecommenturl', $this->owner->actUri('savecomment')->ajaxurl($this->owner->getName()));

        $out = $this->fetch(__DIR__.'/comments.tpl');
        return $out;
    }

    /**
     * Показ коментариев
     * @param array $rec - data form model
     * @return dtring - HTML
     */
    public function showcoment($rec) {
        Output::assignAll($rec);
        Output::assign('deleteurl', $this->owner->actUri('deletecomment',$rec["id"])->ajaxurl($this->owner->getName()));
        $out = $this->fetch(__DIR__.'/coment.tpl');
        return $out;
    }
}

?>
