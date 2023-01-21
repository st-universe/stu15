<?php
class user {

	function user() {
	
		global $myDB;
		$this->db = $myDB;
	
	}
	
	function getUserByID($userId) {
	
		$result = $t->db->qry("SELECT * from stu_user WHERE id=".$userId);
		if (mysql_num_rows($result) == 0) return -1;
		$data = mysql_fetch_array($result);
		return $data;
	}
	
	function updateUserById($userId,$value,$field) {
	
		$this->db->query("UPDATE stu_user SET ".$field."='".$value."' WHERE id=".$userId);
	
	}
	
	function getUser() {
		
		global $myAlly;
		$result = $this->db->query("SELECT id,user,symp,startrunde,level,UNIX_TIMESTAMP(lastaction) as last_tsp,allys_id FROM stu_user WHERE status!=9 AND aktiv=1 ORDER BY id ASC");
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$data[$i][ally] = $myAlly->getallybyid($data[$i][allys_id]);
		}
		return $data;
	
	}
	
	function getdoublelogins($day)
	{
		echo $t;
		$result = $this->db->query("SELECT id,user FROM stu_user WHERE id>100 AND aktiv>0 ORDER BY id");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$data[$i] = mysql_fetch_array($result);
			$tr = $this->db->query("SELECT * FROM stu_stats_iptable WHERE user_id=".$data[$i][id]." AND start_tsp>=".$day." AND start_tsp<".($day+86400)." ORDER BY start_tsp ASC");
			if (mysql_num_rows($tr) > 1)
			{
				for ($j=0;$j<mysql_num_rows($tr);$j++) $data[$i][lg][$j] = mysql_fetch_array($tr);
				$data[$i][nlg] = 2;
			}
			else $data[$i][nlg] = 1;
		}
		return $data;
	}
	
	function getiplogins($day)
	{
		$result = $this->db->query("SELECT * FROM stu_stats_iptable WHERE start_tsp>=".$day." AND start_tsp<".($day+86400)." GROUP BY ip ORDER BY ip");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$data[$i] = mysql_fetch_assoc($result);
			$res = $this->db->query("SELECT * FROM stu_stats_iptable WHERE ip='".$data[$i][ip]."' AND start_tsp>=".$day." AND start_tsp<".($day+86400)." AND user_id>100 GROUP BY user_id");
			for ($j=0;$j<mysql_num_rows($res);$j++)	$data[$i][ipl][] = mysql_fetch_array($res);
			if (mysql_num_rows($res) < 2) $data[$i][nlg] = 2;
			else $data[$i][nlg] = 1;
		}
		return $data;
	}

}
?>