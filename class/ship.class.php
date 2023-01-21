<?php
class ship
{
	function ship()
	{
		global $myDB,$user,$grafik;
		$this->db = $myDB;
		$this->user = $user;
		$this->gfx = $grafik;
	}
	
	function gcs()
	{
		global $id;
		if ($id > 0)
		{
			$data = $this->db->query("SELECT a.*,b.type,b.race as frace,c.type as stype FROM stu_ships as a LEFT JOIN stu_map_fields as b USING(coords_x,coords_y,wese) LEFT JOIN stu_map_special as c USING(coords_x,coords_y,wese) WHERE a.id=".$id." AND a.user_id=".$this->user,4);
			if ($data == 0)
			{
				$this->cshow = 0;
				return 0;
			}
			$this->cid = $data[id];
			$this->cships_rumps_id = $data[ships_rumps_id];
			$this->ccrew = $data[crew];
			$this->ccoords_x = $data[coords_x];
			$this->ccoords_y = $data[coords_y];
			$this->frace = $data[frace];
			$this->ctype = $data[type];
			$this->cstype = $data[stype];
			$this->cenergie = $data[energie];
			$this->cfleets_id = $data[fleets_id];
			$this->chuelle = $data[huelle];
			$this->cschilde = $data[schilde];
			$this->cschilde_aktiv = $data[schilde_aktiv];
			$this->calertlevel = $data[alertlevel];
			$this->cepsmodlvl = $data[epsmodlvl];
			$this->cantriebmodlvl = $data[antriebmodlvl];
			$this->cwaffenmodlvl = $data[waffenmodlvl];
			$this->ccomputermodlvl = $data[computermodlvl];
			$this->csensormodlvl = $data[sensormodlvl];
			$this->creaktormodlvl = $data[reaktormodlvl];
			$this->chuellmodlvl = $data[huellmodlvl];
			$this->cschildmodlvl = $data[schildmodlvl];
			$this->clss = $data[lss];
			$this->ckss = $data[kss];
			$this->cname = $data[name];
			$this->cbatt = $data[batt];
			$this->ctraktor = $data[traktor];
			$this->ctraktormode = $data[traktormode];
			$this->cstrb_mode = $data[strb_mode];
			$this->cwarpcore = $data[warpcore];
			$this->ccloak = $data[cloak];
			$this->creplikator = $data[replikator];
			$this->ctachyon = $data[tachyon];
			$this->cepsmod = $data[epsupgrade];
			$this->cactscan = $data[actscan];
			$this->cwese = $data[wese];
			$this->cdeact = $data[deact];
			$this->cdock = $data[dock];
			$this->cshow = 1;
			if ($data[replikator] == 1) $erepl = ceil($data[crew]/5);
			$class = $this->getclassbyid($data[ships_rumps_id]);
			$this->cclass = $class;
			$module = $this->getmodulebyid($data[huellmodlvl]);
			$this->cmaxhuell = $class[huellmod]*$module[huell];
			$module = $this->getmodulebyid($data[schildmodlvl]);
			$this->cmaxshields = $class[schildmod]*$module[shields];
			$module = $this->getmodulebyid($data[epsmodlvl]);
			$this->cmaxeps = $class[epsmod]*$module[eps];
			$this->cmaxtreffer = 0;
			if ($data[waffenmodlvl] > 0)
			{
				$waff = $this->getmodulebyid($data[waffenmodlvl]);
				$plu = $class[waffenmod_max]-$waff[lvl];
				$this->cmaxphaser = round($waff[phaser] * (1+(($class[waffenmod]-1)/3)));
				$this->cmaxtreffer += $waff[phaser_chance];
			}
			else $plu = 1 + ($class[waffenmod_max]-$class[waffenmod_min]);
			if ($data[warpcore] == 0 && $data[reaktormodlvl] != 0) unset($plu);
			if (($data[reaktormodlvl] != 0) && ($data[warpcore] > 0))
			{
				$module = $this->getmodulebyid($data[reaktormodlvl]);
				$this->cmaxreaktor = $module[reaktor]+$class[fusion]+$plu;
			}
			else $this->cmaxreaktor = $class[fusion]+$plu;
			$module = $this->getmodulebyid($data[computermodlvl]);
			$this->cmaxtreffer += $module[phaser_chance];
			$this->cmaxausweichen += $module[torp_evade];
			if ($data[antriebmodlvl] != 0)
			{
				$module = $this->getmodulebyid($data[antriebmodlvl]);
				$this->cmaxausweichen += $module[torp_evade];
				$this->cmaxtreffer += $module[phaser_chance];
			}
			$module = $this->getmodulebyid($data[sensormodlvl]);
			$this->cmaxlss = $module[lss_range]+($class[sensormod]-1);
			$data[huelle] <= ($this->cmaxhuell * 0.4) ? $this->cdamaged = 1 : $this->cdamaged = 0;
			$this->s1count = $this->getcountbygoodid(35,$id);
			$this->s2count = $this->getcountbygoodid(36,$id);
			$this->s3count = $this->getcountbygoodid(37,$id);
			$this->s4count = $this->getcountbygoodid(204,$id);
			$this->s5count = $this->getcountbygoodid(215,$id);
			$data[waffenmodlvl] > 0 ? $pw += ($class[waffenmod_max]-$this->db->query("SELECT lvl FROM stu_ships_modules WHERE id=".$data[waffenmodlvl],1)) : $pw += 1 + ($class[waffenmod_max]-$class[waffenmod_min]);
			if ($data[reaktormodlvl] == 0 || $data[warpcore] == 0)
			{
				$dv = $this->db->query("SELECT count FROM stu_ships_storage WHERE goods_id=2 AND ships_id=".$data[id],1);
				$dv > $class[fusion]+$pw ? $pe = $class[fusion]+$pw : $pe = $dv;
				if ($data[reaktormodlvl] > 0 && $data[warpcore] == 0 && $dv > 0) $this->cmaxreaktor = $class[fusion]+$pw;
			}
			else
			{
				$rea = $this->getmodulebyid($data[reaktormodlvl]);
				$pe = $rea[reaktor]+$class[fusion];
				$pe += $pw;
				if ($pe > $data[warpcore]) $pe = $data[warpcore];
			}
			$this->cverbrauch = $erepl+($data[actscan]*5)+$data[kss]+$data[lss]+($data[cloak]*3)+$data[schilde_aktiv];
			$pe -= $this->cverbrauch;
			if ($data[energie]+$pe > $this->cmaxeps) $pe = $this->cmaxeps-$data[energie];
			$this->cerzeugung = $pe;
			if ($this->cverbrauch >= $this->cmaxreaktor)
			{
				$this->cerzeugung = ($this->cmaxreaktor-$this->cverbrauch);
				$this->cverbrauch = $this->cmaxreaktor;
			}
			if ($this->cerzeugung > $this->cmaxreaktor) $this->cerzeugung = $this->cmaxreaktor;
			$this->csecretimage = $class[secretimage];
		}
		else
		{
			$this->cshow = 0;
			return 0;
		}
	}
	
	function getshiplist($userId,$sort="",$way="")
	{
		if ($way != "") $this->db->query("UPDATE stu_user_profiles SET sl_sortway='".$way."' WHERE user_id=".$userId."");
		if ($sort != "") $this->db->query("UPDATE stu_user_profiles SET sl_sorttype='".$sort."' WHERE user_id=".$userId."");
		global $myUser;
		$sql = $myUser->getslsorting();
		return $this->db->query("SELECT * from stu_ships WHERE user_id='".$userId."' ORDER BY fleets_id DESC,".$sql);
	}
	
	function getDataById($shipId)
	{
		$data = $this->db->query("SELECT a.*,b.type,b.race,c.type as stype FROM stu_ships as a LEFT JOIN stu_map_fields as b ON a.coords_x=b.coords_x AND a.coords_y=b.coords_y AND a.wese=b.wese LEFT JOIN stu_map_special as c ON a.coords_x=c.coords_x AND a.coords_y=c.coords_y WHERE a.id='".$shipId."'",4);
		if ($data == 0) return 0;
		if ($data[cloak] == 1) $ecloak = 3;
		if ($data[replikator] == 1) $erepl = ceil($data[crew]/5);
		$data[name] = stripslashes($data[name]);
		$class = $this->getclassbyid($data[ships_rumps_id]);
		$module = $this->getmodulebyid($data[huellmodlvl]);
		$data[maxhuell] = $class[huellmod]*$module[huell];
		$module = $this->getmodulebyid($data[schildmodlvl]);
		$data[maxshields] = $class[schildmod]*$module[shields];
		$module = $this->getmodulebyid($data[epsmodlvl]);
		$data[maxeps] = $class[epsmod]*$module[eps];
		if ($data[waffenmodlvl] > 0)
		{
			$waff = $this->getmodulebyid($data[waffenmodlvl]);
			$plu = $class[waffenmod_max]-$waff[lvl];
			$data[maxphaser] = round($waff[phaser] * (1+(($class[waffenmod]-1)/3)));
			$data[maxtreffer] = $data[maxtreffer] + $waff[phaser_chance];
		}
		else $plu = 1 + ($class[waffenmod_max]-$class[waffenmod_min]);
		if ($data[warpcore] == 0 && $data[reaktormodlvl] != 0) unset($plu);
		if ($data[reaktormodlvl] != 0)
		{
			$module = $this->getmodulebyid($data[reaktormodlvl]);
			$data[maxreaktor] = $module[reaktor]+$class[fusion]+$plu;
		}
		else $data[maxreaktor] = $class[fusion]+$plu;
		$module = $this->getmodulebyid($data[computermodlvl]);
		$data[maxtreffer] += $module[phaser_chance];
		$data[maxausweichen] = $module[torp_evade];
		if ($data[antriebmodlvl] != 0)
		{
			$module = $this->getmodulebyid($data[antriebmodlvl]);
			$data[maxtreffer] += $module[phaser_chance];
			$data[maxausweichen] += $module[torp_evade];
		}
		$module = $this->getmodulebyid($data[sensormodlvl]);
		$data[maxlss] = $module[lss_range]+($class[sensormod]-1);
		$data[maxausweichen] = $data[maxausweichen] + $class[torp_evade];
		$data[fleets_id] > 0 ? $data[flname] = $this->db->query("SELECT name FROM stu_fleets WHERE id=".$data[fleets_id],1) : $data[flname] = "";
		$data[huelle] <= ($data[maxhuell] * 0.4) ? $data[damaged] = 1 : $data[damaged] = 0;
		$data[c] = $class;
		$data[waffenmodlvl] > 0 ? $pw += ($class[waffenmod_max]-$this->db->query("SELECT lvl FROM stu_ships_modules WHERE id=".$data[waffenmodlvl],1)) : $pw += 1 + ($class[waffenmod_max]-$class[waffenmod_min]);
		if ($data[reaktormodlvl] == 0 || $data[warpcore] == 0)
		{
			$dv = $this->db->query("SELECT count FROM stu_ships_storage WHERE goods_id=2 AND ships_id=".$data[id],1);
			$dv > $data[c][fusion]+$pw ? $pe = $data[c][fusion]+$pw : $pe = $dv;
			if ($data[reaktormodlvl] > 0 && $data[warpcore] == 0 && $dv > 0) $data[maxreaktor] = $data[c][fusion]+$pw;
		}
		else
		{
			$rea = $this->getmodulebyid($data[reaktormodlvl]);
			$pe = $rea[reaktor]+$class[fusion];
			$pe += $pw;
			if ($pe > $data[warpcore]) $pe = $data[warpcore];
		}
		$data[verbrauch] = $erepl+($data[actscan]*5)+$data[kss]+$data[lss]+($data[cloak]*3)+$data[schilde_aktiv];
		$pe -= $data[verbrauch];
		if ($data[energie]+$pe > $data[maxeps]) $pe = $data[maxeps]-$data[energie];
		$data[erzeugung] = $pe;
		if ($data[verbrauch] >= $data[maxreaktor])
		{
			$data[erzeugung] = ($data[maxreaktor]-$data[verbrauch]);
			$data[verbrauch] = $data[maxreaktor];
		}
		if ($data[erzeugung] > $data[maxreaktor]) $data[erzeugung] = $data[maxreaktor];
		return $data;
	}
	
