<?php
class adminhere_model {
	public function userwin() {
		$sql="SELECT *,(UNIX_TIMESTAMP()-UNIX_TIMESTAMP(MAX(ts))) AS lt FROM session JOIN users ON session.u_id=users.id GROUP BY u_id";
		$res=sql::fetchAll($sql);
		$ret = '';
		foreach($res as $rs){
			// z - дней с начала года
			// Z - разница в секундах местного и UTC
			$ret .= "<div>".$rs['nik']." - ".date("z H:i:s",($rs['lt']-date('Z')))."</div>";

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