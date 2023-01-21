<?php
if (!$section || ($section == "main")) {
	if (($action == "addplanet") && $type && $x && $y) $return = $myColony->addplanet($x,$y,$type);
	echo "<table><tr><td class=tdmain>".$return[msg]."</td></tr></table>";
	?>
	<table width=100%>
	<tr>
		<td class=tdmaintop colspan=5 align=center width=100%>Kolonien</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?php	echo "<tr><td class=tdmain align=center><form action=index.php method=post><input type=hidden name=page value=colony><input type=hidden name=section value=showcolony>Kolonie suchen: <input type=text name=id size=5 class=text> <input type=submit value=Anzeigen></form></td></tr>";
	?>
	<tr>
		<td class=tdmain colspan=5 align=center>Planet erstellen<br>
		<form action="index.php" method=post>
		<input type=hidden name=page value=colony>
		<input type=hidden name=action value=addplanet>
		Coords: x/y <input type=text size=3 name=x class=text value=<?php echo $x ?>> / <input type=text size=3 name=y class=text value=<?php echo $y ?>>
		<select name=type><option value=1>Klasse M</option>
						  <option value=2>Klasse L</option>
						  <option value=3>Klasse N</option>
						  <option value=4>Klasse G</option>
						  <option value=5>Klasse K</option>
						  <option value=6>Klasse D</option>
						  <option value=7>Klasse H</option>
						  <option value=8>Klasse X</option></select>
		<input type=submit value=erstellen class=button>
		</form></td>
	</tr>
	<tr>
		<td class=tdmain colspan=5 align=center>Bevölkerungsanzeige MkII<br>
		<form action="index.php" method=post>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=bevlist>
		Arbeiter: <input type=text size=3 name=a class=text>
		Freie:<input type=text size=3 name=f class=text>
		Obdachlose:<input type=text size=3 name=o class=text>
		Unused:<input type=text size=3 name=u class=text>
		<input type=submit value=erstellen class=button>
		</form></td>
	</tr>
	<tr>
		<td class=tdmain colspan=5 align=center>Bonusrestester<br>
		<form action="index.php" method=post>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=sammeltest>
		kartenfeld: <input type=text size=3 name=type class=text>
		sammeln:<input type=text size=3 name=collect class=text>
		energie:<input type=text size=3 name=count class=text>
		<input type=submit value=erstellen class=button>
		</form></td>
	</tr>
	</table>

	<?php
} elseif ($section == "showcolony") {
	$data = $myColony->getColonybyId($id);
	if ($data == 0) echo "Kolonie nicht vorhanden";
	else {
		echo "<table align=Center width=100%>
		<tr>
			<td class=tdmain align=center width=100%>Kolonieinformationen von ".stripslashes($data[name])."</td>
		</tr>
		</table><br>";
		$bevo = 0;
		$map .= "<table cellpadding=1 cellspacing=1>";
		$farm = 0;
		$fields = $myColony->getcolfields($id);
		for ($i=0;$i<count($fields);$i++) {
			if ($i == 0) $map .= "<tr>";
			$build = $myColony->getbuildbyid($fields[$i][buildings_id]);
			if ($fields[$i][name]) $extalt = $fields[$i][name]." - ";
			else unset($extalt);
			if ($fields[$i][buildtime] > 0) $sid = 106;
			else $sid = $build[id];
			if ($fields[$i][aktiv] == 0) $alt = "(offline)";
			elseif ($fields[$i][aktiv] == 1) $alt = "(online)";
			if ($fields[$i][buildtime] > 0) $alt .= " (im Bau)";
			if ($build == 0) $img = "<img src=".$grafik."/fields/".$fields[$i][type].".gif border=0>";
			else $img = "<img src=".$grafik."/buildings/".$sid."_".$fields[$i][type].".gif border=0 alt='".$extalt." ".$build[name]." ".$alt."'>";
			if ($fields[$i][aktiv] == 1) {
				if (($fields[$i][buildings_id] == 2) || ($fields[$i][buildings_id] == 8)) $farm++;
				$eps['min'] = $eps['min'] + $build[eps_min];
				$eps['pro'] = $eps['pro'] + $build[eps_pro];
				$stor = $myColony->getgoodsbybuilding($build[id]);
				for ($j=0;$j<count($stor);$j++) {
					$goods_id = $stor[$j][goods_id];
					if ($stor[$j][mode] == 1) { 
						$goods[$goods_id]['count'] = $goods[$goods_id]['count'] + $stor[$j]['count'];
						$sumgoods = $sumgoods + $stor[$j]['count'];
					}
					if ($stor[$j][mode] == 2) { 
						$goods[$goods_id]['count'] = $goods[$goods_id]['count'] - $stor[$j]['count'];
						$sumgoods = $sumgoods - $stor[$j]['count'];
					}
				}
				if ($bevo+$build[bev_use] > $data[max_bev]) $border = " style='border: 1px solid #FF0000'";
				else $border = " style='border: 1px solid #7f7f7f'";
				$bevo = $bevo + $build[bev_use];
				$addpoint = $addpoint + $build[points];
			}
			else $border = " style='border: 1px solid #262323'";
			if (($build[id] == 107) || ($build[id] == 114) || ($build[id] == 121) || ($build[id] == 128)) $addpoint = $addpoint + 0.2;
			if (($build[id] > 107) && ($build[id] < 114)) $addpoint = $addpoint + 0.35;
			if (($build[id] > 114) && ($build[id] < 121)) $addpoint = $addpoint + 0.35;
			if (($build[id] > 121) && ($build[id] < 128)) $addpoint = $addpoint + 0.35;
			if (($build[id] > 128) && ($build[id] < 135)) $addpoint = $addpoint + 0.35;
			if ($build[id] == 10) $symp = $symp+1;
			if ($build[id] == 4) $addpoint = $addpoint + $build[points];
			$map .= "<td class=collist width=30 align=center".$border."><a href=main.php?page=colony&section=field&field=".$fields[$i][field_id]."&id=".$id.">".$img."</a></td>";
			if (($data[colonies_classes_id] == 6) || ($data[colonies_classes_id] == 9)) {
				if (($i == 6) || ($i == 13) || ($i == 20) || ($i == 27)) $map .= "</tr><tr>";
				if ($i == 34) $map .= "</tr>";
			}
			if (($data[colonies_classes_id] != 6) && ($data[colonies_classes_id] != 9)) {
				if (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44)) $map .= "</tr><tr>";
				if ($i == 53) $map .= "</tr>";
			}
			unset($extalt);
		}
		$map .= "</table>";
		$ormap = "<table cellpadding=1 cellspacing=1>";
		$orbit = $myColony->getcolorbit($id);
		if ($orbit != 0) {
			for ($i=0;$i<count($orbit);$i++) {
				$build = $myColony->getbuildbyid($orbit[$i][buildings_id]);
				if ($orbit[$i][name]) $extalt = $orbit[$i][name]." - ";
				else unset($extalt);
				if ($orbit[$i][aktiv] == 0) $alt = "(offline)";
				elseif ($orbit[$i][aktiv] == 1) $alt = "(online)";
				if ($orbit[$i][buildtime] > 0) $alt .= " (im Bau)";
				if ($orbit[$i][buildtime] > 0) $sid = 106;
				else $sid = $build[id];
				if ($build == 0) $img = "<img src=".$grafik."/fields/".$orbit[$i][type].".gif border=0>";
				else $img = "<img src=".$grafik."/buildings/".$sid."_".$orbit[$i][type].".gif border=0 alt='".$extalt."".$build[name]." ".$alt."'>";
				if ($orbit[$i][aktiv] == 1) {
					if ($orbit[$i][buildings_id] == 47) $wks=1;
					$eps['min'] = $eps['min'] + $build[eps_min];
					$eps['pro'] = $eps['pro'] + $build[eps_pro];
					$stor = $myColony->getgoodsbybuilding($build[id]);
					for ($j=0;$j<count($stor);$j++) {
						$goods_id = $stor[$j][goods_id];
						if ($stor[$j][mode] == 1) { 
							$goods[$goods_id]['count'] = $goods[$goods_id]['count'] + $stor[$j]['count'];
							$sumgoods = $sumgoods + $stor[$j]['count'];
						}
						if ($stor[$j][mode] == 2) { 
							$goods[$goods_id]['count'] = $goods[$goods_id]['count'] - $stor[$j]['count'];
							$sumgoods = $sumgoods - $stor[$j]['count'];
						}
					}
					if ($bevo+$build[bev_use] > $data[max_bev]) $border = " style='border: 1px solid #FF0000'";
					else $border = " style='border: 1px solid #7f7f7f'";
					$bevo = $bevo + $build[bev_use];
					$addpoint = $addpoint + $build[points];
				}
				else $border = " style='border: 1px solid #262323'";
				if ($build[id] == 10) $symp = $symp+1;
				if ((($build[id] > 25) && ($build[id] < 31)) || ($build[id] == 135)) $addpoint = $addpoint + $build[points];
				$ormap .= "<td class=collist width=30 align=center".$border."><a href=main.php?page=colony&section=orbitfield&field=".$orbit[$i][field_id]."&id=".$id.">".$img."</a></td>";
				if (($data[colonies_classes_id] == 6) || ($data[colonies_classes_id] == 9)) if (($i == 6) || ($i == 13)) $ormap .= "</tr>";
				if (($data[colonies_classes_id] != 6) && ($data[colonies_classes_id] != 9)) if (($i == 8) || ($i == 17)) $ormap .= "</tr>";
			}
			unset($extalt);
		}
		$ormap .= "</table>";
		$umap = "<table cellpadding=1 cellspacing=1>";
		$underground = $myColony->getcolunderground($id);
		if ($underground != 0) {
			for ($i=0;$i<count($underground);$i++) {
				$build = $myColony->getbuildbyid($underground[$i][buildings_id]);
				if ($underground[$i][name]) $extalt = $underground[$i][name]." - ";
				else unset($extalt);
				if ($underground[$i][aktiv] == 0) $alt = "(offline)";
				elseif ($underground[$i][aktiv] == 1) $alt = "(online)";
				if ($underground[$i][buildtime] > 0) $alt .= " (im Bau)";
				if ($underground[$i][buildtime] > 0) $sid = 106;
				else $sid = $build[id];
				if ($build == 0) $img = "<img src=".$grafik."/fields/".$underground[$i][type].".gif border=0>";
				else $img = "<img src=".$grafik."/buildings/".$sid."_".$underground[$i][type].".gif border=0 alt='".$extalt."".$build[name]." ".$alt."'>";
				if ($underground[$i][aktiv] == 1) {
					$eps['min'] = $eps['min'] + $build[eps_min];
					$eps['pro'] = $eps['pro'] + $build[eps_pro];
					$stor = $myColony->getgoodsbybuilding($build[id]);
					for ($j=0;$j<count($stor);$j++) {
						$goods_id = $stor[$j][goods_id];
						if ($stor[$j][mode] == 1) { 
							$goods[$goods_id]['count'] = $goods[$goods_id]['count'] + $stor[$j]['count'];
							$sumgoods = $sumgoods + $stor[$j]['count'];
						}
						if ($stor[$j][mode] == 2) { 
							$goods[$goods_id]['count'] = $goods[$goods_id]['count'] - $stor[$j]['count'];
							$sumgoods = $sumgoods - $stor[$j]['count'];
						}
					}
					if ($bevo+$build[bev_use] > $data[max_bev]) $border = " style='border: 1px solid #FF0000'";
					else $border = " style='border: 1px solid #7f7f7f'";
					$bevo = $bevo + $build[bev_use];
					$addpoint = $addpoint + $build[points];
				}
				else $border = " style='border: 1px solid #262323'";
				if ($build[id] == 10) $symp = $symp+1;
				if ($build[id] == 4) $addpoint = $addpoint + $build[points];
				$umap .= "<td class=collist width=30 align=center".$border."><a href=main.php?page=colony&section=groundfield&field=".$underground[$i][field_id]."&id=".$id.">".$img."</a></td>";
				if (($i == 8) || ($i == 17) || ($i == 26)) $umap .= "</tr>";
			}
			unset($extalt);
		}
		$umap .= "</table>";
		if ($eps['pro'] - $eps['min'] > 0) {
			$eps[add] = $eps['pro'] - $eps['min'];
			$energie = $data[energie];
			$eps_show = $eps['pro'] - $eps['min'];
			$mode = "+";
		} elseif ($eps['pro'] - $eps['min'] < 0) {
			$eps[del] = $eps['min'] - $eps['pro'];
			$energie = $data[energie] - ($eps['min'] - $eps['pro']);
			$eps_show = $eps['pro']-$eps['min'];
		} elseif ($eps['pro'] - $eps['min'] == 0) {
			$energie = $data[energie];
			$eps_show = 0;
		}
		if ($data[max_energie] > 70) $small = "_sm";
		if ($eps[del] > $data[energie]) $eps[calc_del] = $data[energie];
		else $eps[calc_del] = $eps[del];
		if ($eps[add]+$energie > $data[max_energie]) $eps[add] = $data[max_energie]-$energie;
		for($i=0;$i<$energie;$i++) $show_energie[] = "<img src=".$grafik."/en".$small.".gif border=0>";
		if ($eps[del] > 0) for($i=0;$i<$eps[calc_del];$i++) $show_energie[] = "<img src=".$grafik."/en_min".$small.".gif border=0>";
		if ($eps[add] > 0) for($i=0;$i<$eps[add];$i++) $show_energie[] = "<img src=".$grafik."/en_pro".$small.".gif border=0>";
		if ($energie < $data[max_energie]) for($i=0;$i<($data[max_energie]-$energie-$eps[del]-$eps[add]);$i++) $show_energie[] = "<img src=".$grafik."/en_em".$small.".gif border=0>";
		$j=1;
		if ($data[colonies_classes_id] < 4) $bev_add_show = ceil(($data[max_bev]-$data[bev_used]-$data[bev_free])/2)."/".$data[bev_free];
		else $bev_add_show = $data[bev_free];
		echo "<table bgcolor=#262323>
		<tr>
			<td class=tdmainobg>Bevölkerung: ".($data[bev_used] + $data[bev_free])."/".$data[max_bev]." (".$bev_add_show.")</td>
		</tr>
		<tr>
			<td class=tdmainobg>";
			if ($data[max_bev] > 70) $bvadd = "_sm";
			if ($data[bev_used] + $data[bev_free] > $data[max_bev]) $freebev = $data[max_bev] - $data[bev_used];
			else $freebev = $data[bev_free];
			$free = ceil(($data[max_bev]-$data[bev_used])/2);
			for($i=1;$i<=$data[bev_used];$i++) $show_bev[] = "<img src=".$grafik."/ppl_used".$bvadd.".gif border=0>";
			for($i=1;$i<=$freebev;$i++) $show_bev[] =  "<img src=".$grafik."/ppl_free".$bvadd.".gif border=0>";
			for($i=1;$i<=($data[max_bev]-$data[bev_used]-$data[bev_free]);$i++) $show_bev[] =  "<img src=".$grafik."/ppl_unused".$bvadd.".gif border=0>";
			if ($data[max_bev] < $data[bev_used] + $data[bev_free]) for($i=1;$i<=($data[bev_used] + $data[bev_free])-$data[max_bev];$i++) $show_bev[] =  "<img src=".$grafik."/ppl_over".$bvadd.".gif border=0>";
			$bev[nr] = ceil(($data[bev_used]+$data[bev_free])/5);
			$goods[1]['count'] = $goods[1]['count']-$bev[nr];
			$sumgoods = $sumgoods-$bev[nr];
			for ($k=0;$k<count($show_bev);$k++) {
				echo $show_bev[$k];
				if (($k ==99) || ($k == 199) || ($k == 299) || ($k == 399) || ($k == 499) || ($k == 599) || ($k == 699) || ($k == 799) || ($k == 899) || ($k == 999)) echo "<br>";
			}
			echo "</a></td>
		</tr>
		</table><br>";
			if ($data[max_schilde] > 0) {
				echo "<table bgcolor=#262323><tr>
				<td class=tdmainobg>Schilde: ".$data[schilde]."/".$data[max_schilde]." Frequenz: ".$data[schild_freq1].",".$data[schild_freq2]."<br>";
				$shcount = round($data[schilde]/5);
				$shcountf = round(($data[max_schilde]-$data[schilde])/5);
				if ($data[schilde_aktiv] == 1) for ($i=1;$i<=$shcount;$i++) echo "<img src=".$grafik."/sh_sm.gif border=0>";
				else for ($i=1;$i<=$shcount;$i++) echo "<img src=".$grafik."/sh_de_sm.gif border=0>";
				for ($i=1;$i<=$shcountf;$i++) echo "<img src=".$grafik."/sh_fr_sm.gif border=0>";
				echo "</td>
			</tr>
			</table><br>";
			}
		echo "<table bgcolor=#262323><tr>
			<td class=tdmainobg>EPS: ".$data[energie]."(".$mode."".$eps_show.")/".$data[max_energie]."<br>";
			for ($i=1;$i<=count($show_energie);$i++) {
				echo $show_energie[$i-1];
				if (($i/$j) == 80) {
					echo "<br>";
					$j++;
				}
			}
		echo "</td>
		</tr>
		</table>";
		echo $ormap;
		echo "<br>";
		echo $map;
		echo "<br>";
		echo $umap;
		if ($wks == 1) $goods[1]['count'] = $goods[1]['count']+$farm;
		$goodlist = $myColony->goodlist();
		for ($i=0;$i<count($goodlist);$i++) {
			$goods_id = $goodlist[$i][id];
			$storage = $myColony->getstoragebygoodid($goodlist[$i][id],$id);
			if ($storage != 0 && ($goods[$goods_id]['count'] != 0)) {
				if ($goods[$goods_id]['count'] < 0) {
					for ($j=0;$j<ceil(($storage['count']-($goods[$goods_id]['count']*-1))/20);$j++) $first .= "<img src=../../gfx/lager_1.gif>";
					$ver = "<font color=red>".$goods[$goods_id]['count']."</font>";
					for ($j=0;$j<ceil(($goods[$goods_id]['count']*-1)/20);$j++) $balken .= "<img src=../../gfx/lager_1m.gif>";
				} elseif ($goods[$goods_id]['count'] > 0) {
					for ($j=0;$j<ceil($storage['count']/20);$j++) $first .= "<img src=../../gfx/lager_1.gif>";
					$ver = "<font color=green>+".$goods[$goods_id]['count']."</font>";
					for ($j=0;$j<ceil($goods[$goods_id]['count']/20);$j++) $balken .= "<img src=../../gfx/lager_1p.gif>";
				}
				$lager .= "<tr>
				<td class=tdmainobg><img src=../../gfx/goods/".$goodlist[$i][id].".gif border=0 alt='".$goodlist[$i][name]."'> ".$storage['count']."</td>
				<td class=tdmainobg>".$first."".$balken."</td>
				<td class=tdmainobg>".$ver."</td>
			</tr>";
			} elseif ($storage == 0 && ($goods[$goods_id]['count'] != 0)) {
				if ($goods[$goods_id]['count'] < 0) {
					$ver = "<font color=red>".$goods[$goods_id]['count']."</font>";
					for ($j=0;$j<ceil(($goods[$goods_id]['count']*-1)/20);$j++) $balken .= "<img src=../../gfx/lager_1m.gif>";
				} elseif ($goods[$goods_id]['count'] > 0) {
					$ver = "<font color=green>+".$goods[$goods_id]['count']."</font>";
					for ($j=0;$j<ceil($goods[$goods_id]['count']/20);$j++) $balken .= "<img src=../../gfx/lager_1p.gif>";
				}
				$lager .= "<tr>
					<td class=tdmainobg><img src=../../gfx/goods/".$goodlist[$i][id].".gif border=0 alt='".$goodlist[$i][name]."'> <font color=red>0</font></td>
					<td class=tdmainobg>".$balken."</td>
					<td class=tdmainobg>".$ver."</td>
				</tr>";
			} elseif ($storage != 0 && ($goods[$goods_id]['count'] == 0)) {
				$count = ceil($storage['count']/20);
				for ($j=0;$j<$count;$j++) $balken .= "<img src=../../gfx/lager_1.gif>";
				$lager .= "<tr>
					<td class=tdmainobg><img src=../../gfx/goods/".$goodlist[$i][id].".gif border=0 alt='".$goodlist[$i][name]."'> ".$storage['count']."</td>
					<td class=tdmainobg>".$balken."</td>
					<td class=tdmainobg>0</td>
				</tr>";
			}
			$insgstor = $insgstor + $storage['count'];
			unset($width);
			unset($balken);
			unset($first);
		}
		for ($j=0;$j<ceil($insgstor/20);$j++) $stored .= "<img src=../../gfx/lager_1.gif>";
		echo "<br><table bgcolor=#262323 width=600>
		<tr>
			<td class=tdmain width=70 valign=middle><img src=../../gfx/buttons/lager.gif alt='Lager'> ".$insgstor."</td>
			<td class=tdmain width=500>".$stored." ".$data[max_storage]."</td>
			<td class=tdmainobg width=30>+/-</td>
		</tr>
		".$lager."
		</table>";
	}
} elseif ($section == "bevlist") {


	echo "<table bgcolor=#262323>
	<tr>
		<td class=tdmainobg>Bevölkerung: ".($myColony->cbev_used + $myColony->cbev_free)."/".$myColony->cmax_bev." (".$bev_add_show.")</td>
	</tr>
	<tr>
		<td class=tdmainobg>";
		$j = 0;
		$a5 = $a / 10;
		$a1 = $a % 10;
		$f5 = $f / 10;
		$f1 = $f % 10;
		$o5 = $o / 10;
		$o1 = $o % 10;
		$u5 = $u / 10;
		$u1 = $u % 10;
		for($i=0;$i<$a5;$i++)
		{
			echo "<img src=http://home.arcor.de/omega-sektion/bev_used_5_1.gif border=0>";
			$j = $j + 21;
			if ($j >= 600) {
				echo "<br>";
				$j = 0;
			}
		}
		for($i=0;$i<$a1;$i++)
		{
			echo "<img src=http://home.arcor.de/omega-sektion/bev_used_1_1.gif border=0>";
			$j = $j + 9;
			if ($j >= 600) {
				echo "<br>";
				$j = 0;
			}
		}
		for($i=0;$i<$f5;$i++)
		{
			echo "<img src=http://home.arcor.de/omega-sektion/bev_unused_5_1.gif border=0>";
			$j = $j + 21;
			if ($j >= 600) {
				echo "<br>";
				$j = 0;
			}
		}
		for($i=0;$i<$f1;$i++)
		{
			echo "<img src=http://home.arcor.de/omega-sektion/bev_unused_1_1.gif border=0>";
			$j = $j + 9;
			if ($j >= 600) {
				echo "<br>";
				$j = 0;
			}
		}
		for($i=0;$i<$o5;$i++)
		{
			echo "<img src=http://home.arcor.de/omega-sektion/ppl_over5_1.gif border=0>";
			$j = $j + 21;
			if ($j >= 600) {
				echo "<br>";
				$j = 0;
			}
		}
		for($i=0;$i<$o1;$i++)
		{
			echo "<img src=http://home.arcor.de/omega-sektion/ppl_over1_1.gif border=0>";
			$j = $j + 9;
			if ($j >= 600) {
				echo "<br>";
				$j = 0;
			}
		}
		for($i=0;$i<$u5;$i++)
		{
			echo "<img src=http://home.arcor.de/omega-sektion/ppl_unused5_1.gif border=0>";
			$j = $j + 21;
			if ($j >= 600) {
				echo "<br>";
				$j = 0;
			}
		}
		for($i=0;$i<$u1;$i++)
		{
			echo "<img src=http://home.arcor.de/omega-sektion/ppl_unused1_1.gif border=0>";
			$j = $j + 9;
			if ($j >= 600) {
				echo "<br>";
				$j = 0;
			}
		}
		echo "</td>
	</tr>
	</table><br>";


} elseif ($section == "pufferzone") {

		for ($y=1;$y<88;$y++)
		{
			$data = $myColony->getpufferx($y);
			echo "<br>".$data[coords_x]." / ".$y;
			for ($j=1;$j<11;$j++)
			{
				echo "  ".($data[coords_x] - $j);
			}
		}



} elseif ($section == "sammeltest") {

	echo "Typ: ".$type." Sammeln: ".$collect." Count: ".$count;
	
	for ($i=1;$i<100;$i++)
	{
		$s = 0;
		$os = 0;
		$ta = 0;
		$ma = 0;
		$si = 0;
		$good2 = "";
		if ($type == 1) $good = "Iridium-Erz";
		if ($type == 2) $good = "Deuterium";

		$erz = $count*$collect;

		for ($j=1;$j<floor(($erz/50));$j++)
		{
			$r = rand(1,100);
			if ($r == 1)
			{
				if ($type == 1)
				{
					$s = rand(1,3);
					if ($s == 1) $os += 1;
					if ($s == 2) $ta += 1;
					if ($s == 3) $ma += 1;
				}
				if ($type == 2)
				{
					$si += 1;
				}


			}
		}
		if ($os > 0) $good2 .= " +".$os." Osmium";
		if ($ta > 0) $good2 .= " +".$ta." Talgonit";
		if ($ma > 0) $good2 .= " +".$ma." Magnesit";
		if ($si > 0) $good2 .= " +".$si." Sirillium";
		echo "<br><br>".$erz." ".$good." ".$good2;
	}

}
?>