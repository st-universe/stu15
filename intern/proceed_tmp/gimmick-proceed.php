<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;
$base_m = 15;
$base_l = 10;
$base_n = 15;
$base_g = 60;
$base_k = -55;
$base_d = -100;
$base_x = 75;
$base_h = 15;
$base_j = 220;
$result = $myDB->query("SELECT id,colonies_classes_id,weather,user_id,bev_free,name FROM stu_colonies");
while($data=mysql_fetch_assoc($result))
{
	$rand = rand(-10,10);
	if ($data[colonies_classes_id] == 1) $nt = $base_m+$rand;
	if ($data[colonies_classes_id] == 2) $nt = $base_l+$rand;
	if ($data[colonies_classes_id] == 3) $nt = $base_n+$rand;
	if ($data[colonies_classes_id] == 4) $nt = $base_g+$rand;
	if ($data[colonies_classes_id] == 5) $nt = $base_k+$rand;
	if ($data[colonies_classes_id] == 6) $nt = $base_d+$rand;
	if ($data[colonies_classes_id] == 7) $nt = $base_h+$rand;
	if ($data[colonies_classes_id] == 8) $nt = $base_x+$rand;
	if ($data[colonies_classes_id] == 9) $nt = $base_j+$rand;
	$rand = rand(1,1000);
	$res2 = $myDB->query("SELECT id FROM stu_colonies_orbit WHERE buildings_id=47 AND aktiv=1 AND colonies_id=".$data[id]);
	if (mysql_num_rows($res2) == 1)
	{
		if ($rand <= 600) $nw = 7;
		if (($rand > 600) && ($rand <= 800)) $nw = 1;
		if ($rand > 800) $nw = 4;
		$nt = $nt + 10;
	}
	else
	{
		if ($data[weather] == 1)
		{
			if ($rand <= 300) $nw = 7;
			if (($rand > 300) && ($rand <= 700)) $nw = 1;
			if (($rand > 700) && ($rand <= 900)) $nw = 4;
			if ($rand > 900) $nw = 2;
			if (($nt < 1) && (($nw == 2) || ($nw == 4))) $nw = 6;
		}
		if ($data[weather] == 2)
		{
			if ($rand <= 300) $nw = 7;
			if (($rand > 300) && ($rand <= 800)) $nw = 1;
			if (($rand > 800) && ($rand <= 950)) $nw = 4;
			if ($rand > 950) $nw = 2;
			if (($nt < 1) && (($nw == 2) || ($nw == 4))) $nw = 6;
		}
		if ($data[weather] == 3)
		{
			if ($rand <= 100) $nw = 3;
			if ($rand > 100) $nw = 7;
		}
		if ($data[weather] == 4)
		{
			if ($rand <= 300) $nw = 7;
			if (($rand > 300) && ($rand <= 850)) $nw = 1;
			if (($rand > 850) && ($rand <= 900)) $nw = 4;
			if ($rand > 900) $nw = 2;
			if (($nt < 1) && (($nw == 2) || ($nw == 4))) $nw = 6;
		}
		if ($data[weather] == 5)
		{
			if ($rand <= 50) $nw = 5;
			if ($rand > 50) $nw = 7;
		}
		if ($data[weather] == 6)
		{
			if ($rand <= 300) $nw = 7;
			if (($rand > 300) && ($rand <= 900)) $nw = 1;
			if ($rand > 900) $nw = 2;
			if (($nt < 1) && ($nw == 2)) $nw = 6;
		}
		if ($data[weather] == 7)
		{
			if ($rand <= 200) $nw = 7;
			if (($rand > 200) && ($rand <= 800)) $nw = 1;
			if (($rand > 800) && ($rand <= 900)) $nw = 4;
			if ($rand > 900) $nw = 2;
			if (($nt < 1) && (($nw == 2) || ($nw == 4))) $nw = 6;
		}
	}
	if ($data[colonies_classes_id] == 6)
	{
		if ($rand <= 10) $nw = 3;
		if ($rand > 10) $nw = 7;
	}
	if ($data[colonies_classes_id] == 4)
	{
		if ($rand <= 150) $nw = 8;
		if ($rand > 150) $nw = 7;
	}
	if ($data[colonies_classes_id] == 5)
	{
		if ($rand <= 100) $nw = 9;
		if (($rand > 100) && ($rand <= 600)) $nw = 6;
		if ($rand > 600) $nw = 1;
	}
	if ($data[colonies_classes_id] == 9) $nw = 1;
	if ($nw == 9)
	{
		$data[weather] == 9 ? $chg = 20 : $chg = 10;
		if (rand(1,100) < $chg)
		{
			$dmgA = $myDB->query("SELECT id,integrity,field_id FROM stu_colonies_fields WHERE buildings_id>0 AND colonies_id=".$data[id]." ORDER BY RAND() LIMIT 1");
			$dmg = rand(1,5);
			if ($dmgA[integrity]-$dmg > 0)
			{
				$myDB->query("UPDATE stu_colonies_fields SET integrity=".($dmgA[integrity]-$dmg)." WHERE id=".$dmgA[id]);
				$myDB->query("INSERT INTO stu_pms (recipient,sender,message,date,cate) VALUES ('".$data[user_id]."','2','Auf der Kolonie ".stripslashes($data[name])." wurde das Gebäude auf Feld ".($dmgA[field_id]+1)." durch einen Sandsturm um ".$dmg." beschädigt',NOW(),'4')");
			}
		}
	}
	if (($nw == 4) && (mysql_num_rows($res2) == 0))
	{
		$data[weather] == 4 ? $chg = 10 : $chg = 1;
		if (rand(1,100) < $chg)
		{
			if ($data[bev_free] > 0)
			{
				$rand = rand(1,$data[bev_free]);
				$myDB->query("UPDATE stu_colonies SET bev_free=bev_free-".$rand." WHERE id=".$data[id]."");
				$myDB->query("INSERT INTO stu_pms (recipient,sender,message,date,cate) VALUES ('".$data[user_id]."','2','Auf der Kolonie ".stripslashes($data[name])." sind ".$rand." Einwohner durch Überschwemmungen ums Leben gekommen',NOW(),'4')");
			}
		}
	}
	$myDB->query("UPDATE stu_colonies SET weather=".$nw.",temp=".$nt." WHERE id=".$data[id]);
}
?>