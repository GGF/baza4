<?php

/*
 * nzap model class
 */

class lanch_nzap_model extends sqltable_model {

    public function getData($all=false, $order='', $find='', $idstr='') {
        $ret = array();
        $sql = "SELECT *,posintz.id AS nzid,posintz.id
        FROM posintz
        LEFT JOIN (lanched) ON (posintz.block_id=lanched.block_id)
        JOIN (blocks,tz,filelinks,customers,orders)
            ON (tz.order_id=orders.id
            AND blocks.id=posintz.block_id
            AND posintz.tz_id=tz.id
            AND tz.file_link_id=filelinks.id
            AND blocks.customer_id=customers.id)
        WHERE posintz.ldate = '0000-00-00' "
                . (!empty($find) ? "AND (blocks.blockname LIKE '%{$find}%'
            OR filelinks.file_link LIKE '%{$find}%'
            OR orders.number LIKE '%{$find}%') " : "")
                . (!empty($order) ? "ORDER BY {$order} " :
                        "ORDER BY customers.customer,tz.id,posintz.id ")
                . ($all ? "" : "LIMIT 20");
        $ret = sql::fetchAll($sql);
        return $ret;
    }

    public function getCols() {
        $cols = array();
        $cols["�"] = "�";
        $cols["nzid"] = "ID";
        $cols["customer"] = "��������";
        $cols["number"] = "�����";
        $cols["blockname"] = "�����";
        $cols["numbers"] = "���-��";
        $cols["lastdate"] = "����. ���";
        return $cols;
    }

    public function delete($id) {
        $sql = "DELETE FROM posintz WHERE id='{$id}'";
        sql::query($sql);
        return sql::affected();
    }

    public function getRecord($id) {
        $sql = "SELECT *,tz.id AS tzid, blocks.sizex AS bsizex,
            blocks.sizey AS bsizey,
            blocks.id AS bid
            FROM posintz JOIN (tz,filelinks)
                            ON (tz.id=posintz.tz_id
                                    AND tz.file_link_id=filelinks.id)
                         LEFT JOIN (blocks) ON (blocks.id=block_id)
                WHERE posintz.id='{$id}'";
        $rs = sql::fetchOne($sql);
        //$rec[block][filelink] = $_SESSION["rights"]["nzap"]["edit"]?fileserver::sharefilelink($rs["file_link"]):"";
        $rec[block][filelink] = fileserver::sharefilelink($rs["file_link"]);
        $rec[block][blockname] = $rs[blockname];
        $rec[block][boardinorder] = $rs[numbers];
        $rec[block][blocksizex] = ceil($rs[bsizex]);
        $rec[block][blocksizey] = ceil($rs[bsizey]);
        $rec[block][phtemplates] = ($rs["template_make"] == '0' ?
                        $rs["template_check"] : $rs["template_make"]);
        $sql = "SELECT *, boards.sizex AS psizex, boards.sizey AS psizey,
        boards.id AS bid FROM blockpos
        JOIN (customers,blocks,boards)
        ON (customers.id=boards.customer_id
            AND blocks.id=block_id
            AND boards.id=board_id)
        WHERE block_id='{$rs["bid"]}'";
        $res = sql::fetchAll($sql);
        $nz = 0; // ������������ ���������� ��������� �� ���������� ���� � �����
        $nl = 0; // ������������ ���������� ����� �� ����� � �����, ���� ����
        $cl = 0; // ����� �����, ���������� �� ��������
        $piz = 0; // ����� ���� �� ��������� (����� �� �����)
        foreach ($res as $rs1) {
            $board[name] = $rs1["board_name"];
            $board[sizex] = $rs1["psizex"];
            $board[sizey] = $rs1["psizey"];
            $board[numberinblock] = $rs1["nib"];
            $board[numberinblockx] = $rs1["nx"];
            $board[numberinblocky] = $rs1["ny"];
            $board[layers] = $rs1["layers"];
            $board[mask] = $rs1["mask"];
            $board[mark] = $rs1["mark"];
            $sql = "SELECT numbers FROM posintz WHERE tz_id='{$rs["tzid"]}'
                    AND board_id='{$rs1["bid"]}'";
            $rs2 = sql::fetchOne($sql);
            $nz = max($nz, ceil($rs2["numbers"] / $rs1["nib"]));
            $nl = max($nl, $rs1["layers"]);
            $cl = max($cl, $rs1["class"]);
            $piz += $rs1["nib"];
            $customer = $rs1["customer"];
            $rec[boards][] = $board;
        }
        //if ($_SESSION["rights"]["nzap"]["edit"]) {
        if ($nl > 2) {
            // ����������� ������ ������ �� �����
            if ($rs["customer_id"] == '8') // �����
                $zip = 1;
            else
                $zip = 5;
            // ���� ��������� ������ - �����������
            if ($rs["first"] == '1' || $rs["template_make"] > 0) {
                $sql = "SELECT * FROM masterplate WHERE posid='{$id}'";
                $mp = sql::fetchOne($sql);
                if (empty($mp)) {
                    $rec[mp] = array(mplink => true);
                }
            }
            $dpp = false;
        } else {
            // ����-������������
            $zip = 25;
            $dpp = true;
            // ���� ������ ���� ��������� - �����������,
            // ���� ��������� ��� ���������������� �����
        }
        if (0 == $nz) {
            $nz = $rs[numbl];
        }
        $rec[parties] = ceil($nz / $zip);
        for ($i = 1; $i <= ceil($nz / $zip); $i++) {
            $party = false;
            $sql = "SELECT lanch.id as lid,file_link FROM lanch
                        JOIN filelinks ON (file_link_id=filelinks.id)
                        WHERE tz_id='{$rs["tz_id"]}'
                            AND pos_in_tz='{$rs["posintz"]}' AND part='{$i}'";
            $rs3 = sql::fetchOne($sql);
            if (!empty($rs3)) {
                $party[sllink] = fileserver::sharefilelink($rs3["file_link"]);
                $party[slid] = $rs3[lid];
            } else {
                $party[party] = $i;
                $party[type] = $dpp ? "dpp" : "mpp";
            }
            $rec[party][] = $party;
        }
        //}
        return $rec;
    }

