<?php
class map {

	function map() {
	
		global $myDB;
		$this->dblink = $myDB->dblink;
	
	}
	
	function insertfield($x,$y,$type) {
		
		$result = mysql_query("SELECT * FROM stu_map_fields WHERE coords_x='".$x."' AND coords_y='".$y."'",$this->dblink);
		if (mysql_num_rows($result) < 1) mysql_query("INSERT INTO stu_map_fields (coords_x,coords_y,type) VALUES ('".$x."','".$y."','".$type."')",$this->dblink);
	
	}
	
	function getrow($x1,$x2,$y,$w) {
	
		$query = "SELECT * from stu_map_fields WHERE coords_x >= ".$x1." && coords_x <= ".$x2." && coords_y = '".$y."' AND wese=".$w."";
		$result = mysql_query($query, $this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}
	
	function getcol($y1,$y2,$x) {
	
		$query = "SELECT * from stu_map_fields WHERE coords_y >= ".$y1." && coords_y <= ".$y2." && coords_x = '".$x."'";
		$result = mysql_query($query, $this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}
	
	function getfieldinfo($x,$y) {
	
		$query = "SELECT id from stu_ships WHERE coords_x='".$x."' && coords_y='".$y."'";
		$result = mysql_query($query, $this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[] = mysql_fetch_array($result);
		return $data;
	}
	
	function getfieldbycoords($x,$y,$wese) {
	
		return mysql_fetch_array(mysql_query("SELECT * FROM stu_map_fields WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese,$this->dblink));
	
	}
}
?>