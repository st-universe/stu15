<?php
class ally {

	function ally() {
	
		global $myDB;
		$this->dblink = $myDB->dblink;
	
	}
	
	function checkally($userId) {
	
		$result = mysql_query("SELECT allys_id FROM stu_user WHERE id='".$userId."'",$this->dblink);
		$data = mysql_fetch_array($result);
		if ($data[allys_id] == 0) return 0;
		else return $data[allys_id];
	}
	
	function getallylist() {
	
		$result = mysql_query("SELECT * from stu_allys",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function getallybyid($allyId) {
	
		$result = mysql_query("SELECT * FROM stu_allys WHERE id='".$allyId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	
	}
	
	function joinally($allyId,$userId,$pass) {
	
		$return = $this->checkally($userId);
		if ($return != 0) return -2;
		else {
			$result = mysql_query("SELECT id,pass FROM stu_allys WHERE id='".$allyId."'",$this->dblink);
			if (mysql_num_rows($result) == 0) return -1;
			else {
				$data = mysql_fetch_array($result);
				if ($data[pass] != md5($pass)) return 0;
				else mysql_query("UPDATE stu_user SET allys_id='".$allyId."' WHERE id='".$userId."'",$this->dblink);
				return 1;
			}
		}
	}
	
	function getallymembers($allyId) {
	
		$result = mysql_query("SELECT id,user FROM stu_user WHERE allys_id='".$allyId."'",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function newally($name,$pass,$descr,$hp,$userId) {
	
		if (strlen($pass) < 5) return -2;
		$result = mysql_query("SELECT id FROM stu_user WHERE id='".$userId."' AND allys_id>0",$this->dblink);
		if (mysql_num_rows($result) == 1) return -1;
		$result = mysql_query("SELECT id FROM stu_allys WHERE name='".$name."'",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			mysql_query("INSERT INTO stu_allys (name,pass,user_id,descr,hp) VALUES
						 ('".$name."','".md5($pass)."','".$userId."','".$descr."','".$hp."')",$this->dblink);
			mysql_query("UPDATE stu_user SET allys_id='".mysql_insert_id()."' WHERE id='".$userId."'",$this->dblink);
			return 1;
		} else return 0;
	}
	
	function leaveally($allyId,$userId) {
	
		$result = mysql_query("SELECT id FROM stu_user WHERE id='".$userId."' AND allys_id='".$allyId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return -1;
		$result = mysql_query("SELECT id FROM stu_allys WHERE id='".$allyId."' ANd user_id='".$userId."'",$this->dblink);
		if (mysql_num_rows($result) == 1) return 0;
		mysql_query("UPDATE stu_user SET allys_id=0 WHERE id='".$userId."'",$this->dblink);
		return 1;
	}
	
	function delally($allyId,$userId) {
	
		$result = mysql_query("SELECT id FROM stu_user WHERE id='".$userId."' AND allys_id='".$allyId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return -1;
		mysql_query("DELETE FROM stu_allys WHERE id='".$allyId."'",$this->dblink);
		mysql_query("UPDATE stu_user SET allys_id=0 WHERE allys_id='".$allyId."'",$this->dblink);
		return 1;
	}
	
	function delfromally($allyId,$del_userId,$userId) {
	
		$return = $this->checkAlly($userId);
		if ($return == 0) return -1;
		$result = mysql_query("SELECT id FROM stu_allys WHERE id='".$allyId."' AND user_id='".$userId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		mysql_query("UPDATE stu_user SET allys_id=0 WHERE id='".$del_userId."'",$this->dblink);
		return 1;
	}

}
?>