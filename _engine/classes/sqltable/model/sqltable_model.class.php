<?php

class sqltable_model {

    protected $maintable;
    protected $idstr;

    public function __construct() {
        $this->maintable = '';
        $this->idstr = '';
    }

    public function init() {
        
    }

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
            $sql = "SELECT * FROM {$this->maintable} WHERE id='$edit'";
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
        // ����� � ������� ���������
        $files = $this->storeFiles($files, $this->maintable);
        if (!isset($curfile))
            $curfile = array();
        if (!isset($linkfile))
            $linkfile = array();
        $curfile = $curfile + $linkfile + $files; // � ����� ������������������ �����!!!
        $this->storeFilesInTable($curfile, $this->maintable, $edit);
        $ret[affected] = true;
        return $ret;
    }

    /*
     * ���������� ������ ���������� ��� ����
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
     * ���������� ����� ��������� ��� ����
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
     * ���������� ����� ��������� ��� ����
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
//        // ������������� � ������� ��������
//        $filename = multibyte::UTF_decode($filename);
//        // ������� \ ��������
//        $filename = str_replace('\\', '\\\\', $filename);
//        // ������� ������
//        $filename = str_replace('\\\\', '\\', $filename);
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
        return false; // TODO: �������
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
                $out[link] .= "&nbsp;<a class='filelink' href='{$filelink}'>{$file}</a>";
            } else {
                $filelink = str_ireplace($_SERVER['DOCUMENT_ROOT'], '', $file[file_link]);
                $file = basename($filelink);
                $out[link] .= "&nbsp;<a href='http://{$_SERVER["HTTP_HOST"]}{$filelink}'>{$file}</a>";
            }
        }
        return $out;
    }

    public function storeFiles($files=false, $dir='') {
        if ($files) {// ���� ���� ���� ��������
            $curfile = array();
            foreach ($files as $file) {
                if (!empty($file[size])) {
                    $pathname = $_SERVER["DOCUMENT_ROOT"] . UPLOAD_FILES_DIR . "/" . multibyte::UTF_encode($dir);
                    if (!file_exists($pathname)) {
// ������ �������
                        @mkdir($pathname, 0777);
                    }
                    $filename = $pathname . "/" . multibyte::UTF_encode($file["name"]);
                    $i = 0;
                    while (file_exists($filename)) {
                        $i++;
                        $filename = $pathname . "/{$i}_" . multibyte::UTF_encode($file["name"]);
                    }
                    if (@move_uploaded_file($file["tmp_name"], $filename)) {
// ������������� ������
                        @chmod($filename, 0777);
                        $filename = multibyte::UTF_decode($filename);
                        $curfile[$this->getFileId($filename)] = 1; // ������� ��������� ��� ��� ������������
                    } else {
                        return false;
                    }
                }
            }
            return $curfile;
        } else
            return false;
    }

    public function storeFilesInTable($files=false, $table='', $edit='') {
        $sql = "DELETE FROM files WHERE `table`='{$table}' AND rec_id='{$edit}'";
        sql::query($sql);
        if ($files && !empty($files)) {
// �������� ������� files
            foreach ($files as $key => $value) {
                $sql = "INSERT INTO files (`table`,rec_id,fileid) VALUES ('{$table}','{$edit}','{$key}')";
                sql::query($sql);
            }
        }
    }

}

?>