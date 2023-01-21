<?php
class game
{
	function game()
	{
		global $myDB,$user;
		$this->db = $myDB;
		$this->user = $user;
	}
	
	function getcurrentRound() { return $this->db->query("SELECT *,UNIX_TIMESTAMP(start) as start_tsp from stu_game_rounds WHERE runde=".$this->db->query("SELECT max(runde) from stu_game_rounds",1),4); }
	
	function endround()
	{
		$round = $this->getcurrentround();
		$this->db->query("UPDATE stu_game_rounds SET ende=".time()." WHERE id=".$round[id]);
	}
	
	function startround()
	{
		$round = $this->getcurrentround();
		$this->db->query("INSERT INTO stu_game_rounds (runde,start) VALUES ('".($round[runde]+1)."',NOW())");
	}
	
	function getinactiverounds()
	{
		global $myUser;
		$user = $myUser->getuserbyid($userId);
		return $this->db->query("SELECT id FROM stu_game_rounds WHERE ende>".$user[lastaction],3);
	}
	
	function loguser($ip,$agent)
	{
		if ($this->db->query("SELECT user_id FROM stu_stats_iptable WHERE ip='".$ip."' ANd user_id=".$this->user,1) > 0) $this->db->query("UPDATE stu_stats_iptable SET ende_tsp=".time()." WHERE ip='".$ip."' AND user_id=".$this->user);
		else $this->db->query("INSERT INTO stu_stats_iptable (user_id,ip,agent,start_tsp) VALUES ('".$this->user."','".$ip."','".$agent."','".time()."')");
	}
	
	function getvalue($fielddescr) { return $this->db->query("SELECT value FROM stu_game WHERE fielddescr='".$fielddescr."'",1); }
	
	function getrounds() { return $this->db->query("SELECT COUNT(id) FROM stu_game_rounds",1); }
	
	function getTickStats() { return $this->db->query("SELECT *,UNIX_TIMESTAMP(start) as start_tsp FROM stu_game_rounds ORDER BY runde DESC LIMIT 20",2); }
	
	function getPlayerStats()
	{
		$data[active] = $this->db->query("SELECT count(id) FROM stu_user WHERE id>100 AND level>0",1);
		$round = $this->getcurrentround();
		$data[nrdel] = $this->db->query("SELECT count(id) FROM stu_user WHERE id>100 AND ((lastloginround<".($round[runde]-75)." AND vac=0) OR (lastloginround<".($round[runde]-160)." AND vac=1) OR (lastloginround<".($round[runde]-50)." AND level<2) OR (lastloginround<".($round[runde]-25)." AND level=0) OR delmark=1)",1);
		$data[vac] = $this->db->query("SELECT count(id) FROM stu_user WHERE id>100 AND vac=1",1);
		$data[online] = $this->db->query("SELECT count(id) FROM stu_user WHERE UNIX_TIMESTAMP(lastaction)>".(time()-300)." AND id>100",1);
		$data[fed] = $this->db->query("SELECT count(id) FROM stu_user WHERE rasse=1 AND id>100",1);
		$data[rom] = $this->db->query("SELECT count(id) FROM stu_user WHERE rasse=2 AND id>100",1);
		$data[kli] = $this->db->query("SELECT count(id) FROM stu_user WHERE rasse=3 AND id>100",1);
		$data[car] = $this->db->query("SELECT count(id) FROM stu_user WHERE rasse=4 AND id>100",1);
		$data[fer] = $this->db->query("SELECT count(id) FROM stu_user WHERE rasse=5 AND id>100",1);
		$data[symp] = round($this->db->query("SELECT SUM(symp) FROM stu_user WHERE id>100",1)/$data[active]);
		$data[allywar] = $this->db->query("SELECT COUNT(id) FROM stu_allys_beziehungen WHERE type=1",1);
		return $data;
	}
	
	function getColStats()
	{
		$data[settled] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE user_id>100",1);
		$data[wirtschaft] = round(($this->db->query("SELECT SUM(wirtschaft) FROM stu_colonies WHERE user_id>100",1)/$data[settled]),2);
		$data[bev] = $this->db->query("SELECT SUM(bev_free)+SUM(bev_used) as bev FROM stu_colonies WHERE user_id>100",1) + $this->db->query("SELECT SUM(crew) FROM stu_ships WHERE user_id>100");
		$data[jless] = (100/$data[bev])*$this->db->query("SELECT SUM(bev_free) FROM stu_colonies WHERE user_id>100",1);
		$data[lrw] = $this->db->query("SELECT value FROM stu_game WHERE fielddescr='g_wirt'",1);
		$data[cm] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=1 AND user_id>100",1);
		$data[cl] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=2 AND user_id>100",1);
		$data[cn] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=3 AND user_id>100",1);
		$data[cg] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=4 AND user_id>100",1);
		$data[ck] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=5 AND user_id>100",1);
		$data[cd] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=6 AND user_id>100",1);
		$data[ch] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=7 AND user_id>100",1);
		$data[cx] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=8 AND user_id>100",1);
		$data[cj] = $this->db->query("SELECT count(id) FROM stu_colonies WHERE colonies_classes_id=9 AND user_id>100",1);
		$data[wm] = $this->db->query("SELECT colonies_classes_id,name,temp FROM stu_colonies WHERE user_id>100 ORDER BY RAND() LIMIT 1",4);
		return $data;
	}
	
	function getShipStats()
	{
		$data[ships] = $this->db->query("SELECT count(id) FROM stu_ships WHERE user_id>100",1);
		$data[trums] = $this->db->query("SELECT count(a.id) FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE b.trumfield=1 AND a.user_id=2",1);
		$data[inaktiv] = $this->db->query("SELECT count(a.id) FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.crew<b.crew_min AND a.user_id>100",1);
		$data[crew] = round(($this->db->query("SELECT SUM(crew) FROM stu_ships WHERE user_id>100",1)/$data[ships]),1);
		$data[torp] = round(($this->db->query("SELECT SUM(count) FROM stu_ships_storage WHERE user_id>100 AND (goods_id=7 OR goods_id=16 OR goods_id=17 OR goods_id=26)",1)/$data[ships]),1);
		return $data;
	}
	
	function getRessStats()
	{
		$data[ress] = $this->db->query("SELECT SUM(count) FROM stu_ships_storage WHERE user_id>100",1) + $this->db->query("SELECT SUM(count) FROM stu_colonies_storage WHERE user_id>100",1) + $this->db->query("SELECT SUM(count) FROM stu_trade_goods WHERE user_id>100 AND status<2",1);
		return $data;
	}
	
	function addlog($errorId,$level,$user,$string)
	{
		global $loglevel,$global_path;
		$ip = getenv("REMOTE_ADDR");
		$filename = date("d_m_y");
		if (!file_exists($global_path."tracking/glogs/".$filename.".log")) $chmod = 1;
		$logfile = fopen($global_path."tracking/glogs/".$filename.".log","a");
		if ($chmod == 1) chmod($global_path."tracking/glogs/".$filename.".log",0777);
		if ($level <= $loglevel) fwrite($logfile,"[".date("H:i:s")."]%-%".$ip."%-%".$user."%-%".$errorId."%-%".addslashes($string)."\n");
		@fclose($logfile);
	}
	
	function completetags($string)
	{
		$string1 = substr_count($string,"<font");
		$string2 = substr_count($string,"</font>");
		if ($string1 > $string2) $multi = $string1-$string2;
		if ($multi>0) for ($z=1;$z<=$multi;$z++) $addstring .= "</font>";
		return $string.$addstring;
	}
	
}
?>