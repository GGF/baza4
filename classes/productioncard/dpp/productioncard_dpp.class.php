<?php

class productioncard_dpp extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(), 'edit')) {
            return $this->view->getMessage('Нет прав на редактирование');
        }
        $rec = $this->model->getRecord($id);
        $rec[idstr] = $this->idstr;
        $rec[isnew] = empty($id);
        $rec[edit] = $id;
        $rec[action] = $this->actUri('processingform')->ajaxurl($this->getName());
        $rec[commetnpupdatelink] = $this->actUri('commentupdate',$rec[coment_id])->ajaxurl($this->getName());
        $out = $this->view->showrec($rec);
        if ($out) {
            return $this->view->getForm($out);
        } else {
            $out = "Не радактируется!";
            return $this->view->getMessage($out);
        }
    }
    
    public function action_commentupdate($coment_id) {
        $operationid=$_REQUEST[idstr];
        $operations = multibyte::Json_decode(sqltable_model::getComment($coment_id));
        $coment_id = $operations[$operationid][comment_id];
        $out = sqltable_model::getComment($coment_id);
        $date = ajaxform::date2datepicker($operations[$operationid][date]);
        $out .= "<script>$(\"*[datepicker='1']\").val('{$date}')</script>";
        return $out;
    }
}

?>