    public function getMasterplate($tzposid) {
        $sql = "SELECT * FROM posintz JOIN (tz,orders) ON (tz.id=tz_id AND orders.id=tz.order_id) WHERE posintz.id='{$tzposid}'";
        $rs = sql::fetchOne($sql);
        $posintz = $rs[posintz];
        $tzid = $rs[tz_id];
        $block_id = $rs[block_id];
        $customer_id = $rs[customer_id];
        $sql = "SELECT * FROM masterplate WHERE tz_id='{$tzid}' AND posintz='{$posintz}'";
        $res = sql::fetchOne($sql);
        if (empty($res)) {
            $sql = "INSERT INTO masterplate (tz_id,posintz,mpdate,user_id,posid,customer_id,block_id)
                    VALUES ('{$tzid}','{$posintz}',Now(),'{$_SESSION["userid"]}','{$tzposid}','{$customer_id}','{$block_id}')";
            sql::query($sql);
            $rec[mp_id] = sql::lastId();
        } else {
            $sql = "UPDATE masterplate SET mpdate=NOW(), user_id='{$_SESSION[userid]}' WHERE id='{$res[id]}'";
            $rs = sql::fetchOne($res);
            $rec[mp_id] = $res["id"];
        }
        $sql = "SELECT * FROM blocks JOIN customers ON blocks.customer_id=customers.id WHERE blocks.id='{$block_id}'";
        $rs = sql::fetchOne($sql);
        $rec[customer] = $rs[customer];
        $rec[blockname] = $rs[blockname];
        $rec[sizex] = $rs[sizex];
        $rec[sizey] = $rs[sizey];
        $rec[drlname] = $rs[drlname];
        $rec[date] = date("Y-m-d");
        return $rec;
    }

