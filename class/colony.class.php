<?php
class colony
{
	function colony()
	{
		global $myDB,$user;
		$this->db = $myDB;
		$this->user = $user;
	}
	
	function gcc()
	{
		global $id,$user;
		if ($id > 0)
		{
			$data = $this->db->query("SELECT id,name,energie,bev_used,bev_free,max_bev,max_energie,colonies_classes_id,coords_y,coords_x,max_storage,schild_freq1,schild_freq2,max_schilde,schilde,schilde_aktiv,gravi,dn_mode,dn_nextchange,dn_duration,weather,wirtschaft,temp,ewopt,bev_stop_count,sperrung,mkolz,wese FROM stu_colonies WHERE id=".$id." AND user_id=".$user,4);
			if ($data == 0) return 0;
			$this->cshow = 1;
			$this->cid = $data[id];
			$this->cname = stripslashes($data[name]);
			$this->cenergie = $data[energie];
			$this->cbev_used = $data[bev_used];
			$this->cbev_free = $data[bev_free];
			$this->cmax_bev = $data[max_bev];
			$this->cmax_energie = $data[max_energie];
			$this->ccolonies_classes_id = $data[colonies_classes_id];
			$this->ccoords_x = $data[coords_x];
			$this->ccoords_y = $data[coords_y];
			$this->cmax_storage = $data[max_storage];
			$this->cschilde = $data[schilde];
			$this->cmax_schilde = $data[max_schilde];
			$this->cschilde_aktiv = $data[schilde_aktiv];
			$this->cschild_freq1 = $data[schild_freq1];
			$this->cschild_freq2 = $data[schild_freq2];
			$this->cgravi = $data[gravi];
			$this->cdn_mode = $data[dn_mode];
			$this->cdn_nextchange = $data[dn_nextchange];
			$this->cdn_duration = $data[dn_duration];
			$this->cweather = $data[weather];
			$this->cwirtschaft = $data[wirtschaft];
			$this->ctemp = $data[temp];
			$this->cewopt = $data[ewopt];
			$this->cbev_stop_count = $data[bev_stop_count];
			$this->csperrung = $data[sperrung];
			$this->cmkolz = $data[mkolz];
			$this->cwese = $data[wese];
		}
		else $this->cshow = 0;
	}
	
	function getcolonylist() { return $this->db->query("SELECT id,colonies_classes_id,name from stu_colonies WHERE user_id='".$this->user."' ORDER BY colonies_classes_id ASC"); }
	