	function move($shipId,$x,$y,$userId,$fleetmove="",$red=1)
	{
		$data = $this->getdatabyid($shipId);
		if ($data[user_id] != $userId) return 0;
		if ($data[coords_x] == $x && $data[coords_y] == $y) return 0;
		if ($data[c][slots] > 0) return 0;
		if ($data[energie] == 0 && $this->traked == 0)
		{
			if ($data[fleets_id] > 0) if ($this->db->query("SELECT ships_id FROM stu_fleets WHERE id=".$data[fleets_id],1) != $data[id]) $this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE id=".$data[id]);
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($this->traked == 0)
		{
			$ac = $this->checkss("impdef",$shipId);
			if ($ac > time())
			{
				$return[msg] = "Der Antrieb ist ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$ac)." Uhr";
				return $return;
			}
			elseif ($ac > 0 && $ac < time()) $this->repairssd("impdef",$shipId);
		}
		if ($userId != 17 && $data[c][probe] == 0 && $this->traked == 0)
		{
			if ($data[c][crew_min] - 2 > $data[crew])
			{
				$return[msg] = "Zum fliegen werden mindestens ".($data[c][crew_min] - 2)." Crewmitglieder benötigt";
				if ($fleetmove != 0)
				{
					$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE id=".$data[id]);
					$return[msg] .= "<br>Die ".stripslashes($data[name])." hat sich auf der Flotte gelöst";
				}
				return $return;
			}
			if ($data[crew] < 2)
			{
				$return[msg] = "Zum fliegen werden mindestens 2 Crewmitglieder benötigt";
				return $return;
			}
		}
		if ($data[dock] > 0)
		{
			if ($data[fleets_id] > 0) $this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE id=".$data[id]);
			$return[msg] = "Schiff ist angedockt";
			return $return;
		}
		global $myMap,$myComm,$myHistory,$myColony,$myUser;
		if ($data[stype] == 4 && $data[user_id] != 22)
		{
			if (rand(1,100) >= 2*$data[maxausweichen])
			{
				$return[msg] = "Die ".$data[name]." konnte nicht aus dem Energienetz entkommen";
				$this->db->query("UPDATE stu_ships SET energie=energie-1,fleets_id=0 WHERE id=".$data[id]);
				if ($data[fleets_id] > 0)
				{
					if ($this->db->query("SELECT ships_id FROM stu_fleets WHERE id=".$data[fleets_id],1) == $data[id])
					{
						$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE fleets_id=".$data[fleets_id]);
						$this->db->query("DELETE FROM stu_fleets WHERE id=".$data[fleets_id]);
					}
				}
				return $return;
			}
		}
		if ($data[coords_x] != $x && $data[coords_y] != $y) return 0;
		if ($data[coords_x] == $x) if ($data[coords_y] + 1 != $y && $data[coords_y] - 1 != $y) return 0;
		if ($data[coords_y] == $y) if ($data[coords_x] + 1 != $x && $data[coords_x] - 1 != $x) return 0;
		$cd = $myMap->getfieldbycoords($x,$y,$data[wese]);
		if ($cd == 0) return 0;
		if ($data[race] != $cd[race] && $cd[race] != 0)
		{
			$raceenter = "<tr><td class=tdmainobg></td><td class=tdmainobg><img src=".$this->gfx."/map/r".$cd[race].".gif></td>";
			if ($cd[race] == 15)
			{
				$raceenter .= "<td class=tdmainobg>Schiff fliegt in den Raum der Breen ein</td></tr>";
				if (($userId > 100) && ($data[cloak] == 0)) $myComm->sendpm($cd[race],2,"Perimeteralarm: Die ".$data[name]." von ".$myUser->uuser." (".$data[user_id].") hat bei ".$x."/".$y." die Grenze durchbrochen",2);
			}
			if ($cd[race] == 10) $raceenter .= "<td class=tdmainobg>Schiff fliegt in den Raum der Föderation ein</td></tr>";
			if ($cd[race] == 24) $raceenter .= "<td class=tdmainobg>Schiff fliegt in den Raum der Verekkianer ein</td></tr>";
			if ($cd[race] == 11)
			{
				$raceenter .= "<td class=tdmainobg>Schiff fliegt in den Raum der Romulaner ein</td></tr>";
				if ($userId > 100) $myComm->sendpm($cd[race],2,"Perimeteralarm: Die ".$data[name]." von ".$myUser->uuser." (".$data[user_id].") hat bei ".$x."/".$y." die Grenze durchbrochen",2);
			}
			if ($cd[race] == 27)
			{
				$raceenter .= "<td class=tdmainobg>Schiff fliegt in den Raum der Kzinti Hegemonie ein</td></tr>";
				if ($userId > 100) $myComm->sendpm($cd[race],2,"Perimeteralarm: Die ".$data[name]." von ".$myUser->uuser." (".$data[user_id].") hat bei ".$x."/".$y." die Grenze durchbrochen",2);
			}
			if ($cd[race] == 99) $raceenter .= "<td class=tdmainobg>Schiff fliegt in die Neutrale Zone ein</td></tr>";
		}
		else $raceenter = "";
		if ($cd[race] == 15 && $data[cloak] == 1)
		{
			if (($this->db->query("SELECT count(id) FROM stu_ships WHERE coords_x BETWEEN ($x-1) AND ($x+1) AND coords_y BETWEEN ($y-1) AND ($y+1) AND ships_rumps_id=189",1) != 0) AND ($this->db->query("SELECT id FROM stu_ships_action WHERE ships_id = ".$data[id]." AND mode='borderdet' AND ships_id2 = 15",1) == 0))
			{
				$this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$data[id]."','borderdet','15')");
				$raceenter .= "<tr><td class=tdmainobg></td><td class=tdmainobg><img src=".$this->gfx."/ships/189.gif></td><td class=tdmainobg>Das Schiff wurde aufgespürt</td></tr>";
				if ($userId > 100) $myComm->sendpm($cd[race],2,"Annäherungsalarm: Die ".$data[name]." von ".$myUser->uuser." (".$data[user_id].") wurde bei ".$x."/".$y." entdeckt",2);
			}
		}
		elseif (($cd[race] == 0) && ($this->db->query("SELECT id FROM stu_ships_action WHERE ships_id = ".$data[id]." AND mode='borderdet' AND ships_id2 = 15",1) != 0))
		{
			$this->db->query("DELETE FROM stu_ships_action WHERE ships_id = ".$data[id]." AND mode='borderdet' AND ships_id2 = 15");
		}
		if ($data[traktor] != 0 && $data[traktormode] == 1)
		{
			if ($data[energie] > 1)
			{
				$energie += 1;
				$trw = 1;
				$data2 = $this->getdatabyid($data[traktor]);
				$this->traked = 1;
				$trak = $this->move($data2[id],$x,$y,$data2[user_id],0,1);
				$this->traked = 0;
				$trak_msg = "Die ".$data2[name]." wird mit dem Traktorstrahl hinterhergezogen<br>".$trak[msg];
				if ($data[user_id] != $data2[user_id]) $trpm = 1;
			}
			else
			{
				$trw = 0;
				$trak_msg = " - Nicht genügend Energie, Traktorstrahl abgerissen";
				$this->db->query("UPDATE stu_ships SET traktor=0,traktormode=0 WHERE id=".$shipId." OR id=".$data[traktor]);
			}
		}
        if ($data[traktor] != 0 && $data[traktormode] == 2)
		{
			$data2 = $this->getdatabyid($data[traktor]);
			if ($data2[energie] > 0 && $data2[user_id] != $data[user_id] && $this->traked == 0)
			{
				$trak_msg = "Kann nicht von Traktorstrahl der ".$data2[name]." lösen";
				$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$data2[id]);
				$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$data[id]." AND energie>0",$this->dblink);
				if ($data2[user_id] != $userId) $myComm->sendpm($data2[user_id],$data[user_id],"Die ".$data[name]." hat versucht sich vom Traktorstrahl der ".$data2[name]." zu lösen",2);
				$return[msg] = "Die ".$data[name]." kann sich nicht vom Traktorstrahl der ".$data2[name]." lösen";
				return $return;
			}
			elseif ($data2[energie] == 0 && $data2[user_id] != $data[user_id])
			{
				if ($data[user_id] != $data2[user_id]) $myComm->sendpm($data2[user_id],$data[user_id],"Die ".$data[name]." hat sich vom Traktorstrahl der ".$data2[name]." gelöst",2);
				$trak_msg = "Vom Traktorstrahl der ".$data2[name]." gelöst";
				$this->db->query("UPDATE stu_ships SET traktor=0,traktormode=0 WHERE id=".$data[id]." OR id=".$data2[id]);
				$tl = 1;
			}
			if ($data2[user_id] == $data[user_id] && $this->traked == 0)
			{
				$trak_msg = "Vom Traktorstrahl der ".$data2[name]." gelöst";
				$this->db->query("UPDATE stu_ships SET traktormode=0,traktor=0 WHERE id=".$data2[id]." OR id=".$data[id]);
				$tl = 1;
			}
		}
		$this->db->query("UPDATE stu_ships SET coords_x=".$x.",coords_y=".$y." WHERE id=".$shipId." AND user_id=".$userId);
		$this->db->query("DELETE FROM stu_ships_action WHERE mode='defend' AND (ships_id=".$shipId." OR ships_id2=".$shipId.")");
		if ($this->fmsg == 0) $msg = "Die ".stripslashes($data[name])." fliegt in Sektor ".$x."/".$y." ein";
		if ($this->traked == 0) $energie += 1;
		$sa = $data[schilde_aktiv];
		$huelle = $data[huelle];
		$crew = $data[crew];
		$schilde = $data[schilde];
		$cl = $data[cloak];
		$ks = $data[kss];
		$ls = $data[lss];
		if ($cd[type] == 2)
		{
			$msg .= "<br>Feldtyp: Nebel";
			$energie += 1;
			if ($data[energie] < $energie)
			{
				$sa = 0;
				$cl = 0;
				$huelle -= 1;
			}
		}
		if ($cd[type] == 21)
		{
			$msg .= "<br>Feldtyp: Ceruleanischer Nebel";
			$sa = 0;
			$cl = 0;
		}
		if ($cd[type] == 3 || $cd[type] == 28 || $cd[type] == 16 || $cd[type] == 30)
		{
			$msg .= "<br>Feldtyp: Nebel";
			if ($data[c][probe]  == 1) 
			{
				$return[msg] = $msg."<br>Der Kontakt zur Sonde ist abgerissen";
				$this->trumfield($data[id]);
				return $return;
			}
			$energie += 2;
			if ($data[energie] < $energie) $huelle -= 2;
			$sa = 0;
			$cl = 0;
			$ks = 0;
			$ls = 0;
		}
		if ($cd[type] == 4 || $cd[type] == 17 || $cd[type] == 19)
		{
			$msg .= "<br>Feldtyp: Asteroidenfeld";
			$energie += 1;
			$data[energie] < $energie ? $dam = 1 : $dam = 0;
			if ($sa == 1)
			{
				$schilde -= $dam;
				if ($schilde < 0)
				{
					$huelle -= abs($schilde);
					$sa = 0;
				}
			}
			else $huelle -= $dam;
			if ($huelle < 1) $myHistory->addEvent("Die ".addslashes($data[name])." wurde durch einen Asteroiden zerstört",$userId);
		}
		if ($cd[type] == 5 || $cd[type] == 18 || $cd[type] == 20 || $cd[type] == 27 || $cd[type] == 32)
		{
			$msg .= "<br>Feldtyp: Asteroidenfeld";
			$energie += 2;
			$data[energie] < $energie ? $dam = 2 : $dam = 0;
			if ($sa == 1)
			{
				$schilde -= $dam;
				if ($schilde < 0)
				{
					$huelle -= abs($schilde);
					$sa = 0;
				}
			}
			else $huelle -= $dam;
		}
		if ($cd[type] == 11)
		{
			$msg .= "<br>Feldtyp: Schwarzes Loch";
			$energie += 2;
			$dam = rand(1,9);
			if ($sa == 1)
			{
				$schilde -= $dam;
				if ($schilde < 0)
				{
					$huelle -= abs($schilde);
					$sa = 0;
				}
			}
			else $huelle -= $dam;
		}
		if ($cd[type] == 13)
		{
			if ($data[c][probe]  == 1) 
			{
				$return[msg] = "Kontakt zur Sonde ist abgerissen";
				$this->trumfield($data[id]);
				return $return;
			}
			$energie += 1;
			if ($this->fmsg == 0) $msg .= "<br>Feldtyp: Röntgenpulsar";
			$sa = 0;
			$cl = 0;
			$ks = 0;
			$ls = 0;
		}
		if ($cd[type] == 14)
		{
			$energie += 1;
			$msg .= "<br>Feldtyp: Neutronenstern";
			if ($sa == 1)
			{
				$crewkill = rand(0,1);
				$dam = rand(1,5);
				$schilde -= $dam;
				if ($schilde < 0)
				{
					$huelle -= abs($schilde);
					$sa = 0;
				}
			}
			else
			{
				if ($this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$data[huellmodlvl],1) != "Isolierend")
				{
					$crewkill = round($crew/4);
					$crewkill += rand(0,3);
				}
			}
		}
		if ($cd[type] == 22)
		{
			$msg .= "<br>Feldtyp: Subraumspalt";
			$this->trumfield($shipId);
			$myHistory->addEvent("Die ".addslashes($data[name])." wurde in Sektor ".$data[coords_x]."/".$data[coords_y].($data[wese] == 2 ? " (2)" : "")." in einen Subraumspalt gezogen und ist verschollen",$data[user_id]);
			$return[msg] = $msg."<br>Die ".$data[name]." wurde in einen Sumbraumspalt gezogen und ist verschollen";
			return $return;
		}
		if ($cd[type] == 31 && $data[sensormodlvl] != 52 && $data[huellmodlvl] != 54)
		{
			if ($data[c][probe]  == 1) 
			{
				$return[msg] = "Kontakt zur Sonde ist abgerissen";
				$this->trumfield($data[id]);
				return $return;
			}
			if ($this->fmsg == 0) $msg .= "<br>Feldtyp: Mutaranebel";
			$energie += 2;
			$data[energie] < $energie ? $dam = 2 : $dam = 0;
			$huelle -= $dam;
			$red = 0;
			$sa = 0;
			$cl = 0;
			$ks = 0;
			$ls = 0;
		}
		if ($cd[type] == 31 && $data[huellmodlvl] == 54)
		{
			$msg .= "<br>Feldtyp: Mutaranebel";
			$mh = $this->db->query("SELECT huell FROM stu_ships_modules WHERE id=".$data[huellmodlvl],1)*$data[c][huellmod];
			if ($huelle < $mh)
			{
				$rand = rand(1,3);
				if ($huelle + $rand > $mh) $rand = $mh - $huelle;
				$huelle += $rand;
				if ($rand != 0) $msg .= "<br>Regeneration: Hülle um ".$rand." Punkte regeneriert";
			}
			$red = 0;
		}
		if ($cd[type] == 15)
		{
			$msg .= "<br>Feldtyp: Quasar";
			$energie += 2;
			$data[energie] < $energie ? $dam = 2 : $dam = 0;
			if ($sa == 1)
			{
				$schilde -= $dam;
				if ($schilde < 0)
				{
					$huelle -= abs($schilde);
					$sa = 0;
				}
			}
			else $huelle -= $dam;
			$red = 0;
			$ls = 0;
			$ks = 0;
			$cl = 0;
		}
		if (($cd[type] >= 34) && ($cd[type] <= 39))
		{
			$msg .= "<br>Feldtyp: Subraumsandbank";
			$energie += 1;
			$data[energie] < $energie ? $dam = 2 : $dam = 0;
			if ($sa == 1)
			{
				$schilde -= $dam;
				if ($schilde < 0)
				{
					$huelle -= abs($schilde);
					$sa = 0;
				}
			}
			else $huelle -= $dam;
			$red = 0;
			$cl = 0;
			$msg .= "<br>Schiff sitzt in der Sandbank fest - Antrieb ausgefallen";
			$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$data[id]." AND mode='impdef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+86400)."' WHERE ships_id =".$data[id]." AND mode='impdef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$data[id]."','impdef','".(time()+86400)."')");
		}
		$tcheck = $myMap->getMapSectorSpecialType($x,$y,$data[wese]);
		if ($tcheck[type] == 1)
		{
			$msg .= "<br>Es befindet sich ein Ionensturm in diesem Sektor";
			if ($cl == 1)
			{
				$this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$data[id]."','tardef','".(time()+14400)."')");
				$cl = 0;
			}
			if ($this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$data[huellmodlvl],1) != "Isolierend")
			{
				$dam = rand(1,5);
				if ($sa == 1)
				{
					$schilde -= $dam;
					if ($schilde < 0)
					{
						$huelle -= abs($schilde);
						$sa = 0;
					}
				}
				else $huelle -= $dam;
			}
		}
		elseif ($tcheck[type] == 2)
		{
			$msg .= "<br>Es befindet sich ein Plasmasturm in diesem Sektor";
			$energie += 1;
			$data[energie] < $energie ? $dam = rand(4,15) : $dam = rand(1,10);
			if ($sa == 1)
			{
				$schilde -= $dam;
				if ($schilde < 0)
				{
					$huelle -= abs($schilde);
					$sa = 0;
				}
			}
			else $huelle -= $dam;
			$red = 0;
			$ls = 0;
			$ks = 0;
			$cl = 0;
		}
		elseif ($tcheck[type] == 3)
		{
			$msg .= "<br>Es wurde eine Neutronische Wellenfront in diesem Sektor festgestellt";
			if ($cl == 1)
			{
				$this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$data[id]."','tardef','".(time()+14400)."')");
				$cl = 0;
			}
			if ($this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$data[huellmodlvl],1) != "Isolierend")
			{
				$crewkill = round($crew/5);
				$crewkill += rand(0,3);
				if ($sa == 1) $crewkill = round($crewkill/2);
			}
			else $crewkill = rand(0,1);
		}
		elseif ($tcheck[type] == 4 && $data[user_id] != 22)
		{
			$msg .= "<br>Schiff ist in einem tholianischen Energienetz gefangen";
			//-----Impuls
			$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$data[id]." AND mode='impdef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+3600)."' WHERE ships_id =".$data[id]." AND mode='impdef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$data[id]."','impdef','".(time()+3600)."')");
			//------Schilde
			$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$data[id]." AND mode='shidef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+2400)."' WHERE ships_id =".$data[id]." AND mode='shidef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$data[id]."','shidef','".(time()+1200)."')");
			//------Waffen
			$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$data[id]." AND mode='waffdef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+1200)."' WHERE ships_id =".$data[id]." AND mode='waffdef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$data[id]."','waffdef','".(time()+1200)."')");
			//------Tarnung
			$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$data[id]." AND mode='cloakdef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+2400)."' WHERE ships_id =".$data[id]." AND mode='cloakdef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$data[id]."','cloakdef','".(time()+1200)."')");
			$cl = 0;
			$sa = 0;
		}
		if ($userId != 16 && $this->db->query("SELECT behaviour FROM stu_contactlist WHERE user_id=16 AND recipient=".$userId,1) != 1)
		{
			$mfd = $this->db->query("SELECT id,huelle FROM stu_ships WHERE coords_x=".$x." AND coords_y=".$y." ANd ships_rumps_id=154",4);
			if ($mfd != 0)
			{
				$r = rand(1,3);
				if ($this->fmsg == 0) $schadenmsg .= "<br>Es wurde ein Minenfeld in diesem Sektor festgesstellt";
				if ($mfd[huelle] < $r) $r = $mfd[huelle];
				$dam = $r * 4;
				if ($sa == 1)
				{
					$schilde -= $dam;
					if ($schilde < 0)
					{
						$huelle -= abs($schilde);
						$sa = 0;
					}
				}
				else $huelle -= $dam;
				if ($mfd[huelle] == $r)
				{
					$this->db->query("DELETE FROM stu_ships WHERE id=".$mfd[id]);
					$msg .= "<br>Das Minenfeld wurde beseitigt";
				}
				else $this->db->query("UPDATE stu_ships SET huelle=huelle-".$r." WHERE id=".$mfd[id]);
				$cl = 0;
			}
		}
		if ($data[cloak] == 0 && $cd[type] != 31)
		{
			$res = $this->db->query("SELECT id,name,user_id FROM stu_ships WHERE ships_rumps_id=88 AND computermodlvl=44 AND actscan=1 AND lss=1 AND wese=".$data[wese]." AND user_id!=".$data[user_id]." AND ((sensormodlvl=36 AND coords_x BETWEEN ".($x-7)." AND ".($x+7)." AND coords_y BETWEEN ".($y-7)." AND ".($y+7).") OR (sensormodlvl=35 AND coords_x BETWEEN ".($x-6)." AND ".($x+6)." AND coords_y BETWEEN ".($y-6)." AND ".($y+6).") OR (sensormodlvl=34 AND coords_x BETWEEN ".($x-5)." AND ".($x+5)." AND coords_y BETWEEN ".($y-5)." AND ".($y+5).") OR (sensormodlvl=33 AND coords_x BETWEEN ".($x-4)." AND ".($x+4)." AND coords_y BETWEEN ".($y-4)." AND ".($y+4)."))");
			if (mysql_num_rows($res) != 0 )
			{
				while($spd=mysql_fetch_assoc($res))
				{
					$aff = $this->db->query("UPDATE stu_sensor_detects SET coords_x=".$x.",coords_y=".$y.",date=NOW() WHERE phalanx_id=".$spd[id]." AND ships_id=".$data[id],6);
					if ($aff == 0)
					{
						if ($this->db->query("SELECT behaviour FROM stu_contactlist WHERE user_id=".$spd[user_id]." AND recipient=".$this->user,1) == 2 && $myColony->getuserresearch(232,$spd[user_id]) != 0) $myComm->sendpm($spd[user_id],2,"Die Sensorenphalanx ".stripslashes($spd[name])." meldet ein Schiff des Users ".$myUser->getfield("user",$userId)." in Sensorenreichweite",2);
						$this->db->query("INSERT INTO stu_sensor_detects (phalanx_id,ships_id,ships_rumps_id,user_id,coords_x,coords_y,date) VALUES ('".$spd[id]."','".$data[id]."','".$data[ships_rumps_id]."','".$data[user_id]."','".$x."','".$y."',NOW())");
					}
				}
			}
		}
		if ($data[energie] - $energie < 0)
		{
			$energie = 0;
			$amsg .= " Deflektor";
		}
		else $energie = $data[energie] - $energie;
		if ($data[schilde_aktiv] == 1 && $sa == 0) $amsg .= " Schilde";
		if ($data[cloak] == 1 && $cl == 0) $amsg .= " Tarnung";
		if (($data[lss] == 1 && $ls == 0) || ($data[kss] == 1 && $ks == 0)) $amsg .= " Sensoren";
		if ($sa == 1 && $schilde > 0 && $data[schilde] != $schilde) $msg .= "<br>Schildschaden: ".($data[schilde]-$schilde)." - Schilde bei ".$schilde;
		if ($sa == 0 && $data[schilde_aktiv] == 1 && $data[schilde] != $schilde) $msg .= "<br>Schildeschaden: ".$data[schilde]." - Schilde zusammengebrochen";
		if ($huelle < $data[huelle] && $huelle > 0) $msg .= "<br>Hüllenschaden: ".($data[huelle]-$huelle)." - Hülle bei ".$huelle;
		if ($huelle < 1)
		{
			$this->trumfield($shipId);
			$destroy = 1;
			$red = 0;
			$msg .= "<br>Die ".stripslashes($data[name])." wurde beim Einflug in den Sektor zerstört";
			$this->cshow == 0;
		}
		if ($crewkill > 0)
		{
			if ($crewkill > $crew) $crewkill = $crew;
			$crew -= $crewkill;
			$msg .= "<br>Strahlung: ".($crewkill)." Crewmitglieder sterben";
			if ($data[c][crew_min] > $crew)
			{
				$cl = 0;
				$sa = 0;
				$msg .= "<br>Systemausfälle durch fehlende Besatzung";
			}
			if ($data[c][crew_min] - 2 > $crew)
			{
				$ls = 0;
				$ks = 0;
			}
		}
		if ($destroy == 0) $this->db->query("UPDATE stu_ships SET energie=".$energie.",schilde_aktiv=".$sa.",schilde=".$schilde.",huelle=".$huelle.",cloak=".$cl.",lss=".$ls.",kss=".$ks.",crew=".$crew." WHERE id=".$shipId);
		if ($amsg) $msg .= "<br>Systeme ausgefallen:".$amsg;
		if ($red == 1 && $data[cloak] == 0 && $data[ships_rumps_id] != 176 && $data[ships_rumps_id] != 177) $ra = $this->redalert($x,$y,$shipId,0,$data[wese],$userId);
		if ($tl != 1 && $data[traktormode] == 2) $energie = 0;
		$cldat = $this->db->query("SELECT id FROM stu_colonies WHERE user_id!=2 AND user_id!=".$userId." AND coords_x=".$x." AND coords_y=".$y." AND wese=".$data[wese],1);
		if ($cldat != 0 && $cl == 0) $this->db->query("INSERT INTO stu_sector_flights (ships_rumps_id,colonies_id,user_id,date) VALUES ('".$data[c][id]."','".$cldat."','".$userId."',NOW())");
		if ($data[damaged] == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif></td>
			<td class=tdmainobg><img src=".$this->gfx."/map/".$cd[type].".gif></td>
			<td class=tdmainobg>";
		global $myFleet;
		if ($data[fleets_id] > 0) $fleet = $myFleet->getfleetbyshipid($shipId,$userId);
		if ($data[fleets_id] > 0 && $fleet == 0 && $fleetmove == 0)
		{
			$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE id=".$shipId);
			$fleetmsg = "Die ".$data[name]." hat sich aus der Flotte gelöst<br>";
		}
		$return[msg] .= $fleetmsg.$msg;
		$return[code] = 1;
		if ($trak_msg) $return[msg] .= "<br>".$trak_msg;
		if ($ra != 0 || $ra != "") $return[msg] .= "<br>".$ra[msg];
		if ($trpm == 1) $myComm->sendpm($data2[user_id],$data[user_id],"Die ".$data2[name]." wird von der ".$data[name]." per Traktorstrahl in Sektor ".$x."/".$y." gezogen",2);
		$this->db->query("DELETE FROM stu_ships_uncloaked WHERE ships_id=".$shipId);
		$return[msg] .= "</td></tr>".$raceenter."</table>";
		return $return;
	}
	
	function moveap($shipId,$way,$fields,$userId,$fleetmove="",$red=1)
	{
		if ($fields > 9 || $fields < 1) return -1;
		$data = $this->db->query("SELECT coords_x,coords_y,wese from stu_ships WHERE id='".$shipId."' AND user_id='".$userId."'",4);
		if ($data == 0) return 0;
		if ($data[wese] == 1)
		{
			global $mapfields;
			$maxx = $mapfields[max_x];
			$maxy = $mapfields[max_y];
		}
		elseif ($data[wese] == 2)
		{
			global $mapfields2;
			$maxx = $mapfields2[max_x];
			$maxy = $mapfields2[max_y];
		}
		elseif ($data[wese] == 3)
		{
			global $mapfields3;
			$maxx = $mapfields3[max_x];
			$maxy = $mapfields3[max_y];
		}
		global $user;
		if ($way == "hoch")
		{
			if ($data[coords_y] - $fields < 1) return 0;
			else
			{
				for ($i=1;$i<=$fields;$i++)
				{
					$result = $this->move($shipId,$data[coords_x],($data[coords_y] - $i),$userId,$fleetmove,$red);
					$return[msg] .= strip_tags($result[msg],"<br>")."<br>";
					if ($result[code] < 1) break;
				}
				return $return;
			}
		}
		elseif ($way == "runter")
		{
			if ($data[coords_y] + $fields > $maxy) return 0;
			else
			{
				for ($i=1;$i<=$fields;$i++)
				{
					$result = $this->move($shipId,$data[coords_x],($data[coords_y] + $i),$userId,$fleetmove,$red);
					$return[msg] .= strip_tags($result[msg],"<br>")."<br>";
					if ($result[code] < 1) break;
				}
				return $return;
			}
		}
		elseif ($way == "links")
		{
			if ($data[coords_x] - $fields < 1) return 0;
			else
			{
				for ($i=1;$i<=$fields;$i++)
				{
					$result = $this->move($shipId,($data[coords_x] - $i),$data[coords_y],$userId,$fleetmove,$red);
					$return[msg] .= strip_tags($result[msg],"<br>")."<br>";
					if ($result[code] < 1) break;
				}
				return $return;
			}
		}
		elseif ($way == "rechts")
		{
			if ($data[coords_x] + $fields > $maxx) return 0;
			else
			{
				for ($i=1;$i<=$fields;$i++)
				{
					$result = $this->move($shipId,($data[coords_x] + $i),$data[coords_y],$userId,$fleetmove,$red);
					$return[msg] .= strip_tags($result[msg],"<br>")."<br>";
					if ($result[code] < 1) break;
				}
				return $return;
			}
		}
	}
	
	function activatevalue($shipId,$value,$userId)
	{
		$data = $this->db->query("SELECT a.ships_rumps_id,a.huelle,a.schilde,a.dock,a.energie,a.crew,a.schilde_aktiv,a.cloak,a.kss,a.lss,a.traktormode,a.sensormodlvl,a.huellmodlvl,a.computermodlvl,a.actscan,b.crew_min,b.cloak as ccloak,b.huellmod,b.replikator as creplikator,c.type as mtype FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_map_fields as c ON a.coords_x=c.coords_x AND a.coords_y=c.coords_y AND a.wese=c.wese WHERE a.id=".$shipId,4);
		if ($data == 0) return 0;
		if ($value == "cloak")
		{
			if ($data[ccloak] == 0)
			{
				$return[msg] = "Das Schiff besitzt keine Tarnungsfunktion";
				return $return;
			}
			if ($data[dock] > 0)
			{
				$return[msg] = "Das Schiff ist angedockt";
				return $return;
			}
			$cc = $this->checkss("cloakdef",$shipId);
			if ($cc > time())
			{
				$return[msg] = "Die Tarnung ist ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$cc)." Uhr";
				return $return;
			}
			elseif ($cc > 0 && $cc < time()) $this->repairssd("cloakdef",$shipId);
			if ($data[mtype] == 31 || $data[mtype] == 15)
			{
				$return[msg] = "Die Tarnung kann aufgrund eines stellaren Phänomens nicht aktiviert werden";
				return $return;
			}
			if ($data[mtype] == 2 || $data[mtype] == 3 || $data[mtype] == 16 || $data[mtype] == 28 || $data[mtype] == 30)
			{
				$return[msg] = "Die Tarnung kann aufgrund des Nebels nicht aktiviert werden";
				return $return;
			}
			if ($data[energie] < 1)
			{
				$return[msg] = "Zum aktivieren der Tarnung ist mindestens 1 Energie erforderlich";
				return $return;
			}
			if ($data[crew_min] > $data[crew])
			{
				$return[msg] = "Zum aktivieren der Tarnung sind ".$data[crew_min]." Crewmitglieder erforderlich";
				return $return;
			}
			$h = $this->getmodulebyid($data[huellmodlvl]);
			$mh = $h[huell]*$data[huellmod];
			if (floor((100/$mh)*$data[huelle]) < 5)
			{
				$return[msg] = "Das Schiff ist zu sehr beschädigt um die Tarnung zu aktivieren";
				return $return;
			}
			if ($data[schilde_aktiv] == 1)
			{
				$return[msg] = "Die Tarnung kann nicht aktiviert werden da die Schilde aktiviert sind";
				return $return;
			}
			if ($data[traktormode] == 1)
			{
				$return[msg] = "Der Traktorstrahl ist aktiviert";
				return $return;
			}
			if ($data[traktormode] == 2)
			{
				$return[msg] = "Das Schiff wird von einem Traktorstrahl gehalten";
				return $return;
			}
		}
		if ($value == "actscan")
		{
			if ($data[ships_rumps_id] != 88 || $data[computermodlvl] != 44) return 0;
			if ($data[actscan] == 1) return 0;
			if ($data[lss] == 0)
			{
				$return[msg] = "De Langstreckensensoren sind nicht aktiviert";
				return $return;
			}
			if ($data[energie] < 5)
			{
				$return[msg] = "Um den Active-Scan Modus zu aktivieren werden 5 Energie benötigt";
				return $return;
			}
		}
		if ($value == "lss")
		{
			if ($data[crew_min] - 2 > $data[crew] && $data[ships_rumps_id] != 88)
			{
				$return[msg] = "Zum aktivieren der Langstreckensensoren sind ".($data[crew_min] - 2)." Crewmitglieder erforderlich";
				return $return;
			}
			if ($data[energie] < 1)
			{
				$return[msg] = "Zum aktivieren der Langstreckensensoren ist mindestens 1 Energie erforderlich";
				return $return;
			}
			$lc = $this->checkss("lsendef",$shipId);
			if ($lc > time())
			{
				$return[msg] = "Die Langstreckensensoren sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$lc)." Uhr";
				return $return;
			}
			elseif ($lc > 0 && $lc < time()) $this->repairssd("lsendef",$shipId);
		}
		if ($value == "kss")
		{
			global $myUser;
			if ($myUser->ulevel < 2)
			{
				$return[msg] = "Diese Funktion steht erst ab Level 2 zur Verfügung";
				return $return;
			}
			if ($data[crew_min] - 2 > $data[crew] && $data[ships_rumps_id] != 88)
			{
				$return[msg] = "Zum aktivieren der Kurzstreckensensoren sind ".($data[crew_min] - 2)." Crewmitglieder erforderlich";
				return $return;
			}
			if ($data[energie] < 1)
			{
				$return[msg] = "Zum aktivieren der Kurzstreckensensoren ist mindestens 1 Energie erforderlich";
				return $return;
			}
			$kc = $this->checkss("ksendef",$shipId);
			if ($kc > time())
			{
				$return[msg] = "Die Kurzstreckensensoren sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$kc)." Uhr";
				return $return;
			}
			elseif ($value == "kss" && $kc > 0 && $kc < time()) $this->repairssd("ksendef",$shipId);
		}
		if (($value == "kss" || $value == "lss") && $data[mtype] == 31 && $data[sensormodlvl] != 52)
		{
			$return[msg] = "Aufgrund des Mutaranebels können die Sensoren nicht aktiviert werden";
			return $return;
		}
		if (($value == "kss" || $value == "lss") && $data[mtype] == 15 && $data[sensormodlvl] != 52)
		{
			$return[msg] = "Aufgrund des Quasars können die Sensoren nicht aktiviert werden";
			return $return;
		}
		if ($value == "schilde_aktiv")
		{
			if ($data[energie] < 1)
			{
				$return[msg] = "Zum aktivieren der Schilde ist mindestens 1 Energie erforderlich";
				return $return;
			}
			if ($data[schilde] < 1)
			{
				$return[msg] = "Zum aktivieren der Schilde müssen die Schildemitter aufgeladen werden";
				return $return;
			}
			if ($data[dock] > 0)
			{
				$return[msg] = "Das Schiff ist angedockt";
				return $return;
			}
			if ($data[mtype] == 13)
			{
				$return[msg] = "Die Schilde können aufgrund des Röntgenpulsars nicht aktiviert werden";
				return $return;
			}
			if ($data[mtype] == 21)
			{
				$return[msg] = "Die Schilde können aufgrund des ceruleanischen Nebels nicht aktiviert werden";
				return $return;
			}
			if ($data[mtype] == 2 || $data[mtype] == 3 || $data[mtype] == 16 || $data[mtype] == 28 || $$data[mtype] == 30 || $data[mtype] == 31)
			{
				$return[msg] = "Die Schilde können aufgrund des Nebels nicht aktiviert werden";
				return $return;
			}
			if ($data[traktormode] == 1)
			{
				$return[msg] = "Der Traktorstrahl ist aktiviert";
				return $return;
			}
			if ($data[traktormode] == 2)
			{
				$return[msg] = "Das Schiff wird von einem Traktorstrahl gehalten";
				return $return;
			}
			if ($data[cloak] == 1)
			{
				$return[msg] = "Die Tarnung ist aktiviert";
				return $return;
			}
			if ($data[crew_min] - 1 > $data[crew] && $data[ships_rumps_id] != 88)
			{
				$return[msg] = "Zum aktivieren der Schilde sind ".($data[crew_min] - 1)." Crewmitglieder erforderlich";
				return $return;
			}
			$cs = $this->checkss("shidef",$shipId);
			if ($cs > time())
			{
				$return[msg] = "Die Schilde sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$cs)." Uhr";
				return $return;
			}
			elseif ($cs > 0 && $cs < time()) $this->repairssd("shidef",$shipId);
			$loadtime = $this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE mode='sload' AND ships_id=".$shipId,1);
			if ($loadtime > 0)
			{
				if (time()-10800 < $loadtime)
				{
					$return[msg] = "Die Schilde sind polarisiert und können frühestens um ".date("H:i",$loadtime+10800)." Uhr wieder aktiviert werden";
					return $return;
				}
				$this->db->query("DELETE FROM stu_ships_action WHERE mode='sload' AND ships_id=".$shipId);
			}
			$this->db->query("UPDATE stu_ships SET dock=0 WHERE dock=".$shipId);
		}
		if ($value == "replikator")
		{
			if ($data[creplikator] == 0) return 0;
			$this->db->query("UPDATE stu_ships SET replikator=1 WHERE replikator=0 AND id='".$shipId."' ANd user_id=".$userId);
		}
		if ($value != "replikator") $this->db->query("UPDATE stu_ships SET ".$value."=1,energie=energie-1 WHERE id='".$shipId."' AND ".$value."=0 AND user_id=".$userId);
		if ($value == "schilde_aktiv") $return[msg] = "Die Schilde wurden aktiviert";
		if ($value == "replikator") $return[msg] = "Der Replikator wurde aktiviert";
		if ($value == "kss") $return[msg] = "Die Kurzstreckensensoren wurden aktiviert";
		if ($value == "lss") $return[msg] = "Die Langstreckensensoren wurden aktiviert";
		if ($value == "cloak") $return[msg] = "Die Tarnung wurde aktiviert";
		if ($value == "schilde_aktiv") $return[msg] = "Die Schilde wurden aktiviert";
		$return[code] == 1;
		return $return;
	}
	
	function deactivatevalue($shipId,$value,$userId)
	{
		$data = $this->db->query("SELECT a.id,a.name,a.coords_x,a.coords_y,a.wese,a.cloak,a.actscan,a.energie,a.schilde,a.schilde_aktiv,a.crew,b.probe,b.crew_min FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.id=".$shipId." AND a.user_id=".$userId,4);
		if ($data == 0) return 0;
		if ($value == "kss")
		{
			if ($data[probe] == 1)
			{
				$return[msg] = "Kann Sensoren nicht deaktivieren";
				return $return;
			}
			else $return[msg] = "Die Kurzstreckensensoren wurden deaktiviert";
		}
		if ($value == "lss")
		{
			if ($data[actscan] == 1)
			{
				$this->db->query("UPDATE stu_ships SET actscan=0 WHERE id=".$shipId);
				$this->db->query("DELETE FROM stu_sensor_detects WHERE phalanx_id=".$data[id]);
			}
			if ($data[probe] == 1)
			{
				$return[msg] = "Kann Sensoren nicht deaktivieren";
				return $return;
			}
			else $return[msg] = "Die Langstreckensensoren wurden deaktiviert";
		}
		if ($value == "actscan") $this->db->query("DELETE FROM stu_sensor_detects WHERE phalanx_id=".$data[id]);
		$this->db->query("UPDATE stu_ships SET ".$value."=0 WHERE id='".$shipId."' AND ".$value."=1");
		if ($value == "schilde_aktiv") $return[msg] = "Die Schilde wurden deaktiviert";
		if ($value == "replikator") $return[msg] = "Der Replikator wurde deaktiviert";
		if ($value == "cloak" && $data[cloak] == 1)
		{
			$ra = $this->redalert($data[coords_x],$data[coords_y],$shipId,0,$data[wese]);
			if ($ra != 0 || $ra != "")
			{
				$schadenmsg .= $ra[msg];
				if ($ra[destroy] != 1 && $ra[msg] != "Keine Reaktion der Schiffe" && $data[schilde] > 0 && $data[energie] > 0 && $data[schilde_aktiv] == 0 && $data[crew] >= $data[crew_min]-1)
				{
					$this->db->query("UPDATE stu_ships SET schilde_aktiv=1,energie=energie-1 WHERE id=".$shipId);
					$schadenmsg .= "<br>Die ".stripslashes($data[name])." aktiviert die Schilde";
				}
			}
			$this->db->query("DELETE FROM stu_ships_uncloaked WHERE ships_id=".$shipId);
			$return[msg] = "Die ".stripslashes($data[name])." deaktiviert die Tarnung<br>".$schadenmsg;
		}
		return $return;
	}
	
	function getclassbyid($classId) { return $this->db->query("SELECT * from stu_ships_rumps WHERE id='".$classId."'",4); }
	
	function getModuleById($moduleId) { return getmodulebyid($moduleId); }
	
	function etransfer($shipId,$shipId2,$count,$userId,$mode)
	{
		if ($count < 0) return 0;
		global $myUser;
		if ($this->cshow == 0) return 0;
		if ($this->ccloak == 1)
		{
			$return[msg] = "Das Schiff hat die Tarnung aktiviert";
			return $return;
		}
		if ($this->cclass[crew_min] - 2 > $this->ccrew)
		{
			$return[msg] = "Für den Energietransfer werden ".($this->cclass[crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($this->cships_rumps_id == 111)
		{
			$return[msg] = "Von einem Konstrukt kann keine Energie transferiert werden";
			return $return;
		}
		if ($this->cclass[probe] == 1)
		{
			$return[msg] = "Von einer Sonde kann keine Energie transferiert werden";
			return $return;
		}
		if ($this->cships_rumps_id == 111)
		{
			$return[msg] = "Von einem Konstrukt kann keine Energie transferiert werden";
			return $return;
		}
		if ($count == "max") $count = $this->cenergie;
		if ($count > $this->cenergie) $count = $this->cenergie;
		if ($mode == "col")
		{
			global $myColony;
			$data = $myColony->getcolonybyid($shipId2);
			if ($data[coords_x] != $this->ccoords_x || $data[coords_y] != $this->ccoords_y) return 0;
			if ($this->cwese != $data[wese]) return 0;
			if ($myUser->getfield("vac",$data[user_id]) == 1)
			{
				$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
				return $this->cret($destroy);
			}
			if ($data[energie] >= $data[max_energie])
			{
				$return[msg] = "Der EPS-Speicher der Kolonie ".$data[name]." ist bereits voll";
				return $return;
			}
			$data[user_id] == $userId ? $img = "<a href=?page=colony&section=showcolony&id=".$data[id]."><img src=".$this->gfx."/planets/".$data[colonies_classes_id].".gif border=0></a>" : $img = "<img src=".$this->gfx."/planets/".$data[colonies_classes_id].".gif border=0>";
			if ($data[energie]+$count > $data[max_energie]) $count = $data[max_energie] - $data[energie];
			$this->db->query("UPDATE stu_ships SET energie=energie-'".$count."' WHERE energie>='".$add."' AND id=".$shipId);
			$this->db->query("UPDATE stu_colonies SET energie=energie+'".$count."' WHERE id=".$shipId2);
		}
		else
		{
			$data = $this->getdatabyid($shipId2);
			if ($data[c][probe] == 1)
			{
				$return[msg] = "Die Sonde kann nicht erfasst werden";
				return $return;
			}
			if ($data[coords_x] != $this->ccoords_x || $data[coords_y] != $this->ccoords_y) return 0;
			if ($this->cwese != $data[wese]) return 0;
			if ($myUser->getfield("vac",$data[user_id]) == 1)
			{
				$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
				return $this->cret($destroy);
			}
			if ($data[energie] >= $data[maxeps])
			{
				$return[msg] = "Der EPS Speicher auf der ".$data[name]." ist bereits voll";
				return $return;
			}
			if ($data[damaged] == 1) $mpf = "d/";
			$this->user == $data[user_id] ? $img = "<a href=?page=ship&section=showship&id=".$data[id]."><img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif border=0></a>" : $img = "<img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif border=0>";
			$data[energie]+$count > $data[maxeps] ? $count = $data[maxeps] - $data[energie] : $count = $count;
			$this->db->query("UPDATE stu_ships SET energie=energie-'".$count."' WHERE energie>='".$count."' AND id=".$shipId);
			$this->db->query("UPDATE stu_ships SET energie=energie+'".$count."' WHERE id=".$shipId2);
		}
		if ($this->user != $data[user_id])
		{
			global $myComm;
			$myComm->sendpm($data[user_id],$this->user,"Die ".stripslashes($this->cname)." transferiert in Sektor ".$this->ccoords_x."/".$this->ccoords_y." ".$count." Energie zur ".stripslashes($data[name])."",3);
		}
		if ($this->cdamaged == 1) $umpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/ships/".$umpf.$this->cships_rumps_id.".gif></td>
					<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/b_to2.gif></td>
					<td class=tdmainobg align=center>".$img."</td>
					<td class=tdmainobg>".$count." Energie transferiert</td></tr></table>";
		return $return;
	}
	
	function getStorageById($shipId,$userId) { return $this->db->query("SELECT a.goods_id,a.count,b.name FROM stu_ships_storage as a LEFT OUTER JOIN stu_goods as b ON a.goods_id=b.id WHERE a.ships_id=".$shipId." ORDER BY b.sort ASC",2); }
	
	function transferto($shipId,$shipId2,$goodId,$goodcount,$mode,$freq=0)
	{
		if ($this->cshow == 0) return 0;
		if ($goodcount < 0) return 0;
		if ($goodId == 0 || !$goodId) return 0;
		$sstored = $this->getcountbygoodid($goodId,$shipId);
		if ($sstored == 0) return 0;
		if ($goodcount > $sstored) $goodcount = $sstored;
		if ($this->ccloak == 1)
		{
			$return[msg] = "Das Schiff hat die Tarnung aktiviert";
			return $return;
		}
		if ($this->cships_rumps_id == 111)
		{
			$return[msg] = "Mit einem Konstrukt kann nicht gebeamt werden";
			return $return;
		}
		if ($this->user != 19 && $goodId == 301)
		{
			$return[msg] = "Transportversuch fehlgeschlagen, Waren verloren";
			$this->lowerstoragebygoodid($goodcount,$goodId,$shipId);
			return $return;
		}
		if ($this->user > 100 && $goodId == 304)
		{
			$return[msg] = "Der Agent verweigert den Transport";
			return $return;
		}
		if ($this->user > 100 && $goodId == 308)
		{
			$return[msg] = "Ein Dämpfungsfeld schützt das Hangardeck, Transport fehlgeschlagen";
			return $return;
		}
		global $myUser,$myColony;
		if ($mode == "col")
		{
			$field = "stu_colonies_storage";
			$value = "colonies_id";
			$field2 = "stu_colonies";
			$classid = "colonies_classes_id";
			$coldata = $myColony->getcolonybyid($shipId2);
			if ($coldata == 0) return 0;
			if ($this->ccoords_x != $coldata[coords_x] || $this->ccoords_y != $coldata[coords_y]) return 0;
			if ($this->cwese != $coldata[wese]) return 0;
			if ($this->cenergie == 0)
			{
				$return[msg] = "Keine Energie vorhanden";
				return $return;
			}
			if ($coldata[user_id] == 2)
			{
				$return[msg] = "Zu dieser Kolonie kann nicht gebeamt werden";
				return $return;
			}
			if ($myUser->getfield("vac",$coldata[user_id]) == 1)
			{
				$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
				return $this->cret($destroy);
			}
			if ($coldata[schilde_aktiv] == 1 && $freq == 0 && $coldata[user_id] != $this->user)
			{
				$return[msg] = "Die Kolonie hat die Schilde aktiviert";
				return $return;
			}
			if ($coldata[schilde_aktiv] == 1 && $freq != $coldata[schild_freq1].$coldata[schild_freq2] && $coldata[user_id] != $this->user)
			{
				$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$shipId);
				$return[msg] = "Es wurde eine falsche Schildfrequenz angegeben";
				return $return;
			}
			if ($coldata[schilde_aktiv] == 1 && $freq == $coldata[schild_freq1].$coldata[schild_freq2] && $coldata[user_id] != $this->user) $myColony->modulateshields($shipId2);
			$coldata[user_id] == $this->user ? $img = "<a href=?page=colony&section=showcolony&id=".$coldata[id]."><img src=".$this->gfx."/planets/".$coldata[colonies_classes_id].".gif border=0></a>" : $img = "<img src=".$this->gfx."/planets/".$coldata[colonies_classes_id].".gif border=0>";
			$transadd = " zur Kolonie ".stripslashes($coldata[name]);
			$maxstorage = $coldata[max_storage];
		}
		else
		{
			$field = "stu_ships_storage";
			$value = "ships_id";
			$field2 = "stu_ships";
			$classid = "ships_rumps_id";
			$shipdata = $this->getdatabyid($shipId2);
			if ($shipdata == 0) return 0;
			if ($this->ccoords_x != $shipdata[coords_x] || $this->ccoords_y != $shipdata[coords_y]) return 0;
			if ($this->cwese != $shipdata[wese]) return 0;
			if ($shipdata[c][id] == 111 && $shipdata[user_id] != $this->user)
			{
				$return[msg] = "Du kannst auf keine fremden Konstruke beamen";
				return $return;
			}
			if ($shipdata[c][probe] == 1)
			{
				$return[msg] = "Die Sonde kann nicht erfasst werden";
				return $return;
			}
			if ($myUser->getfield("vac",$shipdata[user_id]) == 1)
			{
				$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
				return $this->cret($destroy);
			}
			if ($shipdata[damaged] == 1) $impf = "d/";
			$shipdata[user_id] == $this->user ? $img = "<a href=?page=ship&section=showship&id=".$shipdata[id]."><img src=".$this->gfx."/ships/".$impf.$shipdata[ships_rumps_id].".gif border=0></a>" : $img = "<img src=".$this->gfx."/ships/".$impf.$shipdata[ships_rumps_id].".gif border=0>";
			if ($shipdata[c][trumfield] == 1)
			{
				$return[msg] = "Das Trümmerfeld kann nicht erfasst werden";
				return $return;
			}
			if ($shipdata[cloak] == 1)
			{
				$return[msg] = "Objekt kann nicht erfasst werden";
				return $return;
			}
			if ($shipdata[schilde_aktiv] == 1)
			{
				$return[msg] = "Die ".$shipdata[name]." hat die Schilde aktiviert";
				return $return;
			}
			if ($this->db->query("SELECT id FROM stu_torpedo_types WHERE goods_id=".$goodId,1) != 0 && $shipdata[c][id] != 2)
			{
				$tt = $this->gettorptypegood($shipId2);
				if ($tt != 0 && $tt != $goodId)
				{
					$return[msg] = "Dieses Schiff hat bereits Torpedos an Bord";
					return $return;
				}
				$torp = $this->db->query("SELECT research_id,size FROM stu_torpedo_types WHERE goods_id=".$goodId,4);
				if ($torp[size] > $this->cclass[size])
				{
					$return[msg] = "Dieser Torpedotyp kann nicht geladen werden";
					return $return;
				}
				if ($shipdata[c][torps] == 0)
				{
					$return[msg] = "Dieses Schiff kann keine Torpedos an Bord nehmen";
					return $return;
				}
				$torpcount = $this->getcountbygoodid($goodId,$shipdata[id]);
				$probes = $this->db->query("SELECT SUM(COUNT) FROM stu_ships_storage WHERE ships_id=".$shipdata[id]." AND (goods_id=35 OR goods_id=36 OR goods_id=37 OR goods_id=204)",1);
				if ($torpcount >= ($shipdata[c][torps]-$probes))
				{
					$probes != 0 ? $return[msg] = "Es können maximal ".($shipdata[c][torps]-$probes)." Torpedos geladen werden - ".$probes." Rampen durch Sonden belegt" : $return[msg] = "Es können maximal ".$shipdata[c][torps]." Torpedos geladen werden";
					return $return;
				}
				if ($torpcount + $goodcount + $probes > $shipdata[c][torps]) $goodcount = $shipdata[c][torps]-$torpcount-$probes;
			}
			if ($goodId == 35 || $goodId == 36 || $goodId == 37 || $goodId == 204)
			{
				$probes = $this->db->query("SELECT SUM(COUNT) FROM stu_ships_storage WHERE ships_id=".$shipdata[id]." AND (goods_id=35 OR goods_id=36 OR goods_id=37 OR goods_id=204)",1);
				$tt = $this->gettorptypegood($shipId2);
				$tc = 0;
				if ($tt != 0) $tc = $this->getcountbygoodid($tt,$shipdata[id]);
				if ($probes >= $shipdata[c][probe_stor])
				{
					$return[msg] = "Es können maximal ".$shipdata[c][probe_stor]." Sonden geladen werden";
					return $return;
				}
				$sonkap = $shipdata[c][torps] - $tc;
				if ($sonkap > $shipdata[c][probe_stor]) $sonkap = $shipdata[c][probe_stor];
				if ($probes >= $sonkap)
				{
					$return[msg] = "Es können keine Sonden mehr geladen werden";
					return $return;
				}
				if ($goodcount > ($sonkap-$probes)) $goodcount = ($sonkap-$probes);
			}
			$maxstorage = $shipdata[c][storage];
			$transadd = " zur ".stripslashes($shipdata[name]);
		}
		if ($goodcount == 0)
		{
			$return[msg] = "Keine Waren zum beamen";
			return $return;
		}
		if ($myUser->ulevel == 1)
		{
			$return[msg] = "Als Level 1 Kolonist kannst Du die Warenbörse nicht nutzen";
			return $return;
		}
		if ($this->user != 17)
		{
			if ($this->cclass[crew_min] -2 > $this->ccrew)
			{
				$return[msg] = "Zum beamen werden ".($this->cclass[crew_min] - 2)." Crewmitglieder benötigt";
				return $return;
			}
			if ($this->ccrew < 2)
			{
				$return[msg] = "Zum beamen werden 2 Crewmitglieder benötigt";
				return $return;
			}
		}
		if ($this->cschilde_aktiv == 1)
		{
			$return[msg] = "Die ".$this->cname." hat die Schilde aktiviert";
			return $return;
		}
		$strd = $this->db->query("SELECT count FROM stu_ships_storage WHERE goods_id=".$goodId." AND ships_id=".$shipId,1);
		if ($strd < $goodcount) $goodcount = $strd;
		if ($this->cdock == $shipdata[id] || $this->cid == $shipdata[dock] || ($this->cdock == $shipdata[dock] && $this->cdock > 0))
		{
			$insgstor = $this->db->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$shipId2,1);
			if ($insgstor >= $shipdata[c][storage])
			{
				$return[msg] = "Kein Lagerraum vorhanden";
				return $return;
			}
			if ($goodcount + $insgstor > $shipdata[c][storage]) $goodcount = $shipdata[c][storage] - $insgstor;
			if ($shipdata[c][id] == 2)
			{
				$aff = $this->db->query("UPDATE stu_trade_goods SET count=count+'".$goodcount."' WHERE user_id='".$this->user."' AND status=0 AND goods_id='".$goodId."'",6);
				if ($aff == 0) $this->db->query("INSERT INTO stu_trade_goods (goods_id,count,user_id,date) VALUES ('".$goodId."','".$goodcount."','".$this->user."',NOW())");
			}
			else $this->upperstoragebygoodid($goodcount,$goodId,$shipId2,$shipdata[user_id]);
			$this->lowerstoragebygoodid($goodcount,$goodId,$shipId);
			if ($this->cdamaged == 1) $mpf = "d/";
			$return[beamed] = $goodcount;
			$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/ships/".$mpf.$this->cships_rumps_id.".gif></td>
					<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/b_to2.gif></td>
					<td class=tdmainobg align=center>".$img."</td>
					<td class=tdmainobg>".$goodcount." ".$this->db->query("SELECT name FROM stu_goods WHERE id='".$goodId."'",1)." ".$transadd." gebeamt</td></tr></table>";
			$return[code] = 1;
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		$insgstor = $this->db->query("SELECT SUM(count) as count from ".$field." WHERE ".$value."=".$shipId2,1);
		if ($insgstor >= $maxstorage)
		{
			$return[msg] = "Kein Lagerraum vorhanden";
			return $return;
		}
		if ($maxstorage < $insgstor + $goodcount) $goodcount = $maxstorage - $insgstor;
		$this->ccrew > 5 ? $multi = 5 : $multi = $this->ccrew;
		if ($this->user == 17) $multi = 4;
		$energie = ceil($goodcount/(($multi-1)*10));
		if ($this->cenergie - $energie < 0)
		{
			$goodcount = ceil((($multi-1)*10)*$this->cenergie);
			$energie = $this->cenergie;
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-".$energie." WHERE id=".$shipId);
		if ($mode != "col" && $shipdata[c][id] == 2)
		{
			$aff = $this->db->query("UPDATE stu_trade_goods SET count=count+".$goodcount." WHERE user_id=".$this->user." AND status=0 AND goods_id=".$goodId,6);
			if ($aff == 0) $this->db->query("INSERT INTO stu_trade_goods (goods_id,count,user_id,date) VALUES ('".$goodId."','".$goodcount."','".$this->user."',NOW())");
		}
		else
		{
			$mode == "col" ? $myColony->upperstoragebygoodid($goodcount,$goodId,$shipId2,$coldata[user_id]) : $this->upperstoragebygoodid($goodcount,$goodId,$shipId2,$shipdata[user_id]);
		}
		$this->lowerstoragebygoodid($goodcount,$goodId,$shipId);
		$this->cenergie -= $energie;
		if ($this->cdamaged == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/ships/".$mpf.$this->cships_rumps_id.".gif></td>
					<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/b_to2.gif></td>
					<td class=tdmainobg align=center>".$img."</td>
					<td class=tdmainobg>".$goodcount." ".$this->db->query("SELECT name FROM stu_goods WHERE id='".$goodId."'",1)." ".$transadd." gebeamt - ".$energie." Energie verbraucht</td></tr></table>";
		$return[beamed] = $goodcount;
		$return[code] = 1;
		return $return;
	}
	
	function transferfrom ($shipId,$shipId2,$goodId,$goodcount,$mode,$freq=0)
	{
		if ($this->cshow == 0) return 0;
		if ($goodcount <= 0) return 0;
		global $myUser,$myColony;
		if ($this->ccloak == 1)
		{
			$return[msg] = "Das Schiff hat die Tarnung aktiviert";
			return $return;
		}
		if ($myUser->ulevel == 1)
		{
			$return[msg] = "Kolonisten mit Level 1 können keinen Waren von anderen Schiffen/Kolonien transferieren";
			return $return;
		}
		if (($this->user != 19) && ($goodId == 301))
		{
			$return[msg] = "Transportversuch fehlgeschlagen, Waren verloren";
			$this->lowerstoragebygoodid($goodcount,$goodId,$shipId2);
			return $return;
		}
		if (($this->user > 100) && ($goodId == 304))
		{
			$return[msg] = "Der Agent verweigert den Transport";
			return $return;
		}
		if ($this->user > 100 && $goodId == 308)
		{
			$return[msg] = "Ein Dämpfungsfeld schützt das Hangardeck, Transport fehlgeschlagen";
			return $return;
		}
		if ($mode == "col")
		{
			$field = "stu_colonies_storage";
			$value = "colonies_id";
			$coldata = $myColony->getcolonybyid($shipId2);
			if ($coldata == 0) return 0;
			if ($this->ccoords_x != $coldata[coords_x] || $this->ccoords_y != $coldata[coords_y]) return 0;
			if ($this->cwese != $coldata[wese]) return 0;
			if ($myUser->getfield("vac",$coldata[user_id]) == 1)
			{
				$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
				return $this->cret($destroy);
			}
			$sstored = $myColony->getcountbygoodid($goodId,$shipId2);
			if ($goodcount > $sstored) $goodcount = $sstored;
			$coldata[user_id] == $this->user ? $img = "<a href=?page=colony&section=showcolony&id=".$coldata[id]."><img src=".$this->gfx."/planets/".$coldata[colonies_classes_id].".gif border=0></a>" : $img = "<img src=".$this->gfx."/planets/".$coldata[colonies_classes_id].".gif border=0>";
			if ($coldata[user_id] == 2)
			{
				$return[msg] = "Von dieser Kolonie kann nicht gebeamt werden";
				return $return;
			}
			if ($coldata[schilde_aktiv] == 1 && $freq == 0 && $coldata[user_id] != $this->user)
			{
				$return[msg] = "Die Kolonie hat die Schilde aktiviert";
				return $return;
			}
			if ($coldata[schilde_aktiv] == 1 && $freq != $coldata[schild_freq1].$coldata[schild_freq2] && $coldata[user_id] != $this->user)
			{
				if ($this->cenergie == 0)
				{
					$return[msg] = "Keine Energie vorhanden";
					return $return;
				}
				$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$shipId);
				$myColony->modulateshields($shipId2);
				$return[msg] = "Es wurde eine falsche Schildfrequenz angegeben - 1 Energie abgezogen";
				return $return;
			}
			if ($coldata[schilde_aktiv] == 1 && $freq == $coldata[schild_freq1].$coldata[schild_freq2] && $coldata[user_id] != $this->user) $myColony->modulateshields($shipId2);
			$coldata[user_id] == $this->user ? $transadd = " von der Kolonie <a href=main.php?page=colony&section=sowcolony&id=".$shipdId2.">".$coldata[name]."</a>" : $trandadd = " von der Kolonie ".stripslashes($coldata[name]);
		}
		else
		{
			$field = "stu_ships_storage";
			$value = "ships_id";
			$shipdata = $this->getdatabyid($shipId2);
			if ($shipdata == 0) return 0;
			if ($shipdata[c][probe] == 1)
			{
				$return[msg] = "Die Sonde kann nicht erfasst werden";
				return $return;
			}
			if ($this->ccoords_x != $shipdata[coords_x] || $this->ccoords_y != $shipdata[coords_y]) return 0;
			if ($this->cwese != $shipdata[wese]) return 0;
			if ($shipdata[ships_rumps_id] == 111 && $this->user != $shipdata[user_id])
			{
				$return[msg] = "Von einem fremden Konstrukt kann nicht gebeamt werden";
				return $return;
			}
			if ($myUser->getfield("vac",$shipdata[user_id]) == 1)
			{
				$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
				return $this->cret($destroy);
			}
			if ($shipdata[ships_rumps_id] == 144)
			{
				$return[msg] = "Transporterstrahl kann die Aussenhaut nicht durchdringen";
				return $return;
			}
			$sstored = $this->getcountbygoodid($goodId,$shipId2);
			if ($goodcount > $sstored) $goodcount = $sstored;
			if ($shipdata[damaged] == 1) $impf = "d/";
			$this->user == $shipdata[user_id] ? $img = "<a href=?page=ship&section=showship&id=".$shipdata[id]."><img src=".$this->gfx."/ships/".$impf.$shipdata[ships_rumps_id].".gif border=0></a>" : $img = "<img src=".$this->gfx."/ships/".$impf.$shipdata[ships_rumps_id].".gif border=0>";
			$userdata = $myUser->getuserbyid($shipdata[user_id]);
			if ($userdata[level] == 1)
			{
				$return[msg] = "Das beamen von Schiffen von Siedlern mit Level 1 ist untersagt";
				return $return;
			}
			if ($shipdata[cloak] == 1)
			{
				$return[msg] = "Objekt kann nicht erfasst werden";
				return $return;
			}
			$shipdata[user_id] == $this->user ? $transadd = " von der <a href=main.php?page=ship&section=showship&id=".$shipId2.">".stripslashes($shipdata[name])."</a>" : $trandadd = " von der ".stripslashes($shipdata[name]);
		}
		if ($this->db->query("SELECT id FROM stu_torpedo_types WHERE goods_id=".$goodId,1) != 0)
		{
			if ($this->user != $shipdata[user_id] && $mode != "col" && $shipdata[c][trumfield] == 0 && $shipdata[c][id] != 2 && $shipdata[c][id] != 3)
			{
				$return[msg] = "Du kannst keine Torpedos von anderen Schiffe beamen";
				return $return;
			}
			if ($this->user != $coldata[user_id] && $mode == "col" && $coldata[user_id] != 2)
			{
				$return[msg] = "Du kannst keine Torpedos von anderen Kolonien beamen";
				return $return;
			}
			if ($this->cclass[torps] == 0)
			{
				$return[msg] = "Dieses Schiff kann keine Torpedos an Bord nehmen";
				return $return;
			}
			$torp = $this->db->query("SELECT research_id,size FROM stu_torpedo_types WHERE goods_id=".$goodId,4);
			if ($torp[size] > $this->cclass[size])
			{
				$return[msg] = "Dieser Torpedotyp kann nicht geladen werden";
				return $return;
			}
			$tt = $this->gettorptypegood($shipId);
			if ($tt != 0 && $tt != $goodId)
			{
				$return[msg] = "Die Torpedoabschussrampen sind bereits belegt";
				return $return;
			}
			$torpcount = $this->getcountbygoodid($goodId,$this->cid);
			if ($torpcount >= $this->cclass[torps])
			{
				$return[msg] = "Es können maximal ".$this->cclass[torps]." Torpedos geladen werden";
				return $return;
			}
			if ($torpcount + $goodcount > $this->cclass[torps]) $goodcount = $this->cclass[torps]-$torpcount;
			$torpcount = $this->getcountbygoodid($goodId,$this->cid);
			$probes = $this->db->query("SELECT SUM(COUNT) FROM stu_ships_storage WHERE ships_id=".$this->cid." AND (goods_id=35 OR goods_id=36 OR goods_id=37 OR goods_id=204)",1);
			if ($torpcount >= ($this->cclass[torps]-$probes))
			{
				$probes != 0 ? $return[msg] = "Es können maximal ".($this->cclass[torps]-$probes)." Torpedos geladen werden - ".$probes." Rampen durch Sonden belegt" : $return[msg] = "Es können maximal ".$this->cclass[torps]." Torpedos geladen werden";
				return $return;
			}
			if ($torpcount + $goodcount + $probes > $this->cclass[torps]) $goodcount = $this->cclass[torps]-$torpcount-$probes;
		}
		if ($goodId == 35 || $goodId == 36 || $goodId == 37 || $goodId == 204)
		{
			$probes = $this->db->query("SELECT SUM(COUNT) FROM stu_ships_storage WHERE ships_id=".$this->cid." AND (goods_id=35 OR goods_id=36 OR goods_id=37 OR goods_id=204)",1);
			$tt = $this->gettorptypegood($shipId);
			$tc = 0;
			if ($tt != 0) $tc = $this->getcountbygoodid($tt,$this->cid);
			if ($probes >= $this->cclass[probe_stor])
			{
				$return[msg] = "Es können maximal ".$this->cclass[probe_stor]." Sonden geladen werden";
				return $return;
			}
			$sonkap = $this->cclass[torps] - $tc;
			if ($sonkap > $this->cclass[probe_stor]) $sonkap = $this->cclass[probe_stor];
			if ($probes >= $sonkap)
			{
				$return[msg] = "Es können keine Sonden mehr geladen werden";
				return $return;
			}
			if ($goodcount > ($sonkap-$probes)) $goodcount = ($sonkap-$probes);
		}
		if ($goodcount <=0) return 0;
		if ($this->cschilde_aktiv == 1)
		{
			$return[msg] = "Die ".$this->cname." hat die Schilde aktiviert";
			return $return;
		}
		if ($coldata[schilde_aktiv] == 1 && $mode != "col")
		{
			$return[msg] = "Die Kolonie ".$coldata[name]." hat die Schilde aktiviert";
			return $return;
		}
		if ($shipdata[schilde_aktiv] == 1)
		{
			$return[msg] = "Die ".$shipdata[name]." hat die Schilde aktiviert";
			return $return;
		}
		if ($this->user != 17)
		{
			if ($this->cclass[crew_min] - 2 > $this->ccrew)
			{
				$return[msg] = "Zum beamen werden ".($this->cclass[crew_min] - 2)." Crewmitglieder benötigt";
				return $return;
			}
			if ($this->ccrew < 2)
			{
				$return[msg] = "Zum beamen werden 2 Crewmitglieder benötigt";
				return $return;
			}
		}
		$dock = $this->getdockinfo($shipId);
		if ($this->cdock == $shipdata[id] || $this->cid == $shipdata[dock] || ($this->cdock == $shipdata[dock] && $this->cdock > 0))
		{
			$insgstor = $this->db->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$shipId,1);
			if ($insgstor >= $this->cclass[storage])
			{
				$return[msg] = "Kein Lagerraum vorhanden";
				return $return;
			}
			if ($goodcount + $insgstor > $this->cclass[storage]) $goodcount = $this->cclass[storage] - $insgstor;
			$this->upperstoragebygoodid($goodcount,$goodId,$shipId,$this->user);
			$this->lowerstoragebygoodid($goodcount,$goodId,$shipId2);
			if ($this->cdamaged == 1) $mpf = "d/";
			$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/ships/".$mpf.$this->cships_rumps_id.".gif></td>
				<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/b_from2.gif></td>
				<td class=tdmainobg align=center>".$img."</td>
				<td class=tdmainobg>".$goodcount." ".$this->db->query("SELECT name FROM stu_goods WHERE id='".$goodId."'",1)." ".$transadd." gebeamt</td></tr></table>";
			$return[beamed] = $goodcount;
			$return[code] = 1;
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		$stored = $this->db->query("SELECT count from ".$field." WHERE ".$value."='".$shipId2."' AND goods_id='".$goodId."'",1);
		if ($stored == 0)
		{
			$return[msg] = "Ware nicht vorhanden";
			return $return;
		}
		if ($stored-$goodcount < 0) $goodcount = $stored;
		$insgstor = $this->db->query("SELECT SUM(count) as count FROM stu_ships_storage WHERE ships_id=".$shipId,1);
		if ($this->cclass[storage] <= $insgstor)
		{
			$return[msg] = "Kein Lagerraum auf der ".$this->cname." vorhanden";
			return $return;
		}
		if ($this->cclass[storage] < $insgstor + $goodcount) $goodcount = $this->cclass[storage] - $insgstor;
		$this->ccrew > 5 ? $multi = 5 : $multi = $this->ccrew;
		if ($this->user == 17) $multi = 4;
		$energie = ceil($goodcount/(($multi-1)*10));
		if ($this->cenergie - $energie < 0)
		{
			$goodcount = ceil((($multi-1)*10)*$this->cenergie);
			$energie = $this->cenergie;
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-".$energie." WHERE id=".$shipId);
		$this->upperstoragebygoodid($goodcount,$goodId,$shipId,$this->user);
		$mode != "col" ? $this->lowerstoragebygoodid($goodcount,$goodId,$shipId2) : $myColony->lowerstoragebygoodid($goodcount,$goodId,$shipId2);
		if ($this->cdamaged == 1) $mpf = "d/";
		$this->cenergie -= $energie;
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/ships/".$mpf.$this->cships_rumps_id.".gif></td>
					<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/b_from2.gif></td>
					<td class=tdmainobg align=center>".$img."</td>
					<td class=tdmainobg>".$goodcount." ".$this->db->query("SELECT name FROM stu_goods WHERE id='".$goodId."'",1)." ".$transadd." gebeamt - ".$energie." Energie verbraucht</td></tr></table>";
		$return[beamed] = $goodcount;
		$return[code] = 1;
		return $return;
	}
	
	function getclasses() { return $this->db->query("SELECT * FROM stu_ships_rumps WHERE view=1 ORDER BY sorta ASC,sortb ASC",2); }
	
	function getnpcclasses() { return $this->db->query("SELECT * FROM stu_ships_rumps ORDER BY sorta ASC,sortb ASC",2); }
	
	function beammsg($goods,$id,$id2,$way,$mode)
	{
		if (count($goods) == 0) return 0;
		$shipdata = $this->db->query("SELECT name,user_id,coords_x,coords_y from stu_ships WHERE id='".$id."' AND user_id=".$this->user,4);
		if ($shipdata == 0) return 0;
		$mode == "col" ? $data2 = $this->db->query("SELECT name,user_id from stu_colonies WHERE id=".$id2,4) : $data2 = $this->db->query("SELECT name,user_id from stu_ships WHERE id=".$id2,4);
		if ($data2 == 0) return 0;
		if ($shipdata[user_id] != $data2[user_id])
		{
			foreach($goods as $key => $value) if ($value[id]) $dummygood .= $value['count']."&nbsp;".$this->db->query("SELECT name FROM stu_goods WHERE id=".$value[id],1)."<br>";
			$mode == "col" ? $modeadd = "der Kolonie" : $modeadd = "der";
			$way == "to" ? $wayadd = "zu" : $wayadd = "von";
			$message = "Die ".$shipdata[name]." beamt in Sektor ".$shipdata[coords_x]."/".$shipdata[coords_y]." Waren ".$wayadd." ".$modeadd." ".$data2[name].": ".$dummygood;
			global $myComm;
			$myComm->sendpm($data2[user_id],$shipdata[user_id],$message,3);
		}
	}
	
	function getshipsbyclass($classId) { return $this->db->query("SELECT * FROM stu_ships WHERE ships_rumps_id='".$classId."'",2); }
	
	function newname($shipId,$new_name)
	{
		if ($this->cshow == 0) return 0;
		$data = $this->getdatabyid($shipId);
		$username = strip_tags($new_name,"<font><b></font></b>");
		$string1 = substr_count($username,"<font");
		$string2 = substr_count($username,"</font>");
		if ($string1 > $string2) $multi = $string1-$string2;
		if ($multi>0) for ($z=1;$z<=$multi;$z++) $addstring .= "</font>";
		unset($multi);
		$string1 = substr_count($username,"<Font");
		$string2 = substr_count($username,"</Font>");
		if ($string1 > $string2) $multi = $string1-$string2;
		if ($multi>0) for ($z=1;$z<=$multi;$z++) $addstring .= "</Font>";
		unset($multi);
		$string1 = substr_count($username,"<FONT>");
		$string2 = substr_count($username,"</FONT>");
		if ($string1 > $string2) $multi = $string1-$string2;
		if ($multi>0) for ($z=1;$z<=$multi;$z++) $addstring .= "</FONT>";
		strlen($username.$addstring) > 255 ? $nn = strip_tags($username.$addstring) : $nn = $username.$addstring;
		$this->db->query("UPDATE stu_ships SET name='".addslashes(str_replace("\"","'",str_replace("background","",$nn)))."' WHERE id=".$this->cid);
		if ($data[damaged] == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg align=center><img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif></td>
			<td class=tdmainobg>Schiff umbenannt in ".str_replace("\"","'",$nn)."</td></tr></table>";
		return $return;
	}
	
	function spawnship($classId,$mode,$spawnId)
	{
		$spawndata = $this->db->query("SELECT id,coords_x,coords_y,wese FROM stu_ships WHERE id='".$spawnId."' AND ships_rumps_id=2",4);
		if ($spawndata == 0) return 0;
		global $myUser;
		if ($myUser->ulevel == 8)
		{
			$return[msg] = "Die Handelsallianz hilft nur Siedlern mit einer niedrigen Kolonisationsstufe";
			return $return;
		}
		if (($classId == 4 || $classId == 5) && $myUser->ulevel < 2)
		{
			$return[msg] = "Du musst zuerst ein Kolonieschiff beantragen und einen Paneten kolonisieren";
			return $return;
		}
		if ($myUser->usymp < 100 && $myUser->ulevel >= 2)
		{
			$return[msg] = "Es werden 100 Sympathie zum beantragen eines Schiffes benötigt - Vorhanden sind nur ".$myUser->usymp;
			return $return;
		}
		if ($classId == 4 || $classId == 5)
		{
			if ($this->db->query("SELECT id FROM stu_ships WHERE ships_rumps_id='".$classId."' AND user_id=".$this->user,3) == 1)
			{
				$return[msg] = "Du kannst neben dem Kolonieschiff maximal einen Tanker und einen Frachter beantragen";
				return $return;
			}
			if ($myUser->usymp < 100)
			{
				$return[msg] = "Es werden 100 Sympathie zum beantragen eines Schiffes benötigt - Vorhanden sind nur ".$myUser->usymp;
				return $return;
			}
			$this->db->query("UPDATE stu_user SET symp=symp-100 WHERE id=".$this->user);
			$data = $this->db->query("SELECT * FROM stu_ships_rumps WHERE id='".$classId."'",4);
			$module = $this->getmodulebyid(2);
			$moduleE = $this->getmodulebyid(30);
			$shipId = $this->db->query("INSERT INTO stu_ships (name,ships_rumps_id,user_id,coords_x,coords_y,huelle,energie,batt,crew,reaktormodlvl,huellmodlvl,schildmodlvl,epsmodlvl,sensormodlvl,waffenmodlvl,computermodlvl,antriebmodlvl,wese) VALUES ('".$data[name]."','".$classId."','".$this->user."','".$spawndata[coords_x]."','".$spawndata[coords_y]."','".($module[huell]*$data[huellmod])."','".($data[epsmod]*$moduleE[eps])."','".$data[max_batt]."','".$data[crew]."','0','2','10','30','34','13','6','26','".$spawndata[wese]."')",5);
			$this->db->query("INSERT INTO stu_ships_storage (ships_id,user_id,goods_id,count) VALUES ('".$shipId."','".$this->user."','1','50')");
			$this->db->query("INSERT INTO stu_ships_storage (ships_id,user_id,goods_id,count) VALUES ('".$shipId."','".$this->user."','2','50')");
			$return[msg] = "Schiff beantragt und bereit";
			return $return;
		}
		else
		{
			if ($this->db->query("SELECT id FROM stu_ships WHERE ships_rumps_id='1' ANd user_id=".$this->user,1) > 0)
			{
				$return[msg] = "Du kannst nur 1 Kolonieschiff besitzen";
				return $return;
			}
			if ($classId != 1) return -2;
			$data = $this->db->query("SELECT * FROM stu_ships_rumps WHERE id='".$classId."'",4);
			$module = $this->getmodulebyid(2);
			$shipId = $this->db->query("INSERT INTO stu_ships (name,ships_rumps_id,user_id,coords_x,coords_y,huelle,energie,batt,crew,reaktormodlvl,huellmodlvl,schildmodlvl,epsmodlvl,sensormodlvl,waffenmodlvl,computermodlvl,antriebmodlvl,wese) VALUES ('".$data[name]."','".$classId."','".$this->user."','".$spawndata[coords_x]."','".$spawndata[coords_y]."','".($module[huell]*$data[huellmod])."','27','".$data[max_batt]."','".$data[crew]."','0','2','10','30','34','13','6','26','".$spawndata[wese]."')",5);
			$this->db->query("INSERT INTO stu_ships_storage (ships_id,user_id,goods_id,count) VALUES ('".$shipId."','".$this->user."','1','50')");
			$this->db->query("INSERT INTO stu_ships_storage (ships_id,user_id,goods_id,count) VALUES ('".$shipId."','".$this->user."','2','50')");
			if ($mode == 1) $this->db->query("INSERT INTO stu_ships_storage (ships_id,user_id,goods_id,count) VALUES ('".$shipId."','".$this->user."','3','150')");
			$this->db->query("UPDATE stu_user SET level=1 WHERE level=0 AND id='".$this->user."'",$this->dblink);
			if ($myUser->ulevel > 2) $this->db->query("UPDATE stu_user SET symp=symp-100 WHERE id=".$this->user);
			$return[msg] = "Schiff beantragt und bereit";
			return $return;
		}
	}
	
	function colonize($shipId,$colId)
	{
		if ($this->cshow == 0) return 0;
		$coldata = $this->db->query("SELECT * FROM stu_colonies WHERE id='".$colId."' AND user_id='2' AND coords_x=".$this->ccoords_x." AND coords_y=".$this->ccoords_y." ANd wese=".$this->cwese,4);
		if ($coldata == 0) return 0;
		global $myUser;
		if ($coldata[user_id] != 2)
		{
			$return[msg] = "Du kannst diesen Planeten nicht kolonisiseren";
			return $return;
		}
		if ($this->db->query("SELECT race FROM stu_map_fields WHERE coords_x=".$coldata[coords_x]." AND coords_y=".$coldata[coords_y]." AND wese=".$this->cwese." AND race!=16 AND race!=98",1) != 0)
		{
			$return[msg] = "Dieser Planet kann nicht kolonisiert werden (Rassengebiet)";
			return $return;
		}
		if ($this->user > 100)
		{
			if ($myUser->ulevel < 2 && $coldata[colonies_classes_id] > 1)
			{
				$return[msg] = "Du musst zuerst einen Klasse M Planeten kolonisieren";
				return $return;
			}
			if ($myUser->ulevel < 4 && ($coldata[colonies_classes_id] == 2 || $coldata[colonies_classes_id] == 3))
			{
				$return[msg] = "Zur Besiedlung dieses Plantentypes wird Kolonistenlevel 4 benötigt";
				return $return;
			}
			if ($myUser->ulevel < 6 && ($coldata[colonies_classes_id] == 4 || $coldata[colonies_classes_id] == 5 || $coldata[colonies_classes_id] == 6))
			{
				$return[msg] = "Zur Besiedlung dieses Plantentypes wird Kolonistenlevel 6 benötigt";
				return $return;
			}
			if ($myUser->ulevel < 8 && $coldata[colonies_classes_id] > 6)
			{
				$return[msg] = "Zur Besiedlung dieses Plantentypes wird Kolonistenlevel 8 benötigt";
				return $return;
			}
			if ($this->db->query("SELECT id FROM stu_colonies WHERE user_id=".$this->user." AND colonies_classes_id=1",1) != 0 && $coldata[colonies_classes_id] == 1)
			{
				$return[msg] = "Es ist nur ein Planet der Klasse M pro Kolonist erlaubt";
				return $return;
			}
			if ($this->db->query("SELECT COUNT(id) FROM stu_colonies WHERE user_id=".$this->user." AND (colonies_classes_id=2 OR colonies_classes_id=3 OR colonies_classes_id=4 OR colonies_classes_id=5 OR colonies_classes_id=7 OR colonies_classes_id=8 OR colonies_classes_id=10)",1) == 2 && ($coldata[colonies_classes_id] == 2 || $coldata[colonies_classes_id] == 3 || $coldata[colonies_classes_id] == 4 || $coldata[colonies_classes_id] == 5 || $coldata[colonies_classes_id] == 7 || $coldata[colonies_classes_id] == 8 || $coldata[colonies_classes_id] == 10))
			{
				$return[msg] = "Es sind nur 2 Planeten der Klasse L,N,G,K,H oder X zusätzlich zur Klasse M pro Kolonist erlaubt";
				return $return;
			}
			if ($this->db->query("SELECT id FROM stu_colonies WHERE user_id=".$this->user." AND colonies_classes_id!=6 AND colonies_classes_id!=9",3) == 3 && $coldata[colonies_classes_id] != 6 && $coldata[colonies_classes_id] != 9)
			{
				$return[msg] = "Derzeit sind nur 3 Planeten pro Kolonist erlaubt";
				return $return;
			}
			if ($this->db->query("SELECT id FROM stu_colonies WHERE user_id=".$this->user." AND (colonies_classes_id=6 OR colonies_classes_id=9)",3) == 2 && ($coldata[colonies_classes_id] == 6 || $coldata[colonies_classes_id] == 9))
			{
				$return[msg] = "Derzeit sind nur 2 Asteroiden und/oder J-Klasseplaneten pro Kolonist erlaubt";
				return $return;
			}
			if ($this->db->query("SELECT id FROM stu_colonies WHERE user_id=".$this->user." AND colonies_classes_id=10",3) == 1 && ($coldata[colonies_classes_id] == 10))
			{
				$return[msg] = "Derzeit ist nur ein Planet der Klasse R pro Kolonist erlaubt";
				return $return;
			}
		}
		global $myColony;
		if (($myColony->getuserresearch(182,$this->user) == 0) && ($coldata[colonies_classes_id] == 9))
		{
			$return[msg] = "Du musst zuerst die Gasriesen-Kolonisation erforschen";
			return $return;
		}
		$stor = $this->getcountbygoodid(3,$shipId);
		if ($stor < 50)
		{
			$return[msg] = "Zum Kolonisieren werden 50 Baumaterial an Bord des Schiffes benötigt - Vorhanden sind nur ".$stor;
			return $return;
		}
		if ($this->cenergie < 10)
		{
			$return[msg] = "Zum Kolonisieren wird 10 Energie benötigt - Vorhanden ist nur ".$this->cenergie;
			return $return;
		}
		if ($coldata[colonies_classes_id] > 10)
		{
			$return[msg] = "Dieser Planetentyp kann nicht kolonisiert werden";
			return $return;
		}
		$this->lowerstoragebygoodid(50,3,$shipId);
		$this->db->query("UPDATE stu_ships SET energie=energie-10 WHERE id=".$shipId);
		$this->db->query("UPDATE stu_colonies_storage SET user_id='".$this->user."' WHERE colonies_id='".$colId."'",$this->dblink);
		$this->db->query("UPDATE stu_colonies SET user_id='".$this->user."',name='Kolonie',max_storage=max_storage+200,max_energie=max_energie+15,max_bev='5' WHERE id=".$colId);
		if ($coldata[colonies_classes_id] == 1) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 2) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 3) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 4) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 5) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 6) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='17' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 7) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 8) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 9) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='17' AND colonies_id=".$colId);
		if ($coldata[colonies_classes_id] == 10) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$colId);
		if ($myUser->urasse != 5) $this->db->query("UPDATE stu_colonies_fields SET buildings_id = 4 WHERE buildings_id = 136 AND colonies_id =".$colId);
		if ($coldata[colonies_classes_id] == 1 && $myUser->ulevel == 1) $this->db->query("UPDATE stu_user SET level='2' WHERE id=".$this->user);
		$return[msg] = "Der Planet wurde kolonisiert";
		return $return;
	}
	
	function ebatt($shipId,$count,$userId)
	{
		if ($count < 0) return 0;
		$data = $this->db->query("SELECT a.batt,a.energie,a.ships_rumps_id,a.epsmodlvl,a.crew,b.crew_min,b.epsmod FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.id=".$shipId." AND a.user_id=".$this->user,4);
		if ($data[batt] == 0)
		{
			$return[msg] = "Die Ersatzbatterie ist leer";
			return $return;
		}
		if ($data[crew_min] - 2 > $data[crew])
		{
			$return[msg] = "Für das entladen der Ersatzbatterie werden ".($data[crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		$me = $this->getmodulebyid($data[epsmodlvl]);
		$maxeps = $me[eps]*$data[epsmod];
		if ($data[batt] < $count) $count = $data[batt];
		if (($data[energie] + $count) > $maxeps) $count = $maxeps-$data[energie];
		$this->db->query("UPDATE stu_ships SET batt=batt-".$count.",energie=energie+".$count." WHERE id='".$shipId."' AND user_id='".$userId."'");
		
		if ($data[damaged] == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/battp2.gif></td>
			<td class=tdmainobg align=center><img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif></td>
			<td class=tdmainobg>Ersatzbatterie um ".$count." Energie entladen</td></tr></table>";
		return $return;
	}
	
	function bussard($shipId,$count,$userId)
	{
		$data = $this->db->query("SELECT a.id,a.user_id,a.energie,a.crew,a.coords_x,a.coords_y,a.wese,a.ships_rumps_id,b.bussard,b.crew_min,b.storage,c.type FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_map_fields as c ON a.coords_x=c.coords_x AND a.coords_y=c.coords_y AND a.wese=c.wese WHERE a.id=".$shipId." AND a.user_id=".$this->user,4);
		if ($data == 0) return 0;
		if ($count < 0 && $count != "max") return 0;
		if ($data[bussard] == 0)
		{
			$return[msg] = "Dieses Schiff hat keine Bussard-Kollektoren";
			return $return;
		}
		if ($data[crew_min] - 2 > $data[crew])
		{
			$return[msg] = "Für die Bussard-Kollektoren werden ".($data[crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($data[energie] == 0)
		{
			$return[msg] = "Keine Energie für die Bussard-Kollektoren vorhanden";
			return $return;
		}
		global $myMap;
		if ($count == "max" || $count > $data[energie]) $count = $data[energie];
		if ($data[type] == 2 && $data[bussard] >= 3) $multi = 3;
		if ($data[type] == 2 && $data[bussard] < 3) $multi = $data[bussard];
		if ($data[type] == 3 && $data[bussard] == 6) $multi = 6;
		if ($data[type] == 3 && $data[bussard] == 7) $multi = 7;
		if ($data[type] == 3 && $data[bussard] < 6) $multi = $data[bussard];
		if ($data[type] == 30 && $data[bussard] > 2) $multi = floor($data[bussard]/3);
		if (!$multi || ($multi == 0))
		{
			$return[msg] = "Keine Vorkommen in diesem Sektor vorhanden";
			return $return;
		}
		$insgstor = $this->db->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$shipId,1);
		if ($insgstor >= $data[storage])
		{
			$return[msg] = "Kein Lagerraum vorhanden";
			return $return;
		}
		$maxstor = $data[storage] - $insgstor;
		if ($count*$multi > $maxstor)
		{
			$energie = ceil($maxstor/$multi);
			$add = $maxstor;
		}
		else
		{
			$add = $count*$multi;
			$energie = $count;
		}
		if ($data[type] == 30) { $good = 15;$fname = "Plasma"; }
		else { $good = 2;$fanme = "Deuterium"; }
		$this->db->query("UPDATE stu_ships SET energie=energie-".$energie." WHERE id=".$shipId);
		if ($good == 2)
		{
			$bonus[230] = 0;
			$bonus[231] = 0;
			for ($j=1;$j<=floor(($add/10));$j++)
			{
				$r = rand(1,400);
				if ($r == 1)
				{
					$bonuswahl = rand(1,3);
					if ($bonuswahl  == 1) $bonus[230] += 1;
					elseif ($bonuswahl  == 2) $bonus[231] += 1;
					elseif ($bonuswahl  == 3) $bonus[15] += 1;
				}
			}
			if (($add + $bonus[230] + $bonus[231] + $bonus[15]) > ($data[c][storage]-$insgstor))
			{
				$add = $add - ($bonus[230] + $bonus[231] + $bonus[15]);
			}
			if ($bonus[230] > 0)
			{
				$bonusmsg = "<br>Reiche Vorkommen entdeckt: ".$bonus[230]." Cryonetrium gesammelt";
				$this->upperstoragebygoodid($bonus[230],230,$shipId,$userId);
			}
			if ($bonus[231] > 0)
			{
				$bonusmsg .= "<br>Reiche Vorkommen entdeckt: ".$bonus[231]." Syrillium gesammelt";
				$this->upperstoragebygoodid($bonus[231],231,$shipId,$userId);
			}
			if ($bonus[15] > 0)
			{
				$bonusmsg .= "<br>Reiche Vorkommen entdeckt: ".$bonus[15]." Plasma gesammelt";
				$this->upperstoragebygoodid($bonus[15],15,$shipId,$userId);
			}
		}
		$this->upperstoragebygoodid($add,$good,$shipId,$userId);
		if ($data[damaged] == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/map/".$data[type].".gif></td>
			<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/buss2.gif></td>
			<td class=tdmainobg align=center><img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif></td>
			<td class=tdmainobg>".$add." ".$fanme." eingesaugt - ".$energie." Energie verbraucht".$bonusmsg."</td></tr></table>";
		return $return;
	}
	
	function msghandle($msg,$type=1)
	{
		$this->pmmsg ? $pma = "<br>" : $pma = "";
		$this->returnmsg ? $rea = "<br>" : $rea = "";
		if ($type == 1) $this->returnmsg.=$rea.$msg;
		if ($type == 2) $this->pmmsg.=$pma.$msg;
		if ($type == 3)
		{
			$this->pmmsg.=$pma.$msg;
			$this->returnmsg.=$rea.$msg;
		}
	}
	
	function cret($destroy=0) { return array("msg" => $this->returnmsg,"code" => $this->returncode,"destroy" => $destroy); }
	
	function phaser($shipId,$shipId2,$userId,$strbk=1,$ksscheck=1,$react=0,$df=1)
	{
		if ($shipId == $shipId2) return 0;
		if ($react == 0) $this->msghandle("<strong>Angriff</strong>",3);
		if ($react == 1) $this->msghandle("<strong>Gegenwehr</strong>",3);
		if ($react == 2) $this->msghandle("<strong>Alarm Rot</strong>",3);
		if ($react == 3) $this->msghandle("<strong>Verteidigung</strong>",3);
		$decloak = $this->checkdecloak($shipId2,$userId);
		$ship1 = $this->getdatabyid($shipId);
		if ($ship1[waffenmodlvl] == 0)
		{
			$this->msghandle("Es ist kein Waffenmodul auf diesem Schiff installiert");
			return $this->cret($destroy);
		}
		$wc = $this->checkss("waffdef",$shipId);
		if ($wc > time())
		{
			$react == 0 ? $this->msghandle("Die Waffen sind ausgefallen - Die Reparatur dauert bis ".date("d.m H:i",$wc)." Uhr") : $this->msghandle("Die Waffen der ".$ship1[name]." sind ausgefallen");
			return $this->cret($destroy);
		}
		elseif ($wc > 0 && $wc < time()) $this->repairssd("waffdef",$shipId);
		if ($ship1[cloak] == 1 && $decloak == 0)
		{
			$this->msghandle("Die Tarnung ist aktiviert");
			return $this->cret($destroy);
		}
		if ($ship1[dock] > 0)
		{
			$this->msghandle("Schiff ist angedockt");
			return $this->cret($destroy);
		}
		if ($ship1[energie] < 1)
		{
			$this->msghandle("Keine Energie vorhanden");
			return $this->cret($destroy);
		}
		if ($ship1[kss] == 0 && $ksscheck == 1)
		{
			$this->msghandle("Die Kurzstreckensensoren sind nicht aktiviert");
			return $this->cret($destroy);
		}
		if ($ship1[c][crew_min] > $ship1[crew])
		{
			$this->msghandle("Für einen Schuß werden ".$ship1[c][crew_min]." Crewmitglieder benötigt");
			return $this->cret($destroy);
		}
		$ship2 = $this->getDatabyid($shipId2);
		if ($ship2 == 0) return 0;
		if ($ship1[coords_x] != $ship2[coords_x] || $ship1[coords_y] != $ship2[coords_y]) return 0;
		if ($ship1[wese] != $ship2[wese]) return 0;
		global $myUser;
		if ($myUser->getfield("vac",$ship2[user_id]) == 1)
		{
			$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
			return $this->cret($destroy);
		}
		if ($myUser->getfield("level",$ship1[user_id]) < 2)
		{
			$this->msghandle("Mit Level 1 kannst Du keine anderen Kolonisten angreifen");
			return $this->cret($destroy);
		}
		if ($myUser->getfield("level",$ship2[user_id]) < 2)
		{
			$this->msghandle("Es können keine Kolonisten unter Level 2 angegriffen werden");
			return $this->cret($destroy);
		}
		if ($ship2[ships_rumps_id] == 154)
		{
			$this->msghandle("Das Minenfeld kann nicht erfasst werden");
			return $this->cret($destroy);
		}
		if ($ship2[ships_rumps_id] == 169 && $this->db->query("SELECT id FROM stu_ships WHERE ships_rumps_id=168 AND coords_x=".$ship2[coords_x]." AND coords_y=".$ship2[coords_y],1) != 0)
		{
			$this->msghandle("Das Schiff befindet sich in der Werft und kann nicht erfasst werden");
			return $this->cret($destroy);
		}
		global $myMap,$myHistory;
		$field = $myMap->getfieldbycoords($ship1[coords_x],$ship1[coords_y]);
		if ($field[type] == 16)
		{
			$selfschaden = $ship1[maxphaser] * 2;
			if ($ship1[schilde_aktiv] == 1 && $ship1[schilde] > $selfschaden)
			{
				$sschilde = $ship1[schilde] - $selfschaden;
				$this->msghandle("Explosion durch entzündetes Metreongas - Schildschaden: ".$selfschaden.", Schilde jetzt bei ".$sschilde);
				$this->db->query("UPDATE stu_ships SET schilde=".$sschilde." WHERE id=".$shipId);
			}
			elseif ($ship1[schilde_aktiv] == 1 && $ship1[schilde] < $selfschaden)
			{
				$srhuellemin = $selfschaden-$ship1[schilde];
				$srhuelle = $ship1[huelle] - $srhuellemin;
				if ($srhuelle < 1)
				{
					$this->trumfield($shipId);
					$myHistory->addEvent("Die ".addslashes($ship1[name])." wurde in Sektor ".$ship1[coords_x]."/".$ship1[coords_y].($ship1[wese] == 2 ? " (2)" : "")." durch eine Metreongasexplosion zerstört",$ship1[user_id]);
					$this->msghandle("Explosion durch entzündetes Metreongas - Schilde brechen zusammen - Hüllenbruch!<br>Das Schiff wurde zerstört");
					return $this->cret($destroy);
				}
				$this->db->query("UPDATE stu_ships SET schilde=0,schilde_aktiv=0,huelle=".$srhuelle." WHERE id=".$shipId);
				$this->msghandle("Explosion durch entzündetes Metreongas - Schilde brechen zusammen, Hülle jetzt bei ".$srhuelle);
			}
			else
			{
				$srhuelle = $ship1[huelle] - $selfschaden;
				if ($srhuelle < 1)
				{
					$this->trumfield($shipId);
					$myHistory->addEvent("Die ".addslashes($ship1[name])." wurde in Sektor ".$ship1[coords_x]."/".$ship1[coords_y].($ship1[wese] == 2 ? " (2)" : "")." durch eine Metreongasexplosion zerstört",$ship1[user_id]);
					$this->msghandle("Explosion durch entzündetes Metreongas - Hüllenbruch!<br>Das Schiff wurde zerstört");
					return $this->cret($destroy);
				}
				$this->db->query("UPDATE stu_ships SET huelle=".$srhuelle." WHERE id=".$shipId);
				$this->msghandle("Explosion durch entzündetes Metreongas - Hüllenschaden: ".$selfschaden.", Hülle jetzt bei ".$srhuelle);
			}
		}
		if ($decloak != 0 && $react == 0 && $ship2[alertlevel] > 1 && $ship2[crew] > 0 && $ship2[energie] > 0)
		{
			$this->deactivatevalue($ship2[id],"cloak",$ship2[user_id]);
			$this->msghandle("Die ".$ship2[name]." deaktiviert die Tarnung");
			if ($ship2[schilde] > 0)
			{
				$this->activatevalue($ship2[id],"schilde_aktiv",$ship2[user_id]);
				$this->msghandle("Die ".$ship2[name]." aktiviert die Schilde");
				$sa = 1;
			}
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$shipId);
		$waffe = $this->getModuleById($ship1[waffenmodlvl]);
		$nr = 0;
		if (!$sa) $sa = $ship2[schilde_aktiv];
		$schilde = $ship2[schilde];
		$huelle = $ship2[huelle];
		global $myComm;
		$phaserdam = $ship1[maxphaser];
		if ($ship2[schilde_aktiv] == 1 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$ship2[schildmodlvl],1) == "Regenerativ") $phaserdam = round($phaserdam * 0.8);
		if ($ship2[schilde_aktiv] == 0 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$ship2[huellmodlvl],1) == "Ablativ") $phaserdam = round($phaserdam * 0.8);
		if ($ship2[schilde_aktiv] == 0 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$ship2[huellmodlvl],1) == "Reparatursystem") $phaserdam = round($phaserdam * 0.5);
		if ($waffe[besonder] == "Pulswaffe")
		{
		// -------- Pulsphaser
			$this->msghandle("Die ".$ship1[name]." schießt mit einer Pulswaffe auf die ".$ship2[name],3);
			for ($i=0;$i<3;$i++)
			{
				$sad = 0;
				$nr++;
				if (rand(1,100) > $ship1[maxtreffer])
				{
					$this->msghandle("Schuss ".$nr." hat die ".$ship2[name]." verfehlt",3);
					continue;
				}
				if ((rand(1,100) > 60) && ($ship2[ships_rumps_id] == 165))
				{
					$this->msghandle("Die ".$ship2[name]." führt ein Ausweichmanöver durch - Schuss ".$nr." hat sein Ziel verfehlt",3);
					continue;
				}
				if ($sa == 1 && $schilde > 0)
				{
					$schilde -= $phaserdam;
					if ($schilde <=0)
					{
						$huelle -= abs($schilde);
						$schilde = 0;
						$sa = 0;
						$sad = 1;
					}
					if ($huelle <= 0) $destroy = 1;
				}
				else
				{
					$huelle -= $phaserdam;
					if ($huelle < 1) $destroy = 1;
				}
				if ($destroy == 1 && $ship2[c][trumfield] == 0)
				{
					$this->trumfield($shipId2);
					$this->msghandle("Schuss ".$nr." trifft - Hüllenbruch!<br>Das Schiff wurde zerstört!",3);
					$myHistory->addEvent("Die ".addslashes($ship2[name])." wurde in Sektor ".$ship2[coords_x]."/".$ship2[coords_y].($ship2[wese] == 2 ? " (2)" : "")." von der ".addslashes($ship1[name])." zerstört",$ship2[user_id],1);
					break;
				}
				if ($destroy == 1 && $ship2[c][trumfield] == 1)
				{
					$this->msghandle("Schuss ".$nr." trifft - Das Trümmerfeld wurde beseitigt");
					$this->deletetrumfield($shipId2);
					break;
				}
				if ($destroy == 0 && $ship2[c][trumfield] == 1)
				{
					$this->db->query("UPDATE stu_ships SET huelle=".$huelle." WHERE id=".$shipId2);
					$this->msghandle("Schuss ".$nr." trifft - Status: ".$huelle,3);
				}
				if ($destroy == 0 && $ship2[c][trumfield] == 0)
				{
					$this->db->query("UPDATE stu_ships SET huelle=".$huelle.",schilde=".$schilde.",schilde_aktiv=".$sa." WHERE id=".$shipId2);
					if ($huelle > 0 && $sa == 1) $this->msghandle("Schuss ".$nr." trifft - Schilde bei ".$schilde,3);
					elseif ($sad == 1) $this->msghandle("Schuss ".$nr." trifft - Schilde brechen zusammen - Hülle bei ".$huelle,3);
					else $this->msghandle("Schuss ".$nr." trifft - Hülle bei ".$huelle,3);
				}
			}
			if ($destroy == 1)
			{
				$return[msg] = $this->returnmsg;
				$this->returndestroy = 1;
				$myComm->sendpm($ship2[user_id],$this->user,$this->pmmsg,2);
				return $this->cret($destroy);
			}
		}
		elseif ($waffe[besonder] == "Energiedisruptor" && $ship2[user_id] != 26 && $ship2[user_id] != 5)
		{
		// -------- Energiedisruptor
			if (rand(1,100) > $ship1[maxtreffer])
			{
				$this->msghandle("Die ".$ship1[name]." hat die ".$ship2[name]." verfehlt",3);
				$miss = 1;
			}
			elseif ((rand(1,100) > 60) && ($ship2[ships_rumps_id] == 165))
			{
				$this->msghandle("Die ".$ship2[name]." führt ein Ausweichmanöver durch - Der Phaserschuss hat sein Ziel verfehlt",3);
				$miss = 1;
			}
			else
			{
				$this->msghandle("Die ".$ship1[name]." schießt auf die ".$ship2[name]." - kein direkter Schaden",3);
				$ship2[epsupgrade] == 1 ? $this->msghandle("Teilweiser Zusammenbruch aller Energiesysteme",3) : $this->msghandle("Kompletter Zusammenbruch aller Energiesysteme");
				$breen = 1;
				$class1 = $this->getclassbyid($ship1[ships_rumps_id]);
				if ($ship1[waffenmodlvl] == 43)
				{
					$breendshld = 60 * $class1[waffenmod];
					$breendener = 30 * $class1[waffenmod];
					$breendbatt = 22 * $class1[waffenmod];
				}
				elseif ($ship1[waffenmodlvl] == 46)
				{
					$breendshld = 40 * $class1[waffenmod];
					$breendener = 20 * $class1[waffenmod];
					$breendbatt = 15 * $class1[waffenmod];
				}
				if ($ship2[epsupgrade] == 1)
				{
					$breendshld = round($breendshld/2);
					$breendener = round($breendener/2);
					$breendbatt = round($breendbatt/2);
				}
				if ($ship2[schilde_aktiv] == 1)
				{
					$this->deactivatevalue($ship2[id],"schilde_aktiv",$ship2[user_id]);
					$this->msghandle("Die Schilde der ".$ship2[name]." brechen zusammen",3);
				}
				if ($ship2[cloak] == 1)
				{
					$this->deactivatevalue($ship2[id],"cloak",$ship2[user_id]);
					$this->msghandle("Die Tarnung der ".$ship2[name]." fällt aus",3);
					$this->db->query("DELETE FROM stu_ships_uncloaked WHERE ships_id=".$ship2[id]);
				}
				if ($ship2[epsupgrade] != 1)
				{
					//-----Impuls
					$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$ship2[id]." AND mode='impdef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+300)."' WHERE ships_id =".$ship2[id]." AND mode='impdef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$ship2[id]."','impdef','".(time()+300)."')");
					//-----LSS
					$this->db->query("UPDATE stu_ships SET lss=0 WHERE id=".$ship2[id]);
					$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$ship2[id]." AND mode='lsendef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+300)."' WHERE ships_id =".$ship2[id]." AND mode='lsendef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$ship2[id]."','lsendef','".(time()+300)."')");
					//------KSS
					$this->db->query("UPDATE stu_ships SET kss=0 WHERE id=".$ship2[id]);
					$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$ship2[id]." AND mode='ksendef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+300)."' WHERE ships_id =".$ship2[id]." AND mode='ksendef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$ship2[id]."','ksendef','".(time()+300)."')");
					//------Schilde
					$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$ship2[id]." AND mode='shidef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+300)."' WHERE ships_id =".$ship2[id]." AND mode='shidef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$ship2[id]."','shidef','".(time()+300)."')");
					//------Reaktor
					$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$ship2[id]." AND mode='readef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+300)."' WHERE ships_id =".$ship2[id]." AND mode='readef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$ship2[id]."','readef','".(time()+300)."')");
					//------Waffen
					$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$ship2[id]." AND mode='waffdef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+300)."' WHERE ships_id =".$ship2[id]." AND mode='waffdef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$ship2[id]."','waffdef','".(time()+300)."')");
					//------Tarnung
					$this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$ship2[id]." AND mode='cloakdef'",1) != 0 ? $this->db->query("UPDATE stu_ships_action SET ships_id2='".(time()+300)."' WHERE ships_id =".$ship2[id]." AND mode='cloakdef'") : $this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$ship2[id]."','cloakdef','".(time()+300)."')");
				}
				$schilde -= $breendshld;
				if ($schilde < 0) $schilde = 0;
				$energie = $ship2[energie] - $breendener;
				if ($energie < 0) $energie = 0;
				$batt = $ship2[batt] - $breendbatt;
				if ($batt < 0) $batt = 0;
				$this->db->query("UPDATE stu_ships SET schilde=".$schilde.",energie=".$energie.",batt=".$batt." WHERE id=".$ship2[id]);
			}
		}
		else
		{
		// -------- Normal
			if (rand(1,100) > $ship1[maxtreffer])
			{
				$this->msghandle("Die ".$ship1[name]." hat die ".$ship2[name]." verfehlt",3);
				$miss = 1;
			}
			elseif ((rand(1,100) > 60) && ($ship2[ships_rumps_id] == 165))
			{
				$this->msghandle("Die ".$ship2[name]." führt ein Ausweichmanöver durch - Der Phaserschuss hat sein Ziel verfehlt",3);
				$miss = 1;
			}
			else
			{
				if ($ship1[waffenmodlvl] == 53)
				{
				// -------- Bio-Impulsstrahl
					$ship2[schilde_aktiv] == 1 ? $crewkill = rand(0,floor($ship2[crew]*0.2)) : $crewkill = rand(0,floor($ship2[crew]*0.4));
					$this->msghandle("Strahlung durchflutet das Schiff - ".$crewkill." Crewmitglieder sterben",3);
					$this->db->query("UPDATE stu_ships SET crew=crew-".$crewkill." WHERE id=".$ship2[id]);
				}
				if ((($ship2[schilde_aktiv] == 1) && ($ship2[schilde] > 0) && ($waffe[besonder] == "Partikelwaffe")) || (($ship2[schilde_aktiv] == 0) && ($waffe[besonder] == "Plasmawaffe")))
				{
					$phaserdam = round($phaserdam * 0.6);
				}
				if (($ship2[schilde_aktiv] == 1) && ($ship2[schilde] > 0))
				{
					if (($ship1[waffenmodlvl] >= 60) && ($ship1[waffenmodlvl] <= 62))
					{
					// -------- Polaronstrahl
						if (rand(1,100) <= 40)
						{
							$durch = 1;
							$huelle -= $phaserdam;
							if ($huelle < 1) $destroy = 1;
							$this->msghandle("Schuss durchdringt die Schilde",3);
						}
						else
						{
							$durch = 0;
							$schilde -= $phaserdam;
							if ($schilde <= 0)
							{
								$huelle -= abs($schilde);
								$schilde = 0;
								$sa = 0;
								$sad = 1;
							}
						}
					}
					else
					{
						$schilde -= $phaserdam;
						if ($schilde <= 0)
						{
							$huelle -= abs($schilde);
							$schilde = 0;
							$sa = 0;
							$sad = 1;
						}
					}
				}
				else $huelle -= $phaserdam;
			}
			if ($huelle < 1) $destroy = 1;
			if ($miss != 1)
			{
				if ($destroy == 1 && $ship2[c][trumfield] == 0)
				{
					$this->trumfield($shipId2);
					$this->msghandle("Die ".$ship1[name]." schießt auf die ".$ship2[name]." - Hüllenbruch!<br>Das Schiff wurde zerstört!",3);
					if ($ship2[c][probe] == 0) $myHistory->addEvent("Die ".addslashes($ship2[name])." wurde in Sektor ".$ship2[coords_x]."/".$ship2[coords_y].($ship2[wese] == 2 ? " (2)" : "")." von der ".addslashes($ship1[name])." zerstört",$ship2[user_id],1);
					$this->returndestroy = 1;
					$myComm->sendpm($ship2[user_id],$this->user,$this->pmmsg,2);
					return $this->cret($destroy);
				}
				if ($destroy == 1 && $ship2[c][trumfield] == 1)
				{
					$this->msghandle("Die ".$ship1[name]." schießt im Sektor ".$ship1[coords_x]."/".$ship1[coords_y]." auf das Trümmerfeld - Das Trümmerfeld wurde beseitigt",3);
					$this->deletetrumfield($shipId2);
					$this->returndestroy = 1;
					$myComm->sendpm($ship2[user_id],$this->user,$this->pmmsg,2);
					return $this->cret($destroy);
				}
				if ($destroy == 0 && $ship2[c][trumfield] == 1) $this->msghandle("Die ".$ship1[name]." schießt auf das Trümmerfeld - Status: ".$huelle,3);
				else
				{
					if ($schilde > 0 && $durch == 0 && $sa == 1 && $sad == 0) $this->msghandle("Die ".$ship1[name]." schießt auf die ".$ship2[name]."<br>Die Schilde der ".$ship2[name]." sind bei ".$schilde,3);
					elseif ($durch == 1) $this->msghandle("Die ".$ship1[name]." schießt auf die ".$ship2[name]."<br>Die Hülle der ".$ship2[name]." ist bei ".$huelle,3);
					elseif ($sad == 1) $this->msghandle("Die ".$ship1[name]." schießt auf die ".$ship2[name]."<br>Die Schilde der ".$ship2[name]." brechen zusammen<br>Die Hülle ist bei ".$huelle,3);
					else $this->msghandle("Die ".$ship1[name]." schießt auf die ".$ship2[name]."<br>Die Hülle der ".$ship2[name]." ist bei ".$huelle,3);
				}
				$this->db->query("UPDATE stu_ships SET huelle=".$huelle.",schilde=".$schilde.",schilde_aktiv=".$sa." WHERE id=".$shipId2);
				if ($destroy == 0 && $sa == 0 && $ship2[c][trumfield] == 0)
				{
					$hke = ceil((100/$ship2[maxhuell])*$huelle);
					if (rand(1,50) > $hke && $this->checkss("lsendef",$ship2[id]) == 0)
					{
						$this->msghandle("Die Langstreckensensoren der ".$ship2[name]." sind ausgefallen");
						$dur = time()+rand(1800,18000);
						$this->msghandle("Die Langstreckensensoren der ".$ship2[name]." sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
						$this->ssdamage("lsendef",$ship2[id],$dur);
					}
					if (rand(1,60) > $hke && $this->checkss("cloakdef",$ship2[id]) == 0 && $ship2[c][cloak] == 1)
					{
						$dur = time()+rand(3600,25200);
						$this->msghandle("Die Tarnung der ".$ship2[name]." ist ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
						$this->ssdamage("cloakdef",$ship2[id],$dur);
					}
					if (rand(1,25) > $hke && $this->checkss("impdef",$ship2[id]) == 0)
					{
						$this->msghandle("Der Antrieb der ".$ship2[name]." ist ausgefallen");
						$dur = time()+rand(1200,14400);
						$this->msghandle("Der Antrieb der ".$ship2[name]." ist ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
						$this->ssdamage("impdef",$ship2[id],$dur);
					}
					if (rand(1,30) > $hke && $this->checkss("waffdef",$ship2[id]) == 0)
					{
						$this->msghandle("Die Waffen der ".$ship2[name]." sind ausgefallen");
						$dur = time()+rand(1200,14400);
						$this->msghandle("Die Waffen der ".$ship2[name]." sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
						$this->ssdamage("waffdef",$ship2[id],$dur);
					}
					if (rand(1,20) > $hke && $this->checkss("ksendef",$ship2[id]) == 0)
					{
						$this->msghandle("Die Kurzstreckensensoren der ".$ship2[name]." sind ausgefallen");
						$dur = time()+rand(1800,18000);
						$this->msghandle("Die Kurzstreckensensoren der ".$ship2[name]." sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
						$this->ssdamage("ksendef",$ship2[id],$dur);
					}
					if (rand(1,30) > $hke && $this->checkss("shidef",$ship2[id]) == 0)
					{
						$this->msghandle("Die Schilde der ".$ship2[name]." sind ausgefallen");
						$dur = time()+rand(1800,21600);
						$this->msghandle("Die Schilde der ".$ship2[name]." sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
						$this->ssdamage("shidef",$ship2[id],$dur);
					}
					if (rand(1,10) > $hke && $this->checkss("readef",$ship2[id]) == 0 && $ship2[reaktormodlvl] > 0)
					{
						$dur = time()+rand(7200,36000);
						$this->msghandle("Der Warpkern der ".$ship2[name]." ist ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
						$this->ssdamage("readef",$ship2[id],$dur);
					}
				}
			}
		}
		if ($field[type] == 31) $strbk == 0;
		if (($ship2[fleets_id] > 0) && ($ship2[user_id] != $userId) && ($strbk == 1))
		{
			$this->msghandle("<br><strong>Gegenwehr</strong>");
			$flqry = $this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$ship2[fleets_id]." AND crew>1 AND energie>0 AND alertlevel>0");
			for ($i=0;$i<mysql_num_rows($flqry);$i++)
			{
				if ($strbmsg[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
				$fldat = mysql_fetch_assoc($flqry);
				$fldat[id] == $shipId2 ? $flret = $this->strikeback($shipId,$fldat[id],$userId,1,$breen,5) : $flret = $this->strikeback($shipId,$fldat[id],$userId,1,0,5);
				if ($flretmsg[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
			}
		}
		else if (($ship2[energie] > 0) && ($ship2[alertlevel] > 1) && (($ship2[crew] > 0) || (($ship2[ships_rumps_id] == 189) || ($ship2[ships_rumps_id] == 210) || ($ship2[ships_rumps_id] == 167))) && ($class2[trumfield] == 0) && ($sdestroy == 0) && ($strbk == 1)) $strbmsg = $this->strikeback($shipId,$shipId2,$userId,1,$breen);
		if ($destroy == 0 && $strbk == 1)
		{
			global $myUser;
			$ally = $myUser->getfield("allys_id",$ship2[user_id]);
			if ($ally > 0)
			{
				$ares = $this->db->query("SELECT a.id,a.user_id FROM stu_ships as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.alertlevel>1 AND a.energie>0 AND a.crew>0 AND a.user_id!=".$ship2[user_id]." AND a.coords_x=".$ship1[coords_x]." AND a.coords_y=".$ship1[coords_y]." AND b.allys_id=".$ally);
				for($i=0;$i<mysql_num_rows($ares);$i++)
				{
					$adat = mysql_fetch_assoc($ares);
					$res = $this->strikeback($shipId,$adat[id],$adat[user_id]);
				}
				if ($res[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
			}
		}
		if (($destroy != 1) && ($ship2[ships_rupms_id == 136] == 1)) 
		if ($destroy != 1 && $df == 1)
		{
			$result = $this->db->query("SELECT ships_id from stu_ships_action WHERE mode='defend' AND ships_id2=".$ship2[id]."",$this->dblink);
			for ($i=0;$i<mysql_num_rows($result);$i++)
			{
				$tmp = mysql_fetch_assoc($result);
				$defend = $this->getdatabyid($tmp[ships_id]);
				if ($defend[coords_x] == $ship1[coords_x] && $defend[coords_y] == $ship1[coords_y] && $defend[crew] > 0 && $defend[alertlevel] > 1 && $defend[energie] > 0)
				{
					$defend[strb_mode] == 1 ? $lret = $this->strikeback($shipId,$defend[id],$defend[user_id],1,0,3) : $lret = $this->strikeback($shipId,$defend[id],$defend[user_id],1,0,3);
					if ($lret[destroy] == 1)
					{
						$destroy = 1;
						break;
					}
				}
			}
		}
		if (($destroy != 1) && (($ship2[ships_rumps_id] == 136) || ($ship2[ships_rumps_id] == 174)))
		{
			$this->npcmvam($shipId2);
			$this->msghandle("Die ".$ship2[name]." aktiviert den Multi-Vektor-Angriffsmodus");
		}
		if ($ship1[user_id] != $ship2[user_id] && $this->pmmsg != "") $myComm->sendpm($ship2[user_id],$ship1[user_id],"<strong>Kampf in Sektor ".$ship1[coords_x]."/".$ship1[coords_y]."</strong><br>".$this->pmmsg,2);
		$this->returncode = 1;
		return $this->cret($destroy);
	}
	
	function deletetrumfield($shipId)
	{
		if ($this->db->query("SELECT ships_rumps_id FROM stu_ships WHERE id=".$shipId,1) == 208)
		{
			$this->db->query("UPDATE stu_ships SET ships_rumps_id=3,huelle=10,trumoldrump=3 WHERE id=".$shipId);
			if ($shipId == 67434) $this->upperstoragebygoodid(5,33,$shipId,2);
			if ($shipId == 67581) $this->upperstoragebygoodid(5,215,$shipId,2);
			if ($shipId == 67664) $this->upperstoragebygoodid(5,235,$shipId,2);
			if ($shipId == 67788) $this->upperstoragebygoodid(5,307,$shipId,2);
			if ($shipId == 67909) $this->upperstoragebygoodid(4,98,$shipId,2);
			if ($shipId == 68045) $this->upperstoragebygoodid(2,150,$shipId,2);
			if ($shipId == 68140) $this->upperstoragebygoodid(5,317,$shipId,2);
			if ($shipId == 68282) $this->upperstoragebygoodid(5,220,$shipId,2);
			if ($shipId == 68283) $this->upperstoragebygoodid(5,42,$shipId,2);
			if ($shipId == 68415) $this->upperstoragebygoodid(5,23,$shipId,2);
			if ($shipId == 68574) $this->upperstoragebygoodid(5,49,$shipId,2);
			if ($shipId == 68575) $this->upperstoragebygoodid(5,220,$shipId,2);
			
			if ($shipId == 68999) $this->upperstoragebygoodid(5,21,$shipId,2);
			if ($shipId == 69000) $this->upperstoragebygoodid(5,22,$shipId,2);
			if ($shipId == 69001) $this->upperstoragebygoodid(5,25,$shipId,2);
			if ($shipId == 69002) $this->upperstoragebygoodid(5,23,$shipId,2);
			if ($shipId == 69003) $this->upperstoragebygoodid(5,33,$shipId,2);
			if ($shipId == 69409) $this->db->query("UPDATE stu_ships SET coords_x=100,coords_y=100 WHERE id=69408");
			global $myHistory,$user;
			$myHistory->addEvent($this->db->query("SELECT name FROM stu_ships WHERE id=".$shipId,1)." wurde von ".addslashes($this->db->query("SELECT user FROM stu_user WHERE id=".$user,1))." geöffnet",$user,1);
			return 0;
		}
		$this->db->query("DELETE FROM stu_ships WHERE id=".$shipId);
		$this->db->query("DELETE FROM stu_ships_storage WHERE ships_id=".$shipId);
	}
	
	function strikeback($shipId,$shipId2,$userId,$msg=1,$breen=0,$sbtype=1)
	{
		if ($shipId == $shipId2) return 0;
		unset($msg);
		if ($shipId == 0 || $shipId2 == 0) return 0;
		$shipdata = $this->db->query("SELECT user_id FROM stu_ships WHERE id=".$shipId,4);
		$shipdata = $this->getdatabyid($shipId);
		$target = $this->getdatabyid($shipId2);
		global $myMap,$myUser;
		if ($shipdata == 0) return 0;
		if ($myUser->getfield("level",$shipdata[user_id]) < 2) return 0;
		if ($target[c][trumfield] == 1) return 0;
		if ($target[energie] == 0) return 0;
		if ($target[traktormode] == 2) return 0;
		if ($target[alertlevel] == 3)
		{
			$this->db->query("UPDATE stu_ships SET traktor=0,traktormode=0 WHERE traktormode=1 AND id=".$shipId2);
			$this->db->query("UPDATE stu_ships SET dock=0 WHERE dock=".$target[id]);
		}
		if ($target[cloak] == 1)
		{
			$this->db->query("UPDATE stu_ships SET cloak=0 WHERE id=".$target[id]." AND alertlevel>1");
			$msg .= "Die ".$target[name]." enttarnt sich<br>";
		}
		if ($breen == 0 && $target[schilde_aktiv] == 0 && $target[schilde] > 0)
		{
			$this->activatevalue($target[id],"schilde_aktiv",$target[user_id]);
			$msg .= "Die ".$target[name]." aktiviert die Schilde<br>";
		}
		if ($target[c][crew_min] > $target[crew]) $nr = 1;
		$tt = $this->gettorptype($shipId2);
		if ($target[waffenmodlvl] == 0 && $target[strb_mode] == 1 && $tt == 0) $nr = 1;
		if ($target[ships_rumps_id] >= 65 && $target[ships_rumps_id] <= 68 && $target[strb_mode] == 2 && $tt == 0 && $target[waffenmodlvl] == 0) $nr = 1;
		if ($nr == 1)
		{
			$return[msg] .= "Die ".$target[name]." führt keine Reaktion durch";
			return $return;
		}
		if (($target[ships_rumps_id] == 211) && ($target[alertlevel] != 1))
		{
			if ($this->getcountbygoodid(308,$target[id]) == 0)
			{
				$msg .= "Hangardeck ist leer<br>";
			}
			elseif ($this->getcountbygoodid(308,$target[id]) == 1)
			{
				$this->npclaunchfighter($target[id],1);
				$msg .= "Jäger wurde gestartet<br>";
			}
			else
			{
				$this->npclaunchfighter($target[id],1);
				$this->npclaunchfighter($target[id],1);
				$msg .= "Jägerstaffel wurde gestartet<br>";
			}
		}
		if ($target[strb_mode] == 2 && $tt != 0) $strb = $this->torp($shipId2,$shipId,$target[user_id],$this->gettorptype($shipId2),0,$sbtype,0);
		else $strb = $this->phaser($shipId2,$shipId,$target[user_id],0,1,$sbtype,0);
		$return[destroy] = $strb[destroy];
		$return[msg] = $msg;
		$return[code] = 1;
		return $return;
	}
	
	function gettorptype ($shipId) { return $this->db->query("SELECT a.id FROM stu_torpedo_types as a LEFT OUTER JOIN stu_ships_storage as b ON a.goods_id=b.goods_id WHERE b.ships_id=".$shipId,1); }
	
	function gettorptypegood ($shipId) { return $this->db->query("SELECT a.goods_id FROM stu_torpedo_types as a LEFT OUTER JOIN stu_ships_storage as b ON a.goods_id=b.goods_id WHERE b.ships_id=".$shipId,1); }
	
	function trumfield($shipId)
	{
		$shipdata = $this->getdatabyid($shipId);
		if ($shipdata == 0) return 0;
		global $myFleet;
		if ($shipdata[c][trumfield] == 1) return 0;
		if ($shipdata[c][probe] == 1) return $this->db->query("DELETE FROM stu_ships WHERE id=".$shipId);
		if ($shipdata[ships_rumps_id] == 111)
		{
			$this->db->query("DELETE FROM stu_ships_storage WHERE ships_id=".$shipId);
			$this->db->query("DELETE FROM stu_ships_buildprogress WHERE ships_id=".$shipId);
		}
		if ($shipdata[ships_rumps_id] == 123)
		{
			$this->db->query("UPDATE stu_map_fields SET type=22 WHERE coords_x=".$shipdata[coords_x]." AND coords_y=".$shipdata[coords_y]);
			$this->db->query("UPDATE stu_map_fields SET type=28 WHERE coords_x=".($shipdata[coords_x]-1)." AND coords_y=".$shipdata[coords_y]);
			$this->db->query("UPDATE stu_map_fields SET type=28 WHERE coords_x=".($shipdata[coords_x]+1)." AND coords_y=".$shipdata[coords_y]);
			$this->db->query("UPDATE stu_map_fields SET type=28 WHERE coords_x=".$shipdata[coords_x]." AND coords_y=".($shipdata[coords_y]-1));
			$this->db->query("UPDATE stu_map_fields SET type=28 WHERE coords_x=".$shipdata[coords_x]." AND coords_y=".($shipdata[coords_y]+1));
			$this->db->query("UPDATE stu_map_fields SET type=1 WHERE coords_x=".($shipdata[coords_x]-1)." AND coords_y=".($shipdata[coords_y]-1));
			$this->db->query("UPDATE stu_map_fields SET type=1 WHERE coords_x=".($shipdata[coords_x]+1)." AND coords_y=".($shipdata[coords_y]-1));
			$this->db->query("UPDATE stu_map_fields SET type=1 WHERE coords_x=".($shipdata[coords_x]-1)." AND coords_y=".($shipdata[coords_y]+1));
			$this->db->query("UPDATE stu_map_fields SET type=1 WHERE coords_x=".($shipdata[coords_x]+1)." AND coords_y=".($shipdata[coords_y]+1));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]-2)." AND coords_y=".$shipdata[coords_y]);
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]-2)." AND coords_y=".($shipdata[coords_y]-1));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]-2)." AND coords_y=".($shipdata[coords_y]+1));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]+2)." AND coords_y=".$shipdata[coords_y]);
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]+2)." AND coords_y=".($shipdata[coords_y]-1));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]+2)." AND coords_y=".($shipdata[coords_y]+1));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".$shipdata[coords_x]." AND coords_y=".($shipdata[coords_y]-2));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]-1)." AND coords_y=".($shipdata[coords_y]-2));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]+1)." AND coords_y=".($shipdata[coords_y]-2));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".$shipdata[coords_x]." AND coords_y=".($shipdata[coords_y]+2)."",$this->dblink);
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]-1)." AND coords_y=".($shipdata[coords_y]+2));
			$this->db->query("UPDATE stu_map_fields SET type=27 WHERE coords_x=".($shipdata[coords_x]+1)." AND coords_y=".($shipdata[coords_y]+2));
			$result = $this->db->query("SELECT id,user_id,coords_x,coords_y,name FROM stu_ships WHERE coords_x=".$shipdata[coords_x]." AND coords_y=".$shipdata[coords_y]." AND id!=".$shipId." AND ships_rumps_id!=123 AND ships_rumps_id!=3");
			global $myComm;
			for ($i=0;$i<mysql_num_rows($result);$i++)
			{
				$tmpdat = mysql_fetch_assoc($result);
				$this->trumfield($tmpdat[id]);
				$myComm->sendpm($tmpdat[user_id],"2","Die ".$tmpdat[name]." wurde in Sektor ".$tmpdat[coords_x]."/".$tmpdat[coords_y]." durch eine Subraumexplosion zerstört",2,1);
			}
		}
		$fleet = $myFleet->getfleetbyshipid($shipId,$shipdata[user_id]);
		if ($fleet != 0)
		{
			if ($myFleet->getfleetshipcount($fleet[id]) > 1) $this->db->query("UPDATE stu_fleets SET ships_id=".$this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$fleet[id]." AND id!=".$shipId." ORDER BY huelle,schilde",1)." WHERE id=".$fleet[id]);
			else
			{
				$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE fleets_id='".$fleet[id]."' AND user_id=".$shipdata[user_id]);
				$this->db->query("DELETE FROM stu_fleets WHERE id=".$fleet[id]);
			}
		}
		if ($shipdata[ships_rumps_id] != 111)
		{
			for ($i=0;$i<$shipdata[c][huellmod];$i++)
			{
				$module = $this->getmodulebyid($shipdata[huellmodlvl]);
				if (rand(1,100) <= ($module[demontchg]/2)) $this->upperstoragebygoodid(1,$module[goods_id],$shipdata[id],2);
			}
			for ($i=0;$i<$shipdata[c][schilmod];$i++)
			{
				$module = $this->getmodulebyid($shipdata[schildmodlvl]);
				if (rand(1,100) <= ($module[demontchg]/2)) $this->upperstoragebygoodid(1,$module[goods_id],$shipdata[id],2);
			}
			for ($i=0;$i<$shipdata[c][epsmod];$i++)
			{
				$module = $this->getmodulebyid($shipdata[epsmodlvl]);
				if (rand(1,100) <= ($module[demontchg]/2)) $this->upperstoragebygoodid(1,$module[goods_id],$shipdata[id],2);
			}
			if ($shipdata[waffenmodlvl] > 0)
			{
				for ($i=0;$i<$shipdata[c][waffenmod];$i++)
				{
					$module = $this->getmodulebyid($shipdata[waffenmodlvl]);
					if (rand(1,100) <= ($module[demontchg]/2)) $this->upperstoragebygoodid(1,$module[goods_id],$shipdata[id],2);
				}
			}
			for ($i=0;$i<$shipdata[c][sensormod];$i++)
			{
				$module = $this->getmodulebyid($shipdata[sensormodlvl]);
				if (rand(1,100) <= ($module[demontchg]/2)) $this->upperstoragebygoodid(1,$module[goods_id],$shipdata[id],2);
			}
			if ($shipdata[reaktormodlvl] > 0)
			{
				$module = $this->getmodulebyid($shipdata[reaktormodlvl]);
				if (rand(1,100) <= ($module[demontchg]/2)) $this->upperstoragebygoodid(1,$module[goods_id],$shipdata[id],2);
			}
			if ($shipdata[antriebmodlvl] > 0)
			{
				$module = $this->getmodulebyid($shipdata[antriebmodlvl]);
				if (rand(1,100) <= ($module[demontchg]/2)) $this->upperstoragebygoodid(1,$module[goods_id],$shipdata[id],2);
			}
			$module = $this->getmodulebyid($shipdata[computermodlvl]);
			if (rand(1,100) <= ($module[demontchg]/2)) $this->upperstoragebygoodid(1,$module[goods_id],$shipdata[id],2);
		}
		$nh = ceil(($shipdata[maxhuell]/100)*20);
		if ($nh < 1) $nh = rand(1,10);
		$this->db->query("DELETE FROM stu_ships_action WHERE ships_id='".$shipId."' OR ships_id2='".$shipId."'",$this->dblink);
		$this->db->query("UPDATE stu_ships SET name='Wrack',user_id='2',ships_rumps_id='3',fleets_id=0,huelle='".$nh."',schilde_aktiv=0,schilde=0,alertlevel=1,cloak=0,batt=0,crew=0,cloak=0,tachyon=0,energie=0,traktor=0,traktormode=0,trumoldrump='".$shipdata[ships_rumps_id]."',dock=0 WHERE id=".$shipId);
		$this->db->query("UPDATE stu_ships SET traktormode=0,traktor=0 WHERE traktor=".$shipId);
		$this->db->query("UPDATE stu_ships SET dock=0 WHERE dock=".$shipId);
		$this->db->query("DELETE FROM stu_ships_storage WHERE (goods_id=7 OR goods_id=16 OR goods_id=17 OR goods_id=27 OR goods_id=29 OR goods_id=40 OR goods_id=41 OR goods_id=209 OR goods_id=216 OR goods_id=201 OR goods_id=202 OR goods_id=203 OR goods_id=205 OR goods_id=308) AND ships_id=".$shipId);
		$this->db->query("INSERT INTO stu_ships_action (mode,ships_id,ships_id2) VALUES ('deltrum','".$shipId."','".(time()+2592000)."')");
		global $myUser;
		$myUser->getfield("level",$shipdata[user_id]) == 1 ? $this->db->query("DELETE FROM stu_ships_storage WHERE ships_id=".$shipId) : $this->db->query("UPDATE stu_ships_storage SET user_id='2' WHERE ships_id=".$shipId);
	}
	
	function getcountbygoodid($goodId,$shipId) { return $this->db->query("SELECT count FROM stu_ships_storage WHERE ships_id=".$shipId." AND goods_id=".$goodId,1); }
	
	function transfercrew($shipId,$shipId2,$count,$mode,$way)
	{
		if ($this->cshow == 0) return 0;
		if ($count < 0) return 0;
		if ($this->ccloak == 1)
		{
			$return[msg] = "Die Tarnung der ".$this->cname." ist aktiviert";
			return $return;
		}
		if ($this->cschilde_aktiv == 1)
		{
			$return[msg] = "Die Schilde der ".$this->cname." sind aktiviert";
			return $return;
		}
		
		if ($mode == "col")
		{
			global $myColony;
			$data2 = $myColony->getcolonybyid($shipId2);
			$data2[user_id] == $this->user ? $img = "<a href=?page=colony&section=showcolony&id=".$data2[id]."><img src=".$this->gfx."/planets/".$data2[colonies_classes_id].".gif border=0></a>" : $img = "<img src=".$this->gfx."/planets/".$data2[colonies_classes_id].".gif border=0>";
		}
		else
		{
			$data2 = $this->getdatabyid($shipId2);
			if ($data2[damaged] == 1) $mpf = "d/";
			$this->user == $data2[user_id] ? $img = "<a href=?page=ship&section=showship&id=".$data2[id]."><img src=".$this->gfx."/ships/".$mpf.$data2[ships_rumps_id].".gif border=0></a>" : $img = "<img src=".$this->gfx."/ships/".$mpf.$data2[ships_rumps_id].".gif border=0>";
		}
		if ($this->user != $data2[user_id]) return 0;
		if ($this->ccoords_x != $data2[coords_x] || $this->ccoords_y != $data2[coords_y]) return 0;
		if ($this->cwese != $data2[wese]) return 0;
		if ($way == "to")
		{
			if ($this->ccrew < $count) $count = $this->ccrew;
			$wayimg = "b_to2.gif";
		}
		if ($way == "from")
		{
			if ($this->ccrew + $count > $this->cclass[crew]) $count = $this->cclass[crew]-$this->ccrew;
			$wayimg = "b_from2.gif";
		}
		if ($mode == "col")
		{
			if ($way == "to")
			{
				if ($data2[max_bev] < $data2[bev_used] + $data2[bev_free])
				{
					$return[msg] = "Aufgrund von Wohnraummangel können keine Crewmitglieder transferiert werden";
					return $return;
				}
				if ($data2[max_bev] - $data2[bev_used] - $data2[bev_free] < $count) $count = $data2[max_bev] - $data2[bev_used] - $data2[bev_free];
			}
			else if ($data2[bev_free] < $count) $count = $data2[bev_free];
		}
		else
		{
			if ($way == "to")
			{
				if ($data2[crew] >= $data2[c][crew])
				{
					$return[msg] = "Auf der ".$data2[name]." sind alle Crewquartiere belegt";
					return $return;
				}
				if ($count + $data2[crew] > $data2[c][crew]) $count = $data2[c][crew] - $data2[crew];
			}
			else if ($data2[crew] < $count) $count = $data2[crew];
		}
		if ($this->cclass[crew_min] -2 > $this->ccrew)
		{
			$return[msg] = "Zum beamen werden ".($this->cclass[crew_min]-2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($count == 0)
		{
			$return[msg] = "Es wurden keine Crewmitglieder transferiert";
			return $return;
		}
		if ($this->cdock == $data2[id] || $this->cid == $data2[dock])
		{
			if ($way == "to")
			{
				$this->db->query("UPDATE stu_ships SET crew=crew+".$count." WHERE id=".$shipId2);
				$this->db->query("UPDATE stu_ships SET crew=crew-".$count." WHERE id=".$shipId);
			}
			else
			{
				$this->db->query("UPDATE stu_ships SET crew=crew+".$count." WHERE id=".$shipId);
				$this->db->query("UPDATE stu_ships SET crew=crew-".$count." WHERE id=".$shipId2);
			}
			if ($data[damaged] == 1) $umpf = "d/";
			$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/ships/".$umpf.$this->cships_rumps_id.".gif></td>
				<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/".$wayimg."></td>
				<td class=tdmainobg align=center>".$img."</td>
				<td class=tdmainobg>".$count." Crewmitglieder transferiert</td></tr></table>";
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		$energie = ceil($count/4);
		if ($this->cenergie < $energie)
		{
			$count = $this->cenergie*4;
			$energie = $this->cenergie;
		}
		if ($way == "to")
		{
			$this->db->query("UPDATE stu_ships SET crew=crew-".$count.",energie=energie-".$energie." WHERE id=".$shipId);
			$mode == "col" ? $this->db->query("UPDATE stu_colonies SET bev_free=bev_free+".$count." WHERE id=".$shipId2) : $this->db->query("UPDATE stu_ships SET crew=crew+".$count." WHERE id=".$shipId2);
		}
		else
		{
			$mode == "col" ? $this->db->query("UPDATE stu_colonies SET bev_free=bev_free-".$count." WHERE id=".$shipId2) : $this->db->query("UPDATE stu_ships SET crew=crew-".$count." WHERE id=".$shipId2);
			$this->db->query("UPDATE stu_ships SET crew=crew+".$count.",energie=energie-".$energie." WHERE id=".$shipId);
		}
		if ($this->cdamaged == 1) $umpf = "d/";
		$this->cenergie -= $energie;
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/ships/".$umpf.$this->cships_rumps_id.".gif></td>
					<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/".$wayimg."></td>
					<td class=tdmainobg align=center>".$img."</td>
					<td class=tdmainobg>".$count." Crewmitglieder transferiert - ".$energie." Energie verbraucht</td></tr></table>";
		$return[code] = 1;
		$return[crew] = $count;
		return $return;
	}
	
	function getlrsfield($x,$y,$shipId,$wese)
	{
		if ($this->cshow == 0) return 0;
		global $myMap;
		if ($this->clss == 0) return 0;
		$map = $myMap->getfieldbycoords($x,$y,$wese);
		if ($map[type] == 15 || $map[type] == 31) return -3;
		if ($x >=1 && $x <=20 && $y >=100 && $y <=140) return -3;
		if ($myMap->checksenjammer($x,$y,$this->cwese) != 0) return -3;
		if ($x > $this->ccoords_x) $x_r = $x-$this->ccoords_x;
		elseif ($x < $this->ccoords_x) $x_r = $this->ccoords_x-$x;
		else $x_r = 0;
		if ($y > $this->ccoords_y) $y_r = $y-$this->ccoords_y;
		elseif ($y < $this->ccoords_y)	$y_r = $this->ccoords_y-$y;
		else $y_r = 0;
		if ($x_r > 2 || $y_r > 2) return -1;
		if ($y_r > $x_r) $energie = $y_r;
		elseif ($y_r < $x_r) $energie = $x_r;
		else $energie = $x_r;
		if ($energie > $this->cenergie) return -2;
		$this->db->query("UPDATE stu_ships SET energie=energie-".$energie." WHERE id=".$shipId);
		return $this->db->query("SELECT a.cloak,a.huelle,a.energie,a.crew,a.user_id,a.alertlevel,a.schilde_aktiv,b.id,b.name,b.secretimage FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.wese=".$wese." AND a.coords_x=".$x." AND a.coords_y=".$y);
	}
	
	function traktor($shipId,$shipId2)
	{
		$data2 = $this->getdatabyid($shipId2);
		if ($this->cshow == 0 || $data2 == 0 || $this->cclass[probe] == 1) return 0;
		if ($this->cwese != $data2[wese]) return 0;
		if ($shipId == $shipId2)
		{
			$return[msg] = "Kann Traktorstrahl nicht auf das eigene Schiff richten";
			return $return;
		}
		if ($this->ccloak == 1)
		{
			$return[msg] = "Die Tarnung ist aktiviert";
			return $return;
		}
		if ($this->cschilde_aktiv == 1)
		{
			$return[msg] = "Die ".$this->cname." hat die Schilde aktiviert";
			return $return;
		}
		if ($data2[schilde_aktiv] == 1)
		{
			$return[msg] = "Die ".$data2[name]." hat die Schilde aktiviert";
			return $return;
		}
		if (($this->ccoords_x != $data2[coords_x]) || ($this->ccoords_y != $data2[coords_y]))
		{
			$return[msg] = "Die Schiffe befinden sich nicht im selben Sektor";
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($this->cdock > 0 || $data2[dock] > 0)
		{
			$return[msg] = "Schiff ist angedockt";
			return $return;
		}
		if ($data2[ships_rumps_id] == 154)
		{
			$return[msg] = "Das Minenfeld kann nicht erfasst werden";
			return $return;
		}
		if (($data2[ships_rumps_id] == 161) || ($data2[ships_rumps_id] == 210))
		{
			$return[msg] = "Ziel kann nicht erfasst werden";
			return $return;
		}
		if ($data2[fleets_id] > 0)
		{
			$return[msg] = "Ziel kann nicht erfasst werden";
			return $return;
		}
		global $myUser;
		if ($myUser->getfield("vac",$data2[user_id]) == 1)
		{
			$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
			return $this->cret($destroy);
		}
		if ($myUser->ulevel == 1)
		{
			$return[msg] = "Mit Level 1 kann man den Traktorstrahl nicht aktivieren";
			return $return;
		}
		if ($myUser->getfield("level",$data2[user_id]) == 1)
		{
			$return[msg] = "Du kannst keinen Traktorstrahl auf Schiffe von Level 1 Siedlern richten";
			return $return;
		}
		if ($this->ctraktor > 0 && $this->ctraktormode == 1)
		{
			$return[msg] = "Traktorstrahl der ".$this->cname." ist bereits aktiviert";
			return $return;
		}
		if ($this->ctraktor > 0 && $this->ctraktormode == 2)
		{
			$return[msg] = "Die ".$this->cname." wird von einem Traktorstrahl gehalten";
			return $return;
		}
		if ($data2[traktor] > 0)
		{
			$return[msg] = "Auf die ".$data2[name]." ist bereits ein Traktorstrahl gerichtet";
			return $return;
		}
		if ($this->cclass[crew_min] - 2 > $this->ccrew)
		{
			$return[msg] = "Es werden ".($this->cclass[crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($data2[c][trumfield] == 1 || $data2[c][slots] > 0 || $data2[c][probe] == 1)
		{
			$return[msg] = "Objekt kann nicht erfasst werden";
			return $return;
		}
		$this->db->query("UPDATE stu_ships SET traktor=".$data2[id].",traktormode=1,energie=energie-1 WHERE id=".$shipId);
		$this->db->query("UPDATE stu_ships SET traktor=".$shipId.",traktormode=2 WHERE id=".$data2[id]);
		if ($this->user != $data2[user_id])
		{
			global $myComm;
			$myComm->sendpm($data2[user_id],$this->user,"Die ".$this->cname." hat in Sektor ".$this->ccoords_x."/".$this->ccoords_y." den Traktorstrahl auf die ".$data2[name]." gerichtet",2);
		}
		$return[msg] = "Traktorstrahl auf die ".$data2[name]." gerichtet";
		return $return;
	}
	
	function traktoroff($shipId)
	{
		if ($this->cshow == 0) return 0;
		$this->db->query("UPDATE stu_ships SET traktor=0,traktormode=0 WHERE id=".$shipId." OR id=".$this->ctraktor);
		$return[msg] = "Traktorstrahl deaktiviert";
		return $return;
	}
	
	function shieldemitter($shipId,$count,$userId)
	{
		$data = $this->getdatabyid($shipId);
		if ($data == 0 || $data[user_id] != $userId) return 0;
		if ($data[energie] == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($data[cloak] == 1)
		{
			$return[msg] = "Die Tarnung ist aktiviert";
			return $return;
		}
		if ($data[schilde_aktiv] == 1)
		{
			$return[msg] = "Die Schilde sind aktiviert";
			return $return;
		}
		if ($data[maxshields] == $data[schilde])
		{
			$return[msg] = "Die Schilde sind bereits vollständig geladen";
			return $return;
		}
		if ($data[c][crew_min] - 2 > $data[crew] && $data[ships_rumps_id] != 88)
		{
			$return[msg] = "Es werden ".($data[c][crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($count == "max") $count = $data[energie];
		if ($data[schilde] + $count > $data[maxshields]) $count = $data[maxshields]-$data[schilde];
		if ($count > $data[energie]) $count = $data[energie];
		$this->db->query("UPDATE stu_ships SET schilde=schilde+".$count.",energie=energie-".$count." WHERE id='".$shipId."' AND user_id='".$userId."'",$this->dblink);
		$aff = $this->db->query("UPDATE stu_ships_action SET ships_id2=".time()." WHERE ships_id=".$shipId." AND mode='sload'",6);
		if ($aff == 0) $this->db->query("INSERT INTO stu_ships_action (mode,ships_id,ships_id2) VALUES ('sload','".$shipId."','".time()."')");
		if ($data[damaged] == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/shldp2.gif></td>
			<td class=tdmainobg align=center><img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif></td>
			<td class=tdmainobg>Die Schildemitter wurden um ".$count." Einheiten aufgeladen</td></tr></table>";
		return $return;
	}
	
	function torp($shipId,$shipId2,$userId,$type,$strbk=1,$react=0,$df=1)
	{
		if ($shipId == $shipId2) return 0;
		if ($react == 0) $this->msghandle("<strong>Angriff</strong>",3);
		if ($react == 1) $this->msghandle("<strong>Gegenwehr</strong>",3);
		if ($react == 2) $this->msghandle("<strong>Alarm Rot</strong>",3);
		if ($react == 3) $this->msghandle("<strong>Verteidigung</strong>",3);
		$ship1 = $this->getdatabyid($shipId);
		$ship2 = $this->getDatabyid($shipId2);
		if ($ship1 == 0 || $ship1[user_id] != $userId || $ship2 == 0) return 0;
		if ($ship1[coords_x] != $ship2[coords_x] || $ship1[coords_y] != $ship2[coords_y]) return 0;
		if ($ship1[wese] != $ship2[wese]) return 0;
		$decloak = $this->checkdecloak($shipId2,$userId);
		global $myUser;
		if ($ship1[kss] == 0)
		{
			$this->msghandle("Die Kurzstreckesensoren sind nicht aktiviert");
			return $this->cret($destroy);
		}
		if ($ship1[cloak] == 1)
		{
			$this->msghandle("Die Tarnung ist aktiviert");
			return $this->cret($destroy);
		}
		if ($ship1[ships_rumps_id] == 5 || ($ship1[ships_rumps_id] >= 65 && $ship1[ships_rumps_id] <= 68))
		{
			$this->msghandle("Diese Schiffsklasse kann keine Torpedos abfeuern");
			return $this->cret($destroy);
		}
		if ($ship1[c][crew_min] > $ship1[crew])
		{
			$this->msghandle("Es werden ".$class[crew_min]." Crewmitglieder benötigt");
			return $this->cret($destroy);
		}
		if ($ship1[dock] > 0)
		{
			$this->msghandle("Schiff ist angedockt");
			return $this->cret($destroy);
		}
		if ($ship1[energie] < 1)
		{
			$this->msghandle("Keine Energie vorhanden");
			return $this->cret($destroy);
		}
		if ($ship2[ships_rumps_id] == 110)
		{
			$this->msghandle("Dieses Trümmerfeld kann mit den Sensoren nicht erfasst werden");
			return $this->cret($destroy);
		}
		if ($ship2[ships_rumps_id] == 154)
		{
			$this->msghandle("Das Minenfeld kann nicht erfasst werden");
			return $this->cret($destroy);
		}
		if ($myUser->getfield("vac",$ship2[user_id]) == 1)
		{
			$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
			return $this->cret($destroy);
		}
		$wc = $this->checkss("waffdef",$shipId);
		if ($wc > time())
		{
			$react == 0 ? $this->msghandle("Die Waffen sind ausgefallen - Die Reparatur dauert bis ".date("d.m H:i",$wc)." Uhr") : $this->msghandle("Die Waffen der ".$ship1[name]." sind ausgefallen");
			return $this->cret($destroy);
		}
		elseif ($wc > 0 && $wc < time()) $this->repairssd("waffdef",$shipId);
		if ($myUser->getfield("level",$ship2[user_id]) < 2)
		{
			$this->msghandle("Es können keine Kolonisten unter Level 2 angegriffen werden");
			return $this->cret($destroy);
		}
		if ($myUser->getfield("level",$ship1[user_id]) == 1)
		{
			$this->msghandle("Mit Level 1 kannst Du keine Torpedos abschießen");
			return $this->cret($destroy);
		}
		global $myColony;
		$evade = $ship2[maxausweichen];
		$torp = $this->db->query("SELECT name,damage,evade,research_id,goods_id,size FROM stu_torpedo_types WHERE id=".$type,4);
		if ($torp == 0) return 0;
		if ($this->getcountbygoodid($torp[goods_id],$ship1[id]) == 0)
		{
			$this->msghandle("Keine Torpedos vorhanden");
			return $this->cret($destroy);
		}
		if ($torp[size] > $ship1[c][size])
		{
			$this->msghandle("Dieser Torpedotyp kann nicht abgefeuert werden");
			return $this->cret($destroy);
		}
		if ($torp[research_id] > 0 && $myColony->getuserresearch($torp[research_id],$userId) == 0)
		{
			$this->msghandle("Der ".$torp[name]." kann aufgrund fehlender Forschung nicht abgefeuert werden");
			return $this->cret($destroy);
		}
		if ($ship2[schilde_aktiv] == 1 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$ship2[schildmodlvl],1) == "Regenerativ") $torp[damage] = round($torp[damage] * 0.8);
		if ($ship2[schilde_aktiv] == 0 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$ship2[huellmodlvl],1) == "Ablativ") $torp[damage] = round($torp[damage] * 0.8);
		if ($ship2[schilde_aktiv] == 0 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$ship2[huellmodlvl],1) == "Reparatursystem") $torp[damage] = round($torp[damage] * 0.5);
		if ($ship2[ships_rumps_id] == 165) $ship2[maxausweichen] = 76;
		$schilde = $ship2[schilde];
		$huelle = $ship2[huelle];
		$sad = $ship2[schilde_aktiv];
		if ($type != 13 && $type != 14)
		{
			$evade = $ship2[maxausweichen]*$torp[evade];
			if ($evade > 95) $evade = 95;
			$torp[size] != 5 ? $this->msghandle("Die ".$ship1[name]." feuert einen ".$torp[name]." auf die ".$ship2[name],3) : $this->msghandle("Die ".$ship1[name]." feuert ".$torp[name]." auf die ".$ship2[name],3);
			if ($ship2[waffenmodlvl] == 99 && rand(1,100) <= 20 && $ship2[c][trumfield] == 0)
			{
				$this->msghandle("Die ".$ship2[name]." hat den Torpedo mit den Phasern abgefangen",3);
				$miss = 1;
			}
			else
			{
				if (rand(1,100) <= $evade && $ship2[crew] > 0 && $ship2[c][trumfield] == 0 && $ship2[energie] > 0 && $ship2[dock] == 0 && $ship2[c][slots] == 0)
				{
					$this->msghandle("Die ".$ship2[name]." führt ein Ausweichmanöver durch - Der Torpedo hat sein Ziel verfehlt",3);
					$miss = 1;
				}
				else
				{
					if ($type == 12 && rand(1,5) == 1)
					{
						global $myComm,$myHistory;
						$this->msghandle("Subraumschockwelle durch Detonation eines isolytischen Torpedos");
						$myHistory->addevent("Subrauminstabilität in Sektor ".$ship2[coords_x]."/".$ship2[coords_y].($ship2[wese] == 2 ? " (2)" : ""),$ship1[user_id],1);
						$this->db->query("INSERT INTO stu_map_special (coords_x,coords_y,type) VALUES ('".$ship2[coords_x]."','".$ship2[coords_y]."','1')");
						$sr = $this->db->query("SELECT a.id,a.huelle,a.name,a.user_id FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.coords_x=".$ship2[coords_x]." AND a.coords_y=".$ship2[coords_y]." AND a.reaktormodlvl>0 AND b.trumfield=0 AND a.id!=".$ship1[id]);
						while($sd=mysql_fetch_assoc($sr))
						{
							$dmg = rand(20,40);
							if ($sd[huelle] <= $dmg)
							{
								$myComm->sendpm($sd[user_id],2,"Die ".$sd[name]." wurde aufgrund einer Subraumschockwelle in Sektor ".$ship2[coords_x]."/".$ship2[coords_y]." zerstört",2);
								$myHistory->addEvent("Die ".addslashes($sd[name])." wurde in Sektor ".$ship2[coords_x]."/".$ship2[coords_y].($ship2[wese] == 2 ? " (2)" : "")." durch eine Subraumschockwelle zerstört",$ship2[user_id],1);
								$this->trumfield($sd[id]);
							}
							else
							{
								$myComm->sendpm($sd[user_id],2,"Die ".$sd[name]." wurde aufgrund einer Subraumschockwelle in Sektor ".$ship2[coords_x]."/".$ship2[coords_y]." beschädigt - Hülle bei ".($sd[huelle]-$dmg),2);
								$this->db->query("UPDATE stu_ships SET huelle=huelle-".$dmg.",schilde_aktiv=0 WHERE id=".$sd[id]);
							}
						}
					}
					if ($ship2[schilde_aktiv] == 1 && $ship2[schilde] > 0)
					{
						if ($type == 4)
						{
							if (rand(1,100) >= 40)
							{
								$schilde -= $torp[damage];
								if ($schilde <= 0)
								{
									$huelle -= abs($schilde);
									$schilde = 0;
									$sad = 0;
								}
								if ($huelle <= 0)
								{
									$schilde = 0;
									$sad = 0;
									$destroy = 1;
								}
							}
							else
							{
								$huelle -= $torp[damage];
								if ($huelle < 1) $destroy = 1;
								$this->msghandle("Der Torpedo durchdringt die Schilde der ".$ship2[name],3);
							}
						}
						else
						{
							$schilde -= $torp[damage];
							if ($schilde <= 0)
							{
								$huelle -= abs($schilde);
								$schilde = 0;
								$sad = 0;
							}
							if ($huelle <= 0)
							{
								$schilde = 0;
								$sad = 0;
								$destroy = 1;
							}
						}
					}
					else
					{
						$huelle -= $torp[damage];
						if ($huelle <= 0) $destroy = 1;
					}
				}
			}
		}
		else
		{
			$destroy = 0;
			for ($i=1;$i<=8;$i++)
			{
				if ($sad == 1 && $schilde > 0)
				{
					$schilde -= $torp[damage];
					if ($schilde <= 0)
					{
						$huelle -= abs($schilde);
						$schilde = 0;
						$sad = 0;
					}
					if ($huelle <= 0)
					{
						$schilde = 0;
						$sad = 0;
						$destroy = 1;
					}
				}
				else
				{
					$huelle -= $torp[damage];
					if ($huelle <= 0) $destroy = 1;
				}
				$this->msghandle("Schuss ".$i." der Drohne trifft - Schaden: ".$torp[damage],3);
				if ($destroy == 1) break;
				$intercept = round($ship2[maxtreffer]/7);
				if (rand(1,100) <= $intercept && $i!=8)
				{
					$this->msghandle("Die ".$ship2[name]." hat die Drohne abgefangen",3);
					break;
				}
				if ($i == 8) $this->msghandle("Energievorrat der Drohne verbraucht",3);
			}
		}
		$this->lowerstoragebygoodid(1,$torp[goods_id],$shipId);
		$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$shipId);
		if ($miss != 1)
		{
			if ($destroy == 1 && $ship2[c][trumfield] == 0)
			{
				global $myHistory;
				if ($ship2[c][probe] == 0) $myHistory->addEvent("Die ".addslashes($ship2[name])." wurde in Sektor ".$ship2[coords_x]."/".$ship2[coords_y].($ship2[wese] == 2 ? " (2)" : "")." von der ".addslashes($ship1[name])." zerstört",$ship2[user_id],1);
				$this->trumfield($shipId2);
			}
			elseif (($destroy == 1) && ($ship2[c][trumfield] == 1)) $this->deletetrumfield($shipId2);
			else
			{
				if ($destroy != 1) $this->db->query("UPDATE stu_ships SET huelle=".$huelle.",schilde=".$schilde.",schilde_aktiv=".$sad." WHERE id=".$shipId2);
			}
		}
		else $this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$ship2[id]);
		if ($schilde > 0 && $ship2[schilde_aktiv] && $miss != 1) $this->msghandle("Die Schilde der ".$ship2[name]." sind bei ".$schilde,3);
		elseif ($schilde <= 0 && $sad == 0 && $ship2[schilde_aktiv] == 1 && $ship2[c][trumfield] == 0 && $destroy != 1) $this->msghandle("Die Schilde der ".$ship2[name]." brechen zusammen - Hülle bei ".$huelle,3);
		elseif ($destroy == 1 && $ship2[c][trumfield] == 0) $this->msghandle("Hüllenbruch!<br>Die ".$ship2[name]." wurde zerstört!",3);
		elseif ($ship2[c][trumfield] == 1 && $destroy == 0) $this->msghandle("Status des Trümmerfelds: ".$huelle,3);
		elseif ($ship2[c][trumfield] == 1 && $destroy == 1) $this->msghandle("Das Trümmerfeld wurde beseitigt",3);
		elseif ($miss != 1) $this->msghandle("Die Hülle der ".$ship2[name]." ist bei ".$huelle,3);
		if ($miss == 0 && $destroy == 0 && $sad == 0 && $ship2[c][trumfield] == 0)
		{
			$hke = ceil((100/$ship2[maxhuell])*$huelle);
			if (rand(1,55) > $hke && $this->checkss("lsendef",$ship2[id]) == 0)
			{
				$this->msghandle("Die Langstreckensensoren der ".$ship2[name]." sind ausgefallen");
				$dur = time()+rand(1800,18000);
				$this->msghandle("Die Langstreckensensoren der ".$ship2[name]." sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
				$this->ssdamage("lsendef",$ship2[id],$dur);
			}
			if (rand(1,65) > $hke && $this->checkss("cloakdef",$ship2[id]) == 0 && $ship2[c][cloak] == 1)
			{
				$dur = time()+rand(3600,25200);
				$this->msghandle("Die Tarnung der ".$ship2[name]." ist ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
				$this->ssdamage("cloakdef",$ship2[id],$dur);
			}
			if (rand(1,30) > $hke && $this->checkss("impdef",$ship2[id]) == 0)
			{
				$this->msghandle("Der Antrieb der ".$ship2[name]." ist ausgefallen");
				$dur = time()+rand(1200,14400);
				$this->msghandle("Der Antrieb der ".$ship2[name]." ist ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
				$this->ssdamage("impdef",$ship2[id],$dur);
			}
			if (rand(1,35) > $hke && $this->checkss("waffdef",$ship2[id]) == 0)
			{
				$this->msghandle("Die Waffen der ".$ship2[name]." sind ausgefallen");
				$dur = time()+rand(1200,14400);
				$this->msghandle("<br>Die Waffen der ".$ship2[name]." sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
				$this->ssdamage("waffdef",$ship2[id],$dur);
			}
			if (rand(1,25) > $hke && $this->checkss("ksendef",$ship2[id]) == 0)
			{
				$this->msghandle("Die Kurzstreckensensoren der ".$ship2[name]." sind ausgefallen");
				$dur = time()+rand(1800,18000);
				$this->msghandle("<br>Die Kurzstreckensensoren der ".$ship2[name]." sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
				$this->ssdamage("ksendef",$ship2[id],$dur);
			}
			if (rand(1,35) > $hke && $this->checkss("shidef",$ship2[id]) == 0)
			{
				$this->msghandle("Die Schilde der ".$ship2[name]." sind ausgefallen");
				$dur = time()+rand(1800,21600);
				$this->msghandle("Die Schilde der ".$ship2[name]." sind ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
				$this->ssdamage("shidef",$ship2[id],$dur);
			}
			if (rand(1,15) > $hke && $this->checkss("readef",$ship2[id]) == 0 && $ship2[reaktormodlvl] > 0)
			{
				$dur = time()+rand(7200,36000);
				$this->msghandle("Der Warpkern der ".$ship2[name]." ist ausgefallen - Die Reparatur dauert bis: ".date("d.m H:i",$dur)." Uhr",2);
				$this->ssdamage("readef",$ship2[id],$dur);
			}
		}
		if ($ship2[c][trumfield] == 1) return $this->cret($destroy);
		if ($ship1[user_id] != $ship2[user_id] && $this->pmmsg != "")
		{
			global $myComm;
			$myComm->sendpm($ship2[user_id],$ship1[user_id],"<strong>Kampf in Sektor ".$ship1[coords_x]."/".$ship1[coords_y]."</strong><br>".$this->pmmsg,2);
		}
		if ($decloak != 0 && ($ship2[alertlevel] > 1) && ($ship2[crew] > 0) && ($ship2[energie]-$miss > 0))
		{
			$this->db->query("UPDATE stu_ships SET cloak=0 WHERE id=".$ship2[id]);
			$this->activatevalue($ship2[id],"schilde_aktiv",$ship2[user_id]);
			$this->msghandle("Die ".$ship2[name]." hat die Tarnung deaktiviert");
		}
		if ($ship2[fleets_id] > 0 && $ship2[user_id] != $userId && $strbk == 1)
		{
			$fleet = $this->db->query("SELECT id,user_id FROM stu_ships WHERE alertlevel>1 AND crew>1 AND energie>0 AND fleets_id=".$ship2[fleets_id]);
			for ($i=0;$i<mysql_num_rows($fleet);$i++)
			{
				$fldat = mysql_fetch_assoc($fleet);
				$flret = $this->strikeback($shipId,$fldat[id],$fldat[user_id],1,0,1);
				if ($flret[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
			}
		}
		else if ($ship2[energie] > 0 && $ship2[alertlevel] > 1  && $ship2[crew] > 0 && $ship2[c][trumfield] == 0 && $strbk == 1) $strbmsg = $this->strikeback($shipId,$shipId2,$userId);
		if ($destroy != 1 && $df == 1)
		{
			$result = $this->db->query("SELECT a.id,a.user_id FROM stu_ships as a LEFT OUTER JOIN stu_ships_action as b ON a.id=b.ships_id WHERE a.energie>0 AND a.crew>0 AND a.alertlevel>1 AND b.mode='defend' AND ships_id2=".$ship2[id]);
			for ($i=0;$i<mysql_num_rows($result);$i++)
			{
				$tmp = mysql_fetch_assoc($result);
				$lret = $this->strikeback($shipId,$tmp[id],$tmp[user_id],1,0,3);
				if ($lret[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
			}
		}
		$this->returncode = 1;
		return $this->cret($destroy);
	}
	
	function alertlevel($shipId,$level,$userId)
	{
		$data = $this->getdatabyid($shipId);
		if ($data == 0 || $data[user_id] != $userId) return 0;
		if (($level < 1) || ($level > 3)) return 0;
		if (($data[c][crew_min] - 2 > $data[crew]) && ($data[ships_rumps_id] != 88))
		{
			$return[msg] = "Zum ändern der Alarmstufe werden ".($data[c][crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		global $myUser;
		if ($myUser->getfield("level",$userId) < 2)
		{
			$return[msg] = "Die Alarmstufe kann erst ab Level 2 gewechselt werden";
			return $return;
		}
		$this->db->query("UPDATE stu_ships SET alertlevel=".$level." WHERE id=".$shipId);
		
		if ($data[damaged] == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/buttons/alert".$level.".gif></td>
					<td class=tdmainobg align=center><img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif></td>
					<td class=tdmainobg>Alarmstufe wurde geändert</td></tr></table>";
		return $return;
	}
	
	function collect($shipId,$count,$userId)
	{
		$data = $this->getdatabyid($shipId);
		if ($data == 0 || $data[user_id] != $userId) return 0;
		if ($data[cloak] == 1)
		{
			$return[msg] = "Die Tarnung ist aktiviert";
			return $return;
		}
		if ($data[c][erz] == 0)
		{
			$return[msg] = "Dieses Schiff hat keine Erz-Kollektoren";
			return $return;
		}
		if ($data[c][crew_min] - 2 > $data[crew])
		{
			$return[msg] = "Für die Erz-Kollektoren werden ".($data[c][crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($data[schilde_aktiv] == 1)
		{
			$return[msg] = "Die Schilde sind aktiviert";
			return $return;
		}
		if ($data[energie] < 1)
		{
			$return[msg] = "Keine Energie für die Erz-Kollektoren vorhanden";
			return $return;
		}
		global $myMap;
		if ($count == "max") $count = $data[energie];
		if ($data[energie] < $count) $count = $data[energie];
		$mapdata = $myMap->getfieldbycoords($data[coords_x],$data[coords_y],$data[wese]);
		if ($mapdata[type] != 4 && $mapdata[type] != 5 && $mapdata[type] != 17 && $mapdata[type] != 18 && $mapdata[type] != 19 && $mapdata[type] != 20 && $mapdata[type] != 32)
		{
			$return[msg] = "Erz kann nur in Asteroidenfeldern gesammelt werden";
			return $return;
		}
		if ($mapdata[type] == 4 || $mapdata[type] == 5) $goodId = 4;
		elseif ($mapdata[type] == 17 || $mapdata[type] == 18) $goodId = 11;
		elseif ($mapdata[type] == 19 || $mapdata[type] == 20) $goodId = 13;
		elseif ($mapdata[type] == 32) $goodId = 2;
		if ($mapdata[type] == 4) $multi = 4;
		elseif ($mapdata[type] == 17 || $mapdata[type] == 19) $multi = 2;
		elseif ($mapdata[type] == 18 || $mapdata[type] == 20) $multi = 3;
		elseif ($mapdata[type] == 32 && $data[c][erz] > 2) $multi = floor($data[c][erz] / 3);
		else $multi = 6;
		if ($multi > $data[c][erz]) $multi = $data[c][erz];
		if ($data[sensormodlvl] == 101) $multi += 1;
		$insgstor = $this->db->query("SELECT SUM(count) as count FROM stu_ships_storage WHERE ships_id=".$shipId,1);
		if ($insgstor >= $data[c][storage])
		{
			$return[msg] = "Kein Lagerraum vorhanden";
			return $return;
		}
		if ($count*$multi > $data[c][storage]-$insgstor)
		{
			$erz = $data[c][storage]-$insgstor;
			$count = ceil($erz/$multi);
		}
		else $erz = $count*$multi;
		$this->db->query("UPDATE stu_ships SET energie=energie-".$count." WHERE id=".$shipId);
		if ($goodId != 2)
		{
			$bonus[38] = 0;
			$bonus[232] = 0;
			$bonus[233] = 0;
			for ($j=1;$j<=floor(($erz/10));$j++)
			{
				$r = rand(1,400);
				if ($r == 1)
				{
					$bonuswahl = rand(1,3);
					if ($bonuswahl  == 1) $bonus[38] += 1;
					elseif ($bonuswahl  == 2) $bonus[232] += 1;
					elseif ($bonuswahl  == 3) $bonus[233] += 1;
				}
			}
			if (($erz + $bonus[38] + $bonus[232] + $bonus[233]) > ($data[c][storage]-$insgstor))
			{
				$erz = $erz - ($bonus[38] + $bonus[232] + $bonus[233]);
			}
			if ($bonus[38] > 0)
			{
				$bonusmsg = "<br>Reiche Vorkommen entdeckt: ".$bonus[38]." Osmium gesammelt";
				$this->upperstoragebygoodid($bonus[38],38,$shipId,$userId);
			}
			if ($bonus[232] > 0)
			{
				$bonusmsg .= "<br>Reiche Vorkommen entdeckt: ".$bonus[232]." Magnesit gesammelt";
				$this->upperstoragebygoodid($bonus[232],232,$shipId,$userId);
			}
			if ($bonus[233] > 0)
			{
				$bonusmsg .= "<br>Reiche Vorkommen entdeckt: ".$bonus[233]." Talgonit gesammelt";
				$this->upperstoragebygoodid($bonus[233],233,$shipId,$userId);
			}
		}
		$this->upperstoragebygoodid($erz,$goodId,$shipId,$userId);
		if ($data[damaged] == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/map/".$mapdata[type].".gif></td>
			<td class=tdmainobg width=20 align=Center><img src=".$this->gfx."/buttons/erz2.gif></td>
			<td class=tdmainobg align=center><img src=".$this->gfx."/ships/".$mpf.$data[ships_rumps_id].".gif></td>
			<td class=tdmainobg>".$erz." Erz gesammelt - ".$count." Energie verbraucht".$bonusmsg."</td></tr></table>";
		return $return;
	}
	
	function setfiremode($shipId,$mode)
	{
		if ($this->cshow == 0) return 0;
		if ($mode != 1 && $mode != 2) return 0;
		$this->db->query("UPDATE stu_ships SET strb_mode=".$mode." WHERE id=".$shipId);
		if ($this->cdamaged == 1) $mpf = "d/";
		if ($mode == 1) { $timg = "phaser";	$type = "Phaser";  }
		else { $timg = "t_torp71"; $type = "Torpedos"; }
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$this->gfx."/buttons/".$timg.".gif></td>
			<td class=tdmainobg><img src=".$this->gfx."/ships/".$mpf.$this->cships_rumps_id.".gif></td>
			<td class=tdmainobg>Feuermodus eingestellt auf: ".$type."</td></tr></table>";
		return $return;
	}
	
	function setdestructcode($shipId)
	{
		if ($this->cshow == 0) return 0;
		$code = substr(md5(time()),0,6);
		$this->db->query("INSERT INTO stu_ships_action (mode,ships_id,ships_id2) VALUES ('destruct','".$shipId."','".$code."')");
		return $code;
	}
	
	function selfdestruct($shipId,$code)
	{
		if ($this->cshow == 0) return 0;
		if ($this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE mode='destruct' AND ships_id=".$shipId." AND ships_id2='".$code."'",1) == 0)
		{
			$return[msg] = "Falscher Bestätigungscode!";
			return $return;
		}
		global $myUser,$myHistory;
		$this->trumfield($shipId);
		if ($myUser->ulevel > 1) $myHistory->addEvent("Die ".addslashes($this->cname)." hat sich im Sektor ".$this->ccoords_x."/".$this->ccoords_y.($this->cwese == 2 ? " (2)" : "")." selbst zerstört",$this->user,2);
		$this->db->query("DELETE FROM stu_ships_action WHERE mode='destruct' AND ships_id=".$shipId);
		$return[msg] = "Selbstzerstörung erfolgreich";
		return $return;
	}
	
	function loadwarpcore($shipId,$max=0)
	{
		if ($this->cshow == 0) return 0;
		if ($this->creaktormodlvl == 0)
		{
			$return[msg] = "Dieses Schiff besitzt keinen Warpkern";
			return $return;
		}
		if ($this->cclass[crew_min] - 2 > $this->ccrew && $this->cships_rumps_id != 88)
		{
			$return[msg] = "Zum laden des Warpkerns werden ".($this->cclass[crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($this->cwarpcore == 1000)
		{
			$return[msg] = "Der Warpkern ist bereits voll aufgeladen";
			return $return;
		}
		if ($this->creaktormodlvl == 105)
		{
			$deut = $this->getcountbygoodid(2,$shipId);
			if ($deut < 6)
			{
				$return[msg] = "Es wird 6 Deuterium benötigt";
				return $return;
			}
			$plas = $this->getcountbygoodid(15,$shipId);
			if ($plas < 6)
			{
				$return[msg] = "Es wird 6 Plasma benötigt";
				return $return;
			}
			if ($max == 1)
			{
				$cl = ceil((1000-$this->cwarpcore)/40);
				if (floor($deut/6) < $cl) $cl = floor($deut/6);
				if (floor($plas/6) < $cl) $cl = floor($plas/6);
			}
			else $cl = 1;
			$cl*40 > 1000-$this->cwarpcore ? $wk = 1000-$this->cwarpcore : $wk = $cl*40;
			$this->db->query("UPDATE stu_ships SET warpcore=warpcore+".$wk." WHERE id=".$shipId);
			$this->lowerstoragebygoodid(($cl*6),2,$shipId);
			$this->lowerstoragebygoodid(($cl*6),15,$shipId);
		}
		else
		{
			$dil = $this->getcountbygoodid(8,$shipId);
			if ($dil == 0)
			{
				$return[msg] = "Es wird 1 Dilithium benötigt";
				return $return;
			}
			$am = $this->getcountbygoodid(5,$shipId);
			if ($am < 2)
			{
				$return[msg] = "Es werden 2 Antimaterie benötigt";
				return $return;
			}
			$deut = $this->getcountbygoodid(2,$shipId);
			if ($deut < 2)
			{
				$return[msg] = "Es werden 2 Deuterium benötigt";
				return $return;
			}
			if ($max == 1)
			{
				$cl = ceil((1000-$this->cwarpcore)/40);
				if ($dil < $cl) $cl = $dil;
				if (floor($am/2) < $cl) $cl = floor($am/2);
				if (floor($deut/2) < $cl) $cl = floor($deut/2);
			}
			else $cl = 1;
			$cl*40 > 1000-$this->cwarpcore ? $wk = 1000-$this->cwarpcore : $wk = $cl*40;
			$this->db->query("UPDATE stu_ships SET warpcore=warpcore+".$wk." WHERE id=".$shipId);
			$this->lowerstoragebygoodid(($cl*2),2,$shipId);
			$this->lowerstoragebygoodid(($cl*2),5,$shipId);
			$this->lowerstoragebygoodid($cl,8,$shipId);
		}
		$return[msg] = "Warpkern um ".$wk." Einheiten aufgeladen - Status: ".($this->cwarpcore+$wk);
		return $return;
	}
	
	function dock($shipId,$shipId2,$userId)
	{
		$data = $this->getdatabyid($shipId);
		if ($data == 0 || $data[user_id] != $this->user || $data[c][probe] == 1) return 0;
		if ($data[dock] > 0) return 0;
		if ($data[energie] == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($data[cloak] == 1)
		{
			$return[msg] = "Die Tarnung ist aktiviert";
			return $return;
		}
		if ($data[schilde_aktiv] == 1)
		{
			$return[msg] = "Die Schilde sind aktiviert";
			return $return;
		}
		if ($data[traktormode] == 1)
		{
			$return[msg] = "Das Schiff hat den Traktorstrahl aktiviert";
			return $return;
		}
		if ($data[traktormode] == 2)
		{
			$return[msg] = "Das Schiff wird von einem Traktorstrahl gehalten";
			return $return;
		}
		if ($data[fleets_id] > 0)
		{
			$return[msg] = "Das Schiff kann nicht andocken, da es sich in einer Flotte befindet";
			return $return;
		}
		$data2 = $this->getdatabyid($shipId2);
		if ($data2[schilde_aktiv] == 1)
		{
			$return[msg] = "Andocken nicht möglich - Die ".$data2[name]." hat die Schilde aktiviert";
			return $return;
		}
		if ($data[coords_x] != $data[coords_x] || $data[coords_y] != $data[coords_y]) return 0;
		if ($data[wese] != $data2[wese]) return 0;
		if ($data[c][crew_min] - 2 > $data[crew])
		{
			$return[msg] = "Zum andocken werden mindestens ".($data[c][crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($this->cdock != 0)
		{
			$return[msg] = "Das Schiff ist bereits angedockt";
			return $return;
		}
		if ($this->db->query("SELECT COUNT(id) FROM stu_ships WHERE dock=".$shipId2,1) >= $data2[c][slots])
		{
			$return[msg] = "Alle Dockplätze der ".stripslashes($data2[name])." sind belegt";
			return $return;
		}
		global $myComm,$myUser;
		$cstatus = $myComm->checkcontact($userId,$data2[user_id]);
		if ($userId != $data2[user_id] && $cstatus != 1)
		{
			$denied = 1;
			$dock = $this->db->query("SELECT mode FROM stu_dock_permissions WHERE type='all' AND ships_id=".$shipId2,1);
			if ($dock == 0 && $denied == 1) $denied = 1;
			if ($dock == 1) $denied = 0;
			if ($dock == 2) $denied = 1;
			$dock = $this->db->query("SELECT mode FROM stu_dock_permissions WHERE type='ally' AND id2=".$myUser->getfield("allys_id",$userId)." AND ships_id=".$shipId2,1);
			if ($dock == 0 && $denied == 1) $denied = 1;
			if ($dock == 1) $denied = 0;
			if ($dock == 2) $denied = 1;
			$dock = $this->db->query("SELECT mode FROM stu_dock_permissions WHERE type='user' AND ships_id=".$shipId2." AND id2=".$userId,1);
			if ($dock == 0 && $denied == 1) $denied = 1;
			if ($dock == 1) $denied = 0;
			if ($dock == 2) $denied = 1;
			$dock = $this->db->query("SELECT mode FROM stu_dock_permissions WHERE type='ship' AND ships_id=".$shipId2." AND id2=".$shipId,1);
			if ($dock == 0 && $denied == 1) $denied = 1;
			if ($dock == 1) $denied = 0;
			if ($dock == 2) $denied = 1;
			if ($denied == 1)
			{
				$return[msg] = "Andockerlaubnis verweigert";
				return $return;
			}
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-1,dock=".$data2[id]." WHERE id=".$shipId);
		if ($data[user_id] != $data2[user_id]) $myComm->sendpm($data2[user_id],$data[user_id],"Die ".$data[name]." hat an der ".$data2[name]." angedockt",2);
		$return[msg] = "Die ".$data[name]." hat an der ".$data2[name]." angedockt";
		return $return;
	}
	
	function undock($shipId,$shipId2,$userId)
	{
		if ($this->cshow == 0) return 0;
		if ($this->cdock == 0) return 0;
		$data2 = $this->db->query("SELECT id,name FROM stu_ships WHERE id=".$shipId2,4);
		if ($data2 == 0) return 0;
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($this->cclass[crew_min] - 2 > $this->ccrew)
		{
			$return[msg] = "Zum Abdocken werden ".($this->cclass[crew_min] -2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($this->cships_rumps_id == 7)
		{
			if ($this->db->query("SELECT id FROM stu_ships_buildprogress WHERE ships_id=".$data2[id],1) > 0)
			{
				$return[msg] = "Hier ist zur Zeit eine Station in Bau. Deshalb kann das Workbee nicht abdocken";
				return $return;
			}
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-1,dock=0 WHERE id=".$shipId);
		$return[msg] = "Die ".$this->cname." hat von der ".$data2[name]." abgedockt";
		return $return;
	}
	
	function getstationbysektor($x,$y,$wese) { return $this->db->query("SELECT a.id,a.ships_rumps_id,a.name,a.user_id,a.huelle,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x='".$x."' AND a.coords_y='".$y."' AND a.wese=".$wese." AND b.slots>0",4); }
	
	function getdockinfo($shipId) { return $this->db->query("SELECT COUNT(id) FROM stu_ships_action WHERE mode='dock' AND ships_id=".$shipId,1); }
	
	function getdockedships($shipId) { return $this->db->query("SELECT a.id,a.ships_rumps_id,a.name,a.user_id,ROUND((100/(c.huellmod*d.huell))*a.huelle) as huelldam,b.user,c.secretimage FROM stu_ships as a LEFT JOIN stu_user as b ON a.user_id=b.id LEFT JOIN stu_ships_rumps as c ON a.ships_rumps_id=c.id LEFT JOIN stu_ships_modules as d ON a.huellmodlvl=d.id WHERE a.dock=".$shipId); }
	
	function attackcolfield($shipId,$colId,$mode,$fieldId,$freq=0)
	{
		if ($this->cshow == 0) return 0;
		if ($mode != "field" && $mode != "orbit") return 0;
		global $myColony,$myUser,$myComm;
		$coldata = $myColony->getcolonybyid($colId);
		if ($this->ccoords_x != $coldata[coords_x] || $this->ccoords_y != $coldata[coords_y]) return 0;
		if ($this->cwese != $coldata[wese]) return 0;
		$colclass = $myColony->getclassbyid($coldata[colonies_classes_id]);
		if ($myUser->ulevel < 2)
		{
			$return[msg] = "Du kannst keine Kolonisten angreifen solange du nicht Level 2 hast";
			return $return;
		}
		if ($this->ccloak == 1)
		{
			$return[msg] = "Das Schiff ist getarnt";
			return $return;
		}
		if ($myUser->getfield("vac",$coldata[user_id]) == 1)
		{
			$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
			return $this->cret($destroy);
		}
		if ($myUser->getfield("level",$coldata[user_id]) < 2)
		{
			$return[msg] = "Es können keine Kolonisten unter Level 2 angegriffen werden";
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Auf der ".$this->cname." ist keine Energie vorhanden";
			return $return;
		}
		if ($this->cclass[crew_min] > $this->ccrew)
		{
			$return[msg] = "Es werden ".$this->cclass[crew_min]." Crewmitglieder benötigt";
			return $return;
		}
		if ($mode == "field") {	$fielddata = $myColony->getfielddatabyid($fieldId,$colId); $fadd = "fields"; }
		elseif ($mode == "orbit") { $fielddata = $myColony->getorbitfielddatabyid($fieldId,$colId); $fadd = "orbit"; }
		if ($fielddata[buildings_id] == 218)
		{
			$return[msg] = "Dieses Gebäude kann nicht angegriffen werden";
			return $return;
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$this->cid);
		if ($colclass[atmos] >= $this->cmaxphaser && $this->cstrb_mode == 1)
		{
			$return[msg] = "Der Phaserschuß wurde von der Atmosphäre kompensiert";
			$defend = $myColony->defendColony($colId,$shipId);
			if ($defend[msg] != "") {
				$return[msg] .= "<br><strong>Kolonieverteidigung</strong><br>".$defend[msg];
				$myComm->sendpm($this->user,$coldata[user_id],"<strong>Kolonieverteidigung der ".$coldata[name]."</strong><br>".$defend[msg],2);
			}
			$myComm->sendpm($coldata[user_id],$this->user,"<strong>Kolonieangriff auf Kolonie ".$coldata[name]."</strong><br>".$return[msg],2);
			return $return;
		}
		if ($fielddata == 0)
		{
			$return[msg] = "Feld nicht vorhanden";
			return $return;
		}
		if ($this->cstrb_mode == 2)
		{
			$type = $this->gettorptype($shipId);
			if ($type == 0)
			{
				$return[msg] = "Es befinden sich keine Torpedos an Bord des Schiffes";
				return $return;
			}
			$torp = $this->db->query("SELECT name,damage,goods_id FROM stu_torpedo_types WHERE id=".$type,4);
			$this->lowerstoragebygoodid(1,$torp[goods_id],$shipId);
			$schaden = $torp[damage];
		}
		else
		{
			$schaden = $this->cmaxphaser-$colclass[atmos];
			$phaser = 1;
		}
		$cl = $myColony->getcloaked($colId);
		$cl[$fieldId] == 1 ? $msg = "Die ".$this->cname." schießt auf der Kolonie ".$coldata[name]." auf das Gebäude auf Feld ".($fieldId+1) : $msg = "Die ".$this->cname." schießt auf der Kolonie ".$coldata[name]." auf das Gebäude ".$building[name]." auf Feld ".($fieldId+1);
		if ($coldata[schilde_aktiv] == 1 && $coldata[schild_freq1].$coldata[schild_freq2] != $freq)
		{
			if (($phaser == 1) && ($this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$this->cwaffenmodlvl,1) == "Partikelwaffe"))
			{
				$schaden = round($this->cmaxphaser*0.6)-$colclass[atmos];
			}
			if ($coldata[schilde] > $schaden)
			{
				$this->db->query("UPDATE stu_colonies SET schilde=".($coldata[schilde]-$schaden)." WHERE id=".$coldata[id]);
				$return[msg] .= $msg."<br>Schilde halten - Status: ".($coldata[schilde]-$schaden);
				$defend = $myColony->defendColony($colId,$shipId);
				if ($defend[msg] != "")
				{
					$return[msg] .= "<br><strong>Kolonieverteidigung</strong><br>".$defend[msg];
					$myComm->sendpm($this->user,$coldata[user_id],"<strong>Kolonieverteidigung der ".$coldata[name]."</strong><br>".$defend[msg],2);
				}
				$myComm->sendpm($coldata[user_id],$this->user,"<strong>Kolonieangriff auf Kolonie ".$coldata[name]."</strong><br>".$return[msg],2);
				return $return;
			}
			else
			{
				$schaden -= $coldata[schilde];
				$this->db->query("UPDATE stu_colonies SET schilde=0,schilde_aktiv=0 WHERE id=".$coldata[id]);
				$msg .= "<br>Planetare Schilde brechen zusammen!";
			}
		}
		if ($fielddata[buildings_id] == 0)
		{
			$return[msg] .= $msg."<br>Der Schuß richtete keinen Schaden an";
			$defend = $myColony->defendColony($colId,$shipId);
			if ($defend[msg] != "")
			{
				$return[msg] .= "<br><strong>Kolonieverteidigung</strong><br>".$defend[msg];
				$myComm->sendpm($this->user,$coldata[user_id],"<strong>Kolonieverteidigung der ".$coldata[name]."</strong><br>".$defend[msg],2);
			}
			$myComm->sendpm($coldata[user_id],$this->user,"<strong>Kolonieangriff auf Kolonie ".$coldata[name]."</strong><br>".$return[msg],2);
			return $return;
		}
		$building = $myColony->getbuildbyid($fielddata[buildings_id]);
		if (($coldata[schilde_aktiv] == 1) && ($coldata[schild_freq1].$coldata[schild_freq2] == $freq)) $myColony->modulateshields($colId);
		$phaser == 1 ? $add = "mit den Phasern" : $add = "mit einem ".$torp[name];
		if ($fielddata[integrity] - $schaden <= 0)
		{
			$this->db->query("UPDATE stu_colonies_".$fadd." SET integrity=0,buildings_id=0,aktiv=0,name='',buildtime='' WHERE field_id=".$fieldId." AND colonies_id=".$colId);
			$this->db->query("UPDATE stu_colonies SET bev_used=bev_used-".$building[bev_use].",max_bev=max_bev-".$building[bev_pro].",max_energie=max_energie-".$building[eps].",max_storage=max_storage-".$building[lager].",max_schilde=max_schilde-".$building[schilde]." WHERE id=".$colId);
			if ($building[id] == 1 || $building[id] == 23 || $building[id] == 63 || $building[id] == 64 || $building[id] == 65 || $building[id] == 66 || $building[id] == 89 || $building[id] == 101) $this->db->query("UPDATE stu_colonies SET mkolz=1 WHERe id=".$colId);
			if ($building[id] == 21 || $building[id] == 168 || ($building[id] == 192 && $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildings_id=192 AND aktiv=1",1) < 1))
			{
				$obu = $this->db->query("SELECT SUM(a.bev_use) FROM stu_buildings as a LEFT JOIN stu_colonies_orbit as b ON a.id=b.buildings_id WHERE b.colonies_id=".$colId." AND aktiv=1",1);
				$this->db->query("UPDATE stu_colonies SET bev_used=bev_used-".$obu.",bev_free=bev_free+".$obu." WHERE id=".$colId);
				$this->db->query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE colonies_id=".$colId);
			}
			if ($building[id] == 51 || $building[id] == 81) $this->db->query("UPDATE stu_colonies SET schilde=0,schild_freq1=0,schild_freq2=0,schilde_aktiv=0 WHERE id=".$colId);
			if ($building[id] == 82 && $coldata[schilde] > $coldata[max_schilde]-$building[schilde]) $this->db->query("UPDATE stu_colonies SET schilde=schilde-".($coldata[max_schilde]-$building[schilde])." WHERE id=".$colId);
			if ($building[id] == 38 || ($building[id] == 195 && $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildings_id=195 AND aktiv=1",1) < 1))
			{
				$gbu = $this->db->query("SELECT SUM(a.bev_use) FROM stu_buildings as a LEFT OUTER JOIN stu_colonies_underground as b ON a.id=b.buildings_id WHERE b.colonies_id=".$colId." AND aktiv=1",1);
				$this->db->query("UPDATE stu_colonies SET bev_used=bev_used-".$gbu.",bev_free=bev_free+".$gbu." WHERE id=".$colId);
				$this->db->query("UPDATE stu_colonies_underground SET aktiv=0 WHERE colonies_id=".$colId);
				$this->db->query("UPDATE stu_colonies_underground SET buildings_id=0,aktiv=0,integrity=0 WHERE field_id=13 ANd colonies_id=".$colId);
			}
			$msg .= "<br>Das Gebäude wurde zerstört - Es gab ".$building[bev_use]." Tote";
			if ((($building[id] > 25) && ($building[id] < 31)) || ($building[id] == 135)) $this->db->query("DELETE FROM stu_ships_buildprogress WHERE colonies_id=".$colId);
		}
		else
		{
			$this->db->query("UPDATE stu_colonies_".$fadd." SET integrity=".($fielddata[integrity]-$schaden)." WHERE field_id=".$fieldId." AND colonies_id=".$colId);
			$msg .= "<br>Schaden: ".$schaden." - Integrität: ".($fielddata[integrity]-$schaden);
		}
		$this->cenergie -= 1;
		$return[msg] = $msg;
		$defend = $myColony->defendColony($colId,$shipId);
		if ($defend[msg] != "")
		{
			$return[msg] .= "<br><strong>Kolonieverteidigung</strong><br>".$defend[msg];
			$myComm->sendpm($this->user,$coldata[user_id],"<strong>Kolonieverteidigung der ".$coldata[name]."</strong><br>".$defend[msg],2);
		}
		$myComm->sendpm($coldata[user_id],$this->user,"<strong>Kolonieangriff auf Kolonie ".$coldata[name]."</strong><br>".$return[msg],2);
		return $return;
	}
	
	function getnpcships() { return $this->db->query("SELECT id,name from stu_ships_rumps ORDER by sorta,sortb",2); }
	
	function addnpcship($classId,$coords_x,$coords_y,$huellmod,$sensormod,$schildmod,$epsmod,$antriebmod,$waffenmod,$reaktormod,$computermod,$wese)
	{
		global $mapfields,$mapfields2,$mapfields3;
		if ($wese != 1 && $wese != 2 && $wese != 3) return 0;
		if ($wese == 1)
		{
			$maxx = $mapfields[max_x];
			$maxy = $mapfields[max_y];
		}
		elseif ($wese == 2)
		{
			$maxx = $mapfields2[max_x];
			$maxy = $mapfields2[max_y];
		}
		elseif ($wese == 3)
		{
			$maxx = $mapfields3[max_x];
			$maxy = $mapfields3[max_y];
		}
		if (!$coords_x || !$coords_y || $coords_x > $maxx || $coords_y > $maxy)
		{
			$return[msg] = "Sektor nicht vorhanden";
			return $return;
		}
		$class = $this->getclassbyid($classId);
		if ($class == 0) return 0;
		/*if ($classId != 154)
		{
			$module = $this->getmodulebyid($huellmod);
			if ($this->db->query("SELECT id FROM stu_modules_user WHERE user_id=".$this->user." AND modules_id=".$huellmod,1) == 0 || $class[huellmod_max] < $module[lvl])
			{
				$return[msg] = "Du darfst dieses Modul nicht einbauen";
				return $return;
			}
			$module = $this->getmodulebyid($schildmod);
			if ($this->db->query("SELECT id FROM stu_modules_user WHERE user_id=".$this->user." AND modules_id=".$schildmod,1) == 0 || $class[schildmod_max] < $module[lvl])
			{
				$return[msg] = "Du darfst dieses Modul nicht einbauen";
				return $return;
			}
			$module = $this->getmodulebyid($sensormod);
			if ($this->db->query("SELECT id FROM stu_modules_user WHERE user_id=".$this->user." AND modules_id=".$sensormod,1) == 0 || $class[sensormod_max] < $module[lvl])
			{
				$return[msg] = "Du darfst dieses Modul nicht einbauen";
				return $return;
			}
			$module = $this->getmodulebyid($computermod);
			if ($this->db->query("SELECT id FROM stu_modules_user WHERE user_id=".$this->user." AND modules_id=".$computermod,1) == 0 || $class[computermod_max] < $module[lvl])
			{
				$return[msg] = "Du darfst dieses Modul nicht einbauen";
				return $return;
			}
			$module = $this->getmodulebyid($epsmod);
			if ($this->db->query("SELECT id FROM stu_modules_user WHERE user_id=".$this->user." AND modules_id=".$epsmod,1) == 0 || $class[epsmod_max] < $module[lvl])
			{
				$return[msg] = "Du darfst dieses Modul nicht einbauen";
				return $return;
			}
			if ($antriebmod != 0)
			{
				$module = $this->getmodulebyid($antriebmod);
				if ($this->db->query("SELECT id FROM stu_modules_user WHERE user_id=".$this->user." AND modules_id=".$antriebmod,1) == 0 || $class[antriebsmod_max] < $module[lvl])
				{
					$return[msg] = "Du darfst dieses Modul nicht einbauen";
					return $return;
				}
			}
			if ($waffenmod != 0)
			{
				$module = $this->getmodulebyid($waffenmod);
				if ($this->db->query("SELECT id FROM stu_modules_user WHERE user_id=".$this->user." AND modules_id=".$waffenmod,1) == 0 || $class[waffenmod_max] < $module[lvl])
				{
					$return[msg] = "Du darfst dieses Modul nicht einbauen";
					return $return;
				}
			}
			if ($reaktormod != 0)
			{
				$module = $this->getmodulebyid($reaktormod);
				if ($this->db->query("SELECT id FROM stu_modules_user WHERE user_id=".$this->user." AND modules_id=".$reaktormod,1) == 0 || $class[reaktormod_max] < $module[lvl])
				{
					$return[msg] = "Du darfst dieses Modul nicht einbauen";
					return $return;
				}
			}*/
			$data = $this->getclassbyid($classId);
			$huell = $this->getmodulebyid($huellmod);
			$eps = $this->getmodulebyid($epsmod);
			$schild = $this->getmodulebyid($schildmod);
			$mh = $data[huellmod]*$huell[huell];
		$this->db->query("INSERT INTO stu_ships (name,ships_rumps_id,user_id,coords_x,coords_y,huelle,energie,schilde,batt,crew,huellmodlvl,sensormodlvl,schildmodlvl,reaktormodlvl,epsmodlvl,antriebmodlvl,waffenmodlvl,computermodlvl,wese,tachyon) VALUES ('Schiff','".$classId."','".$this->user."','".$coords_x."','".$coords_y."','".$mh."','".($data[epsmod]*$eps[eps])."','".($data[schildmod]*$schild[shields])."','".$data[max_batt]."','".$data[crew]."','".$huellmod."','".$sensormod."','".$schildmod."','".$reaktormod."','".$epsmod."','".$antriebmod."','".$waffenmod."','".$computermod."','".$wese."','1')");
		$return[msg] = "Schiff erstellt";
		return $return;
	}

	function addnpcblueprintship($classId,$coords_x,$coords_y,$huellmod,$sensormod,$schildmod,$epsmod,$antriebmod,$waffenmod,$reaktormod,$computermod,$wese,$torp,$torpc)
	{
		global $mapfields,$mapfields2,$mapfields3;
		if ($wese != 1 && $wese != 2 && $wese != 3) return 0;
		if ($wese == 1)
		{
			$maxx = $mapfields[max_x];
			$maxy = $mapfields[max_y];
		}
		elseif ($wese == 2)
		{
			$maxx = $mapfields2[max_x];
			$maxy = $mapfields2[max_y];
		}
		elseif ($wese == 3)
		{
			$maxx = $mapfields3[max_x];
			$maxy = $mapfields3[max_y];
		}
		if (!$coords_x || !$coords_y || $coords_x > $maxx || $coords_y > $maxy)
		{
			$return[msg] = "Sektor nicht vorhanden";
			return $return;
		}
		$class = $this->getclassbyid($classId);
		if ($class == 0) return 0;
		if ($classId != 154)
		{
			$data = $this->getclassbyid($classId);
			$huell = $this->getmodulebyid($huellmod);
			$eps = $this->getmodulebyid($epsmod);
			$schild = $this->getmodulebyid($schildmod);
			$mh = $data[huellmod]*$huell[huell];
		}
		else $mh = 50;
		$nextid = $this->db->query("SELECT max(id) AS next_id FROM stu_ships",4);
		$nextid = $nextid[next_id] + 1;
		$this->db->query("INSERT INTO stu_ships (id,name,ships_rumps_id,user_id,coords_x,coords_y,huelle,energie,schilde,batt,crew,huellmodlvl,sensormodlvl,schildmodlvl,reaktormodlvl,epsmodlvl,antriebmodlvl,waffenmodlvl,computermodlvl,wese,tachyon) VALUES ('".$nextid."','Schiff','".$classId."','".$this->user."','".$coords_x."','".$coords_y."','".$mh."','".($data[epsmod]*$eps[eps])."','".($data[schildmod]*$schild[shields])."','".$data[max_batt]."','".$data[crew]."','".$huellmod."','".$sensormod."','".$schildmod."','".$reaktormod."','".$epsmod."','".$antriebmod."','".$waffenmod."','".$computermod."','".$wese."','1')");
		if ($torp > 0 && $torpc > 0) $this->upperstoragebygoodid($torpc,$torp,$nextid,$this->user);
		$return[msg] = "Schiff ".$nextid." erstellt";
		return $return;
	}

	function npclaunchfighter($basis,$type=1)
	{
		global $myUser;
		$basedata = $this->getDataById($basis);
		if ($type == 1) 
		{ 
			$goodId      = 308;
			$classId     = 194;		
			$huellmod    = 3;
			$computermod = 8;
			$schildmod   = 12;
			$waffenmod   = 104;
			$antriebmod  = 102;
			$epsmod      = 32;
			$sensormod   = 35;
			$reaktormod  = 39;
		}
		else return 0;
		$class = $this->getclassbyid($classId);
		if ($class == 0) return 0;
		if ($this->getcountbygoodid($goodId,$basis) == 0)
		{
			$return[msg] = "Keine startbereiten Jäger mehr vorhanden";
			return $return;
		}
		$data = $this->getclassbyid($classId);
		$huell = $this->getmodulebyid($huellmod);
		$eps = $this->getmodulebyid($epsmod);
		$schild = $this->getmodulebyid($schildmod);
		$mh = $data[huellmod]*$huell[huell];

		$name = addslashes($basedata[name]." Jäger");
		$nextid = $this->db->query("SELECT max(id) AS next_id FROM stu_ships",4);
		$nextid = $nextid[next_id] + 1;
		$this->db->query("INSERT INTO stu_ships (id,name,ships_rumps_id,user_id,coords_x,coords_y,huelle,energie,schilde,batt,crew,huellmodlvl,sensormodlvl,schildmodlvl,reaktormodlvl,epsmodlvl,antriebmodlvl,waffenmodlvl,computermodlvl,wese,fleets_id,alertlevel,kss) VALUES ('".$nextid."','".$name."','".$classId."','".$basedata[user_id]."','".$basedata[coords_x]."','".$basedata[coords_y]."','".$mh."','".($data[epsmod]*$eps[eps])."','".($data[schildmod]*$schild[shields])."','".$data[max_batt]."','".$data[crew]."','".$huellmod."','".$sensormod."','".$schildmod."','".$reaktormod."','".$epsmod."','".$antriebmod."','".$waffenmod."','".$computermod."','".$basedata[wese]."','".$basedata[fleets_id]."','2','1')");
		$this->lowerstoragebygoodid(1,$goodId,$basedata[id]);
		$return[msg] = "Jäger wurde gestartet";
		return $return;
	}

	function npcreturnfighter($basis,$type=1)
	{
		global $myUser;
		$basedata = $this->getDataById($basis);
		if ($type == 1) 
		{ 
			$classId     = 194;	
			$goodId      = 308;	
		}
		else return 0;
		$anzahl = $this->db->query("SELECT count(id) as anzahl FROM stu_ships WHERE ships_rumps_id = ".$classId." AND coords_x = ".$basedata[coords_x]." AND coords_y = ".$basedata[coords_y]." AND wese = ".$basedata[wese]." AND fleets_id = ".$basedata[fleets_id]."",4);
		$anzahl = $anzahl[anzahl];
		$this->upperstoragebygoodid($anzahl,$goodId,$basedata[id],$basedata[user_id]);
		$this->db->query("DELETE FROM stu_ships WHERE ships_rumps_id = ".$classId." AND coords_x = ".$basedata[coords_x]." AND coords_y = ".$basedata[coords_y]." AND wese = ".$basedata[wese]." AND fleets_id = ".$basedata[fleets_id]);
		$return[msg] = $anzahl." Jäger sind gelandet";
		return $return;
	}

	function wormhole($shipId,$userId,$fleet=0)
	{
		$data = $this->getdatabyid($shipId);
		if ($data[user_id] != $userId) return 0;
		if ($data[c][crew_min] - 1 > $data[crew])
		{
			$return[msg] = "Es werden ".($data[c][crew_min] - 1)." Crewmitglieder benötigt um in das Wurmloch zu fliegen";
			if ($fleet > 0)
			{
				$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE id=".$shipId);
				$return[msg] .= "<br>Die ".$data[name]." hat sich von der Flotte gelöst";
			}
			return $return;
		}
		if ($data[traktormode] == 1)
		{
			$return[msg] = "Das Schiff hat den Traktorstrahl aktiviert";
			return $return;
		}
		if ($data[traktormode] == 2)
		{
			$return[msg] = "Das Schiff wird von einem Traktorstrahl gehalten";
			return $return;
		}
		if ($data[energie] < 13)
		{
			$return[msg] = "Es wird mindestens 13 Energie benötigt um in das Wurmloch zu fliegen";
			return $return;
		}
		if ($data[dock] > 0)
		{
			$return[msg] = "Das Schiff ist angedockt";
			return $return;
		}
		if ($data[fleets_id] > 0 && $fleet == 0)
		{
			$return[msg] = "Das Schiff befindet sich in einer Flotte.";
			return $return;
		}
		if ($data[c][slots] > 0)
		{
			$return[msg] = "Eine Station kann nicht bewegt werden";
			return $return;
		}
		$this->db->query("DELETE FROM stu_ships_action WHERE mode='defend' AND (ships_id=".$shipId." OR ships_id2=".$shipId.")");
		$wormdata = $this->db->query("SELECT * FROM stu_wormholes WHERE start_x=".$data[coords_x]." AND start_y=".$data[coords_y]." AND start_wese=".$data[wese]." ORDER BY RAND() LIMIT 0,1",4);
		if ($wormdata == 0) return 0;
		if ($wormdata[stable] == 0)
		{
			$msg = "Dieses Wurmloch ist instabil<br>";
			if (rand(1,3) == 1)
			{
				global $mapfields;
				$rx = rand(1,$mapfields[max_x]);
				$ry = rand(1,$mapfields[max_y]);
				$this->db->query("UPDATE stu_ships SET coords_x=".$rx.",coords_y=".$ry." WHERE id=".$shipId);
				$this->trumfield($shipId);
				$return[msg] = $msg."Die ".$data[name]." wurde im instabilen Wurmloch zerstört";
				return $return;
			}
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-13,schilde_aktiv=0,coords_x=".$wormdata[end_x].",coords_y=".$wormdata[end_y].",wese=".$wormdata[end_wese]." WHERE id=".$shipId);
		$return[msg] = $msg."Die ".$data[name]." hat das Wurmloch durchflogen und befindet sich jetzt in Sektor ".$wormdata[end_x]."/".$wormdata[end_y];
		if ($data[schilde_aktiv] == 1) $return[msg] .= "<br>Ausgefallene Systeme: Schilde";
		if ($fleet == 0) $ra = $this->redalert($wormdata[end_x],$wormdata[end_y],$data[id],0,$wormdata[end_wese]);
		if ($ra != 0 || $ra != "") $return[msg] .= "<br>".$ra[msg];
		return $return;
	}
	
	function buildkonstrukt($shipId)
	{
		if ($this->cshow == 0) return 0;
		if ($this->cships_rumps_id != 7)
		{
			$return[msg] = "Zum Konstruktbau wird ein Workbee benötigt";
			return $return;
		}
		if ($this->db->query("SELECT a.id FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.coords_x=".$this->ccoords_x." AND a.coords_y=".$this->ccoords_y." AND wese=".$this->cwese." AND b.slots>0",1) != 0)
		{
			$return[msg] = "Es befindet sich bereits eine Station in diesem Sektor";
			return $return;
		}
		if ($this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=111 AND user_id=".$this->user,1) > 0)
		{
			$return[msg] = "Du kannst nur 1 Konstrukt haben";
			return $return;
		}
		global $myColony;
		if ($myColony->getuserresearch(62,$this->user) == 0)
		{
			$return[msg] = "Du musst zuerst den Vorposten erforschen";
			return $return;
		}
		if ($this->cclass[crew_min] > $this->ccrew)
		{
			$return[msg] = "Zum Konstruktbau werden ".$this->cclass[crew_min]." Crewmitglieder benötigt";
			return $return;
		}
		global $myMap;
		$mapdata = $myMap->getfieldbycoords($this->ccoords_x,$this->ccoords_y);
		if ($mapdata[type] > 10 && $mapdata[type] != 12 && $mapdata[type] != 17 && $mapdata[type] != 18 && $mapdata[type] != 19 && $mapdata[type] != 20 && $mapdata[type] != 23 && $mapdata[type] != 24 && $mapdata[type] != 25 && $mapdata[type] != 26 && $mapdata[type] != 29)
		{
			$return[msg] = "In diesem Sektor kann kein Konstrukt errichtet werden";
			return $return;
		}
		$builddat = $this->getclassbyid(111);
		if ($this->cenergie < 15)
		{
			$return[msg] = "Es wird 15 Energie auf dem Workbee benötigt - Vorhanden sind nur ".$this->cenergie;
			return $return;
		}
		$return = $this->mincost(111,$this->user,$shipId);
		if ($return[code] == 0) return $return;
		$this->db->query("INSERT INTO stu_ships (ships_rumps_id,user_id,name,coords_x,coords_y,huelle,epsmodlvl,wese) VALUES ('111','".$this->user."','Konstrukt','".$this->ccoords_x."','".$this->ccoords_y."','25','31','".$this->cwese."')");
		$this->db->query("UPDATE stu_ships SET energie=0 WHERE id=".$this->cid);
		$return[msg] = "Konstrukt errichtet";
		return $return;
	}
	
	function buildstation($huellmod,$schildmod,$waffenmod,$sensormod,$reaktormod,$epsmod,$computermod,$stationId,$shipId)
	{
		if ($this->cshow == 0) return 0;
		if ($this->cships_rumps_id != 111) return 0;
		$builddat = $this->getclassbyid($stationId);
		if ($builddat == 0) return 0;
		if ($builddat[slots] == 0) return 0;
		if (($builddat[id] != 48) && ($builddat[id] != 49) && ($builddat[id] != 50) && ($builddat[id] != 59) && ($builddat[id] != 88) && ($builddat[id] != 87) && ($builddat[id] != 101) && ($builddat[id] != 102) && ($builddat[id] != 103) && ($builddat[id] != 104)) return 0;
		if ($this->db->query("SELECT id FROM stu_ships_buildprogress WHERE ships_id=".$shipId,1) != 0)
		{
			$return[msg] = "Auf diesem Konstrukt wird bereits eine Station errichtet";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_ships WHERE dock=".$shipId." AND ships_rumps_id=7",1) == 0)
		{
			$return[msg] = "Um eine Station bauen zu können muss ein Workbee am Konstrukt angedockt sein";
			return $return;
		}
		global $myColony;
		if ($builddat[id] == 48) $research = $myColony->getuserresearch(62,$this->user);
		if ($builddat[id] == 49) $research = $myColony->getuserresearch(63,$this->user);
		if ($builddat[id] == 50) $research = $myColony->getuserresearch(64,$this->user);
		if ($builddat[id] == 59) $research = $myColony->getuserresearch(65,$this->user);
		if ($builddat[id] == 88) $research = $myColony->getuserresearch(112,$this->user);
		if ($builddat[id] == 101) $research = $myColony->getuserresearch(94,$this->user);
		if ($builddat[id] == 102) $research = $myColony->getuserresearch(95,$this->user);
		if ($builddat[id] == 103) $research = $myColony->getuserresearch(96,$this->user);
		if ($builddat[id] == 104) $research = $myColony->getuserresearch(97,$this->user);
		if ($builddat[id] == 87) $research = $myColony->getuserresearch(132,$this->user);
		if ($research == 0)
		{
			$return[msg] = "Dieser Stationstyp wurde noch nicht erforscht";
			return $return;
		}
		if (($this->db->query("SELECT COUNT(a.id) FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps AS b ON a.ships_rumps_id=b.id WHERE a.user_id=".$this->user." AND b.slots>1",1) == 3) && ($builddat[id] != 88))
		{
			$return[msg] = "Du kannst maximal 3 Stationen bauen";
			return $return;
		}
		if ($builddat[id] == 88 && $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=88 AND user_id=".$this->user,1) == 2)
		{
			$return[msg] = "Du kannst maximal 2 Sensorenphalanxen bauen";
			return $return;
		}
		if (($this->db->query("SELECT count(id) FROM stu_ships WHERE (ships_rumps_id=101 OR ships_rumps_id=102 OR ships_rumps_id=103 OR ships_rumps_id=104 OR ships_rumps_id=87) AND user_id=".$this->user,1) == 1) && (($builddat[id] == 87) || (($builddat[id] >= 101) && ($builddat[id] <= 104))))
		{
			$return[msg] = "Es kann maximal ein Versorgungsposten gebaut werden";
			return $return;
		}
		if ($this->cenergie < $builddat[eps_cost])
		{
			$return[msg] = "Es wird ".$builddat[eps_cost]." Energie benötigt - Vorhanden ist nur ".$this->cenergie;
			return $return;
		}
		$result = $this->checkModules($huellmod,$schildmod,$waffenmod,$epsmod,$computermod,$sensormod,$reaktormod,$stationId,$shipId,$this->user);
		if ($result[code] == 0)
		{
			$return = $result;
			return $return;
		}
		$buildtime = $builddat[buildtime];
		$points = $builddat[points];
		$module = $this->getmodulebyid($huellmod);
		$huelle = $module[huell]*$builddat[huellmod];
		$buildtime += ($module[buildtime]*$builddat[huellmod]);
		$points += ($module[wirt]*$builddat[huellmod]);
		$module = $this->getmodulebyid($schildmod);
		$buildtime += ($module[buildtime]*$builddat[schildmod]);
		$points += ($module[wirt]*$builddat[schildmod]);
		if ($waffenmod != 0)
		{
			$module = $this->getmodulebyid($waffenmod);
			$points += ($module[wirt]*$builddat[waffenmod]);
			$buildtime += ($module[buildtime]*$builddat[waffenmod]);
		}
		$module = $this->getmodulebyid($epsmod);
		$points += ($module[wirt]*$builddat[epsmod]);
		$buildtime += ($module[buildtime]*$builddat[epsmod]);
		$module = $this->getmodulebyid($computermod);
		$points += $module[wirt];
		$buildtime += $module[buildtime];
		$module = $this->getmodulebyid($sensormod);
		$points += ($module[wirt]*$builddat[sensormod]);
		$buildtime += ($module[buildtime]*$builddat[sensormod]);
		if ($reaktormod != 0)
		{
			$module = $this->getmodulebyid($reaktormod);
			$points += $module[wirt];
			$buildtime += $module[buildtime];
		}
		$wirtsum = $this->db->query("SELECT SUM(wirtschaft) FROM stu_colonies WHERE user_id=".$this->user,1);
		global $myUser;
		if ($this->uwirtmin > 0)
		{
			$return[msg] = "Du musst zuerst noch ".$this->uwirtmin." Wirtschaftspunkte aufholen";
			return $return;
		}
		$wirtsum += floor($myUser->usymp/2500);
		$wirtdata = $this->db->query("SELECT SUM(points) FROM stu_ships WHERE user_id=".$this->user,1);
		$buildwp = $this->db->query("SELECT SUM(points) FROM stu_ships_buildprogress WHERE user_id=".$this->user,1);
		if (round($wirtsum,2) < round($wirtdata+$points+$buildwp,2))
		{
			$return[msg] = "Es sind ".round($wirtsum,2)." Punkte vorhanden - Zum Bau werden ".round($wirtdata+$points+$buildwp,2)." benötigt";
			return $return;
		}
		if ($this->cenergie - $builddat[eps_cost] < 0)
		{
			$return[msg] = "Es wird ".$this->cclass[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
			return $return;
		}
		$result = $this->mincost($stationId,$this->user,$shipId);
		if ($result[code] == 0)
		{
			$return[msg] = $result[msg];
			return $return;
		}
		$module = $this->getmodulebyid($huellmod);
		$this->lowerstoragebygoodid($builddat[huellmod],$module[goods_id],$shipId);
		$module = $this->getmodulebyid($schildmod);
		$this->lowerstoragebygoodid($builddat[schildmod],$module[goods_id],$shipId);
		if ($waffenmod != 0)
		{
			$module = $this->getmodulebyid($waffenmod);
			$waffenmodlvl = $module[lvl];
			$this->lowerstoragebygoodid($builddat[waffenmod],$module[goods_id],$shipId);
		}
		$module = $this->getmodulebyid($epsmod);
		$this->lowerstoragebygoodid($builddat[epsmod],$module[goods_id],$shipId);
		$module = $this->getmodulebyid($computermod);
		$this->lowerstoragebygoodid(1,$module[goods_id],$shipId);
		$module = $this->getmodulebyid($sensormod);
		$this->lowerstoragebygoodid($builddat[sensormod],$module[goods_id],$shipId);
		if ($reaktormod != 0)
		{
			$module = $this->getmodulebyid($reaktormod);
			$this->lowerstoragebygoodid(1,$module[goods_id],$shipId);
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-".$builddat[eps_cost]." WHERE id=".$this->cid);
		$this->db->query("DELETE FROM stu_ships_storage WHERE ships_id=".$shipId);
		$this->db->query("INSERT INTO stu_ships_buildprogress (user_id,ships_id,ships_rumps_id,huelle,huellmodlvl,sensormodlvl,waffenmodlvl,schildmodlvl,reaktormodlvl,computermodlvl,epsmodlvl,buildtime,points) VALUES ('".$this->user."','".$shipId."','".$builddat[id]."','".$huelle."','".$huellmod."','".$sensormod."','".$waffenmod."','".$schildmod."','".$reaktormod."','".$computermod."','".$epsmod."','".(time()+$buildtime)."','".$points."')");
		$return[msg] = "Der Bau der Station (".$builddat[name].") hat begonnen. Fertigstellung am ".date("d.m.y H:i:s",time()+$buildtime);
		return $return;
	}
	
	function getstationbuildoption($rumpsId)
	{
		global $myColony;
		if (($rumpsId == 48) AND ($myColony->getuserresearch(62,$this->user) == 0)) return 0;
		elseif (($rumpsId == 49) AND ($myColony->getuserresearch(63,$this->user) == 0)) return 0;
		elseif (($rumpsId == 50) AND ($myColony->getuserresearch(64,$this->user) == 0)) return 0;
		elseif (($rumpsId == 59) AND ($myColony->getuserresearch(65,$this->user) == 0)) return 0;
		elseif (($rumpsId == 88) AND ($myColony->getuserresearch(112,$this->user) == 0)) return 0;
		elseif (($rumpsId == 101) AND ($myColony->getuserresearch(94,$this->user) == 0)) return 0;
		elseif (($rumpsId == 102) AND ($myColony->getuserresearch(95,$this->user) == 0)) return 0;
		elseif (($rumpsId == 103) AND ($myColony->getuserresearch(96,$this->user) == 0)) return 0;
		elseif (($rumpsId == 104) AND ($myColony->getuserresearch(97,$this->user) == 0)) return 0;
		elseif (($rumpsId == 87) AND ($myColony->getuserresearch(132,$this->user) == 0)) return 0;
		if (($rumpsId == 48) || ($rumpsId == 49) || ($rumpsId == 50) || ($rumpsId == 59))
		{	
			if ($this->db->query("SELECT COUNT(a.id) FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps AS b ON a.ships_rumps_id=b.id WHERE a.user_id=".$this->user." AND b.slots>1",1) == 3)
			{
				return 0;
			}
		}
		elseif (($rumpsId == 87) || (($rumpsId >= 101) && ($rumpsId <= 104)))
		{
			if ($this->db->query("SELECT COUNT(a.id) FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps AS b ON a.ships_rumps_id=b.id WHERE a.user_id=".$this->user." AND b.slots>1",1) == 3)
			{
				return 0;
			}
			if ($this->db->query("SELECT COUNT(id) FROM stu_ships WHERE (ships_rumps_id=101 OR ships_rumps_id=102 OR ships_rumps_id=103 OR ships_rumps_id=104 OR ships_rumps_id=87) AND user_id=".$this->user,1) == 1)
			{
				return 0;
			}
		}
		elseif ($rumpsId == 88)
		{
			if ($this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=88 AND user_id=".$this->user,1) == 2)
			{
				return 0;
			}
		}
		return 1;
	}

	function mincost($classId,$userId,$shipId)
	{
		include_once("inc/shipcost.inc.php");
		$result = getcostbyclass($classId);
		if ($result == 0) return 1;
		for ($i=0;$i<count($result);$i++)
		{
			$rress = $this->db->query("SELECT count FROM stu_ships_storage WHERE goods_id=".$result[$i][goods_id]." AND ships_id=".$shipId,1);
			if ($result[$i]['count'] > $rress)
			{
				$return[msg] = "Es werden ".$result[$i]['count']." ".$result[$i][name]." benötigt - Vorhanden sind nur ".$rress;
				$return[code] = 0;
				return $return;
			}
		}
		for ($i=0;$i<count($result);$i++) $this->lowerstoragebygoodid($result[$i]['count'],$result[$i][goods_id],$shipId);
		$return[code] = 1;
		return $return;
	}
	
	function checkdecloak($shipId,$userId) { return $this->db->query("SELECT ships_id FROM stu_ships_uncloaked WHERE ships_id=".$shipId." AND user_id=".$userId,1); }
	
	function adddockperm($shipId,$type,$dockId,$perm)
	{
		if ($this->cshow == 0) return 0;
		if ($perm != 2 && $perm != 1) return 0;
		if ($this->cclass[slots] == 0) return 0;
		if ($type == "shipid")
		{
			$data2 = $this->db->query("SELECT user_id,name FROM stu_ships WHERE id=".$dockId,4);
			if ($data2 == 0)
			{
				$return[msg] = "Dieses Schiff existiert nicht";
				return $return;
			}
			if ($data2[user_id] == $this->user)
			{
				$return[msg] = "Du kannst Deinen eigenen Schiffen keine Andockerlaubnis erstellen";
				return $return;
			}
			if ($this->db->query("SELECT id FROM stu_dock_permissions WHERE ships_id=".$shipId." AND id2=".$dockId." AND type='ship'",1) > 0)
			{
				$return[msg] = "Für dieses Schiff ist bereits eine Andockregel vorhanden";
				return $return;
			}
			$this->db->query("INSERT INTO stu_dock_permissions (ships_id,type,id2,mode) VALUES ('".$shipId."','ship',".$dockId.",'".$perm."')");
			$return[msg] = "Die Andockerlaubnis für die ".stripslashes($data2[name])." wurde erstellt";
		}
		elseif ($type == "allyid")
		{
			global $myAlly;
			$data2 = $myAlly->getallybyid($dockId);
			if ($data2 == 0)
			{
				$return[msg] = "Diese Allianz existiert nicht";
				return $return;
			}
			
			if ($this->db->query("SELECT id FROM stu_dock_permissions WHERE ships_id=".$shipId." ANd id2=".$dockId." AND type='ally'",1) > 0)
			{
				$return[msg] = "Für diese Allianz ist bereits eine Andockregel vorhanden";
				return $return;
			}
			$this->db->query("INSERT INTO stu_dock_permissions (ships_id,type,id2,mode) VALUES ('".$shipId."','ally',".$dockId.",'".$perm."')");
			$return[msg] = "Die Andockerlaubnis für die Allianz ".$data2[name]." wurde erstellt";
		}
		elseif ($type == "userid")
		{
			global $myUser;
			$data2 = $myUser->getuserbyid($dockId);
			if ($data2 == 0)
			{
				$return[msg] = "Dieser Siedler existiert nicht";
				return $return;
			}
			
			if ($this->db->query("SELECT id FROM stu_dock_permissions WHERE ships_id=".$shipId." ANd id2=".$dockId." AND type='user'",1) > 0)
			{
				$return[msg] = "Für diesen Siedler ist bereits eine Andockregel vorhanden";
				return $return;
			}
			$this->db->query("INSERT INTO stu_dock_permissions (ships_id,type,id2,mode) VALUES ('".$shipId."','user',".$dockId.",'".$perm."')");
			$return[msg] = "Die Andockerlaubnis für den User ".$data2[user]." wurde erstellt";
		}
		return $return;
	}
	
	function dedock($shipId,$shipId2)
	{
		if ($this->cshow == 0) return 0;
		$data2 = $this->db->query("SELECT user_id,ships_rumps_id,name,dock FROM stu_ships WHERE id=".$shipId2,4);
		if ($data2 == 0) return 0;
		if ($data2[dock] == 0) return 0;
		if ($this->cclass[crew_min] - 2 > $this->ccrew)
		{
			$return[msg] = "Zum Abdocken werden ".($this->cclass[crew_min] - 2)." Crewmitglieder benötigt";
			return $return;
		}
		if ($data2[ships_rumps_id] == 7)
		{
			if ($this->db->query("SELECT id FROM stu_ships_buildprogress WHERE ships_id=".$this->cid,1) > 0)
			{
				$return[msg] = "Hier ist zur Zeit eine Station in Bau. Deshalb kann das Workbee nicht abgedockt werden";
				return $return;
			}
		}
		if ($this->user != $data2[user_id])
		{
			global $myComm;
			$myComm->sendpm($data2[user_id],$this->user,"Die ".$data2[name]." wurde von der ".$this->cname." abgedockt",2);
		}
		$this->db->query("UPDATE stu_ships SET dock=0 WHERE id=".$shipId2);
		$return[msg] = "Die ".$data2[name]." wurde von der ".$this->cname." abgedockt";
		return $return;
	}
	
	function getdockpermissions($shipId)
	{
		if ($this->cshow == 0) return 0;
		$data = $this->db->query("SELECT a.id,a.id2,a.mode,b.ships_rumps_id,b.name,b.user_id FROM stu_dock_permissions as a LEFT OUTER JOIN stu_ships as b ON a.id2=b.id WHERE a.ships_id=".$shipId." AND a.type='ship'",2);
		$tmp = $this->db->query("SELECT a.id,a.id2,a.mode,b.user as username FROM stu_dock_permissions as a LEFT OUTER JOIN stu_user as b ON a.id2=b.id WHERE a.ships_id=".$shipId." AND a.type='user'",2);
		if (is_array($tmp) && is_array($data)) $data = array_merge($data,$tmp);
		elseif (is_array($tmp) && !is_array($data)) $data = $tmp;
		$tmp = $this->db->query("SELECT a.id,a.id2,a.mode,b.name as allyname FROM stu_dock_permissions as a LEFT OUTER JOIN stu_allys as b ON a.id2=b.id WHERE a.ships_id=".$shipId." AND a.type='ally'",2);
		if (is_array($tmp) && is_array($data)) $data = array_merge($data,$tmp);
		if (is_array($tmp) && !is_array($data)) $data = $tmp;
		return $data;
	}
	
	function deldockpermission($shipId,$dockId)
	{
		if ($this->cshow == 0) return 0;
		$this->db->query("DELETE FROM stu_dock_permissions WHERE ships_id=".$shipId." AND id=".$dockId);
		$return[msg] = "Die Andockerlaubnis wurde gelöscht";
		return $return;
	}
	
	function getstoragecountbyid($shipId) { return $this->db->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$shipId,1); }
	
	function randomPhaser($shipId)
	{
		if ($this->cshow == 0 || $this->cclass[probe] == 1) return 0;
		if ($this->cenergie == 0)
		{
			$return[msg] = "Zum Abschuß einer Phasersalve wird mindestens 1 Energie benötigt";
			return $return;
		}
		if ($this->ctype != 31 && $this->ctype != 15) return 0;
		$shipCount = $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE coords_x=".$this->ccoords_x." AND coords_y=".$this->ccoords_y." AND id!=".$shipId,1);
		if ($shipCount > 50) $shoot = 1;
		if (!$shoot)
		{
			if (rand(1,9) <= $shipCount) $shoot = 1;
			else
			{
				$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$shipId);
				$return[msg] = "Die Phasersalve hat kein Ziel getroffen";
				return $return;
			}
		}
		if ($shoot == 1) return $this->phaser($shipId,$this->db->query("SELECT id FROM stu_ships WHERE coords_x=".$this->ccoords_x." AND coords_y=".$this->ccoords_y." AND id!=".$shipId." ORDER BY RAND() LIMIT 1",1),$this->user,0,0);
	}
	
	function checkModules($huellmod,$schildmod,$waffenmod,$epsmod,$computermod,$sensormod,$reaktormod,$classId,$shipId,$userId)
	{
		$class = $this->getclassbyid($classId);
		if ($huellmod == 0)
		{
			$return[msg] = "Es wurde kein Hüllenmodul ausgewählt";
			$return[code] = 0;
			return $return;
		}
		$module = $this->getmodulebyid($huellmod);
		if (($module[lvl] > $class[huellmod_max]) || ($module[lvl] < $class[huellmod_min]))
		{
			$return[msg] = "Dieses Hüllenmodul kann in den gewählten Schiffsrumpf nicht eingebaut werden";
			$return[code] = 0;
			return $return;
		}
		$stor = $this->checkstoragebygoodid($class[huellmod],$module[goods_id],$shipId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Hüllenmodul befindet sich nicht an Bord des Konstrukts";
			$return[code] = 0;
			return $return;
		}
		if ($schildmod == 0)
		{
			$return[msg] = "Es wurde kein Schildmodul ausgewählt";
			$return[code] = 0;
			return $return;
		}
		$module = $this->getmodulebyid($schildmod);
		if (($module[lvl] > $class[schildmod_max]) || ($module[lvl] < $class[schildmod_min]))
		{
			$return[msg] = "Dieses Schildmodul kann in den gewählten Schiffsrumpf nicht eingebaut werden";
			$return[code] = 0;
			return $return;
		}
		$stor = $this->checkstoragebygoodid($class[schildmod],$module[goods_id],$shipId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Schildmodul befindet sich nicht an Bord des Konstrukts";
			$return[code] = 0;
			return $return;
		}
		if ($waffenmod != 0)
		{
			$module = $this->getmodulebyid($waffenmod);
			if (($module[lvl] > $class[waffenmod_max]) || ($module[lvl] < $class[waffenmod_min]))
			{
				$return[msg] = "Dieses Waffenmodul kann in den gewählten Schiffsrumpf nicht eingebaut werden";
				$return[code] = 0;
				return $return;
			}
			$stor = $this->checkstoragebygoodid($class[waffenmod],$module[goods_id],$shipId);
			if ($stor == 0)
			{
				$return[msg] = "Dieses Waffenmodul befindet sich nicht an Bord des Konstrukts";
				$return[code] = 0;
				return $return;
			}
		}
		if ($epsmod == 0)
		{
			$return[msg] = "Es wurde kein EPS-Gittermodul ausgewählt";
			$return[code] = 0;
			return $return;
		}
		$module = $this->getmodulebyid($epsmod);
		if (($module[lvl] > $class[epsmod_max]) || ($module[lvl] < $class[epsmod_min]))
		{
			$return[msg] = "Dieses EPS-Gittermodul kann in den gewählten Schiffsrumpf nicht eingebaut werden";
			$return[code] = 0;
			return $return;
		}
		$stor = $this->checkstoragebygoodid($class[epsmod],$module[goods_id],$shipId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses EPS-Gittermodul befindet sich nicht an Bord des Konstrukts";
			$return[code] = 0;
			return $return;
		}
		if ($computermod == 0)
		{
			$return[msg] = "Es wurde kein Computermodul ausgewählt";
			$return[code] = 0;
			return $return;
		}
		$module = $this->getmodulebyid($computermod);
		if (($module[lvl] > $class[computermod_max]) || ($module[lvl] < $class[computermod_min]))
		{
			$return[msg] = "Dieses Computermodul kann in den gewählten Schiffsrumpf nicht eingebaut werden";
			$return[code] = 0;
			return $return;
		}
		$stor = $this->checkstoragebygoodid(1,$module[goods_id],$shipId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Computermodul befindet sich nicht an Bord des Konstrukts";
			$return[code] = 0;
			return $return;
		}
		if ($sensormod == 0)
		{
			$return[msg] = "Es wurde kein Sensorenmodul ausgewählt";
			$return[code] = 0;
			return $return;
		}
		$module = $this->getmodulebyid($sensormod);
		if (($module[lvl] > $class[sensormod_max]) || ($module[lvl] < $class[sensormod_min]))
		{
			$return[msg] = "Dieses Sensorenmodul kann in den gewählten Schiffsrumpf nicht eingebaut werden";
			$return[code] = 0;
			return $return;
		}
		$stor = $this->checkstoragebygoodid($class[sensormod],$module[goods_id],$shipId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Sensorenmodul befindet sich nicht an Bord des Konstrukts";
			$return[code] = 0;
			return $return;
		}
		if ($reaktormod != 0)
		{
			$module = $this->getmodulebyid($reaktormod);
			if (($module[lvl] > $class[reaktormod_max]) || ($module[lvl] < $class[reaktormod_min]))
			{
				$return[msg] = "Dieses Reaktormodul kann in den gewählten Schiffsrumpf nicht eingebaut werden";
				$return[code] = 0;
				return $return;
			}
			$stor = $this->checkstoragebygoodid(1,$module[goods_id],$shipId);
			if ($stor == 0)
			{
				$return[msg] = "Dieses Reaktormodul befindet sich nicht an Bord des Konstrukts";
				return $return;
			}
		}
		$return[code] = 1;
		return $return;
	}
	
	function checkstoragebygoodid($count,$goodId,$shipId) { return $this->db->query("SELECT goods_id FROM stu_ships_storage WHERE count>=".$count." AND goods_id=".$goodId." AND ships_id=".$shipId,3); }
	
	function lowerstoragebygoodid($count,$goodId,$shipId)
	{
		$aff = $this->db->query("UPDATE stu_ships_storage SET count=count-".$count." WHERE count>".$count." AND goods_id=".$goodId." AND ships_id=".$shipId,6);
		if ($aff == 0) $this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=".$goodId." AND ships_id=".$shipId);
		return 1;
	}
	
	function upperstoragebygoodid($count,$goodId,$shipId,$userId)
	{
		if ($count == 0) return 0;
		$aff = $this->db->query("UPDATE stu_ships_storage SET count=count+".$count." WHERE goods_id=".$goodId." AND ships_id=".$shipId,6);
		if ($aff == 0) $this->db->query("INSERT INTO stu_ships_storage (ships_id,user_id,goods_id,count) VALUES ('".$shipId."','".$userId."','".$goodId."','".$count."')");
		return 1;
	}
	
	function checkStationProgress($shipId) { return $this->db->query("SELECT * FROM stu_ships_buildprogress WHERE ships_id=".$shipId,4); }
	
	function defendship($shipId2,$shipId,$userId)
	{
		if ($this->cshow == 0) return 0;
		$data2 = $this->db->query("SELECT name,coords_x,coords_y,wese,fleets_id FROM stu_ships WHERE id=".$shipId2,4);
		$data2 = $this->getdatabyid($shipId2);
		if ($data2 == 0) return 0;
		if ($this->ccoords_x != $data2[coords_x] || $this->ccoords_y != $data2[coords_y] || $this->cwese != $data2[wese]) return 0;
		if ($this->cfleets_id == $data2[fleets_id] && $this->cfleets_id > 0)
		{
			$return[msg] = "Das Schiff kann nicht verteidigt werden, da es sich in der selben Flotte befindet";
			return $return;
		}
		if ($this->ckss == 0)
		{
			$return[msg] = "Die Kurzstreckensensoren sind nicht aktiviert";
			return $return;
		}
		if ($this->cclass[crew_min] > $this->ccrew)
		{
			$return[msg] = "Zur Verteidigung werden ".$this->cclass[crew_min]." Crewmitglieder benötigt";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_ships_action WHERE ships_id=".$shipId." AND mode='defend'",1) > 0)
		{
			$return[msg] = "Du verteidigst bereits ein anderes Schiff";
			return $return;
		}
		$this->db->query("INSERT INTO stu_ships_action (ships_id,ships_id2,mode) VALUES ('".$shipId."','".$shipId2."','defend')");
		$return[msg] = "Die ".stripslashes($data2[name])." wird jetzt von der ".$this->cname." verteidigt";
		return $return;
	}
	
	function deletedefender($shipId,$defendId,$userId)
	{
		$this->db->query("DELETE FROM stu_ships_action WHERE id=".$defendId." AND ships_id=".$shipId);
		$return[msg] = "Die Verteidigung wurde beendet";
		return $return;
	}
	
	function getdefender($shipId) { return $this->db->query("SELECT * FROM stu_ships_action WHERE mode='defend' AND ships_id=".$shipId,4); }
	
	function getmodulesbytype($type,$userId) { return $this->db->query("SELECT a.id,a.name,a.lvl FROM stu_ships_modules as a LEFT OUTER JOIN stu_modules_user as b ON a.id=b.modules_id WHERE a.type=".$type." AND b.user_id=".$userId." ORDER BY a.lvl",2); }
	
	function checkSystem($shipId,$system)
	{
		if ($this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$shipId." AND mode='".$system."'",1) == 0) return "<font color=Green>in Betrieb</font>";
		return "<font color=Red>ausgefallen</font>";
	}
	
	function lowerfield($field,$wert,$shipId,$userId) { $this->db->query("UPDATE stu_ships SET ".$field."=".$field."-".$wert." WHERE id=".$shipId." AND user_id=".$userId); }
	
	function getshipstoragesum() { return $this->db->query("SELECT SUM(count) as gcount,goods_id FROM stu_ships_storage WHERE user_id=".$this->user." GROUP BY goods_id ORDER BY goods_id",2); }
	
	function checkss($sys,$shipId) { return $this->db->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$shipId." AND mode='".$sys."'",1); }
	
	function ssdamage($sys,$shipId,$dur)
	{
		$this->db->query("INSERT INTO stu_ships_action (ships_id,mode,ships_id2) VALUES ('".$shipId."','".$sys."','".$dur."')");
		if ($sys == "lsendef") $this->db->query("UPDATE stu_ships SET lss=0 WHERE id=".$shipId);
		if ($sys == "cloakdef") $this->db->query("UPDATE stu_ships SET cloak=0 WHERE id=".$shipId);
		if ($sys == "shidef") $this->db->query("UPDATE stu_ships SET schilde_aktiv=0 WHERE id=".$shipId);
		if ($sys == "ksendef") $this->db->query("UPDATE stu_ships SET kss=0 WHERE id=".$shipId);
	}
	
	function repairssd($sys,$shipId) { $this->db->query("DELETE FROM stu_ships_action WHERE ships_id=".$shipId." ANd mode='".$sys."'"); }
	
	function fireprobe($shipId,$classlvl)
	{
		if ($this->cshow == 0) return 0;
		if ($this->cenergie < 1)
		{
			$return[msg] = "Zum Starten einer Sonde ist mindestens 1 Energie erforderlich";
			return $return;
		}
		if ($classlvl == 5)
		{
			$targetdata = $this->db->query("SELECT id,user_id,name,coords_x,coords_y FROM stu_ships WHERE coords_x=".$this->ccoords_x." AND coords_y=".$this->ccoords_y." AND wese=".$this->cwese." AND cloak=1 AND user_id!=".$this->user." ORDER BY RAND() LIMIT 1",4);
			if ($targetdata == 0)
			{
				$return[msg] .= "Keine getarnten Schiffe in Reichweite";
				return $return;
			}
			$msg = "Die ".$this->cname." startet eine Detektionsdrohne";
			for ($i=1;$i<=4;$i++)
			{
				if (rand(1,100) <= 20)
				{
					$msg .= "<br>Die ".stripslashes($targetdata[name])." wurde entdeckt";
					if ($this->db->query("SELECT ships_id FROM stu_ships_uncloaked WHERE ships_id=".$targetdata[id],1) == 0)
					{
						global $myComm,$myUser;
						$this->db->query("INSERT INTO stu_ships_uncloaked (user_id,ships_id) VALUES ('".$this->user."','".$targetdata[id]."')");
						$myComm->sendpm($targetdata[user_id],2,"Die ".stripslashes($targetdata[name])." wurde in Sektor ".$targetdata[coords_x]."/".$targetdata[coords_y]." von User ".$myUser->uuser." durch eine Detektionsdrohne entdeckt",2);
					}
					break;
				}
				else $msg .= "<br>Drohne konnte Ziel nicht aufspüren";
				if ($i == 4) $msg .= "<br>Energievorrat der Drohne verbraucht";
			}
			$this->lowerstoragebygoodid(1,215,$this->cid);
			$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$this->cid);
			$return[msg] .= $msg;
			return $return;
		}
		if ($classlvl == 1)
		{
			$goodId = 35;
			$classId = 176;
			$sensormod = 33;
			$computermod = 6;
		}
		elseif ($classlvl == 2)
		{
			$goodId = 36;
			$classId = 177;
			$sensormod = 34;
			$computermod = 44;
		}
		elseif ($classlvl == 3)
		{
			$goodId = 37;
			$classId = 178;
			$sensormod = 35;
			$computermod = 7;
		}
		elseif ($classlvl == 4)
		{
			$goodId = 204;
			$classId = 185;
			$sensormod = 36;
			$computermod = 8;
		}
		else return 0;
		if ($goodId == 0 || !$goodId) return 0;
		if ($this->getcountbygoodid($goodId,$this->cid) < 1)
		{
			$return[msg] = "Dieser Sondentyp befindet sich nicht an Bord";
			return $return;
		}
		if ($this->ctype == 3 || $this->ctype == 15 || $this->ctype == 13 || $this->ctype == 31) 
		{
			$return[msg] = "Kann hier keine Sonde starten";
			return $return;
		}
		if ($this->cships_rumps_id == 63 || $this->cships_rumps_id == 65 || $this->cships_rumps_id == 66 || $this->cships_rumps_id == 67 || $this->cships_rumps_id == 68)
		{
			$return[msg] = "Dieses Schiff kann keine Sonden starten";
			return $return;
		}
		$data = $this->getclassbyid($classId);
		$huell = $this->getmodulebyid(89);
		$eps = $this->getmodulebyid(91);
		$schild = $this->getmodulebyid(90);
		$this->lowerstoragebygoodid(1,$goodId,$this->cid);
		$this->db->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$this->cid);
		$this->db->query("INSERT INTO stu_ships (name,ships_rumps_id,user_id,coords_x,coords_y,huelle,energie,schilde,batt,crew,huellmodlvl,sensormodlvl,schildmodlvl,reaktormodlvl,epsmodlvl,antriebmodlvl,waffenmodlvl,computermodlvl,lss,kss,wese) VALUES ('Sonde','".$classId."','".$this->user."','".$this->ccoords_x."','".$this->ccoords_y."','".($data[huellmod]*$huell[huell])."','".($data[epsmod]*$eps[eps])."','".($data[schildmod]*$schild[shields])."','".$data[max_batt]."','".$data[crew]."','89','".$sensormod."','90','0','91','92','0','".$computermod."','1','1','".$this->cwese."')");
		$return[msg] = "Sonde gestartet";
		return $return;
	}

	function npcchangefiremode($shipId)
	{
		global $myUser;
		$data = $this->db->query("SELECT * FROM stu_ships WHERE id='".$shipId."'",4);
		$class = $this->db->query("SELECT * FROM stu_ships_rumps WHERE id='".$data[ships_rumps_id]."'",4);
		if ($class[size] < 5)
		{
			$return[msg] = "Umstellung nicht möglich";
			return $return;
		}
		$beg = 0;
		if ($this->gettorptypegood($shipId) == 17) {
			$beg = 17;
			$end = 202;
		}
		if ($this->gettorptypegood($shipId) == 202) {
			$beg = 202;
			$end = 17;
		}
		if ($this->gettorptypegood($shipId) == 40) {
			$beg = 40;
			$end = 201;
		}
		if ($this->gettorptypegood($shipId) == 201) {
			$beg = 201;
			$end = 40;
		}
		if ($beg == 0)
		{
			$return[msg] = "Keine Quad-fähigen Torpedos an Bord";
			return $return;
		}
		$this->db->query("UPDATE stu_ships_storage SET goods_id=".$end." WHERE ships_id=".$shipId." AND goods_id=".$beg);
		$return[msg] = "Feuermodus geändert";
		return $return;
	}

	function npcgetfiremode($shipId)
	{
		if ($this->gettorptypegood($shipId) == 17) return 1;
		if ($this->gettorptypegood($shipId) == 202) return 2;
		if ($this->gettorptypegood($shipId) == 40) return 1;
		if ($this->gettorptypegood($shipId) == 201) return 2;
		return 0;
	}

	function npcmvam($shipId)
	{
		global $myUser;
		if ($myUser->ustatus != 9)
		{
			$return[msg] = "Betrugsversuch";
			return $return;
		}
		$data = $this->db->query("SELECT * FROM stu_ships WHERE id='".$shipId."'",4);
		if ($data[ships_rumps_id] == 136)
		{
			$this->db->query("UPDATE stu_ships SET ships_rumps_id=165 WHERE id=".$shipId);
			if ($this->npcgetfiremode($shipId) == 1) $this->npcchangefiremode($shipId);
			$return[msg] = "Multi-Vektor-Angriffsmodus aktiviert";
			return $return;
		}
		if ($data[ships_rumps_id] == 165)
		{
			if ($this->npcgetfiremode($shipId) == 2) $this->npcchangefiremode($shipId);
			$this->db->query("UPDATE stu_ships SET ships_rumps_id=136 WHERE id=".$shipId);
			$return[msg] = "Multi-Vektor-Angriffsmodus deaktiviert";
			return $return;
		}
		if ($data[ships_rumps_id] == 174)
		{
			$this->db->query("UPDATE stu_ships SET ships_rumps_id=175 WHERE id=".$shipId);
			if ($this->npcgetfiremode($shipId) == 1) $this->npcchangefiremode($shipId);
			$return[msg] = "Multi-Vektor-Angriffsmodus aktiviert";
			return $return;
		}
		if ($data[ships_rumps_id] == 175)
		{
			if ($this->npcgetfiremode($shipId) == 2) $this->npcchangefiremode($shipId);
			$this->db->query("UPDATE stu_ships SET ships_rumps_id=174 WHERE id=".$shipId);
			$return[msg] = "Multi-Vektor-Angriffsmodus aktiviert";
			return $return;
		}
		$return[msg] = "Fehler";
		return $return;
	}
	
	function getfield($field,$shipId) { return $this->db->query("SELECT ".$field." FROM stu_ships WHERE id=".$shipId,1); }

	function getascanresults()
	{
		$this->db->query("DELETE FROM stu_sensor_detects WHERE UNIX_TIMESTAMP(date)<".(time()-64800));
		return $this->db->query("SELECT a.ships_rumps_id,a.coords_x,a.coords_y,UNIX_TIMESTAMP(a.date) date_tsp,b.user,c.secretimage FROM stu_sensor_detects as a LEFT JOIN stu_user as b ON a.user_id=b.id LEFT JOIN stu_ships_rumps as c on a.ships_rumps_id = c.id WHERE phalanx_id=".$this->cid." ORDER BY a.date");
	}

	function getascancount() { return $this->db->query("SELECT COUNT(ships_id) FROM stu_sensor_detects WHERE phalanx_id=".$this->cid,1); }

	function redalert($x,$y,$shipId=0,$fleet=0,$wese=1,$userId=0)
	{
		if ($userId == 0) $userId = $this->user;
		if ($shipId == 0 && $fleet == 0) return 0;
		$result = $this->db->query("SELECT a.id,a.ships_rumps_id,a.user_id,a.strb_mode,a.name,a.cloak,a.schilde,a.schilde_aktiv,a.alertlevel,a.crew,a.traktor,a.traktormode,a.waffenmodlvl FROM stu_ships as a LEFT JOIN stu_contactlist as b ON a.user_id=b.user_id WHERE a.wese=".$wese." AND a.coords_x=".$x." AND a.coords_y=".$y." AND a.energie>0 AND a.kss=1 AND a.user_id!=".$userId." AND a.alertlevel>=2 GROUP BY a.id ORDER BY RAND()");
		if (mysql_num_rows($result) == 0) return 0;
		global $myUser,$myComm;
		while($data=mysql_fetch_assoc($result))
		{
			if ($data[waffenmodlvl] == 0 && $data[strb_mode] == 1) continue;
			$behav = $this->db->query("SELECT behaviour FROM stu_contactlist WHERE recipient=".$userId." AND user_id=".$data[user_id],1);
			$ally = $myUser->getfield("allys_id",$data[user_id]);
			$allybeh = $this->db->query("SELECT type FROM stu_allys_beziehungen WHERE (allys_id1=".$myUser->ually." OR allys_id2=".$myUser->ually.") AND (allys_id1=".$ally." OR allys_id2=".$ally.")",1);
			if ($behav == 1 || ($behav == 0 && $data[alertlevel] < 3 && $allybeh != 1) || $allybeh > 2) continue;
			if ($myUser->ually == $ally && $myUser->ually > 0) continue;
			if ($ally != 0 && $myUser->ually != 0 && $allybeh > 2) continue;
			if ($data[crew] < $this->db->query("SELECT crew_min FROM stu_ships_rumps WHERE id=".$data[ships_rumps_id],1)) continue;
			$fleet != 0 ? $target = $this->db->query("SELECT id,name FROM stu_ships WHERE cloak=0 AND fleets_id=".$fleet." AND wese=".$wese." ORDER BY RAND() LIMIT 1",4) : $target = $this->db->query("SELECT id,name FROM stu_ships WHERE cloak=0 AND wese=".$wese." AND id=".$shipId,4);
			if ($target == 0) break;
			if ($data[traktormode] == 2 && $data[traktor] == $target[id]) continue;
			if (!$fremd) { $msg .= "Es wurden feindliche Schiffe geortet<br>---------------------------<br>"; $fremd = 1; }
			if ($res[code] == 1) $msg .= "---------------------------<br>";
			if ($data[cloak] == 1)
			{
				$this->db->query("UPDATE stu_ships SET cloak=0 WHERE id=".$data[id]);
				$msg .= "Die ".stripslashes($data[name])." deaktiviert die Tarnung<br>";
			}
			if ($data[schilde_aktiv] == 0 && $data[schilde] > 0 && $data[energie] > 1)
			{
				$this->db->query("UPDATE stu_ships SET traktor=0,traktormode=0 WHERE traktor=".$data[id]);
				$this->db->query("UPDATE stu_ships SET energie=energie-1,schilde_aktiv=1 WHERE id=".$data[id]);
				$msg .= "Die ".stripslashes($data[name])." aktiviert die Schilde<br>";
			}
			$data[strb_mode] == 2 ? $torp = $this->gettorptype($data[id]) : $torp = 0;
			$data[strb_mode] == 1 || ($data[strb_mode] == 2 && $torp == 0) ? $res = $this->phaser($data[id],$target[id],$data[user_id],0,0,5,0) : $res = $this->torp($data[id],$target[id],$data[user_id],$torp,0,5,0);
			if ($res[code] == 1)
			{
				$msg .= strip_tags($this->returnmsg,"<font></font><br><strong></strong>")."<br>";
				$myComm->sendpm($data[user_id],2,"<strong>Kampf in Sektor ".$x."/".$y."</strong><br>Die ".stripslashes($data[name])." (Alarm Rot/Freund-Feind-Erkennung) beschießt die ".stripslashes($target[name])." des Users ".stripslashes($myUser->getfield("user",$userId))." (".$this->user.")",2);
			}
			$this->returnmsg = "";
			$this->pmmsg = "";
			if ($res[destroy] == 1 && $fleet == 0) break;
		}
		if (!$res) $msg .= "Keine Reaktion der Schiffe";
		return array("msg" => $msg,"code" => $res[destroy]);
	}

	function getgravitonchance($shipId)
	{
		$data = $this->getdatabyid($shipId);
		$smodule = $this->getmodulebyid($this->csensormodlvl);
		$cmodule = $this->getmodulebyid($this->ccomputermodlvl);
		$chance = round($smodule[lvl] * $data[c][sensormod] * $cmodule[lvl] / 2);
		return $chance;
	}

	function gravitonscan($shipId)
	{
		global $myUser;
		global $myColony;
		$data = $this->getdatabyid($shipId);
		if ($data == 0) return 0;
		if ($data[user_id] != $this->user) return 0;
		if ($data[c][slots] < 2)
		{
			$return[msg] = "Dies ist keine Station";
			return $return;
		}
		if ($myColony->getuserresearch(233,$data[user_id]) == 0)
		{
			$return[msg] = "Gravitonscan wurde noch nicht erforscht";
			return $return;
		}
		if ($this->cenergie < 8)
		{
			$return[msg] = "Für einen Gravitonscan werden 8 Energie benötigt";
			return $return;
		}
		if ($this->ccrew < $this->cclass[crew_min])
		{
			$return[msg] = "Es werden ".$this->cclass[crew_min]." Crewmitglieder benötigt";
			return $return;
		}
		$j=0;
		$smodule = $this->getmodulebyid($this->csensormodlvl);
		$cmodule = $this->getmodulebyid($this->ccomputermodlvl);
		if ($cmodule[lvl] < 3)
		{
			$return[msg] = "Für einen Gravitonscan wird mindestens ein Isolinearer Computer benötigt";
			return $return;
		}
		$chance = round($smodule[lvl] * $data[c][sensormod] * $cmodule[lvl] / 2);
		$result2 = $this->db->query("SELECT id FROM stu_ships WHERE coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y]." AND wese=".$data[wese]."  AND cloak=1 AND user_id!=".$this->user);
		global $myComm;
		for ($i=0;$i<mysql_num_rows($result2);$i++)
		{
			$shipd = mysql_fetch_assoc($result2);
			$shipdat = $this->getDataById($shipd[id]);
			$chance2 = $chance * round(sqrt($shipdat[maxhuell]/$shipdat[huelle]),1);
			if (rand(1,100) <= $chance2)
			{
				if ($this->db->query("SELECT ships_id FROM stu_ships_uncloaked WHERE ships_id=".$shipdat[id],1) > 0) continue;
				$this->db->query("INSERT INTO stu_ships_uncloaked (user_id,ships_id) VALUES ('".$this->user."','".$shipdat[id]."')");
				$myComm->sendpm($shipdat[user_id],2,"Die ".$shipdat[name]." wurde in Sektor ".$shipdat[coords_x]."/".$shipdat[coords_y]." von User ".$myUser->getfield("user",$this->user)." durch einen Gravitonscan entdeckt",2);
				$j++;
			}
		}
		$this->db->query("UPDATE stu_ships SET energie=energie-8 WHERE id=".$shipId);
		$return[msg] = "Durch den Gravitonscan wurden ".$j." Schiffe entdeckt";
		return $return;
	}

	function getshiprepaircost ($shipId,$faktor=2)
	{
		global $myColony;
		$shipdata = $this->getdatabyid($shipId);
		$huelldam = $shipdata[maxhuell] - $shipdata[huelle];
		$huellfak = $huelldam / $shipdata[maxhuell];
		$cost = $myColony->getshipcostbyid($shipdata[ships_rumps_id]);
		for ($i=0;$i<19;$i++) $kosten[$i] = 0;
		for ($i=0;$i<count($cost);$i++) {
			if ($cost[$i][goods_id] > 9) $kosten[$cost[$i][goods_id]] = floor($cost[$i]['count'] * $huellfak);
			else $kosten[$cost[$i][goods_id]] = ceil($cost[$i]['count'] * $huellfak);
		}
		$kosten[0] = ceil($shipdata[c][eps_cost] * $huellfak);
		if ($kosten[0] > 200) $kosten[0] = 200;
		$cst = round($shipdata[c][huellmod]*$huellfak * $faktor);
		$module = $this->getmodulebyid($shipdata[huellmodlvl]);
		$kosten[modules][huellec] = $cst;
		$kosten[modules][huellem] = $shipdata[huellmodlvl];
		$kosten[modules][huelleg] = $module[goods_id];
		$cst = round($shipdata[c][schildmod]*$huellfak * $faktor);
		$module = $this->getmodulebyid($shipdata[schildmodlvl]);
		$kosten[modules][schildec] = $cst;
		$kosten[modules][schildem] = $shipdata[schildmodlvl];
		$kosten[modules][schildeg] = $module[goods_id];
		$cst = round($shipdata[c][sensormod]*$huellfak * $faktor);
		$module = $this->getmodulebyid($shipdata[sensormodlvl]);
		$kosten[modules][sensorc] = $cst;
		$kosten[modules][sensorm] = $shipdata[sensormodlvl];
		$kosten[modules][sensorg] = $module[goods_id];
		$cst = round($shipdata[c][waffenmod]*$huellfak * $faktor);
		$module = $this->getmodulebyid($shipdata[waffenmodlvl]);
		$kosten[modules][waffenc] = $cst;
		$kosten[modules][waffenm] = $shipdata[waffenmodlvl];
		$kosten[modules][waffeng] = $module[goods_id];
		$cst = round(1*$huellfak * $faktor);
		$module = $this->getmodulebyid($shipdata[reaktormodlvl]);
		$kosten[modules][reaktorc] = $cst;
		$kosten[modules][reaktorm] = $shipdata[reaktormodlvl];
		$kosten[modules][reaktorg] = $module[goods_id];
		$cst = round(1*$huellfak * $faktor);
		$module = $this->getmodulebyid($shipdata[antriebmodlvl]);
		$kosten[modules][antriebc] = $cst;
		$kosten[modules][antriebm] = $shipdata[antriebmodlvl];
		$kosten[modules][antriebg] = $module[goods_id];
		$cst = round(1*$huellfak * $faktor);
		$module = $this->getmodulebyid($shipdata[computermodlvl]);
		$kosten[modules][computerc] = $cst;
		$kosten[modules][computerm] = $shipdata[computermodlvl];
		$kosten[modules][computerg] = $module[goods_id];
		$cst = round($shipdata[c][epsmod]*$huellfak * $faktor);
		$module = $this->getmodulebyid($shipdata[epsmodlvl]);
		$kosten[modules][epsc] = $cst;
		$kosten[modules][epsm] = $shipdata[epsmodlvl];
		$kosten[modules][epsg] = $module[goods_id];
		return $kosten;
	}

	function shiprepair ($shipId,$targetId)
	{
		$shipdata = $this->getdatabyid($shipId);
		$targetdata = $this->getdatabyid($targetId);
		if ($shipdata == 0 || $targetdata == 0) return 0;
		if ($targetdata[c][trumfield] == 1)
		{
			$return[msg] = "Wracks können nicht repariert werden";
			return $return;
		}
		if ($targetdata[c][id] == 111)
		{
			$return[msg] = "Konstrukte können nicht repariert werden";
			return $return;
		}
		if ($targetdata[c][slots] == 0)
		{
			if ($targetdata[huelle] <= ($targetdata[maxhuell] * 0.4))
			{
				$return[msg] = "Schiff ist zu schwer beschädigt";
				return $return;
			}
		}
		if (($shipdata[coords_x] != $targetdata[coords_x]) || ($shipdata[coords_y] != $targetdata[coords_y]) || ($shipdata[wese] != $targetdata[wese]))
		{
			$return[msg] = "Die Schiffe müssen sich im selben Sektor befinden";
			return $return;
		}
		if (($shipdata[cloak] == 1) || ($targetdata[cloak] == 1))
		{
			$return[msg] = "Das Schiff ist getarnt";
			return $return;
		}
		if (($shipdata[schilde_aktiv] == 1) || ($targetdata[schilde_aktiv] == 1))
		{
			$return[msg] = "Das Schiff hat die Schilde aktiviert";
			return $return;
		}
		if ($targetdata[maxhuell] == $targetdata[huelle])
		{
			$return[msg] = "Das Schiff ist nicht beschädigt";
			return $return;
		}
		$cost = $this->getshiprepaircost($targetId);
		if ($targetdata[c][slots] == 0)
		{
			if ($cost[0] > $this->cenergie)
			{
				$return[msg] = "Zur Reparatur wird ".$cost[0]." Energie benötigt - Vorhanden ist nur ".$this->cenergie;
				return $return;
			}
			$count = $this->getcountbygoodid(3,$this->cid);
			if ($cost[3] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[3]." Baumaterial benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(6,$this->cid);
			if ($cost[6] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[6]." Duranium benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(9,$this->cid);
			if ($cost[9] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[9]." Tritanium benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(10,$this->cid);
			if ($cost[10] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[10]." Iso-Chips benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(12,$this->cid);
			if ($cost[12] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[12]." Kelbonit benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(14,$this->cid);
			if ($cost[14] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[14]." Nitrium benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(15,$this->cid);
			if ($cost[15] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[15]." Plasma benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(19,$this->cid);
			if ($cost[19] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[19]." Gel-Packs benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid($cost[modules][huelleg],$this->cid);
			if ($cost[modules][huellec] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][huellec]." Hüllenmodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid($cost[modules][schildeg],$this->cid);
			if ($cost[modules][schildec] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][schildec]." Schildmodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid($cost[modules][antriebg],$this->cid);
			if ($cost[modules][antriebc] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][antriebc]." Antriebsmodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			if ($shipdata[reaktormodlvl] > 0)
			{
				$count = $this->getcountbygoodid($cost[modules][reaktorg],$this->cid);
				if ($cost[modules][reaktorc] > $count)
				{
					$return[msg] = "Zur Reparatur werden ".$cost[modules][reaktorc]." Reaktormodule benötigt - Vorhanden sind nur ".$count;
					return $return;
				}
			}
			$count = $this->getcountbygoodid($cost[modules][sensorg],$this->cid);
			if ($cost[modules][sensorc] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][sensorc]." Sensormodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid($cost[modules][computerg],$this->cid);
			if ($cost[modules][computerc] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][computerc]." Computermodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			if ($shipdata[waffenmodlvl] > 0)
			{
				$count = $this->getcountbygoodid($cost[modules][waffeng],$this->cid);
				if ($cost[modules][waffenc] > $count)
				{
					$return[msg] = "Zur Reparatur werden ".$cost[modules][waffenc]." Waffenmodule benötigt - Vorhanden sind nur ".$count;
					return $return;
				}
			}
			$count = $this->getcountbygoodid($cost[modules][epsg],$this->cid);
			if ($cost[modules][epsc] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][epsc]." EPS-Gittermodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			if ($cost[modules][huellec] > 0) $this->lowerstoragebygoodid($cost[modules][huellec],$cost[modules][huelleg],$this->cid);
			if ($cost[modules][schildec] > 0) $this->lowerstoragebygoodid($cost[modules][schildec],$cost[modules][schildeg],$this->cid);
			if ($cost[modules][antriebm] > 0 && $cost[modules][antriebc] > 0) $this->lowerstoragebygoodid($cost[modules][antriebc],$cost[modules][antriebg],$this->cid);
			if ($cost[modules][reaktorm] > 0 && $cost[modules][reaktorc] > 0)
			{
				$this->lowerstoragebygoodid($cost[modules][reaktorc],$cost[modules][reaktorg],$this->cid);
				$wka = ",warpcore=0";
			}
			if ($cost[modules][waffenm] > 0 && $cost[modules][waffenc] > 0) $this->lowerstoragebygoodid($cost[modules][waffenc],$cost[modules][waffeng],$this->cid);
			if ($cost[modules][computerc] > 0) $this->lowerstoragebygoodid($cost[modules][computerc],$cost[modules][computerg],$this->cid);
			if ($cost[modules][epsc] > 0) $this->lowerstoragebygoodid($cost[modules][epsc],$cost[modules][epsg],$this->cid);
			if ($cost[modules][sensorc] > 0) $this->lowerstoragebygoodid($cost[modules][sensorc],$cost[modules][sensorg],$this->cid);
			if ($cost[0] > 0) $this->db->query("UPDATE stu_ships SET energie=energie-".$cost[0]." WHERE id=".$this->cid);
			if ($cost[3] > 0) $this->lowerstoragebygoodid($cost[3],3,$this->cid);
			if ($cost[6] > 0) $this->lowerstoragebygoodid($cost[6],6,$this->cid);
			if ($cost[9] > 0) $this->lowerstoragebygoodid($cost[9],9,$this->cid);
			if ($cost[10] > 0) $this->lowerstoragebygoodid($cost[10],10,$this->cid);
			if ($cost[12] > 0) $this->lowerstoragebygoodid($cost[12],12,$this->cid);
			if ($cost[14] > 0) $this->lowerstoragebygoodid($cost[14],14,$this->cid);
			if ($cost[15] > 0) $this->lowerstoragebygoodid($cost[15],15,$this->cid);
			if ($cost[19] > 0) $this->lowerstoragebygoodid($cost[19],19,$this->cid);
		}
		else
		{
			if ($cost[0] > ($this->cenergie + $targetdata[energie]))
			{
				$return[msg] = "Zur Reparatur wird ".$cost[0]." Energie benötigt - Vorhanden ist nur ".($this->cenergie + $targetdata[energie]);
				return $return;
			}
			$count = $this->getcountbygoodid(3,$this->cid) + $this->getcountbygoodid(3,$targetId);
			if ($cost[3] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[3]." Baumaterial benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(6,$this->cid) + $this->getcountbygoodid(6,$targetId);
			if ($cost[6] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[6]." Duranium benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(9,$this->cid) + $this->getcountbygoodid(9,$targetId);
			if ($cost[9] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[9]." Tritanium benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(10,$this->cid) + $this->getcountbygoodid(10,$targetId);
			if ($cost[10] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[10]." Iso-Chips benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(12,$this->cid) + $this->getcountbygoodid(12,$targetId);
			if ($cost[12] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[12]." Kelbonit benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(14,$this->cid) + $this->getcountbygoodid(14,$targetId);
			if ($cost[14] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[14]." Nitrium benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(15,$this->cid) + $this->getcountbygoodid(15,$targetId);
			if ($cost[15] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[15]." Plasma benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid(19,$this->cid) + $this->getcountbygoodid(19,$targetId);
			if ($cost[19] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[19]." Gel-Packs benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid($cost[modules][huelleg],$this->cid) + $this->getcountbygoodid($cost[modules][huelleg],$targetId);
			if ($cost[modules][huellec] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][huellec]." Hüllenmodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid($cost[modules][schildeg],$this->cid) + $this->getcountbygoodid($cost[modules][schildeg],$targetId);
			if ($cost[modules][schildec] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][schildec]." Schildmodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			if ($targetdata[reaktormodlvl] > 0)
			{
				$count = $this->getcountbygoodid($cost[modules][reaktorg],$this->cid) + $this->getcountbygoodid($cost[modules][reaktorg],$targetId);
				if ($cost[modules][reaktorc] > $count)
				{
					$return[msg] = "Zur Reparatur werden ".$cost[modules][reaktorc]." Reaktormodule benötigt - Vorhanden sind nur ".$count;
					return $return;
				}
			}
			$count = $this->getcountbygoodid($cost[modules][sensorg],$this->cid) + $this->getcountbygoodid($cost[modules][sensorg],$targetId);
			if ($cost[modules][sensorc] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][sensorc]." Sensormodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			$count = $this->getcountbygoodid($cost[modules][computerg],$this->cid) + $this->getcountbygoodid($cost[modules][computerg],$targetId);
			if ($cost[modules][computerc] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][computerc]." Computermodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			if ($targetdata[waffenmodlvl] > 0)
			{
				$count = $this->getcountbygoodid($cost[modules][waffeng],$this->cid) + $this->getcountbygoodid($cost[modules][waffeng],$targetId);
				if ($cost[modules][waffenc] > $count)
				{
					$return[msg] = "Zur Reparatur werden ".$cost[modules][waffenc]." Waffenmodule benötigt - Vorhanden sind nur ".$count;
					return $return;
				}
			}
			$count = $this->getcountbygoodid($cost[modules][epsg],$this->cid) + $this->getcountbygoodid($cost[modules][epsg],$targetId);
			if ($cost[modules][epsc] > $count)
			{
				$return[msg] = "Zur Reparatur werden ".$cost[modules][epsc]." EPS-Gittermodule benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			if ($cost[modules][huellec] > 0)
			{
				if ($this->getcountbygoodid($cost[modules][huelleg],$this->cid) >= $cost[modules][huellec]) 
				{
					$this->lowerstoragebygoodid($cost[modules][huellec],$cost[modules][huelleg],$this->cid);
				}
				else
				{
					$count = $cost[modules][huellec] - $this->getcountbygoodid($cost[modules][huelleg],$this->cid);
					$this->lowerstoragebygoodid($count,$cost[modules][huelleg],$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=".$cost[modules][huelleg]." AND ships_id=".$this->cid);
				}
			}
			if ($cost[modules][schildec] > 0)
			{
				if ($this->getcountbygoodid($cost[modules][schildeg],$this->cid) >= $cost[modules][schildec]) 
				{
					$this->lowerstoragebygoodid($cost[modules][schildec],$cost[modules][schildeg],$this->cid);
				}
				else
				{
					$count = $cost[modules][schildec] - $this->getcountbygoodid($cost[modules][schildeg],$this->cid);
					$this->lowerstoragebygoodid($count,$cost[modules][schildeg],$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=".$cost[modules][schildeg]." AND ships_id=".$this->cid);
				}
			}
			if ($cost[modules][reaktorm] > 0 && $cost[modules][reaktorc] > 0)
			{
				$wka = ",warpcore=0";
				if ($this->getcountbygoodid($cost[modules][reaktorg],$this->cid) >= $cost[modules][reaktorc]) 
				{
					$this->lowerstoragebygoodid($cost[modules][reaktorc],$cost[modules][reaktorg],$this->cid);
				}
				else
				{
					$count = $cost[modules][reaktorc] - $this->getcountbygoodid($cost[modules][reaktorg],$this->cid);
					$this->lowerstoragebygoodid($count,$cost[modules][reaktorg],$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=".$cost[modules][reaktorg]." AND ships_id=".$this->cid);
				}
			}
			if ($cost[modules][waffenm] > 0 && $cost[modules][waffenc] > 0)
			{
				if ($this->getcountbygoodid($cost[modules][waffeng],$this->cid) >= $cost[modules][waffenc]) 
				{
					$this->lowerstoragebygoodid($cost[modules][waffenc],$cost[modules][waffeng],$this->cid);
				}
				else
				{
					$count = $cost[modules][waffenc] - $this->getcountbygoodid($cost[modules][waffeng],$this->cid);
					$this->lowerstoragebygoodid($count,$cost[modules][waffeng],$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=".$cost[modules][waffeng]." AND ships_id=".$this->cid);
				}
			}
			if ($cost[modules][computerc] > 0)
			{
				if ($this->getcountbygoodid($cost[modules][computerg],$this->cid) >= $cost[modules][computerc]) 
				{
					$this->lowerstoragebygoodid($cost[modules][computerc],$cost[modules][computerg],$this->cid);
				}
				else
				{
					$count = $cost[modules][computerc] - $this->getcountbygoodid($cost[modules][computerg],$this->cid);
					$this->lowerstoragebygoodid($count,$cost[modules][computerg],$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=".$cost[modules][computerg]." AND ships_id=".$this->cid);
				}
			}
			if ($cost[modules][epsc] > 0)
			{
				if ($this->getcountbygoodid($cost[modules][epsg],$this->cid) >= $cost[modules][epsc]) 
				{
					$this->lowerstoragebygoodid($cost[modules][epsc],$cost[modules][epsg],$this->cid);
				}
				else
				{
					$count = $cost[modules][epsc] - $this->getcountbygoodid($cost[modules][epsg],$this->cid);
					$this->lowerstoragebygoodid($count,$cost[modules][epsg],$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=".$cost[modules][epsg]." AND ships_id=".$this->cid);
				}
			}
			if ($cost[modules][sensorc] > 0)
			{
				if ($this->getcountbygoodid($cost[modules][sensorg],$this->cid) >= $cost[modules][sensorc]) 
				{
					$this->lowerstoragebygoodid($cost[modules][sensorc],$cost[modules][sensorg],$this->cid);
				}
				else
				{
					$count = $cost[modules][sensorc] - $this->getcountbygoodid($cost[modules][sensorg],$this->cid);
					$this->lowerstoragebygoodid($count,$cost[modules][sensorg],$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=".$cost[modules][sensorg]." AND ships_id=".$this->cid);
				}
			}
			if ($cost[0] > 0)
			{
				if ($this->cenergie >= $cost[0]) 
				{
					$this->db->query("UPDATE stu_ships SET energie=energie-".$cost[0]." WHERE id=".$this->cid);
				}
				else
				{
					$count = $cost[0] - $this->cenergie;
					$this->db->query("UPDATE stu_ships SET energie=energie-".$count." WHERE id=".$targetId);
					$this->db->query("UPDATE stu_ships SET energie=0 WHERE id=".$this->cid);
				}
			}
			if ($cost[3] > 0)
			{
				if ($this->getcountbygoodid(3,$this->cid) >= $cost[3]) 
				{
					$this->lowerstoragebygoodid($cost[3],3,$this->cid);
				}
				else
				{
					$count = $cost[3] - $this->getcountbygoodid(3,$this->cid);
					$this->lowerstoragebygoodid($count,3,$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=3 AND ships_id=".$this->cid);
				}
			}
			if ($cost[6] > 0)
			{
				if ($this->getcountbygoodid(6,$this->cid) >= $cost[6]) 
				{
					$this->lowerstoragebygoodid($cost[6],6,$this->cid);
				}
				else
				{
					$count = $cost[6] - $this->getcountbygoodid(6,$this->cid);
					$this->lowerstoragebygoodid($count,6,$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=6 AND ships_id=".$this->cid);
				}
			}
			if ($cost[9] > 0)
			{
				if ($this->getcountbygoodid(9,$this->cid) >= $cost[9]) 
				{
					$this->lowerstoragebygoodid($cost[9],9,$this->cid);
				}
				else
				{
					$count = $cost[9] - $this->getcountbygoodid(9,$this->cid);
					$this->lowerstoragebygoodid($count,9,$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=9 AND ships_id=".$this->cid);
				}
			}
			if ($cost[10] > 0)
			{
				if ($this->getcountbygoodid(10,$this->cid) >= $cost[10]) 
				{
					$this->lowerstoragebygoodid($cost[10],10,$this->cid);
				}
				else
				{
					$count = $cost[10] - $this->getcountbygoodid(10,$this->cid);
					$this->lowerstoragebygoodid($count,10,$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=10 AND ships_id=".$this->cid);
				}
			}
			if ($cost[12] > 0)
			{
				if ($this->getcountbygoodid(12,$this->cid) >= $cost[12]) 
				{
					$this->lowerstoragebygoodid($cost[12],12,$this->cid);
				}
				else
				{
					$count = $cost[12] - $this->getcountbygoodid(12,$this->cid);
					$this->lowerstoragebygoodid($count,12,$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=12 AND ships_id=".$this->cid);
				}
			}
			if ($cost[14] > 0)
			{
				if ($this->getcountbygoodid(14,$this->cid) >= $cost[14]) 
				{
					$this->lowerstoragebygoodid($cost[14],14,$this->cid);
				}
				else
				{
					$count = $cost[14] - $this->getcountbygoodid(14,$this->cid);
					$this->lowerstoragebygoodid($count,14,$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=14 AND ships_id=".$this->cid);
				}
			}
			if ($cost[15] > 0)
			{
				if ($this->getcountbygoodid(15,$this->cid) >= $cost[15]) 
				{
					$this->lowerstoragebygoodid($cost[15],15,$this->cid);
				}
				else
				{
					$count = $cost[15] - $this->getcountbygoodid(15,$this->cid);
					$this->lowerstoragebygoodid($count,15,$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=15 AND ships_id=".$this->cid);
				}
			}
			if ($cost[19] > 0)
			{
				if ($this->getcountbygoodid(19,$this->cid) >= $cost[19]) 
				{
					$this->lowerstoragebygoodid($cost[19],19,$this->cid);
				}
				else
				{
					$count = $cost[19] - $this->getcountbygoodid(19,$this->cid);
					$this->lowerstoragebygoodid($count,19,$targetId);
					$this->db->query("DELETE FROM stu_ships_storage WHERE goods_id=19 AND ships_id=".$this->cid);
				}
			}
		}
		$this->db->query("UPDATE stu_ships SET huelle=".$targetdata[maxhuell].$wka." WHERE id='".$targetId."'");
		$return[msg] = "Das Schiff wurde repariert";
		return $return;
	}

	function loadstationbatt($id,$count)
	{
		$shipdata = $this->db->query("SELECT a.name,a.user_id,a.coords_x,a.coords_y,a.wese,a.batt,b.max_batt,b.slots FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.id=".$id,4);
		if ($shipdata == 0) return 0;
		if ($this->ccoords_x != $shipdata[coords_x] || $this->ccoords_y != $shipdata[coords_y] || $this->cwese != $shipdata[wese]) return 0;
		if ($this->ccrew < $this->cclass[crew_min])
		{
			$return[msg] = "Es werden ".$this->cclass[crew_min]." Crewmitglieder benötigt";
			return $return;
		}
		if ($shipdata[batt] == $shipdata[max_batt])
		{
			$return[msg] = "Die Reservebatterie der ".$shipdata[name]." ist bereits vollständig aufgeladen";
			return $return;
		}
		if ($shipdata[slots] == 0)
		{
			$return[msg] = "Nur Stationen können mit Reparaturschiffen aufgeladen werden";
			return $return;
		}
		if ($count == "max") $count = floor($this->cenergie / 3);
		if ($this->cenergie < ($count * 3)) $count = floor($this->cenergie / 3);
		if ($shipdata[batt] + $count > $shipdata[max_batt]) $count = $shipdata[max_batt] - $shipdata[batt];
		$this->db->query("UPDATE stu_ships SET energie=energie-".($count*3)." WHERE id=".$this->cid);
		$this->db->query("UPDATE stu_ships SET batt=batt+".$count." WHERE id='".$id."'");
		if ($this->user != $shipdata[user_id])
		{
			global $myComm;
			$myComm->sendpm($shipdata[user_id],$this->user,"Die ".$this->cname." hat die Reserverbatterien der ".$shipdata[name]." um ".$count." Energie aufgeladen",3);
		}
		$return[msg] = "Reserverbatterien der ".$shipdata[name]." um ".$count." Energie aufgeladen";
		return $return;
	}

	function getshipstorage($shipId) { return $this->db->query("SELECT a.goods_id,a.count,b.name,b.secretimage FROM stu_ships_storage as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.ships_id=".$shipId." ORDER BY b.sort"); }

	function dabo($val)
	{
		if ($this->db->query("SELECT id FROM stu_ships WHERE id=".$this->cdock." AND (ships_rumps_id=87 OR ships_rumps_id=100 AND user_id=14)",1) == 0)
		{
			$return[msg] = "Das Schiff muss an einem Ferengiposten angedockt sein";
			return $return;
		}
		if ($this->getcountbygoodid(24,$this->cid) < 2)
		{
			$return[msg] = "Nicht genügend Latinum vorhanden";
			return $return;
		}
		if ($val < 1 || $val > 40)
		{
			$return[msg] = "Die Zahl liegt nicht zwischen 1 und 40";
			return $return;
		}
		$this->lowerstoragebygoodid(2,24,$this->cid);
		$rm = "Das Dabo-Rad wird gedreht<br>";
		global $myTrade;
		while(TRUE)
		{
			$rand = rand(1,40);
			if ($rand == $lr) continue;
			if ($rand == $val && $r != 2) continue;
			else
			{
				$rm .= "...".($r == 2 ? "<b>".$rand."</b>" : $rand);
				$r++;
			}
			$lr = $rand;
			if ($r == 3)
			{
				if ($rand == $val)
				{
					$j = $this->db->query("SELECT value FROM stu_game WHERE fielddescr='dabo_jack'",1);
					$rm .= " DABO!<br>".$j." Latinum gewonnen";
					$this->upperstoragebygoodid($j,24,$this->cid,$this->user);
					$this->db->query("UPDATE stu_game SET value=10 WHERE fielddescr='dabo_jack'");
					global $myHistory,$myUser;
					$myHistory->addEvent("Der Siedler ".addslashes($myUser->uuser)." hat den Dabo-Jackpot geknackt und ".$j." Latinum gewonnen",$this->user);
				}
				else
				{
					$myTrade->upperstoragebygoodid(1,24,14);
					$this->db->query("UPDATE stu_game SET value=value+1 WHERE fielddescr='dabo_jack'");
					$rm .= "<br>Leider nicht gewonnen";
				}
				break;
			}
		}
		$return[msg] = $rm;
		return $return;
	}
}
?>