    public function getPartyfile($rec) {
        extract($rec);
        if ($dozap) {
            $sql = "SELECT pos_in_tz_id AS posid
            FROM lanch
            WHERE lanch.id='{$posid}'";
            //echo $sql;
            $rs = sql::fetchOne($sql);
            sql::error(true);
            $posid = $rs[posid];
            $sql = "SELECT MAX(part)+1 AS party
                FROM lanch
                WHERE pos_in_tz_id='{$posid}' ";
            $rs = sql::fetchOne($sql);
            sql::error(true);
            $rec[dozapnumbers] = $party; // ��� ���� �������� ������� ��� ���������
            $rec[party] = $party = $rs[party];
            $rec[posid] = $posid;
        }
        $sql = "SELECT * FROM lanch
            WHERE pos_in_tz_id='{$posid}' AND part='{$party}'";
        $rs = sql::fetchOne($sql);
        if (empty($rs)) {
            $sql = "INSERT INTO lanch
            (ldate, user_id,pos_in_tz_id)
            VALUES (NOW(),'{$_SESSION["userid"]}','{$posid}')";
            sql::query($sql);
            $lanch_id = sql::lastId();
        } else {
            $lanch_id = $rs["id"];
        }
        // ������ ������������
        $rec[lanch_id] = $lanch_id;
        // �������� � ��� �����
        $sql = "SELECT * 
                FROM posintz
                JOIN (blocks,customers)
                ON (blocks.id=posintz.block_id AND blocks.customer_id=customers.id)
                WHERE posintz.id='{$posid}'";
        $rs = sql::fetchOne($sql);
        $rec[customer] = $customer = $rs[customer];
        $rec[blockname] = $blockname = $rs[blockname];
        $rec[block_id] = $rs[block_id];
// ��������� ������������� ����������
        $comment_id = 1; //������
// ��������� ������������� �������� ������
        $rec["l_date"] = $l_date = date("Y-m-d");
        $rec[file_link] = $file_link =
                "z:\\\\���������\\\\{$customer}\\\\{$blockname}\\\\�������\\\\" .
                "��-{$l_date}-{$lanch_id}.xml";
        $sql = "SELECT id FROM filelinks WHERE file_link='{$file_link}'";
        $rs = sql::fetchOne($sql);
        if (!empty($rs)) {
            $file_id = $rs["id"];
        } else {
            $sql = "INSERT INTO filelinks (file_link) VALUES ('{$file_link}')";
            sql::query($sql);
            sql::error(true);
            $file_id = sql::lastId();
        }

        $sql = "UPDATE lanch
                SET file_link_id='{$file_id}', comment_id='{$comment_id}'
                WHERE id='{$lanch_id}'";
        sql::query($sql);
        $rec[filename] = fileserver::createdironserver($rec[file_link]);
        return $rec;
    }

    public function getParty($rec) {
        extract($rec);
        $rec = $this->getPartyfile($rec);
        // ����������� � ����� ��� ��� ���
        $sql = "SELECT * FROM blockpos WHERE block_id='{$rec[block_id]}'";
        $res = sql::fetchOne($sql);
        $sql = "SELECT * FROM boards WHERE id='{$res[board_id]}'";
        $res = sql::fetchOne($sql);
        $rec[dpp] = $res[layers] <= 2;
        $rec[date] = date("d-m-Y");
        return $rec;
    }

    public function getSl($rec) {
        if (true){//$rec[dpp]) {
            return $this->getSlDpp($rec);
        } else {
            return $this->getSlMpp($rec);
        }
    }