	function getnrebyid($colId)
	{
		$gtc = @($this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=80 AND aktiv=1 AND colonies_id=".$colId,1)*$this->getgravenergy($colId));
		$e = $this->db->query("SELECT (SUM(a.eps_pro) - SUM(a.eps_min)) as epro FROM stu_buildings as a LEFT JOIN stu_colonies_fields as b ON a.id=b.buildings_id WHERE b.aktiv=1 AND b.buildings_id!=80 AND b.colonies_id=".$colId,1);
		$e += $this->db->query("SELECT (SUM(a.eps_pro) - SUM(a.eps_min)) as epro FROM stu_buildings as a LEFT JOIN stu_colonies_orbit as b ON a.id=b.buildings_id WHERE b.aktiv=1 AND b.buildings_id!=80 AND b.colonies_id=".$colId,1);
		$e += $this->db->query("SELECT (SUM(a.eps_pro) - SUM(a.eps_min)) as epro FROM stu_buildings as a LEFT JOIN stu_colonies_underground as b ON a.id=b.buildings_id WHERE b.aktiv=1 AND b.buildings_id!=80 AND b.colonies_id=".$colId,1);
		return ($e+$gtc);
	}
	
	function getnrwbyid($colId)
	{
		$w = $this->db->query("SELECT SUM(a.points) FROM stu_buildings as a LEFT JOIN stu_colonies_fields as b ON a.id=b.buildings_id WHERE b.colonies_id=".$colId." AND ((b.aktiv=1) OR (b.buildings_id=4) OR (b.buildings_id=121) OR (b.buildings_id=128) OR ((b.buildings_id>106) AND (b.buildings_id<135)) OR (b.buildings_id=157) OR (b.buildings_id=136))",1);
		$w += $this->db->query("SELECT SUM(a.points) FROM stu_buildings as a LEFT JOIN stu_colonies_orbit as b ON a.id=b.buildings_id WHERE b.colonies_id=".$colId." AND ((b.aktiv=1) OR (b.buildings_id=26) OR ((b.buildings_id<=30) AND (b.buildings_id>=26)) OR (b.buildings_id=135))",1);
		$w += $this->db->query("SELECT SUM(a.points) FROM stu_buildings as a LEFT JOIN stu_colonies_underground as b ON a.id=b.buildings_id WHERE b.colonies_id=".$colId." AND b.aktiv=1",1);
		return $w;
	}

	function getnrsbyid($colId) 
	{ 
		return $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=10 AND colonies_id=".$colId,1) + ($this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=206 AND colonies_id=".$colId,1)) * 3;
	}

	function getwksnbyid($colId)
	{
		if ($this->db->query("SELECT id FROM stu_colonies_orbit WHERE aktiv=1 AND buildings_id=47 AND colonies_id=".$colId."",3) == 0) return 0;
		return $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE colonies_id=".$colId." AND (buildings_id=2 OR buildings_id=8) AND aktiv=1",1);
	}

	function getregnbyid($colId)
	{
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE aktiv=1 AND buildings_id=175 AND colonies_id=".$colId."",3) == 0) return 0;
		$nahrung = 2 * $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildings_id=20 AND aktiv=1",1);
		$nahrung += 2 * $this->db->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE colonies_id=".$colId." AND buildings_id=20 AND aktiv=1",1);
		$nahrung += $this->db->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE colonies_id=".$colId." AND buildings_id=60 AND aktiv=1",1);
		$nahrung += $this->db->query("SELECT COUNT(id) FROM stu_colonies_orbit WHERE colonies_id=".$colId." AND buildings_id=44 AND aktiv=1",1);
		return $nahrung;
	}

	function getfnrpgbyid($colId) { return $this->db->query("SELECT SUM(a.count) as gs,goods_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_fields as b ON a.buildings_id=b.buildings_id WHERE a.mode=1 AND b.colonies_id=".$colId." AND b.aktiv=1 GROUP BY a.goods_id"); }
	
	function getfnrvgbyid($colId) { return $this->db->query("SELECT SUM(a.count) as gs,goods_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_fields as b ON a.buildings_id=b.buildings_id WHERE a.mode=2 AND b.colonies_id=".$colId." AND b.aktiv=1 GROUP BY a.goods_id"); }
	
	function getonrpgbyid($colId) { return $this->db->query("SELECT SUM(a.count) as gs,goods_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_orbit as b ON a.buildings_id=b.buildings_id WHERE a.mode=1 AND b.colonies_id=".$colId." AND b.aktiv=1 GROUP BY a.goods_id"); }
	
	function getonrvgbyid($colId) { return $this->db->query("SELECT SUM(a.count) as gs,goods_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_orbit as b ON a.buildings_id=b.buildings_id WHERE a.mode=2 AND b.colonies_id=".$colId." AND b.aktiv=1 GROUP BY a.goods_id"); }
	
	function getunrpgbyid($colId) { return $this->db->query("SELECT SUM(a.count) as gs,goods_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_underground as b ON a.buildings_id=b.buildings_id WHERE a.mode=1 AND b.colonies_id=".$colId." AND b.aktiv=1 GROUP BY a.goods_id"); }
	
	function getunrvgbyid($colId) { return $this->db->query("SELECT SUM(a.count) as gs,goods_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_underground as b ON a.buildings_id=b.buildings_id WHERE a.mode=2 AND b.colonies_id=".$colId." AND b.aktiv=1 GROUP BY a.goods_id"); }
	
	function getclassbyid($classId) { return $this->db->query("SELECT * FROM stu_colonies_classes WHERE id='".$classId."'",4); }
	
	function getbuildings() { return $this->db->query("SELECT a.*,b.type FROM stu_buildings as a LEFT JOIN stu_field_build as b ON a.id=b.buildings_id WHERE a.view=1 GROUP BY a.id ORDER by a.level,a.name ASC"); }
	
	function getnpcbuildings()
	{
		$result = $this->db->query("SELECT * FROM stu_buildings WHERE view=0 ORDER by level,name ASC");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$data[$i] = mysql_fetch_assoc($result);
			$data[$i][field] = $this->db->query("SELECT type FROM stu_field_build WHERE buildings_id='".$data[$i][id]."' LIMIT 1",1);
			$data[$i][cost] = $this->getbuildingcostbyid($data[$i][id]);
		}
		return $data;
	}
	
	function getfieldsbybuilding($buildingsId) { return $this->db->query("SELECT type FROM stu_field_build WHERE buildings_id=".$buildingsId." ORDER BY type ASC"); }
	
	function getStorageById($colId) { return $this->db->query("SELECT a.goods_id,a.count,b.name FROM stu_colonies_storage as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.colonies_id=".$colId." ORDER BY sort ASC",2); }
	
	function getcolbyfield($coords_x,$coords_y,$wese)
	{
		if ($this->db->query("SELECT id FROM stu_colonies WHERE user_id=".$this->user,3) == 5) return -1;
		$data = $this->db->query("SELECT id,colonies_classes_id FROM stu_colonies WHERE coords_x='".$coords_x."' AND coords_y='".$coords_y."' AND wese=".$wese." AND user_id='2'",4);
		if ($data == 0) return -1;
		if (($data[colonies_classes_id] == 1) && ($this->db->query("SELECT id FROM stu_colonies WHERE user_id=".$this->user." AND colonies_classes_id=1",3) < 1)) return $data;
		if ($data[colonies_classes_id] == 1) return -1;
		return $data;
	}
	
	function getcolfields($colId) { return $this->db->query("SELECT field_id,buildings_id,buildtime,aktiv,type,name FROM stu_colonies_fields WHERE colonies_id='".$colId."' ORDER BY field_id ASC",2); }
	
	function getcolspaltzahl($colId) { return $this->db->query("SELECT id from stu_colonies_fields WHERE colonies_id='".$colId."' AND type=26 ORDER BY id ASC",3); }
	
	function getcolfieldsbybuilding($colId) { return $this->db->query("SELECT a.field_id,a.aktiv,b.name FROM stu_colonies_fields as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.colonies_id=".$colId." AND buildings_id>0 ORDER BY b.name,a.field_id ASC"); }
	
	function getcolonybyid($colId) { return $this->db->query("SELECT * FROM stu_colonies WHERE id=".$colId,4); }
	
	function getgravenergy($colId)
	{
		$grav = $this->db->query("SELECT gravi FROM stu_colonies WHERE id=".$colId,1);
		if ($grav > 1.4) return (7 + round(($grav - 1)/3*4));
		return (7 + round(($grav - 1)*8));
	}
	
	function getbuildbyid($buildId) { return $this->db->query("SELECT * FROM stu_buildings WHERE id='".$buildId."'",4); }
	
	function getpossiblebuildings($type)
	{
		global $myUser;
		$this->user < 100 ? $result = $this->db->query("SELECT a.buildings_id,a.type,b.id,b.research_id,b.name FROM stu_field_build as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.type='".$type."' AND a.buildings_id!=1 AND a.buildings_id!=23 AND a.buildings_id!=89 AND (a.buildings_id<63 OR a.buildings_id>66) ORDER by b.name") : $result = $this->db->query("SELECT a.buildings_id,a.type,b.id,b.research_id,b.name FROM stu_field_build as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.type='".$type."' AND a.buildings_id!=1 AND a.buildings_id!=23 AND a.buildings_id!=89 AND (a.buildings_id<63 OR a.buildings_id>66) AND b.view=1 AND b.level<=".$myUser->ulevel." ORDER by b.name");
		while($data=mysql_fetch_assoc($result))
		{
			if ($data[id] == 210)
			{
				if ($this->db->query("SELECT a.id FROM stu_allys as a LEFT JOIN stu_user as b ON b.allys_id = a.id LEFT JOIN stu_allys_embassys as c ON a.id = c.allys_id1 WHERE ( a.user_id = ".$this->user." OR a.vize = ".$this->user." OR a.diplo = ".$this->user." ) AND c.colonies_id = 0 GROUP by a.id.") != 0)
				{
					$return[] = $data;
				}
			}
			else
			{
				if ($data[research_id] > 0) if ($this->db->query("SELECT COUNT(user_id) FROM stu_research_user WHERE research_id=".$data[research_id]." AND user_id=".$this->user,1) == 1) $return[] = $data;
				if ($data[research_id] == 0) $return[] = $data;
			}
		}
		return $return;
	}
	
	function getfieldbyid($fieldId,$colId)
	{
		$field[data] = $this->getfielddatabyid($fieldId,$colId);
		$field[build] = $this->getbuildbyid($field[data][buildings_id]);
		return $field;
	}
	
	function getfielddatabyid($fieldId,$colId) { return $this->db->query("SELECT * FROM stu_colonies_fields WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",4); }
	
	function buildonfield($fieldId,$buildingId,$colId)
	{
		$field = $this->getfielddatabyid($fieldId,$colId);
		if ($field == 0)
		{
			$return[msg] = "Dieses Feld kann nicht bebaut werden";
			return $return;
		}
		$building = $this->getbuildbyid($buildingId);
		if ($field[buildings_id] > 0)
		{
			$return[msg] = $field[field_id]." ist bereits bebaut";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_field_build WHERE type='".$field[type]."' AND buildings_id='".$buildingId."'",3) == 0) return 0;
		global $myUser;
		if ($building[level] > $myUser->ulevel)
		{
			$return[msg] = "Das Gebäude (".$building[name].") kann erst ab Level ".$building[level]." gebaut werden.";
			$return[code] = 0;
			return $return;
		}
		if ($building[research_id] > 0)
		{
			if ($this->db->query("SELECT id FROM stu_research_user WHERE research_id=".$building[research_id]." AND user_id=".$this->user,3) == 0)
			{
				$return[msg] = "Dieses Gebäude wurde noch nicht erforscht";
				$return[code] = 0;
				return $return;
			}
		}
		if (($this->cenergie - $building[eps_cost]) < 0)
		{
			$return[msg] = "Zum Bau werden ".$building[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
			$return[code] = 0;
			return $return;
		}
		if (($building[id] == 7) || ($building[id] == 17) || ($building[id] == 33) || ($building[id] == 34) || ($building[id] == 74) || ($building[id] == 75) || ($building[id] == 76))
		{
			$class = $this->getclassbyid($this->ccolonies_classes_id);
			$count = $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=".$building[id]." AND colonies_id=".$colId,1);
			$count += $this->db->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE buildings_id=".$building[id]." AND colonies_id=".$colId,1);
			if ($count >= $class["mine".$building[id]])
			{
				$class["mine".$building[id]] == 0 ? $return[msg] = $building[name]." ist auf diesem Planeten nicht baubar" : $return[msg] = "Es können keine weiteren Gebäude von diesem Typ (".$building[name].") errichtet werden";
				return $return;
			}
		}
		if ($building[blimit] > 0)
		{
			if ($this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=".$building[id]." AND colonies_id=".$colId,1) >= $building[blimit])
			{
				$return[msg] = $building[name]." kann maximal ".$building[blimit]." mal pro Kolonie gebaut werden";
				return $return;
			}
		}
		if ($building[id] == 210)
		{
			global $myAlly;
			if ($myAlly->checkembassybuild() == 0)
			{
				$return[msg] = "Botschaft kann aufgrund fehlender Vorraussetzung nicht gebaut werden";
				return $return;
			}
		}
		if ($building[id] == 51)
		{
			if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildings_id=81",1) > 0)
			{
				$return[msg] = "Es kann nur ein Schildemitter pro Planet gebaut werden";
				return $return;
			}
		}
		if ($building[id] == 82)
		{
			$count = $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=82 AND colonies_id=".$colId,1);
			$count += $this->db->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE buildings_id=82 AND colonies_id=".$colId,1);
			if ($count == 3)
			{
				$return[msg] = "Es können nur maximal 3 Schildbatterien gebaut werden";
				return $return;
			}
		}
		if ($building[id] == 83)
		{
			$count = $this->db->query("SELECT COUNT(a.id) FROM stu_colonies_fields as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE b.user_id=".$this->user." AND a.buildings_id=83",1);
			if ($count >= 4)
			{
				$return[msg] = "Es können maximal 4 Minengefängnisse gebaut werden";
				return $return;
			}
		}
		if ($building[id] == 86)
		{
			if ($this->db->query("SELECT COUNT(a.id) from stu_colonies_fields as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE b.user_id=".$this->user." AND a.buildings_id=86",1) >= 3)
			{
				$return[msg] = "Es können maximal 3 ".$building[name] ."gebaut werden";
				return $return;
			}
		}
		$cost = $this->mincost($buildingId,$this->user,$colId);
		if ($cost[code] == 0)
		{
			$return[msg] = $cost[msg];
			$return[code] = 0;
			return $return;
		}
		if ($building[id] == 38) $this->db->query("UPDATE stu_colonies_underground SET buildings_id=39,integrity=30 WHERE field_id=13 AND colonies_id=".$colId);
		$this->db->query("UPDATE stu_colonies_fields SET buildings_id='".$buildingId."',integrity='".$building[integrity]."',buildtime='".(time()+$building[buildtime])."' WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'");
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$building[eps_cost]." WHERE id='".$colId."'");
		if ($building[eps] > 0) $this->db->query("UPDATE stu_colonies SET max_energie=max_energie+".$building[eps]." WHERE id='".$colId."' AND user_id='".$this->user."'");
		if ($building[lager] > 0) $this->db->query("UPDATE stu_colonies SET max_storage=max_storage+".$building[lager]." WHERE id='".$colId."' AND user_id='".$this->user."'");
		if ($building[schilde] > 0) $this->db->query("UPDATE stu_colonies SET max_schilde=max_schilde+".$building[schilde]." WHERE id=".$colId." AND user_id=".$this->user);
		$return[msg] = "Der Bau des Gebäudes (".$building[name].") auf Feld ".($field[field_id]+1)." wird am ".date("d.m.Y H:i",(time()+$building[buildtime]))." beendet sein";
		$return[code] = 1;
		return $return;
	}

	function deactivateBuilding($fieldId,$colId)
	{
		$data = $this->getcolonybyid($colId);
		if ($data == 0) return 0;
		$field = $this->getfielddatabyid($fieldId,$colId);
		if ($field == 0) return 0;
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($field[buildings_id] == 1) || ($field[buildings_id] == 23) || ($field[buildings_id] == 63) || ($field[buildings_id] == 64) ||($field[buildings_id] == 65) || ($field[buildings_id] == 66) || ($field[buildings_id] == 89) || ($field[buildings_id] == 163) || ($field[buildings_id] == 178))
		{
			$return[msg] = $build[name]." kann nicht deaktiviert werden.";
			$return[code] = -1;
			return $return;
		}
		if ($field[aktiv] == 0) return 0;
		if (($build[bev_pro] > 0) && ($data[max_bev] - $build[bev_pro] < $data[bev_used]))
		{
			$return[msg] = "Das Gebäude konnte nicht deaktiviert werden, da sonst einige Arbeiter obdachlos wären";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies_fields SET aktiv=0 WHERE colonies_id='".$colId."' AND field_id='".$fieldId."' AND aktiv=1");
		$this->db->query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use].",max_bev=max_bev-".$build[bev_pro]." WHERE id='".$colId."'");
		if ($build[id] == 51 || $build[id] == 81) $this->db->query("UPDATE stu_colonies SET schilde_aktiv=0 WHERE id=".$colId);
		if ($build[id] == 21 || $build[id] == 168 || ($build[id] == 192 && $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildings_id=192 AND aktiv=1",1) < 1))
		{
			$orbu = $this->db->query("SELECT SUM(a.bev_use) as usum,SUM(a.bev_pro) as besum FROM stu_buildings as a LEFT JOIN stu_colonies_orbit as b ON a.id=b.buildings_id WHERE b.aktiv=1 AND colonies_id=".$colId,4);
			if ($orbu[usum] != "") $this->db->query("UPDATE stu_colonies SET bev_used=bev_used-".$orbu[usum].",bev_free=bev_free+".$orbu[usum].",max_bev=max_bev-".$orbu[besum]." WHERE id=".$colId);
			$this->db->query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE colonies_id=".$colId);
		}
		if ($build[id] == 38 || ($build[id] == 192 && $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildings_id=195 AND aktiv=1",1) < 2))
		{
			$grou = $this->db->query("SELECT SUM(a.bev_use) as usum,SUM(a.bev_pro) as besum FROM stu_buildings as a LEFT JOIN stu_colonies_underground as b ON a.id=b.buildings_id WHERE b.aktiv=1 AND colonies_id=".$colId,4);
			if ($grou[usum] != "") $this->db->query("UPDATE stu_colonies SET bev_used=bev_used-".$grou[usum].",bev_free=bev_free+".$grou[usum].",max_bev=max_bev-".$grou[besum]." WHERE id=".$colId);
			$this->db->query("UPDATE stu_colonies_underground SET aktiv=0 WHERE colonies_id=".$colId);
			$this->db->query("UPDATE stu_colonies_underground SET aktiv=0 WHERE field_id=13 AND colonies_id=".$colId);
		}
		if ($build[id] == 88) $this->db->query("UPDATE stu_colonies SET cloakfield='0' WHERE id=".$colId);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." deaktiviert";
		$return[code] = 1;
		return $return;
	}

	function activateBuilding($fieldId,$colId,$userId)
	{
		$data = $this->getcolonybyid($colId);
		if ($data == 0) return 0;
		$field = $this->getfielddatabyid($fieldId,$colId);
		if ($field[buildings_id] == 0) return 0;
		if ($field == 0) return 0;
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($build[id] == 10) || ($build[id] == 4) || ($build[id] == 35) || ($build[id] == 37) || ($build[id] == 82) || ($build[id] == 52) || ($build[id] == 136) || ($build[id] == 143) || (($build[id] >= 107) && ($build[id] <= 134)) || ($build[id] == 157) || ($build[id] == 206))
		{
			$return[msg] = "Das Gebäude besitzt keine Aktivierungsfunktion";
			return $return;
		}
		if (($build[id] >= 210) && ($build[id] <= 215))
		{
			$return[msg] = "Das Gebäude besitzt keine Aktivierungsfunktion";
			return $return;
		}
		if ($field[aktiv] == 1)
		{
			$return[msg] = "Das Gebäude ist bereits aktiviert";
			return $return;
		}
		if ($field[buildtime] > 0)
		{
			$return[msg] = "Dieses Gebäude kann nicht aktiviert werden, da es sich noch in Bau befindet";
			return $return;
		}
		if ($data[bev_free] < $build[bev_use] && $build[bev_pro] == 0)
		{
			$return[msg] = "Zum Aktivieren werden ".$build[bev_use]." Arbeiter benötigt - Es sind jedoch nur ".$data[bev_free]." Kolonisten frei";
			$return[code] = 0;
			return $return;
		}
		global $myUser;
		if ($myUser->getfield("level",$userId) < $build[level])
		{
			$return[msg] = "Zum aktivieren des Gebäudes wird Level ".$build[level]." benötigt";
			return $return;
		}
		if ($build[research_id] > 0 && $this->getuserresearch($build[research_id],$userId) == 0)
		{
			$return[msg] = "Du kannst dieses Gebäude nicht aktivieren, da Du es noch nicht erforscht hast";
			return $return;
		}
		if ($build[bev_use] <= $data[bev_free])
		{
			if ($field[buildings_id] != 4)
			{
				$this->db->query("UPDATE stu_colonies_fields SET aktiv=1 WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'");
				$this->db->query("UPDATE stu_colonies SET bev_free=bev_free-".$build[bev_use].",bev_used=bev_used+".$build[bev_use]." WHERE id='".$colId."'");
			}
		}
		if (($build[bev_pro] > 0) && ($field[aktiv] == 0))
		{
			$this->db->query("UPDATE stu_colonies_fields SET aktiv=1 WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'");
			$this->db->query("UPDATE stu_colonies SET max_bev=max_bev+".$build[bev_pro]." WHERE id='".$colId."'");
		}
		if ($build[id] == 38) $this->db->query("UPDATE stu_colonies_underground SET aktiv=1 WHERE field_id=13 ANd colonies_id=".$colId);
		if ($build[id] == 88)
		{
			$this->db->query("UPDATE stu_colonies SET cloakfield=".$field[field_id]." WHERE id=".$colId);
		}
		if ($build[id] == 51 || $build[id] == 81) $this->db->query("UPDATE stu_colonies SET schild_freq1=".rand(10,99).",schild_freq2=".rand(1,9)." WHERE id=".$colId);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." aktiviert";
		$return[code] = 1;
		return $return;
	}

	function renameCol($new_name)
	{
		$this->db->query("UPDATE stu_colonies SET name='".strip_tags(addslashes(str_replace("\"","",str_replace("background","",$new_name))),"<font></font>")."' WHERE id='".$this->cid."' AND user_id=".$this->user);
		$return[msg] = "Änderung des Namens in ".stripslashes($new_name)." erfolgreich";
		return $return;
	}

	function deletebuilding($fieldId)
	{
		if ($this->cshow == 0) return 0;
		$field = $this->getfielddatabyid($fieldId,$this->cid);
		if ($field == 0) return -1;
		$build = $this->getbuildbyid($field[buildings_id]);
		if ($field[buildings_id] == 1 || ($field[buildings_id] >= 63 && $field[buildings_id] <= 66))
		{
			$return[msg] = "Die Koloniezentrale kann nicht abgerissen werden";
			return $return;
		}
		if ($field[buildings_id] == 23)
		{
			$return[msg] = "Die Basiskuppel kann nicht abgerissen werden";
			return $return;
		}
		if ($field[buildings_id] == 218)
		{
			$return[msg] = "Dieses Gebäude kann nicht demontiert werden";
			return $return;
		}
		global $myAlly;
		if (($build[id] >= 210 && $build[id] <= 215) && ($myAlly->getembassyownerbycolony($this->cid,$fieldId) != 0))
		{
			$return[msg] = "Diese Botschaft wird noch von einer Allianz verwendet";
			return $return;
		}
		if ($field[buildings_id] == 4) $this->db->query("UPDATE stu_colonies SET max_energie=max_energie-'30'".$energie." WHERE id=".$this->cid);
		if ($build[lager] > 0) $this->db->query("UPDATE stu_colonies SET max_storage=max_storage-".$build[lager]." WHERE id=".$this->cid);
		if ($field[aktiv] == 1)
		{
			$test = $this->deactivatebuilding($fieldId,$this->cid);
			if ($test[code] != 1) return $test;
		}
		if ($build[id] == 38) $this->db->query("UPDATE stu_colonies_underground SET buildings_id=0,aktiv=0,integrity=0 WHERE field_id=13 ANd colonies_id=".$this->cid);
		if ($build[id] == 51 || $build[id] == 81) $this->db->query("UPDATE stu_colonies SET schilde=0,max_schilde=max_schilde-".$build[schilde].",schild_freq1=0,schild_freq2=0,schilde_aktiv=0 WHERE id=".$this->cid);
		if ($build[schilde] > 0)
		{
			$this->db->query("UPDATE stu_colonies SET max_schilde=max_schilde-".$build[schilde]." WHERE id=".$this->cid);
			if ($this->cschilde > $this->cmax_schilde-$build[schilde]) $this->db->query("UPDATE stu_colonies SET schilde=".($this->cmax_schilde-$build[schilde])." WHERE id=".$this->cid);
		}
		if ($build[id] == 88) $this->db->query("UPDATE stu_colonies SET cloakfield='0' WHERE id=".$this->cid);
		$this->db->query("UPDATE stu_colonies_fields SET buildings_id='0',integrity='0',name='',buildtime='' WHERE colonies_id='".$this->cid."' AND field_id=".$fieldId);
		//$this->returncost($build[id],$this->user,$this->cid);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." demontiert";
		return $return;
	}

	function getgoodsbybuilding($buildingId) { return $this->db->query("SELECT count,goods_id,mode,buildings_id FROM stu_buildings_goods WHERE buildings_id='".$buildingId."'",2); }

	function goodlist()	{ return $this->db->query("SELECT id,name FROM stu_goods ORDER BY sort ASC",2); }

	function getstoragebygoodid($goodsId) { return $this->db->query("SELECT colonies_id,user_id,goods_id,count FROM stu_colonies_storage WHERE goods_id='".$goodsId."' AND colonies_id='".$this->cid."'",4); }

	function getunknownmodules($colId) { return $this->db->query("SELECT COUNT(b.id) FROM stu_colonies_storage as a LEFT JOIN stu_ships_modules as b ON a.goods_id=b.goods_id WHERE a.colonies_id=".$colId." AND a.count>0 AND b.view=0",1); }

	function getunknownmodulest($colId)	{ return $this->db->query("SELECT a.* FROM stu_ships_modules as a LEFT JOIN stu_colonies_storage as b ON a.goods_id=b.goods_id WHERE a.view=0 AND b.count>0 AND b.colonies_id=".$colId." ORDER BY type,lvl",2); }

	function getbuildingcostbyid($buildingId) { return $this->db->query("SELECT a.goods_id,a.count,b.name FROM stu_buildings_cost as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.buildings_id=".$buildingId." ORDER BY a.goods_id ASC",2); }

	function getmodulecostbyid($moduleId) { return $this->db->query("SELECT a.goods_id,a.count,b.name FROM stu_ships_modules_cost as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.modules_id=".$moduleId." ORDER BY goods_id ASC",2); }

	function getmodulebytype($type) { return $this->db->query("SELECT id,name,lvl,wirt,buildtime,huell,eps,phaser,torp_evade,reaktor,phaser_chance,lss_range,shields,goods_id,ecost,besonder FROM stu_ships_modules WHERE type=".$type." AND view=1 ORDER BY lvl ASC",2); }

	function getbuildinggoodsbyid($buildingId) { return $this->db->query("SELECT a.goods_id,a.count,a.mode,b.name FROM stu_buildings_goods as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.buildings_id=".$buildingId." ORDER BY goods_id ASC",2); }

	function mincost($buildingId)
	{
		include_once("inc/buildcost.inc.php");
		$result = getbuildingcostbyid($buildingId);
		if ($result == 0) return 1;
		for ($i=0;$i<count($result);$i++)
		{
			$count = $this->db->query("SELECT count FROM stu_colonies_storage WHERE goods_id='".$result[$i][goods_id]."' AND colonies_id=".$this->cid,1);
			if ($count < $result[$i]['count'])
			{
				$return[code] = 0;
				$return[msg] = "Zum Bau werden ".$result[$i]['count']." ".$result[$i][name]." benötigt - Vorhanden sind aber nur ".$count;
				return $return;
			}
		}
		for ($i=0;$i<count($result);$i++) $this->lowerstoragebygoodid($result[$i]['count'],$result[$i]['goods_id'],$this->cid);
		$return[code] = 1;
		return $return;
	}

	function returncost($buildingId)
	{
		include_once("inc/buildcost.inc.php");
		$result = getbuildingcostbyid($buildingId);
		if ($result == 0) return 1;
		$sum = $this->db->query("SELECT SUM(count) as sum_count FROM stu_colonies_storage WHERE colonies_id='".$colId."'",1);
		for ($i=0;$i<count($result);$i++)
		{
			if ($sum >= $this->cmax_storage) return 1;
			if ($result[$i][goods_id] != 7)
			{
				$count = floor($result[$i]['count']/2);
				if ($this->cmax_storage < $count + $sum) $count = $this->cmax_storage - $sum;
				$this->upperstoragebygoodid($count,$result[$i][goods_id],$this->cid,$this->user);
				$sum += $count;
			}
		}
		return 1;
	}
	
	function gettfbyid($terraformId) { return $this->db->query("SELECT * FROM stu_terraform WHERE id=".$terraformId,4); }

	function terraform($fieldId,$colId,$terraform,$mode)
	{
		if ($this->cshow == 0) return 0;
		if ($mode == "field")
		{
			$field = "fields";
			$data = $this->getfielddatabyid($fieldId,$colId);
		}
		if ($mode == "orbit") $field = "orbit";
		if ($mode == "ground")
		{
			$field = "underground";
			$data = $this->getgroundfielddatabyid($fieldId,$colId);
		}
		if ($data == 0) return 0;
		$tf = $this->gettfbyid($terraform);
		if ($tf == 0) return 0;
		if ($tf[v_feld] != $data[type]) return 0;
		if ($tf[research_id] > 0) if ($this->getuserresearch($tf[research_id],$this->user) == 0) return 0;
		if (($terraform == 16 || $terraform == 6 || $terraform == 17) && $this->db->query("SELECT aktiv FROM stu_colonies_fields WHERE (buildings_id=38 OR buildings_id=195) AND colonies_id=".$this->cid,1) == 0)
		{
			$return[msg] = "Zum sprengen wird ein aktivierter Untergrundlift benötigt";
			return $return;
		}
		if ($fieldId == 31 && $tf[save31] == 1)
		{
			$return[msg] = "Dieses Feld kann aufgrund der planetaren Integrität nicht gesprengt werden";
			return $return;
		}
		if ($tf[id] == 14 && $this->ccolonies_classes_id !=5)
		{
			$return[msg] = "Tiefenbohrungen können nuf auf Klasse K Planeten durchgeführt werden";
			return $return;
		}
		if (($tf[id] == 7 || $tf[id] == 18 || $tf[id] == 19 || $tf[id] == 20) && $this->db->query("SELECT id FROM stu_colonies_fields WHERE type=".$tf[z_feld]." AND colonies_id=".$this->cid,1) != 0)
		{
			$return[msg] = "Dieses Terraforming ist nur einmal möglich";
			return $return;
		}
		if ($tf[flimit] > 0)
		{
			if ($this->db->query("SELECT COUNT(id) FROM stu_colonies_".$field." WHERE type=".$tf[z_feld]." AND colonies_id=".$this->cid,1) >= $tf[flimit])
			{
				$return[msg] = "Dieses Terraforming ist nur ".$tf[flimit]." mal möglich";
				return $return;
			}
		}
		if ($data[buildings_id] > 0)
		{
			$return[msg] = "Terraforming nicht möglich - Das Feld ist bebaut";
			return $return;
		}
		if ($tf[ecost] > $this->cenergie)
		{
			$return[msg] = "Für das Terraforming werden ".$tf[ecost]." Energie benötigt - Vorhanden ist nur ".$this->cenergie;
			return $return;
		}
		$result = $this->tfmincost($terraform);
		if ($result[code] == 0) return $result;
		if ($tf[id] == 7 || $tf[id] == 18 || $tf[id] == 19 || $tf[id] == 20) $this->db->query("UPDATE stu_colonies_underground SET type=15 WHERE field_id=13 AND colonies_id=".$this->cid);
		$this->db->query("UPDATE stu_colonies_".$field." SET type=".$tf[z_feld]." WHERE field_id=".$fieldId." AND colonies_id=".$colId);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$tf[ecost]." WHERE id=".$this->cid);
		if ($tf[symp_min] > 0) $this->db->query("UPDATE stu_user SET symp=symp-".$tf[symp_min]." WHERE id=".$this->user);
		if ($tf[symp_plus] > 0) $this->db->query("UPDATE stu_user SET symp=symp+".$tf[symp_plus]." WHERE id=".$this->user);
		global $grafik;
		$return[msg] = "<table cellpadding= cellspacing=1 bgcolor=#262323>
		<tr>
			<td class=tdmainobg><img src=".$grafik."/fields/".$tf[v_feld].".gif></td>
			<td class=tdmainobg>-></td>
			<td class=tdmainobg><img src=".$grafik."/fields/".$tf[z_feld].".gif></td>
			<td class=tdmainobg>Terraforming abgeschlossen</td>
		</tr></table>";
		return $return;
	}
	
	function gettfcost($terraformId) { return $this->db->query("SELECT a.goods_id,a.count,b.name FROM stu_terraform_cost as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.terraform_id=".$terraformId); }
	
	function tfmincost($terraformId)
	{
		$result = $this->gettfcost($terraformId);
		if ($result == 0)
		{
			$return[code] = 1;
			return $return;
		}
		while($tf=mysql_fetch_assoc($result))
		{
			$count = $this->db->query("SELECT a.count FROM stu_colonies_storage as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.goods_id='".$tf[goods_id]."' AND a.colonies_id=".$this->cid,1);
			if ($count < $tf['count'])
			{
				$return[code] = 0;
				$return[msg] = "Zum Terraformen werden ".$tf['count']." ".$tf[name]." benötigt - Vorhanden sind aber nur ".$count;
				return $return;
			}
		}
		$result = $this->gettfcost($terraformId);
		while($t=mysql_fetch_assoc($result)) $this->lowerstoragebygoodid($t['count'],$t[goods_id],$this->cid);
		$return[code] = 1;
		return $return;
	}
	
	function beamto($id,$goodId,$count)
	{
		global $myShip,$grafik,$myUser;
		$shipdata = $myShip->getdatabyid($id);
		if ($shipdata == 0 || $this->cshow == 0 || $shipdata[cloak] == 1) return 0;
		if ($this->ccoords_x != $shipdata[coords_x] || $this->ccoords_y != $shipdata[coords_y]) return 0;
		if ($this->cwese != $shipdata[wese]) return 0;
		if ($shipdata[damaged] == 1) $mpf = "d/";
		if ($shipdata[user_id] == $this->user)
		{
			$transadd = "zu der <a href=main.php?page=ship&section=showship&id=".$shipId2.">".$shipdata[name]."</a>";
			$img = "<a href=?page=ship&section=showship&id=".$shipdata[id]."><img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif border=0></a>";
		}
		else
		{
			$transadd = "zu der ".$shipdata[name];
			$img = "<img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif>";
		}
		if ($count < 0)
		{
			$return[msg] = "Es wurde eine falsche Anzahl eingegeben";
			return $return;
		}
		if ($myUser->getfield("vac",$shipdata[user_id]) == 1)
		{
			$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
			return $this->cret($destroy);
		}
		if ($shipdata[c][trumfield] == 1)
		{
			$return[msg] = "Das Wrack kann nicht erfasst werden.";
			$return[code] = -1;
			return $return;
		}
		if ($shipdata[schilde_aktiv] == 1)
		{
			$return[msg] = "Die ".$shipdata[name]." hat die Schilde aktiviert";
			$return[code] == 0;
			return $return;
		}
		if ($this->user != 19 && $goodId == 301)
		{
			$return[msg] = "Transportversuch fehlgeschlagen, Waren verloren";
			$this->lowerstoragebygoodid($count,$goodId,$this->cid);
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
		$stor = $this->db->query("SELECT count FROM stu_colonies_storage WHERE goods_id=".$goodId." AND colonies_id=".$this->cid,1);
		if ($stor == 0)
		{
			$return[msg] = "Ware nicht vorhanden.";
			$return[code] = 0;
			return $return;
		}
		if ($count > $stor) $count = $stor;
		if ($this->db->query("SELECT id FROM stu_torpedo_types WHERE goods_id=".$goodId,1) != 0)
		{
			if ($shipdata[c][torps] == 0)
			{
				$return[msg] = "Dieses Schiff kann keine Torpedos an Board nehmen";
				return $return;
			}
			$torp = $this->db->query("SELECT research_id,size FROM stu_torpedo_types WHERE goods_id=".$goodId,4);
			if ($torp[size] > $shipdata[c][size])
			{
				$return[msg] = "Dieser Torpedotyp kann nicht geladen werden";
				return $return;
			}
			$tt = $myShip->gettorptypegood($shipdata[id]);
			if ($tt != 0 && $tt != $goodId)
			{
				$return[msg] = "Die Torpedoabschussrampen sind bereits belegt";
				return $return;
			}
			$torpcount = $myShip->getcountbygoodid($goodId,$shipdata[id]);
			if ($torpcount >= $shipdata[c][torps])
			{
				$return[msg] = "Es können maximal ".$shipdata[c][torps]." Torpedos geladen werden";
				return $return;
			}
			if ($torpcount + $count > $shipdata[c][torps]) $count = $shipdata[c][torps]-$torpcount;
			$probes = $this->db->query("SELECT SUM(COUNT) FROM stu_ships_storage WHERE ships_id=".$shipdata[id]." AND (goods_id=35 OR goods_id=36 OR goods_id=37 OR goods_id=204)",1);
			if ($torpcount >= ($shipdata[c][torps]-$probes))
			{
				$probes != 0 ? $return[msg] = "Es können maximal ".($shipdata[c][torps]-$probes)." Torpedos geladen werden - ".$probes." Rampen durch Sonden belegt" : $return[msg] = "Es können maximal ".$shipdata[c][torps]." Torpedos geladen werden";
				return $return;
			}
			if ($torpcount + $count + $probes > $shipdata[c][torps]) $count = $shipdata[c][torps]-$torpcount-$probes;
		}
		if ($goodId == 35 || $goodId == 36 || $goodId == 37 || $goodId == 204)
		{
			$probes = $this->db->query("SELECT SUM(COUNT) FROM stu_ships_storage WHERE ships_id=".$shipdata[id]." AND (goods_id=35 OR goods_id=36 OR goods_id=37 OR goods_id=204)",1);
			$tt = $myShip->gettorptypegood($shipdata[id]);
			$tc = 0;
			if ($tt != 0) $tc = $myShip->getcountbygoodid($tt,$shipdata[id]);
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
			if ($count > ($sonkap-$probes)) $count = ($sonkap-$probes);
		}
		if ($count < 1)
		{
			$return[msg] = "Keine Waren zum beamen.";
			$return[code] = 0;
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie auf der Kolonie vorhanden.";
			$return[code] = 0;
			return $return;
		}
		$sum = $this->db->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$shipdata[id],1);
		if ($sum >= $shipdata[c][storage])
		{
			$return[msg] = "Kein Lagerraum vorhanden";
			return $return;
		}
		$sum + $count > $shipdata[c][storage] ? $beam = $shipdata[c][storage]-$sum : $beam = $count;
		if ($beam < 1)
		{
			$return[msg] = "Kein Platz auf dem Schiff vorhanden.";
			$return[code] = 0;
			return $return;
		}
		$faktor = 30;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE buildings_id=143 AND colonies_id=".$this->cid,3) != 0) $faktor += 20;
		$energie = ceil($beam/$faktor);
		if ($energie > $this->cenergie)
		{
			$energie = $this->cenergie;
			$beam = $this->cenergie * $faktor;
		}
		if ($energie < 1) return 0;
		$myShip->upperstoragebygoodid($beam,$goodId,$shipdata[id],$shipdata[user_id]);
		$this->lowerstoragebygoodid($beam,$goodId,$this->cid);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$energie." WHERE id=".$this->cid);
		$this->cenergie -= $energie;
		$return[beamed] = $beam;
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$grafik."/planets/".$this->ccolonies_classes_id.".gif></td>
			<td class=tdmainobg width=20 align=Center><img src=".$grafik."/buttons/b_to2.gif></td>
			<td class=tdmainobg align=center>".$img."</td>
			<td class=tdmainobg>".$beam." ".$this->db->query("SELECT name FROM stu_goods WHERE id=".$goodId,1)." ".$transadd." gebeamt - ".$energie." Energie verbraucht</td></tr></table>";
		$return[code] = 1;
		return $return;
	}
	
	function beamfrom($id,$goodId,$count)
	{
		global $myShip,$grafik,$myUser;
		$shipdata = $myShip->getdatabyid($id);
		if ($shipdata == 0 || $this->cshow == 0 || $shipdata[cloak] == 1) return 0;
		if ($this->cwese != $shipdata[wese]) return 0;
		if ($this->ccoords_x != $shipdata[coords_x] || $this->ccoords_y != $shipdata[coords_y]) return 0;
		if ($this->cwese != $shipdata[wese]) return 0;
		if ($myUser->getfield("level",$shipdata[user_id]) == 1)
		{
			$return[msg] = "Das beamen von Schiffen von Siedlern mit Level 1 ist untersagt";
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden.";
			$return[code] = 0;
			return $return;
		}
		if ($shipdata[damaged] == 1) $mpf = "d/";
		if ($shipdata[user_id] == $this->user)
		{
			$transadd = "von der <a href=main.php?page=ship&section=showship&id=".$shipId2.">".$shipdata[name]."</a>";
			$img = "<a href=?page=ship&section=showship&id=".$shipdata[id]."><img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif border=0></a>";
		}
		else
		{
			$transadd = "von der ".$shipdata[name];
			$img = "<img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif>";
		}
		if ($count < 0)
		{
			$return[msg] = "Es wurde eine falsche Anzahl eingegeben";
			return $return;
		}
		if ($shipdata[ships_rumps_id] == 111)
		{
			$return[msg] = "Von einem Konstrukt kann nicht gebeamt werden";
			return $return;
		}
		if ($shipdata[ships_rumps_id] == 213)
		{
			$return[msg] = "Transporterstrahl wird vom Ziel geblockt";
			return $return;
		}
		if ($myUser->getfield("vac",$shipdata[user_id]) == 1)
		{
			$this->msghandle("Der User befindet sich zur Zeit im Urlaub");
			return $this->cret($destroy);
		}
		if ($this->db->query("SELECT id FROM stu_torpedo_types WHERE goods_id=".$goodId,1) != 0 && $shipdata[c][trumfield] != 1 && $shipdata[user_id] != $this->user)
		{
			$return[msg] = "Du kannst keine Torpedos von anderen Schiffe beamen";
			return $return;
		}
		if ($shipdata[schilde_aktiv] == 1)
		{
			$return[msg] = "Die ".$shipdata[name]." hat die Schilde aktiviert";
			return $return;
		}
		if ($this->user != 19 && $goodId == 301)
		{
			$return[msg] = "Transportversuch fehlgeschlagen, Waren verloren";
			$this->lowerstoragebygoodid($count,$goodId,$id);
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
		$stor = $this->db->query("SELECT count FROM stu_ships_storage WHERE goods_id=".$goodId." AND ships_id=".$shipdata[id],1);
		if ($stor == 0)
		{
			$return[msg] = "Ware nicht vorhanden.";
			$return[code] = 0;
			return $return;
		}
		if ($stor < $count) $count = $stor;
		$sum = $this->db->query("SELECT SUM(count) FROM stu_colonies_storage WHERE colonies_id=".$this->cid,1);
		if ($sum > $this->cmax_storage)
		{
			$return[msg] = "Kein Lagerraum vorhanden";
			return $return;
		}
		$this->cmax_storage < $sum + $count ? $beam = $this->cmax_storage-$sum : $beam = $count;
		$faktor = 30;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE buildings_id=143 AND colonies_id=".$this->cid,3) != 0) $faktor += 20;
		$energie = ceil($beam/$faktor);
		if ($energie > $this->cenergie)
		{
			$energie = $this->cenergie;
			$beam = $this->cenergie * $faktor;
		}
		if ($beam < 1)
		{
			$return[msg] = "Kein Lagerraum vorhanden.";
			$return[code] = 0;
			return $return;
		}
		$myShip->lowerstoragebygoodid($beam,$goodId,$shipdata[id]);
		$this->upperstoragebygoodid($beam,$goodId,$this->cid,$this->user);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$energie." WHERE id=".$this->cid);
		$this->cenergie -= $energie;
		$return[beamed] = $beam;
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$grafik."/planets/".$this->ccolonies_classes_id.".gif></td>
			<td class=tdmainobg width=20 align=Center><img src=".$grafik."/buttons/b_from2.gif></td>
			<td class=tdmainobg align=center>".$img."</td>
			<td class=tdmainobg>".$beam." ".$this->db->query("SELECT name FROM stu_goods WHERE id=".$goodId,1)." ".$transadd." gebeamt - ".$energie." Energie verbraucht</td></tr></table>";
		$return[code] = 1;
		return $return;
	}
	
	function getorbititems($x,$y,$wese=1)
	{
		global $myUser;
		return $this->db->query("SELECT a.id,a.ships_rumps_id,a.user_id,a.name,a.batt,a.huelle,a.huellmodlvl,b.huellmod,a.energie,a.epsmodlvl,b.epsmod,b.max_batt,b.trumfield FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.coords_x='".$x."' AND a.coords_y='".$y."' AND a.wese=".$wese." AND a.cloak=0 ORDER BY fleets_id DESC,".$myUser->getslsorting($sq="a."),2);
	}
	
	function getownorbititems($x,$y,$cs=0)
	{
		global $myUser;
		$cs == 0 ? $cadd = " AND cloak=0" : $cadd = "";
		return $this->db->query("SELECT id,name FROM stu_ships WHERE coords_x='".$x."' AND coords_y='".$y."'".$cadd." AND wese=".$this->cwese." AND user_id=".$this->user." ORDER BY fleets_id DESC,".$myUser->getslsorting(),2);
	}
	
	function transfercrew($shipid,$colId,$crew,$way)
	{
		if ($crew < 0 || $this->cshow == 0) return 0;
		global $myShip;
		$shipdata = $myShip->getdatabyid($shipid);
		if ($shipdata == 0) return 0;
		if ($this->ccoords_x != $shipdata[coords_x] || $this->ccoords_y != $shipdata[coords_y]) return 0;
		if ($this->cwese != $shipdata[wese]) return 0;
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($shipdata[schilde_aktiv] == 1)
		{
			$return[msg] = "Die Schilde der ".stripslashes($shipdata[name])." sind aktiviert";
			return $return;
		}
		if ($way == "to")
		{
			if ($this->cbev_free == 0)
			{
				$return[msg] = "Es befinden sich keine freien Einwohner auf dem Planeten";
				return $return;
			}
			if ($shipdata[c][crew] <= $shipdata[crew])
			{
				$return[msg] = "Alle Crewquartiere der ".stripslashes($shipdata[name])." sind belegt";
				return $return;
			}
			if ($crew > $this->cbev_free) $crew = $this->cbev_free;
			if ($crew > $shipdata[c][crew] - $shipdata[crew]) $crew = $shipdata[c][crew] - $shipdata[crew];
		}
		elseif ($way == "from")
		{
			if ($shipdata[crew] == 0)
			{
				$return[msg] = "Es befinden sich keine Crewmitglieder auf dem Schiff";
				return $return;
			}
			if ($this->cbev_free+$this->cbev_used >= $this->cmax_bev)
			{
				$return[msg] = "Es ist kein freier Wohnraum auf der Kolonie vorhanden";
				return $return;
			}
			if ($crew > $shipdata[crew]) $crew = $shipdata[crew];
			if ($crew > $this->cmax_bev-$this->cbev_used-$this->cbev_free) $crew = $this->cmax_bev-$this->cbev_used-$this->cbev_free;
		}
		if ($crew == 0) return 0;
		$energie = ceil($crew/4);
		if ($energie > $this->cenergie)
		{
			$energie = $this->cenergie;
			$crew = $energie*4;
		}
		if ($way == "to")
		{
			$part = "bev_free=bev_free-".$crew;
			$part2 = "crew=crew+".$crew;
		}
		elseif ($way == "from")
		{
			$part = "bev_free=bev_free+".$crew;
			$part2 = "crew=crew-".$crew;
		}
		if ($crew < 0) return 0;
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$energie.",".$part." WHERE id='".$colId."'");
		$this->db->query("UPDATE stu_ships SET ".$part2." WHERE id=".$shipid);
		global $grafik;
		$way == "to" ? $img = "<img src=".$grafik."/buttons/b_to2.gif>" : $img = "<img src=".$grafik."/buttons/b_from2.gif>";
		if ($shipdata[damaged] == 1) $mpf = "d/";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><a href=main.php?page=colony&section=showcolony&id=".$colId."><img src=".$grafik."/planets/".$this->ccolonies_classes_id.".gif border=0></a></td>
						<td class=tdmainobg width=20 align=Center>".$img."</td>
						<td class=tdmainobg align=center><a href=main.php?page=ship&section=showship&id=".$shipid."><img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif border=0></a></td>
						<td class=tdmainobg>".$crew." Crewmitglieder gebeamt - ".$energie." Energie verbraucht</td></tr></table>";
		$return[code] = 1;
		$this->cenergie -= $energie;
		return $return;
	}
	
	function etransfer($id,$colId,$count)
	{
		if ($count <= 0 && $count != "max") return 0;
		global $myShip,$grafik,$myUser;
		$shipdata = $myShip->getdatabyid($id);
		if ($shipdata == 0 || $this->cshow == 0) return 0;
		if ($shipdata[c][probe] == 1)
		{
			$return[msg] = "Die Sonde kann nicht erfasst werden";
			return $return;
		}
		if ($myUser->getfield("vac",$shipdata[user_id]) == 1)
		{
			$return[msg] = "Der User befindet sich zur Zeit im Urlaub";
			return $return;
		}
		if ($shipdata[damaged] == 1) $mpf = "d/";
		$shipdata[user_id] == $this->user ? $img = "<a href=main.php?page=ship&section=showship&id=".$id."><img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif border=0></a>" : $img = "<img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif border=0>";
		if (($this->ccoords_x != $shipdata[coords_x]) || ($this->ccoords_y != $shipdata[coords_y])) return 0;
		if ($count == "max") $count = $this->cenergie;
		if ($this->cenergie < $count) $count = $this->cenergie;
		if ($shipdata[energie] + $count > $shipdata[maxeps]) $count = $shipdata[maxeps] - $shipdata[energie];
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$count." WHERE id='".$colId."'");
		$this->db->query("UPDATE stu_ships SET energie=energie+".$count." WHERE id='".$id."'");
		if ($this->user != $shipdata[user_id])
		{
			global $myComm;
			$myComm->sendpm($shipdata[user_id],$this->user,"Die ".$this->cname." transferiert in Sektor ".$this->ccoords_x."/".$this->ccoords_y." ".$count." Energie zu der ".$shipdata[name]."",3);
		}
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$grafik."/planets/".$this->ccolonies_classes_id.".gif border=0></td>
						<td class=tdmainobg width=20 align=Center><img src=".$grafik."/buttons/b_to2.gif></td>
						<td class=tdmainobg align=center>".$img."</td>
						<td class=tdmainobg>".$count." Energie zur ".$shipdata[name]." transferiert</td></tr></table>";
		$return[code] = 1;
		return $return;
	}
	
	function getclassm($x,$y,$wese) { return $this->db->query("SELECT * FROM stu_colonies as a LEFT JOIN stu_map_fields as b USING(coords_x,coords_y,wese) WHERE a.user_id=2 AND a.colonies_classes_id=1 AND a.coords_x BETWEEN ".($x-20)." AND ".($x+20)." AND a.coords_y BETWEEN ".($y-20)." AND ".($y+20)." AND a.wese=".$wese." AND b.race=0"); }
	
	function upgradebuilding($fieldId,$colId)
	{
		if ($this->cshow == 0) return 0;
		$data = $this->getfielddatabyid($fieldId,$colId);
		if ($data == 0) return 0;
		if ($data[buildtime] != 0)
		{
			$return[msg] = "Das Gebäude wurde noch nicht fertiggestellt";
			return $return;
		}
		global $myUser;
		if ($data[buildings_id] == 3)
		{
			if ($data[aktiv] == 0)
			{
				$return[msg] = "Die Häuser auf diesem Feld sind nicht aktiviert";
				return $return;
			}
			$build1 = $this->getbuildbyid(3);
			$build2 = $this->getbuildbyid(16);
			if ($build2[level] > $myUser->ulevel)
			{
				$return[msg] = "Städte können erst ab Level ".$build2[level]." errichtet werden";
				$return[code] = -1;
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				$return[code] = 0;
				return $return;
			}
			if ($data[buildings_id] == 3) $result = $this->mincost(16,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost].",max_bev=max_bev-".$build1[bev_pro]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='16',integrity=".$build2[integrity].",aktiv=0,buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
		elseif ($data[buildings_id] == 21)
		{
			$build1 = $this->getbuildbyid(21);
			$build2 = $this->getbuildbyid(168);
			$coldata = $this->getcolonybyid($colId);
			if ($this->ccolonies_classes_id == 9)
			{
				$return[msg] = "Auf dieser Planetenklasse kann kein Interstellarer Raumhafen gebaut werden";
				return $return;
			}
			if ($this->getuserresearch(211,$this->user) == 0)
			{
				$return[msg] = "Du hast den interstellaren Raumhafen noch nicht erforscht";
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie." Energie";
				return $return;
			}
			if ($this->db->query("SELECT COUNT(a.id) FROM stu_colonies_fields as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE b.user_id=".$this->user." AND a.buildings_id=168",1) >= 2)
			{
				$return[msg] = "Es können maximal 2 interstellare Raumhäfen gebaut werden";
				return $return;
			}
			$result = $this->mincost(168,$this->user,$this->cid);
			if ($result[code] == 0)
			{
				$return[msg] = $result[msg];
				return $return;
			}
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id=".$this->cid);
			$this->deactivatebuilding($fieldId,$colId,$userId);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='168',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id=".$this->cid);
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
		elseif ($data[buildings_id] == 14)
		{
			$build1 = $this->getbuildbyid(14);
			$build2 = $this->getbuildbyid(15);
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost(15,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->deactivatebuilding($fieldId,$colId,$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='15',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
		elseif ($data[buildings_id] == 36)
		{
			$build1 = $this->getbuildbyid(36);
			$build2 = $this->getbuildbyid(78);
			if ($this->getuserresearch(88,$this->user) == 0)
			{
				$return[msg] = "Du hast den Doppelkonverter noch nicht erforscht";
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				return $return;
			}
			$result = $this->mincost(78,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->deactivatebuilding($fieldId,$colId,$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='78',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
		elseif ($data[buildings_id] == 51)
		{
			$build1 = $this->getbuildbyid(51);
			$build2 = $this->getbuildbyid(81);
			if ($research = $this->getuserresearch(114,$this->user) == 0)
			{
				$return[msg] = "Modulation noch nicht erforscht!";
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				return $return;
			}
			$result = $this->mincost(81,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' AND user_id=".$this->user);
			$this->deactivatebuilding($fieldId,$colId,$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='81',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
		elseif ($data[buildings_id] == 1)
		{
			if ($myUser->urasse == 1) $newbuild = 63;
			if ($myUser->urasse == 2) $newbuild = 64;
			if ($myUser->urasse == 3) $newbuild = 65;
			if ($myUser->urasse == 4) $newbuild = 66;
			if ($myUser->urasse == 5) $newbuild = 89;
			$build1 = $this->getbuildbyid(1);
			$build2 = $this->getbuildbyid($newbuild);
			if ($this->getuserresearch(81,$this->user) == 0)
			{
				$return[msg] = "Dieses Gebäude muss erst erforscht werden";
				return $return;
			}
			if ($this->ccolonies_classes_id != 1)
			{
				$return[msg] = "Dieses Gebäude ist auf diesem Planeten nicht baubar";
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost(63,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost].",bev_used=bev_used-".$build2[bev_use]." WHERE id='".$colId."' AND user_id=".$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='".$newbuild."',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Upgrade auf ".$build2[name]." erfolgreich";
			return $return;
		}
		elseif ($data[buildings_id] == 107)
		{
			if ($myUser->urasse == 1) $newbuild = 108;
			if ($myUser->urasse == 2) $newbuild = 109;
			if ($myUser->urasse == 3) $newbuild = 110;
			if ($myUser->urasse == 4) $newbuild = 111;
			if ($myUser->urasse == 5) $newbuild = 112;
			$build1 = $this->getbuildbyid(107);
			$build2 = $this->getbuildbyid($newbuild);
			if ($this->getuserresearch(161,$this->user) == 0)
			{
				$return[msg] = "Dieses Gebäude muss erst erforscht werden";
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost(108,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='".$newbuild."',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
		elseif ($data[buildings_id] == 114)
		{
			if ($myUser->urasse == 1) $newbuild = 115;
			if ($myUser->urasse == 2) $newbuild = 116;
			if ($myUser->urasse == 3) $newbuild = 117;
			if ($myUser->urasse == 4) $newbuild = 118;
			if ($myUser->urasse == 5) $newbuild = 119;
			$build1 = $this->getbuildbyid(114);
			$build2 = $this->getbuildbyid($newbuild);
			if ($this->getuserresearch(162,$this->user) == 0)
			{
				$return[msg] = "Dieses Gebäude muss erst erforscht werden";
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost(115,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='".$newbuild."',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
		elseif ($data[buildings_id] == 121)
		{
			if ($myUser->urasse == 1) $newbuild = 122;
			if ($myUser->urasse == 2) $newbuild = 123;
			if ($myUser->urasse == 3) $newbuild = 124;
			if ($myUser->urasse == 4) $newbuild = 125;
			if ($myUser->urasse == 5) $newbuild = 126;
			$build1 = $this->getbuildbyid(121);
			$build2 = $this->getbuildbyid($newbuild);
			if ($this->getuserresearch(163,$this->user) == 0)
			{
				$return[msg] = "Dieses Gebäude muss erst erforscht werden";
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost(122,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='".$newbuild."',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
		elseif ($data[buildings_id] == 128)
		{
			if ($myUser->urasse == 1) $newbuild = 129;
			if ($myUser->urasse == 2) $newbuild = 130;
			if ($myUser->urasse == 3) $newbuild = 131;
			if ($myUser->urasse == 4) $newbuild = 132;
			if ($myUser->urasse == 5) $newbuild = 133;
			$build1 = $this->getbuildbyid(128);
			$build2 = $this->getbuildbyid($newbuild);
			if ($this->getuserresearch(164,$this->user) == 0)
			{
				$return[msg] = "Dieses Gebäude muss erst erforscht werden";
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost(129,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='".$newbuild."',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
	}
	
	function orbitupgrade($fieldId,$colId)
	{
		if ($this->cshow == 0) return 0;
		$data = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($data == 0) return 0;
		if ($data[buildtime] != 0)
		{
			$return[msg] = "Das Gebäude wurde noch nicht fertiggestellt";
			return $return;
		}
		if ($data[buildings_id] == 26)
		{
			$build1 = $this->getbuildbyid(26);
			global $myUser;
			if ($myUser->urasse == 1) $building = 27;
			if ($myUser->urasse == 2) $building = 28;
			if ($myUser->urasse == 3) $building = 29;
			if ($myUser->urasse == 4) $building = 30;
			if ($myUser->urasse == 5) $building = 135;
			$build2 = $this->getbuildbyid($building);
			if ($this->getuserresearch(5,$this->user) != 1)
			{
				$return[msg] = "Die erweiterte Werft wurde noch nicht erforscht";
				return $return;
			}
			if ($data[buildings_id] != 26)
			{
				$return[msg] = "Es kann nur eine Werft zu einer erweiterten Werft upgegradet werden";
				$return[code] = -1;
				return $return;
			}
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost($building,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->db->query("UPDATE stu_colonies_orbit SET buildings_id='".$building."',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
		}
		$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
		return $return;
	}
	
	function groundupgrade($fieldId,$colId)
	{
		if ($this->cshow == 0) return 0;
		$data = $this->getgroundfielddatabyid($fieldId,$colId);
		if ($data == 0) return 0;
		if ($data[buildtime] != 0)
		{
			$return[msg] = "Gebäude noch nicht fertiggestellt!";
			return $return;
		}
		if ($data[buildings_id] == 36)
		{
			$build1 = $this->getbuildbyid(36);
			$building = 78;
			$build2 = $this->getbuildbyid($building);
			if ($this->getuserresearch(88,$this->user) != 1)
			{
				$return[msg] = "Der Doppelkonverter wurde noch nicht erforscht";
				return $return;
			}
			$coldata = $this->getcolonybyid($colId);
			if ($this->cenergie - $build2[eps_cost] < 0)
			{
				$return[msg] = "Es ist nicht genügend Energie vorhanden";
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost($building,$this->user,$colId);
			if ($result[code] == 0) return $result;
			$this->deactivategroundbuilding($fieldId,$colId,$this->user);
			$this->db->query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost]." WHERE id='".$colId."' ANd user_id=".$this->user);
			$this->db->query("UPDATE stu_colonies_underground SET buildings_id='".$building."',integrity=".$build2[integrity].",buildtime=".(time()+$build2[buildtime])." WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Der Bau des Gebäudes (".$build2[name].") auf Feld ".($fieldId+1)." wird am ".date("d.m.Y H:i",(time()+$build2[buildtime]))." beendet sein";
			return $return;
		}
	}
	
	function getcolorbit($colId) { return $this->db->query("SELECT field_id,buildings_id,buildtime,aktiv,type,name FROM stu_colonies_orbit WHERE colonies_id='".$colId."' ORDER BY field_id ASC",2); }
	
	function getcolorbitbybuilding($colId) { return $this->db->query("SELECT a.aktiv,a.field_id,b.name FROM stu_colonies_orbit as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.colonies_id=".$colId." AND buildings_id>0 ORDER BY b.name,a.field_id ASC"); }
	
	function orbitbuild($fieldId,$buildingId,$colId)
	{
		if ($this->cshow == 0) return 0;
		$data = $this->getcolonybyid($colId);
		$building = $this->getbuildbyid($buildingId);
		if ($data == 0) return 0;
		$field = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($field == 0)
		{
			$return[msg] = "Dieses Feld kann nicht bebaut werden";
			return $return;
		}
		$building = $this->getbuildbyid($buildingId);
		if ($field[buildings_id] > 0)
		{
			$return[msg] = "Dieses Feld ist bereits bebaut";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_field_build WHERE type='".$field[type]."' AND buildings_id='".$buildingId."'",1) == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 OR buildings_id=168 OR buildings_id=192) AND colonies_id=".$colId." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum Orbitbau wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		if ($buildingId == 26 && $this->db->query("SELECT id FROM stu_colonies_orbit WHERE ((buildings_id>25 AND buildings_id<31) OR  buildings_id=135) AND colonies_id=".$colId,1) > 0)
		{
			$return[msg] = "Es ist bereits eine Werft vorhanden";
			return $return;
		}
		if ($buildingId == 47 && $this->db->query("SELECT id FROM stu_colonies_orbit WHERE buildings_id=47 AND colonies_id=".$colId,1) > 0)
		{
			$return[msg] = "Es ist bereits eine Wetterkontrollstation vorhanden";
			return $return;
		}
		if ($buildingId == 84 && $this->db->query("SELECT id FROM stu_colonies_orbit WHERE buildings_id=84 AND colonies_id=".$colId,1) > 0)
		{
			$return[msg] = "Es ist bereits ein Sensornetz vorhanden";
			return $return;
		}
		if ($buildingId == 102 && $this->db->query("SELECT id FROM stu_colonies_orbit WHERE buildings_id=102 AND colonies_id=".$colId,1) > 0)
		{
			$return[msg] = "Es ist bereits ein Horchposten vorhanden";
			return $return;
		}
		if ($buildingId == 156 && $this->db->query("SELECT COUNT(id) FROM stu_colonies_orbit WHERE buildings_id=156 AND colonies_id=".$colId,1) == 3)
		{
			$return[msg] = "Es können maximal 3 Materie-Extraktoren gebaut werden";
			return $return;
		}
		if ($building[id] == 87 && $this->db->query("SELECT COUNT(a.id) from stu_colonies_orbit as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE b.user_id=".$this->user." AND a.buildings_id=87",1) == 2)
		{
			$return[msg] = "Es können maximal 2 Genesis-Forschungsstationen gebaut werden";
			return $return;
		}
		if ($building[id] == 165 && $this->db->query("SELECT COUNT(a.id) from stu_colonies_orbit as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE b.user_id=".$this->user." AND a.buildings_id=165",1) == 4)
		{
			$return[msg] = "Es können maximal 4 Verarbeitungsstationen gebaut werden";
			return $return;
		}
		if ($building[research_id] > 0 && $this->getuserresearch($building[research_id],$this->user) == 0)
		{
			$return[msg] = "Dieses Gebäude wurde noch nicht erforscht";
			return $return;
		}
		if ($this->cenergie - $building[eps_cost] < 0)
		{
			$return[msg] = "Zum Bau werden ".$building[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
			return $return;
		}
		$cost = $this->mincost($buildingId,$this->user,$colId);
		if ($cost[code] == 0) return $cost;
		$this->db->query("UPDATE stu_colonies_orbit SET buildings_id='".$buildingId."',integrity='".$building[integrity]."',buildtime='".(time()+$building[buildtime])."' WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'");
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$building[eps_cost]." WHERE id='".$colId."'");
		if ($building[eps] > 0) $this->db->query("UPDATE stu_colonies SET max_energie=max_energie+".$building[eps]." WHERE id='".$colId."' AND user_id=".$this->user);
		if ($building[lager] > 0) $this->db->query("UPDATE stu_colonies SET max_storage=max_storage+".$building[lager]." WHERE id='".$colId."' AND user_id=".$this->user);
		$return[msg] = "Der Bau des Gebäudes (".$building[name].") auf Feld ".($field[field_id]+1)." wird am ".date("d.m.Y H:i",(time()+$building[buildtime]))." beendet sein";
		$return[code] = 1;
		return $return;
	}

	function getorbitfielddatabyid($fieldId,$colId) { return $this->db->query("SELECT type,buildings_id,aktiv,integrity,name,buildtime,field_id FROM stu_colonies_orbit WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",4); }

	function activateorbitBuilding($fieldId,$colId,$userId)
	{
		$data = $this->getcolonybyid($colId);
		$field = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($data == 0) return 0;
		if ($field == 0) return 0;
		if ($data[user_id] != $userId) return 0;
		if ($field[buildings_id] == 0) return 0;
		if ($field[aktiv] == 1)
		{
			$return[msg] = "Das Gebäude ist bereits aktiviert";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 OR buildings_id=168 OR buildings_id=192) AND colonies_id=".$colId." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum Aktivieren wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		if ($field[buildtime] > 0)
		{
			$return[msg] = "Dieses Gebäude kann nicht aktiviert werden, da es sich noch in Bau befindet";
			return $return;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($build[id] == 26) || ($build[id] == 27) || ($build[id] == 28) || ($build[id] == 29) || ($build[id] == 30) || ($build[id] == 53) || ($build[id] == 52) || ($build[id] == 84) || ($build[id] == 102) || ($build[id] == 135))
		{
			$return[msg] = "Das Gebäude besitzt keine Aktivierungsfunktion";
			return $return;
		}
		if ($data[bev_free] < $build[bev_use])
		{
			$return[msg] = "Es werden ".$build[bev_use]." freie Arbeiter benötigt";
			return $return;
		}
		global $myUser;
		if ($myUser->getfield("level",$userId) < $build[level])
		{
			$return[msg] = "Zum aktivieren des Gebäudes wird Level ".$build[level]." benötigt";
			return $return;
		}
		if ($build[research_id] > 0 && $this->getuserresearch($build[research_id],$userId) == 0)
		{
			$return[msg] = "Du kannst dieses Gebäude nicht aktivieren, da Du es noch nicht erforscht hast";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies SET bev_free=bev_free-".$build[bev_use].",bev_used=bev_used+".$build[bev_use].",max_bev=max_bev+".$build[bev_pro]." WHERE id='".$data[id]."'");
		$this->db->query("UPDATE stu_colonies_orbit SET aktiv=1 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."'");
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." aktiviert";
		$return[code] = 1;
		return $return;
	}
	
	function getorbitfieldbyid($fieldId,$colId)
	{
		$field[data] = $this->getorbitfielddatabyid($fieldId,$colId);
		$field[build] = $this->getbuildbyid($field[data][buildings_id]);
		return $field;
	}

	function deactivateorbitBuilding($fieldId,$colId,$userId)
	{
		$data = $this->getcolonybyid($colId);
		if ($data == 0) return 0;
		$field = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($field[aktiv] == 0) return 0;
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($build[bev_pro] > 0) && ($data[bev_used] > $data[max_bev]-$build[bev_pro]))
		{
			$return[msg] = "Das Gebäude konnte nicht deaktiviert werden, da sonst einige Arbeiter obdachlos wären";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."' AND aktiv=1");
		$this->db->query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use].",max_bev=max_bev-".$build[bev_pro]." WHERE id=".$data[id]);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." deaktiviert";
		$return[code] = 1;
		return $return;
	}

	function deleteorbitbuilding($fieldId,$colId,$userId)
	{
		if ($this->cshow == 0) return 0;
		$field = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($field == 0) return 0;
		if ($field[aktiv] == 1)
		{
			$test = $this->deactivateorbitbuilding($fieldId,$colId,$userId);
			if ($test[code] != 1) return $test;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		//$this->returncost($build[id],$userId,$colId);
		$this->db->query("UPDATE stu_colonies_orbit SET buildings_id='0',integrity='0',name='',buildtime='' WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'");
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($build[id] > 25 && $build[id] < 32) || $build[id] == 135) $this->db->query("DELETE FROM stu_ships_buildprogress WHERE colonies_id=".$colId);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." wurde demontiert";
		return $return;
	}

	function getcolunderground($colId) { return $this->db->query("SELECT field_id,buildings_id,buildtime,aktiv,type,name FROM stu_colonies_underground WHERE colonies_id='".$colId."' ORDER BY field_id ASC",2); }

	function getcolundergroundbybuilding($colId) { return $this->db->query("SELECT a.aktiv,a.field_id,b.name FROM stu_colonies_underground as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.colonies_id=".$colId." AND buildings_id>0 ORDER BY b.name,a.field_id ASC"); }

	function groundbuild($fieldId,$buildingId)
	{
		if ($this->cshow == 0) return 0;
		$field = $this->getgroundfielddatabyid($fieldId,$this->cid);
		if ($field == 0) return 0;
		if ($field[buildings_id] > 0) return 0;
		if ($this->db->query("SELECT type FROM stu_field_build WHERE type='".$field[type]."' AND buildings_id='".$buildingId."'",1) == 0) return 0;
		if ($this->db->query("SELECT field_id FROM stu_colonies_fields WHERE (buildings_id=38 OR buildings_id=195) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum Bau im Untergrund wird ein aktivierter Untergrundlift benötigt";
			return $return;
		}
		if ($buildingId == 39 && $this->db->query("SELECT field_id FROM stu_colonies_underground WHERE buildings_id=39 AND colonies_id=".$this->cid,1) != 0)
		{
			$return[msg] = "Es ist bereits ein Untergrundlift vorhanden";
			return $return;
		}
		$building = $this->getbuildbyid($buildingId);
		if ($building[blimit] > 0)
		{
			if ($this->db->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE buildings_id=".$building[id]." AND colonies_id=".$this->cid,1) >= $building[blimit])
			{
				$return[msg] = $building[name]." kann maximal ".$building[blimit]." mal pro Kolonie gebaut werden";
				return $return;
			}
		}
		if ($this->cenergie - $building[eps_cost] < 0)
		{
			$return[msg] = "Zum Bau werden ".$building[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
			$return[code] = 0;
			return $return;
		}
		if (($building[id] == 7) || ($building[id] == 17) || ($building[id] == 33) || ($building[id] == 34))
		{
			$class = $this->getclassbyid($this->ccolonies_classes_id);
			$count = $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=".$building[id]." AND colonies_id=".$this->cid,1);
			$count += $this->db->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE buildings_id=".$building[id]." AND colonies_id=".$this->cid,1);
			if ($count >= $class["mine".$building[id]])
			{
				if ($class["mine".$building[id]] == 0) $return[msg] = $building[name]." ist auf diesem Planeten nicht baubar";
				else $return[msg] = "Es können keine weiteren Gebäude von diesem Typ (".$building[name].") errichtet werden";
				return $return;
			}
		}
		if ($building[id] == 82)
		{
			$count = $this->db->query("SELECT COUNT(id) FROM stu_colonies_fields WHERE buildings_id=82 AND colonies_id=".$this->cid,1);
			$count += $this->db->query("SELECT COUNT(id) FROM stu_colonies_underground WHERE buildings_id=82 AND colonies_id=".$this->cid,1);
			if ($count == 3)
			{
				$return[msg] = "Es können nur maximal 3 Schildbatterien gebaut werden";
				return $return;
			}
		}
		if ($building[research_id] > 0 && $this->getuserresearch($building[research_id],$this->user) == 0)
		{
			$return[msg] = "Dieses Gebäude wurde noch nicht erforscht";
			return $return;
		}
		$cost = $this->mincost($buildingId,$this->user,$this->cid);
		if ($cost[code] == 0) return $cost;
		$this->db->query("UPDATE stu_colonies_underground SET buildings_id='".$buildingId."',integrity='".$building[integrity]."',buildtime=".(time()+$building[buildtime])." WHERE field_id='".$fieldId."' AND colonies_id=".$this->cid);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$building[eps_cost].",max_energie=max_energie+".$building[eps].",max_storage=max_storage+".$building[lager].",max_schilde=max_schilde+".$building[schilde]." WHERE id=".$this->cid);
		$return[msg] = "Der Bau des Gebäudes (".$building[name].") auf Feld ".($field[field_id]+1)." wird am ".date("d.m.Y H:i",(time()+$building[buildtime]))." beendet sein";
		return $return;
	}

	function getgroundfielddatabyid($fieldId,$colId) { return $this->db->query("SELECT type,buildings_id,aktiv,integrity,name,buildtime,field_id FROM stu_colonies_underground WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",4); }

	function activategroundBuilding($fieldId,$colId,$userId)
	{
		$data = $this->getcolonybyid($colId);
		if ($data == 0) return 0;
		$field = $this->getgroundfielddatabyid($fieldId,$colId);
		if ($field == 0) return 0;
		if ($field[buildings_id] == 0) return 0;
		if ($field[aktiv] == 1)
		{
			$return[msg] = "Das Gebäude ist bereits aktiviert";
			return $return;
		}
		if ($field[buildtime] > 0)
		{
			$return[msg] = "Dieses Gebäude kann nicht aktiviert werden, da es sich noch in Bau befindet";
			return $return;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=38 OR buildings_id=195) AND colonies_id=".$colId." AND aktiv=1",1) == 0) && ($build[id] != 39))
		{
			$return[msg] = "Zum Aktivieren wird ein aktivierter Untergrundlift benötigt";
			return $return;
		}
		if (($build[id] == 26) || ($build[id] == 35) || ($build[id] == 37) || ($build[id] == 52) || ($build[lager]> 100) || ($build[id] == 82))
		{
			$return[msg] = "Das Gebäude besitzt keine Aktivierungsfunktion";
			return $return;
		}
		if ($data[bev_free] < $build[bev_use])
		{
			$return[msg] = "Zum Aktivieren des Gebäudes werden ".$build[bev_use]." Arbeiter benötigt";
			return $return;
		}
		global $myUser;
		if ($myUser->getfield("level",$userId) < $build[level])
		{
			$return[msg] = "Zum aktivieren des Gebäudes wird Level ".$build[level]." benötigt";
			return $return;
		}
		if ($build[research_id] > 0 && $this->getuserresearch($build[research_id],$userId) == 0)
		{
			$return[msg] = "Du kannst dieses Gebäude nicht aktivieren, da Du es noch nicht erforscht hast";
			return $return;
		}
		if ($build[id] == 39) $act = $this->activateBuilding(31,$colId,$userId);
		if ($build[id] == 39 && $act[code] != 1) return $act;
		if ($build[bev_use] <= $data[bev_free])
		{
			if ($field[buildings_id] != 4) $this->db->query("UPDATE stu_colonies_underground SET aktiv=1 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."'");
			$this->db->query("UPDATE stu_colonies SET bev_free=bev_free-".$build[bev_use].",bev_used=bev_used+".$build[bev_use]." WHERE id=".$data[id]);
		}
		if ($build[bev_pro] > 0) $this->db->query("UPDATE stu_colonies SET max_bev=max_bev+".$build[bev_pro]." WHERE id=".$colId);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." aktiviert";
		$return[code] = 1;
		return $return;
	}
	
	function getgroundfieldbyid($fieldId,$colId)
	{
		$field[data] = $this->getgroundfielddatabyid($fieldId,$colId);
		$field[build] = $this->getbuildbyid($field[data][buildings_id]);
		return $field;
	}
	
	function deactivategroundBuilding($fieldId,$colId,$userId)
	{
		$data = $this->getcolonybyid($colId);
		if ($data == 0) return -1;
		$field = $this->getgroundfielddatabyid($fieldId,$colId);
		if ($field == 0) return 0;
		if ($field[aktiv] == 0) return 0;
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($build[bev_pro] > 0) && ($data[max_bev] - $build[bev_pro] < $data[bev_used]))
		{
			$return[msg] = "Das Gebäude konnte nicht deaktiviert werden da, sonst einige Arbeiter obdachlos wären";
			$return[code] = 0;
			return $return;
		}
		$this->db->query("UPDATE stu_colonies_underground SET aktiv=0 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."' AND aktiv=1");
		$this->db->query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use].",max_bev=max_bev-".$build[bev_pro]." WHERE id=".$data[id]);
		if ($build[id] == 39)
		{
			$grou = $this->db->query("SELECT SUM(a.bev_use) as usum,SUM(a.bev_pro) as besum FROM stu_buildings as a LEFT JOIN stu_colonies_underground as b ON a.id=b.buildings_id WHERE b.aktiv=1 AND colonies_id=".$colId,4);
			if ($grou[usum] != "") $this->db->query("UPDATE stu_colonies SET bev_used=bev_used-".$grou[usum].",bev_free=bev_free+".$grou[usum].",max_bev=max_bev-".$grou[besum]." WHERE id=".$colId);
			$this->db->query("UPDATE stu_colonies_underground SET aktiv=0 WHERE colonies_id=".$colId);
			$liftbuild = $this->getbuildbyid(38);
			$this->db->query("UPDATE stu_colonies SET bev_used=bev_used-".$liftbuild[bev_use].",bev_free=bev_free+".$liftbuild[bev_use]." WHERE id=".$colId);
			$this->db->query("UPDATE stu_colonies_fields SET aktiv=0 WHERE field_id=31 AND colonies_id=".$colId);
		}
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." deaktiviert";
		$return[code] = 1;
		return $return;
	}
	
	function deletegroundbuilding($fieldId)
	{
		if ($this->cshow == 0) return 0;
		$field = $this->getgroundfielddatabyid($fieldId,$this->cid);
		if ($field == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE buildings_id=38 AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum demontieren wird ein aktivierter Untergrundlift benötigt";
			return $return;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		if ($field[aktiv] == 1)
		{
			$act = $this->deactivategroundbuilding($fieldId,$this->cid,$this->user);
			if ($act[code] != 1) return $act;
		}
		$this->db->query("UPDATE stu_colonies_underground SET buildings_id='0',integrity='0',name='',buildtime='' WHERE colonies_id='".$this->cid."' AND field_id='".$fieldId."'");
		if ($build[id] == 39)
		{
			$this->db->query("UPDATE stu_colonies SET max_storage=max_storage-'150' WHERE id=".$this->cid);
			$this->deletebuilding(31,$this->cid,$this->user);
		}
		if ($build[lager] > 0) $this->db->query("UPDATE stu_colonies SET max_storage=max_storage-".$build[lager]." WHERE id=".$this->cid);
		if ($build[schilde] > 0)
		{
			$this->db->query("UPDATE stu_colonies SET max_schilde=max_schilde-".$build[schilde]." WHERE id=".$this->cid);
			if ($this->cschilde > $this->cmax_schilde-$build[schilde]) $this->db->query("UPDATE stu_colonies SET schilde=".($this->cmax_schilde-$build[schilde])." WHERE id=".$this->cid);
		}
		//$this->returncost($build[id],$this->user,$this->cid);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." wurde demontiert";
		return $return;
	}
	
	function checkgroundfield($type,$colId) { return $this->db->query("SELECT id FROM stu_colonies_underground WHERE type='".$type."' AND colonies_id='".$colId."'",3); }
	
	function getpossibleships() { return $this->db->query("SELECT b.id,b.name,b.ewerft,b.secretimage FROM stu_ships_build as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.user_id='".$this->user."' AND b.slots=0 AND b.ewerft != 2 ORDER BY b.sorta,b.sortb"); }
	
	//function getModuleById($moduleId) { return $this->db->query("SELECT * FROM stu_ships_modules WHERE id=".$moduleId,4); }
	
	function getModuleById($moduleId) { return getmodulebyid($moduleId); }
	
	function buildship($huellmod,$schildmod,$waffenmod,$sensormod,$antriebmod,$reaktormod,$epsmod,$computermod,$classId)
	{
		// if ($this->cshow == 0) return 0;

		if ($this->db->query("SELECT id FROM stu_ships_build WHERE user_id='".$this->user."' AND ships_rumps_id='".$classId."'",1) == 0)
		{
			$return[msg] = "Du darfst diesen Schiffstyp nicht bauen";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 OR buildings_id=168) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum Bau eines Schiffes wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		global $myShip;
		$class = $myShip->getclassbyid($classId);
		if ($class[slots] > 0)
		{
			$return[msg] = "Stationen können nicht in Werften gebaut werden";
			return $return;
		}
		if (($this->db->query("SELECT id FROM stu_colonies_orbit WHERE ((buildings_id > 25 AND buildings_id < 31) OR buildings_id = 135) AND colonies_id=".$this->cid." AND buildtime=0",1) == 0) && ($class[ewerft] == 0))
		{
			$return[msg] = "Zum Bau dieses Schiffes wird eine Werft benötigt";
			return $return;
		}
		if (($this->db->query("SELECT id FROM stu_colonies_orbit WHERE ((buildings_id > 26 AND buildings_id < 31) OR buildings_id = 135) AND colonies_id=".$this->cid." AND buildtime=0",1) == 0) && ($class[ewerft] == 1))
		{
			$return[msg] = "Zum Bau dieses Schiffes wird eine erweiterte Werft benötigt";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_ships_buildprogress WHERE colonies_id=".$this->cid,1) > 0)
		{
			$return[msg] = "In dieser Werft wird bereits ein Schiff gebaut";
			return $return;
		}
		$result = $this->checkModules($huellmod,$schildmod,$waffenmod,$epsmod,$computermod,$antriebmod,$sensormod,$reaktormod,$classId,$this->cid,$this->user);
		if ($result[code] == 0) return $result;
		$buildtime = $class[buildtime];
		$points = $class[points];
		$module = $this->getmodulebyid($huellmod);
		$huelle = $module[huell]*$class[huellmod];
		$buildtime = $buildtime + ($module[buildtime]*$class[huellmod]);
		$points = $points + ($module[wirt]*$class[huellmod]);
		$module = $this->getmodulebyid($schildmod);
		$buildtime = $buildtime + ($module[buildtime]*$class[schildmod]);
		$points = $points + ($module[wirt]*$class[schildmod]);
		if ($waffenmod != 0)
		{
			$module = $this->getmodulebyid($waffenmod);
			$buildtime = $buildtime + ($module[buildtime]*$class[waffenmod]);
			$points = $points + ($module[wirt]*$class[waffenmod]);
		}
		$module = $this->getmodulebyid($epsmod);
		$buildtime = $buildtime + ($module[buildtime]*$class[epsmod]);
		$points = $points + ($module[wirt]*$class[epsmod]);
		$module = $this->getmodulebyid($computermod);
		$buildtime = $buildtime + $module[buildtime];
		$points = $points + $module[wirt];
		$module = $this->getmodulebyid($sensormod);
		$buildtime = $buildtime + ($module[buildtime]*$class[sensormod]);
		$points = $points + ($module[wirt]*$class[sensormod]);
		$module = $this->getmodulebyid($antriebmod);
		$buildtime = $buildtime + $module[buildtime];
		$points = $points + $module[wirt];
		$module = $this->getmodulebyid($reaktormod);
		$buildtime = $buildtime + $module[buildtime];
		$points = round(($points + $module[wirt]),2);
		global $myUser;
		if ($myUser->uwirtmin > 0)
		{
			$return[msg] = "Du musst zuerst noch ".$userdata[wirtmin]." Wirtschaftspunkte aufholen";
			return $return;
		}
		$wirtsum = $this->db->query("SELECT SUM(wirtschaft) FROM stu_colonies WHERE user_id=".$this->user,1) + floor($myUser->usymp/2500);
		$wirtdata = $this->db->query("SELECT SUM(points) FROM stu_ships WHERE user_id=".$this->user,1);
		if (round($wirtsum,2) < round($wirtdata,2) + round($points,2))
		{
			$return[msg] = "Es sind ".round($wirtsum,2)." Punkte vorhanden - Zum Bau werden ".round(($points+$wirtdata),2)." benötigt";
			return $return;
		}
		if ($this->cenergie - $class[eps_cost] < 0)
		{
			$return[msg] = "Es wird ".$class[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$this->cenergie;
			return $return;
		}
		$result = $this->shipmincost($classId,$this->user,$this->cid);
		if ($result[code] == 0) return $result;
		$module = $this->getmodulebyid($huellmod);
		$this->lowerstoragebygoodid($class[huellmod],$module[goods_id],$this->cid);
		$module = $this->getmodulebyid($schildmod);
		$this->lowerstoragebygoodid($class[schildmod],$module[goods_id],$this->cid);
		if ($waffenmod != 0)
		{
			$module = $this->getmodulebyid($waffenmod);
			$waffenmodlvl = $module[lvl];
			$this->lowerstoragebygoodid($class[waffenmod],$module[goods_id],$this->cid);
		}
		$module = $this->getmodulebyid($epsmod);
		$this->lowerstoragebygoodid($class[epsmod],$module[goods_id],$this->cid);
		$module = $this->getmodulebyid($computermod);
		$this->lowerstoragebygoodid(1,$module[goods_id],$this->cid);
		$module = $this->getmodulebyid($sensormod);
		$this->lowerstoragebygoodid($class[sensormod],$module[goods_id],$this->cid);
		$module = $this->getmodulebyid($antriebmod);
		$this->lowerstoragebygoodid(1,$module[goods_id],$this->cid);
		if ($reaktormod != 0)
		{
			$module = $this->getmodulebyid($reaktormod);
			$this->lowerstoragebygoodid(1,$module[goods_id],$this->cid);
		}
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$class[eps_cost]." WHERE id=".$this->cid);
		$this->db->query("INSERT INTO stu_ships_buildprogress (user_id,colonies_id,ships_rumps_id,huelle,huellmodlvl,sensormodlvl,waffenmodlvl,schildmodlvl,reaktormodlvl,antriebmodlvl,computermodlvl,epsmodlvl,buildtime,points,wese) VALUES ('".$this->user."','".$this->cid."','".$class[id]."','".$huelle."','".$huellmod."','".$sensormod."','".$waffenmod."','".$schildmod."','".$reaktormod."','".$antriebmod."','".$computermod."','".$epsmod."','".(time()+$buildtime)."','".$points."','".$this->cwese."')");
		$return[msg] = $class[name]." wird gebaut - Fertigstellung am ".date("d.m.Y H:i:s",(time()+$buildtime));
		return $return;
	}
	
	function shipmincost($classId,$userId,$colId)
	{
		include_once("inc/shipcost.inc.php");
		//$result = getcostbyclass($classId);
		$result[0][goods_id] = 3;
		$result[0]['count'] = 1;
		$result[0][name] = "Baumaterial";
		if ($result == 0) return 1;
		for ($i=0;$i<count($result);$i++)
		{
			$r_ress = $this->db->query("SELECT count FROM stu_colonies_storage WHERE goods_id='".$result[$i][goods_id]."' AND colonies_id='".$colId."' AND user_id='".$userId."'",1);
			if ($result[$i]['count'] > $r_ress)
			{
				$return[msg] = "Es werden ".$result[$i]['count']." ".$result[$i][name]." benötigt - Vorhanden sind nur ".$r_ress;
				$return[code] = 0;
				return $return;
			}
		}
		for ($i=0;$i<count($result);$i++) $this->lowerstoragebygoodid($result[$i]['count'],$result[$i][goods_id],$colId);
		$return[code] = 1;
		return $return;
	}
	
	function getshipcostbyid($classId) { return $this->db->query("SELECT a.goods_id,a.count,b.name FROM stu_ships_cost as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.ships_rumps_id=".$classId." ORDER BY goods_id ASC",2); }
	
	function getresearchlist()
	{
		global $myUser;
		return $this->db->query("SELECT * FROM stu_research_list WHERE rasse=0 OR rasse=".$myUser->urasse." ORDER BY sort ASC");
	}
	
	function getresearchinfobyid($researchId)
	{
		global $myUser;
		$data = $this->db->query("SELECT * FROM stu_research_list WHERE id='".$researchId."' AND (rasse=0 OR rasse=".$myUser->urasse.")",4);
		if ($data == 0) return 0;
		$rdepencies = $this->db->query("SELECT depency_id FROM stu_research_depencies WHERE research_id='".$researchId."'");
		for ($i=0;$i<mysql_num_rows($rdepencies);$i++)
		{
			$tmp = mysql_fetch_assoc($rdepencies);
			$data[depenc][$i] = $this->getresearchbyid($tmp[depency_id]);
			if ($this->db->query("SELECT COUNT(id) FROM stu_research_user WHERE research_id='".$tmp[depency_id]."' AND user_id=".$this->user,1) == 1) $data[depenc][$i][done] = 1;
			else $data[depenc][$i][done] = 0;
		}
		return $data;
	}
	
	function getresearchdepencies($researchId)
	{
		$rdepencies = $this->db->query("SELECT depency_id FROM stu_research_depencies WHERE research_id='".$researchId."'");
		if ($rdepencies == 0) return 0;
		for ($i=0;$i<mysql_num_rows($rdepencies);$i++)
		{
			$tmp = mysql_fetch_assoc($rdepencies);
			$data[depenc][$i] = $this->getresearchbyid($tmp[depency_id]);
			if ($this->db->query("SELECT COUNT(id) FROM stu_research_user WHERE research_id='".$tmp[depency_id]."' AND user_id=".$this->user,1) == 1) $data[depenc][$i][done] = 1;
			else $data[depenc][$i][done] = 0;
		}
		return $data;
	}
	
	function getresearchbyid($researchId)
	{
		global $myUser;
		return $this->db->query("SELECT * FROM stu_research_list WHERE id='".$researchId."' AND (rasse=0 OR rasse=".$myUser->urasse.")",4);
	}

	function getresearchbyidbone($researchId) { return $this->db->query("SELECT * FROM stu_research_list WHERE id='".$researchId."'",4); }
	
	function getcountbygoodid($goodId,$colId) { return $this->db->query("SELECT count FROM stu_colonies_storage WHERE colonies_id='".$colId."' AND goods_id='".$goodId."'",1); }
	
	function research($researchId)
	{
		$research = $this->getresearchbyid($researchId);
		if ($research == 0) return 0;
		if ($this->db->query("SELECT level FROM stu_user WHERE id=".$this->user,1) < 8) return 0;
		if ($this->db->query("SELECT id FROM stu_research_user WHERE research_id='".$researchId."' AND user_id=".$this->user,1) != 0)
		{
			$return[msg] = $research[name]." wurde bereits erforscht";
			return $return;
		}
		$count = $this->getcountbygoodid(10,$this->cid);
		if ($count < $research[cost])
		{
			$return[msg] = "Nicht genügend Iso-Chips vorhanden";
			return $return;
		}
		$rdepencies = $this->db->query("SELECT * FROM stu_research_depencies WHERE research_id='".$researchId."'");
		for ($i=0;$i<mysql_num_rows($rdepencies);$i++)
		{
			$tmp = mysql_fetch_assoc($rdepencies);
			$data = $this->getresearchbyid($tmp[depency_id]);
			if ($this->db->query("SELECT id FROM stu_research_user WHERE research_id='".$tmp[depency_id]."' AND user_id=".$this->user,1) == 0)
			{
				$return[msg] = "Vorraussetzung wurde noch nicht erforscht";
				return $return;
			}
		}
		$this->lowerstoragebygoodid($research[cost],10,$this->cid);
		$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('".$researchId."','".$this->user."')");
		if ($researchId == 5)
		{
			global $myUser;
			if ($myUser->urasse == 1) $this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','10')");
			if ($myUser->urasse == 2) $this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','18')");
			if ($myUser->urasse == 3) $this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','17')");
			if ($myUser->urasse == 4) $this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','22')");
			if ($myUser->urasse == 5) $this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','113')");
		}
		if ($researchId == 62) $this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES (".$this->user.",'7')");
		if ($research[ships_id] > 0) $this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','".$research[ships_id]."')");
		$return[msg] = $research[name]." wurde erforscht";
		return $return;
	}
	
	function loadbatt($id,$count)
	{
		if ($this->cshow == 0) return 0;
		global $myShip;
		$shipdata = $myShip->getdatabyid($id);
		if ($shipdata == 0)
		{
			$return[msg] = "Schiff nicht vorhanden.";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_orbit WHERE colonies_id=".$this->cid." AND buildtime=0 AND ((buildings_id=26) OR (buildings_id=27) OR (buildings_id=28) OR (buildings_id=29) OR (buildings_id=30) OR (buildings_id=135))",1) == 0)
		{
			$return[msg] = "Es befindet sich keine fertiggestellte Werft auf der Kolonie";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 OR buildings_id=168) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum Aufladen der Ersatzbatterie wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		if (($this->ccoords_x != $shipdata[coords_x]) || ($this->ccoords_y != $shipdata[coords_y]))
		{
			$return[msg] = "Schiff und Kolonie müssen sich im selben Sektor befinden.";
			return $return;
		}
		if ($shipdata[batt] == $shipdata[c][max_batt])
		{
			$return[msg] = "Die Reservebatterie der ".$shipdata[name]." ist bereits vollständig aufgeladen";
			return $return;
		}
		if ($shipdata[c][slots] != 0)
		{
			$return[msg] = "Die Reservebatterien von Stationen können nicht in einer Werft aufgeladen werden";
			return $return;
		}
		if ($count == "max") $count = $this->cenergie;
		if ($this->cenergie < $count) $count = $this->cenergie;
		if ($shipdata[batt] + $count > $shipdata[c][max_batt]) $count = $shipdata[c][max_batt] - $shipdata[batt];
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$count." WHERE id=".$this->cid);
		$this->db->query("UPDATE stu_ships SET batt=batt+".$count." WHERE id='".$id."'");
		if ($this->user != $shipdata[user_id])
		{
			global $myComm;
			$myComm->sendpm($shipdata[user_id],$this->user,"Die Kolonie ".$this->cname." hat die Reserverbatterien der ".$shipdata[name]." um ".$count." Energie aufgeladen",3);
		}
		global $grafik;
		if ($this->user == $shipdata[user_id]) $add = "<a href=main.php?page=ship&section=showship&id=".$id."><img src=".$grafik."/ships/".$shipdata[ships_rumps_id].".gif border=0></a>";
		else $add = "<img src=".$grafik."/ships/".$shipdata[ships_rumps_id].".gif>";
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg width=20 align=Center><img src=".$grafik."/buttons/battp2.gif></td>
						<td class=tdmainobg align=center>".$add."</td>
						<td class=tdmainobg>Die Ersatzbatterie der ".$shipdata[name]." wurde um ".$count." Energie aufgeladen</td></tr></table>";
		return $return;
	}
	
	function replikator($source,$end,$count)
	{
		if ($this->cshow == 0) return 0;
		$storcount = $this->getcountbygoodid(3,$this->cid);
		if ($storcount == 0)
		{
			$return[msg] = "Ausgangs-Ressource Baumaterial nicht vorhanden";
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($storcount < $count) $count = $storcount;			
		if ($this->cenergie < $count) $count = $this->cenergie;

		$this->lowerstoragebygoodid($count,3,$this->cid);
		$this->upperstoragebygoodid($count,$end,$this->cid,$this->user);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$count." WHERE id=".$this->cid);
		$return[msg] = "Replikation: ".$count." Torpedos hergestellt - ".$count." Energie verbraucht";
		return $return;
	}

	function fabrik($end,$count,$buildID)
	{
		if ($this->cshow == 0) return 0;
		if ($end <= 49)
		{
			$return[msg] = "Dies ist kein Schiffsmodul";
			return $return;
		}
		if ($end == 51 && $this->db->query("SELECT id FROM stu_research_user WHERE research_id=135 AND user_id=".$this->user,1) == 0) $ferror = 1;
		elseif ($end == 52)
		{
			if ($this->getuserresearch(147,$this->user) == 0) $ferror = 1;
			if (($buildID <= 114) || ($buildID >= 121)) $faerror = 1;
		}
		elseif ($end == 56)
		{
			if ($this->getuserresearch(152,$this->user) == 0) $ferror = 1;
			if (($buildID <= 128) || ($buildID >= 135)) $faerror = 1;
		}
		elseif ($end == 57)
		{
			if ($this->getuserresearch(160,$this->user) == 0) $ferror = 1;
			if (($buildID <= 128) || ($buildID >= 135)) $faerror = 1;
		}
		elseif ($end == 59) if ($this->getuserresearch(136,$this->user) == 0) $ferror = 1;
		elseif ($end == 60)
		{
			if ($this->getuserresearch(148,$this->user) == 0) $ferror = 1;
			if (($buildID <= 114) || ($buildID >= 121)) $faerror = 1;
		}
		elseif ($end == 61)
		{
			if ($this->getuserresearch(166,$this->user) == 0) $ferror = 1;
			if (($buildID <= 114) || ($buildID >= 121)) $faerror = 1;
		}
		elseif ($end == 63) if ($this->getuserresearch(142,$this->user) == 0 || $this->getuserresearch(143,$this->user) == 0) $ferror = 1;
		elseif ($end == 64)
		{
			if ($this->getuserresearch(154,$this->user) == 0 || $this->getuserresearch(155,$this->user) == 0) $ferror = 1;
			if (($buildID <= 107) || ($buildID >= 114)) $faerror = 1;
		}
		elseif ($end == 65)
		{
			if ($this->getuserresearch(177,$this->user) == 0 || $this->getuserresearch(178,$this->user) == 0) $ferror = 1;
			if (($buildID <= 107) || ($buildID >= 114)) $faerror = 1;
		}
		elseif ($end == 67) if ($this->getuserresearch(144,$this->user) == 0 || $this->getuserresearch(145,$this->user) == 0) $ferror = 1;
		elseif ($end == 68)
		{
			if ($this->getuserresearch(156,$this->user) == 0 || $this->getuserresearch(157,$this->user) == 0) $ferror = 1;
			if (($buildID <= 107) || ($buildID >= 114)) $faerror = 1;
		}
		elseif ($end == 69)
		{
			if ($this->getuserresearch(179,$this->user) == 0 || $this->getuserresearch(180,$this->user) == 0) $ferror = 1;
			if (($buildID <= 107) || ($buildID >= 114)) $faerror = 1;
		}
		elseif ($end == 71) if ($this->getuserresearch(146,$this->user) == 0) $ferror = 1;
		elseif ($end == 72)
		{
			if ($this->getuserresearch(158,$this->user) == 0) $ferror = 1;
			if (($buildID <= 107) || ($buildID >= 114)) $faerror = 1;
		}
		elseif ($end == 73)
		{
			if ($this->getuserresearch(181,$this->user) == 0) $ferror = 1;
			if (($buildID <= 107) || ($buildID >= 114)) $faerror = 1;
		}
		elseif ($end == 76) if ($this->getuserresearch(139,$this->user) == 0) $ferror = 1;
		elseif ($end == 77)
		{
			if ($this->getuserresearch(151,$this->user) == 0) $ferror = 1;
			if (($buildID <= 128) || ($buildID >= 135)) $faerror = 1;
		}
		elseif ($end == 78)
		{
			if ($this->getuserresearch(170,$this->user) == 0) $ferror = 1;
			if (($buildID <= 128) || ($buildID >= 135)) $faerror = 1;
		}
		elseif ($end == 80) if ($this->getuserresearch(137,$this->user) == 0) $ferror = 1;
		elseif ($end == 81)
		{
			if ($this->getuserresearch(149,$this->user) == 0) $ferror = 1;
			if (($buildID <= 121) || ($buildID >= 128)) $faerror = 1;
		}
		elseif ($end == 82)
		{
			if ($this->getuserresearch(167,$this->user) == 0) $ferror = 1;
			if (($buildID <= 121) || ($buildID >= 128)) $faerror = 1;
		}
		elseif ($end == 84) if ($this->getuserresearch(141,$this->user) == 0) $ferror = 1;
		elseif ($end == 85)
		{
			if ($this->getuserresearch(153,$this->user) == 0) $ferror = 1;
			if (($buildID <= 128) || ($buildID >= 135)) $faerror = 1;
		}
		elseif ($end == 86)
		{
			if ($this->getuserresearch(169,$this->user) == 0) $ferror = 1;
			if (($buildID <= 128) || ($buildID >= 135)) $faerror = 1;
		}
		elseif ($end == 88) if ($this->getuserresearch(138,$this->user) == 0) $ferror = 1;
		elseif ($end == 89)
		{
			if ($this->getuserresearch(150,$this->user) == 0) $ferror = 1;
			if (($buildID <= 121) || ($buildID >= 128)) $faerror = 1;
		}
		elseif ($end == 90)
		{
			if ($this->getuserresearch(168,$this->user) == 0) $ferror = 1;
			if (($buildID <= 121) || ($buildID >= 128)) $faerror = 1;
		}
		elseif ($end == 168)
		{
			if ($this->getuserresearch(117,$this->user) == 0) $ferror = 1;
			if (($buildID <= 114) || ($buildID >= 121)) $faerror = 1;
		}
		elseif ($end == 152)
		{
			if ($this->getuserresearch(244,$this->user) == 0) $ferror = 1;
			if (($buildID <= 114) || ($buildID >= 121)) $faerror = 1;
		}
		elseif ($end == 166)
		{
			if ($this->getuserresearch(216,$this->user) == 0) $ferror = 1;
			if (($buildID <= 107) || ($buildID >= 114)) $faerror = 1;
		}
		elseif ($end == 167)
		{
			if ($this->getuserresearch(231,$this->user) == 0) $ferror = 1;
			if (($buildID <= 128) || ($buildID >= 135)) $faerror = 1;
		}
		elseif ($end == 99) if ($this->getuserresearch(140,$this->user) == 0) $ferror = 1;
		elseif ($end == 62) if ($this->db->query("SELECT id FROM stu_user WHERE ((rasse=1) OR (rasse=5)) AND id=".$this->user,1) == 0) $ferror = 1;
		elseif ($end == 66) if ($this->db->query("SELECT id FROM stu_user WHERE ((rasse=3) OR (rasse=3)) AND id=".$this->user,1) == 0) $ferror = 1;
		elseif ($end == 70) if ($this->db->query("SELECT id FROM stu_user WHERE rasse=4 AND id=".$this->user,1) == 0) $ferror = 1;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE colonies_id=".$this->cid." AND buildings_id=".$buildID." ANd buildtime=0",1) == 0)
		{
			$return[msg] = "Es befindet sich keine fertiggestellte geeignete Fabrik für dieses Modul auf der Kolonie";
			return $return;
		}
		if ($ferror == 1)
		{
			$return[msg] = "Du kannst dieses Modul nicht bauen";
			return $return;
		}
		if ($faerror == 1)
		{
			$return[msg] = "Dieses Modul kann in dieser Fabrik nicht hergestellt werden";
			return $return;
		}
		$modId = $this->db->query("SELECT id FROM stu_ships_modules WHERE goods_id=".$end,1);
		$module = $this->getmodulebyid($modId);
		$ecost = $module[ecost] * $count;
		if ($this->cenergie < $ecost)
		{
			$return[msg] = "Nicht genug Energie vorhanden";
			return $return;
		}
		$modcost = $this->getmodulecostbyid($modId);
		for ($i=0;$i<count($modcost);$i++)
		{
			$modcost[$i][gesamt] = $modcost[$i]['count'] * $count;
			$storcount = $this->getcountbygoodid($modcost[$i][goods_id],$this->cid);
			if ($storcount < $modcost[$i][gesamt])
			{
				$return[msg] = "Es werden ".($count*$modcost[$i]['count'])." ".$modcost[$i][name]." benötigt - Vorhanden sind nur ".$storcount;
				return $return;
			}
		}
		for ($i=0;$i<count($modcost);$i++) $this->lowerstoragebygoodid($modcost[$i][gesamt],$modcost[$i][goods_id],$this->cid);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$ecost." WHERe id='".$this->cid."' AND user_id=".$this->user);
		$this->upperstoragebygoodid($count,$end,$this->cid,$this->user);
		$return[msg] = "Leite Produktion ein... ".$count." Module hergestellt";
		return $return;
	}
	
	function raffinerie($source,$count)
	{
		if ($this->cshow == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE buildings_id=35 ANd colonies_id=".$this->cid." AND buildtime=0",1) == 0)
		{
			$return[msg] = "Es befindet sich keine aktivierte Raffinerie auf dem Planeten";
			return $return;
		}
		if (($source != 11) && ($source != 13))
		{
			$return[msg] = "Es können nur Kelbonit-Erz und Nitrum-Erz veredelt werden";
			return $return;
		}
		$source == 11 ? $end = 12 : $end = 14;
		$storcount = $this->getcountbygoodid($source,$this->cid);
		if ($storcount < 3)
		{
			$return[msg] = "Ausgangs-Ressourcen nicht vorhanden";
			return $return;
		}
		if ($this->cenergie == 0)
		{
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($storcount < $count*3) $count = floor($storcount/3);
		if ($this->cenergie < $count) $count = $this->cenergie;
		$this->lowerstoragebygoodid(($count*3),$source,$this->cid);
		$this->upperstoragebygoodid($count,$end,$this->cid,$this->user);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$count." WHERE id=".$this->cid);
		$return[msg] = "Leite Veredelung ein...".(3*$count)." ".$this->db->query("SELECT name FROM stu_goods WHERE id='".$source."'",1)." zu ".$count." ".$this->db->query("SELECT name FROM stu_goods WHERE id='".$end."'",1)." umgewandelt - ".$count." Energie verbraucht";
		return $return;
	}
	
	function getuserresearch($researchId,$userId) { return $this->db->query("SELECT COUNT(id) FROM stu_research_user WHERE research_id=".$researchId." AND user_id=".$userId,1); }
	
	function getbestcols() { return $this->db->query("SELECT a.name,a.bev_free+a.bev_used as bevcount,b.user FROM stu_colonies as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.user_id>100 ORDER BY a.bev_used+a.bev_free DESC LIMIT 10",2); }
	
	function getbestwirt() { return $this->db->query("SELECT sum(a.wirtschaft) as maxsum,b.user FROM stu_colonies as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.user_id>100 GROUP BY a.user_id ORDER BY maxsum DESC,a.user_id ASC LIMIT 10",2); }
	
	function getrichestuser()
	{
		$result = $this->db->query("SELECT a.count,b.user FROM stu_ships_storage as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.goods_id=24 AND a.user_id>100");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$tpdata = mysql_fetch_assoc($result);
			if ($data[$tpdata[user]]) $data[$tpdata[user]][latinum] += $tpdata['count'];
			else $data[$tpdata[user]][latinum] = $tpdata['count'];
			$data[$tpdata[user]][user] = $tpdata[user];
		}
		$result = $this->db->query("SELECT a.count,b.user FROM stu_colonies_storage as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.goods_id=24 AND a.user_id>100");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$tpdata = mysql_fetch_assoc($result);
			if ($data[$tpdata[user]]) $data[$tpdata[user]][latinum] += $tpdata['count'];
			else $data[$tpdata[user]][latinum] = $tpdata['count'];
			$data[$tpdata[user]][user] = $tpdata[user];
		}
		$result = $this->db->query("SELECT a.count,b.user FROM stu_trade_goods as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.goods_id=24 AND a.status<2 AND a.user_id>100");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$tpdata = mysql_fetch_assoc($result);
			if ($data[$tpdata[user]]) $data[$tpdata[user]][latinum] += $tpdata['count'];
			else $data[$tpdata[user]][latinum] = $tpdata['count'];
			$data[$tpdata[user]][user] = $tpdata[user];
		}
		return $data;
	}
	
	function getbestresearch() { return $this->db->query("SELECT count(a.id) as idcount,b.user FROM stu_research_user as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.user_id>100 GROUP BY a.user_id ORDER BY idcount DESC,a.user_id ASC LIMIT 10",2); }
	
	function getmostbev() { return $this->db->query("SELECT sum(a.bev_free)+sum(a.bev_used) as maxsum,b.user FROM stu_colonies as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.user_id>100 GROUP BY a.user_id ORDER BY maxsum DESC,a.user_id ASC LIMIT 10",2); }

	function getmostjobless() { return $this->db->query("SELECT SUM(a.bev_free) as maxsum,b.user FROM stu_colonies as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.user_id>100 GROUP BY a.user_id ORDER BY maxsum DESC LIMIT 10"); }

	function getrepaircost ($shipId,$colId)
	{
		global $myShip;
		$shipdata = $myShip->getdatabyid($shipId);
		$huelldam = $shipdata[maxhuell] - $shipdata[huelle];
		$huellfak = $huelldam / $shipdata[maxhuell];
		$cost = $this->getshipcostbyid($shipdata[ships_rumps_id]);
		for ($i=0;$i<19;$i++) $kosten[$i] = 0;
		for ($i=0;$i<count($cost);$i++) {
			if ($cost[$i][goods_id] > 9) $kosten[$cost[$i][goods_id]] = floor($cost[$i]['count'] * $huellfak);
			else $kosten[$cost[$i][goods_id]] = ceil($cost[$i]['count'] * $huellfak);
		}
		$kosten[0] = ceil($shipdata[c][eps_cost] * $huellfak);
		$cst = round($shipdata[c][huellmod]*$huellfak);
		$module = $myShip->getmodulebyid($shipdata[huellmodlvl]);
		$kosten[modules][huellec] = $cst;
		$kosten[modules][huellem] = $shipdata[huellmodlvl];
		$kosten[modules][huelleg] = $module[goods_id];
		$cst = round($shipdata[c][schildmod]*$huellfak);
		$module = $myShip->getmodulebyid($shipdata[schildmodlvl]);
		$kosten[modules][schildec] = $cst;
		$kosten[modules][schildem] = $shipdata[schildmodlvl];
		$kosten[modules][schildeg] = $module[goods_id];
		$cst = round($shipdata[c][sensormod]*$huellfak);
		$module = $myShip->getmodulebyid($shipdata[sensormodlvl]);
		$kosten[modules][sensorc] = $cst;
		$kosten[modules][sensorm] = $shipdata[sensormodlvl];
		$kosten[modules][sensorg] = $module[goods_id];
		$cst = round($shipdata[c][waffenmod]*$huellfak);
		$module = $myShip->getmodulebyid($shipdata[waffenmodlvl]);
		$kosten[modules][waffenc] = $cst;
		$kosten[modules][waffenm] = $shipdata[waffenmodlvl];
		$kosten[modules][waffeng] = $module[goods_id];
		$cst = round(1*$huellfak);
		$module = $myShip->getmodulebyid($shipdata[reaktormodlvl]);
		$kosten[modules][reaktorc] = $cst;
		$kosten[modules][reaktorm] = $shipdata[reaktormodlvl];
		$kosten[modules][reaktorg] = $module[goods_id];
		$cst = round(1*$huellfak);
		$module = $myShip->getmodulebyid($shipdata[antriebmodlvl]);
		$kosten[modules][antriebc] = $cst;
		$kosten[modules][antriebm] = $shipdata[antriebmodlvl];
		$kosten[modules][antriebg] = $module[goods_id];
		$cst = round(1*$huellfak);
		$module = $myShip->getmodulebyid($shipdata[computermodlvl]);
		$kosten[modules][computerc] = $cst;
		$kosten[modules][computerm] = $shipdata[computermodlvl];
		$kosten[modules][computerg] = $module[goods_id];
		$cst = round($shipdata[c][epsmod]*$huellfak);
		$module = $myShip->getmodulebyid($shipdata[epsmodlvl]);
		$kosten[modules][epsc] = $cst;
		$kosten[modules][epsm] = $shipdata[epsmodlvl];
		$kosten[modules][epsg] = $module[goods_id];
		return $kosten;
	}

	function repairship ($shipId)
	{
		global $myShip;
		$shipdata = $myShip->getdatabyid($shipId);
		if ($shipdata == 0)
		{
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		if ($shipdata[c][trumfield] == 1)
		{
			$return[msg] = "Wracks können nicht repariert werden";
			return $return;
		}
		if ($shipdata[c][slots] != 0)
		{
			$return[msg] = "Stationen können nicht in einer Werft repariert werden";
			return $return;
		}
		if (($shipdata[coords_x] != $this->ccoords_x) || ($shipdata[coords_y] != $this->ccoords_y))
		{
			$return[msg] = "Das Schiff muss sich im selben Sektor wie die Kolonie befinden";
			return $return;
		}
		if ($shipdata[cloak] == 1)
		{
			$return[msg] = "Das Schiff ist getarnt";
			return $return;
		}
		if ($shipdata[schilde_aktiv] == 1)
		{
			$return[msg] = "Das Schiff hat die Schilde aktiviert";
			return $return;
		}
		if ($shipdata[maxhuell] == $shipdata[huelle])
		{
			$return[msg] = "Das Schiff ist nicht beschädigt";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 OR buildings_id=168) AND colonies_id=".$this->cid,1) == 0)
		{
			$return[msg] = "Zur Reparatur wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_orbit WHERE (buildings_id>25 AND buildings_id<31) OR buildings_id = 135 AND colonies_id=".$this->cid,1) == 0)
		{
			$return[msg] = "Zur Reparatur wird eine Werft benötigt";
			return $return;
		}
		$cost = $this->getrepaircost($shipId,$this->cid);
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
		if ($cost[0] > 0) $this->db->query("UPDATE stu_colonies SET energie=energie-".$cost[0]." WHERE id=".$this->cid);
		if ($cost[3] > 0) $this->lowerstoragebygoodid($cost[3],3,$this->cid);
		if ($cost[6] > 0) $this->lowerstoragebygoodid($cost[6],6,$this->cid);
		if ($cost[9] > 0) $this->lowerstoragebygoodid($cost[9],9,$this->cid);
		if ($cost[12] > 0) $this->lowerstoragebygoodid($cost[12],12,$this->cid);
		if ($cost[14] > 0) $this->lowerstoragebygoodid($cost[14],14,$this->cid);
		if ($cost[15] > 0) $this->lowerstoragebygoodid($cost[15],15,$this->cid);
		if ($cost[19] > 0) $this->lowerstoragebygoodid($cost[19],19,$this->cid);
		$this->db->query("UPDATE stu_ships SET huelle=".$shipdata[maxhuell].$wka." WHERE id='".$shipId."'");
		$this->db->query("DELETE FROM stu_ships_action WHERE (mode='impdef' OR mode='waffdef' OR mode='cloakdef' OR mode='readef' OR mode='ksendef' OR mode='lsendef' OR mode='shidef') AND ships_id=".$shipdata[id]);
		$return[msg] = "Das Schiff wurde repariert";
		return $return;
	}
	
	function evacuateCol()
	{
		$bev = $this->cbev_used + $this->cbev_free;
		if ($bev > 0) $this->db->query("UPDATE stu_user SET symp=symp-".($bev*5)." WHERE id=".$this->user);
		$this->db->query("UPDATE stu_colonies_fields SET aktiv=0 WHERE colonies_id=".$this->cid);
		$this->db->query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE colonies_id=".$this->cid);
		$this->db->query("UPDATE stu_colonies_fields SET buildings_id=1,integrity=100 WHERE (buildings_id>=63 AND buildings_id<=66) AND colonies_id=".$this->cid);
		$this->db->query("UPDATE stu_colonies_underground SET aktiv=0 WHERE colonies_id=".$this->cid);
		$this->db->query("UPDATE stu_colonies SET name='',bev_used=0,bev_free=0,user_id=2,energie=0,max_bev=0,bev_stop_count=0,wirtschaft=0,schilde_aktiv=0,sperrung=0 WHERE id=".$this->cid);
		$this->db->query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$this->cid);
		$this->db->query("DELETE FROM stu_ships_buildprogress WHERE colonies_id=".$this->cid);
		$this->db->query("DELETE FROM stu_sector_flights WHERE colonies_id=".$this->cid);
		if ($bev > 0) $msg = "<br>".($bev*5)." Sympathie abgezogen";
		$return[msg] = "Die Kolonie wurde aufgegeben".$msg;
		return $return;
	}
	
	function destroyCol()
	{
		if ($this->cid == 6014) 
		{
			$return[msg] = "Diese Kolonie kann nicht gesprengt werden (Denkmalschutz)";
			return $return;
		}
		$bev = $this->cbev_used + $this->cbev_free;
		if ($bev > 0) $this->db->query("UPDATE stu_user SET symp=symp-".($bev*5)." WHERE id=".$this->user);
		$this->db->query("UPDATE stu_colonies_orbit SET aktiv=0,buildings_id=0 WHERE colonies_id=".$this->cid);
		$this->db->query("UPDATE stu_colonies SET name='',bev_used=0,bev_free=0,user_id=2,energie=0,max_storage=0,max_energie=0,max_bev=0,wirtschaft=0,sperrung=0,schilde=0,max_schilde=0,schilde_aktiv=0 WHERE id=".$this->cid);
		$this->db->query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$this->cid);
		$this->db->query("DELETE FROM stu_ships_buildprogress WHERE colonies_id=".$this->cid);
		global $global_path;
		if ($this->ccolonies_classes_id == 1) include_once($global_path."intern/inc/m.inc.php");
		if ($this->ccolonies_classes_id == 2) include_once($global_path."intern/inc/l.inc.php");
		if ($this->ccolonies_classes_id == 3) include_once($global_path."intern/inc/n.inc.php");
		if ($this->ccolonies_classes_id == 4) include_once($global_path."intern/inc/g.inc.php");
		if ($this->ccolonies_classes_id == 5) include_once($global_path."intern/inc/k.inc.php");
		if ($this->ccolonies_classes_id == 6) include_once($global_path."intern/inc/d.inc.php");
		if ($this->ccolonies_classes_id == 7) include_once($global_path."intern/inc/h.inc.php");
		if ($this->ccolonies_classes_id == 8) include_once($global_path."intern/inc/x.inc.php");
		if ($this->ccolonies_classes_id == 9) include_once($global_path."intern/inc/j.inc.php");
		if ($this->ccolonies_classes_id == 10) include_once($global_path."intern/inc/r.inc.php");
		for ($i=0;$i<count($fields);$i++) $this->db->query("UPDATE stu_colonies_fields SET type=".$fields[$i].",buildings_id=0,integrity=0,aktiv=0 WHERE field_id=".$i." AND colonies_id=".$this->cid);
		unset($fields);
		if ($this->ccolonies_classes_id == 1) include_once($global_path."intern/inc/um.inc.php");
		if ($this->ccolonies_classes_id == 2) include_once($global_path."intern/inc/ul.inc.php");
		if ($this->ccolonies_classes_id == 3) include_once($global_path."intern/inc/un.inc.php");
		if ($this->ccolonies_classes_id == 4) include_once($global_path."intern/inc/ug.inc.php");
		if ($this->ccolonies_classes_id == 5) include_once($global_path."intern/inc/uk.inc.php");
		if ($this->ccolonies_classes_id == 7) include_once($global_path."intern/inc/uh.inc.php");
		if ($this->ccolonies_classes_id == 8) include_once($global_path."intern/inc/ux.inc.php");
		if ($this->ccolonies_classes_id == 10) include_once($global_path."intern/inc/ur.inc.php");
		for ($i=0;$i<count($fields);$i++) $this->db->query("UPDATE stu_colonies_underground SET type=".$fields[$i].",buildings_id=0,integrity=0,aktiv=0 WHERE field_id=".$i." AND colonies_id=".$this->cid);
		if ($bev > 0) $msg = "<br>".($bev*5)." Sympathie abgezogen";
		$this->db->query("DELETE FROM stu_sector_flights WHERE colonies_id=".$this->cid);
		$return[msg] = "Die Kolonie wurde gesprengt".$msg;
		return $return;
	}
	
	function ewopt($mode)
	{
		if (($mode != 1) && ($mode != 0))
		{
			$return[msg] = "Parameterfehler";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies SET ewopt=".$mode." WHERE id=".$this->cid." AND user_id=".$this->user);
		$return[msg] = "Einwanderungseinstellung geändert";
		return $return;
	}
	
	function beammsg($goods,$shipId,$way)
	{
		$shipdata = $this->db->query("SELECT name,user_id FROM stu_ships WHERE id='".$shipId."'",4);
		if ($shipdata[user_id] == $this->user) return 0;
		foreach($goods as $key => $value) if ($value[id]) $dummygood .= $value['count']."&nbsp;".$this->db->query("SELECT name FROM stu_goods WHERE id='".$value[id]."'",1)."<br>";
		$way == "to" ? $way = "zu" : $way = "von";
		$message = "Die Kolonie ".$this->cname." beamt in Sektor ".$this->ccoords_x."/".$this->ccoords_y." ".$way." der ".$shipdata[name].": ".$dummygood;
		global $myComm;
		$myComm->sendpm($shipdata[user_id],$this->user,$message,3);
	}
	
	function demontship($shipId)
	{
		global $myShip;
		$shipdata = $myShip->getdatabyid($shipId);
		if ($shipdata[user_id] != $this->user) return 0;
		if ($shipdata[coords_x] != $this->ccoords_x || $shipdata[coords_y] != $this->ccoords_y) return 0;
		$cost = $this->getshipcostbyid($shipdata[ships_rumps_id]);
		$pro = @(100/$shipdata[maxhuell])*$shipdata[huelle];
		/*if ($shipdata[ships_rumps_id] != 111)
		{
			$msg = "Die ".$shipdata[name]." wird demontiert... Es wurden folgende Ressourcen dabei gewonnen<br>";
			for ($i=0;$i<count($cost);$i++)
			{
				if ((($cost[$i][goods_id] == 3) || ($cost[$i][goods_id] == 6) || ($cost[$i][goods_id] == 9)) && (floor(($cost[$i]['count']/100)*$pro) > 0))
				{
					$this->upperstoragebygoodid(floor(($cost[$i]['count']/100)*$pro),$cost[$i][goods_id],$this->cid,$this->user);
					$msg .= floor(($cost[$i]['count']/100)*$pro)." ".$cost[$i][name]."<br>";
				}
			}
			$mod = 0;
			$module = $myShip->getmodulebyid($shipdata[huellmodlvl]);
			for ($i=0;$i<floor(($shipdata[c][huellmod]/100)*$pro);$i++)
			{
				if (rand(1,100) <= $module[demontchg])
				{
					$this->upperstoragebygoodid(1,$module[goods_id],$this->cid,$this->user);
					$mod++;
				}
			}
			if ($mod > 0) $msg .= $mod." ".$module[name]."<br>";
			$mod = 0;
			$module = $myShip->getmodulebyid($shipdata[schildmodlvl]);
			for ($i=0;$i<floor(($shipdata[c][schilmod]/100)*$pro);$i++)
			{
				if (rand(1,100) <= $module[demontchg])
				{
					$this->upperstoragebygoodid(1,$module[goods_id],$this->cid,$this->user);
					$mod++;
				}
			}
			if ($mod > 0) $msg .= $mod." ".$module[name]."<br>";
			$mod = 0;
			$module = $myShip->getmodulebyid($shipdata[epsmodlvl]);
			for ($i=0;$i<floor(($shipdata[c][epsmod]/100)*$pro);$i++)
			{
				if (rand(1,100) <= $module[demontchg])
				{
					$this->upperstoragebygoodid(1,$module[goods_id],$this->cid,$this->user);
					$mod++;
				}
			}
			if ($mod > 0) $msg .= $mod." ".$module[name]."<br>";
			$mod = 0;
			if ($shipdata[waffenmodlvl] > 0)
			{
				$module = $myShip->getmodulebyid($shipdata[waffenmodlvl]);
				for ($i=0;$i<floor(($shipdata[c][waffenmod]/100)*$pro);$i++)
				{
					if (rand(1,100) <= $module[demontchg])
					{
						$this->upperstoragebygoodid(1,$module[goods_id],$this->cid,$this->user);
						$mod++;
					}
				}
				if ($mod > 0) $msg .= $mod." ".$module[name]."<br>";
				$mod = 0;
			}
			if ($mod > 0) $msg .= $mod." ".$module[name]."<br>";
			$mod = 0;
			$module = $myShip->getmodulebyid($shipdata[sensormodlvl]);
			for ($i=0;$i<floor(($shipdata[c][sensormod]/100)*$pro);$i++)
			{
				if (rand(1,100) <= $module[demontchg])
				{
					$this->upperstoragebygoodid(1,$module[goods_id],$this->cid,$this->user);
					$mod++;
				}
			}
			if ($mod > 0) $msg .= $mod." ".$module[name]."<br>";
			$mod = 0;
			if ($shipdata[reaktormodlvl] > 0)
			{
				$module = $myShip->getmodulebyid($shipdata[reaktormodlvl]);
				if (rand(1,100) <= $module[demontchg] && round((1/100)*$pro) > 0)
				{
					$this->upperstoragebygoodid(1,$module[goods_id],$this->cid,$this->user);
					$msg .= "1 ".$module[name]."<br>";
				}
			}
			$module = $myShip->getmodulebyid($shipdata[antriebmodlvl]);
			if (rand(1,100) <= $module[demontchg] && round((1/100)*$pro) > 0)
			{
				$this->upperstoragebygoodid(1,$module[goods_id],$this->cid,$this->user);
				$msg .= "1 ".$module[name]."<br>";
			}
			$module = $myShip->getmodulebyid($shipdata[computermodlvl]);
			if (rand(1,100) <= $module[demontchg] && round((1/100)*$pro) > 0)
			{
				$this->upperstoragebygoodid(1,$module[goods_id],$this->cid,$this->user);
				$msg .= "1 ".$module[name]."<br>";
			}
			if ($this->db->query("SELECT id FROM stu_fleets WHERE user_id=".$this->user." AND ships_id=".$shipId,1) != 0)
			{
				global $myFleet;
				$myFleet->delfleet($shipId,$this->user);
			}
		}*/
		$msg = "Das Schiff wurde demontiert";
		$this->db->query("DELETE FROM stu_ships_storage WHERE ships_id='".$shipId."' AND user_id=".$this->user);
		$this->db->query("DELETE FROM stu_ships WHERE id='".$shipId."' AND user_id=".$this->user);
		$this->db->query("DELETE FROM stu_ships_action WHERE ships_id='".$shipId."' OR ships_id2='".$shipId."'");
		$this->db->query("UPDATE stu_ships SET dock=0 WHERE dock=".$shipId);
		$return[msg] = $msg;
		return $return;
	}
	
	function teleskop($coordsx,$coordsy)
	{
		if (!is_numeric($coordsx) || !is_numeric($coordsy)) return 0;
		global $myMap;
		$field = $myMap->getfieldbycoords($coordsx,$coordsy,$this->cwese);
		if ($field == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_orbit WHERE colonies_id=".$this->cid." AND buildings_id='53' AND buildtime=0",1) == 0)
		{
			$return[msg] = "Im Orbit dieses Planeten befindet sich kein fertiggestelltes Subraumteleskop";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 or buildings_id=168) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum scannen wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		if ($this->cenergie < 3)
		{
			$return[msg] = "Es werden mindestens 3 Energie benötigt - Vorhanden sind nur ".$this->cenergie;
			return $return;
		}
		$this->db->query("UPDATE stu_colonies SET energie=energie-3 WHERE id=".$this->cid);
		if ($field[type] == 15)
		{
			$return[msg] = "Dieser Sektor kann aufgrund des Quasars nicht gescant werden";
			return $return;
		}
		if ($field[type] == 31)
		{
			$return[msg] = "Dieser Sektor kann aufgrund des Mutaranebels nicht gescant werden";
			return $return;
		}
		if ($myMap->checksenjammer($coordsx,$coordsy,$this->cwese) != 0)
		{
			$return[msg] = "Der Scan dieses Sektors ist fehlgeschlagen";
			return $return;
		}
		if ($coordsx >=1 && $coordsx <=20 && $coordsy >=80 && $coordsy <=120 && $this->cwese == 1)
		{
			$return[msg] = "Dieser Sektor kann aufgrund von Subraumstörungen nicht gescant werden";
			return $return;
		}
		if ($coordsx >=1 && $coordsx <=25 && $coordsy >=1 && $coordsy <=25 && $this->cwese == 1)
		{
			$return[msg] = "Dieser Sektor kann aufgrund von Subraumstörungen nicht gescant werden";
			return $return;
		}
		global $myMap;
		return $myMap->getfieldships($coordsx,$coordsy,0,$this->cwese);
	}
	
	function checkbuilding($buildingId,$colId) { return $this->db->query("SELECT id FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildings_id='".$buildingId."' AND aktiv=1",3); }
	
	function getcolonybysektor($x,$y,$wese=1) { return $this->db->query("SELECT a.*,b.user FROM stu_colonies as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.coords_x='".$x."' AND a.coords_y='".$y."' AND a.wese=".$wese."",4); }
	
	function repairbuilding($mode,$fieldId)
	{
		if ($this->cshow == 0) return 0;
		if ($mode == "field")
		{
			$add = "fields";
			$fielddata = $this->getfielddatabyid($fieldId,$this->cid);
		}
		elseif ($mode == "orbit")
		{
			$add = "orbit";
			$fielddata = $this->getorbitfielddatabyid($fieldId,$this->cid);
		}
		elseif ($mode == "ground")
		{
			$add = "underground";
			$fielddata = $this->getgroundfielddatabyid($fieldId,$this->cid);
		}
		else return 0;
		$build = $this->getbuildbyid($fielddata[buildings_id]);
		if ($fielddata[integrity] == $build[integrity])
		{
			$return[msg] = "Dieses Gebäude ist nicht beschädigt";
			return $return;
		}
		$cost = $this->getbuildingcostbyid($build[id]);
		for ($i=0;$i<count($cost);$i++) {
			$count = $this->getcountbygoodid($cost[$i][goods_id],$this->cid);
			$rcost = ceil((($cost[$i]['count']/100)*((100/$build[integrity])*($build[integrity]-$fielddata[integrity]))));
			if ($count < $rcost)
			{
				$return[msg] = "Es wird ".$rcost." ".$cost[$i][name]." benötigt - Vorhanden ist nur ".$count;
				return $return;
			}
		}
		for ($i=0;$i<count($cost);$i++) $this->lowerstoragebygoodid($rcost,$cost[$i][goods_id],$this->cid);
		$this->db->query("UPDATE stu_colonies_".$add." SET integrity=".$build[integrity]." WHERE colonies_id=".$this->cid." AND field_id=".$fieldId);
		$return[msg] = $build[name]." auf Feld ".($fieldId+1)." repariert";
		return $return;
	}
	
	function coldefensefire($colId,$shipId,$damage)
	{
		global $myShip,$myHistory;
		$shipdata = $myShip->getdatabyid($shipId);
		if ($shipdata[schilde_aktiv] == 1 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$shipdata[schildmodlvl],1) == "Regenerativ") $damage = round($damage * 0.8);
		if ($shipdata[schilde_aktiv] == 0 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$shipdata[huellmodlvl],1) == "Ablativ") $damage = round($damage * 0.8);
		if ($shipdata[schilde_aktiv] == 0 && $this->db->query("SELECT besonder FROM stu_ships_modules WHERE id=".$shipdata[huellmodlvl],1) == "Reparatursystem") $damage = round($damage * 0.5);

		if ($shipdata[schilde_aktiv] == 1)
		{
			if ($shipdata[schilde] - $damage <= 0)
			{
				$huell = $shipdata[huelle] - ($damage - $shipdata[schilde]);
				$myShip->deactivatevalue($shipId,"schilde_aktiv",$shipdata[user_id]);
				if ($huell > 0)
				{
					$this->db->query("UPDATE stu_ships SET schilde=0 WHERE id='".$shipId."'");
					$msg .= "Schilde brechen zusammen - Huelle bei ".$huell;
				}
				else $msg .= "Hüllenbruch - Das Schiff wurde zerstört";
			}
			else
			{
				$schilde = $shipdata[schilde] - $damage;
				$this->db->query("UPDATE stu_ships SET schilde=".$schilde." WHERE id='".$shipId."'");
				$msg .= "Schilde bei ".$schilde;
				$huell = $shipdata[huelle];
			}
		}
		else
		{
			$huell = $shipdata[huelle] - $damage;
			if ($huell <= 0) $msg .= "Hüllenbruch - Das Schiff wurde zerstört";
			else
			{
				$this->db->query("UPDATE stu_ships SET huelle=".$huell." WHERE id=".$shipId);
				$msg .= "Hülle bei ".$huell;
			}
		}
		if ($huell < 1)
		{
			$myShip->trumfield($shipId);
			$coldata = $this->getcolonybyid($colId);
			$myHistory->addEvent("Die ".addslashes($shipdata[name])." wurde in Sektor ".$shipdata[coords_x]."/".$shipdata[coords_y].($shipdata[wese] == 2 ? " (2)" : "")." von der Orbitalverteidigung der Kolonie ".addslashes($coldata[name])." zerstört",$shipdata[user_id],1);
			$return[destroyed] = 1;
		}
		$return[msg] = $msg;
		return $return; 
	}

	function defendcolony($colId,$shipId)
	{
		$coldata = $this->getcolonybyid($colId);
		if ($coldata == 0) return 0;
		
		// if ($coldata[energie] == 0)
		// {
		// 	$return[msg] = "Energiemangel auf der Kolonie - Orbitalverteidigung offline";
		// 	return $return;
		// }

		global $myShip, $myHistory;
		$usede = 0;

		$result = $this->db->query("SELECT * FROM stu_colonies_orbit WHERE buildings_id=46 AND aktiv=1 AND colonies_id='".$colId."'");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$shipdata = $myShip->getdatabyid($shipId);
			if ($usede == $coldata[energie])
			{
				$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
				$return[msg] = $msg;
				return $return;
			}
			$data = mysql_fetch_assoc($result);
			$msg .= "Phaserbatterie  (Feld ".($data[field_id]+1).") schießt auf die ".$shipdata[name]."<br>";
			$fire = $this->coldefensefire($colId,$shipId,8);
			$msg .= $fire[msg]."<br>";
			$usede++;
			if ($fire[destroyed] == 1)
			{
				$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
				$return[msg] = $msg;
				return $return;
			}
		}

		$result = $this->db->query("SELECT * FROM stu_colonies_orbit WHERE (buildings_id=166 OR buildings_id=162) AND aktiv=1 AND colonies_id='".$colId."'");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$shipdata = $myShip->getdatabyid($shipId);
			if ($usede == $coldata[energie])
			{
				$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
				$return[msg] = $msg;
				return $return;
			}
			$data = mysql_fetch_assoc($result);
			$msg .= "Disruptorphalanx  (Feld ".($data[field_id]+1).") schießt auf die ".$shipdata[name]."<br>";
			$fire = $this->coldefensefire($colId,$shipId,12);
			$msg .= $fire[msg]."<br>";
			$usede++;
			if ($fire[destroyed] == 1)
			{
				$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
				$return[msg] = $msg;
				return $return;
			}
		}
		$result = $this->db->query("SELECT * FROM stu_colonies_orbit WHERE buildings_id=180 AND aktiv=1 AND colonies_id='".$colId."'");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$shipdata = $myShip->getdatabyid($shipId);
			if ($usede == $coldata[energie])
			{
				$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
				$return[msg] = $msg;
				return $return;
			}
			$data = mysql_fetch_assoc($result);
			$count = $this->getcountbygoodid(41,$colId);
			$shots = 4;
			if ($count['count'] > 0)
			{
				if ($count['count'] < $shots) $shots = $count['count'];
				for ($q=0;$q<$shots;$q++)
				{
					$msg .= "Raketenwerfer (Feld ".($data[field_id]+1).") schießt eine Rakete auf die ".$shipdata[name]."<br>";
					$fire = $this->coldefensefire($colId,$shipId,5);
					$msg .= $fire[msg]."<br>";
				}
				$this->lowerstoragebygoodid($shots,41,$colId);
				$usede++;
				if ($fire[destroyed] == 1)
				{
					$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
					$return[msg] = $msg;
					return $return;
				}
			}
			else $msg .= "Munition verbraucht - Raketenwerfer (Feld ".($data[field_id]+1).") offline<br>";
		}
		$result = $this->db->query("SELECT * FROM stu_colonies_orbit WHERE buildings_id=72 AND aktiv=1 AND colonies_id='".$colId."'");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$shipdata = $myShip->getdatabyid($shipId);
			if ($usede == $coldata[energie])
			{
				$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
				$return[msg] = $msg;
				return $return;
			}
			$data = mysql_fetch_assoc($result);
			$count = $this->getcountbygoodid(16,$colId);
			$shots = 3;
			if ($count['count'] > 0)
			{
				if ($count['count'] < $shots) $shots = $count['count'];
				for ($q=0;$q<$shots;$q++)
				{
					$msg .= "Orbitale Verteidigungsplattform (Feld ".($data[field_id]+1).") schießt einen Plasmatorpedo auf die ".$shipdata[name]."<br>";
					$fire = $this->coldefensefire($colId,$shipId,16);
					$msg .= $fire[msg]."<br>";
				}
				$this->lowerstoragebygoodid($shots,16,$colId);
				$usede++;
				if ($fire[destroyed] == 1)
				{
					$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
					$return[msg] = $msg;
					return $return;
				}
			}
			else $msg .= "Munition verbraucht - Orbitale Verteidigungsplattform (Feld ".($data[field_id]+1).") offline<br>";
		}
		$result = $this->db->query("SELECT buildings_id,aktiv,field_id FROM stu_colonies_orbit WHERE (buildings_id=48 OR buildings_id=49 OR buildings_id=50) AND aktiv=1 AND colonies_id='".$colId."'");
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$shipdata = $myShip->getdatabyid($shipId);
			if ($usede == $coldata[energie])
			{
				$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
				$return[msg] = $msg;
				return $return;
			}
			$data = mysql_fetch_assoc($result);
			if ($data[buildings_id] == 48)
			{
				$schaden = 12;
				$type = "Photonentorpedo";
				$good = 7;
			}
			elseif ($data[buildings_id] == 49)
			{
				$schaden = 16;
				$type = "Plasmatorpedo";
				$good = 16;
			}
			elseif ($data[buildings_id] == 50)
			{
				$schaden = 20;
				$type = "Quantentorpedo";
				$good = 17;
			}
			$count = $this->getcountbygoodid($good,$colId);
			if ($count['count'] > 0)
			{
				$msg .= "Torpedoplattform (Orbitfeld ".($data[field_id]+1).") schießt einen ".$type." auf die ".$shipdata[name]."<br>";
				$fire = $this->coldefensefire($colId,$shipId,$schaden);
				$msg .= $fire[msg]."<br>";
				$this->lowerstoragebygoodid(1,$good,$colId);
				$usede++;
				if ($fire[destroyed] == 1)
				{
					$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
					$return[msg] = $msg;
					return $return;
				}
			}
			else $msg .= "Munition verbraucht - Torpedoplattform (Feld ".($data[field_id]+1).") offline<br>";
		}

		$this->db->query("UPDATE stu_colonies SET energie=energie-".$usede." WHERE id='".$colId."'");
		$return[msg] = $msg;
		return $return;
	}
	
	function newkolozent()
	{
		if ($this->cmkolz == 1)
		{
			$this->db->query("UPDATE stu_colonies SET max_bev=max_bev+5,max_energie=max_energie+15,max_storage=max_storage+200,mkolz=0 WHERE id=".$this->cid);
			if ($this->ccolonies_classes_id == 1) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$this->cid);
			if ($this->ccolonies_classes_id == 2) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$this->cid);
			if ($this->ccolonies_classes_id == 3) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$this->cid);
			if ($this->ccolonies_classes_id == 4) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$this->cid);
			if ($this->ccolonies_classes_id == 5) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$this->cid);
			if ($this->ccolonies_classes_id == 6) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='17' AND colonies_id=".$this->cid);
			if ($this->ccolonies_classes_id == 7) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$this->cid);
			if ($this->ccolonies_classes_id == 8) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id=".$this->cid);
			if ($this->ccolonies_classes_id == 9) $this->db->query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='17' AND colonies_id=".$this->cid);
			$return[msg] = "Es wurde eine neue Koloniezentrale errichtet";
			return $return;
		}
	}
	
	function changebuildname($mode,$name,$fieldId)
	{
		if ($this->cshow == 0) return 0;
		if ($mode == "field")
		{
			$add = "fields";
			$data = $this->getfielddatabyid($fieldId,$this->cid);
		}
		elseif ($mode == "orbit")
		{
			$add = "orbit";
			$data = $this->getorbitfielddatabyid($fieldId,$this->cid);
		}
		elseif ($mode == "ground")
		{
			$add = "underground";
			$data = $this->getgroundfielddatabyid($fieldId,$this->cid);
		}
		else return 0;
		if ($data[buildings_id] == 0)
		{
			$return[msg] = "Leere Felder können nicht benannt werden";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies_".$add." SET name='".addslashes($name)."' WHERE field_id='".$fieldId."' AND colonies_id=".$this->cid);
		$return[msg] = "Der Name wurde geändert";
		return $return;	
	}
	
	function set_ewstop($count)
	{
		if ($this->cshow == 0) return 0;
		if ($this->cmax_bev < $count) $count = $this->cmax_bev;
		if ($count == "reset")
		{
			$this->db->query("UPDATE stu_colonies SET bev_stop_count=0,ewopt=1 WHERE user_id=".$this->user." AND id=".$this->cid);
			$return[msg] = "Die Einwanderungsgrenze wurde zurückgesetzt";
		}
		else
		{
			if (!is_numeric($count)) return 0;
			$this->db->query("UPDATE stu_colonies SET bev_stop_count=".$count." WHERE user_id=".$this->user." AND id=".$this->cid);
			$return[msg] = "Einwanderungsgrenze wurde auf ".$count." festgelegt";
		}
		return $return;
	}
	
	function setsperrung($mode)
	{
		if ($this->cshow == 0) return 0;
		$this->db->query("UPDATE stu_colonies SET sperrung='".$mode."' WHERE id=".$this->cid);
		if ($mode == 1) $return[msg] = "Der Sektor wurde gesperrt";
		else $return[msg] = "Die Sektorsperrung wurde aufgehoben";
		return $return;
	}
	
	function getmines($classId) { return $this->db->query("SELECT * FROM stu_colonies_classes WHERE id='".$classId."'",4); }
	
	function getwirtschaft($userId) { return $this->db->query("SELECT SUM(wirtschaft) FROM stu_colonies WHERE user_id=".$userId,1); }
	
	function checksperrung($coords_x,$coords_y,$wese) { return $this->db->query("SELECT sperrung FROM stu_colonies WHERE coords_x='".$coords_x."' AND coords_y='".$coords_y."' AND wese=".$wese,1); }
	
	function getflights() { return $this->db->query("SELECT a.user_id,a.ships_rumps_id,UNIX_TIMESTAMP(a.date) as date_tsp,b.user FROM stu_sector_flights as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.colonies_id=".$this->cid); }
	
	function getflightcount($colId) { return $this->db->query("SELECT COUNT(ships_rumps_id) FROM stu_sector_flights WHERE colonies_id=".$colId,1); }
	
	function shipupgrade($shipId,$upgradeId)
	{
		if ($this->cshow == 0) return 0;
		global $myShip;
		$shipdata = $myShip->getdatabyid($shipId);
		if ($shipdata == 0) return 0;
		if ($shipdata[coords_x] != $this->ccoords_x || $shipdata[coords_y] != $this->ccoords_y) return 0;
		if ($this->cwese != $shipdata[wese]) return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 OR buildings_id=168) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Für die Schiffsupgrades wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		if ($this->user != $shipdata[user_id])
		{
			$return[msg] = "Du kannst nur Deine eigenen Schiffe upgraden";
			return $return;
		}
		if ($upgradeId == 1)
		{
			if ($this->getuserresearch(111,$this->user) == 0)
			{
				$return[msg] = "Der Tachyon Emitter wurde noch nicht erforscht";
				return $return;
			}
			if ($shipdata[tachyon] == 1)
			{
				$return[msg] = "Auf diesem Schiff ist bereits ein Tachyon Emitter installiert";
				return $return;
			}
			if ($shipdata[c][tachyon] == 0)
			{
				$return[msg] = "Auf diesem Schiffstyp kann kein Tachyon Emitter installiert werden";
				return $return;
			}
			if ($this->db->query("SELECT id FROM stu_colonies_orbit WHERE ((buildings_id > 26 AND buildings_id < 31) OR buildings_id = 135) AND colonies_id=".$this->cid,1) == 0)
			{
				$return[msg] = "Für die Schiffsupgrades wird eine erweiterte Werft benötigt";
				return $return;
			}
			if ($this->cenergie < 10)
			{
				$return[msg] = "Es wird 10 Energie benötigt - Vorhanden ist nur ".$this->cenergie;
				return $return;
			}
			$good[bm] = $this->getstoragebygoodid(3,$this->cid);
			if ($good[bm] == 0 || $good[bm] < 10)
			{
				$return[msg] = "Es werden 10 Baumaterial benötigt - Vorhanden sind nur ".$good[bm];
				return $return;
			}
			$good[dura] = $this->getstoragebygoodid(6,$this->cid);
			if ($good[dura] == 0 || $good[dura] < 10)
			{
				$return[msg] = "Es werden 10 Duranium benötigt - Vorhanden sind nur ".$good[dura];
				return $return;
			}
			$good[iso] = $this->getstoragebygoodid(10,$this->cid);
			if ($good[iso] == 0 || $good[iso] < 5)
			{
				$return[msg] = "Es werden 5 Iso Chips benötigt - Vorhanden sind nur ".$good[iso];
				return $return;
			}
			$good[gel] = $this->getstoragebygoodid(19,$this->cid);
			if ($good[gel] == 0 || $good[gel] < 2)
			{
				$return[msg] = "Es werden 2 Gel Packs benötigt - Vorhanden sind nur ".$good[gel];
				return $return;
			}
			$this->db->query("UPDATE stu_ships SET tachyon=1 WHERE id=".$shipId);
			$this->db->query("UPDATE stu_colonies SET energie=energie-10 WHERE id=".$this->cid);
			$this->lowerstoragebygoodid(10,3,$this->cid);
			$this->lowerstoragebygoodid(10,6,$this->cid);
			$this->lowerstoragebygoodid(5,10,$this->cid);
			$this->lowerstoragebygoodid(2,19,$this->cid);
			$return[msg] = "Auf der ".$shipdata[name]." wurde ein Tachyon Emitter installiert";
			return $return;
		}
	}
	
	function loadshields($count)
	{
		if ($this->cshow == 0) return 0;
		if ($count <= 0 && $count != "max") return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=51 OR buildings_id=81) AND colonies_id=".$this->cid,1) == 0)
		{
			$return[msg] = "Es ist kein planetarer Schildemitter installiert";
			return $return;
		}
		if ($this->cschilde_aktiv == 1)
		{
			$return[msg] = "Die Schilde können nicht aufgeladen werden wenn sie aktiviert sind";
			return $return;
		}
		if ($this->cschilde == $this->cmax_schilde)
		{
			$return[msg] = "Die planetaren Schilde sind bereits vollständig aufgeladen";
			return $return;
		}
		if ($count == "max") $count = $this->cenergie;
		if ($this->cenergie < $count) $count = $this->cenergie;
		if ($this->cmax_schilde-$this->cschilde < $count) $count = $this->cmax_schilde-$this->cschilde;
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$count.",schilde=schilde+".$count." WHERE id=".$this->cid);
		$return[msg] = "Die planetaren Schilde wurden um ".$count." Einheiten aufgeladen";
		return $return;
	}
	
	function setshieldfreq($freq1,$freq2)
	{
		if ($this->cshow == 0) return 0;
		if (!is_numeric($freq1) || !is_numeric($freq2) || (strlen($freq1) != 2) || ($freq1 < 10) || (strlen($freq2) > 1))
		{
			$return[msg] = "Es wurde eine ungültige Schildfrequenz eingegeben";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=51 OR buildings_id=81) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Es befindet sich kein aktivierter planetarer Schildemitter auf dem Planeten";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies SET schild_freq1='".$freq1."',schild_freq2=".$freq2." WHERE id=".$this->cid);
		$return[msg] = "Die neue Schildfrequenz beträgt ".$freq1.",".$freq2."";
		return $return;
	}
	
	function activateshields()
	{
		if ($this->cshow == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=51 OR buildings_id=81) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Es befindet sich kein aktivierter planetarer Schildemitter auf dem Planeten";
			return $return;
		}
		if ($this->cenergie < 15)
		{
			$return[msg] = "Zum aktivieren der Schilde werden 15 Energie benötigt";
			return $return;
		}
		if ($this->cschilde == 0)
		{
			$return[msg] = "Die planetaren Schilde sind nicht aufgeladen";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies SET schilde_aktiv=1,energie=energie-15 WHERE id=".$this->cid);
		$return[msg] = "Die planetaren Schilde wurden aktiviert";
		return $return;
	}
	
	function deactivateshields()
	{
		if ($this->cshow == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=51 OR buildings_id=81) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Es befindet sich kein aktivierter planetarer Schildemitter auf dem Planeten";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies SET schilde_aktiv=0 WHERE id=".$this->cid);
		$return[msg] = "Die planetaren Schilde wurden deaktiviert";
		return $return;
	}
	
	function modulateshields($colId)
	{
		$data = $this->getcolonybyid($colId);
		if ($data == 0) return 0;
		if ($this->checkbuilding(81,$colId) == 0) return 0;
		if ($data[schilde_aktiv] == 0) return 0;
		$freq1 = rand(10,99);
		$freq2 = rand(1,9);
		$this->db->query("UPDATE stu_colonies SET schild_freq1=".$freq1.",schild_freq2=".$freq2." WHERE id=".$colId);
		global $myComm;
		$myComm->sendpm($data[user_id],2,"Auf der Kolonie ".$data[name]." wurde die Schildfrequenz moduliert. Die neue Frequenz beträgt nun ".$freq1.",".$freq2,1);
	}

	function decloakgeb($colId)
	{
		global $myUser;
		$data = $this->getcolonybyid($colId);
		if ($this->cshow == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_colonies_orbit WHERE colonies_id=".$data[id]." AND buildings_id=84",1) == 0)
		{
			$return[msg] = "Auf dieser Kolonie ist kein Sensornetz installiert";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 OR buildings_id=168) AND colonies_id=".$colId." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum Scannen wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		if ($this->cenergie < 12)
		{
			$return[msg] = "Es wird mindestens 12 Energie zum Scan benötigt";
			return $return;
		}
		global $myComm;
		$j=0;
		$chance = 15;
		for ($k=($data[coords_y]-1);$k<($data[coords_y]+2);$k++)
		{
			for ($l=($data[coords_x]-1);$l<($data[coords_x]+2);$l++)
			{
				$result2 = $this->db->query("SELECT a.id,a.user_id,a.name,a.coords_x,a.coords_y,a.huelle,a.huellmodlvl,b.huellmod FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.coords_x=".$l." AND a.coords_y=".$k." AND a.cloak=1 AND a.user_id!=".$this->user);
				for ($i=0;$i<mysql_num_rows($result2);$i++)
				{
					$rand = rand(1,100);
					$shipdat = mysql_fetch_assoc($result2);
					$chance2 = $chance * round(sqrt(($this->db->query("SELECT huell FROM stu_ships_modules WHERE id=".$shipdat[huellmodlvl],1)*$shipdat[huellmod])/$shipdat[huelle]),1);
					if (($k == $data[coords_y]) && ($l == $data[coords_x])) $chance2 = 2 * $chance2;
					if ($rand <= $chance2)
					{
						if ($this->db->query("SELECT ships_id FROM stu_ships_uncloaked WHERE ships_id = ".$shipdat[id],1) == 0)
						{
							$this->db->query("INSERT INTO stu_ships_uncloaked (user_id,ships_id) VALUES ('".$this->user."','".$shipdat[id]."')");
							$myComm->sendpm($shipdat[user_id],2,"Die ".$shipdat[name]." wurde in Sektor ".$shipdat[coords_x]."/".$shipdat[coords_y]." von User ".$myUser->getfield("user",$this->user)." durch eine Sensorabtastung enttarnt",2);
							$j++;
						}
					}
				}
				$chance2 = 0;
			}
		}
		$this->db->query("UPDATE stu_colonies SET energie=energie-12 WHERE id=".$data[id]);
		$return[msg] = "Durch den Scan wurden ".$j." Schiffe entdeckt";
		return $return;
	}
	
	function horchposten($coordsx,$coordsy,$wese=1)
	{
		if ($this->cshow == 0) return 0;
		global $myMap;
		global $myUser;
		if ($this->db->query("SELECT id FROM stu_colonies_orbit WHERE colonies_id='".$this->cid."' AND buildings_id='102'",1) == 0)
		{
			$return[msg] = "Im Orbit dieses Planeten befindet sich kein Horchposten";
			return $return;
		}
		if ($myUser->urasse != 2) 
		{
			$return[msg] = "Kann diese Technologie nicht benutzen";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE (buildings_id=21 OR buildings_id=168) AND colonies_id=".$this->cid." AND aktiv=1",1) == 0)
		{
			$return[msg] = "Zum Scannen wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		if ($this->cenergie < 6)
		{
			$return[msg] = "Zum Scannen wird 6 Energie benötigt";
			return $return;
		}
		if (abs($this->ccoords_x - $coordsx) + abs($this->ccoords_y - $coordsy) >= 40)
		{
			$return[msg] = "Die Scanreichweite des Horchpostens beträgt 40 Sektoren";
			return $return;
		}
		global $mapfields,$grafik;
		$return[code] = 1;
		$x1 = $coordsx - 4;
		$x2 = $coordsx + 4;
		$y1 = $coordsy - 4;
		$y2 = $coordsy + 4;
		if ($x1<1)
		{
			$x1=1;
			$x2=$coordsx+4;
		}
		if ($x2>$mapfields[max_x]) $x2=$mapfields[max_x];
		if ($y1<1)
		{
			$y1=1;
			$y2=$coordsy+4;
		}
		if ($y2>$mapfields[max_y]) $y2=$mapfields[max_y];
		$map .= "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain x/y></td>";
		for ($i=$x1;$i<=$x2;$i++) $map .= "<td class=tdmain>".$i."</td>";
		$map .= "</tr>";
		for ($i=$y1;$i<=$y2;$i++)
		{
			$map .= "<tr><td class=tdmain>".$i."</td>";
			for ($j=$x1;$j<=$x2;$j++)
			{
				$fielddata = $myMap->getfieldinfo($j,$i,$wese);
				$fieldtype = $myMap->getfieldbycoords($j,$i,$wese);
				$fielddata != 0 ? $fieldinf = "<b><font color=#ffffff>".count($fielddata)."</font></b>" : $fieldinf = "&nbsp;";
				if ($fieldtype == 15) $fieldinf = "<strong><font color=#ffffff>X</font></strong>";
				$map .= "<td width=29 height=30 border=1 background=".$grafik."/map/".$fieldtype[type].".gif><table align=center width=30 height=30><tr><td align=center>".$fieldinf."</td></tr></table></td>";
			}
			$map .= "</tr>";
		}
		$map .= "</table>";
		$return[msg] = $map;
		$this->db->query("UPDATE stu_colonies SET energie=energie-6 WHERE id=".$this->cid);
		return $return;
	}

	function finishProcesses()
	{
		if ($this->db->query("SELECT value FROM stu_game WHERE fielddescr='proceed_user'",1) != $this->user) return 0;
		$this->db->query("UPDATE stu_game SET value='0' WHERE fielddescr='proceed_user'");
		global $myComm;
		$result = $this->db->query("SELECT a.id,a.buildings_id,a.colonies_id,a.field_id,a.aktiv,b.user_id,b.name FROM stu_colonies_fields as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE a.buildtime>0 AND (a.buildtime<".time().")");
		while($data=mysql_fetch_assoc($result))
		{
			$building = $this->getbuildbyid($data[buildings_id]);
			$this->db->query("UPDATE stu_colonies_fields SET buildtime=0,aktiv=0 WHERE id=".$data[id]);
			if ($data[buildings_id] != 4 && $data[buildings_id] != 218 && $data[buildings_id] != 10 && $data[aktiv] == 0) $act = $this->activateBuilding($data[field_id],$data[colonies_id],$data[user_id]);
			if ($data[buildings_id] == 38)
			{
				if ($act[code] == 1) $aktiv = ",aktiv=1";
				$build2 = $this->getbuildbyid(39);
				$this->db->query("UPDATE stu_colonies_underground SET buildings_id=39".$aktiv.",integrity=".$build2[integrity]." WHERE field_id=13 ANd colonies_id=".$data[colonies_id]);
			}
			if ($data[buildings_id] == 218)
			{
				$myComm->sendpm($data[user_id],2,"Der Auftrag an Bord des abgestürzten Jem hadar Schiffes wurde abgeschlossen",4,$data[buildtime]);
			}
			else
			{
				$myComm->sendpm($data[user_id],2,"Auf der Kolonie ".addslashes(stripslashes($data[name]))." wurde ein Gebäude auf der Oberfläche fertiggestellt (".$building[name].")",4,$data[buildtime]);
			}
		}
		$result = $this->db->query("SELECT a.id,a.buildings_id,a.colonies_id,a.field_id,a.aktiv,b.user_id,b.name FROM stu_colonies_orbit as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE a.buildtime>0 AND (a.buildtime<".time().")");
		while($data=mysql_fetch_assoc($result))
		{
			$building = $this->getbuildbyid($data[buildings_id]);
			$this->db->query("UPDATE stu_colonies_orbit SET buildtime=0,aktiv=0 WHERE id=".$data[id]);
			if ($data[aktiv] == 0) $this->activateOrbitBuilding($data[field_id],$data[colonies_id],$data[user_id]);
			$myComm->sendpm($data[user_id],2,"Auf der Kolonie ".addslashes(stripslashes($data[name]))." wurde ein Gebäude im Orbit fertiggestellt (".$building[name].")",4,$data[buildtime]);
		}
		$result = $this->db->query("SELECT a.id,a.buildings_id,a.colonies_id,a.field_id,a.aktiv,b.user_id,b.name FROM stu_colonies_underground as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE a.buildtime>0 AND (a.buildtime<".time().")");
		while($data=mysql_fetch_assoc($result))
		{
			$building = $this->getbuildbyid($data[buildings_id]);
			$this->db->query("UPDATE stu_colonies_underground SET buildtime=0,aktiv=0 WHERE id=".$data[id]);
			if ($data[aktiv] == 0) $this->activateGroundBuilding($data[field_id],$data[colonies_id],$data[user_id]);
			$myComm->sendpm($data[user_id],2,"Auf der Kolonie ".addslashes(stripslashes($data[name]))." wurde ein Gebäude im Untergrund fertiggestellt (".$building[name].")",4,$data[buildtime]);
		}
		$result = $this->db->query("SELECT a.*,b.name,b.coords_x,b.coords_y FROM stu_ships_buildprogress as a LEFT JOIN stu_colonies as b ON a.colonies_id=b.id WHERE a.buildtime<=".time()." AND a.colonies_id>0");
		while($data=mysql_fetch_assoc($result))
		{
			$this->db->query("INSERT INTO stu_ships (name,ships_rumps_id,user_id,huelle,coords_x,coords_y,huellmodlvl,sensormodlvl,waffenmodlvl,schildmodlvl,reaktormodlvl,antriebmodlvl,computermodlvl,epsmodlvl,points,wese) VALUES ('Noname','".$data[ships_rumps_id]."','".$data[user_id]."','".$data[huelle]."','".$data[coords_x]."','".$data[coords_y]."','".$data[huellmodlvl]."','".$data[sensormodlvl]."','".$data[waffenmodlvl]."','".$data[schildmodlvl]."','".$data[reaktormodlvl]."','".$data[antriebmodlvl]."','".$data[computermodlvl]."','".$data[epsmodlvl]."','".$data[points]."','".$data[wese]."')");
			$myComm->sendpm($data[user_id],2,"In der Werft auf der Kolonie ".$data[name]." wurde ein Schiff fertiggestellt",4,$data[buildtime]);
			$this->db->query("DELETE FROM stu_ships_buildprogress WHERE id=".$data[id]);
		}
		$data = $this->db->query("SELECT * FROM stu_ships_buildprogress WHERE buildtime<=".time()." AND ships_id>0",2);
		if ($data != 0)
		{
			global $myShip;
			for ($i=0;$i<count($data);$i++)
			{
				$this->db->query("DELETE FROM stu_ships_buildprogress WHERE id=".$data[$i][id]);
				$ship = $myShip->getdatabyid($data[$i][ships_id]);
				$class = $myShip->getclassbyid($data[$i][ships_rumps_id]);
				$this->db->query("UPDATE stu_ships SET ships_rumps_id=".$data[$i][ships_rumps_id].",user_id=".$data[$i][user_id].",huelle=".$data[$i][huelle].",huellmodlvl=".$data[$i][huellmodlvl].",sensormodlvl=".$data[$i][sensormodlvl].",waffenmodlvl=".$data[$i][waffenmodlvl].",schildmodlvl=".$data[$i][schildmodlvl].",reaktormodlvl=".$data[$i][reaktormodlvl].",computermodlvl=".$data[$i][computermodlvl].",epsmodlvl=".$data[$i][epsmodlvl].",points=".$data[$i][points].",energie=0 WHERE id=".$data[$i][ships_id]);
				$myComm->sendpm($data[$i][user_id],2,"In Sektor ".$ship[coords_x]."/".$ship[coords_y]." wurde der Bau einer Station (".$class[name].") abgeschlossen",2,$data[$i][buildtime]);
				$this->db->query("DELETE FROM stu_ships_storage WHERE ships_id=".$data[$i][ships_id]);
			}
		}
		$result = $this->db->query("SELECT a.ships_id,b.user_id,b.name FROM stu_ships_action as a LEFT JOIN stu_ships as b ON a.ships_id=b.id WHERE a.mode='deact' AND a.ships_id2<".time());
		while($data=mysql_fetch_assoc($result))
		{
			$myComm->sendpm($data[user_id],2,"Die Kommunikationsverbindung zur ".addslashes(stripslashes($data[name]))." wurde wieder hergestellt",2);
			$this->db->query("UPDATE stu_ships SET deact=0 WHERE id=".$data[ships_id]);
		}
		$this->db->query("DELETE FROM stu_ships_action WHERE (mode='lsendef' OR mode='ksendef' OR mode='shidef' OR mode='readef' OR mode='waffdef' OR mode='cloakdef' OR mode='deact') AND ships_id2<".time());
	}
	
	function getShipModules($classId)
	{
		if ($this->db->query("SELECT ships_rumps_id FROM stu_ships_build WHERE user_id='".$this->user."' AND ships_rumps_id='".$classId."'",1) == 0)
		{
			$return[msg] = "Du darfst diesen Schiffstyp nicht bauen";
			return $return;
		}
		global $myShip;
		$ship = $myShip->getclassbyid($classId);
		$tmp = $this->db->query("SELECT * FROM stu_ships_modules WHERE type=1 AND lvl >= ".$ship[huellmod_min]." AND lvl <= ".$ship[huellmod_max]." ORDER BY lvl",2);
		for ($i=0;$i<count($tmp);$i++)
		{
			if (($tmp[$i][research_id] == 0) || ($this->getuserresearch($tmp[$i][research_id],$this->user) == 1))
			{
				$data[huelle][$i] = $tmp[$i];
				$data[huelle][$i][c] = $ship[huellmod];
			}
		}
		$tmp = $this->db->query("SELECT * FROM stu_ships_modules WHERE type=2 AND lvl >= ".$ship[computermod_min]." AND lvl <= ".$ship[computermod_max]." ORDER BY lvl",2);
		for ($i=0;$i<count($tmp);$i++)
		{
			if (($tmp[$i][research_id] == 0) || ($this->getuserresearch($tmp[$i][research_id],$this->user) == 1))
			{
				$data[computer][] = $tmp[$i];
				$data[computer][$i][c] = 1;
			}
		}
		$tmp = $this->db->query("SELECT * FROM stu_ships_modules WHERE type=3 AND lvl >= ".$ship[schildmod_min]." AND lvl <= ".$ship[schildmod_max]." ORDER BY lvl",2);
		for ($i=0;$i<count($tmp);$i++)
		{
			if (($tmp[$i][research_id] == 0) || ($this->getuserresearch($tmp[$i][research_id],$this->user) == 1))
			{
				$data[schilde][] = $tmp[$i];
				$data[schilde][$i][c] = $ship[schildmod];
			}
		}
		if ($ship[waffenmod_max] != 0)
		{
			$tmp = $this->db->query("SELECT * FROM stu_ships_modules WHERE type=4 AND lvl >= ".$ship[waffenmod_min]." AND lvl <= ".$ship[waffenmod_max]." ORDER BY lvl",2);
			for ($i=0;$i<count($tmp);$i++)
			{
				if (($tmp[$i][research_id] == 0) || ($this->getuserresearch($tmp[$i][research_id],$this->user) == 1)){
					$data[waffen][] = $tmp[$i];
					$data[waffen][$i][c] = $ship[waffenmod];
				}
			}
		}
		$tmp = $this->db->query("SELECT * FROM stu_ships_modules WHERE type=5 AND lvl >= ".$ship[antriebsmod_min]." AND lvl <= ".$ship[antriebsmod_max]." ORDER BY lvl",2);
		for ($i=0;$i<count($tmp);$i++)
		{
			if (($tmp[$i][research_id] == 0) || ($this->getuserresearch($tmp[$i][research_id],$this->user) == 1)){
				$data[antrieb][] = $tmp[$i];
				$data[antrieb][$i][c] = 1;
			}
		}
		$tmp = $this->db->query("SELECT * FROM stu_ships_modules WHERE type=6 AND lvl >= ".$ship[epsmod_min]." AND lvl <= ".$ship[epsmod_max]." ORDER BY lvl",2);
		for ($i=0;$i<count($tmp);$i++)
		{
			if (($tmp[$i][research_id] == 0) || ($this->getuserresearch($tmp[$i][research_id],$this->user) == 1))
			{
				$data[eps][] = $tmp[$i];
				$data[eps][$i][c] = $ship[epsmod];
			}
		}
		$tmp = $this->db->query("SELECT * FROM stu_ships_modules WHERE type=7 AND lvl >= ".$ship[sensormod_min]." AND lvl <= ".$ship[sensormod_max]." ORDER BY lvl",2);
		for ($i=0;$i<count($tmp);$i++)
		{
			if (($tmp[$i][research_id] == 0) || ($this->getuserresearch($tmp[$i][research_id],$this->user) == 1))
			{
				$data[sensor][] = $tmp[$i];
				$data[sensor][$i][c] = $ship[sensormod];
			}
		}
		$tmp = $this->db->query("SELECT * FROM stu_ships_modules WHERE type=8 AND lvl >= ".$ship[reaktormod_min]." AND lvl <= ".$ship[reaktormod_max]." ORDER BY lvl",2);
		for ($i=0;$i<count($tmp);$i++)
		{
			if (($tmp[$i][research_id] == 0) || ($this->getuserresearch($tmp[$i][research_id],$this->user) == 1))
			{
				$data[reaktor][] = $tmp[$i];
				$data[reaktor][$i][c] = 1;
			}
		}
		return $data;
	}
	
	function checkModules($huellmod,$schildmod,$waffenmod,$epsmod,$computermod,$antriebmod,$sensormod,$reaktormod,$classId,$colId,$userId)
	{
		global $myShip;
		$class = $myShip->getclassbyid($classId);
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
		$stor = $this->checkstoragebygoodid($class[huellmod],$module[goods_id],$colId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Hüllenmodul befindet sich nicht auf dem Planeten";
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
		$stor = $this->checkstoragebygoodid($class[schildmod],$module[goods_id],$colId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Schildmodul befindet sich nicht auf dem Planeten";
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
			$stor = $this->checkstoragebygoodid($class[waffenmod],$module[goods_id],$colId);
			if ($stor == 0)
			{
				$return[msg] = "Dieses Waffenmodul befindet sich nicht auf dem Planeten";
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
		$stor = $this->checkstoragebygoodid($class[epsmod],$module[goods_id],$colId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses EPS-Gittermodul befindet sich nicht auf dem Planeten";
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
		$stor = $this->checkstoragebygoodid(1,$module[goods_id],$colId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Computermodul befindet sich nicht auf dem Planeten";
			$return[code] = 0;
			return $return;
		}
		if ($antriebmod == 0)
		{
			$return[msg] = "Es wurde kein Antriebsmodul ausgewählt";
			$return[code] = 0;
			return $return;
		}
		$module = $this->getmodulebyid($antriebmod);
		if (($module[lvl] > $class[antriebsmod_max]) || ($module[lvl] < $class[antriebsmod_min]))
		{
			$return[msg] = "Dieses Antriebsmodul kann in den gewählten Schiffsrumpf nicht eingebaut werden";
			$return[code] = 0;
			return $return;
		}
		$stor = $this->checkstoragebygoodid(1,$module[goods_id],$colId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Antriebsmodul befindet sich nicht auf dem Planeten";
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
		$stor = $this->checkstoragebygoodid($class[sensormod],$module[goods_id],$colId);
		if ($stor == 0)
		{
			$return[msg] = "Dieses Sensorenmodul befindet sich nicht auf dem Planeten";
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
			$stor = $this->checkstoragebygoodid(1,$module[goods_id],$colId);
			if ($stor == 0)
			{
				$return[msg] = "Dieses Reaktormodul befindet sich nicht auf dem Planeten";
				return $return;
			}
		}
		$return[code] = 1;
		return $return;
	}
	
	function checkstoragebygoodid($count,$goodId,$colId) { return $this->db->query("SELECT goods_id FROM stu_colonies_storage WHERE count>=".$count." AND goods_id=".$goodId." AND colonies_id=".$colId,3); }
	
	function lowerstoragebygoodid($count,$goodId,$colId)
	{
		$result = $this->db->query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE count>".$count." AND goods_id=".$goodId." AND colonies_id=".$colId,6);
		if ($result == 0) $this->db->query("DELETE FROM stu_colonies_storage WHERE goods_id=".$goodId." AND colonies_id=".$colId);
		return 1;
	}
	
	function upperstoragebygoodid($count,$goodId,$colId,$userId)
	{
		$result = $this->db->query("UPDATE stu_colonies_storage SET count=count+".$count." WHERE goods_id=".$goodId." AND colonies_id=".$colId,6);
		if ($result == 0) $this->db->query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','".$goodId."','".$count."')");
	}
	
	function getProgressShips($colId) { return $this->db->query("SELECT ships_rumps_id,buildtime FROM stu_ships_buildprogress WHERE colonies_id=".$colId,4); }
	
	function getunfinishedprocesses($colId)
	{
		$fdata = $this->db->query("SELECT buildings_id,buildtime,type FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildtime>0 ORDER BY buildtime",2);
		if ($fdata != 0) $data = $fdata;
		$odata = $this->db->query("SELECT buildings_id,buildtime,type FROM stu_colonies_orbit WHERE colonies_id=".$colId." AND buildtime>0 ORDER BY buildtime",2);
		if ($odata != 0)
		{
			if ($data) $data = array_merge($data,$odata);
			else $data = $odata;
		}
		$gdata = $this->db->query("SELECT buildings_id,buildtime,type FROM stu_colonies_underground WHERE colonies_id=".$colId." AND buildtime>0 ORDER BY buildtime",2);
		if ($gdata != 0)
		{
			if ($data) $data = array_merge($data,$gdata);
			else $data = $gdata;
		}
		return $data;
	}
	
	function getunfinishedshipprocesses($userId) { return $this->db->query("SELECT a.ships_rumps_id,a.colonies_id,a.buildtime,b.name,b.secretimage FROM stu_ships_buildprogress as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE a.user_id=".$userId); }
	
	function chbam($fieldId,$mode)
	{
		$mode == 1 ? $m = 0 : $m = 2;
		$this->db->query("UPDATE stu_colonies_fields SET aktiv=".$m." WHERE colonies_id=".$this->cid." AND field_id=".$fieldId);
		$return[msg] = "Aktivierungseinstellung für das Gebäude auf Feld ".($fieldId+1)." geändert";
		return $return;
	}
	
	function chobam($fieldId,$mode)
	{
		$mode == 1 ? $m = 0 : $m = 2;
		$this->db->query("UPDATE stu_colonies_orbit SET aktiv=".$m." WHERE colonies_id=".$this->cid." AND field_id=".$fieldId);
		$return[msg] = "Aktivierungseinstellung für das Gebäude auf Orbitfeld ".($fieldId+1)." geändert";
		return $return;
	}
	
	function chubam($fieldId,$mode)
	{
		$mode == 1 ? $m = 0 : $m = 2;
		$this->db->query("UPDATE stu_colonies_underground SET aktiv=".$m." WHERE colonies_id=".$this->cid." AND field_id=".$fieldId);
		$return[msg] = "Aktivierungseinstellung für das Gebäude auf Untergrundfeld ".($fieldId+1)." geändert";
		return $return;
	}
	
	function setdaytime()
	{
		$result = $this->db->query("SELECT id,dn_nextchange,dn_mode,dn_duration FROM stu_colonies WHERE ( dn_nextchange<".time()." AND dn_nextchange > 0)");
		if (mysql_num_rows($result) == 0) return 0;
		while($data=mysql_fetch_assoc($result))
		{
			$data[dn_mode] == 1 ? $nm = 2 : $nm = 1;
			$data[dn_nextchange] == 0 ? $nchg = time()+$data[dn_duration] : $nchg = $data[dn_nextchange]+$data[dn_duration];
			$this->db->query("UPDATE stu_colonies SET dn_mode=".$nm.",dn_nextchange=".$nchg." WHERE id=".$data[id]);
		}
	}
	
	function getgoodstorbyid($colId,$goodId) { return $this->db->query("SELECT count FROM stu_colonies_storage WHERE colonies_id=".$colId." ANd goods_id=".$goodId,1); }
	
	function makelatinum($colId,$count)
	{
		if ($this->cshow == 0)
		{
			$return[msg] = "Dies ist nicht Deine Kolonie";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_fields WHERE buildings_id=157 AND colonies_id=".$colId." ANd buildtime=0",1) == 0) $return[msg] = "Auf dieser Kolonie befindet sich keine fertiggestellte Latinumpresse";
		if ($this->cenergie < 10) $return[msg] = "Zum Pressen werden mindestens 10 Energie benötigt - Vorhanden sind jedoch nur ".$this->cenergie;
		$stor = $this->getgoodstorbyid($colId,34);
		if ($stor < 60) $return[msg] = "Zum Pressen werden mindestens 60 Ring-Materie benötigt. Vorhanden sind jedoch nur ".$stor;
		$storbm = $this->getgoodstorbyid($colId,3);
		if ($storbm < 1) $return[msg] = "Zum Pressen wird mindestens 1 Baumaterial benötigt. Vorhanden sind jedoch nur ".$storbm;
		if ($return) return $return;
		global $user;
		if ($count*10 > $this->cenergie) $count = floor($this->cenergie/10);
		if ($count*60 > $stor) $count = floor($stor/60);
		if ($count > $storbm) $count = $storbm;
		$this->lowerstoragebygoodid($count*60,34,$colId);
		$this->lowerstoragebygoodid($count,3,$colId);
		$this->upperstoragebygoodid($count,24,$colId,$user);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".($count*10)." WHERE id=".$colId);
		$return[msg] = ($count*60)." Ringmaterie zu ".$count." Latinum gepresst - ".($count*10)." Energie und ".$count." Baumaterial verbraucht";
		return $return;
	}
	
	function getcolstoragesum() { return $this->db->query("SELECT SUM(count) as gcount,goods_id FROM stu_colonies_storage WHERE user_id=".$this->user." GROUP BY goods_id ORDER BY goods_id",2); }
	
	function rendercolony($colId,$link)
	{
		if ($this->db->query("SELECT id FROM stu_colonies WHERE id=".$colId,1) == 0) return 0;
		global $grafik;
		if ($link == 2)
		{
			global $id;
			$shipId = $id;
		}
		$col = $this->getcolonybyid($colId);
		if ($col[dn_mode] == 2 && $col[colonies_classes_id] != 9) $n = "n/";
		$result = $this->db->query("SELECT a.field_id,a.type,a.buildings_id,a.aktiv,a.name,a.buildtime,b.name as bname, b.secretimage FROM stu_colonies_orbit as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.colonies_id=".$colId." ORDER BY a.field_id");
		$map = "<table cellspacing=1 cellpadding=1><tr>";
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$data = mysql_fetch_assoc($result);
			$data[buildtime] > 0 ? $id = 106 : $id = $data[buildings_id];
			if ($data[name] != "") $alt = "- ".$data[name]." ";
			if ($data[aktiv] == 0 && $link == 1) $alt .= "(offline)";
			elseif ($data[aktiv] == 1 && $link == 1) $alt .= "(online)";
			if ($data[buildtime] > 0 && $link == 1) $alt .= " (im Bau bis ".date("d.m H:i",$data[buildtime]).")";

			if ($data[buildings_id] == 0)
			{
				$img = "<img src=".$grafik."/fields/".$data[type].".gif border=0>";
			}
			elseif ($data[secretimage] != "0")
			{
				$img = "<img src=http://www.stuniverse.de/gfx/secret/".$data[secretimage]."_".$data[type].".gif border=0 title='".$data[bname]." ".$alt."'>";
			}
			else
			{
				$img = "<img src=".$grafik."/buildings/".$id."_".$data[type].".gif border=0 title='".$data[bname]." ".$alt."'>";
			}
			$data[aktiv] == 1 && $link == 1 ? $border = " style='border: 1px solid #9BA4A4'" : $border = " style='border: 1px solid #505656'";
			if ($link == 1) $map .= "<td class=collist width=30 align=center".$border."><a href=main.php?page=colony&section=orbitfield&field=".$data[field_id]."&id=".$colId.">".$img."</a></td>";
			elseif ($link == 2)
			{
				global $freq1,$freq2;
				if ($data[buildings_id] == 0) $map .= "<td class=collist width=30 align=center".$border.">".$img."</td>";
				else $map .= "<td class=collist width=30 align=center".$border."><a href=?page=ship&section=scan&mode=col&action=attack&type=orbit&field=".$data[field_id]."&id=".$shipId."&id2=".$colId."&freq1=".$freq1."&freq2=".$freq2.">".$img."</a></td>";
			}
			else $map .= "<td class=collist width=30 align=center".$border.">".$img."</td>";
			if ((($col[colonies_classes_id] == 6) || ($col[colonies_classes_id] == 9) && ($col[colonies_classes_id] != 11)) && (($i == 6) || ($i == 13))) $map .= "</tr><tr>";
			if ((($col[colonies_classes_id] != 6) && ($col[colonies_classes_id] != 9) && ($col[colonies_classes_id] != 11)) && (($i == 8) || ($i == 17))) $map .= "</tr><tr>";
			if (($col[colonies_classes_id] == 11) && (($i == 17) || ($i == 35))) $map .= "</tr><tr>";
			unset($alt);
		}
		$map .= "</tr></table>";
		$result = $this->db->query("SELECT a.field_id,a.type,a.buildings_id,a.aktiv,a.name,a.buildtime,b.name as bname, b.secretimage FROM stu_colonies_fields as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.colonies_id=".$colId." ORDER BY a.field_id");
		$map .= "<table cellspacing=1 cellpadding=1><tr>";
		if ($link == 2) $cl = $this->getcloaked($colId);
		for ($i=0;$i<mysql_num_rows($result);$i++)
		{
			$data = mysql_fetch_assoc($result);
			$data[buildtime] > 0 ? $id = 106 : $id = $data[buildings_id];
			if ($data[name] != "") $alt = "- ".$data[name]." ";
			if ($data[aktiv] == 0 && $link == 1) $alt .= "(offline)";
			elseif ($data[aktiv] == 1 && $link == 1) $alt .= "(online)";
			if (($data[buildings_id] >= 210) && ($data[buildings_id] <= 215))
			{
				$allyname = $this->db->query("SELECT a.name FROM stu_allys as a left outer join stu_allys_embassys as b on a.id = b.allys_id2 WHERE b.colonies_id=".$colId." AND b.field_id=".$data[field_id],1);
				if ($allyname != "") $alt = "der ".strip_tags(stripslashes($allyname));
			}
			if ($data[buildtime] > 0 && $link == 1) $alt .= " (im Bau bis ".date("d.m H:i",$data[buildtime]).")";
			if (($data[buildings_id] == 0) || ($cl[$data[field_id]] == 1))
			{
				$img = "<img src=".$grafik."/fields/".$n.$data[type].".gif border=0>";
			}
			elseif ($data[secretimage] != "0")
			{
				$img = "<img src=http://www.stuniverse.de/gfx/secret/".$n.$data[secretimage]."_".$data[type].".gif border=0 title='".$data[bname]." ".$alt."'>";
			}
			else
			{
				$img = "<img src=".$grafik."/buildings/".$n.$id."_".$data[type].".gif border=0 title='".$data[bname]." ".$alt."'>";
			}
			$data[aktiv] == 1 && $cl[$data[field_id]] == 0 && $link == 1? $border = " style='border: 1px solid #9BA4A4'" : $border = " style='border: 1px solid #505656'";
			if ($link == 1) $map.= "<td class=collist width=30 align=center".$border."><a href=main.php?page=colony&section=field&field=".$data[field_id]."&id=".$colId.">".$img."</a></td>";
			elseif ($link == 2)
			{
				global $freq1,$freq2;
				$map .= "<td class=collist width=30 align=center".$border."><a href=?page=ship&section=scan&mode=col&action=attack&type=field&field=".$data[field_id]."&id=".$shipId."&id2=".$colId."&freq1=".$freq1."&freq2=".$freq2.">".$img."</a></td>";
			}
			else $map .= "<td class=collist width=30 align=center".$border.">".$img."</td>";
			if ((($col[colonies_classes_id] == 6) || ($col[colonies_classes_id] == 9) && ($col[colonies_classes_id] != 11)) && (($i == 6) || ($i == 13) || ($i == 20) || ($i == 27))) $map .= "</tr><tr>";
			if ((($col[colonies_classes_id] != 6) && ($col[colonies_classes_id] != 9) && ($col[colonies_classes_id] != 11)) && (($i == 8) || ($i == 17) || ($i == 26) || ($i == 35) || ($i == 44))) $map .= "</tr><tr>";
			if (($col[colonies_classes_id] == 11) && (($i == 17) || ($i == 35) || ($i == 53) || ($i == 71) || ($i == 89) || ($i == 107) || ($i == 125) || ($i == 143) || ($i == 161) || ($i == 179) || ($i == 197) || ($i == 215))) $map .= "</tr><tr>";
			unset($alt);
		}
		$map .= "</tr></table>";
		if ($col[colonies_classes_id] != 6 && $data[colonies_classes_id] != 9 && $link == 1)
		{
			$result = $this->db->query("SELECT a.field_id,a.type,a.buildings_id,a.aktiv,a.name,a.buildtime,b.name as bname, b.secretimage FROM stu_colonies_underground as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.colonies_id=".$colId." ORDER BY a.field_id");
			$map .= "<table cellspacing=1 cellpadding=1><tr>";
			for ($i=0;$i<mysql_num_rows($result);$i++)
			{
				$data = mysql_fetch_assoc($result);
				$data[buildtime] > 0 ? $id = 106 : $id = $data[buildings_id];
				if ($data[name] != "") $alt = "- ".$data[name]." ";
				if ($data[aktiv] == 0) $alt .= "(offline)";
				elseif ($data[aktiv] == 1) $alt .= "(online)";
				if ($data[buildtime] > 0 && $link == 1) $alt .= " (im Bau bis ".date("d.m H:i",$data[buildtime]).")";
				if ($data[buildings_id] == 0)
				{
					$img = "<img src=".$grafik."/fields/".$data[type].".gif border=0>";
				}
				elseif ($data[secretimage] != "0")
				{
					$img = "<img src=http://www.stuniverse.de/gfx/secret/".$data[secretimage]."_".$data[type].".gif border=0 title='".$data[bname]." ".$alt."'>";
				}
				else
				{
					$img = "<img src=".$grafik."/buildings/".$id."_".$data[type].".gif border=0 title='".$data[bname]." ".$alt."'>";
				}
				$data[aktiv] == 1 && $link == 1 ? $border = " style='border: 1px solid #9BA4A4'" : $border = " style='border: 1px solid #505656'";
				$link == 1 ? $map.= "<td class=collist width=30 align=center".$border."><a href=main.php?page=colony&section=groundfield&field=".$data[field_id]."&id=".$colId.">".$img."</a></td>" : $map .= "<td class=collist width=30 align=center".$border.">".$img."</td>";
				if (($col[colonies_classes_id] != 11) && (($i == 8) || ($i == 17) || ($i == 26))) $map .= "</tr>";
				if (($col[colonies_classes_id] == 11) && (($i == 17) || ($i == 35) || ($i == 53) || ($i == 71) || ($i == 89) || ($i == 107))) $map .= "</tr>";
				unset($alt);
			}
			$map .= "</tr></table>";
		}
		return $map;
	}
	
	function getpossibleterraforming($type)
	{
		$result = $this->db->query("SELECT * FROM stu_terraform WHERE v_feld=".$type);
		while($tmp=mysql_fetch_assoc($result))
		{
			if ($tmp[id] == 14 && $this->ccolonies_classes_id != 5) continue;
			if ($tmp[id] == 24 && $this->ccolonies_classes_id != 10) continue;
			if ($tmp[id] == 1 && $this->ccolonies_classes_id == 10) continue;
			if ($tmp[id] == 7 || $tmp[id] == 18 || $tmp[id] == 19 || $tmp[id] == 20)
			{
				global $field;
				if ($field != 31) continue;
			}
			if ($tmp[research_id] > 0)
			{
				if ($this->getuserresearch($tmp[research_id],$this->user) == 1) $data[] = $tmp;
			}
			else $data[] = $tmp;
		}
		return $data;
	}
	
	function robotik($sonId,$count)
	{
		if ($this->cshow == 0) return 0;
		if ($sonId < 1 || $sonId > 4) {
			$return[msg] = "Parameterfehler";
			return $return;
		}
		if ($sonId == 1 && $this->getuserresearch(212,$this->user) == 0) $ferror = 1;
		elseif ($sonId == 2 && $this->getuserresearch(213,$this->user) == 0) $ferror = 1;
		elseif ($sonId == 3 && $this->getuserresearch(214,$this->user) == 0) $ferror = 1;
		elseif ($sonId == 4 && $this->getuserresearch(230,$this->user) == 0) $ferror = 1;
		if ($ferror == 1)
		{
			$return[msg] = "Du hast diesen Sondentyp noch nicht erforscht";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies_orbit WHERE colonies_id=".$this->cid." AND buildings_id=169 ANd buildtime=0",1) == 0)
		{
			$return[msg] = "Es befindet sich keine fertiggestellte Robotikfabrik auf der Kolonie";
			return $return;
		}
		if ($sonId == 1)
		{
			$end = 35;
			$ecost = 3;
		}
		elseif ($sonId == 2)
		{
			$end = 36;
			$ecost = 5;
		}
		elseif ($sonId == 3)
		{
			$end = 37;
			$ecost = 7;
		}
		elseif ($sonId == 4)
		{
			$end = 204;
			$ecost = 9;
		}
		$ecost *= $count;
		if ($this->cenergie < $ecost)
		{
			$return[msg] = "Es wird ".$ecost." Energie bentötigt - Vorhanden sind nur ".$this->cenergie;
			return $return;
		}
		$probecost = $this->getprobecostbylvl($sonId);
		for ($i=0;$i<count($probecost);$i++)
		{
			$probecost[$i][gesamt] = $probecost[$i]['count'] * $count;
			$storcount = $this->getcountbygoodid($probecost[$i][goods_id],$this->cid);
			if ($storcount < $probecost[$i][gesamt])
			{
				$return[msg] = "Es werden ".$probecost[$i][name]." benötigt - Vorhanden sind nur ".$storcount;
				return $return;
			}
		}
		for ($i=0;$i<count($probecost);$i++) $this->lowerstoragebygoodid($probecost[$i][gesamt],$probecost[$i][goods_id],$this->cid);
		$this->db->query("UPDATE stu_colonies SET energie=energie-".$ecost." WHERE id=".$this->cid);
		$this->upperstoragebygoodid($count,$end,$this->cid,$this->user);
		$return[msg] = "Leite Produktion ein... ".$count." Sonden hergestellt";
		return $return;
	}

	function analyse($goodId)
	{
		if ($this->cshow == 0) return 0;
		if ($goodId != 38 && $goodId != 39 && $goodId != 45 && $goodId != 46 && $goodId != 47 && $goodId != 48 && $goodId != 49 && $goodId != 221 && $goodId != 102 && $goodId != 103 && $goodId != 104 && $goodId != 142 && $goodId != 143 && $goodId != 144 && $goodId != 234 && $goodId != 97 && $goodId != 98 && $goodId != 27 && $goodId != 29) return 0;
		$storcount = $this->getcountbygoodid($goodId,$this->cid);
		if ($goodId == 38)
		{
			if ($this->getuserresearch(218,$this->user) == 1)
			{
				$return[msg] = "Osmium-Analyse bereits durchgeführt";
				return $return;
			}
			if ($storcount < 5)
			{
				$return[msg] = "Zur Analyse werden 5 Osmium benötigt";
				return $return;
			}
			$this->lowerstoragebygoodid(5,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('218','".$this->user."')");
			$return[msg] = "Osmium-Analyse komplett";
			return $return;
		}
		if ($goodId == 39)
		{
			if ($this->getuserresearch(219,$this->user) == 1)
			{
				$return[msg] = "Kirstall-Analyse bereits durchgeführt";
				return $return;
			}
			if ($storcount < 5)
			{
				$return[msg] = "Zur Analyse werden 5 tholianische Kristalle benötigt";
				return $return;
			}
			$this->lowerstoragebygoodid(5,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('219','".$this->user."')");
			$return[msg] = "Kristall-Analyse komplett";
			return $return;
		}

		if (($goodId >= 102) && ($goodId <= 104))
		{
			if ($this->getuserresearch(254,$this->user) == 1)
			{
				$return[msg] = "Plasmawaffen bereits bekannt";
				return $return;
			}
			if ($storcount < 1)
			{
				$return[msg] = "Zur Analyse wird mindestens eine Plasmakanone benötigt";
				return $return;
			}
			$this->lowerstoragebygoodid(1,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('254','".$this->user."')");
			$return[msg] = "Plasmakanone wurde demontiert und analysiert";
			return $return;
		}
		if (($goodId >= 142) && ($goodId <= 144))
		{
			if ($this->getuserresearch(255,$this->user) == 1)
			{
				$return[msg] = "Partikelwaffen bereits bekannt";
				return $return;
			}
			if ($storcount < 1)
			{
				$return[msg] = "Zur Analyse wird mindestens eine Partikelkanone benötigt";
				return $return;
			}
			$this->lowerstoragebygoodid(1,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('255','".$this->user."')");
			$return[msg] = "Partikelkanone wurde demontiert und analysiert";
			return $return;
		}
		if (($goodId == 97) || ($goodId == 98) || ($goodId == 234))
		{
			if ($this->getuserresearch(262,$this->user) == 1)
			{
				$return[msg] = "Polaronwaffen bereits bekannt";
				return $return;
			}
			if ($storcount < 1)
			{
				$return[msg] = "Zur Analyse wird mindestens ein Polaronstrahler benötigt";
				return $return;
			}
			$this->lowerstoragebygoodid(1,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('262','".$this->user."')");
			$return[msg] = "Polaronstrahler wurde demontiert und analysiert";
			return $return;
		}
		if ($goodId == 27)
		{
			if ($this->getuserresearch(266,$this->user) == 1)
			{
				$return[msg] = "Polarontorpedos bereits bekannt";
				return $return;
			}
			if ($storcount < 5)
			{
				$return[msg] = "Zur Analyse werden mindestens fünf Polarontropedos benötigt";
				return $return;
			}
			$this->lowerstoragebygoodid(5,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('266','".$this->user."')");
			$return[msg] = "Polarontorpedos wurden demontiert und analysiert";
			return $return;
		}
		if ($goodId == 29)
		{

			if ($this->getuserresearch(268,$this->user) == 1)
			{
				$return[msg] = "Positrontorpedos bereits bekannt";
				return $return;
			}
			if ($storcount < 5)
			{
				$return[msg] = "Zur Analyse werden mindestens fünf Positrontropedos benötigt";
				return $return;
			}
			$this->lowerstoragebygoodid(5,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('268','".$this->user."')");
			$return[msg] = "Positrontorpedos wurden demontiert und analysiert";
			return $return;
		}
		if ($goodId == 45)
		{
			if ($this->getuserresearch(221,$this->user) == 1)
			{
				$return[msg] = "Daten bereits bekannt";
				return $return;
			}
			if ($storcount < 1) {
				$return[msg] = "Keine Daten vorhanden";
				return $return;
			}
			$this->lowerstoragebygoodid(1,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('221','".$this->user."')");
			$return[msg] = "Daten eingespeist";
			return $return;
		}
		if ($goodId == 46)
		{
			if ($this->getuserresearch(222,$this->user) == 1)
			{
				$return[msg] = "Daten bereits bekannt";
				return $return;
			}
			if ($storcount < 1)
			{
				$return[msg] = "Keine Daten vorhanden";
				return $return;
			}
			$this->lowerstoragebygoodid(1,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('222','".$this->user."')");
			$return[msg] = "Daten eingespeist";
			return $return;
		}
		if ($goodId == 47)
		{
			if ($this->getuserresearch(226,$this->user) == 1)
			{
				$return[msg] = "Daten bereits bekannt";
				return $return;
			}
			if ($storcount < 1)
			{
				$return[msg] = "Keine Daten vorhanden";
				return $return;
			}
			$this->lowerstoragebygoodid(1,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('226','".$this->user."')");
			$return[msg] = "Daten eingespeist";
			return $return;
		}
		if ($goodId == 48)
		{
			if ($this->getuserresearch(227,$this->user) == 1)
			{
				$return[msg] = "Daten bereits bekannt";
				return $return;
			}
			if ($storcount < 1)
			{
				$return[msg] = "Keine Daten vorhanden";
				return $return;
			}
			$this->lowerstoragebygoodid(1,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('227','".$this->user."')");
			$return[msg] = "Daten eingespeist";
			return $return;
		}
		if ($goodId == 221)
		{
			if ($this->getuserresearch(252,$this->user) == 1)
			{
				$return[msg] = "Daten bereits bekannt";
				return $return;
			}
			if ($storcount < 1)
			{
				$return[msg] = "Keine Daten vorhanden";
				return $return;
			}
			$this->lowerstoragebygoodid(1,$goodId,$this->cid);
			$this->db->query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('252','".$this->user."')");
			$return[msg] = "Daten eingespeist";
			return $return;
		}
		if ($goodId == 49)
		{
			if ($storcount < 1)
			{
				$return[msg] = "Keine Daten vorhanden";
				return $return;
			}
			$return[msg] = "Verschlüsselung konnte nicht überwunden werden. Der Computer identifiziert eine Kessok-Signatur.";
			return $return;
		}
		$return[msg] = "Materialien/Daten können nicht verarbeitet werden";
		return $return;
	}
	function getprobecostbylvl($lvl) 
	{
		if ($lvl == 1)
		{
			$cost[0]['count'] = 3;
			$cost[0]['goods_id'] = 3;
			$cost[0]['name'] = "Baumaterial";
			$cost[1]['count'] = 2;
			$cost[1]['goods_id'] = 6;
			$cost[1]['name'] = "Duranium";
			$cost[2]['count'] = 1;
			$cost[2]['goods_id'] = 55;
			$cost[2]['name'] = "Duotronischer Computer";
			$cost[3]['count'] = 1;
			$cost[3]['goods_id'] = 83;
			$cost[3]['name'] = "Sensorenphalanx Klasse 1";
		}
		elseif ($lvl == 2)
		{
			$cost[0]['count'] = 5;
			$cost[0]['goods_id'] = 3;
			$cost[0]['name'] = "Baumaterial";
			$cost[1]['count'] = 4;
			$cost[1]['goods_id'] = 6;
			$cost[1]['name'] = "Duranium";
			$cost[2]['count'] = 1;
			$cost[2]['goods_id'] = 99;
			$cost[2]['name'] = "Multitronischer Computer";
			$cost[3]['count'] = 1;
			$cost[3]['goods_id'] = 84;
			$cost[3]['name'] = "Sensorenphalanx Klasse 2";
		}
		elseif ($lvl == 3)
		{
			$cost[0]['count'] = 7;
			$cost[0]['goods_id'] = 3;
			$cost[0]['name'] = "Baumaterial";
			$cost[1]['count'] = 6;
			$cost[1]['goods_id'] = 6;
			$cost[1]['name'] = "Duranium";
			$cost[2]['count'] = 1;
			$cost[2]['goods_id'] = 56;
			$cost[2]['name'] = "Isolinearer Computer";
			$cost[3]['count'] = 1;
			$cost[3]['goods_id'] = 85;
			$cost[3]['name'] = "Sensorenphalanx Klasse 3";
		}
		elseif ($lvl == 4)
		{
			$cost[0]['count'] = 8;
			$cost[0]['goods_id'] = 3;
			$cost[0]['name'] = "Baumaterial";
			$cost[1]['count'] = 8;
			$cost[1]['goods_id'] = 6;
			$cost[1]['name'] = "Duranium";
			$cost[2]['count'] = 1;
			$cost[2]['goods_id'] = 57;
			$cost[2]['name'] = "Bioneuraler Computer";
			$cost[3]['count'] = 1;
			$cost[3]['goods_id'] = 86;
			$cost[3]['name'] = "Adv. Sensorenphalanx";
		}
		return $cost;
	}
	
	function getcloaked($colId)
	{
		$class = $this->db->query("SELECT colonies_classes_id FROM stu_colonies WHERE id=".$colId,1);
		$field = $this->db->query("SELECT cloakfield FROM stu_colonies WHERE id=".$colId,1);
		$cf = $this->db->query("SELECT field_id FROM stu_colonies_fields WHERE buildings_id=88 AND aktiv=1 AND colonies_id=".$colId,1);
		if ($cf == 0) return 0;
		if ($class == 6 || $class == 9)
		{
			$r = 7;
			$m = 35;
		}
		else
		{
			$r = 9;
			$m = 54;
		}
		$cr = floor($field/$r);
		for ($i=0;$i<=2;$i++)
		{
			if ($field - ($i*$r) >= 0 && $cr-$i == floor(($field-($i*$r))/$r)) $map[$field-($i*$r)] = 1;
			if (($field - $i >= 0 ) && ($cr == floor(($field-$i)/$r))) $map[$field-$i] = 1;
			if ($i != 0)
			{
				if ($field + ($i*$r) <= $m && $cr+$i == floor(($field+($i*$r))/$r)) $map[$field+($i*$r)] = 1; 
				if (($field + $i <= $m) && ($cr == floor(($field+$i)/$r))) $map[$field+$i] = 1;
			}
			for ($j=1;$j<=2;$j++)
			{
				if (($field + ($i*$r)-$j <= $m) && ($cr+$i == floor(($field+($i*$r)-$j)/$r))) $map[$field+($i*$r)-$j] = 1;
				if (($field + ($i*$r)+$j <= $m) && ($cr+$i == floor(($field+($i*$r)+$j)/$r))) $map[$field+($i*$r)+$j] = 1;
				if ($i != 0)
				{
					if (($field - ($i*$r)-$j >= 0) && ($cr-$i == floor(($field-($i*$r)-$j)/$r))) $map[$field-($i*$r)-$j] = 1;
					if (($field - ($i*$r)+$j >= 0) && ($cr-$i == floor(($field-($i*$r)+$j)/$r))) $map[$field-($i*$r)+$j] = 1;
				}
			}
		}
		return $map;
	}

	function movecloakfield($colId,$field)
	{
		if ($this->cshow == 0) return 0;
		$class = $this->db->query("SELECT colonies_classes_id FROM stu_colonies WHERE id=".$colId,1);
		$cf = $this->db->query("SELECT field_id FROM stu_colonies_fields WHERE buildings_id=88 AND aktiv=1 AND colonies_id=".$colId,1);
		$class == 6 || $class == 9 ? $m = 35 : $m = 54;
		if ($field >= $m+1)
		{
			$return[msg] = "Dieses Feld existiert nicht";
			return $return;
		}
		if ($cf == 0)
		{
			$return[msg] = "Es ist kein aktivierter Tarnfeldgenerator vorhanden";
			return $return;
		}
		$this->db->query("UPDATE stu_colonies SET cloakfield=".$field." WHERE id=".$colId);
		$return[msg] = "Tarnfeld wurde neu ausgerichtet";
		return $return;
	}

	function swapbuilding($fieldId,$colId,$newId)
	{
		if ($this->cshow == 0) return 0;
		$data = $this->getfielddatabyid($fieldId,$colId);
		if ($data == 0) return 0;
		if ($data[buildtime] != 0)
		{
			$return[msg] = "Das Gebäude wurde noch nicht fertiggestellt";
			return $return;
		}
		global $myUser;
		if ($data[buildings_id] == 201)
		{
			if ($this->getuserresearch(251,$this->user) == 0)
			{
				$return[msg] = "Du hast die Automatische Raffinerie noch nicht erforscht";
				return $return;
			}
			if ($newId != 202)
			{
				$return[msg] = "Falsche Moduseinstellung";
				return $return;
			}
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='202' WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Raffinerie auf Kelbonitverarbeitung umgestellt";
			return $return;
		}
		elseif ($data[buildings_id] == 202)
		{
			if ($this->getuserresearch(251,$this->user) == 0)
			{
				$return[msg] = "Du hast die Automatische Raffinerie noch nicht erforscht";
				return $return;
			}
			if ($newId != 201)
			{
				$return[msg] = "Falsche Moduseinstellung";
				return $return;
			}
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id='201' WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'");
			$return[msg] = "Raffinerie auf Nitriumverarbeitung umgestellt";
			return $return;
		}
		else 
		{
			$return[msg] = "Parameterfehler";
			return $return;
		}
	}

	function changeembassyowner($colId,$field,$embassy)
	{
		global $myComm, $myAlly;
		if ($this->cshow == 0) return 0;
		if ($embassy != 0)
		{
			$embdata = mysql_fetch_array($myAlly->getembassybyid($embassy));
			$ally = $myAlly->getallybyid($embdata[allys_id2]);
			$ally2 = $myAlly->getallybyid($embdata[allys_id1]);
			$ally[diplo] > 0 ? $recipient = $ally[diplo] : $recipient = $ally[user_id];
			$emb = $this->db->query("SELECT * FROM stu_allys_embassys WHERE id=".$embassy,2);
			if ($emb[colonies_id] != 0)
			{
				$return[msg] = "Dieser Allianz wurde bereits eine Botschaft übergeben";
				return $return;
			}
			$emb = $this->db->query("SELECT * FROM stu_allys_embassys WHERE colonies_id=".$colId." AND field_id = ".$field,2);
			if ($emb != 0)
			{
				$return[msg] = "Dieses Botschaftsgebäude wird bereits verwendet";
				return $return;
			}
			$this->db->query("UPDATE stu_allys_embassys SET colonies_id=".$colId.", field_id = ".$field." WHERE id=".$embassy);
			$myComm->sendpm($recipient,$this->user,"Die Allianz ".$ally2[name]." hat Ihnen eine Botschaft überstellt");
			$return[msg] = "Botschaft wurde übergeben";
		}
		else
		{
			$ally = $myAlly->getallybyid($myAlly->getembassyownerbycolony($colId,$field));
			$ally2 = $myAlly->getallybyid($myAlly->getembassybuilderbycolony($colId,$field));
			$ally[diplo] > 0 ? $recipient = $ally[diplo] : $recipient = $ally[user_id];
			$this->db->query("UPDATE stu_allys_embassys SET colonies_id=0, field_id = 0 WHERE colonies_id=".$colId." AND field_id = ".$field);
			$this->db->query("UPDATE stu_colonies_fields SET buildings_id=210 WHERE colonies_id=".$colId." AND field_id = ".$field);
			$myComm->sendpm($recipient,$this->user,"Die Allianz ".$ally2[name]." hat Ihre Botschafter ausgewiesen");
			$return[msg] = "Botschafter wurden ausgewiesen";
		}
		return $return;
	}
	function jemhadarfunction($fieldId,$type)
	{
		if ($this->cshow == 0) return 0;
		global $myComm;
		$data = $this->getcolonybyid($this->cid);
		if ($data == 0) return 0;
		$field = $this->getfielddatabyid($fieldId,$this->cid);
		if ($field[buildings_id] == 0) return 0;
		if ($field == 0) return 0;

		if ($type == 1)
		{
			// Reaktor hochfahren
			if ($data[bev_free] < 5)
			{
				$return[msg] = "Zum Hochfahren des Reaktors werden 5 freie Arbeiter benötigt";
				return $return;
			}
			if ($field[aktiv] == 1)
			{
				$return[msg] = "Der Reaktor läuft bereits";
				return $return;
			}
			$this->activateBuilding($fieldId,$this->cid,$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildtime='".(time()+10800)."' WHERE field_id='".$fieldId."' AND colonies_id='".$this->cid."'");
			$return[msg] = "Reaktor wurde in Betrieb genommen";
			return $return;
		}
		if ($type == 2)
		{
			// Reaktor runterfahren
			if ($data[bev_free] < 5)
			{
				$return[msg] = "Zum Herunterfahren des Reaktors werden 5 freie Arbeiter benötigt";
				return $return;
			}
			if ($field[aktiv] == 0)
			{
				$return[msg] = "Der Reaktor läuft noch nicht";
				return $return;
			}
			$this->deactivateBuilding($fieldId,$this->cid,$this->user);
			$this->db->query("UPDATE stu_colonies_fields SET buildtime='".(time()+10800)."' WHERE field_id='".$fieldId."' AND colonies_id='".$this->cid."'");
			$return[msg] = "Reaktor wurde außer Betrieb genommen";
			return $return;
		}
		elseif ($type == 3)
		{
			// Waffen ausbauen

			$return[msg] = "Alle übrigen Waffensysteme sind unbrauchbar, eine Bergung wäre sinnlos";
			return $return;
		}
		elseif ($type == 4)
		{
			// Computer anzapfen

			$return[msg] = "Der Computer ist endgültig zerstört. Weitere Versuche, die Daten zu bergen, sind unmöglich.";
			return $return;

		}
		elseif ($type == 5)
		{
			// Erkunden

			$return[msg] = "Das Schiff wurde schon erkundet";
			return $return;
			
		}
		$return[msg] = "Fehler";
		return $return;

	}

	function getgoodpicbyid($Id)
	{
		global $grafik;
		$data =  $this->db->query("SELECT name,secretimage,id FROM stu_goods WHERE id = ".$Id."",4);
		if ($data[secretimage] != "0") $return = "<img src=http://www.stuniverse.de/gfx/secret/".$data[secretimage].".gif title='".$data[name]."'>";
		else $return = "<img src=".$grafik."/goods/".$data[id].".gif title='".$data[name]."'>";
		return $return;
	}


	function getmodbytype($type)
	{
		$result =  $this->db->query("SELECT id FROM stu_ships_modules WHERE type = ".$type." AND view=1 ORDER BY lvl ASC",2);
		return $result;
	}
}
?>
