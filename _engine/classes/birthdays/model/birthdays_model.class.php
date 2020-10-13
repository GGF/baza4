<?php

class birthdays_model {

    public function getToday() {
        $filename = $_SERVER[CACHE] . '/birhdays.html';
        if(file_exists($filename) && (date('d',@filemtime($filename))==date('d'))) {
            return file_get_contents($filename);
        }
        $dr = '';
        $sql = "SELECT *, (YEAR(NOW())-YEAR(dr)) as let FROM workers WHERE DAYOFYEAR(dr)>= DAYOFYEAR(CURRENT_DATE()) AND DAYOFYEAR(dr)<= (DAYOFYEAR(CURRENT_DATE())+4) ORDER BY DAYOFYEAR(dr)";
        $rs = sql::fetchAll($sql);
        if (empty($rs)) {
            //$calend = file_get_contents('http://www.calend.ru/img/export/calend.rss');
            //$calend = file_get_contents(__DIR__ . '/sample.xml');
            //return $this->xml2html($calend, __DIR_ . "/calend.xsl");
            $filenamerss = $_SERVER[CACHE] . '/calend.xml';
            $filetime = @filemtime($filenamerss);
            if (!$filetime || (date('d',$filetime)!=date('d'))) {
                $data = preg_replace("/\x01/","",file_get_contents('http://www.calend.ru/img/export/calend.rss'));
                @file_put_contents ($filenamerss, $data);
                @chmod($filenamerss, 0777);
            }
            $rss = simplexml_load_file($filenamerss);
            $count = 0;
            foreach ($rss->channel->item as $item) {
                if ($count++ >= 3) break;
                $text = explode(' ', $item->title.' - '.$item->description, 10);
                array_pop($text);
                $text = implode(' ', $text);
                $dr .= "<a target=blank href={$item->link}><div>{$text}...</div></a>";
            }
        } else {

            foreach ($rs as $res) {
                $dr .= "<div>Празднуем - {$res["fio"]} - {$res["dr"]} - {$res["let"]} лет</div>";
            }
        }
        @file_put_contents ($filename, $dr);
        return $dr;
    }

    function xml2html($xmldata, $xslfile) {
// Load the XML source 
        $xml = new DOMDocument;
        $xml->loadXML($xmldata);

        $xsl = new DOMDocument;
        $xsl->load($xslfile);

// Configure the transformer 
        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl); // attach the xsl rules 

        return $proc->transformToXML($xml);
    }

}

?>