    public function getSlDpp($rec) {
        extract($rec);
        $boardname1 = $boardname2 = $boardname3 = $boardname4 = $boardname5 = $boardname6 =
                $nib1 = $nib2 = $nib3 = $nib4 = $nib5 = $nib6 =
                $psizex1 = $niz1 = $pio1 = $ppart1 = $psizex2 = $niz2 = $pio2 = $ppart2 = $psizex3 = $niz3 = $pio3 = $ppart3 =
                $psizex4 = $niz4 = $pio4 = $ppart4 = $psizex5 = $niz5 = $pio5 = $ppart5 = $psizex6 = $niz6 = $pio6 = $ppart6 = '';
// �������� ������ � ����������
        $sql = "SELECT
        orderdate AS ldate,
        orders.number AS letter,
        fullname AS custom,
        drlname,
        scomp,
        ssolder AS ssold,
        blocks.sizex,
        blocks.sizey,
        numpl1,numpl2,numpl3,numpl4,numpl5,numpl6,
        blockname,
        blocks.id AS block_id,
        blocks.comment_id AS comment_id1,
        posintz.comment_id AS comment_id2,
        pitz_mater AS pmater,
        pitz_psimat AS ppsimat,
        blocks.thickness AS tolsh,
        posintz.numbers AS numbers,
        posintz.priem AS priem,
        tz.id as tzid,
        numbl,
        posintz.posintz AS posintz
        FROM posintz
        JOIN (customers,blocks,tz,orders)
        ON (blocks.id=posintz.block_id
            AND customers.id=orders.customer_id
            AND posintz.tz_id=tz.id
            AND tz.order_id=orders.id)
        WHERE posintz.id='{$posid}'";
        $rs = sql::fetchOne($sql);
        $rs = multibyte::UTF_encode($rs);
        $rec = array_merge($rec, $rs);
        extract($rs);
        $sql = "SELECT comment FROM coments WHERE id='{$comment_id1}'";
        $res = sql::fetchOne($sql);
        $rec[comment1] = empty($res["comment"]) ? '' : multibyte::UTF_encode($res["comment"]);
        $sql = "SELECT comment FROM coments WHERE id='{$comment_id2}'";
        $res = sql::fetchOne($sql);
        $rec[comment2] = empty($res["comment"]) ? '' : multibyte::UTF_encode($res["comment"]);
// ������� ������ � ������ � �����
        $sql = "SELECT *, board_name AS boardname, sizex AS psizex, sizey AS psizey 
                FROM blockpos 
                JOIN (boards) 
                ON (boards.id=blockpos.board_id) 
                WHERE blockpos.block_id='{$block_id}' 
                ORDER BY blockpos.id"; // ��� ����������� ���������� � �������
        $res = sql::fetchAll($sql);
        $i = 0; // �������
        $platonblock = $numlam = $rmark = $immer = 0;
        $commentp = $mask = $layers = $class = $mark = '';
        foreach ($res as $rs) {
            $platonblock = max($platonblock, $rs[nib]);
            $numlam+=$rs[numlam];
            $rmark = max($rmark, $rs[rmark]);
            $immer = max($immer, $rs[immer]);
            $class = max($class, $rs['class']);
            $i++;
            $sql = "SELECT comment FROM coments WHERE id='{$rs[comment_id]}'";
            $com = sql::fetchOne($sql);
            $commentp .= empty($com["comment"]) ? '' : multibyte::UTF_encode($com["comment"]);
            foreach ($rs as $key => $val) {
                $rec[$key . $i] = multibyte::UTF_encode($val);
            }
            $mask = $rec["mask{$i}"];
            $mark = $rec["mark{$i}"];
        }
        $rec = array_merge($rec, compact('platonblock', 'numlam', 'rmark', 'immer', 'mask', 'layers', 'class', 'mark', 'commentp'));
        // ������� ���������� ����������������
        $zagotinparty = 25;
        if ($dozap) {
            //
            $zagotovokvsego = ceil($dozapnumbers / $platonblock);
            $zag = $zagotovokvsego;
            $ppart1 = $dozapnumbers;
            $ppart1 = $zag * $platonblock;
            $numpl1 = $numbers = $dozapnumbers;
            $part = $party;
        } else {
            $zagotovokvsego = $numbl != 0 ? $numbl : ceil($numbers / $platonblock); // ����� ���������� ���������
            $zag = ($party * $zagotinparty >= $zagotovokvsego) ? ($zagotovokvsego - ($party - 1) * $zagotinparty) : $zagotinparty; //��������� � ������
            /* ���� � ������ */
            $ppart = $zag * $platonblock;
            /* ������ */
            $part = (ceil($zagotovokvsego / $zagotinparty) > 1) ?
                    $party . "(" . ceil($zagotovokvsego / $zagotinparty) . ")" : $party;
        }
        // ������������ ��� ��������� �� ����� ��������
        $rec[type] = multibyte::UTF_encode($layers == '1' ? '���' : '���');
        $rec[number] = sprintf("%08d", $lanch_id);
        $rec[zagotovokvsego] = $rec[zzak] = $zagotovokvsego;
        $rec[zag] = $rec[zppart] = $zag;
        $rec[fm1] = (strstr($rec[mark], '1') || strstr($rec[mark], '2') ? "+" : "-");
        $rec[fm2] = (strstr($rec[mark], '2') ? "+" : "-");
        $rec[rmark] = ($rec[rmark] == '1' ? "+" : "-");
        $rec[sizex] = ceil($rec[sizex]);
        $rec[sizey] = ceil($rec[sizey]);
        for ($i = 1; $i <= 6; $i++) {
            $rec["psizex{$i}"] = $rec["psizex{$i}"] == 0 ? "" : $rec["psizex{$i}"] . 'x' . $rec["psizey{$i}"];
            $rec["pio{$i}"] = $rec["numpl{$i}"] == 0 ? "" : $rec["numpl{$i}"];
            $rec["ppart{$i}"] = $rec["nib{$i}"] * $rec[zag] == 0 ? "" : $rec["nib{$i}"] * $rec[zag];
            $rec["niz{$i}"] = $rec["nib{$i}"];
            $rec["boardname{$i}"] = $rec["boardname{$i}"];
        }
        $rec[pio1] = $numpl1 == 0 ? $numbers : $numpl1; // ����� �������� ����� ����� ������� ���� numpl ����� �� �� ����������
        $rec[datez] = $rec[date];
        $rec[mater] = ($rec[pmater] == '' ? $rec[mater] : $rec[pmater]) . '-' . trim(sprintf("%5.1f", $rec[tolsh]));
        $rec[tolsh] = trim(sprintf("%5.1f", $rec[tolsh]));
        $rec[smask] = strstr($rec[mask], multibyte::UTF_encode('��')) ? "+" : "-";
        $rec[zmask] = strstr($rec[mask], multibyte::UTF_encode('��')) ? "+" : "-";
        $rec[aurum] = ($rec[immer] == '1' ? "+" : "-");
        $rec[priemz] = strstr($priem, multibyte::UTF_encode('��')) ? "+" : "-";
        $rec[priemotk] = '+'; // ������
        $rec[scomp] = sprintf("%3.2f", $rec[scomp] / 10000);
        $rec[ssold] = sprintf("%3.2f", $rec[ssold] / 10000);
        $rec[lamel] = $rec[numlam] > 0 ? "+" : "-";
        $rec[psimat] = (empty($rec[ppsimat]) ? (empty($rec[psimat]) ? "" : $rec[psimat] . '-' . trim(sprintf("%5.1f", $rec[tolsh]))) :
                        ($rec[ppsimat] . '-' . trim(sprintf("%5.1f", $rec[tolsh])))
                ) . $rec[commentp];
        $rec[dozap] = $rec[dozap] ? multibyte::UTF_encode('��������') : '';
        $rec = array_merge($rec, compact('ppart', 'part'));
        return $rec;
    }

