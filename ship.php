<?php
if ($command == "bussard" && $sent == 0) $section = "fleetbussard";
if ($command == "erz" && $sent == 0) $section = "fleeterz";
if ($command == "ebatt" && $sent == 0) $section = "fleetebatt";
if ($command == "shload" && $sent == 0) $section = "fleetshload";
if ($section)
{
	$myShip->gcs();
	if ($myShip->cshow == 0) exit;
	if ($myShip->cdeact == 1) exit;
}
if (!$id && $section) exit;
if (!$section)
{
	if ($action == "newfleet" && $id) $result = $myFleet->newfleet($id,$user);
	if ($action == "delfleet" && $id) $result = $myFleet->delfleet($id,$user);
	if ($action == "join" && $shipid > 0 && $fleet > 0) $result = $myFleet->joinfleet($shipid,$fleet);
	if ($action == "chname" && $fname != "" && $fleet > 0) $result = $myFleet->changename($fname,$fleet);
	if ($action == "leavefleet" && $id) $result = $myFleet->leavefleet($id,$user);
	echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"tooltip.js\"></script>
	<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ <strong>Schiffe</strong></td>
	</tr>
	</table><br>";
	$lf = "s";
	if (is_array($result)) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td width=100% class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	$result = $myShip->getshiplist($user,$sort,$way);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>";
	if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg align=center>Keine Schiffe vorhanden</td></td></tr>";
	else
	{
		while($tmp=mysql_fetch_assoc($result))
		{
			$i++;
			$data = $myShip->getdatabyid($tmp[id]);
			$data[damaged] == 1 ? $mpf = "d/" : $mpf = "";
			if ($data[c][secretimage] != "0")
			{
				$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[c][secretimage].".gif border=0  class=\"tooltip\" title=\"".strip_tags($data[c][name])." Klasse<br>Warpkern: ".$data[warpcore]."\">";
			}
			else
			{
				$shippic = "<img src=".$grafik."/ships/".$mpf.$data[c][id].".gif border=0  class=\"tooltip\" title=\"".strip_tags($data[c][name])." Klasse<br>Warpkern: ".$data[warpcore]."\">";
			}
			if ($data[deact] == 1)
			{
				echo "<tr><td class=tdmainobg>".$shippic."</td><td class=tdmainobg colspan=10>Kommunikationsverbindung abgerissen</td></tr>";
				continue;
			}
			if ($lf != $data[fleets_id] && $data[fleets_id] != 0)
			{
				$jlist = $myFleet->generatejlist($data[coords_x],$data[coords_y],$data[wese]);
				$fleet = $myFleet->getfleetbyid($data[fleets_id]);
				if ($lf != 0) echo "<tr><td class=tdmainobg colspan=11 height=20>&nbsp;</td></tr>";
				echo "<form action=main.php method=post>
				<input type=hidden name=page value=ship>
				<input type=hidden name=fleet value=".$fleet[id].">
				<input type=hidden name=action value=chname>
				<tr>
					<td colspan=11 class=tdmain>
					<table>
					<tr>
						<td class=tdmain width=300><img src=".$grafik."/buttons/fleet.gif> <a href=?page=ship&section=showship&id=".$fleet[ships_id].">".stripslashes($fleet[name])."</a> (".round($myDB->query("SELECT SUM(points) FROM stu_ships WHERE fleets_id=".$fleet[id],1),2)."/150)</td>
						<td class=tdmain><input type=text size=20 name=fname class=text value=\"".htmlspecialchars(stripslashes($fleet[name]))."\"> <input type=submit class=button name=submit value=umbenennen></td>
						</form>
						<form action=main.php method=post>
						<input type=hidden name=page value=ship>
						<input type=hidden name=action value=join>
						<input type=hidden name=fleet value=".$fleet[id].">
						<td class=tdmain><select name=shipid>".$jlist."</select> <input type=submit value=Join name=submit class=button></td>
					</tr>
					</table>
					</td>
				</tr>
				</form>
				<tr>
					<td class=tdmainobg align=center><strong>Typ</strong></td>
					<td class=tdmainobg><strong>Name</strong></td>
					<td class=tdmainobg align=center><strong>x/y</strong></td>
					<td class=tdmainobg align=center><strong>Zustand</strong></td>
					<td class=tdmainobg align=center><strong>A</strong></td>
					<td class=tdmainobg align=center><strong>E (B</strong>)</td>
					<td class=tdmainobg align=center><strong>Schilde</strong></td>
					<td class=tdmainobg align=Center><strong>C</strong> (<a href=main.php?page=help&help=c target=leftbottom>?</a>)</td>
					<td class=tdmainobg align=center><strong>R</strong> (<a href=main.php?page=help&help=r target=leftbottom>?</a>)</td>
					<td class=tdmainobg align=center><strong>L/K</strong></td>
					<td class=tdmainobg></td>
				</tr>";
			}
			if (($lf > 0 || $lf == "s") && $data[fleets_id] == 0)
			{
				if ($lf != 0) echo "<tr><td class=tdmainobg colspan=11 height=20>&nbsp;</td></tr>";
				echo "<tr><td colspan=10 class=tdmain height=25>Einzelschiffe</td></tr>
				<tr>
					<td class=tdmainobg align=center><strong>Typ</strong></td>
					<td class=tdmainobg><strong>Name</strong></td>
					<td class=tdmainobg align=center><strong>x/y</strong></td>
					<td class=tdmainobg align=center><strong>Zustand</strong></td>
					<td class=tdmainobg align=center><strong>A</strong></td>
					<td class=tdmainobg align=center><strong>E (B)</strong></td>
					<td class=tdmainobg align=center><strong>Schilde</strong></td>
					<td class=tdmainobg align=Center><strong>C</strong> (<a href=main.php?page=help&help=c target=leftbottom>?</a>)</td>
					<td class=tdmainobg align=center><strong>R</strong> (<a href=main.php?page=help&help=r target=leftbottom>?</a>)</td>
					<td class=tdmainobg align=center><strong>L/K</strong></td>
					<td class=tdmainobg></td>
				</tr>";
			}
			$data[schilde_aktiv] == 1 ? $schilde = "<font color=cyan>".$data[schilde]."/".$data[maxshields]."</font>" : $schilde = $data[schilde]."/".$data[maxshields];
			if ($data[crew] > 0 || $data[c][probe] == 1 || $data[c][id] == 88)
			{
				if ($data[reaktormodlvl] > 0)
				{
					if ($data[warpcore] < $data[verbrauch])
					{
						$dv = $myShip->getcountbygoodid(2,$data[id]);
						if ($dv > 0) { $rd = 0; $rda = " (<font color=Yellow>".@floor($dv/($data[verbrauch]+$data[erzeugung]))."</font>)"; }
						else $rd = 0;
					}
					else $rd = @floor($data[warpcore]/($data[verbrauch]+$data[erzeugung]));
				}
				else $rd = @floor($myShip->getcountbygoodid(2,$data[id])/($data[verbrauch]+$data[erzeugung]));
				$stor = $myShip->getcountbygoodid(1,$data[id]);
				if ($rd <= 5 && $rd > 2) $rd = "<font color=Yellow>".$rd."</font>";
				if ($rd <= 2) $rd = "<font color=red>".$rd."</font>";
				if ($data[verbrauch]+$data[erzeugung] == 0) $rd = "-";
				if ($data[c][id] != 88)
				{
					if ($stor == 0 && $data[replikator] == 0) $r = 0;
					else
					{
						if ($data[replikator] == 0)
						{
							$crew = ceil($data[crew]/5);
							$stor < $crew ? $r = 0 : $r = floor($stor/$crew);
						}
					}
				}
				if (($data[replikator] == 1 || ($data[c][replikator] == 1 && $stor == 0 && $data[replikator] == 0)) && ($data[erzeugung] > 0 || $data[energie] > $crew)) $r = "*";
				if (($r == 0 || $r == 1) && is_int($r)) $r = "<font color=#ff0000>".$r."</font>";
				if ($r > 1 && $r <= 5 && $r != "*") $r = "<font color=yellow>".$r."</font>";
				if ($data[crew] == 0) $r = "-";
			}
			else
			{
				$r = "-";
				$rd = "-";
			}
			if ($fleet[ships_id] == $data[id]) $ftd = "<a href=?page=ship&action=delfleet&id=".$data[id]." onMouseOver=\"cp('df".$i."','buttons/fl_flag3')\" onMouseOut=\"cp('df".$i."','buttons/fl_flag2')\"><img src=".$grafik."/buttons/fl_flag2.gif name=df".$i." border=0 title='Flotte auflösen'></a>";
			elseif ($data[fleets_id] > 0 && $fleet[ships_id] != $data[id]) $ftd = "<a href=?page=ship&action=leavefleet&id=".$data[id]." onMouseOver=\"cp('fl".$i."','buttons/fl_raus2')\" onMouseOut=\"cp('fl".$i."','buttons/fl_raus1')\"><img src=".$grafik."/buttons/fl_raus1.gif name=fl".$i." border=0 title='Flotte verlassen'></a>";
			else $ftd = "<a href=?page=ship&action=newfleet&id=".$data[id]." onMouseOver=\"cp('nf".$i."','buttons/fl_flag2')\" onMouseOut=\"cp('nf".$i."','buttons/fl_flag1')\"><img src=".$grafik."/buttons/fl_flag1.gif name=nf".$i." border=0 title='Flotte gründen'></a>";
			$data[lss] == 1 ? $l = "*" : $l = "-";
			$data[kss] == 1 ? $k = "*" : $k = "-";
			$data[cloak] == 1 ? $ids = "<font color=#808080>".$data[id]."</font>" : $ids = $data[id];
			if ($data[wese] == 2) $wadd = " (2)";
			if ($data[wese] == 3) $wadd = " (3)";
			echo "<tr>
				<td class=tdmainobg><a href=?page=ship&section=showship&id=".$data[id].">".$shippic."</a></td>
				<td class=tdmainobg><a href=?page=ship&section=showship&id=".$data[id].">".$data[name]."</a> (".$ids.")</td>
				<td class=tdmainobg><img src=gfx/map/".$data[type].".gif width=15 height=15> ".$data[coords_x]."/".$data[coords_y].$wadd."</td>
				<td class=tdmainobg align=center>".$data[huelle]."/".$data[maxhuell]."</td>
				<td class=tdmainobg align=center><img src=".$grafik."/buttons/alert".$data[alertlevel].".gif></td>
				<td class=tdmainobg align=center>".$data[energie]."/".$data[maxeps]." (".$data[batt].") (".($data[erzeugung] >= 0 ? "<font color=Green>+".$data[erzeugung]."</font>" : "<font color=#FF0000>".$data[erzeugung]."</font>")."/<font color=#B22222>".$data[verbrauch]."</font>/".$data[maxreaktor].")</td>
				<td class=tdmainobg align=center>".$schilde."</td>
				<td class=tdmainobg align=center><a href=main.php?page=showinfo&section=showstorage&id=".$data[id]." target=leftbottom>".$data[crew]."</a></td>
				<td class=tdmainobg align=center>".$r."/".$rd.$rda."</td>
				<td class=tdmainobg align=center>".$l."/".$k."</td>
				<td class=tdmainobg>".$ftd."</td>
			</tr>";
			unset($ftd);
			unset($rda);
			unset($wadd);
			$lf = $data[fleets_id];
		}
	}
	$sort = explode(" ",$myUser->getslsorting());
	if ($sort[0] == "class") $sc = " SELECTED";
	if ($sort[0] == "name") $sn = " SELECTED";
	if ($sort[0] == "coords") $sk = " SELECTED";
	if ($sort[0] == "energie") $se = " SELECTED";
	if ($sort[1] == "DESC") $su = " SELECTED";
	if ($sort[1] == "ASC") $sd = " SELECTED";
	echo "</table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Sortierung</strong></td></tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<tr><td class=tdmainobg><select name=sort>
	<option value=class".$sc.">Typ
	<option value=name".$sn.">Name
	<option value=coords".$sk.">Koordinaten
	<option value=energie".$se.">Energie</select> <select name=way>
	<option value=up".$su.">Aufsteigend
	<option value=down".$sd.">Absteigend</select> <input type=submit value=sortieren class=button></td></tr></form></table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Wirtschaftspunkte</strong></td></tr>
	<tr><td class=tdmainobg>".round($myDB->query("SELECT SUM(points) FROM stu_ships WHERE user_id=".$user,1),2)."/".(round($myColony->getwirtschaft($user),2)+floor($myUser->usymp/2500))." (".$myUser->uwirtplus.")</td></tr></table>";
	$result = $myColony->getunfinishedshipprocesses($user);
	if (mysql_num_rows($result) != 0)
	{
		echo "<br><table bgcolor=#262323 cellpadding=1 cellspacing=1>
		<tr><td class=tdmain><strong>Baustatus</strong></td></tr>";
		while ($data=mysql_fetch_assoc($result)) 
		{
			if ($data[c][secretimage] != "0")
			{
				echo "<tr><td class=tdmainobg><img src=http://www.stuniverse.de/gfx/secret/".$data[secretimage].".gif> ".$data[name]." in Bau bis ".date("d.m.Y H:i:s",$data[buildtime])."</td></tr>";
			}
			else
			{
				echo "<tr><td class=tdmainobg><img src=".$grafik."/ships/".$data[ships_rumps_id].".gif> ".$data[name]." in Bau bis ".date("d.m.Y H:i:s",$data[buildtime])."</td></tr>";
			}
		}
		echo "</table>";
	}
}
elseif (($section == "showship") && $id)
{
	$fleet = $myFleet->getfleetbyshipid($id);
	if ($fleet != 0 && $user == $fleet[user_id])
	{
		if ($action == "phaser" && $id && $id2)	{ $result = $myFleet->phaser($id,$id2); $fleetac = 1; }
		if ($action == "torp" && $id && $id2) { $result = $myFleet->torp($id,$id2); $fleetac = 1; }
		if ($action == "move" && $id && $x > 0 && $y > 0) { $result = $myFleet->move($id,$x,$y); $fleetac = 1; }
		if ($action == "movewh" && $id)	{ $result = $myFleet->wormhole($id); $fleetac = 1; }
		if ($action == "ap" && $id && $way && $fields && is_numeric($fields)) { $result = $myFleet->autopilot($id,$way,$fields); $fleetac = 1; }
		if ($command == "shieldson" && $id)	{ $result = $myFleet->activatevalue($id,"schilde_aktiv"); $fleetac = 1; }
		if ($command == "agreen" && $id) { $result = $myFleet->alertlevel($id,1,$user); $fleetac = 1; }
		if ($command == "ayellow" && $id) { $result = $myFleet->alertlevel($id,2,$user); $fleetac = 1; }
		if ($command == "ared" && $id) { $result = $myFleet->alertlevel($id,3,$user); $fleetac = 1; }
		if ($command == "bussard" && $id && $ener_count) { $result = $myFleet->bussard($id,$ener_count); $fleetac = 1; }
		if ($command == "erz" && $id && $ener_count) { $result = $myFleet->collect($id,$ener_count); $fleetac = 1; }
		if ($command == "replikatoron" && $id) { $result = $myFleet->activatevalue($id,"replikator"); $fleetac = 1; }
		if ($command == "ebatt" && $id && $batt_count) { $result = $myFleet->ebatt($id,$batt_count); $fleetac = 1; }
		if ($command == "shload" && $id && $load) { $result = $myFleet->shload($id,$load); $fleetac = 1; }
		if ($command == "ksson" && $id) { $result = $myFleet->activatevalue($id,"kss"); $fleetac = 1; }
		if ($command == "cloakon" && $id) { $result = $myFleet->activatevalue($id,"cloak"); $fleetac = 1; }
		if ($command == "decloak" && $id) { $result = $myFleet->decloak($id); $fleetac = 1; }
		if ($command == "fmphaser" && $id) { $result = $myFleet->fmphaser($id); $fleetac = 1; }
		if ($command == "fmtorp" && $id) { $result = $myFleet->fmtorp($id); $fleetac = 1; }
	}
	if ($action != "" || $command != "") $gcsr = $myShip->gcs();
	if ($command == "lssoff" && $myShip->cfleets_id > 0) $result = $myFleet->fdeac("lss");
	if ($command == "cloakoff" && $myShip->cfleets_id > 0) $result = $myFleet->fdeac("cloak");
	if ($command == "kssoff" && $myShip->cfleets_id > 0) $result = $myFleet->fdeac("kss");
	if ($command == "replikatoroff" && $myShip->cfleets_id > 0) $result = $myFleet->fdeac("replikator");
	if ($command == "shieldsoff" && $myShip->cfleets_id > 0) $result = $myFleet->fdeac("schilde_aktiv");
	// Einzelaktionen
	if ($action == "phaser" && $id && $id2 && $fleetac == 0) $result = $myShip->phaser($id,$id2,$user);
	if ($action == "bussard" && $id && $ener_count && (is_numeric($ener_count) || $ener_count == "max")) $result = $myShip->bussard($id,$ener_count,$user);
	if ($action == "ebatt" && $id && $batt_count && (is_numeric($batt_count) || $batt_count == "max")) $result = $myShip->ebatt($id,$batt_count,$user);
	if ($action == "colonize" && $id && $colid) $result = $myShip->colonize($id,$colid,$user);
	if ($action == "rename" && $new_name) $result = $myShip->newname($id,$new_name);
	if ($action == "ap" && $way && $fields && $fleetac == 0 && is_numeric($fields)) $result = $myShip->moveap($id,$way,$fields,$user);
	if ($action == "move" && $x > 0 && $y > 0 && $fleetac == 0) $result = $myShip->move($id,$x,$y,$user);
	if ($action == "activate" && $value) $result = $myShip->activateValue($id,$value,$user);
	if ($action == "dock" && $id != $id2 && $id && $id2) $result = $myShip->dock($id,$id2,$user);
	if ($action == "undock" && $id && $id2) $result = $myShip->undock($id,$id2,$user);
	if ($action == "shieldemitter" && $id && $shield_count && (is_numeric($shield_count) || ($shield_count == "max"))) $result = $myShip->shieldemitter($id,$shield_count,$user);
	if ($action == "deactivate" && $value) $result = $myShip->deactivateValue($id,$value,$user);
	if ($action == "etransfer" && $id && $id2 && $count && (is_numeric($count) || ($count == "max"))) $result = $myShip->etransfer($id,$id2,$count,$user,$mode);
	if ($action == "collect" && $id && $collect_count && (is_numeric($collect_count) || ($collect_count == "max"))) $result = $myShip->collect($id,$collect_count,$user);
	if ($action == "firemode" && $id && $firemode) $result = $myShip->setfiremode($id,$firemode);
	if ($action == "traktor" && $id && $id2) $result = $myShip->traktor($id,$id2,$user);
	if ($action == "selfdestruct" && $id && $destructcode) $result = $myShip->selfdestruct($id,$destructcode);
	if ($action == "torp" && $id && $id2 && $type && $fleetac == 0) $result = $myShip->torp($id,$id2,$user,$type);
	if ($action == "alertlevel" && $id && $alertlevel) $result = $myShip->alertlevel($id,$alertlevel,$user);
	if ($action == "traktoroff" && $id) $result = $myShip->traktoroff($id);
	if ($action == "warpcore" && $id) $result = $myShip->loadwarpcore($id,$max);
	if ($action == "movewh" && $id && $fleetac == 0) $result = $myShip->wormhole($id,$user);
	if ($action == "buildkonstrukt" && $id) $result = $myShip->buildkonstrukt($id);
	if ($action == "buildstation" && $stationid && $id) $result = $myShip->buildstation($id,$stationid);
	if ($action == "adddockperm" && $type && $dockid && $perm && $id) $result = $myShip->adddockperm($id,$type,$dockid,$perm);
	if ($action == "dedock" && $id2 && $id) $result = $myShip->dedock($id,$id2);
	if ($action == "deldockper" && $id2 && $id) $result = $myShip->deldockpermission($id,$id2);
	if ($action == "randomphaser" && $id) $result = $myShip->randomPhaser($id);
	if ($action == "defendship" && $id && $id2) $result = $myShip->defendShip($id2,$id,$user);
	if ($action == "deletedefender" && $id && $id2) $result = $myShip->deletedefender($id,$id2,$user);
	if ($action == "fireprobe" && $id && $sonclass) $result = $myShip->fireprobe($id,$sonclass);
	if ($action == "graviton" && $id) $result = $myShip->gravitonscan($id);
	if ($action == "shiprepair" && $id && $targetid) $result = $myShip->shiprepair($id,$targetid);
	if ($action == "loadstationbatt" && $id && $batt_count && $targetid && (is_numeric($batt_count) || $batt_count == "max")) $result = $myShip->loadstationbatt($targetid,$batt_count);
	if ($action == "npcfiremode" && $id) $result = $myShip->npcchangefiremode($id);
	if ($action == "npcmvam" && $id) $result = $myShip->npcmvam($id);
	if ($action == "npclfighter" && $id) $result = $myShip->npclaunchfighter($id);
	if ($action == "npccfighter" && $id) $result = $myShip->npcreturnfighter($id);
	if ($action == "chgflagg" && is_numeric($id) && is_numeric($id2)) $result = $myFleet->chgflag($id,$id2);
	if ($action == "transferto" && $id && $id2)
	{
		if ($crew && is_numeric($crew)) $result = $myShip->transferCrew($id,$id2,$crew,$mode,$way);
		if (is_array($beam))
		{
			foreach($beam as $key => $value)
			{
				if (!is_numeric($value) || !is_numeric($good[$key])) continue;
				$res = $myShip->transferto($id,$id2,$good[$key],$value,$mode,$freq1.$freq2);
				$result[msg] .= $res[msg];
				if ($res[code] < 1) break;
				if ($res[code] == 1)
				{
					$dummygood[$j][id] = $good[$key];
					$dummygood[$j]['count'] = $res[beamed];
					$j++;
				}
			}
		}
		if (is_array($dummygood)) $myShip->beammsg($dummygood,$id,$id2,"to",$mode);
	}
	if ($action == "transferfrom" && $id && $id2)
	{
		if ($crew && is_numeric($crew)) $result = $myShip->transferCrew($id,$id2,$crew,$mode,$way);
		if (is_array($beam))
		{
			foreach($beam as $key => $value)
			{
				if (!is_numeric($value) || !is_numeric($good[$key])) continue;
				$res = $myShip->transferfrom($id,$id2,$good[$key],$value,$mode,$freq1.$freq2);
				$result[msg] .= $res[msg];
				if ($res[code] < 1) break;
				if ($res[code] == 1)
				{
					$dummygood[$j][id] = $good[$key];
					$dummygood[$j]['count'] = $res[beamed];
					$j++;
				}
			}
		}
		if (is_array($dummygood)) $myShip->beammsg($dummygood,$id,$id2,"from",$mode);
	}
	if ($action != "" || $command != "") $myShip->gcs();
	if ($myShip->cshow == 0)
	{
		echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain align=center><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".stripslashes($result[msg])."</td></tr><tr><td class=tdmainobg>Du bist nicht Besitzer dieses Schiffes</td></tr></table>";
		exit;
	}
	if ($myShip->cdeact == 1)
	{
		if (!$result) $result = array("msg" => "Kommunikationsverbindung abgerissen");
		echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
		<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <strong>".$myShip->cname."</strong></td>
		</tr>
		</table><br>";
		if (is_array($result)) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr>
		<tr><td class=tdmainobg>".stripslashes($result[msg])."</td></tr></table><br>";
		exit;
	}
	if ($myShip->cfleets_id > 0) $fleet = $myFleet->getfleetbyid($myShip->cfleets_id); 
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <strong>".stripslashes($myShip->cname)."</strong></td>
	</tr>
	</table><br>";
	if (is_array($result)) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr>
	<tr><td class=tdmainobg>".stripslashes($result[msg])."</td></tr></table><br>";
	echo "<table width=700 bgcolor=#262323>
	<tr>
		<td class=tdmain></td>
		<td class=tdmain></td>
		<td class=tdmain>Name</td>
		<td class=tdmain>Energie</td>
		<td class=tdmain>Hülle</td>
		<td class=tdmain>Schilde</td>
		<td class=tdmain>Crew</td>
		<td class=tdmain>Koordinaten</td>
		<td class=tdmain>Info</td>
	</tr>
	<tr>";
	$myShip->cdamaged == 1 ? $mpf = "d/" : $mpf = "";
	if ($myShip->csecretimage != "0")
	{
		$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$myShip->csecretimage.".gif title=\"".$myShip->cclass[name]."\">";
	}
	else
	{
		$shippic = "<img src=".$grafik."/ships/".$mpf.$myShip->cships_rumps_id.".gif title=\"".$myShip->cclass[name]."\">";
	}
	$myShip->cerzeugung > 0 ? $epf = "+" : $epf = "";
	if ($myShip->cwese == 2) $wadd = " (2)";
	if ($myShip->cwese == 3) $wadd = " (3)";
	echo "<td class=tdmainobg><a href=?page=ship&section=showship&id=".$id." onMouseOver=\"cp('ak','buttons/lese2')\" onMouseOut=\"cp('ak','buttons/lese1')\"><img src='".$grafik."/buttons/lese1.gif' name=ak border=0 title='aktualisieren'></a></td>
		<td class=tdmainobg>".$shippic."</td>
		<td class=tdmainobg>".stripslashes($myShip->cname)."</td>
		<td class=tdmainobg>".$myShip->cenergie."/".$myShip->cmaxeps."(<font color=Green>".$epf.$myShip->cerzeugung."</font>/<font color=#B22222>".$myShip->cverbrauch."</font>/".$myShip->cmaxreaktor.")</td>
		<td class=tdmainobg>".$myShip->chuelle."/".$myShip->cmaxhuell."</td>
		<td class=tdmainobg>".$myShip->cschilde."/".$myShip->cmaxshields."</td>
		<td class=tdmainobg>".$myShip->ccrew."/".$myShip->cclass[crew]." (".$myShip->cclass[crew_min].")</td>
		<td class=tdmainobg><img src=".$grafik."/map/".$myShip->ctype.".gif width=15 height=15> ".$myShip->ccoords_x."/".$myShip->ccoords_y.$wadd."</td>
		<td class=tdmainobg><center><a href=main.php?page=shiphelp&section=ship&shipid=".$id." target=leftbottom onMouseOver=\"cp('info','buttons/info2')\" onMouseOut=\"cp('info','buttons/info1')\"><img src=".$grafik."/buttons/info1.gif name=info border=0></center></a></td>
	</tr></table><br>
	<table>
	<tr>
		<td valign=top>";
	if ($myShip->cclass[cloak] == 1)
	{
		 $myShip->ccloak == 0 ? $clink = "<a href=main.php?page=ship&section=showship&action=activate&value=cloak&id=".$id." onMouseOver=\"cp('tarn','buttons/tarn2')\" onMouseOut=\"cp('tarn','buttons/tarn1')\"><img src=".$grafik."/buttons/tarn1.gif name=tarn border=0> aktivieren</a>" : $clink = "<a href=main.php?page=ship&section=showship&action=deactivate&value=cloak&id=".$id." onMouseOver=document.tarn.src='".$grafik."/buttons/tarn1.gif' onMouseOut=document.tarn.src='".$grafik."/buttons/tarn2.gif'><img src=".$grafik."/buttons/tarn2.gif name=tarn border=0> deaktivieren</a>";
		 $cloak = "<tr>
			<td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/tarnv.gif> <strong>Tarnvorrichtung</strong></td>
			</tr>
		<tr><td class=tdmainobg width=100% colspan=2>".$clink."</td></tr>";
	}
	if ($myShip->ctraktormode == 1)
	{
		$traktorbeam = "<tr>
			<td class=tdmainobg width=45%><a href=main.php?page=ship&section=showship&action=traktoroff&id=".$id." onMouseOver=\"cp('traktor','buttons/trak1')\" onMouseOut=\"cp('traktor','buttons/trak2')\"><img src=".$grafik."/buttons/trak2.gif name=traktor border=0> Deaktivieren</a></td>
			<td class=tdmainobg width=55%>Ziel: ".stripslashes($myShip->getfield("name",$myShip->ctraktor))."</td>
		</tr>";
	}
	else $traktorbeam = "<tr><td class=tdmainobg colspan=2><img src=".$grafik."/buttons/trak1.gif> Deaktiviert</td></tr>";
	if ($myShip->cschilde_aktiv == 1)
	{
		$shields = "<a href=main.php?page=ship&section=showship&action=deactivate&value=schilde_aktiv&id=".$id." onMouseOver=\"cp('shields','buttons/shldac1')\" onMouseOut=\"cp('shields','buttons/shldac2')\"><img src=".$grafik."/buttons/shldac2.gif name=shields border=0> deaktivieren</a>";
		$shields_load = "<input class=text type=text size=2 name=shield_count> <input class=button type=submit value=laden disabled=yes> <input type=submit name=shield_count value=max class=button disabled=yes>";
	}
	else
	{
		$myShip->cenergie == 0 || $myShip->cschilde >= $myShip->cmaxshields ? $sda = " disabled=yes" : $sda = "";
		$shields = "<a href=main.php?page=ship&section=showship&action=activate&value=schilde_aktiv&id=".$id." onMouseOver=\"cp('shields','buttons/shldac2')\" onMouseOut=\"cp('shields','buttons/shldac1')\"><img src=".$grafik."/buttons/shldac1.gif name=shields border=0> aktivieren</a>";
		$shields_load = "<input class=text type=text size=2 name=shield_count".$sda."> <input class=button type=submit value=laden".$sda."> <input type=submit name=shield_count value=max class=button".$sda.">";
	}
	echo "<table width=300 bgcolor=#262323>";
	if ($myShip->cclass[probe] != 1) echo "<tr>
		<td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/batt.gif> <strong>Ersatzbatterie</strong></td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=showship>
	<input type=hidden name=action value=ebatt>
	<input type=hidden name=id value=".$id.">";
	$myShip->cbatt == 0 ? $bdv = " disabled=yes" : $bdv = "";
	echo "<tr>
		<td class=tdmainobg width=45%><img src=".$grafik."/buttons/battp2.gif title='Ersatzbatterie'> Energie: ".$myShip->cbatt."</td>
		<td class=tdmainobg width=55%><input class=text type=text size=2 maxlength=3 name=batt_count".$bdv."> <input class=button type=submit value=entladen".$bdv."> <input type=submit name=batt_count value=max class=button".$bdv."></td>
	</tr>
	</form>
	<tr>
		<td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/shld.gif> <strong>Schilde</strong></td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=showship>
	<input type=hidden name=action value=shieldemitter>
	<input type=hidden name=id value=".$id.">
	<tr>
		<td class=tdmainobg width=45%>".$shields."</td>
		<td class=tdmainobg width=55%>".$shields_load."</td>
	</tr>
	</form>
	<tr>
		<td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/trak.gif> <strong>Traktorstrahl</strong></td>
	</tr>
	".$traktorbeam."
	".$cloak;
	if ($myShip->cclass[erz] > 0 && ($myShip->ctype == 4 || $myShip->ctype == 5 || $myShip->ctype == 17 || $myShip->ctype == 18 || $myShip->ctype == 19 || $myShip->ctype == 20 || $myShip->ctype == 32))
	{
		if ($myShip->ctype == 4) { $vk = 4; $t = "Iridium"; }
		elseif ($myShip->ctype == 5) { $vk = 6; $t = "Iridium"; }
		elseif ($myShip->ctype == 17) { $vk = 2; $t = "Kelbonit"; }
		elseif ($myShip->ctype == 18) { $vk = 3; $t = "Kelbonit"; }
		elseif ($myShip->ctype == 19) { $vk = 2; $t = "Nitrium"; }
		elseif ($myShip->ctype == 20) { $vk = 3; $t = "Nitrium"; }
		if ($myShip->csensormodlvl == 101) $b = "<font color=yellow>+</font>";
		echo "<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=action value=collect>
		<input type=hidden name=id value=".$id.">
		<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/goods/4.gif> <strong>Erzkollektoren</strong></td></tr>
		<tr><td class=tdmainobg><img src=".$grafik."/buttons/erz1.gif title='".$t."-Erz sammeln'> Vorkommen ".$vk."/".$myShip->cclass[erz]."".$b."</td>
		<td class=tdmainobg><input class=text type=text size=2 name=collect_count> <input class=button type=submit value=sammeln> <input type=submit name=collect_count value=max class=button></td></tr></form>";
	}
	if ($myShip->cclass[bussard] && ($myShip->ctype == 2 || $myShip->ctype == 3 || $myShip->ctype == 30))
	{
		if ($myShip->ctype == 2) $buscount = 3;
		if ($myShip->ctype == 3) $buscount = 7;
		if ($myShip->ctype == 2 || $myShip->ctype == 3) $vk = $buscount."/".$myShip->cclass[bussard];
		if ($myShip->ctype == 30) $vk = floor($myShip->cclass[bussard]/3);
		echo "<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=action value=bussard>
		<input type=hidden name=id value=".$id.">
		<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/goods/2.gif> <strong>Bussardkollektoren</strong></td></tr>
		<tr><td class=tdmainobg width=45%><img src=".$grafik."/buttons/buss2.gif title='Bussardkollektoren'> Vorkommen: ".$buscount."/".$myShip->cclass[bussard]."</td>
		<td class=tdmainobg width=55%><input class=text type=text size=2 name=ener_count> <input class=button type=submit value=sammeln> <input type=submit name=ener_count value=max class=button></td></tr></form>";
	}
	if ($myShip->creaktormodlvl > 0)
	{
		if ($myShip->creaktormodlvl == 105) echo "<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/goods/212.gif> <strong>Plasmareaktor</strong></td></tr>";
		else echo "<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/warpk.gif> <strong>Warpkern</strong></td></tr>";
        	echo "<form action=main.php method=post>
			<input type=hidden name=page value=ship>
			<input type=hidden name=section value=showship>
			<input type=hidden name=id value=".$id.">
			<input type=hidden name=action value=warpcore>
			<input type=hidden name=max value=1>
			<tr><td class=tdmainobg width=45%><a href=main.php?page=ship&section=showship&action=warpcore&id=".$id." onMouseOver=\"cp('warp','buttons/wkp2')\" onMouseOut=\"cp('warp','buttons/wkp1')\"><img src=".$grafik."/buttons/wkp1.gif name=warp border=0> Aufladen</a> <input type=submit value=max class=button></td>
			<td class=tdmainobg width=55%>Ladung: ".$myShip->cwarpcore."</td></tr></form>";
	}
	if ($myShip->cclass[replikator] == 1)
	{
		$myShip->creplikator == 1 ? $replink = "<a href=main.php?page=ship&section=showship&action=deactivate&value=replikator&id=".$id." onMouseOver=\"cp('repli','buttons/repli1')\" onMouseOut=\"cp('repli','buttons/repli2')\"><img src=".$grafik."/buttons/repli2.gif name=repli border=0> deaktivieren</a>" : $replink = "<a href=main.php?page=ship&section=showship&action=activate&value=replikator&id=".$id." onMouseOver=document.repli.src='".$grafik."/buttons/repli2.gif' onMouseOut=document.repli.src='".$grafik."/buttons/repli1.gif'><img src=".$grafik."/buttons/repli1.gif name=repli border=0> aktivieren</a>";
		echo "<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/repli.gif> <strong>Replikator</strong></td></tr>
		<tr><td class=tdmainobg width=45%>".$replink."</td>
		<td class=tdmainobg width=55%>Verbrauch: ".ceil($myShip->ccrew/5)." Energie</td></tr>";
	}
	if ($myShip->cships_rumps_id == 201 || $myShip->cships_rumps_id == 202 || $myShip->cships_rumps_id == 203 || $myShip->cships_rumps_id == 204 || $myShip->cships_rumps_id == 205 || $myShip->cships_rumps_id == 206 || $myShip->cships_rumps_id == 207)
	{
		$stationinfo = $myShip->getstationbysektor($myShip->ccoords_x,$myShip->ccoords_y,$myShip->cwese);
		if ($stationinfo != 0 && $stationinfo != $id)
		{
			echo "<table width=300 bgcolor=#262323>";
			echo "<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/station.gif> <strong>Stationsbatterien laden</strong></td>
			</tr>
			<form action=main.php method=post>
			<input type=hidden name=page value=ship>
			<input type=hidden name=section value=showship>
			<input type=hidden name=action value=loadstationbatt>
			<input type=hidden name=targetid value=".$stationinfo[id].">
			<input type=hidden name=id value=".$id.">";
			echo "<tr>
				<td class=tdmainobg width=45%><img src=".$grafik."/buttons/battp2.gif title='Ersatzbatterie'> Aufladen</td>
				<td class=tdmainobg width=55%><input class=text type=text size=2 maxlength=3 name=batt_count> <input class=button type=submit value=aufladen> <input type=submit name=batt_count value=max class=button></td>
			</tr>
			</form>";
		}
	}
	if ($myShip->cclass[size] == 5 && $myShip->npcgetfiremode($id) != 0)
	{
		if ($myShip->npcgetfiremode($id) == 1) $npcf = "<a href=main.php?page=ship&section=showship&action=npcfiremode&id=".$id." onMouseOver=\"cp('npcf','buttons/tmode12')\" onMouseOut=\"cp('npcf','buttons/tmode11')\"><img src=".$grafik."/buttons/tmode11.gif name=npcf border=0> Auf Quad umstellen</a>";
		elseif ($myShip->npcgetfiremode($id) == 2) $npcf = "<a href=main.php?page=ship&section=showship&action=npcfiremode&id=".$id." onMouseOver=\"cp('npcf','buttons/tmode42')\" onMouseOut=\"cp('npcf','buttons/tmode41')\"><img src=".$grafik."/buttons/tmode41.gif name=npcf border=0> Auf Einzel umstellen</a>";
		echo "<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/gefecht.gif> <strong>Feuermodus</strong></td></tr>
		<tr><td class=tdmainobg colspan=2 width=100%>".$npcf."</td></tr>";
	}
	if ($myShip->cships_rumps_id == 136 || $myShip->cships_rumps_id == 165 || $myShip->cships_rumps_id == 174 || $myShip->cships_rumps_id == 175)
	{
		if ($myShip->cships_rumps_id == 136 || $myShip->cships_rumps_id == 174) $npcm = "<a href=main.php?page=ship&section=showship&action=npcmvam&id=".$id." onMouseOver=\"cp('npcm','buttons/mvama2')\" onMouseOut=\"cp('npcm','buttons/mvama1')\"><img src=".$grafik."/buttons/mvama1.gif name=npcm border=0> Trennung einleiten</a>";
		elseif ($myShip->cships_rumps_id == 165 || $myShip->cships_rumps_id == 175) $npcm = "<a href=main.php?page=ship&section=showship&action=npcmvam&id=".$id." onMouseOver=\"cp('npcm','buttons/mvamd2')\" onMouseOut=\"cp('npmc','buttons/mvamd1')\"><img src=".$grafik."/buttons/mvamd1.gif name=npcm border=0> Reintegration einleiten</a>";
		echo "<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/mvam.gif> <strong>Multi-Vektor-Angriffsmodus</strong></td></tr>
		<tr><td class=tdmainobg colspan=2 width=100%>".$npcm."</td></tr>";
	}
	if ($myShip->cships_rumps_id == 211)
	{
		$lfighter = "<a href=main.php?page=ship&section=showship&action=npclfighter&id=".$id." onMouseOver=\"cp('npclf','buttons/lfighter2')\" onMouseOut=\"cp('npclf','buttons/lfighter1')\"><img src=".$grafik."/buttons/lfighter1.gif name=npclf border=0> Jäger starten</a>";
		$cfighter = "<a href=main.php?page=ship&section=showship&action=npccfighter&id=".$id." onMouseOver=\"cp('npccf','buttons/cfighter2')\" onMouseOut=\"cp('npccf','buttons/cfighter1')\"><img src=".$grafik."/buttons/cfighter1.gif name=npccf border=0> Rückruf</a>";
		echo "<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/hangar.gif> <strong>Hangar</strong></td></tr>
		<tr><td class=tdmainobg colspan=2 width=100%>".$lfighter."</td></tr>
		<tr><td class=tdmainobg colspan=2 width=100%>".$cfighter."</td></tr>";
	}
	if (($myShip->s1count != 0 || $myShip->s2count != 0 || $myShip->s3count != 0 || $myShip->s4count != 0 || $myShip->s5count != 0) && $myShip->cships_rumps_id != 63 && $myShip->cships_rumps_id != 65 && $myShip->cships_rumps_id != 66 && $myShip->cships_rumps_id != 67 && $myShip->cships_rumps_id != 68)
	{
		$sonden = "<form action=main.php method=post><input type=hidden name=page value=ship><input type=hidden name=section value=showship><input type=hidden name=action value=fireprobe><input type=hidden name=id value=".$id.">";
		$choice .= "<select name=sonclass>";
		if ($myShip->s1count != 0) $choice .= "<option value=1>Sonde Klasse 1</option>";
		if ($myShip->s2count != 0) $choice .= "<option value=2>Sonde Klasse 2</option>";
		if ($myShip->s3count != 0) $choice .= "<option value=3>Sonde Klasse 3</option>";
		if ($myShip->s4count != 0) $choice .= "<option value=4>Ionen-Sonde</option>";
		if ($myShip->s5count != 0) $choice .= "<option value=5>Detektions-Drohne</option>";
		$choice .= "</select>";
		echo $sonden."<tr><td class=tdmain colspan=2 width=100%><img src=".$grafik."/buttons/s1.gif> <strong>Sonde starten</strong></td></tr>
		<tr><td  class=tdmainobg width=45%>".$choice."</td>
		<td class=tdmainobg width=55%><input type=submit value=Starten class=button></td></tr></form>";
	}
	$colData = $myColony->getcolbyfield($myShip->ccoords_x,$myShip->ccoords_y,$myShip->cwese);
	if ($colData != -1) 
	{
		if ($myShip->frace == 98)
		{
			echo "<tr><td class=tdmain colspan=2><strong>Kolonisation</strong></td></tr>
			<tr><td colspan=2 class=tdmainobg width=100%><img src=".$grafik."/planets/".$colData[colonies_classes_id].".gif> 
			<a href=main.php?page=ship&section=colonizepuffer&id=".$id."&colid=".$colData[id].">Kolonisieren</a></td></tr>";
		}
		else
		{
			echo "<tr><td class=tdmain colspan=2><strong>Kolonisation</strong></td></tr>
			<tr><td colspan=2 class=tdmainobg width=100%><img src=".$grafik."/planets/".$colData[colonies_classes_id].".gif> <a href=main.php?page=ship&section=showship&action=colonize&id=".$id."&colid=".$colData[id].">Kolonisieren</a></td></tr>";
		}
	}
	if ($myShip->cclass[id] == 7) echo "<tr><td class=tdmain colspan=2><img src=".$grafik."/buttons/station.gif> <strong>Stationsbau</strong></td></tr>
		<tr><td colspan=2 class=tdmainobg><a href=main.php?page=ship&section=showship&action=buildkonstrukt&id=".$id." onMouseOver=\"cp('konstr','buttons/konstr2')\" onMouseOut=\"cp('konstr','buttons/konstr1')\"><img src=".$grafik."/buttons/konstr1.gif name=konstr border=0> Konstrukt errichten</a></td></tr>";
	$bcheck = $myShip->checkStationProgress($id);
	if ($myShip->cclass[id] == 111 && $bcheck == 0) echo "<tr><td class=tdmain colspan=2><img src=".$grafik."/buttons/station.gif> <strong>Stationsbau</strong></td></tr>
		<tr><td colspan=2 class=tdmainobg width=100%><a href=main.php?page=ship&section=showship&section=buildstation&id=".$id." onMouseOver=\"cp('statio','buttons/statio2')\" onMouseOut=\"cp('statio','buttons/statio1')\"><img src=".$grafik."/buttons/statio1.gif name=statio border=0> Station bauen</a></td></tr>";
	elseif ($bcheck != 0)
	{
		$bclass = $myShip->getclassbyid($bcheck[ships_rumps_id]);
		if ($bclass[secretimage] != "0")
		{
			$bpic = "<img src=http://www.stuniverse.de/gfx/secret/".$bclass[secretimage].".gif align=left>";
		}
		else
		{
			$bpic = "<img src=".$grafik."/ships/".$bcheck[ships_rumps_id].".gif align=left>";
		}
		echo "<tr><td class=tdmain colspan=2><img src=".$grafik."/buttons/station.gif> <strong>Stationsbau</strong></td></tr>
		<tr><td colspan=2 class=tdmainobg width=100%>".$bpic." In Bau: ".$bclass[name]."<br>Ende: ".date("d.m.Y H:i",$bcheck[buildtime])."</td></tr>";
	}
	$wormdata = $myMap->checkwormhole($myShip->ccoords_x,$myShip->ccoords_y,$myShip->cwese);
	if ($wormdata != 0)
	{
		if ($wormdata[stable] == 0) $wadd = " (Instabil)";
		echo "<tr><td class=tdmain colspan=2><img src=".$grafik."/buttons/wormh1.gif> <strong>Wurmloch</strong>".$wadd."</td></tr>
		<tr><td colspan=2 class=tdmainobg><a href=main.php?page=ship&section=showship&action=movewh&id=".$id." onMouseOver=\"cp('wh','buttons/wormh2')\" onMouseOut=\"cp('wh','buttons/wormh1')\"><img src=".$grafik."/buttons/wormh1.gif name=wh border=0> Hineinfliegen</a></td></tr>";
	}
	if (($myShip->ctype == 31 || $myShip->ctype == 15) && $myShip->cclass[probe] == 0) echo "<tr><td class=tdmain colspan=2><img src=".$grafik."/buttons/phaser.gif> <strong>Phasersalve</strong></td></tr>
		<tr><td colspan=2 class=tdmainobg width=100%><a href=main.php?page=ship&section=showship&section=showship&action=randomphaser&id=".$id." onMouseOver=\"cp('phaser','buttons/phaser2')\" onMouseOut=\"cp('phaser','buttons/phaser1')\"><img src=".$grafik."/buttons/phaser1.gif name=phaser border=0> Phasersalve feuern</a></td></tr>";
	if ($myShip->cclass[slots] == 0)
	{
		echo "<tr>
		<td valign=top colspan=2 class=tdmainobg>
		<table>
		<tr>
		<td>";
		$myShip->ccoords_x-1 > 0 && $myShip->ccoords_y-1 > 0 ? $a1 = ($myShip->ccoords_x-1)."/".($myShip->ccoords_y-1) : $a1 = "-";
		$myShip->ccoords_x > 0 && $myShip->ccoords_y-1 > 0 ? $b1 = "<a href=main.php?page=ship&section=showship&action=move&x=".$myShip->ccoords_x."&y=".($myShip->ccoords_y-1)."&id=".$id.">".$myShip->ccoords_x."/".($myShip->ccoords_y-1)."</a>" : $b1 = "-";
		$myShip->ccoords_x+1 <= $mapfields[max_x] && $myShip->ccoords_y-1 > 0 ? $c1 = ($myShip->ccoords_x+1)."/".($myShip->ccoords_y-1) : $c1 = "-";
		$myShip->ccoords_x-1 > 0 && $myShip->ccoords_y > 0 ? $a2 = "<a href=main.php?page=ship&section=showship&action=move&x=".($myShip->ccoords_x-1)."&y=".$myShip->ccoords_y."&id=".$id.">".($myShip->ccoords_x-1)."/".$myShip->ccoords_y."</a>" : $a2 = "-";
		$myShip->ccoords_x+1 <= $mapfields[max_x] && $myShip->ccoords_y > 0 ? $c2 = "<a href=main.php?page=ship&section=showship&action=move&x=".($myShip->ccoords_x+1)."&y=".$myShip->ccoords_y."&id=".$id.">".($myShip->ccoords_x+1)."/".$myShip->ccoords_y."</a>" : $c2 = "-";
		$myShip->ccoords_x-1 > 0 && $myShip->ccoords_y+1 <= $mapfields[max_y] ? $a3 = ($myShip->ccoords_x-1)."/".($myShip->ccoords_y+1) : $a3 = "-";
		$myShip->ccoords_x > 0 && $myShip->ccoords_y+1 <= $mapfields[max_y] ? $b3 = "<a href=main.php?page=ship&section=showship&action=move&x=".$myShip->ccoords_x."&y=".($myShip->ccoords_y+1)."&id=".$id.">".$myShip->ccoords_x."/".($myShip->ccoords_y+1)."</a>" : $b3 = "-";
		$myShip->ccoords_x+1 <= $mapfields[max_x] && $myShip->ccoords_y+1 <= $mapfields[max_y] ? $c3 = ($myShip->ccoords_x+1)."/".($myShip->ccoords_y+1) : $c3 = "-";
		if ($way == "hoch") $hoch = "SELECTED";
		elseif ($way == "runter") $runter = "SELECTED";
		elseif ($way == "links") $links = "SELECTED";
		elseif ($way == "rechts") $rechts = "SELECTED";
		if (!$fields) $fields = 2;
		if ($myShip->cenergie == 0) $apa = " disabled=yes";
		echo "<table width=100 height=100 bgcolor=#262323>
		<tr>
			<td width=150 align=Center colspan=3 class=tdmain align=center><b>Navigation</b></td>
		</tr>
		<tr>
			<td width=50 align=Center class=tdmainobg>".$a1."</td>
			<td width=50 align=Center class=tdmainobg><b>".$b1."</b></td>
			<td width=50 align=Center class=tdmainobg>".$c1."</td>
		</tr>
		<tr>
			<td width=50 align=Center class=tdmainobg><b>".$a2."</b></td>
			<td width=50 align=Center class=tdmainobg>".$myShip->ccoords_x."/".$myShip->ccoords_y."</td>
			<td width=50 align=Center class=tdmainobg><b>".$c2."</b></td>
		</tr>
		<tr>
			<td width=50 align=Center class=tdmainobg>".$a3."</td>
			<td width=50 align=Center class=tdmainobg><b>".$b3."</b></td>
			<td width=50 align=Center class=tdmainobg>".$c3."</td>
		</tr>
		</table>
		</td>
		<td valign=top>
		<table bgcolor=#262323>
		<tr>
			<td class=tdmain align=center><strong>Autopilot</strong></td>
		</tr><form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=action value=ap>
		<input type=hidden name=id value=".$id.">
		<tr>
			<td class=tdmainobg align=center>
			<table cellpadding=1 cellspacing=1>
			<tr>
				<td></td>
				<td align=center><input type=submit value=hoch name=way class=button".$apa."></td>
				<td></td>
			</tr>
			<tr>
				<td><input type=submit value=links name=way class=button".$apa."></td>
				<td align=center><input type=text size=3 name=fields class=text maxlength=1 value=2".$apa."></td>
				<td><input type=submit value=rechts name=way class=button".$apa."></td>
			</tr>
			<tr>
				<td></td>
				<td align=center><input type=submit value=runter name=way class=button".$apa."></td>
				<td></td>
			</tr>
			</table>
			</td>
		</tr></form>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>";
	}
	echo "</table></td><td valign=top>";
	if ($myShip->calertlevel == 1) $gruen = " SELECTED";
	elseif ($myShip->calertlevel == 2) $gelb = " SELECTED";
	elseif ($myShip->calertlevel == 3) $rot = " SELECTED";
	if ($myShip->cstrb_mode == 1)
	{
		$phaser = " SELECTED";
		$strbpic = "<img src=".$grafik."/buttons/phaser.gif>";
	}
	elseif ($myShip->cstrb_mode == 2)
	{
		$torpedo = " SELECTED";
		$strbpic = "<img src=".$grafik."/buttons/torp.gif>";
	}
	echo "<table width=350 bgcolor=#262323>";
	if ($myShip->cclass[probe] != 1) echo "<tr>
		<td class=tdmain><img src=".$grafik."/buttons/gefecht.gif> <strong>Gefechtskontrolle</strong></td>
		</tr><form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=action value=alertlevel>
		<input type=hidden name=id value=".$id.">
		<tr>
			<td class=tdmainobg><img src=".$grafik."/buttons/alert".$myShip->calertlevel.".gif> Alarmstufe <select name=alertlevel>
			<option value=1".$gruen.">Grün</option>
			<option value=2".$gelb.">Gelb</option>
			<option value=3".$rot.">Rot</option>
			</select> <input type=submit value='Ändern' class=button></td>
		</tr></form>
		<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=action value=firemode>
		<input type=hidden name=id value=".$id.">
		<tr>
			<td class=tdmainobg>".$strbpic." Feuermodus <select name=firemode>
				<option value=1".$phaser.">Phaser</option>
				<option value=2".$torpedo.">Torpedos</option>
			</select> <input type=submit value=Ändern class=button></td>
		</tr></form>";
	if ($myShip->cships_rumps_id != 111)
	{
		echo "<tr><td class=tdmain><strong>Module</strong></td></tr>
		<tr><td class=tdmainobg>";
		$mdd = $myDB->query("SELECT a.goods_id,a.name,b.secretimage FROM stu_ships_modules as a LEFT OUTER JOIN stu_goods as b on a.goods_id = b.id WHERE a.id=".$myShip->creaktormodlvl." OR a.id=".$myShip->cantriebmodlvl." OR a.id=".$myShip->chuellmodlvl." OR a.id=".$myShip->cschildmodlvl." OR a.id=".$myShip->csensormodlvl." OR a.id=".$myShip->cwaffenmodlvl." OR a.id=".$myShip->ccomputermodlvl." OR a.id=".$myShip->cepsmodlvl." ORDER BY type");
		while($md=mysql_fetch_assoc($mdd)) 
		{
			if (($md[secretimage] != "0") && ($md[secretimage] != "")) echo "<img src=http://www.stuniverse.de/gfx/secret/".$md[secretimage].".gif title=\"".$md[name]."\"> ";
			else echo "<img src=".$grafik."/goods/".$md[goods_id].".gif title=\"".$md[name]."\"> ";
		}
		if ($myShip->ctachyon == 1) echo " <img src=".$grafik."/buttons/decloak.gif title='Tachyon Emitter'> ";
		if ($myShip->cepsmod == 1) echo " <img src=".$grafik."/buttons/epsmod.gif title='EPS-Modifikation'> ";
		echo "</td></tr>";
	}
	$defend = $myShip->getdefender($id);
	if ($defend != 0)
	{
		echo '<tr>
			<td class=tdmain><strong>Verteidigung</strong></td>
		</tr><tr><td class=tdmainobg>
		<img src='.$grafik.'/buttons/guard2.gif> '.stripslashes($myShip->getfield("name",$defend[ships_id2])).'
		</td></tr>';
	}
	if ($fleet[ships_id] == $id)
	{
		echo "<tr>
			<td class=tdmain><img src=".$grafik."/buttons/fleet.gif> <strong>Flottenkontrolle</strong></td>
		</tr>
		<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=id value=".$id.">
		<tr>
		<td class=tdmainobg><select name=command class=text>
		<option value=shieldson>Schilde aktivieren</option>
		<option value=shieldsoff>Schilde deaktivieren</option>
		<option value=agreen>Alarmstufe: Grün</option>
		<option value=ayellow>Alarmstufe: Gelb</option>
		<option value=ared>Alarmstufe: Rot</option>
		<option value=bussard>Bussardkollektoren</option>
		<option value=erz>Erzkollektoren</option>
		<option value=replikatoron>Replikatoren an</option>
		<option value=replikatoroff>Replikatoren aus</option>
		<option value=ebatt>Ersatzbatterie entladen</option>
		<option value=shload>Schilde laden</option>
		<option value=ksson>Kurzstreckensensoren an</option>
		<option value=kssoff>Kurzstreckensensoren aus</option>
		<option value=lssoff>Langstreckensensoren aus</option>
		<option value=cloakon>Tarnung an</option>
		<option value=cloakoff>Tarnung aus</option>
		<option value=decloak>Tachyonenscan</option>
		<option value=fmphaser>Feuermodus: Phaser</option>
		<option value=fmtorp>Feuermodus: Torpedos</option>
		</select> <input type=submit value=Ausführen class=button></td>
		</tr></form>";
		$fleetdata = $myFleet->getfleetshipsinfo($myShip->cfleets_id,$id);
		if ($fleetdata != 0)
		{
			echo "<tr><td class=tdmainobg width=350><table width=350>
			<tr>
				<td class=tdmainobg width=120></td>
				<td class=tdmainobg align=center><strong>Zustand</strong></td>
				<td class=tdmainobg align=center><strong>Energie</strong></td>
				<td class=tdmainobg></td>
			</tr>";
			while($fd=mysql_fetch_assoc($fleetdata))
			{
				$fd[schilde_aktiv] == 1 ? $sd = " (<font color=cyan>".$fd[schilde]."</font>)" : $sd = "";
				echo "<tr>
						<td class=tdmainobg><a href=?page=ship&section=showship&id=".$fd[id]." title=\"".stripslashes($fd[rname])."\">".stripslashes($fd[name])."</a></td>
						<td class=tdmainobg align=center>".$fd[huelle].$sd."</td>
						<td class=tdmainobg align=center>".$fd[energie]." (".$fd[batt].")</td>
						<td class=tdmainobg><a href=?page=ship&section=showship&action=chgflagg&id=".$id."&id2=".$fd[id]." onmouseover=cp('flagg".$i."','buttons/fl_flag2') onmouseout=cp('flagg".$i."','buttons/fl_flag1')><img src=".$grafik."/buttons/fl_flag1.gif name=flagg".$i." border=0 title=\"Neues Flaggschiff: ".strip_tags(stripslashes($fd[name]))."\"></a></td>
					</tr>";
				$i++;
			}
			echo "</table></td></tr>";
		}
	}
	if ($fleet[ships_id] != $id && $myShip->cfleets_id == $fleet[id])
	{
		echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1 width=350><tr><td class=tdmain><strong>Flotte: ".stripslashes($fleet[name])."</strong></td></tr>
			<tr><td class=tdmainobg>Flaggschiff: <a href=?page=ship&section=showship&id=".$fleet[ships_id].">".stripslashes($myShip->getfield("name",$fleet[ships_id]))."</a></td></tr>";
	}
	if ($myShip->cships_rumps_id == 88 && $myShip->ccomputermodlvl == 44)
	{
		echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1 width=350>
		<tr><td colspan=2 class=tdmain><img src=".$grafik."/buttons/ascan2.gif title='Active Scan'> <strong>Active Scan</strong></td></tr><tr>";
		$myShip->cactscan == 1 ? print("<td class=tdmainobg><a href=?page=ship&section=showship&id=".$id."&action=deactivate&value=actscan onMouseOver=\"cp('ascan','buttons/ascan1')\" onMouseOut=\"cp('ascan','buttons/ascan2')\"><img src=".$grafik."/buttons/ascan2.gif name=ascan border=0> deaktivieren</a></td><td class=tdmainobg width=45%><a href=?page=ship&section=actscan&id=".$id.">Sensorlogs</a> (".$myShip->getascancount().")</td>") : print("<td class=tdmainobg colspan=2><a href=?page=ship&section=showship&id=".$id."&action=activate&value=actscan onMouseOver=\"cp('ascan','buttons/ascan2')\" onMouseOut=\"cp('ascan','buttons/ascan1')\"><img src=".$grafik."/buttons/ascan1.gif name=ascan border=0> aktivieren</a></td>");
		echo "</tr>";
	}
	if (($myShip->cclass[slots] > 4) && ($myColony->getuserresearch(233,$user) != 0) && ($myShip->ccomputermodlvl != 44) && ($myShip->ccomputermodlvl != 6))
	{
		echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1 width=350><tr><td class=tdmain colspan=2><img src=".$grafik."/buttons/graviton.gif> <strong>Graviton-Scan</strong></td></tr>
		<tr><td class=tdmainobg><a href=main.php?page=ship&section=showship&action=graviton&id=".$id." onMouseOver=\"cp('graviton','buttons/graviton2')\" onMouseOut=\"cp('graviton','buttons/graviton1')\"><img src=".$grafik."/buttons/graviton1.gif name=graviton border=0> Scannen</a></td><td class=tdmainobg width=45%>Chance: ".$myShip->getgravitonchance($id)."</td></tr>";
	}
	echo "</table></td>
	</tr>";
	if ($myShip->cclass[slots] > 0)
	{
		$result = $myShip->getdockedships($id);
		echo "</table><br><table bgcolor=#262323>
		<tr><td class=tdmain colspan=2><img src=".$grafik."/buttons/dock2.gif> <strong>Angedockte Schiffe</strong></td></tr>";
		if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg colspan=2>Keine Schiffe angedockt</td></tr>";
		else
		{
			while($docked=mysql_fetch_assoc($result))
			{
				$i++;
				$docked[huelldam] < 40 ? $umpf = "d/" : $umpf = "";
				if ($docked[secretimage] != "0")
				{
					$docked[user_id] == $user ? $link = "<a href=main.php?page=ship&section=showship&id=".$docked[id]."><img src=http://www.stuniverse.de/gfx/secret/".$umpf.$docked[secretimage].".gif border=0></a>" : $link = "<img src=http://www.stuniverse.de/gfx/secret/".$umpf.$docked[secretimage].".gif>";
				}
				else
				{
					$docked[user_id] == $user ? $link = "<a href=main.php?page=ship&section=showship&id=".$docked[id]."><img src=".$grafik."/ships/".$umpf.$docked[ships_rumps_id].".gif border=0></a>" : $link = "<img src=".$grafik."/ships/".$umpf.$docked[ships_rumps_id].".gif>";
				}
				echo "<tr>
					<td class=tdmainobg>".$link."</td>
					<td class=tdmainobg>".stripslashes($docked[name])."</td>
					<td class=tdmainobg>".stripslashes($docked[user])."</td>
					<td class=tdmainobg><a href=main.php?page=ship&section=showship&action=dedock&id2=".$docked[id]."&id=".$id." onMouseOver=\"cp('dedock".$i."','buttons/x2')\" onMouseOut=\"cp('dedock".$i."','buttons/x1')\"><img src=".$grafik."/buttons/x1.gif name=dedock".$i." border=0></td>
				</tr>";
			}
		}
		echo "</table><br><table bgcolor=#262323><tr>
			<td class=tdmain><strong>Andockregel erstellen</strong></td>
		</tr>
		<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=action value=adddockperm>
		<input type=hidden name=id value=".$id.">
		<tr>
			<td class=tdmainobg class=select>Typ <select name=type>
			<option value=shipid>Schiff</option>
			<option value=allyid>Allianz</option>
			<option value=userid>User</option>
			</select> ID: <input type=text size=5 name=dockid class=text> <select name=perm><option value=1>erlauben</option><option value=2>verweigern</option></select> <input type=submit value=Erstellen class=button></td>
		</tr></form>
		</table><br><table bgcolor=#262323>
		<tr>
			<td class=tdmain colspan=4><strong>Andockregeln</strong></td>
		</tr>";
		$dockper = $myShip->getdockpermissions($id,$user);
		if ($dockper != 0)
		{
			for ($i=0;$i<count($dockper);$i++)
			{
				if ($dockper[$i][ships_rumps_id])
				{
					$dockper[$i][mode] == 2 ? $name = "<font color=Red>".stripslashes(strip_tags($dockper[$i][name]))."</font>" : $name = "<font color=Lime>".stripslashes(strip_tags($dockper[$i][name]))."</font>";
					$dockclass = $myShip->getclassbyid($dockper[$i][ships_rumps_id]);
					$dockclass[secretimage] != "0" ? $dockpic = "<img src=http://www.stuniverse.de/gfx/secret/".$dockclass[secretimage].".gif>" : $dockpic = "<img src=".$grafik."/ships/".$dockper[$i][ships_rumps_id].".gif>";
					echo "<tr><td class=tdmainobg>".$dockpic."</td>
					<td class=tdmainobg>".$name."</td>
					<td class=tdmainobg>".stripslashes($myUser->getfield("user",$dockper[$i][user_id]))."</td>
					<td class=tdmainobg><a href=main.php?page=ship&section=showship&action=deldockper&id2=".$dockper[$i][id]."&id=".$id." onMouseOver=\"cp('dockper".$i."','buttons/x2')\" onMouseOut=\"cp('dockper".$i."','buttons/x1')\"><img src=".$grafik."/buttons/x1.gif name=dockper".$i." border=0></td>
					</tr>";
				}
				elseif ($dockper[$i][username])
				{
					$dockper[$i][mode] == 2 ? $name = "<font color=Red>".strip_Tags($dockper[$i][username])."</font>" : $name = "<font color=Lime>".strip_tags($dockper[$i][username])."</font>";
					echo "<tr><td class=tdmainobg>U</td>
						<td class=tdmainobg colspan=2>".stripslashes($name)."</td>
						<td class=tdmainobg><a href=main.php?page=ship&section=showship&action=deldockper&id2=".$dockper[$i][id]."&id=".$id." onMouseOver=\"cp('dockper".$i."','buttons/x2')\" onMouseOut=\"cp('dockper".$i."','buttons/x1')\"><img src=".$grafik."/buttons/x1.gif name=dockper".$i." border=0></td>
						</tr>";
				}
				elseif ($dockper[$i][allyname])
				{
					$dockper[$i][mode] == 2 ? $name = "<font color=Red>".strip_tags($dockper[$i][allyname])."</font>" : $name = "<font color=Lime>".strip_tags($dockper[$i][allyname])."</font>";
					echo "<tr><td class=tdmainobg>A</td>
					<td class=tdmainobg colspan=2>".stripslashes($name)."</td>
					<td class=tdmainobg><a href=main.php?page=ship&section=showship&action=deldockper&id2=".$dockper[$i][id]."&id=".$id." onMouseOver=\"cp('dockper".$i."','buttons/x2')\" onMouseOut=\"cp('dockper".$i."','buttons/x1')\"><img src=".$grafik."/buttons/x1.gif name=dockper".$i." border=0></td>
				</tr>";
				}
			}
		}
	}
	echo "</table>";
	if ($myUser->ulevel < 2)
	{
		if (!$search_m && $myShip->cclass[id] == 1) echo "<br><table bgcolor=#262323><tr><td class=tdmain><a href=main.php?page=ship&section=showship&id=".$id."&search_m=1><strong>Freien Klasse-M Planet suchen</strong></a></td></tr></table>";
		elseif ($search_m && $myShip->cclass[id] == 1)
		{
			echo "<br><table bgcolor=#262323>
			<tr><td class=tdmain><strong>Liste freier Klasse-M Planeten</strong></td></tr>";
			$pr = $myColony->getclassm($myShip->ccoords_x,$myShip->ccoords_y,$myShip->cwese);
			if (mysql_num_rows($pr) == 0) echo "<tr><td class=tdmainobg>Keine freien Planeten im Umkreis von 20 Sektoren gefunden</td></tr>";
			else
			{
				while ($planets=mysql_fetch_assoc($pr))
				{
					$myShip->ccoords_x > $planets[coords_x] ? $entf += $myShip->ccoords_x - $planets[coords_x] : $entf += $planets[coords_x] - $myShip->ccoords_x;
					$myShip->ccoords_y > $planets[coords_y] ? $entf += $myShip->ccoords_y - $planets[coords_y] : $entf += $planets[coords_y] - $myShip->ccoords_y;
					echo "<tr><td class=tdmainobg>".$planets[coords_x]."/".$planets[coords_y]." - Entfernung: ".$entf." Sektoren</td></tr>";
					unset($entf);
				}
			}
			echo "</table>";
		}
	}
	$colinfo = $myColony->getcolonybysektor($myShip->ccoords_x,$myShip->ccoords_y,$myShip->cwese);
	if ($colinfo != 0)
	{
		if ($colinfo[schilde_aktiv] == 1) $add = "s";
		$colinfo[user_id] == $user ? $collink = "<a href=main.php?page=colony&section=showcolony&id=".$colinfo[id]."><img src=".$grafik."/planets/".$colinfo[colonies_classes_id].$add.".gif border=0></a>" : $collink = "<img src=".$grafik."/planets/".$colinfo[colonies_classes_id].$add.".gif>";
		echo "<br><table bgcolor=#262323>
			<tr>
			<td class=tdmainobg>".$collink."</td>
			<td class=tdmainobg>".stripslashes($colinfo[name])." (".$colinfo[id].")</td>
			<td class=tdmainobg>".stripslashes($colinfo[user])." (".$colinfo[user_id].")</td>
			<td class=tdmainobg><a href=main.php?page=ship&section=scan&mode=col&id2=".$colinfo[id]."&id=".$id." onMouseOver=\"cp('scanco','buttons/lupe2')\" onMouseOut=\"cp('scanco','buttons/lupe1')\"><img src=".$grafik."/buttons/lupe1.gif name=scanco border=0 title='Kolonie scannen'></a>";
			if ($myShip->cclass[probe] != 1) echo " <a href=main.php?page=ship&section=etransfer&id2=".$colinfo[id]."&mode=col&id=".$id." onMouseOver=\"cp('cole','buttons/e_trans2')\" onMouseOut=\"cp('cole','buttons/e_trans1')\"><img src=".$grafik."/buttons/e_trans1.gif name=cole border=0 title='Energietransfer'></a>
			<a href=main.php?page=ship&section=transfer&way=to&mode=col&id2=".$colinfo[id]."&id=".$id." onMouseOver=\"cp('colbto','buttons/b_to2')\" onMouseOut=\"cp('colbto','buttons/b_to1')\"><img src=".$grafik."/buttons/b_to1.gif name=colbto border=0 title='Beamen zur Kolonie'></a>&nbsp;<a href=main.php?page=ship&section=transfer&way=from&mode=col&id2=".$colinfo[id]."&id=".$id." onMouseOver=document.colbfrom.src='".$grafik."/buttons/b_from2.gif' onMouseOut=document.colbfrom.src='".$grafik."/buttons/b_from1.gif'><img src=".$grafik."/buttons/b_from1.gif name=colbfrom border=0 title='Beamen von Kolonie'></a>";
			echo " <a href=main.php?page=comm&section=writepm&recipient=".$colinfo[user_id]." onMouseOver=\"cp('msgco','buttons/msg2')\" onMouseOut=\"cp('msgco','buttons/msg1')\"><img src=".$grafik."/buttons/msg1.gif name=msgco border=0 title='Private Nachricht schreiben'></a></td>
			</tr></table>";
	}
	$stationinfo = $myShip->getstationbysektor($myShip->ccoords_x,$myShip->ccoords_y,$myShip->cwese);
	if ($stationinfo != 0 && $stationinfo[id] != $id && $myShip->cclass[probe] != 1)
	{
		$myShip->cdock == $stationinfo[id] ? $dockrow = "<a href=main.php?page=ship&section=showship&action=undock&id2=".$stationinfo[id]."&id=".$id." onMouseOver=\"cp('dock','buttons/dock1')\" onMouseOut=\"cp('dock','buttons/dock2')\"><img src=".$grafik."/buttons/dock2.gif border=0 name=dock title='Abdocken'>" : $dockrow = "<a href=main.php?page=ship&section=showship&action=dock&id2=".$stationinfo[id]."&id=".$id." onMouseOver=document.dock.src='".$grafik."/buttons/dock2.gif' onMouseOut=document.dock.src='".$grafik."/buttons/dock1.gif'><img src=".$grafik."/buttons/dock1.gif border=0 name=dock title='Andocken'>";
		if ($stationinfo[huelldam] < 40 && $stationinfo[ships_rumps_id] != 111) $smpf = "d/";
		if ($stationinfo[secretimage] != "0")
		{
			if ($stationinfo[user_id] == $user) $statlink = "<a href=main.php?page=ship&section=showship&id=".$stationinfo[id]."><img src=http://www.stuniverse.de/gfx/secret/".$smpf.$stationinfo[secretimage].".gif border=0></a>";
			else $statlink = "<img src=http://www.stuniverse.de/gfx/secret/".$smpf.$stationinfo[secretimage].".gif>";
		}
		else
		{
			if ($stationinfo[user_id] == $user) $statlink = "<a href=main.php?page=ship&section=showship&id=".$stationinfo[id]."><img src=".$grafik."/ships/".$smpf.$stationinfo[ships_rumps_id].".gif border=0></a>";
			else $statlink = "<img src=".$grafik."/ships/".$smpf.$stationinfo[ships_rumps_id].".gif>";
		}
		echo "<br><table bgcolor=#262323>
			<tr>
			<td class=tdmainobg>".$statlink."</td>
			<td class=tdmainobg>".$stationinfo[name]."</td>
			<td class=tdmainobg>".$dockrow."</td>
			<td class=tdmainobg><a href=main.php?page=ship&section=etransfer&id2=".$stationinfo[id]."&id=".$id." onMouseOver=\"cp('state','buttons/e_trans2')\" onMouseOut=\"cp('state','buttons/e_trans1')\"><img src=".$grafik."/buttons/e_trans1.gif name=state border=0></a></td>
			<td class=tdmainobg><a href=main.php?page=ship&section=transfer&way=to&id2=".$stationinfo[id]."&id=".$id." onMouseOver=\"cp('stationbto','buttons/b_to2')\" onMouseOut=\"cp('stationbto','buttons/b_to1')\"><img src=".$grafik."/buttons/b_to1.gif name=stationbto border=0></a></td>
			<td class=tdmainobg><a href=main.php?page=ship&section=transfer&way=from&id2=".$stationinfo[id]."&id=".$id." onMouseOver=\"cp('stationbfrom','buttons/b_from2')\" onMouseOut=\"cp('stationbfrom','buttons/b_from1')\"><img src=".$grafik."/buttons/b_from1.gif name=stationbfrom border=0></a></td>";
		if (($stationinfo[ships_rumps_id] == 87 || $stationinfo[ships_rumps_id] == 100) && $stationinfo[user_id] == 14)
		{
			echo "<td class=tdmainobg><a href=main.php?page=ship&section=fergtrade&id2=".$stationinfo[id]."&id=".$id." onMouseOver=\"cp('fergtrade','buttons/fergtrade2')\" onMouseOut=\"cp('fergtrade','buttons/fergtrade1')\"><img src=".$grafik."/buttons/fergtrade1.gif name=fergtrade border=0 title='Handel'></a></td>
			  <td class=tdmainobg><a href=main.php?page=ship&section=bar&pid=".$stationinfo[id]."&id=".$id." onMouseOver=\"cp('bar','buttons/bar2')\" onMouseOut=\"cp('bar','buttons/bar1')\"><img src=".$grafik."/buttons/bar1.gif name=bar border=0 title='Bar'></a></td>
			  <td class=tdmainobg><a href=main.php?page=ship&section=techtrade&id2=".$stationinfo[id]."&id=".$id." onMouseOver=\"cp('techtrade','buttons/forsch2')\" onMouseOut=\"cp('techtrade','buttons/forsch1')\"><img src=".$grafik."/buttons/forsch1.gif name=techtrade border=0 title='Datenbörse'></a></td>";
		}
		echo "</tr></table>";
	}
	echo "<br>";
	//------- KURZSTRECKENSENSOREN
	if ($myShip->cships_rumps_id != 111)
	{
		if ($myShip->ckss == 1)
		{
			$torpcount = $myShip->gettorptype($myShip->cid);
			if ($myShip->cships_rumps_id >= 65 && $myShip->cships_rumps_id <= 68) $torpcount = 0;
			if ($torpcount != 0 || ($myShip->cships_rumps_id>=201 && $myShip->cships_rumps_id<=206)) $tdtorp = "<td class=tdmain align=center>*</td>";
			echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1>
				<tr><td class=tdmain><a href=main.php?page=ship&section=showship&action=deactivate&value=kss&id=".$id." onMouseOver=\"cp('kss','buttons/lupe1')\" onMouseOut=\"cp('kss','buttons/lupe2')\"><img src=".$grafik."/buttons/lupe2.gif name=kss border=0 title='Kurzstreckensensoren deaktivieren'></a></td>
				<td align=center class=tdmain>Name</td>
				<td align=center class=tdmain>Siedler</td>
				<td align=center class=tdmain>NCC</td>
				<td class=tdmain align=center width=100>Zustand</td>
				<td class=tdmain align=center>S</td>
				<td class=tdmain align=center>P</td>
				".$tdtorp."
				<td class=tdmain align=center>T</td>
				<td class=tdmain align=center>E</td>
				<td class=tdmain align=center>V</td>
				<td class=tdmain>Beamen</td>
				<td class=tdmain>&nbsp;</td></tr>";
			if ($myShip->cstype != 0)
			{
				if ($myShip->cstype == 1)
				{
					echo "<tr><td class=tdmainobg><img src=".$grafik."/storm.gif></td>
					<td class=tdmainobg>Ionensturm</td>";
				}
				elseif ($myShip->cstype == 2)
				{
					echo "<tr><td class=tdmainobg><img src=".$grafik."/stormp.gif></td>
					<td class=tdmainobg>Plasmasturm</td>";
				}
				elseif ($myShip->cstype == 3)
				{
					echo "<tr><td class=tdmainobg><img src=".$grafik."/stormn.gif></td>
					<td class=tdmainobg>Neutronische Wellenfront</td>";
				}
				elseif ($myShip->cstype == 4)
				{
					echo "<tr><td class=tdmainobg><img src=".$grafik."/web.gif></td>
					<td class=tdmainobg>Energienetz</td>";
				}
				elseif ($myShip->cstype == 5)
				{
					echo "<tr><td class=tdmainobg><img src=".$grafik."/komet.gif></td>
					<td class=tdmainobg>Komet</td>";
				}
				echo "<td class=tdmainobg>-</td>
				<td align=center class=tdmainobg>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td></tr>";
			}
			/*echo "<tr><td class=tdmainobg><img src=gfx/gesch.gif></td>
				<td class=tdmainobg>Ein frohes Weihnachtsfest wünscht euch euer STU-Team</td>
				<td class=tdmainobg>Weihnachtsmann <b>NPC</b></td>
				<td align=center class=tdmainobg>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td>
				<td class=tdmainobg align=center>-</td></tr>";*/
			$slist = $myMap->getfieldships($myShip->ccoords_x,$myShip->ccoords_y,$id,$myShip->cwese);
			while($data=mysql_fetch_assoc($slist))
			{
				$i++;
				$userdata = $myUser->getUserById($data[user_id]);
				$userdata[status] == 9 ? $showuser = stripslashes($userdata[user])." <b>NPC</b>" : $showuser = stripslashes($userdata[user]).($userdata[vac] == 1 ? " <font color=yellow>*</font>" : "")." (".$userdata[id].")";
				$ally1 = $userdata[allys_id];
				$ally2 = $myUser->ually;
				$dstatus = $myAlly->checkbez($ally1,$ally2);
				if ($data[fleets_id] != 0 && $data[fleets_id] != $svfid && ($data[cloak] == 0 || ($data[cloak] == 1 && ($data[user_id] == $user || ($ally1>0 && ($ally1 == $ally2) || $dstatus == 4)))))
				{
					echo "<tr><td colspan=13 class=tdmainobg>Flotte: ".($data[user_id] == $user ? "<a href=?page=ship&section=showship&id=".$data[fship].">".stripslashes($data[flname])."</a>" : stripslashes($data[flname]))."</td></tr>";
					$svfid = $data[fleets_id];
				}
				if ($data[fleets_id] == 0 && $svfid != 0)
				{
					echo "<tr><td colspan=13 class=tdmainobg>Einzelschiffe</td></tr>";
					$svfid = 0;
				}
				$decloak = $myShip->checkdecloak($data[id],$user);
				if ($data[cloak] == 1 && ($decloak == 0 && $data[user_id] != $user && (($ally1 != $ally2) || ($ally1 == 0 && $ally2 == 0)) && $dstatus != 4))
				{
					$cloaked++;
					continue;
				}
				if ($torpcount != 0) $tdtorp = "<td class=tdmainobg><a href=main.php?page=ship&section=showship&action=torp&type=".$torpcount."&id2=".$data[id]."&id=".$id." onMouseOver=\"cp('t_torp".$i."','buttons/t_torp".$torpcount."2')\" onMouseOut=\"cp('t_torp".$i."','buttons/t_torp".$torpcount."1')\"><img src=".$grafik."/buttons/t_torp".$torpcount."1.gif name=t_torp".$i." border=0 title='Torpedo abfeuern'></a></td>";
				if (($myShip->cships_rumps_id >= 201) && ($myShip->cships_rumps_id <= 207)) $tdtorp = "<td class=tdmainobg><a href=main.php?page=ship&section=shiprepair&targetid=".$data[id]."&id=".$id." onMouseOver=\"cp('t_torp".$i."','buttons/rep2')\" onMouseOut=\"cp('t_torp".$i."','buttons/rep1')\"><img src=".$grafik."/buttons/rep1.gif name=t_torp".$i." border=0 title='Schiff reparieren'></a></td>";
				if ($data[traktormode] == 1) $traktorncc = ">";
				elseif ($data[traktormode] == 2) $traktorncc = "<";
				$data[dock] > 0 ? $dockingncc = "+" : $dockingncc = "";
				$traktor == 0 ? $trak_act = "<a href=main.php?page=ship&section=showship&action=traktor&id=".$id."&id2=".$data[id]." onmouseover=\"cp('traktor".$i."','buttons/trak2')\" onmouseout=\"cp('traktor".$i."','buttons/trak1')\"><img src=".$grafik."/buttons/trak1.gif name=traktor".$i." border=0 title='Traktorstrahl aktivieren'></a>" : $trak_act = "<img src=".$grafik."/buttons/x1.gif>";
				$data[huelldam] < 40 && $data[trumfield] == 0 && $data[ships_rumps_id] != 111 && $data[ships_rumps_id] != 154 ? $mpf = "d/" : $mpf = "";
				if ($data[secretimage] != "0")
				{
					$data[user_id] == $user ? $td = "<td class=tdmainobg><a href=main.php?page=ship&section=showship&id=".$data[id]." border=0><img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif title=\"".stripslashes($data[classname])."\" border=0></a></td>" : $td = "<td class=tdmainobg><img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif title=\"".$data[classname]."\"></td>";
					if ($data[ships_rumps_id] == 3 && $data[trumoldrump] != 3)
					{
						$trumrump = $myShip->getclassbyid($data[trumoldrump]);
						$td = "<td class=tdmainobg><img src=http://www.stuniverse.de/gfx/secret/t/".$data[secretimage].".gif title=\"".stripslashes($data[classname])." (".$trumrump[name].")\"></td>";
					}
				}
				else
				{
					$data[user_id] == $user ? $td = "<td class=tdmainobg><a href=main.php?page=ship&section=showship&id=".$data[id]." border=0><img src=".$grafik."/ships/".$mpf.$data[classid].".gif title=\"".stripslashes($data[classname])."\" border=0></a></td>" : $td = "<td class=tdmainobg><img src=".$grafik."/ships/".$mpf.$data[classid].".gif title=\"".$data[classname]."\"></td>";
					if ($data[ships_rumps_id] == 3 && $data[trumoldrump] != 3)
					{
						$trumrump = $myShip->getclassbyid($data[trumoldrump]);
						$td = "<td class=tdmainobg><img src=".$grafik."/ships/t/".$data[trumoldrump].".gif title=\"".stripslashes($data[classname])." (".$trumrump[name].")\"></td>";
					}
				}
				echo "<tr>".$td;
				$data[cloak] == 1 ? print("<td class=tdcloakobg>".$data[name]."</td>") : print("<td class=tdmainobg>".$data[name]."</td>");
				echo "<td class=tdmainobg>".$showuser."</td>";
				if ($data[schilde_aktiv] == 1) $schilde = "(<font color=#00D5D5>".$data[schilde]."</font>)";
				$data[cloak] == 1 && $decloak == 0 ? print("<td class=tdcloakobg align=center>".$dockingncc."".$traktorncc."".$data[id]."</td>") : print("<td class=tdmainobg align=center>".$dockingncc."".$traktorncc."".$data[id]."</td>");
				echo "<td class=tdmainobg align=center>".$data[huelle]."/".$data[maxhuell]." ".$schilde."</td>";
				if ($myShip->cclass[probe] != 1)
				{
					if ($data[cloak] == 0 || ($decloak != 0 && $data[cloak] == 1))
					{
						echo "<td class=tdmainobg><a href=main.php?page=ship&section=scan&id2=".$data[id]."&id=".$id." onMouseOver=\"cp('scan".$i."','buttons/lupe2')\" onMouseOut=\"cp('scan".$i."','buttons/lupe1')\"><img src=".$grafik."/buttons/lupe1.gif name=scan".$i." border=0 title='Schiff scannen'></a></td>
						  	<td class=tdmainobg><a href=main.php?page=ship&section=showship&action=phaser&id=".$id."&id2=".$data[id]." onMouseOver=\"cp('phaser".$i."','buttons/phaser2')\" onMouseOut=\"cp('phaser".$i."','buttons/phaser1')\"><img src=".$grafik."/buttons/phaser1.gif name=phaser".$i." border=0 title='Phaser/Disruptor abfeuern'></a></td>
						  	".$tdtorp."
						  	<td class=tdmainobg>".$trak_act."</td>
						  	<td class=tdmainobg><a href=main.php?page=ship&section=etransfer&id2=".$data[id]."&id=".$id." onMouseOver=\"cp('pic".$i."','buttons/e_trans2')\" onMouseOut=\"cp('pic".$i."','buttons/e_trans1')\"><img src=".$grafik."/buttons/e_trans1.gif name=pic".$i." border=0 title='Energietransfer'></a></td>";
						$defend[ships_id2] == $data[id] ? print("<td class=tdmainobg><a href=main.php?page=ship&section=showship&action=deletedefender&id2=".$defend[id]."&id=".$id." onMouseOver=\"cp('guard".$i."','buttons/x1')\" onMouseOut=\"cp('guard".$i."','buttons/x2')\"><img src=".$grafik."/buttons/x2.gif name=guard".$i." border=0 title='Verteidigung aufheben'></a></td>") : print("<td class=tdmainobg><a href=main.php?page=ship&section=showship&action=defendship&id2=".$data[id]."&id=".$id." onMouseOver=\"cp('guard".$i."','buttons/guard2')\" onMouseOut=\"cp('guard".$i."','buttons/guard1')\"><img src=".$grafik."/buttons/guard1.gif name=guard".$i." border=0 title='Schiff verteidigen'></a></td>");
						echo "<td class=tdmainobg align=center><a href=main.php?page=ship&section=transfer&way=to&id2=".$data[id]."&id=".$id." onMouseOver=\"cp('to".$i."','buttons/b_to2')\" onMouseOut=\"cp('to".$i."','buttons/b_to1')\"><img src=".$grafik."/buttons/b_to1.gif name=to".$i." border=0 title='Beamen zu Schiff'></a>&nbsp;<a href=main.php?page=ship&section=transfer&way=from&id2=".$data[id]."&id=".$id." onMouseOver=\"cp('from".$i."','buttons/b_from2')\" onMouseOut=\"cp('from".$i."','buttons/b_from1')\"><img src=".$grafik."/buttons/b_from1.gif name=from".$i." border=0 title='Beamen von Schiff'></a></td>";
					}
					else
					{
						$torpcount == 0 ? $span = 6 : $span = 7;
						echo "<td class=tdcloakobg align=center colspan=".$span.">-</td>";
					}
				}
				else echo "<td class=tdmainobg><a href=main.php?page=ship&section=scan&id2=".$data[id]."&id=".$id." onMouseOver=\"cp('scan".$i."','buttons/lupe2')\" onMouseOut=\"cp('scan".$i."','buttons/lupe1')\"><img src=".$grafik."/buttons/lupe1.gif name=scan".$i." border=0 title='Schiff scannen'></a></td><td class=tdcloakobg align=center colspan=5>-</td>";
				echo "<td class=tdmainobg><a href=main.php?page=comm&section=writepm&recipient=".$data[user_id]." onMouseOver=\"cp('msg".$i."','buttons/msg2')\" onMouseOut=\"cp('msg".$i."','buttons/msg1')\"><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0 title='Private Nachricht senden'></a></td></tr>";
				unset($traktorncc);
				unset($schilde);
			}
			if ($cloaked > 0) echo "<tr><td class=tdmain colspan=12>Es befinden sich nicht scanbare Objekte in diesem Sektor</td></tr>";
		}
		else echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><a href=main.php?page=ship&section=showship&action=activate&value=kss&id=".$id." onMouseOver=\"cp('kss','buttons/lupe2')\" onMouseOut=\"cp('kss','buttons/lupe1')\"><img src=".$grafik."/buttons/lupe1.gif name=kss border=0 title='Kurzstreckensensoren aktivieren'> Kurzstreckensensoren aktivieren</a></td></tr>";
		echo "</table><br>";
	}
	if ($myShip->cships_rumps_id != 185 && $myShip->cships_rumps_id != 111)
	{
		if ($myShip->clss == 1) 
		{
			if ($myShip->cwese == 1)
			{
				$maxx = $mapfields[max_x];
				$maxy = $mapfields[max_y];
			}
			elseif ($myShip->cwese == 2)
			{
				$maxx = $mapfields2[max_x];
				$maxy = $mapfields2[max_y];
			}
			elseif ($myShip->cwese == 3)
			{
				$maxx = $mapfields3[max_x];
				$maxy = $mapfields3[max_y];
			}
			$lss_range = $myShip->cmaxlss;
			$x1 = $myShip->ccoords_x-$lss_range;
			$x2 = $myShip->ccoords_x+$lss_range;
			$y1 = $myShip->ccoords_y-$lss_range;
			$y2 = $myShip->ccoords_y+$lss_range;
			if (($x1<1) && ($x2>$maxx))
			{
				$x1=1;
				$x2=$maxx;
			}
			elseif ($x1<1)
			{
				$x1=1;
			}
			elseif ($x2>$maxx)
			{
				$x2=$maxx;
			}
			if (($y1<1) && ($y2>$maxy))
			{
				$y1=1;
				$y2=$maxy;
			}
			elseif ($y1<1)
			{
				$y1=1;
			}
			elseif ($y2>$maxy)
			{
				$y2=$maxy;
			}
			echo "<table border=1 bordercolor=#080807 cellspacing=0 cellpadding=0>";
			while($y1<=$y2)
			{
				$res = $myDB->query("SELECT a.coords_x,a.coords_y,a.type,a.race,b.sperrung FROM stu_map_fields as a LEFT JOIN stu_colonies as b USING(coords_x,coords_y,wese) WHERE a.wese=".$myShip->cwese." AND a.coords_x>=".$x1." AND a.coords_x<=".$x2." AND a.coords_y=".$y1);
				if (!$coordxrow)
				{
					echo "<tr><td class=tdmain width=30 height=30 align=center><a href=main.php?page=ship&section=showship&action=deactivate&value=lss&id=".$id." onMouseOver=\"cp('lss','buttons/lupe1')\" onMouseOut=\"cp('lss','buttons/lupe2')\"><img src=".$grafik."/buttons/lupe2.gif name=lss border=0 title='Langstreckensensoren deaktivieren'></a></td>";
					for($j=$x1;$j<=$x2;$j++) if ($y1>0) echo "<td class=tdmain width=30 height=30 align=center>".$j."</td>";
				}
				if ($y1>0)
				{
					$coordxrow = 1;
					echo "</tr><tr><td class=tdmain width=30 height=30 align=center>".$y1."</td>";
				}
				$j=0;
				while($data=mysql_fetch_assoc($res))
				{
					$fielddata = $myMap->getshipcount($x1+$j,$y1,$myShip->cwese);
					if ($fielddata != 0)
					{
						$myShip->cclass[probe] != 1 ? $fieldinf = "<a href=main.php?page=ship&section=lrsscan&x=".($x1+$j)."&y=".$y1."&id=".$id."><b><font size=-1 color=#FFFFFF>".$fielddata."</font></b></a>" : $fieldinf = "<b><font color=#ffffff size=-1>".$fielddata."</font></b>";
					}
					else
					{
						$cloakdata = $myMap->getcloakedfieldinfo($x1+$j,$y1,$myShip->cwese);
						$cloakdata > 0 ? $fieldinf = "<strong><font size=-1 color=#FFFFFF>?</font></strong>" : $fieldinf = "&nbsp;";
					}
					if ($data[type] == 15 && $fielddata != 0) $fieldinf = "<strong><font size=-1 color=#FFFFFF>X</font></strong>";
					if ($data[type] == 31 && $fielddata != 0) $fieldinf = "<strong><font size=-1 color=#FFFFFF>X</font></strong>";
					if ($x1+$j > 0)
					{
						if ($data[sperrung] == 1) $border = "bordercolor=Red style='border: 1px solid Red'";
						elseif ($data[race] == 11) $border = "bordercolor=#417B40 style='border: 1px solid #417B40'";
						elseif ($data[race] == 15) $border = "bordercolor=#424A4A style='border: 1px solid #424A4A'";
						elseif ($data[race] == 13) $border = "bordercolor=#DDDD00 style='border: 1px solid #DDDD00'";
						elseif ($data[race] == 16) $border = "bordercolor=#D61FC4 style='border: 1px solid #D61FC4'";
						elseif ($data[race] == 10) $border = "bordercolor=#0088FF style='border: 1px solid #0088FF'";
						elseif ($data[race] == 22) $border = "bordercolor=#BB60BB style='border: 1px solid #BB60BB'";
						elseif ($data[race] == 24) $border = "bordercolor=#22DD88 style='border: 1px solid #22DD88'";
						elseif ($data[race] == 27) $border = "bordercolor=#B54A29 style='border: 1px solid #B54A29'";
						elseif ($data[race] == 98) $border = "bordercolor=#503030 style='border: 1px solid #503030'";
						elseif ($data[race] == 99) $border = "bordercolor=#AEAEAE style='border: 1px solid #AEAEAE'";
						else $border = "";
						if ((($x1+$j) == $myShip->ccoords_x) && ($y1 == $myShip->ccoords_y) && (($border == "") || ($data[race] == 15))) $border = "bordercolor=#141412 style='border: 1px solid #7f7f7f'";
						if ($border == "") $border = "style='border: 1px solid #000000'";
						if ($myShip->csensormodlvl == 51 || $myShip->csensormodlvl == 94)
						{
							$iocheck = $myMap->getMapSectorSpecialType($x1+$j,$y1,$myShip->cwese);
							if ($iocheck[type] == 1) $fieldinf = $fieldinf."<strong><font color=#3535FF>!</font></strong>";
						}
						if ($myMap->checksenjammer($x1+$j,$y1,$myShip->cwese) > 0) $fieldinf = "&nbsp;";
						echo "<td width=30 height=30 border=1 ".$border." background=".$grafik."/map/".$data[type].".gif><table align=center width=30 height=30><tr><td align=center>".$fieldinf."</td></tr></table></td>";
					}
					$j++;
				}
				echo "</tr>";
				$y1++;
			}
		}
		else echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><a href=main.php?page=ship&section=showship&action=activate&value=lss&id=".$id."  onMouseOver=\"cp('lss','buttons/lupe2')\" onMouseOut=\"cp('lss','buttons/lupe1')\"><img src=".$grafik."/buttons/lupe1.gif name=lss border=0 title='Langstreckensensoren aktivieren'> Langstreckensensoren aktivieren</a></td></tr>";
	}
	elseif ($myShip->cships_rumps_id == 185)
	{
		if ($myShip->cwese == 1)
		{
			$maxx = $mapfields[max_x];
			$maxy = $mapfields[max_y];
		}
		elseif ($myShip->cwese == 2)
		{
			$maxx = $mapfields2[max_x];
			$maxy = $mapfields2[max_y];
		}
		elseif ($myShip->cwese == 3)
		{
			$maxx = $mapfields3[max_x];
			$maxy = $mapfields3[max_y];
		}
		$lss_range = 2;
		$x1 = $myShip->ccoords_x-$lss_range;
		$x2 = $myShip->ccoords_x+$lss_range;
		$y1 = $myShip->ccoords_y-$lss_range;
		$y2 = $myShip->ccoords_y+$lss_range;
		if ($x1<1)
		{
			$x1=1;
			$x2=$myShip->ccoords_x+$lss_range;
		}
		if ($x2>$maxx)
		{
			$x1=$maxx-$lss_range-($maxx-$myShip->ccoords_x);
			$x2=$maxx;
		}
		if ($y1<1)
		{
			$y1=1;
			$y2=$myShip->ccoords_y+$lss_range;
		}
		if ($y2>$maxy)
		{
			$y1=$maxy-$lss_range-($maxy-$myShip->ccoords_y);
			$y2=$maxy;
		}
		echo "<table border=1 bordercolor=#080807 cellspacing=0 cellpadding=0>";
		while($y1<=$y2)
		{
			$data = $myMap->getrow($x1,$x2,$y1,$myShip->cwese);
			if (!$coordxrow) echo "<tr><td class=tdmain width=30 height=30 align=center><a href=main.php?page=ship&section=showship&action=deactivate&value=lss&id=".$id." onMouseOver=document.lss.src='".$grafik."/buttons/lupe1.gif' onMouseOut=document.lss.src='".$grafik."/buttons/lupe2.gif'><img src=".$grafik."/buttons/lupe2.gif name=lss border=0 title='Langstreckensensoren deaktivieren'></a></td>";
			for($j=0;$j<=$x2-$x1;$j++) if (!$coordxrow && $y1>0) echo "<td class=tdmain width=30 height=30 align=center>".$data[$j][coords_x]."</td>";
			if ($y1>0)
			{
				$coordxrow = 1;
				echo "</tr><tr><td class=tdmain width=30 height=30 align=center>".$y1."</td>";
			}
			for ($j=0;$j<count($data);$j++)
			{
				$x1+$j == $myShip->ccoords_x && $y1 == $myShip->ccoords_y ? $border = "bordercolor=#141412 style='border: 1px solid #7f7f7f'" : $border = "";
				$iocheck = $specialcheck = $myMap->getMapSectorSpecialType($x1+$j,$y1,$myShip->cwese);
				$iocheck[type] == 1 ? print("<td width=30 height=30 border=1 ".$border." background=".$grafik."/map/ion.gif><table align=center width=30 height=30><tr><td align=center></td></tr></table></td>") : print("<td width=30 height=30 border=1 ".$border." background=".$grafik."/map/1.gif><table align=center width=30 height=30><tr><td align=center></td></tr></table></td>");
			}
			echo "</tr>";
			$y1++;
		}
	}
	$storage = $myShip->getshipstorage($id);
	while($data=mysql_fetch_assoc($storage))
	{
		if ($data[secretimage] != "0") $ladungpic = "<img src=http://www.stuniverse.de/gfx/secret/".$data[secretimage].".gif title='".$data[name]."'>";
		else $ladungpic = "<img src=".$grafik."/goods/".$data[goods_id].".gif title='".$data[name]."'>";
		$ladung .= "<tr>
			<td width=100>".$ladungpic."</td>
			<td class=tdmain>".$data['count']."</td>
			</tr>";
		$insgstor += $data['count'];
	}
	if (!$insgstor) $insgstor = 0;
	echo "</table><br>";
	if ($myShip->cclass[probe] != 1) echo "<table bgcolor=#262323 width=400>
	<tr>
		<td valign=top>
		<table bgcolor=#262323 width=150>
		<tr>
			<td class=tdmain><img src=".$grafik."/buttons/lager.gif title='Lagerraum'> ".$insgstor."/".$myShip->cclass[storage]." (".@round((100/$myShip->cclass[storage])*$insgstor,2)."%)</td>
		</tr>
		<tr>
			<td class=tdmainobg align=Center valign=middle>
			<table width=40>
			".$ladung."
			</table>
			</td>
		</tr>
		</table>
	</td>
	<td valign=top class=tdmainobg>
		<table bgcolor=#262323 width=260 cellpadding=1 cellspacing=1 border=0>
		<tr>
			<form action=main.php method=post>
			<input type=hidden name=page value=ship>
			<input type=hidden name=section value=showship>
			<input type=hidden name=id value=".$id.">
			<input type=hidden name=action value=rename>
			<td class=tdmainobg>Name <input class=text type=text size=20 name=new_name value=\"".htmlspecialchars($myShip->cname)."\"> <input class=button type=submit value=ändern></td>
			</form>
		</tr>
		<tr>
			<td class=tdmainobg><a href=main.php?page=ship&section=selfdestruct&id=".$id."><font color=#ff0000>Selbstzerstörung</font></a></td>
		</tr>
		</table>
	</td>
	</tr>
	</table>";
}
elseif (($section == "etransfer") && $id && $id2)
{
	if ($mode == "col")
	{
		$data2 = $myDB->query("SELECT name,colonies_classes_id FROM stu_colonies WHERE coords_x=".$myShip->ccoords_x." AND coords_y=".$myShip->ccoords_y." AND wese=".$myShip->cwese." AND id=".$id2,4);
		if ($data2 == 0) exit;
		$pic = "<img src=".$grafik."/planets/".$data2[colonies_classes_id].".gif  title='".htmlspecialchars(strip_tags($data2[name]))."'>";
	}
	else
	{
		$data2 = $myDB->query("SELECT a.ships_rumps_id,a.name,a.ships_rumps_id,b.trumfield,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myShip->ccoords_x." AND a.coords_y=".$myShip->ccoords_y." AND a.wese=".$myShip->cwese."  AND a.id=".$id2,4);
		if ($data2 == 0) exit;
		$data2[huelldam] < 40 && $data2[trumfield] == 0 && $data2[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
		if ($data2[secretimage] != "0")	$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data2[secretimage].".gif  title='".htmlspecialchars(strip_tags($data2[name]))."'>";
		else $pic = "<img src=".$grafik."/ships/".$mpf.$data2[ships_rumps_id].".gif  title='".htmlspecialchars(strip_tags($data2[name]))."'>";
		$mpf = "";
	}
	if ($data2 == 0) exit;
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Energietransfer</strong></td>
	</tr>
	</table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=showship>
	<input type=hidden name=action value=etransfer>
	<input type=hidden name=mode value=".$mode.">
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=id2 value=".$id2.">
	<tr>
		<td class=tdmainobg colspan=3>Ziel: ".stripslashes($data2[name])."</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center><input type=text size=2 class=text name=count> / ".$myShip->cenergie." <input type=submit class=button value=Transfer> <input type=submit name=count value=max class=button></td>
	</tr></form></table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain colspan=3 align=Center>Modus</td>
	</tr>
	<tr>";
	if ($myShip->cdamaged == 1) $mpf = "d/";
	if ($myShip->csecretimage != "0") $spic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$myShip->csecretimage.".gif title='".htmlspecialchars(strip_tags($myShip->cname))."'>";
	else $spic = "<img src=".$grafik."/ships/".$mpf.$myShip->cships_rumps_id.".gif title='".htmlspecialchars(strip_tags($myShip->cname))."'>";
	echo "<td class=tdmainobg>".$spic."</td>
		<td width=50 align=center class=tdmainobg><img src=".$grafik."/buttons/b_to1.gif></td>
		<td align=right class=tdmainobg>".$pic."</td>
	</tr></table>";
}
elseif (($section == "transfer") && $id && $id2 && $way)
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Beamen</strong></td>
	</tr>
	</table><br>
	<table>";
	if ($way == "to")
	{
		$mode == "col" ? $data2 = $myDB->query("SELECT user_id,name,colonies_classes_id,schilde_aktiv,bev_free+bev_used as bevsum,max_bev,max_storage as storage FROM stu_colonies WHERE coords_x=".$myShip->ccoords_x." AND coords_y=".$myShip->ccoords_y." AND wese=".$myShip->cwese." AND id=".$id2,4) : $data2 = $myDB->query("SELECT a.user_id,a.ships_rumps_id,a.name,a.ships_rumps_id,a.crew as bevsum,b.crew as max_bev,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.trumfield,b.storage,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myShip->ccoords_x." AND a.coords_y=".$myShip->ccoords_y." AND a.wese=".$myShip->cwese." AND a.id=".$id2,4);
		if ($data2[colonies_classes_id] > 0) $class2 = "<img src=".$grafik."/planets/".$data2[colonies_classes_id].".gif title='".htmlspecialchars(strip_tags($data2[name]))."'>";
		else
		{
			$data2[huelldam] < 40 && $data2[trumfield] == 0 && $data2[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
			if ($data2[secretimage] != "0")	$class2 = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data2[secretimage].".gif title='".htmlspecialchars(strip_tags($data2[name]))."'>";
			else 	$class2 = "<img src=".$grafik."/ships/".$mpf.$data2[ships_rumps_id].".gif title='".htmlspecialchars(strip_tags($data2[name]))."'>";
			unset($mpf);
		}
		if ($data2 == 0) exit;
		$storage = $myDB->query("SELECT a.goods_id,a.count,b.name FROM stu_ships_storage as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.ships_id=".$id." ORDER BY b.sort");
		echo "<tr><td valign=top><table bgcolor=#262323 cellspacing=1 cellpadding=1>
		<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=action value=transferto>
		<input type=hidden name=way value=".$way.">
		<input type=hidden name=mode value=".$mode.">
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=id2 value=".$id2.">";
		if ($mode == "col")
		{
			$mode_add = "&mode=col";
			$cr = "Freier Wohnraum auf der Kolonie";
			$ss = $myDB->query("SELECT SUM(count) FROM stu_colonies_storage WHERE colonies_id=".$id2,1);
		}
		else
		{
			$cr = "Freie Crewquartiere auf dem Schiff";
			$ss = $myDB->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$id2,1);
		}
		if ($user == $data2[user_id])echo "<tr>
			<td width=100 class=tdmainobg><img src=".$grafik."/buttons/crew.gif title=Crew> ".$myShip->ccrew." | <img src=".$grafik."/bev_unused_1_".$myUser->urasse.".gif title=\"".$cr."\"> ".($data2[bevsum]>$data2[max_bev] ? "0" : $data2[max_bev]-$data2[bevsum])."</td>
			<td class=tdmainobg><input type=text class=text size=2 name=crew></td>
		</tr>";
		if (mysql_num_rows($storage) == 0) echo "<tr><td class=tdmainobg colspan=2 align=center>Keine Waren vorhanden</td></tr>";
		else
		{
			while ($sd=mysql_fetch_assoc($storage))
			{
				echo "<tr>
				<td width=100 class=tdmainobg><img src=".$grafik."/goods/".$sd[goods_id].".gif title='".$sd[name]."'> ".$sd['count']."</td>
				<td class=tdmainobg><input type=hidden name=good[] value=".$sd[goods_id]."><input class=text type=text size=2 name=beam[]></td>
				</tr>";
			}
		}
		if ($mode == "col" && $data2[schilde_aktiv] == 1 && $data2[user_id] != $user) echo "<tr><td colspan=3 class=tdmainobg>Schildfrequenz: <input type=text size=2 maxlength=2 name=freq1 class=text>,<input type=text size=1 maxlength=1 name=freq2 class=text></td></tr>";
		echo "<tr>
			<td colspan=2 class=tdmain align=Center><input class=button type=submit value=Transport></td>
		</tr></table></form></td>
		<td valign=top>
		<table bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
		<td class=tdmainobg colspan=3>Ziel: ".stripslashes($data2[name])."<br>
		<img src=".$grafik."/buttons/lager.gif title='Lagerplatz'> ".(!$ss ? "0" : $ss)."/".$data2[storage]."</td>
		</tr>
		<tr>
			<td colspan=3 class=tdmain align=Center>Modus</td>
		</tr>
		<tr>";
		if ($myShip->cdamaged == 1) $mpf = "d/";
		if ($myShip->csecretimage != "0") $class1 = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$myShip->csecretimage.".gif title='".htmlspecialchars(strip_tags($myShip->cname))."'>";
		else $class1 = "<img src=".$grafik."/ships/".$mpf.$myShip->cships_rumps_id.".gif title='".htmlspecialchars(strip_tags($myShip->cname))."'>";
		echo "<td class=tdmainobg>".$class1."</td>
			<td width=50 align=center class=tdmainobg><a href=main.php?page=ship&section=transfer&way=from".$mode_add."&id2=".$id2."&id=".$id."><img src=".$grafik."/buttons/b_to2.gif border=0></a></td>
			<td align=right class=tdmainobg>".$class2."</td>
		</tr></table></td></tr></table>";
	}
	elseif ($way == "from")
	{
		if (!is_numeric($id2)) exit;
		if ($mode == "col")
		{
			$storage = $myDB->query("SELECT a.goods_id,a.count,b.name FROM stu_colonies_storage as a LEFT OUTER JOIN stu_goods as b ON a.goods_id=b.id WHERE a.colonies_id=".$id2." ORDER BY sort ASC");
			$data2 = $myDB->query("SELECT user_id,name,colonies_classes_id,bev_free,schilde_aktiv FROM stu_colonies WHERE coords_x=".$myShip->ccoords_x." AND coords_y=".$myShip->ccoords_y." AND wese=".$myShip->cwese." AND id=".$id2,4);
			$class2 = "<img src=".$grafik."/planets/".$data2[colonies_classes_id].".gif title='".htmlspecialchars(strip_tags($data2[name]))."'>";
		}
		else
		{
			$storage = $myDB->query("SELECT a.goods_id,a.count,b.name FROM stu_ships_storage as a LEFT OUTER JOIN stu_goods as b ON a.goods_id=b.id WHERE a.ships_id=".$id2." ORDER BY sort ASC");
			$data2 = $myDB->query("SELECT a.user_id,a.ships_rumps_id,a.name,a.ships_rumps_id,a.crew,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.trumfield,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myShip->ccoords_x." AND a.coords_y=".$myShip->ccoords_y." AND a.wese=".$myShip->cwese." AND a.id=".$id2,4);
			$data2[huelldam] < 40 && $data2[trumfield] == 0 && $data2[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
			if ($data2[secretimage] != "0")	$class2 = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data2[secretimage].".gif title='".htmlspecialchars(strip_tags($data2[name]))."'>";
			else 	$class2 = "<img src=".$grafik."/ships/".$mpf.$data2[ships_rumps_id].".gif title='".htmlspecialchars(strip_tags($data2[name]))."'>";
			unset($mpf);
		}
		if ($data2 == 0) exit;
		echo "<tr><td valign=top><table bgcolor=#262323 cellspacing=1 cellpadding=1>
		<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=action value=transferfrom>
		<input type=hidden name=way value=".$way.">
		<input type=hidden name=mode value=".$mode.">
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=id2 value=".$id2.">";
		if ($mode == "col") $mode_add = "&mode=col";
		if ($user == $data2[user_id])
		{
			echo "<tr>
				<td width=100 class=tdmainobg><img src=".$grafik."/buttons/crew.gif title=Crew> ";
				$mode == "col" ?  print($data2[bev_free]) : print($data2[crew]);
				echo " | <img src=".$grafik."/bev_unused_1_".$myUser->urasse.".gif title='Freie Crewquartiere auf dem Schiff'> ".($myShip->cclass[crew]-$myShip->ccrew)."</td>
				<td class=tdmainobg><input type=text class=text size=2 name=crew></td>
			</tr>";
		}
		if (mysql_num_rows($storage) == 0) echo "<tr><td class=tdmainobg colspan=2 align=center>Keine Waren vorhanden</td></tr>";
		else
		{
			while($s=mysql_fetch_assoc($storage))
			{
			echo "<tr>
				<td width=100 class=tdmainobg><img src=".$grafik."/goods/".$s[goods_id].".gif title='".$s[name]."'> ".$s['count']."</td>
				<td class=tdmainobg><input type=hidden name=good[] value=".$s[goods_id]."><input class=text type=text size=2 name=beam[]></td>
				</tr>";
			}
		}
		if ($myShip->cdamaged == 1) $mpf = "d/";
		if ($myShip->csecretimage != "0") $class1 = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$myShip->csecretimage.".gif title='".htmlspecialchars(strip_tags($myShip->cname))."'>";
		else $class1 = "<img src=".$grafik."/ships/".$mpf.$myShip->cships_rumps_id.".gif title='".htmlspecialchars(strip_tags($myShip->cname))."'>";
		if ($mode == "col" && $data2[schilde_aktiv] == 1 && $data2[user_id] != $user) echo "<tr><td colspan=3 class=tdmainobg>Schildfrequenz: <input type=text size=2 maxlength=2 name=freq1 class=text>,<input type=text size=1 maxlength=1 name=freq2 class=text></td></tr>";
		$ss = $myDB->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$id,1);
		echo "<tr>
			<td colspan=2 class=tdmain align=center><input class=button type=submit value=Transport></td>
		</tr></table></form></td>
		<td valign=top>
		<table bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr><td class=tdmainobg colspan=3>Ziel: ".stripslashes($data2[name])."<br>
		<img src=".$grafik."/buttons/lager.gif title='Lagerplatz'> ".(!$ss ? "0" : $ss)."/".$myShip->cclass[storage]."</td></tr>
		<tr>
			<td colspan=3 class=tdmain align=Center>Modus</td>
		</tr>
		<tr>";
			echo "<td class=tdmainobg>".$class1."</td>
			<td width=50 align=center class=tdmainobg><a href=main.php?page=ship&section=transfer&way=to".$mode_add."&id2=".$id2."&id=".$id."><img src=".$grafik."/buttons/b_from2.gif border=0></a></td>
			<td align=right class=tdmainobg>".$class2."</td>
		</tr></table></td></tr></table>";
	}
}
elseif ($section == "lrsscan")
{
	$lrsdata = $myShip->getlrsfield($x,$y,$id,$myShip->cwese);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>LSS-Scan</strong></td>
	</tr>
	</table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1 width=600>
	<tr>
		<td class=tdmain align=center>Typ</td>
		<td class=tdmain align=center>Zustand</td>
		<td class=tdmain>Energie</td>
		<td class=tdmain align=center>Lebenszeichen</td>
		<td class=tdmain>Waffen</td>
		<td class=tdmain>Schilde</td>
	</tr>";
	if ($lrsdata == -2) echo "<tr><td class=tdmainobg align=center colspan=6>Nicht genügend Energie vorhanden</td></tr>";
	elseif ($lrsdata == -3) echo "<tr><td class=tdmainobg align=center colspan=6>Dieser Sektor ist nicht scanbar</td></tr>";
	elseif ($lrsdata == -1) echo "<tr><td class=tdmainobg align=center colspan=6>Der Sektor befindet sich weiter als 2 Felder vom Schiff entfernt</td></tr>";
	elseif (mysql_num_rows($lrsdata) == 0) echo "<tr><td class=tdmainobg align=center colspan=6>Der Sektor ist leer</td></tr>";
	else
	{
		while ($data=mysql_fetch_assoc($lrsdata))
		{
			$data[id] == 144 ? $lrsdatacrew = "<font color=#98713D>1</font>" : $lrsdatacrew = $data[crew];
			$data[alertlevel] == 1 ? $ws = "-" : $ws = "!";
			$data[schilde_aktiv] ? $schilde = "!" : $schilde = "-";
			if ($data[cloak] == 0 || ( $myUser->ually == $myUser->getfield("allys_id",$data[user_id]) && $myUser->ually != 0))
			{
				echo "<tr>";
				$lrsdata[$i][damaged] == 1 ? $mpf = "d/" : $mpf = "";
				if ($data[secretimage] != "0") $lrspic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif title=\"".stripslashes($data[name])."\">";
				else $lrspic = "<img src=".$grafik."/ships/".$mpf.$data[id].".gif title=\"".stripslashes($data[name])."\">";
				echo "<td class=tdmainobg align=center>".$lrspic."</td>
					<td class=tdmainobg align=center>".$data[huelle]."</td>
					<td class=tdmainobg align=center>"; if ($data[cloak] == 0) echo $data[energie]; echo "</td>
					<td class=tdmainobg align=center>"; if ($data[cloak] == 0) echo $lrsdatacrew; echo "</td>
					<td class=tdmainobg align=center>"; if ($data[cloak] == 0) echo $ws; echo "</td>
					<td class=tdmainobg align=center>"; if ($data[cloak] == 0) echo $schilde; echo "</td>
				</tr>";
			}
			else $cloaked++;
		}
	}
	if ($cloaked) echo "<tr><td class=tdmainobg colspan=6>Es befinden sich nicht scanbare Objekte in diesem Sektor</td></tr>";
	echo "</table>";
}
elseif ($section == "scan")
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Scan</strong></td>
	</tr>
	</table><br>";
	if ($myUser->ulevel < 2)
	{
		echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
		<td class=tdmain>Meldung</td>
		</tr><tr><td class=tdmainobg>Diese funktion steht erst ab Level 2 zur Verfügung</td></tr>
		</table>";
		exit;
	}
	$mode == "col" ? $shipdata2 = $myColony->getcolonybyid($id2) : $shipdata2 = $myShip->getdatabyid($id2);
	if ($shipdata2 == 0) $txt = "Schiff nicht vorhanden";
	if ($myShip->ccoords_x != $shipdata2[coords_x] || $myShip->ccoords_y != $shipdata2[coords_y] || $myShip->cwese != $shipdata2[wese]) $txt = "Beide Schiffe müssen sich im selben Sektor befinden";
	if ($myShip->cenergie < 2 && $myShip->cclass[probe] == 0) $txt = "Zum Scannen werden 2 Energie benötigt";
	if ($myShip->cenergie < 1 && $myShip->cclass[probe] == 1) $txt = "Zum Scannen wird 1 Energie benötigt";
	if ($myShip->ccrew < 1 && $myShip->cclass[probe] == 0) $txt = "Zum Scannen wird mindests 1 Crewmitglied benötigt";
	if ($txt) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg align=center>".$txt."</td></tr></table>";
	else
	{
		if ($myShip->cclass[probe] == 1) { $myShip->lowerfield("energie",1,$id,$user); $myShip->cenergie -= 1; }
		else { $myShip->lowerfield("energie",2,$id,$user); $myShip->cenergie -= 2; }
		if ($shipdata2[user_id] == 15) $pplicon = "<img src=".$grafik."/buttons/ppl_breen.gif title='Breen'> ";
		elseif (($shipdata2[user_id] == 5) || ($shipdata2[user_id] == 16) || ($shipdata2[user_id] == 26)) $pplicon = "<img src=".$grafik."/buttons/ppl_unclear.gif title='Verschiedene'> ";
		elseif ($shipdata2[user_id] == 18) $pplicon = "<img src=".$grafik."/buttons/ppl_bajo.gif title='Bajoraner'> ";
		elseif ($shipdata2[user_id] == 19) $pplicon = "<img src=".$grafik."/buttons/ppl_kessok.gif title='Kessok'> ";
		elseif ($shipdata2[user_id] == 21) $pplicon = "<img src=".$grafik."/buttons/ppl_tama.gif title='Tamarianer'> ";
		elseif ($shipdata2[user_id] == 22) $pplicon = "<img src=".$grafik."/buttons/ppl_thol.gif title='Tholianer'> ";
		elseif ($shipdata2[user_id] == 23) $pplicon = "<img src=".$grafik."/buttons/ppl_sona.gif title='Sona/Ellora/Tarlac'> ";
		elseif ($shipdata2[user_id] == 24) $pplicon = "<img src=".$grafik."/buttons/ppl_verek.gif title='Verekkianer'> ";
		elseif ($shipdata2[user_id] == 27) $pplicon = "<img src=".$grafik."/buttons/ppl_kzin.gif title='Kzinti'> ";
		elseif ($shipdata2[user_id] == 30) $pplicon = "<img src=".$grafik."/buttons/ppl_karemma.gif title='Karemma'> ";
		else
		{
			$udata = $myUser->getUserByID($shipdata2[user_id]);
			if ($udata[rasse] == 1) $pplicon = "<img src=".$grafik."/buttons/ppl_fed.gif title='Föderation'> ";
			elseif ($udata[rasse] == 2) $pplicon = "<img src=".$grafik."/buttons/ppl_rom.gif title='Romulaner'> ";
			elseif ($udata[rasse] == 3) $pplicon = "<img src=".$grafik."/buttons/ppl_klin.gif title='Klingonen'> ";
			elseif ($udata[rasse] == 4) $pplicon = "<img src=".$grafik."/buttons/ppl_card.gif title='Cardassianer'> ";
			elseif ($udata[rasse] == 5) $pplicon = "<img src=".$grafik."/buttons/ppl_ferg.gif title='Ferengi'> ";
		}
		if ($mode == "col")
		{
			$class = $myColony->getclassbyid($shipdata2[colonies_classes_id]);
			if ($action == "attack")
			{
				$result = $myShip->attackcolfield($id,$id2,$type,$field,$freq1.$freq2);
				$myShip->gcs();
			}
			if ($result) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
			echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1>
			<tr>
				<td class=tdmainobg>Typ</td>
				<td class=tdmainobg>".$class[name]."</td>
			</tr>
			<tr>
				<td class=tdmainobg>Energiesignaturen</td>
				<td class=tdmainobg>".$shipdata2[energie]."</td>
			</tr>";
			echo "<tr>
				<td class=tdmainobg>Lebenszeichen</td>
				<td class=tdmainobg>".$pplicon."".($shipdata2[bev_used]+$shipdata2[bev_free])."</td>
				</tr>";
			if ($shipdata2[schilde_aktiv] == 1) echo "<form action=main.php method=post>
			<input type=hidden name=page value=ship>
			<input type=hidden name=section value=scan>
			<input type=hidden name=mode value=col>
			<input type=hidden name=id value=".$id.">
			<input type=hidden name=id2 value=".$id2.">
			<tr>
				<td class=tdmainobg>Schildfrequenz eingeben</td>
				<td class=tdmainobg><input type=text size=2 maxlength=2 name=freq1 class=text value=".$freq1.">,<input type=text size=1 maxlength=1 name=freq2 class=text value=".$freq2."> <input type=submit value=Einstellen class=button></td>
				</tr></form>";
			echo "</table><br>";
			$myShip->cclass[probe] == 0 ? print($myColony->rendercolony($id2,2)) : print($myColony->rendercolony($id2,3));
		}
		else
		{
			$shipdata2[ships_rumps_id] == 144 ? $shipdatacrew = "<font color=#98713D>1</font>" : $shipdatacrew = $shipdata2[crew];
			$shipdata2[kss] == 0 ? $kss = "deaktiviert" : $kss = "aktiviert";
			$shipdata2[lss] == 0 ? $lss = "deaktiviert" : $lss = "aktiviert";
			$shipdata2[alertlevel] == 1 ? $ws = "-" : $ws = "!";
			$shipdata2[damaged] == 1 ? $mpf = "d/" : $mpf = "";
			if ($shipdata2[c][secretimage] != "0") $ship2pic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$shipdata2[c][secretimage].".gif title='".htmlspecialchars(strip_tags(stripslashes($shipdata2[c][name])))."'>";
			else $ship2pic = "<img src=".$grafik."/ships/".$mpf.$shipdata2[c][id].".gif title='".htmlspecialchars(strip_tags(stripslashes($shipdata2[c][name])))."'>";
			echo "<table bgcolor=#262323 width=400>
			<tr>
				<td class=tdmain colspan=2 align=center><strong>Scanergebnis von ".stripslashes($shipdata2[name])." in Sektor ".$shipdata2[coords_x]."/".$shipdata2[coords_y]."</strong></td>
			</tr>
			<tr>
				<td class=tdmainobg>Typ</td>
				<td class=tdmainobg>".$ship2pic."</td>
			</tr>
			<tr>
				<td class=tdmainobg>Zustand</td>
				<td class=tdmainobg>".$shipdata2[huelle]."/".$shipdata2[maxhuell]."</td>
			</tr>
			<tr>
				<td class=tdmainobg>Energiesignaturen</td>
				<td class=tdmainobg>".$shipdata2[energie]."</td>
			</tr>";
			if ($shipdata2[user_id] == 23) 
			{
				$crew1 = ceil($shipdatacrew/5);
				$crew2 = ceil(($shipdatacrew - $crew1)/2);
				$crew3 = $shipdatacrew - $crew1 - $crew2;
				echo "<tr>
					<td class=tdmainobg>Lebenszeichen</td>
					<td class=tdmainobg><img src=".$grafik."/buttons/ppl_sona.gif title='Sona'> ".$crew1." <img src=".$grafik."/buttons/ppl_tarlac.gif title='Tarlac'> ".$crew2." <img src=".$grafik."/buttons/ppl_ellora.gif title='Ellora'> ".$crew3."</td>
				</tr>";
			}
			else 
			{
				echo "<tr>
					<td class=tdmainobg>Lebenszeichen</td>
					<td class=tdmainobg>".$pplicon."".$shipdatacrew."</td>
				</tr>";
			}
			echo "<tr>
				<td class=tdmainobg>KSS</td>
				<td class=tdmainobg>".$kss."</td>
			</tr>
			<tr>
				<td class=tdmainobg>LSS</td>
				<td class=tdmainobg>".$lss."</td>
			</tr>
			<tr>
				<td class=tdmainobg>Alarmstatus</td>
				<td class=tdmainobg>".$ws."</td>
			</tr>";
			if ($shipdata2[c][trumfield] != 1)
			{
				if ($shipdata2[antriebmodlvl] > 0)
				{
					echo "<tr>
						<td class=tdmainobg>Antrieb</td>
						<td class=tdmainobg>".$myShip->checkSystem($shipdata2[id],"impdef")."</td>
					</tr>";
				}
				if ($shipdata2[waffenmodlvl] > 0)
				{
					echo "<tr>
						<td class=tdmainobg>Waffen</td>
						<td class=tdmainobg>".$myShip->checkSystem($shipdata2[id],"waffdef")."</td>
					</tr>";
				}
				if ($shipdata2[c][cloak] == 1)
				{
					echo "<tr>
						<td class=tdmainobg>Tarnung</td>
						<td class=tdmainobg>".$myShip->checkSystem($shipdata2[id],"cloakdef")."</td>
					</tr>";
				}
				if ($shipdata2[reaktormodlvl] > 0)
				{
					echo "<tr>
						<td class=tdmainobg>Warpkern</td>
						<td class=tdmainobg>".$myShip->checkSystem($shipdata2[id],"readef")."</td>
					</tr>";
				}
				echo "<tr>
					<td class=tdmainobg>Kurzstreckensensoren</td>
					<td class=tdmainobg>".$myShip->checkSystem($shipdata2[id],"ksendef")."</td>
				</tr>
				<tr>
					<td class=tdmainobg>Langstreckensensoren</td>
					<td class=tdmainobg>".$myShip->checkSystem($shipdata2[id],"lsendef")."</td>
				</tr>
				<tr>
					<td class=tdmainobg>Schilde</td>
					<td class=tdmainobg>".$myShip->checkSystem($shipdata2[id],"shidef")."</td>
				</tr>";
			}
			echo "</table>";
		}
	}
}
elseif ($section == "selfdestruct")
{
	$code = $myShip->setdestructcode($id,$user);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Selbstzerstörung</strong></td>
	</tr>
	</table><br>
	<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=showship>
	<input type=hidden name=action value=selfdestruct>
	<input type=hidden name=id value=".$id.">
		<td class=tdmainobg>Bitte diesen Code in das Feld eingeben um die Selbstzerstörung zu bestätigen:<br><strong>".$code."</strong><br><input class=text type=text size=6 maxlength=6 name=destructcode> <input class=button type=submit value=Bestätigung></td>
	</tr>
	</form>
	</table>";
}
elseif ($section == "fleetbussard")
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Bussardkollektoren (Flotte)</strong></td>
	</tr>
	</table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=showship>
	<input type=hidden name=command value=bussard>
	<input type=hidden name=sent value=1>
	<input type=hidden name=id value=".$id.">
	<td class=tdmain><input class=text type=text size=2 name=ener_count> <input class=button type=submit value=sammeln> <input type=submit name=ener_count value=max class=button></td>
	</tr>
	</form></table>";
}
elseif ($section == "fleeterz")
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Erzkollektoren (Flotte)</strong></td>
	</tr>
	</table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=showship>
	<input type=hidden name=command value=erz>
	<input type=hidden name=sent value=1>
	<input type=hidden name=id value=".$id.">
	<td class=tdmain><input class=text type=text size=2 name=ener_count> <input class=button type=submit value=sammeln> <input type=submit name=ener_count value=max class=button></td>
	</tr>
	</form></table>";
}
elseif ($section == "fleetebatt")
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Ersatzbatterie (Flotte)</strong></td>
	</tr>
	</table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=command value=ebatt>
		<input type=hidden name=sent value=1>
		<input type=hidden name=id value=".$id.">
		<td class=tdmainobg>Batterie aller Schiffe um <input class=text type=text size=2 name=batt_count> <input class=button type=submit value=entladen> <input type=submit name=batt_count value=max class=button></td>
	</tr>
	</form></table>";
}
elseif ($section == "fleetshload")
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Schilde laden (Flotte)</strong></td>
	</tr>
	</table><br>
	<table bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=showship>
		<input type=hidden name=command value=shload>
		<input type=hidden name=sent value=1>
		<input type=hidden name=id value=".$id.">
		<td class=tdmainobg>Schilde aller Schiffe um <input class=text type=text size=2 name=load> <input class=button type=submit value=laden> <input type=submit name=load value=max class=button></td>
	</tr>
	</form></table>";
}
elseif ($section == "buildstation")
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Stationsbau</strong></td>
	</tr>
	</table><br>
	<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=selectsm>
	<input type=hidden name=id value=".$id.">";
	if ($myShip->getstationbuildoption(48) == 1)
	{
		echo "<tr>
			<td class=tdmainobg><input type=radio name=classid value=48></td>
			<td class=tdmainobg align=center>Vorposten<br><img src=".$grafik."/ships/48.gif></td>
			<td class=tdmainobg><img src=".$grafik."/buttons/e_trans2.gif> 72<br>";
			$cost = $myColony->getshipcostbyid(48);
			for ($i=0;$i<count($cost);$i++) echo $myColony->getgoodpicbyid($cost[$i][goods_id])." ".$cost[$i]['count']."<br>";
			echo "</td>
		</tr>";
	}
	if ($myShip->getstationbuildoption(49) == 1)
	{
		echo "<tr>
			<td class=tdmainobg><input type=radio name=classid value=49></td>
			<td class=tdmainobg align=center>Station<br><img src=".$grafik."/ships/49.gif></td>
			<td class=tdmainobg><img src=".$grafik."/buttons/e_trans2.gif> 120<br>";
			$cost = $myColony->getshipcostbyid(49);
			for ($i=0;$i<count($cost);$i++) echo $myColony->getgoodpicbyid($cost[$i][goods_id])." ".$cost[$i]['count']."<br>";
			echo "</td>
		</tr>";
	}
	if ($myShip->getstationbuildoption(50) == 1)
	{
		echo "<tr>
			<td class=tdmainobg><input type=radio name=classid value=50></td>
			<td class=tdmainobg align=center>Depot<br><img src=".$grafik."/ships/50.gif></td>
			<td class=tdmainobg><img src=".$grafik."/buttons/e_trans2.gif> 120<br>";
			$cost = $myColony->getshipcostbyid(50);
			for ($i=0;$i<count($cost);$i++) echo $myColony->getgoodpicbyid($cost[$i][goods_id])." ".$cost[$i]['count']."<br>";
			echo "</td>
		</tr>";
	}
	if ($myShip->getstationbuildoption(59) == 1)
	{
		echo "<tr>
			<td class=tdmainobg><input type=radio name=classid value=59></td>
			<td class=tdmainobg align=center>Basis<br><img src=".$grafik."/ships/59.gif></td>
			<td class=tdmainobg><img src=".$grafik."/buttons/e_trans2.gif> 180<br>";
			$cost = $myColony->getshipcostbyid(59);
			for ($i=0;$i<count($cost);$i++) echo $myColony->getgoodpicbyid($cost[$i][goods_id])." ".$cost[$i]['count']."<br>";
			echo "</td>
		</tr>";
	}
	if ($myShip->getstationbuildoption(88) == 1)
	{
		echo "<tr>
			<td class=tdmainobg><input type=radio name=classid value=88></td>
			<td class=tdmainobg align=center>Sensorphalanx<br><img src=".$grafik."/ships/88.gif></td>
			<td class=tdmainobg><img src=".$grafik."/buttons/e_trans2.gif> 12<br>";
			$cost = $myColony->getshipcostbyid(88);
			for ($i=0;$i<count($cost);$i++) echo $myColony->getgoodpicbyid($cost[$i][goods_id])." ".$cost[$i]['count']."<br>";
			echo "</td>
		</tr>";
	}
	$udata = $myUser->getuserbyid($user);
	if ($udata[rasse] == 1) $class = 101;
	if ($udata[rasse] == 2) $class = 102;
	if ($udata[rasse] == 3) $class = 103;
	if ($udata[rasse] == 4) $class = 104;
	if ($udata[rasse] == 5) $class = 87;
	if ($myShip->getstationbuildoption($class) == 1)
	{
		echo "<tr>
			<td class=tdmainobg><input type=radio name=classid value=".$class."></td>
			<td class=tdmainobg align=center>Versorgungsposten<br><img src=".$grafik."/ships/".$class.".gif></td>
			<td class=tdmainobg><img src=".$grafik."/buttons/e_trans2.gif> 270<br>";
			$cost = $myColony->getshipcostbyid($class);
			for ($i=0;$i<count($cost);$i++) echo $myColony->getgoodpicbyid($cost[$i][goods_id])." ".$cost[$i]['count']."<br>";
			echo "</td>
		</tr>";
	}
	echo "<tr>
		<td class=tdmainobg colspan=3>&nbsp;</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center colspan=3><input type=submit value=Bauen class=button></td>
	</tr></form></table>";
}
elseif ($section == "fergtrade")
{
	if ($goodid && ($sbutton == "Kaufen")) $result = $myTrade->buylatinum($goodid,$id,$user);
	if ($goodid && ($sbutton == "Verkaufen")) $result = $myTrade->selllatinum($goodid,$id,$user);
	$goods = $myTrade->getferggoods();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Ferengihandel</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table width=400 bgcolor=#262323 cellspacing=1 cellpadding=1><tr>
		<td></td>
		<td class=tdmainobg align=center><strong>Verfügbar</strong></td>
		<td class=tdmainobg align=center><strong>Ankauf</strong></td>
		<td class=tdmainobg align=center><strong>Verkauf</strong></td>
		<td class=tdmainobg colspan=2 align=Center><strong>Latinum</strong></td>
	</tr>";
	for ($i=0;$i<count($goods);$i++)
	{
		$kpreis = $myTrade->getkpricebygoodid($goods[$i][id]);
		$vkpreis = $myTrade->getvkpricebygoodid($goods[$i][id]);
		$verfgcount = $myTrade->getfergcountbygoodid($goods[$i][id]);
		if ($verfgcount == "") $verfgcount = 0;
		echo "<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=fergtrade>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=goodid value=".$goods[$i][id].">
		<tr>
			<td class=tdmainobg>".$myColony->getgoodpicbyid($goods[$i][id])."</td>
			<td class=tdmainobg>".$verfgcount."</td>
			<td class=tdmainobg>".$kpreis."</td>
			<td class=tdmainobg>".$vkpreis."</td>
			<td class=tdmainobg><input type=submit value=Kaufen name=sbutton class=button></td>
			<td class=tdmainobg><input type=submit value=Verkaufen name=sbutton class=button></td>
		</tr>
		</form>";
	}
	$storage = $myShip->getstoragecountbyid($id);
	if ($storage == "") $storage = 0;
	echo "</table><br><table>
	<table width=100 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain><a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a></td>
	</tr>
	<tr>
		<td class=tdmainobg>Lagerraum: (".$storage."/".$myShip->cclass[storage].")</td>
	</tr></table>";
}
elseif ($section == "techtrade")
{
	if ($goodid && ($sbutton == "Kaufen")) $result = $myTrade->buytech($goodid,$id,$user);
	$goods = $myTrade->getfergtechs();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Datenbörse</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table width=600 bgcolor=#262323 cellspacing=1 cellpadding=1><tr>
		<td></td>
		<td class=tdmainobg align=center><strong>Anbieter</strong></td>
		<td class=tdmainobg align=center><strong>Verfügbar</strong></td>
		<td class=tdmainobg align=center><strong>Ankauf</strong></td>
		<td class=tdmainobg align=Center><strong></strong></td>
	</tr>";
	for ($i=0;$i<count($goods);$i++)
	{
		if ($goods[$i][id] == 45)      $raceadd = "<font color=#7DABBA><b>Son'a</b></font>";
		elseif ($goods[$i][id] == 46)  $raceadd = "<font color=#00DD22><b>Romulanisches Sternenimperium</b></font>";
		elseif ($goods[$i][id] == 48)  $raceadd = "<font color=#997241><b>Kessok</b></font>";
		elseif ($goods[$i][id] == 221) $raceadd = "<font color=#EE9960><b>Die Kinder von Tama</b></font>";
		$kpreis = $myTrade->gettechpricebygoodid($goods[$i][id]);
		$verfgcount = $myTrade->getfergcountbygoodid($goods[$i][id]);
		if ($verfgcount == "") $verfgcount = 0;
		echo "<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=techtrade>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=goodid value=".$goods[$i][id].">
		<tr>
			<td class=tdmainobg>".$myColony->getgoodpicbyid($goods[$i][id])."</td>
			<td class=tdmainobg>".$raceadd."</td>
			<td class=tdmainobg>".$verfgcount."</td>
			<td class=tdmainobg>".$myColony->getgoodpicbyid($kpreis[goods_id])."  ".$kpreis[count]."</td>
			<td class=tdmainobg><input type=submit value=Kaufen name=sbutton class=button></td>
		</tr>
		<tr><td class=tdmainobg colspan=5></td></tr>
		</form>";
	}
	$storage = $myShip->getstoragecountbyid($id);
	if ($storage == "") $storage = 0;
	echo "</table><br><table>
	<table width=100 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain><a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a></td>
	</tr>
	<tr>
		<td class=tdmainobg>Lagerraum: (".$storage."/".$myShip->cclass[storage].")</td>
	</tr></table>";
}
elseif ($section == "bar")
{
	if (!is_numeric($pid)) exit;
	if ($action == "getinfo") $result = $myTrade->informant($pid,$id,$info,$informant,$shipid,$user);
	$myTrade->delInformants($user);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Bar</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table width=500 bgcolor=#262323 cellspacing=1 cellpadding=1><tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=informant>
	<input type=hidden name=pid value=".$pid.">
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=info value=1>
	<tr>
		<td class=tdmainobg width=500>
		Wo befindet sich das Schiff ID: <input type=text size=5 name=shipid class=text>? <input type=submit value=Erfragen class=button>
	</tr>
	</form>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=informant>
	<input type=hidden name=pid value=".$pid.">
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=info value=2>
	<tr>
		<td class=tdmainobg width=500>
		Hast Du Informationen über die Kolonie ID: <input type=text size=5 name=shipid class=text>? <input type=submit value=Erfragen class=button>
	</tr>
	</form>
	<form action=main.php method=post>
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=informant>
	<input type=hidden name=pid value=".$pid.">
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=info value=3>
	<tr>
		<td class=tdmainobg width=500>
		Wann wurde Siedler ID: <input type=text size=5 name=shipid class=text> zuletzt gesehen? <input type=submit value=Erfragen class=button>
	</tr>
	</form>
	<tr>
		<td class=tdmainobg width=500><a href=main.php?page=ship&section=informant&pid=".$pid."&id=".$id."&info=4>Kartenausschnitt erwerben</a></td>
	</tr>
	<tr>
		<td class=tdmainobg width=500><a href=?page=ship&section=dabo&id=".$id.">Dabo</a></td>
	</tr></table>";
}
elseif ($section == "informant")
{
	$myTrade->delinformants($user);
	$myTrade->addInformants($pid,$info,$user);
	$infos = $myTrade->getinformants($pid,$info,$id,$user);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <a href=main.php?page=ship&section=bar&id=".$id."&pid=".$pid.">Bar</a> / <strong>Informanten</strong></td>
	</tr>
	</table><br>";
	if ($infos[msg] != "") echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$infos[msg]."</td></tr></table>";
	else
	{
		echo "<table width=500 bgcolor=#262323 cellspacing=1 cellpadding=1>";
		for ($i=0;$i<count($infos);$i++) echo "<tr><td class=tdmainobg><img src=".$grafik."/informants/".$infos[$i][pic].".gif></td>
		<td class=tdmainobg><a href=main.php?page=ship&section=bar&action=getinfo&pid=".$pid."&id=".$id."&shipid=".$shipid."&info=".$info."&informant=".$infos[$i][id].">".$infos[$i][type]."</a> (Preis: ".$infos[$i][price]." Latinum)</td></tr>";
		echo "</table>";
	}
}
elseif ($section == "selectsm")
{
	$ship = $myShip->getclassbyid($classid);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Stationsbau</strong></td>
	</tr>
	</table><br>";
	if ($ship[slots] > 0)
	{
		if ($submit == "Bauen") $result = $myShip->buildstation($huellmod,$schildmod,$waffenmod,$sensormod,$reaktormod,$epsmod,$computermod,$classid,$id,$user);
		$modules = $myColony->getshipmodules($classid,$user);
		$points = $ship[points];
		$buildtime = $ship[buildtime];
		$torp_evade = $ship[torp_evade];
		$timem = floor($ship[buildtime]/60);
		$times = $ship[buildtime]-($timem*60);
		$time = $timem."m ".$times."s";
		if ($result) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
		echo "<table width=600 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmainobg colspan=2><strong>".$ship[name]." Klasse</strong></td>
			<td class=tdmainobg width=50>".$time."</td>
			<td class=tdmainobg width=30>".$ship[points]."</td>
			<td class=tdmainobg></td>
		</tr>
		<tr>
			<td class=tdmainobg colspan=5><strong>Hülle</strong></td>
		</tr>
		<form action=main.php method=post>
		<input type=hidden name=page value=ship>
		<input type=hidden name=section value=selectsm>
		<input type=hidden name=classid value=".$classid.">
		<input type=hidden name=id value=".$id.">";
		for ($i=0;$i<count($modules[huelle]);$i++)
		{
			$stor = $myShip->getcountbygoodid($modules[huelle][$i][goods_id],$id);
			if ($stor == 0)
			{
				$vshow = "<font color=Red>0</font>";
				$dis = " disabled=yes";
			}
			elseif ($stor < $modules[huelle][$i][c])
			{
				$vshow = "<font color=Red>".$stor."</font>";
				$dis = " disabled=yes";
			}
			else
			{
				$vshow = $stor;
				$dis = "";
			}
			if ($huellmod == $modules[huelle][$i][id])
			{
				$sel = " checked";
				$thism = $modules[huelle][$i];
				$buildtime += ($thism[buildtime]*$thism[c]);
				$points = $points + ($thism[wirt]*$thism[c]);
				$huelle = $huelle + ($thism[huell]*$thism[c]);
				$shields = $shields + ($thism[shields]*$thism[c]);
				$torp_evade = $torp_evade + ($thism[torp_evade]*$thism[c]);
				$eps = $eps + ($thism[eps]*$thism[c]);
				$reaktor = $reaktor + ($thism[reaktor]*$thism[c]);
				$phaser_chance = $phaser_chance + ($thism[phaser_chance]*$thism[c]);
				$sensor = $sensor + ($thism[lss_range]*$thism[c]);
			}
			else unset($sel);
			$timem = floor($modules[huelle][$i][buildtime]/60);
			$times = $modules[huelle][$i][buildtime]-($timem*60);
			$time = $timem."m ".$times."s";
			if (($modules[huelle][$i][view] == 1) || ($stor > 0))
				echo "<tr><td class=tdmainobg width=300><input type=radio name=huellmod value=\"".$modules[huelle][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[huelle][$i][goods_id])." ".$modules[huelle][$i][name]."</td>
				 <td class=tdmainobg width=50>".$vshow."/".$modules[huelle][$i][c]."</td>
				 <td class=tdmainobg width=80>".$time."</td>
				 <td class=tdmainobg>".$modules[huelle][$i][wirt]."</td>
				 <td class=tdmainobg width=200>Hülle: ".$modules[huelle][$i][huell]."</td></tr>";
		}
		echo "<tr><td class=tdmainobg colspan=5><strong>Computer</strong></td></tr>";
		for ($i=0;$i<count($modules[computer]);$i++)
		{
			$stor = $myShip->getcountbygoodid($modules[computer][$i][goods_id],$id);
			if ($stor == 0)
			{
				$vshow = "<font color=Red>0</font>";
				$dis = " disabled=yes";
			}
			elseif ($stor < $modules[computer][$i][c])
			{
				$vshow = "<font color=Red>".$stor."</font>";
				$dis = " disabled=yes";
			}
			else
			{
				$vshow = $stor;
				$dis = "";
			}
			if ($computermod == $modules[computer][$i][id])
			{
				$sel = " checked";
				$thism = $modules[computer][$i];
				$buildtime += $thism[buildtime];
				$points = $points + ($thism[wirt]*$thism[c]);
				$huelle = $huelle + ($thism[huell]*$thism[c]);
				$shields = $shields + ($thism[shields]*$thism[c]);
				$torp_evade = $torp_evade + ($thism[torp_evade]*$thism[c]);
				$eps = $eps + ($thism[eps]*$thism[c]);
				$reaktor = $reaktor + ($thism[reaktor]*$thism[c]);
				$phaser_chance = $phaser_chance + ($thism[phaser_chance]*$thism[c]);
				$sensor = $sensor + ($thism[lss_range]*$thism[c]);
			}
			else unset($sel);
			$timem = floor($modules[computer][$i][buildtime]/60);
			$times = $modules[computer][$i][buildtime]-($timem*60);
			$time = $timem."m ".$times."s";
			if (($modules[computer][$i][view] == 1) || ($stor > 0))
			echo "<tr><td class=tdmainobg><input type=radio name=computermod value=\"".$modules[computer][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[computer][$i][goods_id])." ".$modules[computer][$i][name]."</td>
				 <td class=tdmainobg>".$vshow."/".$modules[computer][$i][c]."</td>
				 <td class=tdmainobg>".$time."</td>
				 <td class=tdmainobg>".($modules[computer][$i][wirt]*$modules[computer][$i][c])."</td>
				 <td class=tdmainobg>Ausweichchance: ".$modules[computer][$i][torp_evade]."%<br>
				 					 Trefferchance: ".$modules[computer][$i][phaser_chance]."%</td></tr>";
		}
		echo "<tr><td class=tdmainobg colspan=5><strong>Schilde</strong></td></tr>";
		for ($i=0;$i<count($modules[schilde]);$i++)
		{
			$stor = $myShip->getcountbygoodid($modules[schilde][$i][goods_id],$id);
			if ($stor == 0)
			{
				$vshow = "<font color=Red>0</font>";
				$dis = " disabled=yes";
			}
			elseif ($stor < $modules[schilde][$i][c])
			{
				$vshow = "<font color=Red>".$stor."</font>";
				$dis = " disabled=yes";
			}
			else
			{
				$vshow = $stor;
				$dis = "";
			}
			if ($schildmod == $modules[schilde][$i][id])
			{
				$sel = " checked";
				$thism = $modules[schilde][$i];
				$buildtime += ($thism[buildtime]*$thism[c]);
				$points = $points + ($thism[wirt]*$thism[c]);
				$huelle = $huelle + ($thism[huell]*$thism[c]);
				$shields = $shields + ($thism[shields]*$thism[c]);
				$torp_evade = $torp_evade + ($thism[torp_evade]*$thism[c]);
				$eps = $eps + ($thism[eps]*$thism[c]);
				$reaktor = $reaktor + ($thism[reaktor]*$thism[c]);
				$phaser_chance = $phaser_chance + ($thism[phaser_chance]*$thism[c]);
				$sensor = $sensor + ($thism[lss_range]*$thism[c]);
			}
			else unset($sel);
			$timem = floor(($modules[schilde][$i][buildtime]*$modules[schilde][$i][c])/60);
			$times = ($modules[schilde][$i][buildtime]*$modules[schilde][$i][c])-($timem*60);
			$time = $timem."m ".$times."s";
			if (($modules[schilde][$i][view] == 1) || ($stor > 0))
			echo "<tr><td class=tdmainobg><input type=radio name=schildmod value=\"".$modules[schilde][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[schilde][$i][goods_id])." ".$modules[schilde][$i][name]."</td>
				 <td class=tdmainobg>".$vshow."/".$modules[schilde][$i][c]."</td>
				 <td class=tdmainobg>".$time."</td>
				 <td class=tdmainobg>".($modules[schilde][$i][wirt]*$modules[schilde][$i][c])."</td>
				 <td class=tdmainobg>Schilde: ".$modules[schilde][$i][shields]."</td></tr>";
		}
		echo "<tr><td class=tdmainobg colspan=5><strong>Waffen</strong></td></tr>";
		if ($waffenmod == 0)
		{
			$sel = " checked";
			$plu = 1+($ship[waffenmod_max]-$ship[waffemond_min]);
		}
		echo "<tr><td class=tdmainobg><input type=radio name=waffenmod value=0".$sel."> Keine</td>
			 <td class=tdmainobg></td>
			 <td class=tdmainobg></td>
			 <td class=tdmainobg></td>
			 <td class=tdmainobg></td></tr>";
		for ($i=0;$i<count($modules[waffen]);$i++)
		{
			$stor = $myShip->getcountbygoodid($modules[waffen][$i][goods_id],$id);
			if ($stor == 0)
			{
				$vshow = "<font color=Red>0</font>";
				$dis = " disabled=yes";
			}
			elseif ($stor < $modules[waffen][$i][c])
			{
				$vshow = "<font color=Red>".$stor."</font>";
				$dis = " disabled=yes";
			}
			else
			{
				$vshow = $stor;
				$dis = "";
			}
			if ($waffenmod == $modules[waffen][$i][id])
			{
				$sel = " checked";
				$thism = $modules[waffen][$i];
				$buildtime += ($thism[buildtime]*$thism[c]);
				$points = $points + ($thism[wirt]*$thism[c]);
				$phaser = round($thism[phaser] * (1+(($thism[c]-1)/3)));
				$huelle = $huelle + ($thism[huell]*$thism[c]);
				$shields = $shields + ($thism[shields]*$thism[c]);
				$torp_evade = $torp_evade + ($thism[torp_evade]*$thism[c]);
				$eps = $eps + ($thism[eps]*$thism[c]);
				$reaktor = $reaktor + ($thism[reaktor]*$thism[c]);
				$phaser_chance = $phaser_chance + $thism[phaser_chance];
				$sensor = $sensor + ($thism[lss_range]*$thism[c]);
				$plu = ($ship[waffenmod_max]-$thism[lvl]);
			}
			else unset($sel);
			$timem = floor($modules[waffen][$i][buildtime]/60);
			$times = $modules[waffen][$i][buildtime]-($timem*60);
			$time = $timem."m ".$times."s";
			if (($modules[waffen][$i][view] == 1) || ($stor > 0))
			echo "<tr><td class=tdmainobg><input type=radio name=waffenmod value=\"".$modules[waffen][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[waffen][$i][goods_id])." ".$modules[waffen][$i][name]."</td>
				 <td class=tdmainobg>".$vshow."/".$modules[waffen][$i][c]."</td>
				 <td class=tdmainobg>".$time."</td>
				 <td class=tdmainobg>".$modules[waffen][$i][wirt]."</td>
				 <td class=tdmainobg>Schaden: ".round($thism[phaser]*(1+($thism[c]-1)/3))."<br>
				 					 Trefferchance: ".$modules[waffen][$i][phaser_chance]."%</td></tr>";
		}
		echo "<tr><td class=tdmainobg colspan=5><strong>EPS-Gitter</strong></td></tr>";
		for ($i=0;$i<count($modules[eps]);$i++)
		{
			$stor = $myShip->getcountbygoodid($modules[eps][$i][goods_id],$id);
			if ($stor == 0)
			{
				$vshow = "<font color=Red>0</font>";
				$dis = " disabled=yes";
			}
			elseif ($stor < $modules[eps][$i][c])
			{
				$vshow = "<font color=Red>".$stor."</font>";
				$dis = " disabled=yes";
			}
			else
			{
				$vshow = $stor;
				$dis = "";
			}
			if ($epsmod == $modules[eps][$i][id])
			{
				$sel = " checked";
				$thism = $modules[eps][$i];
				$buildtime += ($thism[buildtime]*$thism[c]);
				$points = $points + ($thism[wirt]*$thism[c]);
				$huelle = $huelle + ($thism[huell]*$thism[c]);
				$shields = $shields + ($thism[shields]*$thism[c]);
				$torp_evade = $torp_evade + ($thism[torp_evade]*$thism[c]);
				$eps = $eps + ($thism[eps]*$thism[c]);
				$reaktor = $reaktor + ($thism[reaktor]*$thism[c]);
				$phaser_chance = $phaser_chance + ($thism[phaser_chance]*$thism[c]);
				$sensor = $sensor + ($thism[lss_range]*$thism[c]);
			}
			else unset($sel);
			$timem = floor($modules[eps][$i][buildtime]/60);
			$times = $modules[eps][$i][buildtime]-($timem*60);
			$time = $timem."m ".$times."s";
			if (($modules[eps][$i][view] == 1) || ($stor > 0))
			echo "<tr><td class=tdmainobg><input type=radio name=epsmod value=\"".$modules[eps][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[eps][$i][goods_id])." ".$modules[eps][$i][name]."</td>
				 <td class=tdmainobg>".$vshow."/".$modules[eps][$i][c]."</td>
				 <td class=tdmainobg>".$time."</td>
				 <td class=tdmainobg>".$modules[eps][$i][wirt]."</td>
				 <td class=tdmainobg>EPS: ".$modules[eps][$i][eps]."</td></tr>";
		}
		echo "<tr><td class=tdmainobg colspan=5><strong>Sensoren</strong></td></tr>";
		for ($i=0;$i<count($modules[sensor]);$i++)
		{
			$stor = $myShip->getcountbygoodid($modules[sensor][$i][goods_id],$id);
			if ($stor == 0)
			{
				$vshow = "<font color=Red>0</font>";
				$dis = " disabled=yes";
			}
			elseif ($stor < $modules[sensor][$i][c])
			{
				$vshow = "<font color=Red>".$stor."</font>";
				$dis = " disabled=yes";
			}
			else
			{
				$vshow = $stor;
				$dis = "";
			}
			if ($sensormod == $modules[sensor][$i][id])
			{
				$sel = " checked";
				$thism = $modules[sensor][$i];
				$buildtime += ($thism[buildtime]*$thism[c]);
				$points = $points + ($thism[wirt]*$thism[c]);
				$huelle = $huelle + ($thism[huell]*$thism[c]);
				$shields = $shields + ($thism[shields]*$thism[c]);
				$torp_evade = $torp_evade + ($thism[torp_evade]*$thism[c]);
				$eps = $eps + ($thism[eps]*$thism[c]);
				$reaktor = $reaktor + ($thism[reaktor]*$thism[c]);
				$phaser_chance = $phaser_chance + ($thism[phaser_chance]*$thism[c]);
				$sensor = $modules[sensor][$i][lss_range] + ($thism[c]-1);
			}
			else unset($sel);
			$timem = floor($modules[sensor][$i][buildtime]/60);
			$times = $modules[sensor][$i][buildtime]-($timem*60);
			$time = $timem."m ".$times."s";
			if (($modules[sensor][$i][view] == 1) || ($stor > 0))
			echo "<tr><td class=tdmainobg><input type=radio name=sensormod value=\"".$modules[sensor][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[sensor][$i][goods_id])." ".$modules[sensor][$i][name]."</td>
				 <td class=tdmainobg>".$vshow."/".$modules[sensor][$i][c]."</td>
				 <td class=tdmainobg>".$time."</td>
				 <td class=tdmainobg>".$modules[sensor][$i][wirt]."</td>
				 <td class=tdmainobg>LSS-Range: ".$modules[sensor][$i][lss_range]."</td></tr>";
		}
		echo "<tr><td class=tdmainobg colspan=5><strong>Warpkern</strong></td></tr>";
		if ($reaktormod == 0) $sel = " checked";
		echo "<tr><td class=tdmainobg><input type=radio name=reaktormod value=0".$sel."> Keiner</td>
			 <td class=tdmainobg></td>
			 <td class=tdmainobg></td>
			 <td class=tdmainobg></td>
			 <td class=tdmainobg></td></tr>";
		for ($i=0;$i<count($modules[reaktor]);$i++)
		{
			$stor = $myShip->getcountbygoodid($modules[reaktor][$i][goods_id],$id);
			if ($stor == 0)
			{
				$vshow = "<font color=Red>0</font>";
				$dis = " disabled=yes";
			}
			elseif ($stor < $modules[reaktor][$i][c])
			{
				$vshow = "<font color=Red>".$stor."</font>";
				$dis = " disabled=yes";
			}
			else
			{
				$vshow = $stor;
				$dis = "";
			}
			if ($reaktormod == $modules[reaktor][$i][id])
			{
				$sel = " checked";
				$thism = $modules[reaktor][$i];
				$buildtime += $thism[buildtime];
				$points = $points + ($thism[wirt]*$thism[c]);
				$huelle = $huelle + ($thism[huell]*$thism[c]);
				$shields = $shields + ($thism[shields]*$thism[c]);
				$torp_evade = $torp_evade + ($thism[torp_evade]*$thism[c]);
				$eps = $eps + ($thism[eps]*$thism[c]);
				$reaktor = $reaktor + ($thism[reaktor]*$thism[c]);
				$phaser_chance = $phaser_chance + ($thism[phaser_chance]*$thism[c]);
				$sensor = $sensor + ($thism[lss_range]*$thism[c]);
			}
			else unset($sel);
			$timem = floor($modules[reaktor][$i][buildtime]/60);
			$times = $modules[reaktor][$i][buildtime]-($timem*60);
			$time = $timem."m ".$times."s";
			if (($modules[reaktor][$i][view] == 1) || ($stor > 0))
			echo "<tr><td class=tdmainobg><input type=radio name=reaktormod value=\"".$modules[reaktor][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[reaktor][$i][goods_id])." ".$modules[reaktor][$i][name]."</td>
				 <td class=tdmainobg>".$vshow."/".$modules[reaktor][$i][c]."</td>
				 <td class=tdmainobg>".$time."</td>
				 <td class=tdmainobg>".$modules[reaktor][$i][wirt]."</td>
				 <td class=tdmainobg>Reaktor: ".$modules[reaktor][$i][reaktor]."</td></tr>";
		}
		$timem = floor($buildtime/60);
		$times = $buildtime-($timem*60);
		$time = $timem."m ".$times."s";
		echo "<tr><td colspan=3><input type=submit name=Vorschau value=Vorschau class=button> <input type=submit name=submit value=Bauen class=button></td></tr></table>
		<br>
		<table width=750 bgcolor=#262323>
		<tr>
			<td rowspan=2><img src=".$grafik."/ships/".$ship[id].".gif></td>
			<td class=tdmain><strong>Hülle</strong></td>
			<td class=tdmain><strong>EPS</strong></td>
			<td class=tdmain><strong>Schilde</strong></td>
			<td class=tdmain><strong>Schaden</strong></td>
			<td class=tdmain><strong>Ausweichchance</strong></td>
			<td class=tdmain><strong>Reaktor</strong></td>
			<td class=tdmain><strong>Trefferchance</strong></td>
			<td class=tdmain><strong>LSS</strong></td>
			<td class=tdmain><strong>Frachtraum</strong></td>
			<td class=tdmain><strong>Bauzeit</strong></td>
			<td class=tdmain><strong>Punkte</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg align=center>".$huelle."</td>
			<td class=tdmainobg align=center>".$eps."</td>
			<td class=tdmainobg align=center>".$shields."</td>
			<td class=tdmainobg align=center>".$phaser."</td>
			<td class=tdmainobg align=center>".$torp_evade."%</td>
			<td class=tdmainobg align=center>".($reaktor+$plu)."</td>
			<td class=tdmainobg align=center>".$phaser_chance."%</td>
			<td class=tdmainobg align=center>".$sensor."</td>
			<td class=tdmainobg align=center>".$ship[storage]."</td>
			<td class=tdmainobg align=center>".$time."</td>
			<td class=tdmainobg align=center>".$points."</td>
		</tr></table>";
	}
}
elseif ($section == "shiprepair")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Schiffe</a> / <a href=main.php?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Schiff reparieren</strong></td>
	</tr>
	</table><br>";
	if (!$targetid) exit;
	$targetdata = $myShip->getdatabyid($targetid);
	if ($myShip->cwese != $targetdata[wese] || $myShip->ccoords_x != $targetdata[coords_x] || $myShip->ccoords_y != $targetdata[coords_y]) exit;
	$cost = $myShip->getshiprepaircost($targetid);
	echo "<form action=main.php method=post>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=targetid value=".$targetid.">
	<input type=hidden name=page value=ship>
	<input type=hidden name=section value=showship>
	<input type=hidden name=action value=shiprepair><table><tr>
	<td class=tdmain align=Center>Schiffsreparatur: ".stripslashes($targetdata[name])." (Hülle: ".$targetdata[huelle]."/".$targetdata[maxhuell].")</td></tr>
	<tr>
	<td class=tdmain align=left><strong>Reparaturkosten</strong><br>";
	$stor[0] = $myShip->cenergie;
	$stor[3] = $myShip->getcountbygoodid(3,$id);
	$stor[6] = $myShip->getcountbygoodid(6,$id);
	$stor[9] = $myShip->getcountbygoodid(9,$id);
	$stor[10] = $myShip->getcountbygoodid(10,$id);
	$stor[12] = $myShip->getcountbygoodid(12,$id);
	$stor[14] = $myShip->getcountbygoodid(14,$id);
	$stor[15] = $myShip->getcountbygoodid(15,$id);
	$stor[19] = $myShip->getcountbygoodid(19,$id);
	if ($cost[modules][huellem] > 0) $stor[modules][huellec] = $myShip->getcountbygoodid($cost[modules][huelleg],$id);
	if ($cost[modules][schildem] > 0) $stor[modules][schildec] = $myShip->getcountbygoodid($cost[modules][schildeg],$id);
	if ($cost[modules][sensorm] > 0) $stor[modules][sensorc] = $myShip->getcountbygoodid($cost[modules][sensorg],$id);
	if ($cost[modules][waffenm] > 0) $stor[modules][waffenc] = $myShip->getcountbygoodid($cost[modules][waffeng],$id);
	if ($cost[modules][antriebm] > 0) $stor[modules][antriebc] = $myShip->getcountbygoodid($cost[modules][antriebg],$id);
	if ($cost[modules][reaktorm] > 0) $stor[modules][reaktorc] = $myShip->getcountbygoodid($cost[modules][reaktorg],$id);
	if ($cost[modules][computerm] > 0) $stor[modules][computerc] = $myShip->getcountbygoodid($cost[modules][computerg],$id);
	if ($cost[modules][epsm] > 0) $stor[modules][epsc] = $myShip->getcountbygoodid($cost[modules][epsg],$id);
	if ($targetdata[c][slots] > 0)
	{
		$stor[0] += $targetdata[energie];
		$stor[3] += $myShip->getcountbygoodid(3,$targetid);
		$stor[6] += $myShip->getcountbygoodid(6,$targetid);
		$stor[9] += $myShip->getcountbygoodid(9,$targetid);
		$stor[10] += $myShip->getcountbygoodid(10,$targetid);
		$stor[12] += $myShip->getcountbygoodid(12,$targetid);
		$stor[14] += $myShip->getcountbygoodid(14,$targetid);
		$stor[15] += $myShip->getcountbygoodid(15,$targetid);
		$stor[19] += $myShip->getcountbygoodid(19,$targetid);
		if ($cost[modules][huellem] > 0) $stor[modules][huellec] += $myShip->getcountbygoodid($cost[modules][huelleg],$targetid);
		if ($cost[modules][schildem] > 0) $stor[modules][schildec] += $myShip->getcountbygoodid($cost[modules][schildeg],$targetid);
		if ($cost[modules][sensorm] > 0) $stor[modules][sensorc] += $myShip->getcountbygoodid($cost[modules][sensorg],$targetid);
		if ($cost[modules][waffenm] > 0) $stor[modules][waffenc] += $myShip->getcountbygoodid($cost[modules][waffeng],$targetid);
		if ($cost[modules][antriebm] > 0) $stor[modules][antriebc] += $myShip->getcountbygoodid($cost[modules][antriebg],$targetid);
		if ($cost[modules][reaktorm] > 0) $stor[modules][reaktorc] += $myShip->getcountbygoodid($cost[modules][reaktorg],$targetid);
		if ($cost[modules][computerm] > 0) $stor[modules][computerc] += $myShip->getcountbygoodid($cost[modules][computerg],$targetid);
		if ($cost[modules][epsm] > 0) $stor[modules][epsc] += $myShip->getcountbygoodid($cost[modules][epsg],$targetid);
	}
	if ($cost[0] > 0)
	{
		$stor[0] < $cost[0] ? $menge = "<font color=red>".$stor[0]."</font>" : $menge = $stor[0];
		echo "<img src=".$grafik."/buttons/e_trans2.gif title='Energie'> ".$cost[0]."/".$menge."<br>";
	}
	if ($cost[3] > 0)
	{
		$stor[3] < $cost[3] ? $menge = "<font color=red>".$stor[3]."</font>" : $menge = $stor[3];
		echo $myColony->getgoodpicbyid(3)." ".$cost[3]."/".$menge."<br>";
	}
	if ($cost[6] > 0)
	{
		$stor[6] < $cost[6] ? $menge = "<font color=red>".$stor[6]."</font>" : $menge = $stor[6];
		echo $myColony->getgoodpicbyid(6)." ".$cost[6]."/".$menge."<br>";
	}
	if ($cost[9] > 0)
	{
		$stor[9] < $cost[9] ? $menge = "<font color=red>".$stor[9]."</font>" : $menge = $stor[9];
		echo $myColony->getgoodpicbyid(9)." ".$cost[9]."/".$menge."<br>";
	}
	if ($cost[10] > 0)
	{
		$stor[10] < $cost[10] ? $menge = "<font color=red>".$stor[10]."</font>" : $menge = $stor[10];
		echo $myColony->getgoodpicbyid(10)." ".$cost[10]."/".$menge."<br>";
	}
	if ($cost[12] > 0)
	{
		$stor[12] < $cost[12] ? $menge = "<font color=red>".$stor[12]."</font>" : $menge = $stor[12];
		echo $myColony->getgoodpicbyid(12)." ".$cost[12]."/".$menge."<br>";
	}
	if ($cost[14] > 0)
	{
		$stor[14] < $cost[14] ? $menge = "<font color=red>".$stor[14]."</font>" : $menge = $stor[14];
		echo $myColony->getgoodpicbyid(14)." ".$cost[14]."/".$menge."<br>";
	}
	if ($cost[15] > 0)
	{
		$stor[15] < $cost[15] ? $menge = "<font color=red>".$stor[15]."</font>" : $menge = $stor[15];
		echo $myColony->getgoodpicbyid(15)." ".$cost[15]."/".$menge."<br>";
	}
	if ($cost[19] > 0)
	{
		$stor[19] < $cost[19] ? $menge = "<font color=red>".$stor[19]."</font>" : $menge = $stor[19];
		echo $myColony->getgoodpicbyid(19)." ".$cost[19]."/".$menge."<br>";
	}
	if ($cost[modules][huellec] > 0)
	{
		$stor[modules][huellec] < $cost[modules][huellec] ? $menge = "<font color=red>".$stor[modules][huellec]."</font>" : $menge = $stor[modules][huellec];
		echo $myColony->getgoodpicbyid($cost[modules][huelleg])." ".$cost[modules][huellec]."/".$menge."<br>";
	}
	if ($cost[modules][schildec] > 0)
	{
		$stor[modules][schildec] < $cost[modules][schildec] ? $menge = "<font color=red>".$stor[modules][schildec]."</font>" : $menge = $stor[modules][schildec];
		echo $myColony->getgoodpicbyid($cost[modules][schildeg])." ".$cost[modules][schildec]."/".$menge."<br>";
		}
	if ($cost[modules][sensorc] > 0)
	{
		$stor[modules][sensorc] < $cost[modules][sensorc] ? $menge = "<font color=red>".$stor[modules][sensorc]."</font>" : $menge = $stor[modules][sensorc];
		echo $myColony->getgoodpicbyid($cost[modules][sensorg])." ".$cost[modules][sensorc]."/".$menge."<br>";
	}
	if (($cost[modules][waffenm] > 0) && ($cost[modules][waffenc] > 0))
	{
		$stor[modules][waffenc] < $cost[modules][waffenc] ? $menge = "<font color=red>".$stor[modules][waffenc]."</font>" : $menge = $stor[modules][waffenc];
		echo $myColony->getgoodpicbyid($cost[modules][waffeng])." ".$cost[modules][waffenc]."/".$menge."<br>";
	}
	if (($cost[modules][antriebm] > 0) && ($cost[modules][antriebc] > 0))
	{
		$stor[modules][antriebc] < $cost[modules][antriebc] ? $menge = "<font color=red>".$stor[modules][antriebc]."</font>" : $menge = $stor[modules][antriebc];
		echo$myColony->getgoodpicbyid($cost[modules][antriebg])." ".$cost[modules][antriebc]."/".$menge."<br>";
	}
	if (($cost[modules][reaktorm] > 0) && ($cost[modules][reaktorc] > 0))
	{
		$stor[modules][reaktorc] < $cost[modules][reaktorc] ? $menge = "<font color=red>".$stor[modules][reaktorc]."</font>" : $menge = $stor[modules][reaktorc];
		echo $myColony->getgoodpicbyid($cost[modules][reaktorg])." ".$cost[modules][reaktorc]."/".$menge."<br>";
	}
	if ($cost[modules][computerc] > 0)
	{
		$stor[modules][computerc] < $cost[modules][computerc] ? $menge = "<font color=red>".$stor[modules][computerc]."</font>" : $menge = $stor[modules][computerc];
		echo $myColony->getgoodpicbyid($cost[modules][computerg])." ".$cost[modules][computerc]."/".$menge."<br>";
	}
	if ($cost[modules][epsc] > 0)
	{
		$stor[modules][epsc] < $cost[modules][epsc] ? $menge = "<font color=red>".$stor[modules][epsc]."</font>" : $menge = $stor[modules][epsc];
		echo $myColony->getgoodpicbyid($cost[modules][epsg])." ".$cost[modules][epsc]."/".$menge."<br>";
	}
	echo "</td>
	</tr>
	<tr>
	<td class=tdmain align=center><input type=submit class=button value=Reparieren></td>
	</tr></form></table>";
}
elseif ($section == "actscan")
{
	if ($myShip->cships_rumps_id != 88 || $myShip->ccomputermodlvl != 44) exit;
	$scan = $myShip->getascanresults();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Active Scan</strong></td>
	</tr>
	</table><br>
	<table width=700 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr><td class=tdmain>Klasse</td>
	<td class=tdmain>User</td>
	<td class=tdmain>x/y</td>
	<td class=tdmain>Datum des Kontakts</td></tr>";
	if (mysql_num_rows($scan) == 0) echo "<tr><td class=tdmainobg colspan=4 align=center>Keine Kontakte gespeichert</td></tr>";
	else
	{
		while($sd=mysql_fetch_assoc($scan))
		{
			if ($sd[secretimage] != "0") $scanpic = "<img src=http://www.stuniverse.de/gfx/secret/".$sd[secretimage].".gif>";
			else $scanpic = "<img src=".$grafik."/ships/".$sd[ships_rumps_id].".gif>";
			echo "<tr><td class=tdmainobg>".$scanpic."</td>
			<td class=tdmainobg>".stripslashes($sd[user])."</td>
			<td class=tdmainobg>".$sd[coords_x]."/".$sd[coords_y]."</td>
			<td class=tdmainobg>".date("d.m.",$sd[date_tsp]).(date("Y",$sd[date_tsp])+375).date(" H:i",$sd[date_tsp])."</td></tr>";
		}
	}
	echo "</table>";
}
elseif ($section == "colonizepuffer")
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <strong>Kolonisierung</strong></td>
	</tr>
	</table><br><br>Diese Kolonie befindet sich in einer Pufferzone, die zwischen den Mächten des Ivor-Vertrages und der <font color=#B54A29><b>Kzinti Hegemonie</b></font> vereinbart wurde. In einem von allen Beteiligten Vertrag wurde die Kolonisierung dieser Zone untersagt.<br><br>Mit der Kolonisierung fortfahren und den Vertrag brechen?";
	echo "<br><br><a href=?page=ship&section=showship&id=".$id."><font color=green>Kolonisation abbrechen</font></a>";
	echo "<br><br><a href=main.php?page=ship&section=showship&action=colonize&id=".$id."&colid=".$colid."><font color=#FF0000>Kolonisieren</font></a>";
}
elseif ($section == "dabo")
{
	if (is_numeric($val)) $result = $myShip->dabo($val);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
	<td width=100% class=tdmain>/ <a href=?page=ship>Schiffe</a> / <a href=?page=ship&section=showship&id=".$id.">".$myShip->cname."</a> / <a href=?page=ship&section=bar&id=".$id.">Bar</a> / <b>Dabo</b></td>
	</tr>
	</table><br>";
	if (is_numeric($val))
	{
		echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr>
		<td class=tdmain><b>Meldung</b></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
		$oval = $val;
		unset($val);
	}
	if (!is_numeric($val))
	{
		echo "<form action=main.php method=post><input type=hidden name=page value=ship><input type=hidden name=section value=dabo><input type=hidden name=id value=".$id."><table width=400 bgcolor=#262323 cellspacing=1 cellpadding=1><tr>
		<td class=tdmain><b>Mitspielen</b></td></tr>
		<tr><td class=tdmainobg>Zahl zwischen 1 und 40 wählen <input type=text size=2 class=text maxlength=2 name=val value=".$oval."><br>
		<img src=".$grafik."/goods/24.gif title='Latinum'> Einsatz 2 / ".$myShip->getcountbygoodid(24,$id)."&nbsp;<input type=submit class=button value=Setzen><br><br>Jackpot: ".$myDB->query("SELECT value FROM stu_game WHERE fielddescr='dabo_jack'",1)." <img src=".$grafik."/goods/24.gif title='Latinum'></td></tr></table></form>";
	}
}
?>
