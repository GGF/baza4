<?
class birthdays_model {
	public function getToday() {
		$sql = "SELECT *, (YEAR(NOW())-YEAR(dr)) as let FROM workers WHERE DAYOFYEAR(dr)>= DAYOFYEAR(CURRENT_DATE()) AND DAYOFYEAR(dr)<= (DAYOFYEAR(CURRENT_DATE())+4) ORDER BY DAYOFYEAR(dr)";
		$rs = sql::fetchAll($sql);
		$dr = "";
		foreach ($rs as $res) {
			$dr .= "<div>День рождения - {$res["fio"]} - {$res["dr"]} - {$res["let"]} лет</div>";
		}
		return $dr;
	}
}
?>