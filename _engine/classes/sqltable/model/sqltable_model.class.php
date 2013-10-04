<?php

class sqltable_model extends model {

    protected $maintable;
    public $idstr;

    public function __construct() {
        $this->maintable = 'coments';
        $this->idstr = '';
    }

    public function getDir() {
	return __DIR__;
    }

    public function init() {
        if (empty($this->maintable)) {
            return true;
        } else {
            $sql = "SELECT COUNT(*) FROM {$this->maintable}";
            if (! sql::query($sql) )
		$this->install ();
        }
    }


    /**
     * Воззвращает массив данных из базы
     * @param boolean $all Покказывать все
     * @param string $order Наззвание столбца по которому сортировать
     * @param string $find Подстрока поиска
     * @param string $idstr строка  идентификаторов, специальное использование, очень специальное
     * @return array
     */
    public function getData($all=false, $order='', $find='', $idstr='') {
        $this->idstr = $idstr;
        return array();
    }

    public function getCols() {
        return array();
    }

    public function delete($delete) {
        return true;
    }

    public function getRecord($edit) {
        if (empty($edit))
            return array();
        if (is_numeric($edit)) {
            $sql = "SELECT * FROM {$this->maintable} WHERE id='{$edit}'";
            $rec = sql::fetchOne($sql);
            $rec[files] = $this->getFilesForId($this->maintable, $edit);
            return $rec;
        } else {
            $ret = sql::fetchOne($edit);
            return $ret;
        }
    }

    public function setRecord($data) {
        extract($data);
        // файлы к таблице привязать
        $files = $this->storeFiles($files, $this->maintable);
        if (!isset($curfile))
            $curfile = array();
        if (!isset($linkfile))
            $linkfile = array();
        $curfile = $curfile + $linkfile + $files; // в мерге перенумеровываются ключи!!!
        $this->storeFilesInTable($curfile, $this->maintable, $edit);
        $ret[affected] = true;
        return $ret;
    }

    /*
     * TODO: дальше идет код связанные именно с этим проектом, потому его лучше выделить 
     * в класс не в каталоге engin, а в classes, а остальные наследовать уже от того
     */
    
    /*
     * Возвращает список заказчиков для форм
     */

