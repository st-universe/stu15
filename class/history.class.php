<?php
class history
{
	function history()
	{
		global $myDB;
		$this->db = $myDB;
	}
	
	function addEvent($message,$userId=0,$type=0) { $this->db->query("INSERT INTO stu_event_history (message,date,user_id,type) VALUES ('".$message."',NOW(),'".$userId."','".$type."')"); }
	
	function gethistory($limit,$type=0)
	{
		$type == 0 ? $add = "" : $add = " WHERE type=".stripslashes($type);
		return $this->db->query("SELECT *,UNIX_TIMESTAMP(date) as date_tsp FROM stu_event_history".$add." ORDER BY date DESC LIMIT 0,".$limit);
	}
	
	function shipHistory($shipcount,$torpcount,$runde) { $this->db->query("INSERT INTO stu_stats_shiphistory (shipcount,torpcount,runde) VALUES ('".$shipcount."','".$torpcount."'.'".$runde."')"); }
	
	function shipTtHistory($data,$runde) { for ($i=0;$i<count($data);$i++) $this->db->query("INSERT INTO stu_stats_shipstopten (count,user_id,runde) VALUES ('".$data[$i][idcount]."','".$data[$i][user_id]."','".$runde."')"); }
	
	function getShipTtHistory($runde) { return $this->db->query("SELECT a.count,b.user FROM stu_stats_shipstopten as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.runde=".$runde." ORDER BY a.count DESC",2); }
}
?>
