<?php
class opt
{

	function opt()
	{
		global $myDB,$_SESSION;
		$this->db = $myDB;
		$this->sess = $_SESSION;
	}

	function actumo()
	{
		$data = $this->db->query("SELECT vac,pvac FROM stu_user WHERE id=".$this->sess["uid"],4);
		if ($data[vac] == 1) return "Der U-Mode ist bereits aktiviert";
		if ($data[pvac] == 0) return "U-Mode kann diesen Monat nicht mehr aktiviert werden";
		$this->db->query("UPDATE stu_user SET vac=1,pvac=pvac-1,vactime=".time()." WHERE id=".$this->sess["uid"]);
		$this->db->query("UPDATE stu_ships SET alertlevel=1 WHERE user_id=".$this->sess["uid"]);
		return "U-Mode aktiviert";
	}
}
?>