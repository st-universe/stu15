<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;
unlink($global_path."/inc/bstats.html");
$fd = fopen($global_path."/inc/bstats.html","a+");
fwrite($fd,"<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
<tr>
	<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Gebäudedetails</strong></td>
</tr>
</table><br>
<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
<tr>
	<td class=tdmainobg width=32></td>
	<td class=tdmainobg><strong>Name</strong></td>
	<td class=tdmainobg align=center><strong>Gesamt</strong></td>
	<td class=tdmainobg align=center><strong>Aktiv</strong></td>
	<td class=tdmainobg align=center><strong>Im Bau</strong></td>
</tr>");
$result = $myDB->query("SELECT a.id,a.name,b.type FROM stu_buildings as a LEFT JOIN stu_field_build as b ON a.id=b.buildings_id WHERE a.view=1 AND a.id!=171 AND a.id!=172 AND a.id!=173 AND a.id!=174 AND a.id!=175 AND a.id!=197 AND a.id!=203 AND a.id!=206 AND a.id!=207 GROUP by a.id ORDER BY a.name");
while($data=mysql_fetch_assoc($result))
{
	$data[ges] = $myDB->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=".$data[id],1)+$myDB->query("SELECT COUNT(id) FROM stu_colonies_orbit WHERE buildings_id=".$data[id],1)+$myDB->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE buildings_id=".$data[id],1);
	$data[bau] = $myDB->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildtime>0 AND buildings_id=".$data[id],1)+$myDB->query("SELECT COUNT(id) FROM stu_colonies_orbit WHERE buildtime>0 AND buildings_id=".$data[id],1)+$myDB->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE buildtime>0 AND buildings_id=".$data[id],1);
	$data[akt] = $myDB->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE aktiv=1 AND buildings_id=".$data[id],1)+$myDB->query("SELECT COUNT(id) FROM stu_colonies_orbit WHERE aktiv=1 AND buildings_id=".$data[id],1)+$myDB->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE aktiv=1 AND buildings_id=".$data[id],1);
	if ($data[id] == 16) $data[type] = 1;
	elseif ($data[id] == 15) $data[type] = 1;
	elseif ($data[id] == 81) $data[type] = 1;
	elseif ($data[id] == 39) $data[type] = 15;
	elseif ($data[id] == 135) $data[type] = 12;
	elseif ($data[id] == 89) $data[type] = 1;
	elseif (($data[id] >= 108) && ($data[id] <= 133))  $data[type] = 1;
	elseif ($data[id] == 168)  $data[type] = 1;
	elseif (($data[id] > 26) && ($data[id] < 31))  $data[type] = 12;
	elseif (($data[id] > 62) && ($data[id] < 67))  $data[type] = 1;
	elseif ($data[id] == 78)  $data[type] = 1;
	fwrite($fd,"<tr>
		<td class=tdmainobg><img src=http://gfx.stuniverse.de/buildings/".$data[id]."_".$data[type].".gif></td>
		<td class=tdmainobg>".$data[name]."</td>");
		$data[ges] == 0 ? fwrite($fd,"<td class=tdmainobg align=center><font color=#888888>-</font></td>") : fwrite($fd,"<td class=tdmainobg align=center>".$data[ges]."</td>");
		$data[akt] == 0 ? fwrite($fd,"<td class=tdmainobg align=center><font color=#888888>-</font></td>") : fwrite($fd,"<td class=tdmainobg align=center><font color=green>".$data[akt]."</font></td>");
		$data[bau] == 0 ? fwrite($fd,"<td class=tdmainobg align=center><font color=#888888>-</font></td>") : fwrite($fd,"<td class=tdmainobg align=center><font color=yellow>".$data[bau]."</font></td>");
	fwrite($fd,"</tr>");
}
fwrite($fd,"</table>");
fclose($fd);

unlink($global_path."/inc/sstats.html");
$fd = fopen($global_path."/inc/sstats.html","a+");
fwrite($fd,"<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
<tr>
	<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Schiffsdetails</strong></td>
</tr>
</table><br>
<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
<tr>
	<td class=tdmainobg></td>
	<td class=tdmainobg><strong>Name</strong></td>
	<td class=tdmainobg align=center><strong>Gesamt</strong></td>
	<td class=tdmainobg align=center><strong>Aktiv</strong></td>
	<td class=tdmainobg align=center><strong>Im Bau</strong></td>
	<td class=tdmainobg align=center><strong>Wracks</strong></td>
</tr>");
$result = $myDB->query("SELECT id,name,crew_min FROM stu_ships_rumps WHERE view=1 ORDER BY sorta ASC,sortb ASC"); 
while($data=mysql_fetch_assoc($result))
{
	$data['gcount'] = $myDB->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id =".$data[id],1);
	$data['tcount'] = $myDB->query("SELECT COUNT(id) FROM stu_ships WHERE trumoldrump =".$data[id],1);
	$data['bcount'] = $myDB->query("SELECT COUNT(id) FROM stu_ships_buildprogress WHERE ships_rumps_id =".$data[id],1);
	$data['acount'] = $myDB->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=".$data[id]." AND crew>=".$data[crew_min],1);
	fwrite($fd,"<tr>
		<td class=tdmainobg><img src=http://gfx.stuniverse.de/ships/".$data[id].".gif></td>
		<td class=tdmainobg>".$data[name]."</td>");
		$data[gcount] == 0 ? fwrite($fd,"<td class=tdmainobg align=center><font color=#888888>-</font></td>") : fwrite($fd,"<td class=tdmainobg align=center>".$data[gcount]."</td>");
		$data[acount] == 0 ? fwrite($fd,"<td class=tdmainobg align=center><font color=#888888>-</font></td>") : fwrite($fd,"<td class=tdmainobg align=center><font color=green>".$data[acount]."</font></td>");
		$data[bcount] == 0 ? fwrite($fd,"<td class=tdmainobg align=center><font color=#888888>-</font></td>") : fwrite($fd,"<td class=tdmainobg align=center><font color=yellow>".$data[bcount]."</font></td>");
		$data[tcount] == 0 ? fwrite($fd,"<td class=tdmainobg align=center><font color=#888888>-</font></td>") : fwrite($fd,"<td class=tdmainobg align=center><font color=red>".$data[tcount]."</font></td>");
	fwrite($fd,"</tr>");
}
fwrite($fd,"</table>");
fclose($fd);
?>