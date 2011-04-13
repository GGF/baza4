<?php

class Auth {

    public function __construct() {
        $mes = '';
        // Почистим устаревшие сессии
        $sql = "DELETE FROM session " .
                "WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(ts) > 3600*8";
        sql::query($sql);
        $sessionid = session_id();
        if (empty($_SESSION["username"])) {
            if ($sessionid) {
                $rs = sql::fetchOne("SELECT * FROM session " .
                                "WHERE session ='{$sessionid}'");
                if (!empty($rs)) {
                    $rs = sql::fetchOne("SELECT * FROM users" .
                                    "WHERE id='{$rs["u_id"]}'");
                    if (!empty($rs)) {
                        $_SESSION["username"] = $rs["nik"];
                        $_SESSION["userid"] = $rs["id"];
                    } else {
                        $mes .= "Не могу найти пользователя по сессии." .
                                "Обратитесь к разработчику!";
                    }
                } else {
                    $mes .= "Сессия не верна или устарела!";
                }
            }

            if (!empty($_POST["password"])) {
                $sql = "SELECT * FROM users " .
                        "WHERE password='{$_POST["password"]}'";
                $res = sql::fetchOne($sql);
                if ($res) {
                    $sql = "INSERT INTO session (session,u_id) " .
                            "VALUES ('{$sessionid}','{$res["id"]}')";
                    sql::query($sql);
                    $_SESSION["username"] = $res["nik"];
                    $_SESSION["userid"] = $res["id"];
                    $sql = "SELECT rights.right,type,rtype FROM rights " .
                            "JOIN (users,rtypes,rrtypes) " .
                            "ON (users.id=u_id AND rtypes.id=type_id " .
                            "AND rrtypes.id=rtype_id) " .
                            "WHERE nik='{$_SESSION["username"]}'";
                    $res = sql::fetchAll($sql);
                    foreach ($res as $rs) {
                        if ($rs["right"] == '1') {
                            $_SESSION["rights"][$rs["type"]][$rs["rtype"]] = true;
                        }
                    }
                    // удачная авторизация
                } else {
                    $mes = "Логин или пароль указаны не верно. " .
                            "Авторизация не удалась. Попробуйте ещё раз.";
                }
            } else {
                if ($_SERVER["SCRIPT_NAME"] != '/index.php') {
                    $this->gohome();
                    // показать начало чтоб не подменю показывать
                }
                echo "<html><head><title>База данных ЗАО МПП. Вход.</title>";
                echo "<META HTTP-EQUIV=Content-Type CONTENT=text/html; charset=windows-1251>";
                echo "</head>";
                echo "<body bgcolor=#FFFFFF><div align=center> <p>&nbsp;</p>";
                echo "<style>.zag {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; font-weight: bold; color: #000000}
	.tekst {  font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #000000}
	.podtekst {  font-family: Arial, Helvetica, sans-serif; font-size: 6pt; color: red; text-align:left}</style>";
                echo " <form action='' method='POST'>";
                echo "<table width=500 border=0 cellspacing=0 cellpadding=0 bgcolor='#FFFFFF'>";
                echo "<tr>  <td rowspan=6 width=3>&nbsp;</td>";
                echo "<td colspan=2 class=zag align=center>&nbsp;</td><td>&nbsp;</td>";
                echo "</tr> <tr><td colspan=2 class=zag align=center>Необходимо авторизоваться для работы с базой</td><td>&nbsp;</td> </tr>";
                echo "<tr><td colspan=2 class=zag align=center>{$mes}&nbsp;</td> <td>&nbsp;</td> </tr>";
                echo "<tr><td class=tekst align=right>Пароль <span class=podtekst>(именно пароль и только пароль)</td>";
                echo "<td align=center><input type=password name='password' id=password></td>";
                echo "</tr><tr><td width='10'>&nbsp;</td><td class=tekst>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                echo "<tr valign=top align=left><td colspan=4>-------------------------------------------------------------------------</td></tr></table>";
                echo "</form>";
                echo "<p>&nbsp;</p></div><script>document.location.hash = '';
			currentState = document.location.hash</script></body></html>";
                exit;
            }
        } else {
            $sql = "UPDATE session SET ts=NOW() WHERE session='{$sessionid}'";
            sql::query($sql);
        }
    }

    public function logout() {
        $sql = "DELETE FROM session WHERE session='" . session_id() . "'";
        sql::query($sql);
        // Unset all of the session variables.
        session_unset();
        // Finally, destroy the session.
        session_destroy();
        $this->gohome();
    }

    private function gohome() {
        if (Ajax::isAjaxRequest()) {
            echo "<script>";
            echo "window.location = 'http://{$_SERVER['HTTP_HOST']}'";
            echo "</script>";

        } else
            header("Location: http://{$_SERVER['HTTP_HOST']}");
        exit;
    }

}

?>