    public function getSlMpp($rec) {
        extract($rec);


        return $rec;
    }

    public function lanchsl($rec) {
        extract($rec);
        $last = ceil($zagotovokvsego / $zagotinparty) <= $party;
        $numbp = $zagotovokvsego <= $zagotinparty ? $numbers :
                ($last ? ($numbers - ($party - 1) * $zagotinparty * $platonblock) : $zagotinparty * $platonblock);
        $sql = "UPDATE lanch
        SET ldate=NOW(), block_id='{$block_id}',
            numbz='{$zag}', numbp='{$numbp}',
            user_id='{$_SESSION["userid"]}', part='{$party}',
            tz_id='{$tzid}', pos_in_tz='{$posintz}'
        WHERE id='{$lanch_id}'";
        sql::query($sql);

        // ���� ��� �������� - ��������� �� �������
        if (!$dozap) {
            // ������� ������� ��������

            $sql = "DELETE FROM lanched WHERE block_id='{$block_id}'";
            sql::query($sql);
            $sql = "INSERT INTO lanched (block_id,lastdate) VALUES ('{$block_id}',NOW())";
            sql::query($sql);


            $sql = "SELECT SUM(numbz) AS snumbz FROM lanch WHERE pos_in_tz_id='{$posid}'
            GROUP BY pos_in_tz_id";
            $rs = sql::fetchOne($sql);
            if ($rs[snumbz] >= $zagotovokvsego) {
                $sql = "UPDATE posintz SET ldate=NOW(), luser_id='{$_SERVER["userid"]}'
         WHERE id='{$posid}'";
                sql::query($sql);
            }
        }
        return true;
    }

}

?>
