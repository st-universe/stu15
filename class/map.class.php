<?php
class map
{
	function map()
	{
		global $myDB,$user;
		$this->db = $myDB;
		$this->user = $user;
	}
	
	function getrow($x1,$x2,$y,$wese=1) { return $this->db->query("SELECT a.coords_x,a.coords_y,a.type,a.race,b.sperrung FROM stu_map_fields as a LEFT JOIN stu_colonies as b USING(coords_x,coords_y,wese) WHERE a.wese=".$wese." AND a.coords_x>=".$x1." AND a.coords_x<=".$x2." AND a.coords_y=".$y,2); }
	
	function getfieldinfo($x,$y,$wese=1) { return $this->db->query("SELECT id FROM stu_ships WHERE coords_x=".$x." AND coords_y=".$y." AND cloak=0 AND wese=".$wese." AND ships_rumps_id!=154 AND ships_rumps_id!=158",2); }
	
	function getshipcount($x,$y,$wese=1) { return $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE coords_x=".$x." AND coords_y=".$y." AND cloak=0 AND wese=".$wese." AND ships_rumps_id!=154 AND ships_rumps_id!=158",1); }
	
	function getcloakedfieldinfo($x,$y,$wese=1) { return $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE coords_x=".$x." AND coords_y=".$y." AND wese=".$wese." AND cloak=1",1); }
	
	function getfieldcol($x,$y,$wese=1)
	{
		if (!is_numeric($x) || !is_numeric($y) || !is_numeric($wese)) return 0;
		return $this->db->query("SELECT a.id,a.name,a.colonies_classes_id,a.user_id,a.schilde_aktiv,b.name as classname FROM stu_colonies as a LEFT OUTER JOIN stu_colonies_classes as b ON a.colonies_classes_id=b.id WHERE a.wese=".$wese." AND a.coords_x=".$x." AND a.coords_y=".$y,4);
	}
	
	function getfieldships($coordx,$coordy,$shipId,$wese)
	{
		global $myUser,$user;
		return $this->db->query("SELECT a.id,a.fleets_id,a.user_id,a.huelle,a.schilde_aktiv,a.schilde,a.name,a.ships_rumps_id,a.trumoldrump,a.cloak,a.dock,a.traktormode,b.trumfield,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.huellmod*c.huell as maxhuell,b.id as classid,b.name as classname,b.slots,d.name as flname,d.ships_id as fship,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id LEFT JOIN stu_fleets as d ON a.fleets_id=d.id WHERE a.coords_x='".$coordx."' AND a.coords_y='".$coordy."' AND a.wese=".$wese." AND a.id!=".$shipId." ORDER BY b.slots DESC,a.fleets_id DESC,".$myUser->getslsorting("a.").",a.id");
	}
	
	function getfieldbycoords($x,$y,$wese=1) { return $this->db->query("SELECT type,race FROM stu_map_fields WHERE wese=".$wese." AND coords_x=".$x." AND coords_y=".$y,4); }
	
	function checksenjammer($x,$y,$wese=1) { return $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=166 AND coords_x=".$x." AND coords_y=".$y,1); }
	
	function editfield($x,$y,$type,$wese=1) { $this->db->query("UPDATE stu_map_fields SET type=".$type." WHERE coords_x=".$x." AND coords_y=".$y." AND type!=6 AND type!=7 AND type!=8 AND type!=9 AND type!=10"); }
	
	function getfreem() { return $this->db->query("SELECT a.id,a.coords_x,a.coords_y,a.wese FROM stu_colonies as a LEFT JOIN stu_map_fields as b USING(coords_x,coords_y,wese) WHERE a.colonies_classes_id=1 AND a.user_id=2 AND b.race=0",2); }
	
	function checkwormhole($x,$y,$wese=1) { return $this->db->query("SELECT start_x,start_y,stable FROM stu_wormholes WHERE start_wese=".$wese." AND start_x=".$x." AND start_y=".$y,4); }
	
	function getUserCheck($x1,$x2,$y1,$y2,$userId,$wese=1) { return $this->db->query("SELECT a.id FROM stu_map_sectors as a LEFT OUTER JOIN stu_map_sectors_user as b ON a.id=b.map_sectors_id WHERE a.coords_x1 BETWEEN ".$x1." AND ".$x2." AND a.coords_y1 BETWEEN ".$y1." AND ".$y2." b.user_id=".$userId,3); }
	
	function checkIoSector($x,$y,$wese=1) { return $this->db->query("SELECT id FROM stu_map_special WHERE coords_x=".$x." AND coords_y=".$y." AND wese=".$wese." AND type=1",3); }
	
	function getMapSectorSpecialType($x,$y,$wese=1) { return $this->db->query("SELECT type FROM stu_map_special WHERE coords_x=".$x." AND coords_y=".$y." AND wese=".$wese,4); }

	function getmapsectors($wese=1) { return $this->db->query("SELECT * FROM stu_map_sectors ORDER BY coords_y1,coords_x1",2); }
	
	function checksektor($sektorId,$wese=1) { return $this->db->query("SELECT COUNT(id) FROM stu_map_sectors_user WHERE map_sectors_id=".$sektorId." AND user_id=".$this->user,1); }
	
	function checkSectorUser($userId,$sectorId) { return $this->db->query("SELECT COUNT(user_id) FROM stu_map_sectors_user WHERE map_sectors_id=".$sectorId." ANd user_id=".$userId,1); }
	
	function rendersektor($sektorId)
	{
		$sek = $this->db->query("SELECT coords_x1,coords_x2,coords_y1,coords_y2,hide FROM stu_map_sectors WHERE id=".$sektorId,4);
		if ($sek[hide] == 1) return 0;
		if ($this->checkSectorUser($this->user,$sektorId) == 0) return 0;
		global $grafik,$myUser,$myColony;
		$fields = $this->db->query("SELECT * FROM stu_map_fields WHERE coords_x BETWEEN ".$sek[coords_x1]." AND ".$sek[coords_x2]." AND coords_y BETWEEN ".$sek[coords_y1]." AND ".$sek[coords_y2]." AND wese=1 ORDER BY coords_y,coords_x");
		$j=0;
		if ($sektorId-1 > 0) $hoch = "<tr><td colspan=23 align=center class=tdmain><a href=?page=starmap&section=showsektor&id=".($sektorId-1).">hoch</a></td></tr>";
		$sektorId+1 <= 100 ? $runter = "<tr><td colspan=23 align=center class=tdmain><a href=?page=starmap&section=showsektor&id=".($sektorId+1).">runter</a></td></tr>" : $runter = "<tr><td colspan=22></td></tr>";;
		if ($sektorId-10 > 0) $links = "<td rowspan=22 align=Center valign=middle class=tdmain><a href=?page=starmap&section=showsektor&id=".($sektorId-10)."><=</a></td>";
		if ($sektorId+10 <= 100) $rechts = "<td rowspan=22 align=Center valign=middle class=tdmain><a href=?page=starmap&section=showsektor&id=".($sektorId+10).">=></a></td>";
		$map .= "<table border=1 bordercolor=#080807 cellspacing=0 cellpadding=0>".$hoch."<tr>".$links."</tr><tr><td class=tdmain></td>";
		for ($i=$sek[coords_x1];$i<=$sek[coords_x2];$i++) $map .= "<td class=tdmain align=center>".$i."</td>";
		$map .= $rechts."</tr><tr>";
		while($data=mysql_fetch_assoc($fields))
		{
			if ($j == 20)
			{
				$map .= "</tr><tr>";
				$j=0;
			}
			if (!$oldy || $oldy < $data[coords_y])
			{
				$map .= "<td class=tdmain>".$data[coords_y]."</td>";
				$oldy = $data[coords_y];
			}
			$hares = $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=2 AND wese=1 AND coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y],1);
			$fergres = $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=100 AND wese=1  AND user_id=14 AND coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y],1) + $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=87 ANd user_id=14 AND coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y],1);
			$fedres = $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=36 AND wese=1  AND coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y],1);
			$deepres = $this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id=86 AND wese=1  AND user_id=10 AND coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y],1);
			$bajres = $this->db->query("SELECT COUNT(id) FROM stu_colonies WHERE colonies_classes_id=1 AND wese=1  AND user_id=18 AND coords_x=".$data[coords_x]." AND coords_y=".$data[coords_y],1);
			if ($myColony->getuserresearch(115,$this->user) == 1 || $this->user < 101)
			{
				$count = $this->db->query("SELECT COUNT(a.id) FROM stu_ships as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE (a.user_id=".$this->user." OR (b.allys_id=".$myUser->ually." AND b.allys_id>0)) AND a.wese=1 AND a.coords_x=".$data[coords_x]." AND a.coords_y=".$data[coords_y],1);
				$col = $this->db->query("SELECT COUNT(a.id) FROM stu_colonies as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE (a.user_id=".$this->user." OR (b.allys_id=".$myUser->ually." AND b.allys_id>0)) AND a.wese=1 AND a.coords_x=".$data[coords_x]." AND a.coords_y=".$data[coords_y],1);
			}
			$count == 0 || !$count ? $count = "&nbsp;" : $count = "<font size=-1>".$count."</font>";
			if ($data[race] == 15) $border = "bordercolor=#424A4A style='border: 1px solid #424A4A'";
			elseif ($data[race] == 13) $border = "bordercolor=#DDDD00 style='border: 1px solid #DDDD00'";
			elseif ($data[race] == 16) $border = "bordercolor=#D61FC4 style='border: 1px solid #D61FC4'";
			elseif ($data[race] == 10) $border = "bordercolor=#0088FF style='border: 1px solid #0088FF'";
			elseif ($data[race] == 22) $border = "bordercolor=#BB60BB style='border: 1px solid #BB60BB'";
			elseif ($data[race] == 24) $border = "bordercolor=#22DD88 style='border: 1px solid #22DD88'";
			else $border = "";
			if ($hares == 1) $map .= "<td class=tdmain bordercolor=\"#ffffff\" style='border: 1px solid #7f7f7f' border=1 width=30 height=30 background=".$grafik."/map/hap.gif><table align=center><tr><td align=center><b><font color=#ffffff>".$count."</font></b></td></tr></table></td>";
			elseif ($fergres == 1) $map .= "<td class=tdmain bordercolor=\"#ffffff\" style='border: 1px solid #933006' border=1 width=30 height=30 background=".$grafik."/map/fgp.gif><table align=center><tr><td align=center><b><font color=#ffffff>".$count."</font></b></td></tr></table></td>";
			elseif ($fedres == 1) $map .= "<td class=tdmain bordercolor=\"#ffffff\" style='border: 1px solid #0088FF' border=1 width=30 height=30 background=".$grafik."/map/fep.gif><table align=center><tr><td align=center><b><font color=#ffffff>".$count."</font></b></td></tr></table></td>";
			elseif ($deepres == 1) $map .= "<td class=tdmain bordercolor=\"#ffffff\" style='border: 1px solid #0088FF' border=1 width=30 height=30 background=".$grafik."/map/ds3.gif><table align=center><tr><td align=center><b><font color=#ffffff>".$count."</font></b></td></tr></table></td>";
			elseif ($bajres == 1) $map .= "<td class=tdmain bordercolor=\"#A45B45\" style='border: 1px solid #A45B45' border=1 width=30 height=30 background=".$grafik."/map/baj.gif><table align=center><tr><td align=center><b><font color=#ffffff>".$count."</font></b></td></tr></table></td>";
			else $map .= "<td width=30 height=30 border=1 ".$border." background=".$grafik."/map/".$data[type].".gif><table align=center width=30 height=30><tr><td align=center><strong><font color=#FFFFFF>".$count."</font></strong></td></tr></table></td>";
			$j++;
		}
		$map .= "</tr>".$runter."</table>";
		return $map;
	}
}
?>
