<?php
class comm {

	function comm() {
	
		global $myDB;
		$this->db = $myDB;
	
	}
	
	function getnpcmsg()
	{
		$result = $this->db->query("SELECT id,user FROM stu_user WHERE id>=5 AND id<100 ORDER BY id");
		for($i=0;$i<mysql_num_rows($result);$i++)
		{
			$data[$i] = mysql_fetch_array($result);
			$data[$i][npm][1] = mysql_result($this->db->query("SELECT count(id) FROM stu_pms WHERE new=1 AND cate=1 AND recipient=".$data[$i][id]),0);
			$data[$i][npm][2] = mysql_result($this->db->query("SELECT count(id) FROM stu_pms WHERE new=1 AND cate=2 AND recipient=".$data[$i][id]),0);
			$data[$i][npm][3] = mysql_result($this->db->query("SELECT count(id) FROM stu_pms WHERE new=1 AND cate=3 AND recipient=".$data[$i][id]),0);
			$data[$i][npm][4] = mysql_result($this->db->query("SELECT count(id) FROM stu_pms WHERE new=1 AND cate=4 AND recipient=".$data[$i][id]),0);
		}
		return $data;
	}
}
?>