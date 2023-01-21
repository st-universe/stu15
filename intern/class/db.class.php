<?
class db {

	function db() {
		
		global $db,$sqlerr;
		$this->dblink = @mysql_connect($db[server],$db[user],$db[pass]);
		if (!$this->dblink) {
			$sqlerr = 1;
			return -1;
		}
		mysql_select_db($db[database], $this->dblink);
		return 1;
	}
	
	function query($qry)
	{
		return mysql_query($qry,$this->dblink);
		
	}
}
?>
