<?php
if ($myUser->uhasperr == 1 && $section != "goodview")
{
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td width=100% class=tdmain>/ <strong>Handelsallianz</strong></td>
	</tr>
	</table><br>
	<table bgcolor=#262323 cellpadding=1 cellspacing=1 width=300>
	<tr>
		<td class=tdmainobg>Ihnen ist der Zugriff auf die Datenbanken der Handelsallianz nicht gestattet</td>
	</tr>
	</table>";
	exit;
}
if (!$section)
{
	echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain>/ <strong>Handelsallianz</strong></td>
	</tr>
	</table><br>";
	echo "
	<table width=500 cellspacing=1 cellpadding=1>
	<tr>
		<td valign=top width=300>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
		<tr>
			<td class=tdmain align=center>Dienstleistungen</td>
		</tr>
		<tr>
			<td class=tdmainobg>";
			if ($myUser->ulevel < 8) echo "<a href=main.php?page=hally&section=getship>Schiff beantragen</a><br>
			<a href=main.php?page=hally&section=getlevel>Höheres Kolonistenlevel beantragen</a><br>";
			if ($myUser->ulevel < 2) echo "<a href=main.php?page=hally&section=freemclass>Freie Klasse-M Planeten</a><br>";
			echo "<a href=main.php?page=comm&section=writepm&recipient=5>Kontakt aufnehmen</a></td>
		</tr>
		</Table>
		</td>
		<td valign=top width=300>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
		<tr>
			<td class=tdmain align=center>Informationen</td>
		</tr>
		<tr>
			<td class=tdmainobg>
			<a href=main.php?page=hally&section=slist>Siedlerliste</a><br>
			<a href=main.php?page=hally&section=buildings>Gebäudetypen</a><br>
			<a href=main.php?page=hally&section=mapdb>Kartenfelder</a><br>
			<a href=main.php?page=hally&section=shipclassesdb>Schiffsrümpfe</a><br>
			<a href=main.php?page=hally&section=shipmoddb>Schiffsmodule</a><br>
			<a href=main.php?page=hally&section=planclasses>Planetentypen</a><br>
			<a href=?page=hally&section=placedb>Wichtige Orte</a><br>
			<a href=?page=hally&section=ivor>Vertrag von Ivor</a></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>";
}
elseif ($section == "getship")
{
	if ($stationid)
	{
		if ($shipid == 1) $fullship = $myUser->getcolship($user);
		if (($fullship == 1) && ($shipid == 1)) $return = $myShip->spawnship(1,1,$stationid);
		elseif (($fullship == 0) && ($shipid == 1)) $return = $myShip->spawnship(1,0,$stationid);
		elseif (($shipid == 4) || ($shipid == 5)) $return = $myShip->spawnship($shipid,0,$stationid);
	}
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Schiff beantragen</strong></td>
	</tr>
	</table><br>";
	if ($return) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$return[msg]."</td></tr></table><br>";
	echo "<table width=50% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<form action=main.php method=post>
	<input type=hidden name=page value=hally>
	<input type=hidden name=section value=getship>
	<tr>
		<td class=tdmainobg colspan=4><input type=radio name=shipid value=1 CHECKED> Kolonieschiff (100/1000 Sympathie)</td>
	</tr>
	<tr>
		<td class=tdmainobg colspan=4><input type=radio name=shipid value=4> Tanker (Kosten: 100/1000 Sympathie)</td>
	</tr>
	<tr>
		<td class=tdmainobg colspan=4><input type=radio name=shipid value=5> Frachter (Kosten: 100/1000 Sympathie)</td>
	</tr>
	</table><br>
	<table width=50% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td colspan=5 class=tdmain align=center><strong>Startpunkt</strong></td>
	</tr>";
	$data = $myShip->getShipsbyClass(2);
	for ($i=0;$i<count($data);$i++)
	{
		$freem = $myColony->getclassm($data[$i][coords_x],$data[$i][coords_y],$data[$i][wese]);
		if ($freem != 0) $freem = count($freem);
		if ($myUser->ulevel == 0) $fradd = "<img src=".$grafik."/planets/1.gif title='Klasse M Planet'><br>".$freem;
		echo "<tr>
				<td class=tdmainobg align=center><img src=".$grafik."/ships/".$data[$i][ships_rumps_id].".gif></td>
			  	<td class=tdmainobg>".$data[$i][name]."</td>
			  	<td class=tdmainobg>".$data[$i][coords_x]."/".$data[$i][coords_y]." (".$data[$i][wese].")</td>
			  	<td class=tdmainobg align=center><input class=button type=radio name=stationid value=".$data[$i][id]."></td>
				<td class=tdmainobg align=center>".$fradd."</td></tr>";
	}
	echo "<tr>
		<td colspan=5 class=tdmainobg align=center><input class=button type=submit value='Schiff beantragen'></td>
	</tr></form>";
}
elseif ($section == "buildings")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Gebäudetypen</strong></td>
	</tr>
	</table>
	<br>
	<table width=400>";
	$bla = 0;
	include_once("inc/buildcost.inc.php");
	$res = $myColony->getbuildings();
	while($buildings=mysql_fetch_assoc($res))
	{
		if ($bla == 0) echo "<tr>";
		if ($buildings[id] == 16) $pic = "<img src=".$grafik."/buildings/16_1.gif>";
		elseif ($buildings[id] == 15) $pic = "<img src=".$grafik."/buildings/15_1.gif>";
		elseif ($buildings[id] == 81) $pic = "<img src=".$grafik."/buildings/81_1.gif>";
		elseif ($buildings[id] == 39) $pic = "<img src=".$grafik."/buildings/39_15.gif>";
		elseif ($buildings[id] == 135) $pic = "<img src=".$grafik."/buildings/135_12.gif>";
		elseif ($buildings[id] == 89) $pic = "<img src=".$grafik."/buildings/89_1.gif>";
		elseif ($buildings[id] == 108) $pic = "<img src=".$grafik."/buildings/108_1.gif> <img src=".$grafik."/buildings/109_1.gif> <img src=".$grafik."/buildings/110_1.gif> <img src=".$grafik."/buildings/111_1.gif> <img src=".$grafik."/buildings/112_1.gif>";
		elseif ($buildings[id] == 115) $pic = "<img src=".$grafik."/buildings/115_1.gif> <img src=".$grafik."/buildings/116_1.gif> <img src=".$grafik."/buildings/117_1.gif> <img src=".$grafik."/buildings/118_1.gif> <img src=".$grafik."/buildings/119_1.gif>";
		elseif ($buildings[id] == 122) $pic = "<img src=".$grafik."/buildings/122_1.gif> <img src=".$grafik."/buildings/123_1.gif> <img src=".$grafik."/buildings/124_1.gif> <img src=".$grafik."/buildings/125_1.gif> <img src=".$grafik."/buildings/126_1.gif>";
		elseif ($buildings[id] == 129) $pic = "<img src=".$grafik."/buildings/129_1.gif> <img src=".$grafik."/buildings/130_1.gif> <img src=".$grafik."/buildings/131_1.gif> <img src=".$grafik."/buildings/132_1.gif> <img src=".$grafik."/buildings/133_1.gif>";
		elseif ($buildings[id] == 210) $pic = "<img src=".$grafik."/buildings/210_1.gif> <img src=".$grafik."/buildings/211_1.gif> <img src=".$grafik."/buildings/212_1.gif> <img src=".$grafik."/buildings/213_1.gif> <img src=".$grafik."/buildings/215_1.gif>";
		elseif ($buildings[id] == 168) $pic = "<img src=".$grafik."/buildings/168_1.gif>";
		elseif (($buildings[id] > 26) && ($buildings[id] < 31)) $pic = "<img src=".$grafik."/buildings/".$buildings[id]."_12.gif>";
		elseif (($buildings[id] > 62) && ($buildings[id] < 67)) $pic = "<img src=".$grafik."/buildings/".$buildings[id]."_1.gif>";
		elseif ($buildings[id] == 78) $pic = "<img src=".$grafik."/buildings/78_1.gif>";
		else $pic = "<img src=".$grafik."/buildings/".$buildings[id]."_".$buildings[type].".gif>";
		if ($buildings[id] != 39 && ($buildings[id] <= 108 || $buildings[id] >= 113) && ($buildings[id] <= 115 || $buildings[id] >= 120) && ($buildings[id] <= 122 || $buildings[id] >= 127) && ($buildings[id] <= 129 || $buildings[id] >= 134) && $buildings[id] != 171 && $buildings[id] != 172 && $buildings[id] != 173 && $buildings[id] != 174 && $buildings[id] != 175 && $buildings[id] != 197 && $buildings[id] != 203 && $buildings[id] != 206&& $buildings[id] != 207)
		{
			$goods = $myDB->query("SELECT count,goods_id,mode FROM stu_buildings_goods WHERE buildings_id=".$buildings[id]);
			echo "<td class=tdmainobg width=200 height=380 valign=top><table bgcolor=#262323 height=380 width=200><tr><td class=tdmainobg height=380 width=200 valign=top><strong>".$buildings[name]."</strong><br>".$pic."<br>&nbsp;<br>";
			$pf = $myColony->getfieldsbybuilding($buildings[id]);
			if (mysql_num_rows($pf) != 0)
			{
				while($p=mysql_fetch_assoc($pf)) if ($p[type] != 42) echo "<img src=".$grafik."/fields/".$p[type].".gif width=16 height=16 border=0> ";
			}
			else echo " Upgrade ";
			if ($buildings[research_id] == 0) echo "<br>&nbsp;<br> Ab Level ".$buildings[level]."<br>";
			else
			{
				$forschung = $myColony->getresearchbyidbone($buildings[research_id]);
				$forschung[rasse] == 0 ? print("<br>&nbsp;<br> <img src=".$grafik."/buttons/forsch2.gif alt='Forschung'> ".$forschung[name]."<br>" ) : print("<br>&nbsp;<br> <img src=".$grafik."/rassen/".$forschung[rasse]."s.gif alt='Forschung'> ".$forschung[name]."<br>");
			}
			echo "<strong>Baukosten</strong><br>
			<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'> ".$buildings[eps_cost]."<br>";
			$cost = getbuildingcostbyid($buildings[id]);
			for ($j=0;$j<count($cost);$j++) echo "<img src=".$grafik."/goods/".$cost[$j][goods_id].".gif alt='".$cost[$j][name]."'> ".$cost[$j]['count']."<br>";
			$timeh = floor($buildings[buildtime]/3600);
			$timed = $buildings[buildtime]-($timeh*3600);
			$timem = floor($timed/60);
			$times = $timed-($timem*60);
	 		$time = $timeh."h ".$timem."m ".$times."s";
			echo "<br><img src=".$grafik."/buttons/time.gif alt='Bauzeit'> ".$time."<br><img src=".$grafik."/buttons/integ.gif alt='Integrität'> ".$buildings[integrity]."<br>";
			echo "<br>+/-<br>";
			if ($buildings[eps] > 0) echo "<img src=".$grafik."/buttons/eps.gif>+".$buildings[eps]."<br>";
			if ($buildings[lager] > 0) echo "<img src=".$grafik."/buttons/lager.gif>+".$buildings[lager]."<br>";
			if ($buildings[eps_pro] > 0)
			{
				if ($buildings[id] == 80) echo "<img src=".$grafik."/buttons/e_trans2.gif>+ Abhängig von Gravitation<br>";
				else echo "<img src=".$grafik."/buttons/e_trans2.gif>+".$buildings[eps_pro]."<br>";
			}
			elseif ($buildings[eps_min] > 0) echo "<img src=".$grafik."/buttons/e_trans2.gif>-".$buildings[eps_min]."<br>";
			if ($buildings[bev_pro] > 0) echo "<img src=".$grafik."/buttons/crew.gif>+".$buildings[bev_pro]."<br>";
			if ($buildings[bev_use] > 0) echo "<img src=".$grafik."/buttons/crew.gif>-".$buildings[bev_use]."<br>";
			while($g=mysql_fetch_assoc($goods))
			{
				if ($g[mode] == 1) echo "<img src=".$grafik."/goods/".$g[goods_id].".gif>+". $g['count']."<br>";
				elseif ($g[mode] == 2) echo "<img src=".$grafik."/goods/".$g[goods_id].".gif>-". $g['count']."<br>";
			}
			if ($buildings[schilde] > 0) echo "<img src=".$grafik."/buttons/shld.gif>+".$buildings[schilde]."<br>";	
			if ($buildings[points] > 0) echo "<img src=".$grafik."/buttons/points.gif>+".$buildings[points]."<br>";
			if ($buildings[blimit] > 0) echo "<img src=".$grafik."/buttons/classm.gif alt='Limit/Planet'> ".$buildings[blimit];
			echo "</td></tr></table></td>";
			$bla += 1;
			if ($bla == 4)
			{
				echo "</tr><tr>";
				$bla = 0;
			}
		}
	}
	echo "</table>";
}
elseif ($section == "slist")
{
	if ($rb)
	{
		unset($stxt);
		unset($way);
		unset($sort);
		unset($se);
	}
	if (!$se || $se < 1) $se = 1;
	$seiten = "<tr><td class=tdmainobg colspan=8>Seite ";
	if (is_string($stxt)) $ac = $myDB->query("SELECT COUNT(id) FROM stu_user WHERE user LIKE '%".addslashes(strip_tags($stxt))."%'",1);
	else $ac = $myDB->query("SELECT COUNT(id) FROM stu_user",1);
	for ($i=1;$i<=ceil($ac/50);$i++)
	{
		if ($se == $i) $seiten .= " ".($i != 1 ? "| " : "")."<b>".$i."</b>";
		else $seiten .= " ".($i != 1 ? "| " : "")."<a href=main.php?page=hally&section=slist&se=".$i."&sort=".$sort."&way=".$way."&stxt=".strip_tags($stxt).">".$i."</a>";
	}
	$seiten .= " (".$ac." Siedler)</td></tr>";
	if (is_string($stxt)) $result = $myUser->findUser($stxt,$sort,$way,$se);
	else $result = $myUser->getUser($sort,$way,$se);
	$gameData = $myGame->getcurrentround();
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Siedlerliste</strong></td>
	</tr>
	</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323><form action=main.php method=post>
	<input type=hidden name=page value=hally>
	<input type=hidden name=section value=slist>
	<input type=hidden name=a value=search><tr>
	<td class=tdmainobg colspan=8>Suche <input type=text class=text size=20 name=stxt> <input type=submit class=button value=Suche> <input type=submit class=button name=rb value=Reset></td>
	</tr></form>".$seiten."
	<tr>
		<td class=tdmain align=center width=60>Id</td>
		<td class=tdmain align=center>Name</td>
		<td class=tdmain align=center>Sympathie</td>
		<td class=tdmain align=center>Runden</td>
		<td class=tdmain align=center>Level</td>
		<td class=tdmain align=center>Allianz</td>
		<td class=tdmain></td>
		<td class=tdmain></td>
	</tr>
	<tr>
		<td class=tdmain align=center><a href=main.php?page=hally&section=slist&sort=id&way=up&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pup.gif border=0></a> <a href=main.php?page=hally&section=slist&sort=id&way=down&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pdown.gif border=0></a></td>
		<td class=tdmain align=center><a href=main.php?page=hally&section=slist&sort=name&way=up&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pup.gif border=0></a> <a href=main.php?page=hally&section=slist&sort=name&way=down&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pdown.gif border=0></a></td>
		<td class=tdmain align=center><a href=main.php?page=hally&section=slist&sort=symp&way=up&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pup.gif border=0></a> <a href=main.php?page=hally&section=slist&sort=symp&way=down&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pdown.gif border=0></a></td>
		<td class=tdmain align=center><a href=main.php?page=hally&section=slist&sort=round&way=up&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pup.gif border=0></a> <a href=main.php?page=hally&section=slist&sort=round&way=down&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pdown.gif border=0></a></td>
		<td class=tdmain></td>
		<td class=tdmain align=center><a href=main.php?page=hally&section=slist&sort=ally&way=up&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pup.gif border=0></a> <a href=main.php?page=hally&section=slist&sort=ally&way=down&stxt=".strip_tags($stxt)."><img src=".$grafik."/buttons/pdown.gif border=0></a></td>
		<td class=tdmain></td>
		<td class=tdmain></td>
	</tr>";
	while($settler=mysql_fetch_assoc($result))
	{
		$i++;
		$settler[last_tsp]<time()-604800 ? $ina = "<font color=Red>*</font>" : $ina = "";
		$settler[vac] == 1 ? $vac = "<font color=Yellow>*</font>" : $vac = "";
		echo "<tr>
			<td class=tdmainobg>".$settler[id].$ina.$vac."</td>
			<td class=tdmainobg>".stripslashes($settler[user])."</td>
			<td class=tdmainobg>".$settler[symp]."</td>
			<td class=tdmainobg>".($gameData[runde]-$settler[startrunde])."</td>
			<td class=tdmainobg align=center>".$settler[level]."</td>
			<td class=tdmainobg>".$settler[name]."</td>
			<td class=tdmainobg align=center><img src=".$grafik."/rassen/".$settler[rasse]."s.gif></td>
			<td class=tdmainobg><a href=main.php?page=hally&section=sinfo&id=".$settler[id]." onMouseOver=cp('msg".$i."','buttons/info2') onMouseOut=cp('msg".$i."','buttons/info1')><img src='".$grafik."/buttons/info1.gif' name=msg".$i." border=0></a></td>
		</tr>";
	}
	echo $seiten."</table>";
}
elseif ($section == "mapdb")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Kartenfelder</strong></td>
	</tr>
	</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/1.gif></td>
		<td class=tdmainobg width=30%>Weltraum</td>
		<td class=tdmainobg width=65%>keine Auswirkungen</td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=90% class=tdmain align=center colspan=3><b>Nebel</b></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/2.gif></td>
		<td class=tdmainobg width=30%>dünner Nebel</td>
		<td class=tdmainobg width=65%>-1 Energie beim Einflug, +3 Bussard-Kollektoren</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/3.gif></td>
		<td class=tdmainobg width=30%>dichter Nebel</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug, Sensorenausfall, +6 Bussard Kollektoren</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/16.gif></td>
		<td class=tdmainobg width=30%>Metreongasnebel</td>
		<td class=tdmainobg width=65%>Bei Phaserschüssen Gefahr durch explodierendes Metreongas</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/21.gif></td>
		<td class=tdmainobg width=30%>Ceruleanischer nebel </td>
		<td class=tdmainobg width=65%>Schilde nicht aktivierbar</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/30.gif></td>
		<td class=tdmainobg width=30%>Plasmanebel</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug, Plasmavorkommen: Bussard/3</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/31.gif></td>
		<td class=tdmainobg width=30%>Mutara-Nebel</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug, Sensoren nicht aktivierbar</td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=90% class=tdmain align=center colspan=3><b>Asteroidenfelder</b></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/4.gif></td>
		<td class=tdmainobg width=30%>dünnes Asteroidenfeld (Iridium-Erz)</td>
		<td class=tdmainobg width=65%>-1 Energie beim Einflug, Vorkommen: 4</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/5.gif></td>
		<td class=tdmainobg width=30%>dichtes Asteroidenfeld (Iridium-Erz)</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug, Vorkommen: 6</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/17.gif></td>
		<td class=tdmainobg width=30%>dünnes Asteroidenfeld (Kelbonit-Erz)</td>
		<td class=tdmainobg width=65%>-1 Energie beim Einflug, Vorkommen: 2</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/18.gif></td>
		<td class=tdmainobg width=30%>dichtes Asteroidenfeld (Kelbonit-Erz)</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug, Vorkommen: 3</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/19.gif></td>
		<td class=tdmainobg width=30%>dünnes Asteroidenfeld (Nitrium-Erz)</td>
		<td class=tdmainobg width=65%>-1 Energie beim Einflug, Vorkommen: 2</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/20.gif></td>
		<td class=tdmainobg width=30%>dichtes Asteroidenfeld (Nitrium-Erz)</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug, Vorkommen: 3</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/32.gif></td>
		<td class=tdmainobg width=30%>dichtes Asteroidenfeld (Eis)</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug, Deuteriumvorkommen: Sammler/3</td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=90% class=tdmain align=center colspan=3><b>Stellare Objekte</b></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/11.gif></td>
		<td class=tdmainobg width=30%>schwarzes Loch</td>
		<td class=tdmainobg width=65%>20% Hüllenschaden beim Einflug</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/13.gif></td>
		<td class=tdmainobg width=30%>Röntgenpulsar</td>
		<td class=tdmainobg width=65%>Schild- und Sensorenausfall beim Einflug</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/14.gif></td>
		<td class=tdmainobg width=30%>Neutronenstern</td>
		<td class=tdmainobg width=65%>Schildschaden, Crew flieht bei deaktivierten Schilden</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/15.gif></td>
		<td class=tdmainobg width=30%>Quasar</td>
		<td class=tdmainobg width=65%>???</td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=90% class=tdmain align=center colspan=3><b>Sonstige / Anomalien</b></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/12.gif></td>
		<td class=tdmainobg width=30%>Wurmloch</td>
		<td class=tdmainobg width=65%>Durchflug möglich, Kosten: 13 Energie</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/27.gif></td>
		<td class=tdmainobg width=30%>Borg-Trümmergürtel</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/28.gif></td>
		<td class=tdmainobg width=30%>Tachyonnebel</td>
		<td class=tdmainobg width=65%>-2 Energie beim Einflug</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=5%><img src=".$grafik."/map/22.gif></td>
		<td class=tdmainobg width=30%>Subraumspalt</td>
		<td class=tdmainobg width=65%>Schiff verschwindet in den Weiten des Subraums....</td>
	</tr>
	</table>";
}
elseif ($section == "shipclassesdb")
{
	$ships = $myShip->getClasses();
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Schiffsrümpfe</strong></td>
	</tr>
	</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>";
	for ($i=0;$i<count($ships);$i++)
	{
		if (($ships[$i][sorta] != $ls) && (($ships[$i][sorta] == 0) || ($ships[$i][sorta] == 1) || ($ships[$i][sorta] == 2) || ($ships[$i][sorta] == 3) || ($ships[$i][sorta] == 4) || ($ships[$i][sorta] == 5)))
		{
			echo "<tr>
				<td class=tdmainobg></td>
				<td class=tdmainobg><strong>Name</strong></td>
				<td class=tdmainobg align=center><img src=".$grafik."/goods/50.gif alt='Hüllenlevel anzahl/min/max'></td>
				<td class=tdmainobg align=center><img src=".$grafik."/goods/58.gif alt='Schildlevel anzahl/min/max'></td>
				<td class=tdmainobg align=center><img src=".$grafik."/goods/62.gif alt='Waffenlevel anzahl/min/max'></td>
				<td class=tdmainobg align=center><img src=".$grafik."/goods/87.gif alt='Reaktorlevel anzahl/min/max'></td>
				<td class=tdmainobg align=center><img src=".$grafik."/goods/55.gif alt='Computerlevel anzahl/min/max'></td>
				<td class=tdmainobg align=center><img src=".$grafik."/goods/75.gif alt='Antriebslevel anzahl/min/max'></td>
				<td class=tdmainobg align=center><img src=".$grafik."/goods/83.gif alt='Sensorlevel anzahl/min/max'></td>
				<td class=tdmainobg align=center><img src=".$grafik."/goods/79.gif alt='EPS-Gitterlevel anzahl/min/max'></td>
				<td class=tdmainobg align=center><strong>Fusion</strong></td>
				<td class=tdmainobg align=center><strong>Crew</strong></td>
				<td class=tdmainobg align=center><strong>Ladung</strong></td>
				<td class=tdmainobg align=center></td></td>
			</tr>";
			$ls = $ships[$i][sorta];
		}
		echo "<tr>
			<td class=tdmainobg><img src=".$grafik."/ships/".$ships[$i][id].".gif></td>
			<td class=tdmainobg>".$ships[$i][name]."</td>
			<td class=tdmainobg align=center>".$ships[$i][huellmod]."/<font color=Green>".$ships[$i][huellmod_min]."</font>/<font color=Lime>".$ships[$i][huellmod_max]."</font></td>
			<td class=tdmainobg align=center>".$ships[$i][schildmod]."/<font color=Green>".$ships[$i][schildmod_min]."</font>/<font color=Lime>".$ships[$i][schildmod_max]."</font></td>
			<td class=tdmainobg align=center>".$ships[$i][waffenmod]."/<font color=Green>".$ships[$i][waffenmod_min]."</font>/<font color=Lime>".$ships[$i][waffenmod_max]."</font></td>
			<td class=tdmainobg align=center><font color=Green>".$ships[$i][reaktormod_min]."</font>/<font color=Lime>".$ships[$i][reaktormod_max]."</font></td>
			<td class=tdmainobg align=center><font color=Green>".$ships[$i][computermod_min]."</font>/<font color=Lime>".$ships[$i][computermod_max]."</font></td>
			<td class=tdmainobg align=center><font color=Green>".$ships[$i][antriebsmod_min]."</font>/<font color=Lime>".$ships[$i][antriebsmod_max]."</font></td>
			<td class=tdmainobg align=center>".$ships[$i][sensormod]."/<font color=Green>".$ships[$i][sensormod_min]."</font>/<font color=Lime>".$ships[$i][sensormod_max]."</font></td>
			<td class=tdmainobg align=center>".$ships[$i][epsmod]."/<font color=Green>".$ships[$i][epsmod_min]."</font>/<font color=Lime>".$ships[$i][epsmod_max]."</font></td>
			<td class=tdmainobg align=center>".$ships[$i][fusion]."</td>
			<td class=tdmainobg align=center>".$ships[$i][crew_min]." / ".$ships[$i][crew]."</td>
			<td class=tdmainobg align=center>".$ships[$i][storage]."</td>
			<td class=tdmainobg align=center>(<a href=?page=shiphelp&section=rump&class=".$ships[$i][id]." target=leftbottom>?</a>)</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "shipmoddb")
{
	$mods1 = $myColony->getmodulebytype(1);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Schiffsmodule</strong></td>
	</tr>
	</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain align=center colspan=6>Hüllenmodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Hülle</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonderheit</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		$modcost = $myColony->getmodulecostbyid($mods1[$i][id]);
		$kosten = " ".$mods1[$i][ecost]."<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'>";
		for ($j=0;$j<count($modcost);$j++) $kosten .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][huell]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(3);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=6>Schildmodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Schilde</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonderheit</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		$modcost = $myColony->getmodulecostbyid($mods1[$i][id]);
		$kosten = " ".$mods1[$i][ecost]."<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'>";
		for ($j=0;$j<count($modcost);$j++) $kosten .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][shields]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(6);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=6>EPS-Module</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>EPS</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonderheit</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		$modcost = $myColony->getmodulecostbyid($mods1[$i][id]);
		$kosten = " ".$mods1[$i][ecost]."<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'>";
		for ($j=0;$j<count($modcost);$j++) $kosten .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][eps]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(2);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=7>Computermodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Treffer</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Ausweich</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonderheit</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		$modcost = $myColony->getmodulecostbyid($mods1[$i][id]);
		$kosten = " ".$mods1[$i][ecost]."<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'>";
		for ($j=0;$j<count($modcost);$j++) $kosten .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][phaser_chance]."%</td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][torp_evade]."%</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(5);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=7>Antriebsmodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Treffer</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Ausweich</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonderheit</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		$modcost = $myColony->getmodulecostbyid($mods1[$i][id]);
		$kosten = " ".$mods1[$i][ecost]."<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'>";
		for ($j=0;$j<count($modcost);$j++) $kosten .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][phaser_chance]."%</td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][torp_evade]."%</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(7);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=6>Sensormodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>LSS</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonderheit</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		$modcost = $myColony->getmodulecostbyid($mods1[$i][id]);
		$kosten = " ".$mods1[$i][ecost]."<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'>";
		for ($j=0;$j<count($modcost);$j++) $kosten .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][lss_range]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(8);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=6>Reaktormodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Reaktor</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonderheit</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		$modcost = $myColony->getmodulecostbyid($mods1[$i][id]);
		$kosten = " ".$mods1[$i][ecost]."<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'>";
		for ($j=0;$j<count($modcost);$j++) $kosten .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][reaktor]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(4);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=7>Waffenmodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Schaden</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Treffer</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonderheit</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		$modcost = $myColony->getmodulecostbyid($mods1[$i][id]);
		$kosten = " ".$mods1[$i][ecost]."<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'>";
		for ($j=0;$j<count($modcost);$j++) $kosten .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][phaser]."</td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][phaser_chance]."%</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "getlevel")
{
	if ($myUser->ulevel >= 8) exit;
	if ($action == "setlevel") $result = $myUser->setlevel($user);
	$data = $myUser->getnextlevel($user);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Kolonistenlevel beantragen</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr>
	<tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table width=40% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmainobg>Derzeitiges Level: ".$myUser->ulevel." - Sympathie: ".$myUser->usymp."</td>
	</tr>
	<tr>
		<td class=tdmainobg>Sympathie benötigt für Level ".$data[level].": ".$data[symp]."</td>
	</tr>
	<tr>
		<td class=tdmainobg><a href=main.php?page=hally&section=getlevel&action=setlevel>Level Beantragen</a></td>
	</tr>
	</table>";
}
elseif ($section == "planclasses")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Planetentypen</strong></td>
	</tr>
	</table><br>
	<table cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td colspan=4 class=tdmain align=center width=420><strong>Ab Level 1</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=1><img src=".$grafik."/planets/1.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=1><img src=".$grafik."/map/6.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=1><center><strong>Klasse M</strong></center></a></td>
		<td class=tdmainobg width=120>Erdähnlich</td>
	</tr>";
	$myUser->ulevel < 4 ?$col = "<font color=red><strong>Ab Level 4</strong></font>" : $col = "<strong>Ab Level 4</strong>";
	echo "<tr><td colspan=4 class=tdmain align=center width=420>".$col."</td></tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=2><img src=".$grafik."/planets/2.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=2><img src=".$grafik."/map/7.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=2><center><strong>Klasse L</strong></center></a></td>
		<td class=tdmainobg width=120>Waldplanet</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=3><img src=".$grafik."/planets/3.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=3><img src=".$grafik."/map/8.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=3><center><strong>Klasse N</strong></center></a></td>
		<td class=tdmainobg width=120>Wasserplanet</td>
	</tr>";
	$myUser->ulevel < 6 ? $col = "<font color=red><strong>Ab Level 6</strong></font>" : $col = "<strong>Ab Level 6</strong>";
	echo "<tr><td colspan=4 class=tdmain align=center width=420>".$col."</td></tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=4><img src=".$grafik."/planets/4.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=4><img src=".$grafik."/map/10.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=4><center><strong>Klasse G</strong></center></a></td>
		<td class=tdmainobg width=120>Wüstenplanet</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=5><img src=".$grafik."/planets/5.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=5><img src=".$grafik."/map/9.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=5><center><strong>Klasse K</strong></center></a></td>
		<td class=tdmainobg width=120>Eisplanet</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=6><img src=".$grafik."/planets/6.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=6><img src=".$grafik."/map/4.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=6><center><strong>Klasse D</strong></center></a></td>
		<td class=tdmainobg width=120>Asteroid</td>
	</tr>";
	$myUser->ulevel < 8 ? $col = "<font color=red><strong>Ab Level 8</strong></font>" : $col = "<strong>Ab Level 8</strong>";
	echo "<tr><td colspan=4 class=tdmain align=center width=420>".$col."</td></tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=7><img src=".$grafik."/planets/7.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=7><img src=".$grafik."/map/23.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=7><center><strong>Klasse H</strong></center></a></td>
		<td class=tdmainobg width=120>Ödlandplanet</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=8><img src=".$grafik."/planets/8.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=8><img src=".$grafik."/map/24.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=8><center><strong>Klasse X</strong></center></a></td>
		<td class=tdmainobg width=120>Lavaplanet</td>
	</tr>";
	if ($myColony->getuserresearch(182,$user) != 1) $col = "<font color=red><strong>Nach Forschung</strong></font>";
	else $col = "<strong>Nach Forschung</strong>";
	echo "<tr><td colspan=4 class=tdmain align=center width=420>".$col."</td></tr>
	<tr>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=9><img src=".$grafik."/planets/9.gif border=0></a></td>
		<td class=tdmainobg width=30><a href=?page=hally&section=planclass&id=9><img src=".$grafik."/map/25.gif border=0></a></td>
		<td class=tdmainobg width=240><a href=?page=hally&section=planclass&id=9><center><strong>Klasse J</strong></center></a></td>
		<td class=tdmainobg width=120>Gasriese</td>
	</tr>";
	echo "<tr><td colspan=4 class=tdmain align=center width=420><font color=darkgray><strong>Keine Daten verfügbar</strong></font></td></tr>";
	echo "<tr>
		<td class=tdmainobg width=30><img src=".$grafik."/planets/10.gif border=0></a></td>
		<td class=tdmainobg width=30><img src=".$grafik."/map/29.gif border=0></a></td>
		<td class=tdmainobg width=240><center><strong>Klasse R</strong></center></a></td>
		<td class=tdmainobg width=120>Wanderer</td>
	</tr>";
	echo "</table>";
}
elseif ($section == "planclass")
{
	if (($id < 1) || ($id > 9)) exit;
	$data = $myColony->getmines($id);
	for ($i=0;$i<18;$i++) {
		if ($i == 0) $omap .= "<tr></tr><tr>";
		$omap .= "<td class=collist><img src=".$grafik."/fields/12.gif border=0></td>";
		if ($i == 8) $omap .= "</tr><tr>";
		if ($i == 17) $omap .= "</tr>";
	}
	if ($id == 1) 
	{
		$mapf = 6;
		$classname = "Klasse M";
		include_once("intern/inc/m.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44)) $map .= "</tr><tr>";
			if ($i == 53) $map .= "</tr>";
		}
		unset($fields);
		include_once("intern/inc/um.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $umap .= "<tr></tr><tr>";
			$umap .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17)) $umap .= "</tr><tr>";
			if ($i == 26) $umap .= "</tr>";
		}
	} 
	elseif ($id == 2) 
	{
		$mapf = 7;
		$classname = "Klasse L";
		include_once("intern/inc/l.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44)) $map .= "</tr><tr>";
			if ($i == 53) $map .= "</tr>";
		}
		unset($fields);
		include_once("intern/inc/ul.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $umap .= "<tr></tr><tr>";
			$umap .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17)) $umap .= "</tr><tr>";
			if ($i == 26) $umap .= "</tr>";
		}
	}
	elseif ($id == 3) 
	{
		$mapf = 8;
		$classname = "Klasse N";
		include_once("intern/inc/n.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44)) $map .= "</tr><tr>";
			if ($i == 53) $map .= "</tr>";
		}
		unset($fields);
		include_once("intern/inc/un.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $umap .= "<tr></tr><tr>";
			$umap .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17)) $umap .= "</tr><tr>";
			if ($i == 26) $umap .= "</tr>";
		}
	}
	elseif ($id == 4) 
	{
		$mapf = 10;
		$classname = "Klasse G";
		include_once("intern/inc/g.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44)) $map .= "</tr><tr>";
			if ($i == 53) $map .= "</tr>";
		}
		unset($fields);
		include_once("intern/inc/ug.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $umap .= "<tr></tr><tr>";
			$umap .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17)) $umap .= "</tr><tr>";
			if ($i == 26) $umap .= "</tr>";
		}
	}
	elseif ($id == 5) 
	{
		$mapf = 9;
		$classname = "Klasse K";
		include_once("intern/inc/k.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44)) $map .= "</tr><tr>";
			if ($i == 53) $map .= "</tr>";
		}
		unset($fields);
		include_once("intern/inc/uk.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $umap .= "<tr></tr><tr>";
			$umap .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17)) $umap .= "</tr><tr>";
			if ($i == 26) $umap .= "</tr>";
		}
	}
	elseif ($id == 6) 
	{
		$mapf = 4;
		$classname = "Klasse D";
		include_once("intern/inc/d.inc.php");
		for ($i=0;$i<count($fields);$i++)
		{
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 6) || ($i == 13) || ($i == 20) || ($i == 27)) $map .= "</tr><tr>";
			if ($i == 34) $map .= "</tr>";
		}
		unset ($omap);
		for ($i=0;$i<14;$i++)
		{
			if ($i == 0) $omap .= "<tr>";
			$omap .= "<td class=collist><img src=".$grafik."/fields/12.gif border=0></td>";
			if ($i == 6) $omap .= "</tr><tr>";
			if ($i == 13) $omap .= "</tr>";
		}
	}
	elseif ($id == 9) 
	{
		$mapf = 25;
		$classname = "Klasse J";
		include_once("intern/inc/j.inc.php");
		for ($i=0;$i<count($fields);$i++)
		{
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 6) || ($i == 13) || ($i == 20) || ($i == 27)) $map .= "</tr><tr>";
			if ($i == 34) $map .= "</tr>";
		}
		unset ($omap);
		for ($i=0;$i<7;$i++)
		{
			if ($i == 0) $omap .= "<tr>";
			$omap .= "<td class=collist><img src=".$grafik."/fields/37.gif border=0></td>";
		}
		$omap .= "</tr><tr>";
		for ($i=0;$i<7;$i++)
		{
			$omap .= "<td class=collist><img src=".$grafik."/fields/12.gif border=0></td>";
		}
		$omap .= "</tr>";
	}
	elseif ($id == 7) 
	{
		$mapf = 23;
		$classname = "Klasse H";
		include_once("intern/inc/h.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44)) $map .= "</tr><tr>";
			if ($i == 53) $map .= "</tr>";
		}
		unset($fields);
		include_once("intern/inc/uh.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $umap .= "<tr></tr><tr>";
			$umap .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17)) $umap .= "</tr><tr>";
			if ($i == 26) $umap .= "</tr>";
		}
	}
	elseif ($id == 8) 
	{
		$mapf = 24;
		$classname = "Klasse X";
		include_once("intern/inc/x.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $map .= "<tr></tr><tr>";
			$map .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44)) $map .= "</tr><tr>";
			if ($i == 53) $map .= "</tr>";
		}
		unset($fields);
		include_once("intern/inc/ux.inc.php");
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $umap .= "<tr></tr><tr>";
			$umap .= "<td class=collist><img src=".$grafik."/fields/".$fields[$i].".gif border=0></td>";
			if (($i == 8) || ($i == 17)) $umap .= "</tr><tr>";
			if ($i == 26) $umap .= "</tr>";
		}
	}
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <a href=?page=hally&section=planclasses>Planetentypen</a> /  <strong>".$classname."</strong></td>
	</tr>
	</table><br>";
	echo "<table cellpadding=1 cellspacing=1>
	<tr>
		<td><table cellpadding=1 cellspacing=1 bgcolor=#262323>";
		echo $omap;
		unset($omap);
		echo $map;
		unset($map);
		echo $umap;
		unset($umap);
		echo "</table></td>
		<td valign=top>
			<img src=".$grafik."/planets/".$id.".gif border=0> <img src=".$grafik."/map/".$mapf.".gif border=0>
			<table cellpadding=1 cellspacing=1 bgcolor=#262323>";
			echo "<tr><td class=tdmain align=center><strong>Vorkommen</strong></td></tr>";
			if ($data['mine7'] > 0) echo "<tr><td class=tdmainobg>Iridium-Erz: ".$data['mine7']."</td></tr>";
			if ($data['mine17'] > 0) echo "<tr><td class=tdmainobg>Dilithium: ".$data['mine17']."</td></tr>";
			if ($data['mine33'] > 0) echo "<tr><td class=tdmainobg>Kelbonit-Erz: ".$data['mine33']."</td></tr>";
			if ($data['mine34'] > 0) echo "<tr><td class=tdmainobg>Nitrium-Erz: ".$data['mine34']."</td></tr>";
			if ($data['mine74'] > 0) echo "<tr><td class=tdmainobg>Iridium-Erz (T): ".$data['mine74']."</td></tr>";
			if ($data['mine75'] > 0) echo "<tr><td class=tdmainobg>Kelbonit-Erz (T): ".$data['mine75']."</td></tr>";
			if ($data['mine76'] > 0) echo "<tr><td class=tdmainobg>Nitrium-Erz (T):".$data['mine76']."</td></tr>";
			echo "<tr><td class=tdmainobg>Atmosphäre: ".$data[atmos]."</td></tr>
			</table>
		</td>
	</tr>
	</table>";
}
elseif ($section == "freemclass")
{
	if ($myUser->ulevel > 1) exit;
	$data = $myMap->getfreem();
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Freie Klasse-M Planeten</strong></td>
	</tr>
	</table><br>
	<table cellpadding=1 cellspacing=1 bgcolor=#262323>";
	for ($i=0;$i<count($data);$i++)
	{
		echo "<tr>
			<td class=tdmainobg align=center><img src=".$grafik."/planets/1.gif></td>
			<td class=tdmainobg align=center>".$data[$i][coords_x]."/".$data[$i][coords_y]."</td>
			<td class=tdmainobg align=center>".$data[$i][wese]."</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "goodview")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=stats>Statistiken</a> / <strong>Warenübersicht</strong></td>
	</tr>
	</table><br>
	<table width=400 cellpadding=1 cellspacing=1 bgcolor=#262323>";
	$data = $myColony->getcolstoragesum();
	if ($data != 0) for ($i=0;$i<count($data);$i++) $goods[$data[$i][goods_id]]['count'] += $data[$i][gcount];
	$data = $myShip->getshipstoragesum();
	if ($data != 0) for ($i=0;$i<count($data);$i++) $goods[$data[$i][goods_id]]['count'] += $data[$i][gcount];
	$data = $myTrade->getkontobyuser($user);
	if ($data != 0)	for ($i=0;$i<count($data);$i++) $goods[$data[$i][goods_id]]['count'] += $data[$i]['count'];
	$j = 0;
	if (count($goods) == 0) echo "<tr><td colspan=2 class=tdmainobg>Keine Waren vorhanden</td></tr>";
	else
	{
		$list = $myColony->goodlist();
		for ($i=0;$i<count($list);$i++) if ($goods[$list[$i][id]]['count'])
		{
			$gg += $goods[$list[$i][id]]['count'];
			if ($j == 0) echo "<tr>";
			echo "<td class=tdmainobg><img src=".$grafik."/goods/".$list[$i][id].".gif title='".$list[$i][name]."'> ".$goods[$list[$i][id]]['count']."</td>";
			$j++;
			if ($j == 2)
			{
				echo "</tr>";
				$j = 0;
			}
		}
		if ($j == 1) echo "<td class=tdmainobg>&nbsp;</td></tr>";
	}
	echo "<tr><td colspan=2 class=tdmainobg>Gesamt: ".$gg."</td></tr></table>";
}
elseif ($section == "sinfo")
{
	if ($action == "atc" && $id) $result = $myComm->addcontact($id,0);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain colspan=3>/ <a href=?page=comm>Kommunikation</a> / <strong>Spielerinfo anzeigen</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table width=450 cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain><b>Meldung</b></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table width=400 cellpadding=1 cellspacing=1 bgcolor=#262323>";
	$data = $myUser->getuserbyid($id);
	$profile = $myUser->getUserProfile($id);
	if (($data == 0) || ($id < 101)) echo "<tr><td class=tdmainobg colspan=3 align=center>Spieler nicht vorhanden</td></tr>";
	else
	{
		time() - $data[last_tsp] < 300 ? $status = "<font color=Green>online</font>" : $status = "<font color=Red>offline</font>";
		$data[picture] != "" ? $gfx = stripslashes($data[picture]) : $gfx = $grafik."/rassen/".$data[rasse]."kn.gif";
		if ($data[status] == 8) $aadd = " (Administrator)";
		echo "<tr>
			<td class=tdmainobg rowspan=5 width=70 align=center><img src=".$gfx." width=64 height=64></td>
			<td class=tdmainobg width=290>".stripslashes($data[user])."</td>
			<td class=tdmainobg width=65><img src=".$grafik."/rassen/".$data[rasse]."s.gif>&nbsp;<a href=main.php?page=comm&section=writepm&recipient=".$data[id]." onMouseOver=\"cp('msg','buttons/msg2')\" onMouseOut=\"cp('msg','buttons/msg1')\"><img src=".$grafik."/buttons/msg1.gif name=msg border=0 title='Private Nachricht schreiben'></a>&nbsp;<a href=?page=hally&section=sinfo&action=atc&id=".$data[id]." onmouseover=\"cp('atc','buttons/lese2')\" onmouseout=\"cp('atc','buttons/lese1')\"><img src=".$grafik."/buttons/lese1.gif name=atc border=0 title='Zur Kontaktliste hinzufügen'></a></td></td>
		</tr>
		<tr>
			<td colspan=2 class=tdmainobg>Dabei seit Runde ".$data[startrunde]."</td>
		</tr>
		<tr>
			<td colspan=2 class=tdmainobg>Status: ".$status.$aadd."</td>
		</tr>";
		if ($profile[icq] > 0) echo "<tr><td class=tdmainobg colspan=2>ICQ <a href=http://www.icq.com/whitepages/cmd.php?uin=".$profile[icq]."&action=add target=_blank><img src=http://wwp.icq.com/scripts/online.dll?icq=".$profile[icq]."&img=5 border=0></a></td></tr>";
		if ($profile[regierung] != "")echo "<tr><td class=tdmainobg colspan=2>Regierungsform: ".$profile[regierung]."</td></tr>";
		echo "</table>";
		if ($profile[rpgtxt] != "")
		{
			echo "<br><table width=500 cellpadding=1 cellspacing=1 bgcolor=#262323>
			<tr><td colspan=2 align=center class=tdmain>RPG-Info</td></tr>
			<tr><td class=tdmainobg colspan=2>".nl2br(stripslashes($profile[rpgtxt]))."</td></tr>
			</table>";
		}
	}
	echo "<br><table width=400 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain align=Center colspan=2><a href=\"javascript:history.back()\">zur&uuml;ck</a></td>
	</tr></table>";
}
elseif ($section == "ivor")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Vertrag von Ivor</strong></td>
	</tr>
	</table>
	<br>&nbsp;<br>Vertrag von Ivor, mit Zusätzen<br>reduzierte, vereinfachte Form<br>&nbsp;<br>
	<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmainobg width=30>1.</td>
		<td class=tdmainobg>Die Handelsallianz</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>1.1</td>
		<td class=tdmainobg>Die Handelsallianz ist zuständig für die Verwaltung, Entsendung und Startausstattung aller Kolonisten.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>1.2</td>
		<td class=tdmainobg>Ihre Angebote (Warenbörse, Uplink zu Datenbanken etc.) sind ein Service der Handelsallianz, über die sie frei entscheiden kann. Der Zugang zu diesen Angeboten ist kein Grundrecht und kann nach den Wünschen der Handelsallianz entzogen werden.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>1.3</td>
		<td class=tdmainobg>Die Handelsallianz ist neutral. Sie behandelt alle unterzeichnenden Mächte als gleichwertig und bevorzugt weder eine Großmacht, noch ist sie einer Großmacht unterstellt.</td>
	</tr>
	</table>
	<br>
	<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmainobg width=30>2.</td>
		<td class=tdmainobg>Sektor 75</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>2.1</td>
		<td class=tdmainobg>Sektor 75 ist Niemandsland. Keine Großmacht und kein Verband von Siedlern hat das Recht, Raum innerhalb des Sektors zu annektieren oder für sich zu beansprochen.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>2.2</td>
		<td class=tdmainobg>Ausnahme: Der den Breen zugesprochene Raum. Die Breen entscheiden, ob sie Einflug in diesen gestatten.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>2.3</td>
		<td class=tdmainobg>Ausnahme: Der bereits vorhandene Föderationsraum am südlichen Ende des Sektors bleibt unangetastet. Kolonisation ist nicht gestattet.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>2.4</td>
		<td class=tdmainobg>Es ist den Großmächten sowie der Handelsallianz erlaubt, Anlagen in Sektor 75 zu errichten und den Zugang zu diesen zu Beschränken.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>2.5</td>
		<td class=tdmainobg>Weitere Mächte können eine Erlaubnis bei den Großmächten einholen, um ebenfalls Anlagen und/oder Kolonien errichten zu können.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>2.6</td>
		<td class=tdmainobg>Jeglicher Kontakt zu Pre-Warp Zivilisationen ist untersagt.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>2.7</td>
		<td class=tdmainobg>Es gelten die Verträge von Khitomer sowie das Friedensabkommen von 2378.</td>
	</tr>
	</table>
	<br>
	<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmainobg width=30>3.</td>
		<td class=tdmainobg>Großmächte</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>3.1</td>
		<td class=tdmainobg>Großmächte bezeichnet die Unterzeichner dieses Vertrages, namentlich und in ungeordneter Reihenfolge: Die Vereinte Föderation der Planeten, Das Romulanische Sternenimperium, Das Klingonische Imperium, Die Cardassianische Union, Die Breen Konföderation, Die Ferengi Allianz.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>3.2</td>
		<td class=tdmainobg>Die Großmächte verpflichten sich, diesen Vertrag zu wahren und für seine Einhaltung zu sorgen.</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30>3.3</td>
		<td class=tdmainobg>Mächte, deren Ziel es ist, den Vertrag von Ivor aufzulösen, sind als Feind einzustufen.</td>
	</tr>
	</table>
	<br>
	<br>Diese Darstellung erhebt keinen Anspruch auf Vollständigkeit oder Richtigkeit.";
}
elseif ($section == "placedb")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=hally>Handelsallianz</a> / <strong>Wichtige Orte</strong></td>
	</tr>
	</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/rassen/hakn.gif></td>
		<td class=tdmainobg width=90%><b>Handelsallianz<b></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323 border=0>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Alpha</td>
		<td class=tdmainobg align=center width=10%>25/148</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Beta</td>
		<td class=tdmainobg align=center width=10%>33/60</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Gamma</td>
		<td class=tdmainobg align=center width=10%>50/100</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Delta-2</td>
		<td class=tdmainobg align=center width=10%>70/15</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Epsilon</td>
		<td class=tdmainobg align=center width=10%>98/87</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Zeta</td>
		<td class=tdmainobg align=center width=10%>108/55</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Eta-2</td>
		<td class=tdmainobg align=center width=10%>130/15</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Theta</td>
		<td class=tdmainobg align=center width=10%>133/134</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Iota</td>
		<td class=tdmainobg align=center width=10%>145/80</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Kappa</td>
		<td class=tdmainobg align=center width=10%>179/24</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Lambda</td>
		<td class=tdmainobg align=center width=10%>170/109</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten My</td>
		<td class=tdmainobg align=center width=10%>42/185</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Ny</td>
		<td class=tdmainobg align=center width=10%>151/186</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/hap.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Chi</td>
		<td class=tdmainobg align=center width=10%>45/44 (2)</td>
		<td class=tdmainobg align=center width=50%></td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/rassen/1kn.gif></td>
		<td class=tdmainobg width=90%><font color=#0088FF><b>Föderation</b></font></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323 border=0>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/ds3.gif></td>
		<td class=tdmainobg width=30%>Deep Space 3</td>
		<td class=tdmainobg align=center width=10%>111/125</td>
		<td class=tdmainobg align=center width=50%>Haupt-Föderationsbasis in Sektor 75</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/fep.gif></td>
		<td class=tdmainobg width=30%>Sternenbasis 269</td>
		<td class=tdmainobg align=center width=10%>196/189</td>
		<td class=tdmainobg align=center width=50%>Sternenbasis an der Föderationsgrenze</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/frel.gif></td>
		<td class=tdmainobg width=30%>Relaisstation Sigma-3</td>
		<td class=tdmainobg align=center width=10%>26/36</td>
		<td class=tdmainobg align=center width=50%>Subraum-Kommunikations-Relaisstation</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/frel.gif></td>
		<td class=tdmainobg width=30%>Relaisstation Omikron-6</td>
		<td class=tdmainobg align=center width=10%>74/147</td>
		<td class=tdmainobg align=center width=50%>Subraum-Kommunikations-Relaisstation</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/ftel.gif></td>
		<td class=tdmainobg width=30%>Eagle Array</td>
		<td class=tdmainobg align=center width=10%>172/178</td>
		<td class=tdmainobg align=center width=50%>Subraum-Teleskop</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/f7.gif></td>
		<td class=tdmainobg width=30%>Canarus IV</td>
		<td class=tdmainobg align=center width=10%>197/186</td>
		<td class=tdmainobg align=center width=50%>Kolonie</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/f9.gif></td>
		<td class=tdmainobg width=30%>Ryganon VII</td>
		<td class=tdmainobg align=center width=10%>190/194</td>
		<td class=tdmainobg align=center width=50%>Bergbau-Kolonie</td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/rassen/5kn.gif></td>
		<td class=tdmainobg width=90%><font color=#CC4010><b>Ferengi Allianz</b></font></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323 border=0>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/fl.gif></td>
		<td class=tdmainobg width=30%>Kleo</td>
		<td class=tdmainobg align=center width=10%>46/126</td>
		<td class=tdmainobg align=center width=50%>Erholungs- und Vergnügungskolonie</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/fgp.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Din</td>
		<td class=tdmainobg align=center width=10%>80/60</td>
		<td class=tdmainobg align=center width=50%>Latinumhandel und Bar</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/fgp.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Gid</td>
		<td class=tdmainobg align=center width=10%>46/126</td>
		<td class=tdmainobg align=center width=50%>Latinumhandel und Bar</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/fgp.gif></td>
		<td class=tdmainobg width=30%>Handelsposten Ca</td>
		<td class=tdmainobg align=center width=10%>146/146</td>
		<td class=tdmainobg align=center width=50%>Latinumhandel und Bar</td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/rassen/bajkn.gif></td>
		<td class=tdmainobg width=90%><font color=#A45B45><b>Bajoraner</b></font></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323 border=0>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/baj.gif></td>
		<td class=tdmainobg width=30%>Neu-Bajor</td>
		<td class=tdmainobg align=center width=10%>46/96</td>
		<td class=tdmainobg align=center width=50%>kleine Kolonie von Geistlichen</td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/rassen/thol.gif></td>
		<td class=tdmainobg width=90%><font color=#BB60BB><b>Tholianische Versammlung</b></font></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323 border=0>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/tx.gif></td>
		<td class=tdmainobg width=30%>Außenposten</td>
		<td class=tdmainobg align=center width=10%>175/114</td>
		<td class=tdmainobg align=center width=50%><font color=red>Warnung! Einflug gefährlich!</font></td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/rassen/sona.gif></td>
		<td class=tdmainobg width=90%><font color=#7DABBA><b>Son'a</b></font></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323 border=0>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/sonkol.gif></td>
		<td class=tdmainobg width=30%>Kollektor</td>
		<td class=tdmainobg align=center width=10%>57/176</td>
		<td class=tdmainobg align=center width=50%>Metreon-Kollektor</td>
	</tr>
	</table>
	<br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/rassen/verek.gif></td>
		<td class=tdmainobg width=90%><font color=#22DD88><b>Verekkianische Vereinigung</b></font></td>
	</tr>
	</table>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323 border=0>
	<tr>
		<td class=tdmainobg align=center width=10%><img src=".$grafik."/map/v1.gif></td>
		<td class=tdmainobg width=30%>Verekka</td>
		<td class=tdmainobg align=center width=10%>186/171</td>
		<td class=tdmainobg align=center width=50%>Heimatwelt</td>
	</tr>
	</table>";
}
elseif ($section == "scl")
{
	$result = $myDB->query("SELECT id,colonies_classes_id,name,coords_x,coords_y,energie,schilde_aktiv FROM stu_colonies WHERE user_id=".$user." ORDER BY colonies_classes_id");
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td class=tdmain align=Center colspan=3><b>Kolonieliste</b></td>
	</tr>";
	while($data=mysql_fetch_assoc($result))
	{
		echo "<tr><td class=tdmainobg><a href=http://www.stuniverse.de/main.php?page=colony&section=showcolony&id=".$data[id]." target=main><img src=".$grafik."/planets/".$data[colonies_classes_id].($data[schilde_aktiv] == 1 ? "s" : "").".gif title=\"".strip_tags(stripslashes($data[name]))."\" border=0></a></td><td class=tdmainobg>".$data[energie]."</td><td class=tdmainobg>".$data[coords_x]."/".$data[coords_y]."</td></tr>";
	}
	echo "<tr><td class=tdmainobg align=center colspan=3>[<a href=static/leftbottom.php>Schließen</a>]</td></tr></table>";
}
?>

