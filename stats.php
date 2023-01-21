<?php
if (!$section)
{
	$player = $myGame->getPlayerStats();
	$cols = $myGame->getColStats();
	$ships = $myGame->getShipStats();
	$ress = $myGame->getRessStats();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ <strong>Statistiken</strong></td>
	</tr>
	</table><br>
	<table>
	<tr>
		<td width=150 valign=top>
		<table width=150 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain align=center>Spielerstats</td>
		</tr>
		<tr>
			<td class=tdmainobg>Spieler: ".$player[active]."<br>
			|-online: ".$player[online]."<br>
			|-im Urlaub: ".$player[vac]."<br>
			|-werden gelöscht: ".$player[nrdel]."<br>
			|-&Oslash; Sympathie: ".$player[symp]."<br>
			|-Föderation: ".$player[fed]."<br>
			|-Romulaner: ".$player[rom]."<br>
			|-Klingonen: ".$player[kli]."<br>
			|-Cardassianer: ".$player[car]."<br>
			|-Ferengi: ".$player[fer]."<br>
			|-<a href=?page=stats&section=allywars>Allianzkriege: ".$player[allywar]."</a>
			</td>
		</tr>
		</table>
		</td>
		<td width=150 valign=top>
		<table width=150 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain align=center>Koloniestats</td>
		</tr>
		<tr>
			<td class=tdmainobg>Kolonisiert: ".$cols[settled]."<br>
			|-Einwohner: ".$cols[bev]."<br>
			|-kol. Klasse M: ".$cols[cm]."<br>
			|-kol. Klasse L: ".$cols[cl]."<br>
			|-kol. Klasse N: ".$cols[cn]."<br>
			|-kol. Klasse G: ".$cols[cg]."<br>
			|-kol. Klasse K: ".$cols[ck]."<br>
			|-kol. Klasse D: ".$cols[cd]."<br>
			|-kol. Klasse H: ".$cols[ch]."<br>
			|-kol. Klasse X: ".$cols[cx]."<br>
			|-kol. Klasse J: ".$cols[cj]."<br>
			</td>
		</tr>
		</table>
		</td>
		<td width=150 valign=top>
		<table width=150 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain align=center>Schiffstats</td>
		</tr>
		<tr>
			<td class=tdmainobg>Schiffe: ".$ships[ships]."<br>
			|-inaktiv: ".$ships[inaktiv]."<br>
			|-&Oslash; Crew/Schiff: ".$ships[crew]."<br>
			|-&Oslash; Torps/Schiff: ".$ships[torp]."<br>
			|-Trümmerfelder: ".$ships[trums]."<br></td>
		</tr>
		</table>
		</td>
		<td width=150 valign=top>
		<table width=150 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain align=center>Ressourcenstats</td>
		</tr>
		<tr>
			<td class=tdmainobg>Ressourcen: ".$ress[ress]."<br>
			|-&Oslash; Ress/Spieler: ".round($ress[ress]/$player[active])."
			</td>
		</tr>
		<tr>
			<td class=tdmain align=Center>Kolonieschau</td>
		</tr>
		<tr>
		<td class=tdmainobg>
		<table width=100% cellspacing=0 cellpadding=0>
		<tr>
			<td class=tdmainobg rowspan=2><img src=".$grafik."/planets/".$cols[wm][colonies_classes_id].".gif title='Kolonieschau'></td>
			<td class=tdmainobg>".stripslashes($cols[wm][name])."</td>
		</tr>
		<tr>
			<td class=tdmainobg>Temperatur ".$cols[wm][temp]."°C</td>
		</tr>
		</table></td>
		</tr>
		</table>
		</td>
		<td width=150 valign=top>
		<table width=150 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain align=center>Wirtschaftsstats</td>
		</tr>
		<tr>
			<td class=tdmainobg>G-Wirtschaft: ";
			$cols[lrw] >= 0 ? print("<font color=green>+".$cols[lrw]." %</font>") : print("<font color=red>".$cols[lrw]." %</font>");
			echo "<br>
			|-&Oslash; Wirtschaft: ".$cols[wirtschaft]."<br>
			|-Arbeitslose %: ".round($cols[jless],2)."<br>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table><br>
	<table>
	<tr>
		<td valign=top>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323>
		<tr>
			<td class=tdmain align=center>Bestenlisten</td>
		</tr>
		<tr>
			<td class=tdmainobg>
			<a href=main.php?page=stats&section=bestcols>Die 10 größten Kolonien</a><br>
			<a href=main.php?page=stats&section=bestfleet>Die 10 größten Flotten</a><br>
			<a href=main.php?page=stats&section=bestweap>Die 10 bestbewaffnetsten Flotten</a><br>
			<a href=main.php?page=stats&section=bestwirt>Die 10 stärksten Wirtschaftsmächte</a><br>
			<a href=main.php?page=stats&section=mostbev>Die 10 bevölkerungsreichsten Kolonisten</a><br>
			<a href=main.php?page=stats&section=mostjobless>Die 10 miesesten Arbeitgeber</a><br>
			<a href=main.php?page=stats&section=bestresearch>Die 10 fortgeschrittensten Kolonisten</a><br>
			<a href=main.php?page=stats&section=richestuser>Die 10 reichsten Kolonisten</a></td>
		</tr>
		<tr>
			<td class=tdmainobg><a href=main.php?page=hally&section=goodview>Warenübersicht</a></td>
		</tr></table>
		</td><td valign=top>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323>
		<tr>
			<td class=tdmain align=center>Detaillisten</td>
		</tr>
		<tr>
			<td class=tdmainobg>
			<a href=main.php?page=stats&section=shipclasses>Details: Schiffe</a><br>
			<a href=main.php?page=stats&section=buildclasses>Details: Gebäude</a><br>
			(Aktualisierung alle 15mins)</td>
		</tr>
		</table>
		</td>";
		$ships = $myDB->query("SELECT ship_count FROM stu_stats WHERE user_id=".$user,1);
		$wirt = $myDB->query("SELECT wirtschaft FROM stu_stats WHERE user_id=".$user,1);
		$bev = $myDB->query("SELECT bev FROM stu_stats WHERE user_id=".$user,1);
		if (!$wirt) $wirt = 0;
		echo "<td valign=top>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323>
		<tr>
			<td class=tdmain align=center>Mein Account</td>
		</tr>
		<tr>
			<td class=tdmainobg>Schiffe: ".$ships." (".($myDB->query("SELECT COUNT(user_id) FROM stu_stats WHERE ship_count>".$ships,1)+1).".)<br>
			Wirtschaft: ".$wirt." (".($myDB->query("SELECT COUNT(user_id) FROM stu_stats WHERE wirtschaft>".$wirt,1)+1).".)<br>
			Bevölkerung: ".$bev." (".($myDB->query("SELECT COUNT(user_id) FROM stu_stats WHERE bev>".$bev,1)+1).".)</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>";
}
elseif ($section == "tick")
{
	$ticks = $myGame->getTickStats();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Tickverlauf</strong></td>
	</tr>
	</table><br>
	<table width=70% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><b>Runde</b></td>
		<td class=tdmain><b>Start</b></td>
		<td class=tdmain><strong>Ende</strong></td>
	</tr>";
	for ($i=0;$i<count($ticks);$i++) {
		$ticks[$i][ende] != 0 ? $ende = date("d.m.Y H:i:s",$ticks[$i][ende]) : $ende = "-";
		echo "<tr>
			<td class=tdmainobg>".$ticks[$i][runde]."</td>
			<td class=tdmainobg>".date("d.m.Y H:i:s",$ticks[$i][start_tsp])."</td>
			<td class=tdmainobg>".$ende."</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "bestcols")
{
	$cols = $myColony->getbestcols();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Die 10 größten Kolonien</strong></td>
	</tr>
	</table><br><br>
	<table width=90% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><b>Koloniename</b></td>
		<td class=tdmain align=center><b>Einwohner</b></td>
		<td class=tdmain><b>Besitzer</b></td>
	</tr>";
	for ($i=0;$i<count($cols);$i++)
	{
		echo "<tr>
			<td class=tdmainobg>".stripslashes($cols[$i][name])."</td>
			<td class=tdmainobg align=center>".$cols[$i][bevcount]."</td>
			<td class=tdmainobg>".stripslashes($cols[$i][user])."</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "mostbev")
{
	$cols = $myColony->getmostbev();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Die 10 bevölkerungsreichsten Kolonisten</strong></td>
	</tr>
	</table><br>
	<table width=50% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg><strong>User</strong></td>
		<td class=tdmainobg><strong>Bevölkerung</strong></td>
	</tr>";
	for ($i=0;$i<count($cols);$i++)
	{
		echo "<tr>
			<td class=tdmainobg>".stripslashes($cols[$i][user])."</td>
			<td class=tdmainobg>".$cols[$i][maxsum]."</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "mostjobless")
{
	$cols = $myColony->getmostjobless();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Die 10 miesesten Arbeitgeber</strong></td>
	</tr>
	</table><br>
	<table width=50% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg><strong>User</strong></td>
		<td class=tdmainobg><strong>Arbeitslose</strong></td>
	</tr>";
	while($data=mysql_fetch_assoc($cols))
	{
		echo "<tr>
			<td class=tdmainobg>".stripslashes($data[user])."</td>
			<td class=tdmainobg>".$data[maxsum]."</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "bestfleet")
{
	if (!$runde)
	{
		$runde = $myGame->getcurrentround();
		$runde = $runde[runde]-1;
	}
	$cols = $myHistory->getShipTtHistory($runde);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Die 10 größten Flotten</strong></td>
	</tr>
	</table><br>
	<table width=70% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><b>User</b></td>
		<td class=tdmain align=center><b>Schiffe</b></td>
	</tr>";
	if ($cols == 0)	echo "<tr><td class=tdmainobg colspan=2 align=Center>Für diese Runde sind keine Daten gespeichert</td></tr>";
	else
	{
		for ($i=0;$i<count($cols);$i++) {
			echo "<tr>
				<td class=tdmainobg>".stripslashes($cols[$i][user])."</td>
				<td class=tdmainobg align=Center>".$cols[$i]['count']."</td>
			</tr>";
		}
	}
	echo "<form action=main.php method=post>
	<input type=hidden name=page value=stats>
	<input type=hidden name=section value=bestfleet>
	<tr>
		<td colspan=2 align=Center class=tdmainobg>Runde anzeigen: <input type=text size=4 name=runde class=text> <input type=submit value=Anzeigen class=button></td>
	</tr></form>
	</table>";
}
elseif ($section == "shipclasses") include_once("inc/sstats.html");
elseif ($section == "buildclasses")	include_once("inc/bstats.html");
elseif ($section == "bestwirt")
{
	$cols = $myColony->getbestwirt();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Die 10 größten Wirtschaftsmächte</strong></td>
	</tr>
	</table><br>
	<table width=50% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain align=center><b>User</b></td>
	</tr>";
	for ($i=0;$i<count($cols);$i++)
	{
		echo "<tr>
			<td class=tdmainobg>".stripslashes($cols[$i][user])."</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "bestresearch")
{
	$cols = $myColony->getbestresearch();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Die 10 fortgeschrittensten User</strong></td>
	</tr>
	</table><br>
	<table width=50% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><b>User</b></td>
		<td class=tdmain align=center><b>Forschungen</b></td>
	</tr>";
	for ($i=0;$i<count($cols);$i++)
	{
		echo "<tr>
			<td class=tdmainobg>".stripslashes($cols[$i][user])."</td>
			<td class=tdmainobg align=Center>".$cols[$i][idcount]."</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "richestuser")
{
	function fsort(&$array, $sort, $d = 1)
	{
  		usort ($array , create_function(
  		'$a,$b',
  		'return strnatcmp($a["'.$sort.'"],$b["'.$sort.'"])* '.$d.';')); 
	}
	$data = $myColony->getrichestuser();
	if (is_array($data))
	{
		fsort($data,'latinum',-1); 
		reset($data);
	}
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Die 10 reichsten Kolonisten</strong></td>
	</tr>
	</table><br>
	<table width=70% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><b>User</b></td>
		<td class=tdmain align=center><b>Latinum</b></td>
	</tr>";
	if (is_array($data))
	{
		foreach($data as $key => $unit)
		{
			echo "<tr>
				<td class=tdmainobg>".stripslashes($unit[user])."</td>
				<td class=tdmainobg align=Center>".$unit[latinum]."</td>
			</tr>";
			if ($key >=9) break;
		}
	}
	else echo "<tr><td class=tdmainobg colspan=2 align=center>Kein Latinum vorhanden</td></tr>";
	echo "</table>";
}
elseif ($section == "bestweap")
{
	function fsort(&$array, $sort, $d = 1)
	{
  		usort ($array , create_function(
  		'$a,$b',
  		'return strnatcmp($a["'.$sort.'"],$b["'.$sort.'"])* '.$d.';')); 
	}
	$result = $myDB->query("SELECT SUM(a.torps) as torps,COUNT(b.id) as shipcount,b.user_id FROM stu_ships_rumps as a LEFT JOIN stu_ships as b ON a.id=b.ships_rumps_id WHERE b.user_id>100 AND torps>0 AND a.id!=5 AND a.slots=0 AND a.id!=65 AND a.id!=66 AND a.id!=67 AND a.id!=68 GROUP BY b.user_id");
	for ($i=0;$i<mysql_num_rows($result);$i++)
	{
		$data[$i] = mysql_fetch_assoc($result);
		$pho = $myDB->query("SELECT SUM(a.count) FROM stu_ships_storage as a LEFT JOIN stu_ships as b ON a.ships_id=b.id WHERE a.user_id=".$data[$i][user_id]." AND a.goods_id=7 AND b.ships_rumps_id!=5 AND b.ships_rumps_id!=65 AND b.ships_rumps_id!=66 AND b.ships_rumps_id!=67 AND b.ships_rumps_id!=68",1);
		$pla = $myDB->query("SELECT SUM(a.count) FROM stu_ships_storage as a LEFT JOIN stu_ships as b ON a.ships_id=b.id WHERE a.user_id=".$data[$i][user_id]." AND a.goods_id=16 AND b.ships_rumps_id!=5 AND b.ships_rumps_id!=65 AND b.ships_rumps_id!=66 AND b.ships_rumps_id!=67 AND b.ships_rumps_id!=68",1);
		$qua = $myDB->query("SELECT SUM(a.count) FROM stu_ships_storage as a LEFT JOIN stu_ships as b ON a.ships_id=b.id WHERE a.user_id=".$data[$i][user_id]." AND a.goods_id=17 AND b.ships_rumps_id!=5 AND b.ships_rumps_id!=65 AND b.ships_rumps_id!=66 AND b.ships_rumps_id!=67 AND b.ships_rumps_id!=68",1);
		$data[$i][points] = ($pho + $pla*2 + $qua*3)*round($data[$i][torps]/$data[$i][shipcount]);
	}
	fsort($data,'points',-1); 
	reset($data);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Die 10 bestbewaffnetsten Flotten</strong></td>
	</tr>
	</table><br>
	<table width=90% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><b>Spieler</b></td>
		<td class=tdmain align=center><b>Punkte</b></td>
	</tr>";
	if (is_array($data))
	{
		foreach($data as $key => $unit)
		{
			echo "<tr>
				<td class=tdmainobg>".stripslashes($myUser->getfield("user",$unit[user_id]))."</td>
				<td class=tdmainobg align=Center>".$unit[points]."</td>
			</tr>";
			if ($key >=9) break;
		}
	}
} elseif ($section == "allywars") {
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Allianzkriege</strong></td>
	</tr>
	</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><b>Allianz</b></td>
		<td class=tdmain><b>Krieg mit</b></td>
		<td class=tdmain><b>Kriegsbeginn</b></td>
	</tr>";
	$result = $myDB->query("SELECT *,UNIX_TIMESTAMP(date) as date_tsp FROM stu_allys_beziehungen WHERE type=1");
	while($data=mysql_fetch_assoc($result))
	{
		echo "<tr>
		<td class=tdmainobg>".stripslashes($myDB->query("SELECT name FROM stu_allys WHERE id=".$data[allys_id1],1))."</td>
		<td class=tdmainobg>".stripslashes($myDB->query("SELECT name FROM stu_allys WHERE id=".$data[allys_id2],1))."</td>
		<td class=tdmainobg>".date("d.m.Y",$data[date_tsp])."</td>
		</tr>";
	}
	echo "</table>";
}
?>
