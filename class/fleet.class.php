<?php
class fleet
{
	function fleet()
	{
		global $myDB,$myShip,$user;
		$this->myship = $myShip;
		$this->db = $myDB;
		$this->user = $user;
	}
	
	function newfleet($shipId,$name)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		if ($data[user_id] != $this->user)
		{
			$return[msg] = "Das ist nicht Dein Schiff";
			return $return;
		}
		if ($data[c][probe] == 1)
		{
			$return[msg] = "Eine Sonde kann nicht zu einem Flaggschiff ernannt werden";
			return $return;
		}
		if ($data[c][slots] > 0)
		{
			$return[msg] = "Es kann keine Station als Flaggschiff ausgewählt werden";
			return $return;
		}
		$fcount = $this->getfleetcount();
		if ($fcount == 5)
		{
			$return[msg] = "Es sind maximal 5 Flotten erlaubt";
			return $return;
		}
		if ($data[fleets_id] != 0)
		{
			$return[msg] = "Dieses Schiff befindet sich bereits in einer Flotte";
			return $return;
		}
		$id = $this->db->query("INSERT INTO stu_fleets (ships_id,user_id,name) VALUES ('".$shipId."','".$this->user."','Flotte')",5);
		$this->db->query("UPDATE stu_ships SET fleets_id='".$id."' WHERE Id='".$shipId."'");
		$return[msg] = "Die ".$data[name]." wurde zu einem Flaggschiff ernannt";
		return $return;
	}
	
	function getfleetcount() { return $this->db->query("SELECT COUNT(id) FROM stu_fleets WHERE user_id=".$this->user,1); }
	
	function getfleetshipsinfo($fleetId,$shipId) { return $this->db->query("SELECT a.id,a.ships_rumps_id,a.name,a.energie,a.huelle,a.schilde,a.schilde_aktiv,a.batt,b.name as rname FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.fleets_id=".$fleetId." AND a.id!=".$shipId); }
	
	function getfleetbyshipid($shipId) { return $this->db->query("SELECT * FROM stu_fleets WHERE ships_id='".$shipId."'",4); }
	
	function getfleetsbyuserid($userId) { return $this->db->query("SELECT * FROM stu_fleets WHERE user_id='".$userId."'",2); }
	
	function getfleetbyid($fleetId) { return $this->db->query("SELECT * FROM stu_fleets WHERE id='".$fleetId."'",4); }
	
	function delfleet($shipId)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		if ($data[fleets_id] == 0)
		{
			$return[msg] = "Das Schiff befindet sich in keiner Flotte";
			return $return;
		}
		if ($data[user_id] != $this->user)
		{
			$return[msg] = "Das ist nicht Dein Schiff";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet[id] != $data[fleets_id])
		{
			$return[msg] = "Dieses Schiff ist nicht das Flaggschiff der Flotte";
			return $return;
		}
		$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE fleets_id='".$fleet[id]."' AND user_id=".$this->user);
		$this->db->query("DELETE FROM stu_fleets WHERE id=".$fleet[id]." AND user_id=".$this->user);
		$return[msg] = "Die Flotte wurde aufgelöst";
		return $return;
	}
	
	function getfleetshipcount($fleetId) { return $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE fleets_id='".$fleetId."'",1); }
	
	function joinfleet($shipId,$fleetId)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		if ($data[c][probe] == 1)
		{
			$return[msg] = "Eine Sonde kann nicht zu einem Flaggschiff ernannt werden";
			return $return;
		}
		if ($data[user_id] != $this->user)
		{
			$return[msg] = "Das ist nicht Dein Schiff";
			return $return;
		}
		if ($data[fleets_id] > 0)
		{
			$return[msg] = "Das Schiff befindet sich bereits in einer Flotte";
			return $return;
		}
		if ($data[dock] > 0)
		{
			$return[msg] = "Das Schiff ist angedockt";
			return $return;
		}
		$fleet = $this->getfleetbyid($fleetId);
		if (round($this->db->query("SELECT SUM(points) FROM stu_ships WHERE fleets_id=".$fleet[id],1),2) + $data[points] > 150)
		{
			$return[msg] = "Die Gesamtzahl an WP einer Flotte darf maximal bei 150 liegen";
			return $return;
		}
		$shipcount = $this->getfleetshipcount($fleetId);
		if ($shipcount == 20)
		{
			$return[msg] = "Es können maximal 20 Schiffe in einer Flotte sein";
			return $return;
		}
		$flagship = $this->myship->getdatabyid($fleet[ships_id]);
		if ($flagship[coords_x] != $data[coords_x] || $flagship[coords_y] != $data[coords_y] || $flagship[wese] != $data[wese])
		{
			$return[msg] = "Das Schiff muss sich im selben Sektor wie das Flaggschiff (".$flagship[name].") befinden";
			return $return;
		}
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$this->db->query("UPDATE stu_ships SET fleets_id='".$fleetId."' WHERE id='".$shipId."' ANd user_id=".$this->user);
		$return[msg] = "Die ".$data[name]." hat sich der Flotte angeschlossen";
		return $return;
	}
	
	function leavefleet($shipId,$userId)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		if ($data[user_id] != $this->user)
		{
			$return[msg] = "Das ist nicht Dein Schiff";
			return $return;
		}
		if ($data[fleets_id] == 0)
		{
			$return[msg] = "Das Schiff befindet sich in keiner Flotte";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet[ships_id] == $shipId)
		{
			$return[msg] = "Dieses Schiff ist das Flaggschiff einer Flotte";
			return $return;
		}
		$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE id='".$shipId."' AND user_id=".$this->user);
		$return[msg] = "Die ".$data[name]." ist aus der Flotte ausgetreten";
		return $return;
	}
	
	function phaser($shipId,$target)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0) return 0;
		if ($data[cloak] == 1)
		{
			$return[msg] = "Das Flaggschiff hat die Tarnung aktiviert";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$tardata = $this->myship->getdatabyid($target);
		if ($tardata == 0)
		{
			$return[msg] = "Zielobjekt nicht vorhanden";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		$msg = "<strong>Die Flotte formiert sich zum Angriff</strong><br>";
		for ($i=0;$i<count($ships);$i++)
		{
			if ($ships[$i][energie] == 0) continue;
			if ($tardata[fleets_id] == 0)
			{
				$result = $this->myship->phaser($ships[$i][id],$tardata[id],$this->user,0,1,5,0);
				if ($result[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
			}
			else
			{
				$qd = $this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$tardata[fleets_id]." ORDER BY RAND() LIMIT 1",1);
				if ($qd == 0) break;
				$result = $this->myship->phaser($ships[$i][id],$qd,$this->user,0,1,5,0);
			}
			$this->myship->pmmsg="";
		}
		if ($destroy == 1 || $tardata[c][trumfield] == 1)
		{
			$return[msg] = "<table bgcolor=#262323><tr><td class=tdmain><strong>Kampflog</strong></td></tr>
				<tr><td class=tdmainobg>".$this->myship->returnmsg."</td></tr></table>";
			return $return;
		}
		$msg .= $this->myship->returnmsg."<br><strong>Gegenangriff</strong><br>";
		$this->myship->returnmsg="";
		$this->myship->pmmsg="";
		if ($tardata[fleets_id] > 0)
		{
			$result = $this->db->query("SELECT id,user_id FROM stu_ships WHERE fleets_id=".$tardata[fleets_id]." AND energie>0 AND crew>0 AND alertlevel>1");
			for ($i=0;$i<@mysql_num_rows($result);$i++)
			{
				$tmpshipdat = mysql_fetch_assoc($result);
				$tmpdat = $this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$fleet[id]." AND cloak=0 ORDER BY RAND() LIMIT 1",1);
				$res = $this->myship->strikeback($tmpdat,$tmpshipdat[id],$tmpshipdat[user_id],1,1,5);
				$this->myship->pmmsg="";
				if ($res[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
			}
		}
		else
		{
			$tmpdat = $this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$fleet[id]." ORDER BY RAND() LIMIT 1",1);
			$this->myship->strikeback($tmpdat,$tardata[id],$tardata[user_id],1,1,5);
		}
		$msg.=$this->myship->returnmsg;
		$this->myship->returnmsg="";
		$this->myship->pmmsg="";
		$df = $this->db->query("SELECT ships_id FROM stu_ships_action WHERE mode='defend' AND ships_id2=".$tardata[id],2);
		if ($df != 0)
		{
			$msg.= "<br><strong>Verteidigung</strong><br>";
			for ($i=0;$i<count($df);$i++)
			{
				$tmp = $this->db->query("SELECT id,user_id FROM stu_ships WHERE (fleets_id=0 OR fleets_id!=".$tardata[fleets_id].") AND id=".$df[$i][ships_id],4);
				$ts = $this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$fleet[id]." ORDER BY RAND() LIMIT 1",1);
				$this->myship->strikeback($ts,$tmp[id],$tmp[user_id],1,1,5);
				$this->myship->pmmsg="";
			}
		}
		$msg.=$this->myship->returnmsg;
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmain><strong>Kampflog</strong></td></tr>
						<tr><td class=tdmainobg>".$msg."</td></tr></table>";
		return $return;
	}
	
	function getshipsbyfleetid($fleetId) { return $this->db->query("SELECT * FROM stu_ships WHERE fleets_id='".$fleetId."'",2); }
	
	function torp($shipId,$target)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0) return 0;
		if ($data[cloak] == 1)
		{
			$return[msg] = "Das Flaggschiff hat die Tarnung aktiviert";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$tardata = $this->myship->getdatabyid($target);
		if ($tardata == 0)
		{
			$return[msg] = "Zielobjekt nicht vorhanden";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		$msg = "<strong>Die Flotte formiert sich zum Angriff</strong><br>";
		for ($i=0;$i<count($ships);$i++)
		{
			if ($ships[$i][energie] == 0) continue;
			$type = $this->myship->gettorptype($ships[$i][id]);
			if ($type == 0) continue;
			if ($tardata[fleets_id] == 0)
			{
				$result = $this->myship->torp($ships[$i][id],$tardata[id],$this->user,$type,0,5);
				if ($result[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
			}
			else $result = $this->myship->torp($ships[$i][id],$this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$tardata[fleets_id]." ORDER BY RAND() LIMIT 1",1),$this->user,$type,0,5);
			$this->myship->pmmsg="";
		}
		if ($destroy == 1 || $tardata[c][trumfield] == 1)
		{
			$return[msg] = "<table bgcolor=#262323><tr><td class=tdmain><strong>Kampflog</strong></td></tr>
				<tr><td class=tdmainobg>".$this->myship->returnmsg."</td></tr></table>";
			return $return;
		}
		$msg .= $this->myship->returnmsg;
		$this->myship->returnmsg="";
		$msg .= "<br><strong>Gegenangriff</strong><br>";
		if ($tardata[fleets_id] > 0)
		{
			$result = $this->db->query("SELECT id,user_id FROM stu_ships WHERE fleets_id=".$tardata[fleets_id]." AND crew>0 AND energie>0 AND alertlevel>1");
			for ($i=0;$i<mysql_num_rows($result);$i++)
			{
				$tmpshipdat = mysql_fetch_assoc($result);
				$res = $this->myship->strikeback($this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$fleet[id]." AND cloak=0 ORDER BY RAND() LIMIT 1",1),$tmpshipdat[id],$tmpshipdat[user_id],1,0,5);
				$this->myship->pmmsg="";
				if ($res[destroy] == 1)
				{
					$destroy = 1;
					break;
				}
			}
		}
		else
		{
			$res = $this->myship->strikeback($this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$fleet[id]." ORDER BY RAND() LIMIT 1",1),$tardata[id],$tardata[user_id],1,0,5);
			$this->myship->pmmsg="";
		}
		$msg .= $this->myship->returnmsg;
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmain><strong>Kampflog</strong></td></tr>
						<tr><td class=tdmainobg>".$msg."</td></tr></table>";
		return $return;
	}
	
	function move($shipId,$x,$y)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		if ($data[energie] == 0)
		{
			$return[msg] = "Keine Energie auf dem Flaggschiff vorhanden";
			return $return;
		}
		if ($data[dock] > 0)
		{
			$return[msg] = "Das Flaggschiff ist angedockt";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($this->user != 17 && $data[c][probe] == 0)
		{
			if ($data[c][crew_min] - 2 > $data[crew])
			{
				$return[msg] = "Zum fliegen werden mindestens ".($data[c][crew_min] - 2)." Crewmitglieder benötigt";
				return $return;
			}
			if ($data[crew] < 2)
			{
				$return[msg] = "Zum fliegen werden mindestens 2 Crewmitglieder benötigt";
				return $return;
			}
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$res = $this->db->query("SELECT id,energie,name FROM stu_ships WHERE fleets_id=".$fleet[id]);
		while($ship=mysql_fetch_assoc($res))
		{
			if ($ship[energie] == 0)
			{
				$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE id='".$ship[id]."' ANd user_id=".$this->user);
				$msg .= "Die ".stripslashes($ship[name])." hat keine Energie für den Flug und löst sich von der Flotte<br>";
				continue;
			}
			$result = $this->myship->move($ship[id],$x,$y,$this->user,1,0);
			$msg .= strip_tags($result[msg],"<font></font><br><strong></strong>")."<br>";
		}
		$this->myship->fmsg=0;
		if ($this->db->query("SELECT COUNT(id) FROM stu_ships WHERE fleets_id=".$fleet[id]." AND cloak=0",1) != 0) $ra = $this->myship->redalert($x,$y,0,$fleet[id],$data[wese]);
		if ($ra != 0 || $ra != "") $msg .= $ra[msg];
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmain><strong>Fluglog</strong></td></tr>
						<tr><td class=tdmainobg>".$msg."</td></tr></table>";
		return $return;
	}
	
	function autopilot($shipId,$way,$fields)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0) return 0;
		if ($data[energie] == 0)
		{
			$return[msg] = "Keine Energie auf dem Flaggschiff vorhanden";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0) return 0;
		if ($fleet[user_id] != $this->user) return 0;
		if ($data[dock] > 0)
		{
			$return[msg] = "Das Flaggschiff ist angedockt";
			return $return;
		}
		if ($this->user != 17 && $data[c][probe] == 0)
		{
			if ($data[c][crew_min] - 2 > $data[crew])
			{
				$return[msg] = "Zum fliegen werden mindestens ".($data[c][crew_min] - 2)." Crewmitglieder benötigt";
				return $return;
			}
			if ($data[crew] < 2)
			{
				$return[msg] = "Zum fliegen werden mindestens 2 Crewmitglieder benötigt";
				return $return;
			}
		}
		if ($fields > $data[energie]) $fields = $data[energie];
		$i=1;
		while ($i<=$fields)
		{
			if ($this->db->query("SELECT energie FROM stu_ships WHERE id=".$data[id],1) == 0)
			{
				$msg .= "Das Flaggschiff ".stripslashes($ship[name])." hat keine Energie für den Flug. Die Flotte stoppt in diesem Sektor<br>";
				break;
			}
			$res = $this->db->query("SELECT id,energie,name,coords_x,coords_y FROM stu_ships WHERE fleets_id=".$fleet[id]);
			while($ship=mysql_fetch_assoc($res))
			{
				if ($this->db->query("SELECT energie FROM stu_ships WHERE id=".$ship[id],1) == 0)
				{
					$this->db->query("UPDATE stu_ships SET fleets_id=0 WHERE id='".$ship[id]."' ANd user_id=".$this->user);
					$msg .= "Die ".stripslashes($ship[name])." hat keine Energie für den Flug und löst sich von der Flotte<br>";
					continue;
				}
				if ($way == "hoch") { $x=$ship[coords_x];$y=$ship[coords_y]-1; }
				if ($way == "runter") { $x=$ship[coords_x];$y=$ship[coords_y]+1; }
				if ($way == "rechts") { $x=$ship[coords_x]+1;$y=$ship[coords_y]; }
				if ($way == "links") { $x=$ship[coords_x]-1;$y=$ship[coords_y]; }
				if (!$x || !$y) break;
				$result = $this->myship->move($ship[id],$x,$y,$this->user,1,0);
				$msg .= strip_tags($result[msg],"<font></font><b></b>")."<br>";
			}
			$ret = $this->myship->redalert($x,$y,0,$data[fleets_id],$data[wese]);
			if ($ret != 0) $msg .= $ret[msg]."<br>";
			$i++;
		}
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmain><strong>Fluglog</strong></td></tr>
						<tr><td class=tdmainobg>".$msg."</td></tr></table>";
		return $return;
	}
	
	function activatevalue($shipId,$value)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0) return 0;
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0) return 0;
		if ($fleet[user_id] != $this->user) return 0;
		$ships = $this->getshipsbyfleetid($fleet[id]);
		for ($i=0;$i<count($ships);$i++)
		{
			$result = $this->myship->activatevalue($ships[$i][id],$value,$this->user);
			$msg .= $ships[$i][name].": ".$result[msg]."<br>";
		}
		$return[msg] = $msg;
		return $return;
	}
	
	function fdeac($value)
	{
		global $myShip;
		if ($myShip->cshow == 0 || $myShip->cfleets_id == 0 || $this->db->query("SELECT ships_id FROM stu_fleets WHERE id=".$myShip->cfleets_id,1) != $myShip->cid) return 0;
		$this->db->query("UPDATE stu_ships SET ".$value."=0 WHERE fleets_id=".$myShip->cfleets_id);
		if ($value == "lss") $return[msg] = "Alle Schiffe der Flotte haben die Langstreckensensoren deaktiviert";
		if ($value == "kss") $return[msg] = "Alle Schiffe der Flotte haben die Kurzstreckensensoren deaktiviert";
		if ($value == "replikator") $return[msg] = "Alle Schiffe der Flotte haben Replikator deaktiviert";
		if ($value == "schilde_aktiv") $return[msg] = "Alle Schiffe der Flotte haben die Schilde deaktiviert";
		if ($value == "cloak") $return[msg] = "Alle Schiffe der Flotte haben die Tarnung deaktiviert";
		return $return;
	}
	
	function deactivatevalue($shipId,$value)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		for ($i=0;$i<count($ships);$i++)
		{
			$result = $this->myship->deactivatevalue($ships[$i][id],$value,$this->user);
			$msg .= $ships[$i][name].": ".strip_tags("<font></font><b></b>".$result[msg])."<br>";
		}
		$return[msg] = $msg;
		return $return;
	}
	
	function alertlevel($shipId,$level)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		for ($i=0;$i<count($ships);$i++)
		{
			$result = $this->myship->alertlevel($ships[$i][id],$level,$this->user);
			$msg .= $ships[$i][name].": ".strip_tags("<font></font><b></b>".$result[msg])."<br>";
		}
		$return[msg] = $msg;
		return $return;
	}
	
	function bussard($shipId,$count)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		for ($i=0;$i<count($ships);$i++)
		{
			$result = $this->myship->bussard($ships[$i][id],$count,$this->user);
			$msg .= $ships[$i][name].": ".strip_tags("<font></font><b></b>".$result[msg])."<br>";
		}
		$return[msg] = $msg;
		return $return;
	}
	
	function collect($shipId,$count)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		for ($i=0;$i<count($ships);$i++)
		{
			$result = $this->myship->collect($ships[$i][id],$count,$this->user);
			$msg .= $ships[$i][name].": ".strip_tags("<font></font><b></b>".$result[msg])."<br>";
		}
		$return[msg] = $msg;
		return $return;
	}
	
	function ebatt($shipId,$count)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		for ($i=0;$i<count($ships);$i++)
		{
			$result = $this->myship->ebatt($ships[$i][id],$count,$this->user);
			$msg .= $ships[$i][name].": ".strip_tags("<font></font><b></b>".$result[msg])."<br>";
		}
		$return[msg] = $msg;
		return $return;
	}
	
	function shload($shipId,$count)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		for ($i=0;$i<count($ships);$i++)
		{
			$result = $this->myship->shieldemitter($ships[$i][id],$count,$this->user);
			$msg .= $ships[$i][name].": ".strip_tags("<font></font><b></b>".$result[msg])."<br>";
		}
		$return[msg] = $msg;
		return $return;
	}
	
	function decloak($shipId)
	{
		global $myUser;
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		if ($data[cloak] == 1)
		{
			$return[msg] = "Die Tarnung ist aktiviert";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		$result = $this->db->query("SELECT id FROM stu_ships WHERE fleets_id=".$data[fleets_id]." AND coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y]." AND tachyon=1 AND energie>=3 AND cloak=0");
		if (mysql_num_rows($result) < 2)
		{
			$return[msg] = "Die Flotte muss aus mindestens 2 ungetarnten Schiffen bestehen, auf denen mindestens 3 Energie und ein Tachyonemitter vorhanden ist";
			return $return;
		}
		$j=0;
		$chance = mysql_num_rows($result)*3;
		$result2 = $this->db->query("SELECT id FROM stu_ships WHERE coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y]." AND cloak=1 AND user_id!=".$this->user);
		global $myComm;
		for ($i=0;$i<mysql_num_rows($result2);$i++)
		{
			$shipd = mysql_fetch_assoc($result2);
			$shipdat = $this->myship->getDataById($shipd[id]);
			$chance2 = $chance * round(sqrt($shipdat[maxhuell]/$shipdat[huelle]),1);
			if (rand(1,100) <= $chance2)
			{
				if ($this->db->query("SELECT ships_id FROM stu_ships_uncloaked WHERE ships_id=".$shipdat[id],1) > 0) continue;
				$this->db->query("INSERT INTO stu_ships_uncloaked (user_id,ships_id) VALUES ('".$this->user."','".$shipdat[id]."')");
				$myComm->sendpm($shipdat[user_id],2,"Die ".$shipdat[name]." wurde in Sektor ".$shipdat[coords_x]."/".$shipdat[coords_y]." durch den User ".$myUser->getfield("user",$this->user)." durch einen Tachyonenscan enttarnt",2);
				$j++;
			}
		}
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$shipdat = mysql_fetch_assoc($result);
			$this->db->query("UPDATE stu_ships SET energie=energie-3 WHERE id=".$shipdat[id]);
		}
		$return[msg] = "Durch den Tachyonenscan wurden ".$j." Schiffe entdeckt";
		return $return;
	}
	
	function wormhole($shipId)
	{
		global $myUser,$myShip;
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0)
		{
			$return[msg] = "Flotte nicht vorhanden";
			return $return;
		}
		if ($fleet[user_id] != $this->user)
		{
			$return[msg] = "Dies ist nicht Deine Flotte";
			return $return;
		}
		if ($data[c][crew_min] - 1 > $data[crew])
		{
			$return[msg] = "Es werden ".($data[c][crew_min] - 1)." Crewmitglieder auf dem Flaggschiff benötigt um in das Wurmloch zu fliegen";
			return $return;
		}
		if ($this->db->query("SELECT COUNT(id) FROM stu_ships WHERE fleets_id=".$data[fleets_id]." AND coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y]." AND energie<13",1) > 0)
		{
			$return[msg] = "Jedes schiff der Flotte muss mindestens 13 Energie";
			return $return;
		}
		$ships = $this->getshipsbyfleetid($fleet[id]);
		for ($i=0;$i<count($ships);$i++)
		{
			$result = $this->myship->wormhole($ships[$i][id],$ships[$i][user_id],$fleet[id]);
			$msg .= $result[msg]."<br>";
		}
		$nd = $this->db->query("SELECT coords_x,coords_y,wese FROM stu_ships WHERE id=".$shipId,4);
		$ra = $this->myship->redalert($nd[coords_x],$nd[coords_y],0,$fleet[id],$nd[wese]);
		if ($ra != 0 || $ra != "") $msg .= "<br>".$ra[msg];
		$return[msg] = $msg;
		return $return;
	}
	
	function generatejlist($x,$y,$wese)
	{
		global $myUser;
		$result = $this->db->query("SELECT a.id,a.name FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.user_id=".$this->user." AND a.wese=".$wese." AND a.coords_x=".$x." AND a.coords_y=".$y." AND b.slots=0 AND a.fleets_id=0 ORDER BY ".$myUser->getslsorting("a."));
		while($tmp=mysql_fetch_assoc($result)) $string .= "<option value=".$tmp[id].">".strip_tags(stripslashes($tmp[name]))."</option>";
		return $string;
	}
	
	function changename($name,$fleetId)
	{
		global $myGame;
		$name = $myGame->completetags($name);
		$fleet = $this->getfleetbyid($fleetId);
		if ($fleet[name] == $name) return 0;
		if ($fleet == 0 || $fleet[user_id] != $this->user) return 0;
		$this->db->query("UPDATE stu_fleets SET name='".addslashes(str_replace("\"","'",strip_tags($name,"<font></font>")))."' WHERE id=".$fleetId);
		$return[msg] = "Die Flotte wurde in ".stripslashes(strip_tags(str_replace("\"","'",$name),"<font></font>"))." umbenannt";
		return $return;
	}
	
	function chgflag($shipId,$shipId2)
	{
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0) return 0;
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0) return 0;
		if ($fleet[user_id] != $this->user) return 0;
		if ($fleet[ships_id] != $shipId) return 0;
		$data2 = $this->myship->getdatabyid($shipId2);
		if ($data2 == 0) return 0;
		if ($data2[user_id] != $this->user) return 0;
		if ($data2[c][slots] > 0)
		{
			$return[msg] = "Die ".$data2[name]." kann nicht zum Flaggschiff ernannt werden";
			return $return;
		}
		if ($data2[crew] < 2)
		{
			$return[msg] = "Um die ".$data2[name]." zum Flaggschiff zu ernennen müssen mindestens 2 Crewmitglieder an Board sein";
			return $return;
		}
		$this->db->query("UPDATE stu_fleets SET ships_id=".$shipId2." WHERE id=".$fleet[id]);
		$return[msg] = "Die ".$data2[name]." wurde zum neuen Flaggschiff ernannt";
		return $return;
	}
	
	function fmphaser($shipId)
	{
		$data = $this->db->query("SELECT id,fleets_id FROM stu_ships WHERE id=".$shipId,4);
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0) return 0;
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0) return 0;
		if ($fleet[user_id] != $this->user) return 0;
		if ($fleet[ships_id] != $shipId) return 0;
		$this->db->query("UPDATE stu_ships SET strb_mode=1 WHERE fleets_id=".$fleet[id]);
		$return[msg] = "Der Feuermodus aller Schiffe der Flotte ".$fleet[name]." wurde auf Phaser gesetzt";
		return $return;
	}
	
	function fmtorp($shipId)
	{
		$data = $this->db->query("SELECT id,fleets_id FROM stu_ships WHERE id=".$shipId,4);
		$data = $this->myship->getdatabyid($shipId);
		if ($data == 0) return 0;
		$fleet = $this->getfleetbyid($data[fleets_id]);
		if ($fleet == 0) return 0;
		if ($fleet[user_id] != $this->user) return 0;
		if ($fleet[ships_id] != $shipId) return 0;
		$this->db->query("UPDATE stu_ships SET strb_mode=2 WHERE fleets_id=".$fleet[id]);
		$return[msg] = "Der Feuermodus aller Schiffe der Flotte ".$fleet[name]." wurde auf Torpedo gesetzt";
		return $return;
	}
}
?>
