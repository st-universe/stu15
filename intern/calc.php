<?php
include_once("../inc/config.inc.php");
include_once("../class/db.class.php");
$myDB = new db;
if (!$id) {
	$result = mysql_query("SELECT * FROM stu_ships_rumps",$myDB->dblink);
	echo "<form action=calc.php method=post>
		  <select name=id>";
	for ($i=0;$i<mysql_num_rows($result);$i++) {
		$data = mysql_fetch_array($result);
		echo "<option value=".$data[id].">".$data[name]."</option>";
	}
	echo "</select> <input type=submit value=Anzeigen></form>";
} else {
	$result = mysql_query("SELECT * FROM stu_ships_rumps WHERE id=".$id."",$myDB->dblink);
	$data = mysql_fetch_array($result);
	if ($sent == 1) {
		$bm = ceil((($data[huelle]*2)/3 + $data[shields]/3 + $data[cloak]*($data[huelle]/10) + $data[phaser] + $data[energie]/10));
		$dur = ceil(($data[huelle]/3) + ($data[shields]/4) + ($data[cloak]*($data[huelle]/15)) + $data[motor] + $data[energie]/10);
		$tri = ceil($data[huelle]/5 + $data[shields]/6);
		$ni = ceil($data[shields]/6);
		$kel = ceil($data[huelle]/6);
		$iso = ceil(($data[huelle] + $data[shields])/15);
		$plasma = ceil($data[energie]/8 + $data[motor]/2);
		$gel = ceil(($data[huelle] + $data[shields])/30);
		mysql_query("UPDATE stu_ships_rumps SET eps_cost=".ceil(($kel+$bm+$dur+$tri+$ni+$iso+$gel)/4)." WHERE id='".$data[id]."'",$myDB->dblink);
		mysql_query("DELETE FROM stu_ships_cost WHERE ships_rumps_id=".$id."",$myDB->dblink);
		mysql_query("DELETE FROM stu_ships_goods WHERE ships_rumps_id=".$id."",$myDB->dblink);
		if ($g3) {
			mysql_query("INSERT INTO stu_ships_cost (ships_rumps_id,goods_id,count) VALUES ('".$data[id]."','3','".$bm."')",$myDB->dblink);
			mysql_query("INSERT INTO stu_ships_goods (ships_rumps_id,goods_id) VALUES ('".$id."','3')",$myDB->dblink);
		}
		if ($g6) {
			mysql_query("INSERT INTO stu_ships_cost (ships_rumps_id,goods_id,count) VALUES ('".$data[id]."','6','".$dur."')",$myDB->dblink);
			mysql_query("INSERT INTO stu_ships_goods (ships_rumps_id,goods_id) VALUES ('".$id."','6')",$myDB->dblink);
		}
		if ($g9) {
			mysql_query("INSERT INTO stu_ships_cost (ships_rumps_id,goods_id,count) VALUES ('".$data[id]."','9','".$tri."')",$myDB->dblink);
			mysql_query("INSERT INTO stu_ships_goods (ships_rumps_id,goods_id) VALUES ('".$id."','9')",$myDB->dblink);
		}
		if ($g10) {
			mysql_query("INSERT INTO stu_ships_cost (ships_rumps_id,goods_id,count) VALUES ('".$data[id]."','10','".$iso."')",$myDB->dblink);
			mysql_query("INSERT INTO stu_ships_goods (ships_rumps_id,goods_id) VALUES ('".$id."','10')",$myDB->dblink);
		}
		if ($g12) {
			mysql_query("INSERT INTO stu_ships_cost (ships_rumps_id,goods_id,count) VALUES ('".$data[id]."','12','".$kel."')",$myDB->dblink);
			mysql_query("INSERT INTO stu_ships_goods (ships_rumps_id,goods_id) VALUES ('".$id."','12')",$myDB->dblink);
		}
		if ($g14) {
			mysql_query("INSERT INTO stu_ships_cost (ships_rumps_id,goods_id,count) VALUES ('".$data[id]."','14','".$ni."')",$myDB->dblink);
			mysql_query("INSERT INTO stu_ships_goods (ships_rumps_id,goods_id) VALUES ('".$id."','14')",$myDB->dblink);
		}
		if ($g15) {
			mysql_query("INSERT INTO stu_ships_cost (ships_rumps_id,goods_id,count) VALUES ('".$data[id]."','15','".$plasma."')",$myDB->dblink);
			mysql_query("INSERT INTO stu_ships_goods (ships_rumps_id,goods_id) VALUES ('".$id."','15')",$myDB->dblink);
		}
		if ($g19) {
			mysql_query("INSERT INTO stu_ships_cost (ships_rumps_id,goods_id,count) VALUES ('".$data[id]."','19','".$gel."')",$myDB->dblink);	
			mysql_query("INSERT INTO stu_ships_goods (ships_rumps_id,goods_id) VALUES ('".$id."','19')",$myDB->dblink);
		}
	}
	$result = mysql_query("SELECT * FROM stu_ships_rumps WHERE id=".$id."",$myDB->dblink);
	$data = mysql_fetch_array($result);
	$cost = mysql_query("SELECT * FROM stu_ships_cost WHERE ships_rumps_id=".$id." ORDER BY goods_id",$myDB->dblink);
	for ($i=0;$i<mysql_num_rows($cost);$i++) $costdata[$i] = mysql_fetch_array($cost);
	$goods = mysql_query("SELECT * FROM stu_ships_goods WHERE ships_rumps_id=".$id."",$myDB->dblink);
	for ($i=0;$i<mysql_num_rows($goods);$i++) $gooddata[$i] = mysql_fetch_array($goods);
	$list = mysql_query("SELECT * FROM stu_goods",$myDB->dblink);
	for ($i=0;$i<mysql_num_rows($list);$i++) $goodlist[$i] = mysql_fetch_array($list);
	echo "Klasse: ".$data[name]."<br><br>
		  Kosten:<br>
		  Energie: ".$data[eps_cost]."<br>";
	for ($i=0;$i<count($costdata);$i++) echo "<img src=../gfx/goods/".$costdata[$i][goods_id].".gif> ".$costdata[$i]['count']."<br>";
	echo "<br><br>Baumaterialen:<br>
	<form action=calc.php method=post>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=sent value=1>";
	for ($i=1;$i<=count($goodlist);$i++) {
		if (($i == 3) || ($i == 6) || ($i == 9) || ($i == 10) || ($i == 12) || ($i == 14) || ($i == 15) || ($i == 19)) {
			$goods = mysql_query("SELECT * FROM stu_ships_goods WHERE ships_rumps_id=".$id." ANd goods_id=".$i."",$myDB->dblink);
			echo mysql_error();
			if (mysql_num_rows($goods) > 0) $check = " CHECKED";
			else unset($check);
			echo "<img src=../gfx/goods/".$i.".gif> <input type=checkbox name=g".$i."".$check."><br>";
		}
	}
	echo "<input type=submit value=Ändern></form><br><br>
	<a href=calc.php>Zurück zur Übersicht</a>";
}
?>