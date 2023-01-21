<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once("/srv/www/htdocs/web1/html/class/db.class.php");
$myDB = new db;
include_once("/srv/www/htdocs/web1/html/class/history.class.php");
$myHistory = new history;
include_once("/srv/www/htdocs/web1/html/class/map.class.php");
$myMap = new map;
include_once("/srv/www/htdocs/web1/html/class/comm.class.php");
$myComm = new comm;
include_once("/srv/www/htdocs/web1/html/class/ally.class.php");
$myAlly = new ally;
include_once("/srv/www/htdocs/web1/html/class/fleet.class.php");
$myFleet = new fleet;
include_once("/srv/www/htdocs/web1/html/class/ship.class.php");
$myShip = new ship;
include_once("/srv/www/htdocs/web1/html/class/colony.class.php");
$myColony = new colony;
include_once("/srv/www/htdocs/web1/html/class/user.class.php");
$myUser = new user;
function kimove($id,$posx1,$posy1,$posx2,$posy2,$userId,$wp=0) {
	global $myDB,$myShip,$myColony,$myUser;
	if ($posx1 > $posx2)
	{
		$x = $posx1-$posx2;
		$xd = 1;
	}
	elseif ($posx1 < $posx2)
	{
		$x = $posx2-$posx1;
		$xd = 2;
	}
	if ($posy1 > $posy2)
	{
		$y = $posy1-$posy2;
		$yd = 1;
	}
	elseif ($posy1 < $posy2)
	{
		$y = $posy2-$posy1;
		$yd = 2;
	}
	if ($xd || $yd)
	{
		$rand = rand(1,2);
		if (($xd == 1) && (($rand == 1) || !$yd))
		{
			$newx = $posx1-1;
			$newy = $posy1;
		}
		if (($xd == 2) && (($rand == 1) || !$yd))
		{
			$newx = $posx1+1;
			$newy = $posy1;
		}
		if (($yd == 1) && (($rand == 2) || !$xd))
		{
			$newx = $posx1;
			$newy = $posy1-1;
		}
		if (($yd == 2) && (($rand == 2) || !$xd))
		{
			$newx = $posx1;
			$newy = $posy1+1;
		}
	}
	if ($newx && $newy)	$result = $myShip->move($id,$newx,$newy,$userId,0,1);
	if (($posx2 == $newx) && ($posy2 == $newy))
	{
		if ($id == 723)
		{
			if (($newx == 26) && ($newy == 36)) mysql_query("UPDATE stu_ships_ki SET endx=111,endy=125 WHERE ships_id=".$id."",$myDB->dblink);
			if (($newx == 111) && ($newy == 125)) mysql_query("UPDATE stu_ships_ki SET endx=26,endy=36 WHERE ships_id=".$id."",$myDB->dblink);
		}
		if ($id == 1360)
		{
			if (($newx == 1) && ($newy == 96))
			{
				mysql_query("UPDATE stu_ships_ki SET endx=5,endy=119 WHERE ships_id=".$id."",$myDB->dblink);
				$myShip->deactivatevalue($id,"schilde_aktiv",$userId);
				$result = $myShip->transferto($id,1677,26,10,$userId,"col",0);
				$myShip->activatevalue($id,"schilde_aktiv",$userId);
			}
			if (($newx == 5) && ($newy == 119))
			{
				mysql_query("UPDATE stu_ships_ki SET endx=1,endy=96 WHERE ships_id=".$id."",$myDB->dblink);
				mysql_query("INSERT INTO stu_ships_storage (ships_id,user_id,goods_id,count) VALUES ('".$id."','".$userId."','26','".rand(1,10)."')",$myDB->dblink);
			}
		}
	}
	if ($wp == 1)
	{
		$wpdat = mysql_fetch_array(mysql_query("SELECT * FROM stu_ships_ki_waypoints WHERE ships_id=".$id." AND aktiv=1",$myDB->dblink));
		if (($newx == $wpdat[coords_x]) && ($newy == $wpdat[coords_y]))
		{
			mysql_query("UPDATE stu_ships_ki_waypoints SET aktiv=0 WHERE id=".$wpdat[id]."",$myDB->dblink);
			mysql_query("UPDATE stu_ships_ki_waypoints SET aktiv=1 WHERE id=".$wpdat[nwp]."",$myDB->dblink);
		}
	}
}
$result = mysql_query("SELECT * FROM stu_ships_ki",$myDB->dblink);
while($data=mysql_fetch_array($result))
{
	$data1 = mysql_fetch_array(mysql_query("SELECT id,coords_x,coords_y,energie,kss,lss,user_id,schilde_aktiv,ships_rumps_id FROM stu_ships WHERE id=".$data[ships_id]."",$myDB->dblink));
	if (($data1[energie] < 5) && ($data1[ships_rumps_id] != 171)) continue;
	if (($data1[kss] == 0) && ($data1[ships_rumps_id] != 171)) $myShip->activatevalue($data1[id],"kss",$data1[user_id]);
	if (($data1[lss] == 0) && ($data1[ships_rumps_id] != 171)) $myShip->activatevalue($data1[id],"lss",$data1[user_id]);
	if (($data1[schilde_aktiv] == 0) && ($data1[ships_rumps_id] != 171)) $myShip->activatevalue($data1[id],"schilde_aktiv",$data1[user_id]);
	if (($data1[energie] == 0) && ($data1[ships_rumps_id] != 171)) $myShip->ebatt($data1[id],5,$data1[user_id]);
	$res = mysql_query("SELECT * FROM stu_ships_ki_waypoints WHERE ships_id=".$data1[id]." AND aktiv=1",$myDB->dblink);
	if (mysql_num_rows($res) == 1)
	{
		$wpdat = mysql_fetch_array($res);
		kimove($data[ships_id],$data1[coords_x],$data1[coords_y],$wpdat[coords_x],$wpdat[coords_y],$data1[user_id],1);
	}
	else kimove($data[ships_id],$data1[coords_x],$data1[coords_y],$data[endx],$data[endy],$data1[user_id]);
}
?>