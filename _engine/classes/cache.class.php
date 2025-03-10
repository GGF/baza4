<?php

class Cache {

    /**
     * 	@var Абсолютный URL файла с кешом
     */
    protected $url = "";
    /**
     * 	@var Реальный полный путь файла с кешом
     */
    protected $file = "";
    /**
     * 	@var Список файлов для сборки
     */
    protected $list = array();
    /**
     * 	@var Тип файлов
     */
    protected $type = "";
    /**
     * 	@var Массив опций — реально пока не используется
     */
    protected $options = array();
    /**
     * 	@var Дата обновления кеша
     */
    protected $dateCache = false;
    /**
     * 	@var Реальная дата последнего редактирования файлов из списка
     */
    protected $dateActual = false;


    protected function pathAbs($file) {

        return $_SERVER['DOCUMENT_ROOT'] . $this->pathRel($file);
        //str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
    }

    protected function pathRel($file) {

        return str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
    }

    public static function build($file, array $list, 
                                $type = 'js', $options = array()) {

        $cache = new self($file, $list, $type, $options);

        return $cache->url;
    }

    public static function buildPHP($type, array $list, $options = array()) {

        $file = $_SERVER['SYSCACHE'] . '/autoexec_' . $type . '.php';

        return self::build($file, $list, 'php', $options);
    }

    public static function buildScript($list, $type, $options = array()) {

        $file = $_SERVER['SYSCACHE'] . '/autoexec_' . 
                    md5(implode($list)) . "." . $type;

        return self::build($file, $list, $type, $options);
    }

    public function __construct($file, $list, $type, $options = array()) {

        $this->file = $this->pathAbs($file);
        $this->url = $this->pathRel($this->file);
        $this->list = $list;
        $this->type = $type;
        $this->options = $options;

        // проходим по всему списку и убираем пустые строки, 
        // а также делаем полный путь
        foreach ($this->list as $k => $f) {

            if ($f) {

                $this->list[$k] = $this->pathAbs($f);
            } else
                unset($this->list[$k]);
        }

        // проверяем валидность кеша, и если он протух — перестраиваем
        if (!$this->checkValid())
            $this->rebuild();
    }

    public function checkValid() {

        if (!$_SERVER['modCache']['assets']['noValidate']) {

            // Если не стоит флаг noValidate — то проходим по всему массиву 
            // и смотрим, существуют ли файлы, и с какими они датами

            $this->dateCache = @fileMTime($this->file); 
            // собака т.к. файла может и не быть
            $this->dateActual = 0;

            // Проходим по списку и сверяем даты, 
            // постепенно апдейтя реальную дату последего изменения
            // блин! только по времени? а если массив поменялся
            foreach ($this->list as $f) {

                $date = @fileMTime($f);
                if ($date > $this->dateActual)
                    $this->dateActual = $date;
            }

            return ($this->dateActual && $this->dateActual <= $this->dateCache);
        } else {

            // Если стоит флаг noValidate — то просто смотрим, 
            // существует ли файл с кешом, не проверяя его валидность
            return file_exists($this->file);
        }
    }

    public function rebuild() {

        if ($this->type == 'php') {

            $contents = array();

            // Проходим по списку файлов и объединяем все в один большой кусок
            // TODO — обработка «?php в начале файла без закрывающего тега
            foreach ($this->list as $f) {

                if (file_exists($f)) {
                    $content = trim(file_get_contents($f));
                    $content = str_replace('__DI' . 'R__', "'" . dirname(realpath($f)) . "'", $content);
                    $content = str_replace('__FI' . 'LE__', "'". realpath($f) . "'", $content);
                    $contents[] = $content;
                }
            }

            $contents = implode('', $contents);
        } else {
            $contents = Minify::combine($this->list);
        }

        // записываем сборку в файл
        file_put_contents($this->file, $contents);

        // устанавливаем дату
        @touch($this->file, $this->dateActual);
    }


    
}

?>