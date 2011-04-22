<?
class adminhere_model {
	public function userwin() {
		$sql="SELECT *,(UNIX_TIMESTAMP()-UNIX_TIMESTAMP(MAX(ts))) AS lt FROM session JOIN users ON session.u_id=users.id GROUP BY u_id";
		$res=sql::fetchAll($sql);
		$ret = '';
		foreach($res as $rs){
			$ret .= "<div>".$rs[nik]." - ".date("H:i:s",mktime(0, 0, 0, date("m")  , date("d"), date("Y"))+$rs[lt])."</div>";
		}
		return $ret;
	}
	
	public function yes() {
		$sql = "SELECT (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(ts))<180 AS adminhere FROM session WHERE u_id='1' ORDER BY ts DESC LIMIT 1";
		$res = sql::fetchOne($sql);
		return empty($res["adminhere"]) ? false : true;
	}
}
?>