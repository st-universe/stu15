<?
class db
{
	function db()
	{
		global $db,$page;
		$this->dblink = @mysql_connect($db[server],$db[user],$db[pass]);
		if (!$this->dblink)
		{
			$sqlerr = 1;
			return -1;
		}
		mysql_select_db($db[database], $this->dblink);
	}
	
	function query($qry,$m=0)
	{
		global $user,$myGame,$qcount;
		if (!$qry) return 0;
		$qcount += 1;
		$result = @mysql_query($qry,$this->dblink);
		if (mysql_error()) echo $qry."<br>";
		//if (mysql_error()) $myGame->addlog(105,1,$user,mysql_error()."<br>".$qry);
		if ($m == 0) return $result;
		if ($m == 5) return @mysql_insert_id();
		if ($m == 6) return @mysql_affected_rows();
		if (@mysql_num_rows($result) == 0) return 0;
		if ($m == 1) return @mysql_result($result,0);
		if ($m == 2)
		{
			for ($i=0;$i<@mysql_num_rows($result);$i++) $data[] = mysql_fetch_assoc($result);
			return $data;
		}
		if ($m == 3) return @mysql_num_rows($result);
		if ($m == 4) return @mysql_fetch_assoc($result);
	}
}
?>
