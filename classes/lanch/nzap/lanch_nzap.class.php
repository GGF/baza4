<?php

class lanch_nzap extends sqltable {

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    public function init() {
        parent::init();
        $this->addbutton = false;
    }

    public function action_edit($id) {
        if (!Auth::getInstance()->getRights($this->getName(),'view')) // тут можно смотреть, но редактирование(запуск) проверяется в виде(view)
            return $this->view->getMessage('Нет прав на редактирование');
        $rec = $this->model->getRecord($id);
        if ($rec[mp]) {
            $rec[mp][mplink] = $this->actUri('masterplate', $id)->url();
        }
        if ($rec[zadel]>0) { 
            $rec[zadellink] = $this->actUri('zadel', $id)->url(); // создать AJAX ссылку для кнопки
        }
        for ($i = 0; $i < $rec[parties]; $i++) {
            if ($rec[party][$i][party]) {
                $rec[party][$i][sllink] = $this->actUri('sl', $id, $rec[party][$i][party])->url();
            }
        }
        return $this->getMessage($this->view->showrec($rec));
    }

    public function action_masterplate($id) {
        $rec = $this->model->getMasterplate($id);
        return $this->view->showmplink($rec);
    }

    public function action_sl($id, $partyornumbdozap, $dozap=false) {
        $rec[posid] = $id;
        $rec[party] = $partyornumbdozap;
        $rec[dozap] = $dozap;
        $rec = $this->model->getParty($rec);
        $rec = $this->model->getSl($rec);
        $out = $this->view->createsl($rec);
        if ($out) {
            $this->model->lanchsl($rec);
            // если был использован задел, его нужно вычеркнуть
            if($dozap=="zadel") {
                $this->model->usezadel($partyornumbdozap);
            }
        }
        return $out;
    }

    public function action_dozap($lanchid) {
        return '<div class="lego">' . $this->action_sl($lanchid, $_REQUEST[dozapnumbers], true) . '</div>';
    }
    
    /**
     * Отрабатывает действие нажатия на кнопку
     * @param int $id идентификатор  позиции запуска
     * @return string Тест для  кнопки "использовать задел" 
     */
    public function action_zadel($id) {
//        $rec = $this->model->usezadel($id); // доллжна списать задел, уменьшить необходимое количество
//        return $this->view->showzadel($rec); // сдеать лист запуска, и показать текст типа
//        //"<b>Заддел использован! Отккройте плату снова!</b>";
        $zadel = $this->model->getZadelByPosintzId($id);
        // zadel буудет массивом, нужно дальше учитывать
//        return '<div class="lego">' . $this->action_sl($lanchid, $zadel, "zadel") . '</div>';
        return $this->action_sl($id, $zadel, "zadel") ;
    }

}

?>
