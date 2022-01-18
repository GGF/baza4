<?php

class lanch_mp extends sqltable {
    
    /**
     * @var lanch_mp_model
     */
    protected  $model;
    /**
     * @var lanch_mp_view
     */
    protected  $view;

    // обязательно определять для модуля
    public function getDir() {
        return __DIR__;
    }

    /**
     * Возвращает ссылку на файл мастерплаты, создавая его
     * @param int $tzposid - идентификатор позиции в техзадании
     * @param array $mpdata - массив с данными, если создается не привязанная к позиции в ТЗ ($tzposid == 0)
     * @return string - html с сылкой
     */
    public function getMP(int $tzposid = 0, $mp = array())
    {
        if ($tzposid != 0) {
            $mp = $this->model->newByTZposid($tzposid);
        }
        if (!empty($mp)) {
            $mp['filename'] = $this->view->getMPFlieName($mp);
            $rec = $this->model->createMPFile($mp);
            if ($rec['success']) {
                $link = $this->view->getMPLink($rec);
            } else {
                $link = $rec['error_string'];
            }
        }
        return $link;
    }

    /**
     * Обработка формы
     */
    public function action_processingform() {
        // хидер переключим
        ajaxform_recieve::init();
        $form = new ajaxform($this->getName());
        $form->initBackend();

        if (!$form->errors) {
            // сохранение
            // тут я по другому обработаю данные и обновлю скриптом форму на ссылку
            $mp = $form->request;
            if (empty($mp['block_id'])) {
                $alert = Lang::getString('masterboard.errors.needblock');
                $form->alert($alert);
                $form->processed();
            } else {
                $mp['mp_id'] = uniqid();
                $mp['date'] = date("Y-m-d");
                $mp['block'] = $this->model->getBlock($mp['block_id']);
                $mp['blockname'] = $mp['block']['blockname'];
                $mp['customer'] = $this->model->getCustomer($mp['customer_id']);
                $mp['customer'] = $mp['customer']['customer'];
                $link = addslashes(str_replace(PHP_EOL, '', $this->getMP(0,$mp))); // слеши потому что в скрипт вставлять
                // удачное завернение с закрытием диалога
                $form->processed("$('form#".$form->uid.'\').html(\''.$link.'\');');
            }
        } else {
            // в случае ошибок обработка без закрытия
            $form->processed('');
        }
        return '';
    }

}

?>
