<?php
class game {

	function game() {
	
		global $myDB;
		$this->dblink = $myDB->dblink;
	
	}
	
	function getcurrentRound() {
	
		$data = mysql_fetch_array(mysql_query("SELECT max(runde) from stu_game_rounds",$this->dblink));;
		$query = "SELECT *,UNIX_TIMESTAMP(start) as start_tsp from stu_game_rounds WHERE runde='".$data[0]."'";
		return mysql_fetch_array(mysql_query($query,$this->dblink));
		
	}
	
	function endround() {
	
		$round = $this->getcurrentround();
		$runde = $round[runde]+1;
		mysql_query("UPDATE stu_game_rounds SET ende=".time()." WHERE id='".$round[id]."'",$this->dblink);
		mysql_query("INSERT INTO stu_game_rounds (runde,start) VALUES ('".$runde."',NOW())",$this->dblink);
	
	}

}
?>