    public function getCustomers($type='array') {
        $sql = "SELECT id,customer FROM customers ORDER BY customer";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $customers[$rs[id]] = $rs[customer];
        }
        return $customers;
    }

    /*
     * Возвращает блоки заказчика для форм
     */

    public function getBlocks($customerid='', $type='array') {
        if (empty($customerid))
            return '';
        $sql = "SELECT id,blockname,customer_id FROM blocks " .
                (empty($customerid) ? "" : "WHERE customer_id='{$customerid}' ") .
                "ORDER BY blockname";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $blocks[$rs[id]] = $rs[plate];
        }
        return $blocks;
    }

    /*
     * Возвращает платы заказчика для форм
     */

    public function getBoards($customerid='', $type='array') {
        if (empty($customerid))
            return '';
        $blocks = array();
        $sql = "SELECT id,board_name FROM boards " .
                (empty($customerid) ? "" : "WHERE customer_id='{$customerid}' ") .
                "ORDER BY board_name";
        $res = sql::fetchAll($sql);
        foreach ($res as $rs) {
            $blocks[$rs[id]] = $rs[board_name];
        }
        return $blocks;
    }

    public function getCustomer($id) {
        $sql = "SELECT * FROM customers WHERE id='{$id}'";
        return sql::fetchOne($sql);
    }

    public function getOrder($id) {
        $sql = "SELECT * FROM orders WHERE id='{$id}'";
        return sql::fetchOne($sql);
    }

    public function getTZ($id) {
        $sql = "SELECT * FROM tz WHERE id='{$id}'";
        return sql::fetchOne($sql);
    }

    public function getFileId($filename) {
        $filename = fileserver::normalizefile($filename);
        $filename = addslashes($filename);
        $sql = "SELECT id FROM filelinks WHERE file_link='{$filename}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            return $rs["id"];
        } else {
            $sql = "INSERT INTO filelinks (file_link) VALUES ('{$filename}')";
            sql::query($sql) or die(sql::error(true));
            return sql::lastId();
        }
    }

    public function getFileNameById($fileid) {
        $sql = "SELECT file_link FROM filelinks WHERE id='{$fileid}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            return $rs["file_link"];
        } else {
            return "None";
        }
    }

    public function getNeedArc() {
        return false; // TODO: Костыль
    }

    public function getFilesForId($table, $id) {
        $out[link] = '';
        $sql = "SELECT * FROM files WHERE `table`='{$table}' AND rec_id='{$id}'";
        $files = sql::fetchAll($sql);
        foreach ($files as $val) {
            $sql = "SELECT * FROM filelinks WHERE id='{$val[fileid]}'";
            $file = sql::fetchOne($sql);
            $out[file][] = $file;
            if (!strstr($file[file_link], $_SERVER['DOCUMENT_ROOT'])) {
                $filelink = fileserver::sharefilelink($file[file_link]);
                $file = basename($filelink);
                $out[link] .= "<a class='filelink' href='{$filelink}'>{$file}</a><br>";
            } else {
                $filelink = str_ireplace($_SERVER['DOCUMENT_ROOT'], '', $file[file_link]);
                $file = basename($filelink);
                $out[link] .= "<a href='http://{$_SERVER["HTTP_HOST"]}{$filelink}'>{$file}</a><br>";
            }
        }
        return $out;
    }

    public function storeFiles($files=false, $dir='') {
        if ($files) {// файл если есть сохраним
            $curfile = array();
            foreach ($files as $file) {
                if (!empty($file[size])) {
                    $pathname = $_SERVER["DOCUMENT_ROOT"] . UPLOAD_FILES_DIR . "/" . multibyte::UTF_encode($dir);
                    if (!file_exists($pathname)) {
// содать каталог
                        @mkdir($pathname, 0777);
                    }
                    $filename = $pathname . "/" . multibyte::UTF_encode($file["name"]);
                    $i = 0;
                    while (file_exists($filename)) {
                        $i++;
                        $filename = $pathname . "/{$i}_" . multibyte::UTF_encode($file["name"]);
                    }
                    if (@move_uploaded_file($file["tmp_name"], $filename)) {
// переместилось удачно
                        @chmod($filename, 0777);
                        $filename = multibyte::UTF_decode($filename);
                        $curfile[$this->getFileId($filename)] = 1; // сделаем структуру как уже существующие
                    } else {
                        return false;
                    }
                }
            }
            return $curfile;
        } else
            return array();
    }

    public function storeFilesInTable($files=false, $table='', $edit='') {
        $sql = "DELETE FROM files WHERE `table`='{$table}' AND rec_id='{$edit}'";
        sql::query($sql);
        if ($files && !empty($files)) {
// заполним таблицу files
            foreach ($files as $key => $value) {
                $sql = "INSERT INTO files (`table`,rec_id,fileid) VALUES ('{$table}','{$edit}','{$key}')";
                sql::query($sql);
            }
        }
    }

    /**
     * Возвращает текст комментария по идентификатору
     * @param int $id
     * @return string
     */
    static public function getComment($id) {
        $sql = "SELECT * FROM coments WHERE id='{$id}'";
        $comment=sql::fetchOne($sql);
        return $comment[comment];
    }

    /**
     * Возвращает идентификатор коментария по тексту коментария
     * @param string $comment
     * @return int
     */
    static public function getCommentId($comment) {
        $sql = "SELECT * FROM coments WHERE comment='{$comment}'";
        $rs = sql::fetchOne($sql);
        if (empty ($rs)) {
            $sql = "INSERT INTO coments (comment) VALUES ('{$comment}')";
            sql::query($sql);
            return sql::lastId();
        } else {
            return $rs[id];
        }
    }

    public function getCommentsForId($id,$table='') {
        if (empty($table))
            $table = $this->maintable;
        $sql = "SELECT `time`, `nik` AS `author`, `comment`, comments.id as id FROM comments JOIN (users,coments) ON (comments.author_id = users.id AND comments.coment_id = coments.id ) WHERE forobject='{$table}' AND record_id='{$id}'";
        $rec["comments"] = sql::fetchAll($sql);
        $rec["table"] = $table;
        $rec["id"] = $id;
        return $rec;
    }

    public function saveComment() {
        $author_id = $_REQUEST["userid"];
        $coment = $_REQUEST["coment"];
        $table = $_REQUEST["table"];
        $record_id = $_REQUEST["recordid"];
        $comentid = $this->getCommentId($coment);
        $sql = "INSERT INTO comments (forobject,record_id,coment_id,author_id) VALUES ('{$table}','{$record_id}','{$comentid}','$author_id')";
        sql::query($sql);
        $rec=  compact('table','record_id');
        //$sql = "SELECT `time`, `nik` AS `author`, `comment` FROM comments JOIN (users,coments) ON (comments.author_id = users.id AND comments.coment_id = coments.id ) WHERE comments.id='{$id}'";
        return $rec;
    }

    public function deleteComment($id) {
        $sql="DELETE FROM comments WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }

}
?>