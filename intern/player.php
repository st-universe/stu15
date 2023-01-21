<?php
if (!$section || ($section == "main")) {
	 ?>
	<table width=100%>
	<tr>
		<td class=tdmaintop colspan=5 align=center width=100%>Spieler / Allgemeine Infos</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?php	echo "<tr><td class=tdmain align=center><form action=index.php method=post><input type=hidden name=page value=player><input type=hidden name=section value=playerwirt>Spielerwirtschaft anzeigen: <input type=text name=userid size=5 class=text> <input type=submit value=Anzeigen></form></td></tr>";
	?>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?php	echo "<tr><td class=tdmain align=center><form action=index.php method=post><input type=hidden name=page value=player><input type=hidden name=section value=weap>Schiffsbewaffnung anzeigen:  <input type=submit value=Anzeigen></form></td></tr>";
	?>
	<?php	echo "<tr><td class=tdmain align=center><form action=index.php method=post><input type=hidden name=page value=player><input type=hidden name=section value=rump>Cheatrümpfe freigeben  <input type=submit value=Anzeigen></form></td></tr>";
	?>
	</table>
	<?php
} elseif ($section == "playerwirt") {
	?>
		<table width=100%>
		<tr>
			<td class=tdmaintop colspan=5 align=center width=100%>Wirtschaft</td>
		</tr>
		<tr>
			<td class=tdmainobg align=center>&nbsp; </td>
		</tr>
	<?php
	$data = $myColony->getcolonylist($userid);
	if ($data == 0) {
		echo "<tr>
			<td class=tdmainobg colspan=7 align=center>Keine Kolonien vorhanden</td>
		</tr>";
	} else {
		for ($i=0;$i<count($data);$i++)	{
			$wirt = $wirt + $data[$i][wirtschaft];
			$fields = $myColony->getcolfields($data[$i][id]);
			for ($j=0;$j<count($fields);$j++) {
				$build = $myColony->getbuildbyid($fields[$j][buildings_id]);
				if ($fields[$j][aktiv] == 1) {
					$points = $points + $build[points];
				}
				if ($build[id] == 4) $points = $points + $build[points];
			}
			$orbit = $myColony->getcolorbit($data[$i][id]);
			for ($j=0;$j<count($orbit);$j++) {
				$build = $myColony->getbuildbyid($orbit[$j][buildings_id]);
				if ($orbit[$j][aktiv] == 1) {
					$points = $points + $build[points];
				}
				if (($build[id] > 25) && ($build[id] < 31)) $points = $points + $build[points];
			}
			$ground = $myColony->getcolunderground($data[$i][id]);
			for ($j=0;$j<count($ground);$j++) {
				$build = $myColony->getbuildbyid($ground[$j][buildings_id]);
				if ($ground[$j][aktiv] == 1) {
					$points = $points + $build[points];
				}
			}

		}

		?>
		<tr>
			<?php echo "<td class=tdmainobg align=left> Punkte: ".$points." (".$wirt.")</td>";
			?>
		</tr>
		<?php
	}



	unset ($points);
	$data = $myShip->getshiplist($userid,id,asc);
	$useriddata = $myUser->getuserbyid($userid);
	if ($data == 0) {
		echo "<tr>
			<td class=tdmainobg colspan=7 align=center>Keine Schiffe vorhanden</td>
		</tr>";
	} else {

		for ($i=0;$i<count($data);$i++)	{
			$class = $myShip->getclassbyid($data[$i][ships_classes_id]);
			$points = $points + $class[points];
		}

		?>
		<tr>
			<?php echo "<td class=tdmainobg align=left> Schiffe: ".$points." (".($wirt+floor($useriddata[symp]/5000)).")</td>";
			?>
		</tr>
		<?php
	}


	echo "</table>";
} elseif ($section == "rump") {


	$ids = $myColony->getuserids();
	for ($i=0;$i<count($ids);$i++)
	{
      		 echo $ids[$i][id]." - ".$ids[$i][rasse]."<br>";

		if ($ids[$i][rasse] == 1)
		{
			$myColony->giverump(12,$ids[$i][id]);
			$myColony->giverump(14,$ids[$i][id]);
			$myColony->giverump(71,$ids[$i][id]);
		}
		elseif ($ids[$i][rasse] == 2)
		{
			$myColony->giverump(20,$ids[$i][id]);
			$myColony->giverump(19,$ids[$i][id]);
			$myColony->giverump(85,$ids[$i][id]);
		}
		elseif ($ids[$i][rasse] == 3)
		{
			$myColony->giverump(15,$ids[$i][id]);
			$myColony->giverump(135,$ids[$i][id]);
			$myColony->giverump(16,$ids[$i][id]);
		}
		elseif ($ids[$i][rasse] == 4)
		{
			$myColony->giverump(23,$ids[$i][id]);
			$myColony->giverump(21,$ids[$i][id]);
			$myColony->giverump(90,$ids[$i][id]);
		}
		elseif ($ids[$i][rasse] == 5)
		{
			$myColony->giverump(196,$ids[$i][id]);
			$myColony->giverump(182,$ids[$i][id]);
			$myColony->giverump(131,$ids[$i][id]);
		}
	}

	$ids = $myColony->getmodids();
	for ($i=0;$i<count($ids);$i++) $myColony->givecost($ids[$i][id]);














} elseif ($section == "weap") {

	$pt = $myColony->gettorpsships(7);
	$lt = $myColony->gettorpsships(16);
	$qt = $myColony->gettorpsships(17);

	$maxt = $myColony->getmaxtorpsships();
	$j = 0;
	$k = 0;
	$l = 0;
	$n = 0;
	for ($i=0;$i<count($maxt);$i++) {
		if ($maxt[$i][1] > 0) {
			
			$stat[$n][userid] = $maxt[$i][0];
			$stat[$n][maxtorp] = $maxt[$i][1];

			$stat[$n][phot] = 0;
			$abbruch = true;
			while ($abbruch) {
				if ($stat[$n][userid] == $pt[$j][0]) {
					$stat[$n][phot] = $pt[$j][1];

				} 
				$j++;
				if (($j >= count($pt)) || ($pt[$j][0] > $stat[$n][userid])) {
					$abbruch = false;
					$j = $j - 1;
				}
			}

			$stat[$n][plas] = 0;
			$abbruch = true;
			while ($abbruch) {
				if ($stat[$n][userid] == $lt[$k][0]) {
					$stat[$n][plas] = $lt[$k][1];

				} 
				$k++;
				if (($k >= count($lt)) || ($lt[$k][0] > $stat[$n][userid])) {
					$abbruch = false;
					$k = $k - 1;
				}
			}

			$stat[$n][quan] = 0;
			$abbruch = true;
			while ($abbruch) {
				if ($stat[$n][userid] == $qt[$l][0]) {
					$stat[$n][quan] = $qt[$l][1];

				} 
				$l++;
				if (($l >= count($qt)) || ($qt[$l][0] > $stat[$n][userid])) {
					$abbruch = false;
					$l = $l - 1;
				}
			}

			$stat[$n][ratio] = 100 * (($stat[$n][phot] + $stat[$n][plas] + $stat[$n][quan]) / $stat[$n][maxtorp]);
			$stat[$n][ratio] = round($stat[$n][ratio],2);

			$stat[$n][pointratio] = 100 * (($stat[$n][phot] + (2* $stat[$n][plas]) + (3* $stat[$n][quan])) / (3* $stat[$n][maxtorp]));
			$stat[$n][pointratio] = round($stat[$n][pointratio],2);
			$n++;
		}
	
	}

	echo "<table width=90% cellpadding=0 cellspacing=0><tr>
		<td width=100% class=tdmaintop align=center>Die 10 bestbewaffnetsten Flotten</td>
	</tr>
	</table><br>
	<table width=90% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><b>Spieler</b></td>
		<td class=tdmain align=center><b>Kapazität</b></td>
		<td class=tdmain align=center><b>Photonen</b></td>
		<td class=tdmain align=center><b>Plasma</b></td>
		<td class=tdmain align=center><b>Quanten</b></td>
		<td class=tdmain align=center><b>Ratio</b></td>
		<td class=tdmain align=center><b>Punkte</b></td>
	</tr>";
	for ($i=0;$i<count($stat);$i++) {
		$userdata = $myUser->getuserbyid($erg[$i][userid]);
		echo "<tr>
			<td class=tdmainobg>".$stat[$i][userid]."</td>
			<td class=tdmainobg align=center>".$stat[$i][maxtorp]."</td>
			<td class=tdmainobg align=center>".$stat[$i][phot]."</td>
			<td class=tdmainobg align=center>".$stat[$i][plas]."</td>
			<td class=tdmainobg align=center>".$stat[$i][quan]."</td>
			<td class=tdmainobg align=center>".$stat[$i][ratio]." %</td>
			<td class=tdmainobg align=center>".$stat[$i][pointratio]." %</td>
		</tr>";
	}
	echo "</table><br><table width=90% cellpadding=0 cellspacing=0>
	<tr>
		<td class=tdmain align=center><a href=main.php?page=hally&user=".$user."&pass=".$pass.">Handelsallianz</a> - <a href=main.php?page=hally&section=stats&user=".$user."&pass=".$pass.">Statistiken</a></td>
	</tr>
	</table>";







}
?>
