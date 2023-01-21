<?php
function kometmv($posx1,$posy1,$posx2,$posy2)
{
	global $myDB;
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
	$myDB->query("UPDATE stu_map_special SET coords_x=".$newx.",coords_y=".$newy." WHERE type=5");
	$wpdat = $myDB->query("SELECT * FROM stu_ships_ki_waypoints WHERE ships_id='k1' AND aktiv=1",4);
	if ($newx == $wpdat[coords_x] && $newy == $wpdat[coords_y])
	{
		$myDB->query("UPDATE stu_ships_ki_waypoints SET aktiv=0 WHERE id=".$wpdat[id]);
		$myDB->query("UPDATE stu_ships_ki_waypoints SET aktiv=1 WHERE id=".$wpdat[nwp]);
	}
}
function randomexit()
{
	global $myDB;
	$field = $myDB->query("SELECT coords_x as x,coords_y as y FROM stu_map_fields WHERE type=1 AND wese=1 AND coords_x < 200 ORDER BY RAND() LIMIT 1",4);
	return $field;
}
/*
-- intern/proceed/stellar-proceed.php

-- Autor: Daniel Jakob
-- Aktuelle Version: 0.3
-- Zuletzt geändert: 23.03.04 (dj)
-- Erstellt am: 14.12.03 (dj)
-- Version-History:
---0.3 (dj)
---{
--- Steuerung für das Wurmloch hinzugefügt (Ausgangsänderung alle 2 Stunden)
---}
---0.2 (dj)
---{
--- Steuerung für einen wandernden Ionensturm hinzugefügt
---}
---0.1 (dj)
---{
--- Script zur Steuerung von stellaren Phänomenen wie Ionenstürme, Subraumspalten, etc
---}
*/
// Steuerung einzelner Ionenstürme in der Arcadia-Ausdehnung
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."/class/db.class.php");
$myDB = new db;
include_once($global_path."class/game.class.php");
$myGame = new game;
$result = $myDB->query("SELECT id FROM stu_map_special WHERE type=1 ANd id<=20");
while($data=mysql_fetch_assoc($result))
{
	$res = $myDB->query("SELECT coords_x,coords_y FROM stu_map_fields WHERE coords_x BETWEEN 1 AND 20 AND coords_y BETWEEN 80 AND 140 ORDER BY RAND() LIMIT 1",4);
	$myDB->query("UPDATE stu_map_special SET coords_x=".$res[coords_x].",coords_y=".$res[coords_y]." WHERE id=".$data[id]);
}
// Steuerung des großen Ionensturms
$ff = $myDB->query("SELECT coords_x,coords_y FROM stu_map_special WHERE id=21",4);
$tx = $myDB->query("SELECT value FROM stu_game WHERE fielddescr='ios_x'",1);
$ty = $myDB->query("SELECT value FROM stu_game WHERE fielddescr='ios_y'",1);
if (($tx == $ff[coords_x]) && ($ty == $ff[coords_y])) $error = 1;
if ($tx < $ff[coords_x]) $handlerx = -1;
if ($tx > $ff[coords_x]) $handlerx = 1;
if ($ty < $ff[coords_y]) $handlery = -1;
if ($ty > $ff[coords_y]) $handlery = 1;
$result = $myDB->query("SELECT * FROM stu_map_special WHERE type=1 AND id>20 ANd id<=33");
for ($i=0;$i<mysql_num_rows($result);$i++)
{
	$data = mysql_fetch_assoc($result);
	if (($data[coords_x]+$handlerx > 0) && ($data[coords_x]+$handlerx <= $mapfields[max_x]) && ($data[coords_y]+$handlery > 0) && ($data[coords_y]+$handlery <= $mapfields[max_y]))
	{
		$newpos[$i][id] = $data[id];
		$newpos[$i][x] = $data[coords_x]+$handlerx;
		$newpos[$i][y] = $data[coords_y]+$handlery;
	}
	else break;
}
if ($error == 0) for ($i=0;$i<count($newpos);$i++) $myDB->query("UPDATE stu_map_special SET coords_x=".$newpos[$i][x].",coords_y=".$newpos[$i][y]." WHERE id=".$newpos[$i][id]);
else
{
	$newx = rand(1,$mapfields[max_x]);
	$newy = rand(1,$mapfields[max_y]);
	$myDB->query("UPDATE stu_game SET value='".$newx."' WHERE fielddescr='ios_x'");
	$myDB->query("UPDATE stu_game SET value='".$newy."' WHERE fielddescr='ios_y'");
}
// Steuerung anderer Anomalien
$result = $myDB->query("SELECT * FROM stu_map_special WHERE type=5 AND wese=1");
while($data=mysql_fetch_assoc($result))
{
	$wp = $myDB->query("SELECT * FROM stu_ships_ki_waypoints WHERE ships_id='k1' AND aktiv=1",4);
	kometmv($data[coords_x],$data[coords_y],$wp[coords_x],$wp[coords_y]);
}
$result = $myDB->query("SELECT * FROM stu_map_special WHERE type=3 AND wese=1");
while($data=mysql_fetch_assoc($result))
{
	$rand = rand(1,4);
	if ($rand == 1 && $data[coords_x]+1 <= $mapfields[max_x]) { $newx = $data[coords_x]+1; $newy = $data[coords_y]; }
	if ($rand == 2 && $data[coords_x]-1 >= 1) { $newx = $data[coords_x]-1; $newy = $data[coords_y]; }
	if ($rand == 3 && $data[coords_y]+1 <= $mapfields[max_y]) { $newx = $data[coords_x]; $newy = $data[coords_y]+1; }
	if ($rand == 4 && $data[coords_y]-1 >= 1) { $newx = $data[coords_x]; $newy = $data[coords_y]-1; }
	if ($newx && $newy) $myDB->query("UPDATE stu_map_special SET coords_x=".$newx.",coords_y=".$newy." WHERE wese=1 AND id=".$data[id]);
}
// Wurmlochsteuerung
if (rand(1,4) == 4)
{
	$pos[1][x] = 6;
	$pos[1][y] = 97;
	$pos[2][x] = 73;
	$pos[2][y] = 33;
	$pos[3][x] = 90;
	$pos[3][y] = 129;
	$pos[4][x] = 152;
	$pos[4][y] = 127;
	$pos[5][x] = 112;
	$pos[5][y] = 31;
	$sel = rand(1,6);
	if ($sel == 6)
	{
		$hallowolv = randomexit();
		$pos[6][x] = $hallowolv[x];
		$pos[6][y] = $hallowolv[y];
	}
	$myDB->query("UPDATE stu_wormholes SET end_x=".$pos[$sel][x].",end_y=".$pos[$sel][y]." WHERE id=1");
	$myDB->query("UPDATE stu_wormholes SET start_x=".$pos[$sel][x].",start_y=".$pos[$sel][y]." WHERE id=2");
}
?>