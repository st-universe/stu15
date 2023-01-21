<?php
class colony {

	function colony() {
	
		global $myDB;
		$this->dblink = $myDB->dblink;
		$this->db = $myDB;
	}
	
	function getcolonylist($userId) {
	
		$result = mysql_query("SELECT * from stu_colonies WHERE user_id='".$userId."'", $this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
		}
		return $data;
	}
	
	function getclassbyid($classId) {
	
		return mysql_fetch_array(mysql_query("SELECT * FROM stu_colonies_classes WHERE id='".$classId."'",$this->dblink));
	
	}
	
	function getbuildings() {
	
		$result = mysql_query("SELECT * FROM stu_buildings ORDER by level ASC",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$r1 = mysql_query("SELECT * FROM stu_field_build WHERE buildings_id='".$data[$i][id]."' LIMIT 1",$this->dblink);
			$bla = mysql_fetch_array($r1);
			$data[$i][field] = $bla[type];
			$data[$i][cost] = $this->getbuildingcostbyid($data[$i][id]);
		}
		return $data;
	}
	
	function getStorageById($colId,$userId) {
	
		$result = mysql_query("SELECT stu_colonies_storage.goods_id,stu_colonies_storage.count,stu_goods.name FROM stu_colonies_storage,stu_goods WHERE stu_colonies_storage.colonies_id='$colId' && stu_goods.id=stu_colonies_storage.goods_id ORDER BY goods_id ASC", $this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}
	
	function getcolbyfield($coords_x,$coords_y,$userId) {
		
		$result = mysql_query("SELECT * FROM stu_colonies WHERE user_id='".$userId."'",$this->dblink);
		if (mysql_num_rows($result) == 6) return -1;
		$result = mysql_query("SELECT * FROM stu_colonies WHERE coords_x='".$coords_x."' AND coords_y='".$coords_y."' AND user_id='2'",$this->dblink);
		if (mysql_num_rows($result) == 0) return -1;
		else {
			$data = mysql_fetch_array($result);
			if ($data[colonies_classes_id] == 1) {
				$result = mysql_query("SELECT * FROM stu_colonies WHERE user_id='".$userId."' AND colonies_classes_id='1'",$this->dblink);
				if (mysql_num_rows($result) < 1) return $data;
				else return -1;
			} else return $data;
		}
	}
	
	function getcolfields($colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies_fields WHERE colonies_id='".$colId."' ORDER BY field_id ASC",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}
	
	function getcolfieldsbybuilding($colId) {
	
		$result = mysql_query("SELECT stu_colonies_fields.* FROM stu_colonies_fields LEFT JOIN stu_buildings ON stu_colonies_fields.buildings_id=stu_buildings.id WHERE stu_colonies_fields.colonies_id='".$colId."' ORDER BY stu_buildings.name,stu_buildings.id ASC",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}
	
	function getcolonybyid($colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies WHERE id='".$colId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	
	}
	
	function getcolonyDatabyid($colId,$userId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	}
	
	function checkCol($col_class,$userId) {
	
		$result = mysql_query("SELECT id FROM stu_colonies WHERE user_id='".$userId."' AND colonies_classes_id='".$col_class."'",$this->dblink);
		if (mysql_num_rows($result) == 6) return -1;
		return mysql_num_rows($result);
	
	}
	
	function getbuildbyid($buildId) {
	
		$result =  mysql_query("SELECT * FROM stu_buildings WHERE id='".$buildId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	
	}
	
	function getpossiblebuildings($type) {
	
		global $user,$myUser;
		$userdata = $myUser->getuserbyid($user);
		$result = mysql_query("SELECT * FROM stu_field_build WHERE type='".$type."' AND buildings_id!=1 AND buildings_id!=23 ORDER by buildings_id",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data = mysql_fetch_array($result);
			$build = mysql_fetch_array(mysql_query("SELECT * FROM stu_buildings WHERE id='".$data[buildings_id]."'",$this->dblink));
			if ($build[research_id] > 0) {
				$rresult = mysql_query("SELECT * FROM stu_research_user WHERE research_id='".$build[research_id]."' AND user_id='".$user."'",$this->dblink);
				if (mysql_num_rows($rresult) == 1) if ($build[level] <= $userdata[level]) $return[] = $this->getbuildbyid($data[buildings_id]);
			} else if ($build[level] <= $userdata[level]) $return[] = $this->getbuildbyid($data[buildings_id]);
		}
		return $return;
	}
	
	function getfieldbyid($fieldId,$colId) {
		
		$field[data] = mysql_fetch_array(mysql_query("SELECT * FROM stu_colonies_fields WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink));
		$field[build] = $this->getbuildbyid($field[data][buildings_id]);
		$field[possible] = $this->getpossiblebuildings($field[data][type]);
		return $field;
	}
	
	function getfielddatabyid($fieldId,$colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies_fields WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	}
	
	function buildonfield($fieldId,$buildingId,$colId,$userId) {
		
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$field = $this->getfielddatabyid($fieldId,$colId);
		$building = $this->getbuildbyid($buildingId);
		if ($field[buildings_id] > 0) {
			$return[msg] = $field[field_id]." ist bereits bebaut";
			return $return;
		}
		$possible = mysql_query("SELECT id FROM stu_field_build WHERE type='".$field[type]."' AND buildings_id='".$buildingId."'",$this->dblink);
		if (mysql_num_rows($possible) == 0) return 0;
		if (($data[energie] - $building[eps_cost]) < 0) {
			$return[msg] = "Zum Bau werden ".$building[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$data[energie];
			$return[code] = 0;
			return $return;
		}
		global $myUser;
		$userdata = $myUser->getuserbyid($userId);
		if ($building[level] > $userdata[level]) {
			$return[msg] = "Das Gebäude (".$building[name].") kann erst ab Level ".$building[level]." gebaut werden.";
			$return[code] = 0;
			return $return;
		}
		if ($building[research_id] > 0) {
			$rresult = mysql_query("SELECT * FROM stu_research_user WHERE research_id='".$building[research_id]."' AND user_id='".$userId."'",$this->dblink);
			if (mysql_num_rows($rresult) == 0) {
				$return[msg] = "Dieses Gebäude wurde noch nicht erforscht";
				$return[code] = 0;
				return $return;
			}
		}
		if (($building[id] == 7) || ($building[id] == 17) || ($building[id] == 33) || ($building[id] == 34)) {
			$class = $this->getclassbyid($data[colonies_classes_id]);
			$count = mysql_num_rows(mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=".$building[id]." AND colonies_id=".$colId."",$this->dblink));
			if ($count >= $class[$building[id]]) {
				if ($class[$building[id]] == 0) $return[msg] = $building[name]." ist auf diesem Planeten nicht baubar";
				else $return[msg] = "Es können keine weiteren Gebäude von diesem Typ (".$building[name].") errichtet werden";
				return $return;
			}
		}
		$cost = $this->mincost($buildingId,$userId,$colId);
		if ($cost[code] == 0) {
			$return[msg] = $cost[msg];
			$return[code] = 0;
			return $return;
		}
		mysql_query("UPDATE stu_colonies_fields SET buildings_id='".$buildingId."',integrity='".$building[integrity]."' WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies SET energie=energie-".$building[eps_cost]." WHERE id='".$colId."'",$this->dblink);
		if ($building[eps] > 0) mysql_query("UPDATE stu_colonies SET max_energie=max_energie+".$building[eps]." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		if ($building[lager] > 0) mysql_query("UPDATE stu_colonies SET max_storage=max_storage+".$building[lager]." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		if (($buildingId != 4) && ($buildingId != 10)) $act = $this->activateBuilding($fieldId,$colId,$userId);
		if ($buildingId == 38) {
			if ($act[code] == 1) $aktiv = ",aktiv=1";
			mysql_query("UPDATE stu_colonies_underground SET buildings_id=39".$aktiv." WHERE field_id=13 ANd colonies_id=".$colId."",$this->dblink);
		}
		$return[msg] = $building[name]." auf Feld ".($field[field_id]+1)." errichtet";
		$return[code] = 1;
		return $return;
		echo mysql_error();
	}
	
	function deactivateBuilding($fieldId,$colId,$userId) {
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$field = $this->getfielddatabyid($fieldId,$colId);
		$build = $this->getbuildbyid($field[buildings_id]);
		if ($field[buildings_id] == 1) {
			$return[msg] = "Koloniezentrale kann nicht deaktiviviert werden.";
			$return[code] = -1;
			return $return;
		}
		if ($field[aktiv] == 0) {
			$return[msg] = "Das Gebäude ist bereits deaktiviert";
			return $return;
		}
		if ($build[bev_pro] > 0) {
			if ($build[bev_pro] + $data[bev_used] <= $data[max_bev]) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$build[bev_pro]." WHERE id='".$data[id]."'",$this->dblink);
			else {
				$return[msg] = "Das Gebäude konnte nicht deaktiviert werden da von dem Wohnraum noch Siedler arbeiten";
				$return[code] = 0;
				return $return;
			}
		}
		mysql_query("UPDATE stu_colonies_fields SET aktiv=0 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."' AND aktiv=1",$this->dblink);
		if (mysql_affected_rows() == 1) mysql_query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use]." WHERE id='".$data[id]."'",$this->dblink);
		if ($build[id] == 21) {
			$orbit= $this->getcolorbit($colId);
			for ($i=0;$i<count($orbit);$i++) {
				if ($orbit[$i][aktiv] == 1) {
					mysql_query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE field_id='".$orbit[$i][field_id]."' AND colonies_id=".$colId."",$this->dblink);
					$binfo = $this->getbuildbyid($orbit[$i][buildings_id]);
					if ($binfo[bev_use] > 0) mysql_query("UPDATE stu_colonies SET bev_used=bev_used-".$binfo[bev_use].",bev_free=bev_free+".$binfo[bev_use]." WHERE id='".$colId."'",$this->dblink);
					if ($binfo[bev_pro] > 0) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$binfo[bev_pro]." WHERE id='".$colId."'",$this->dblink);
				}
			}
		}
		if ($build[id] == 38) {
			$ground = $this->getcolunderground($colId);
			for ($i=0;$i<count($ground);$i++) {
				if ($ground[$i][aktiv] == 1) {
					mysql_query("UPDATE stu_colonies_underground SET aktiv=0 WHERE field_id='".$ground[$i][field_id]."' AND colonies_id=".$colId."",$this->dblink);
					$binfo = $this->getbuildbyid($ground[$i][buildings_id]);
					if ($binfo[bev_use] > 0) mysql_query("UPDATE stu_colonies SET bev_used=bev_used-".$binfo[bev_use].",bev_free=bev_free+".$binfo[bev_use]." WHERE id='".$colId."'",$this->dblink);
					if ($binfo[bev_pro] > 0) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$binfo[bev_pro]." WHERE id='".$colId."'",$this->dblink);
				}
			}
			mysql_query("UPDATE stu_colonies_underground SET aktiv=0 WHERE field_id=13 ANd colonies_id='".$colId."'",$this->dblink);
		}
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." deaktiviert";
		$return[code] = 1;
		return $return;
	}
	
	function activateBuilding($fieldId,$colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$field = $this->getfielddatabyid($fieldId,$colId);
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($build[id] == 10) || ($build[id] == 35) || ($build[id] == 37)) {
			$return[msg] = "Das Gebäude besitzt keine Aktivierungsfunktion";
			$return[code] = 0;
			return $return;
		}
		if ($field[aktiv] == 1) {
			$return[msg] = "Das Gebäude ist bereits aktiviert";
			return $return;
		}
		if (($data[bev_free] < $build[bev_use]) && ($build[bev_pro] == 0)) {
			$return[msg] = "Zum aktivieren werden ".$build[bev_use]." Arbeiter benötigt - Es sind jedoch nur ".$data[bev_free]." Kolonisten frei";
			$return[code] = 0;
			return $return;
		}
		if (($build[bev_use] <= $data[bev_free]) && ($field[aktiv] == 0)) {
			if ($field[buildings_id] != 4) mysql_query("UPDATE stu_colonies_fields SET aktiv=1 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."'",$this->dblink);
			if ($build[bev_pro] > 0) $part = ",max_bev=max_bev+".$build[bev_pro];
			mysql_query("UPDATE stu_colonies SET bev_free=bev_free-".$build[bev_use].",bev_used=bev_used+".$build[bev_use]."".$part." WHERE id='".$data[id]."'",$this->dblink);
		} elseif (($build[bev_pro] > 0) && ($field[aktiv] == 0)) {
			if ($field[buildings_id] != 4) mysql_query("UPDATE stu_colonies_fields SET aktiv=1 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."'",$this->dblink);
			if ($build[bev_pro] > 0) $part = ",max_bev=max_bev+".$build[bev_pro];
			mysql_query("UPDATE stu_colonies SET bev_free=bev_free-".$build[bev_use].",bev_used=bev_used+".$build[bev_use]."".$part." WHERE id='".$data[id]."'",$this->dblink);
		}
		if ($build[id] == 38) mysql_query("UPDATE stu_colonies_underground SET aktiv=1 WHERE field_id=13 ANd colonies_id=".$colId."",$this->dblink);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." aktiviert";
		$return[code] = 1;
		return $return;
	
	}
	
	function renameCol($colId,$userId,$new_name) {
	
		mysql_query("UPDATE stu_colonies SET name='".strip_tags($new_name)."' WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		$return[msg] = "Namensänderung in ".$new_name." erfolgreich";
		return $return;
	}
	
	function deletebuilding($fieldId,$colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$field = $this->getfielddatabyid($fieldId,$colId);
		if ($field == 0) return -1;
		$build = $this->getbuildbyid($field[buildings_id]);
		if ($field[buildings_id] == 1) return 0;
		if ($data[energie] > ($data[max_energie] - 40)) $energie = ",energie=".($data[max_energie] - 40);
		if ($field[buildings_id] == 4) mysql_query("UPDATE stu_colonies SET max_energie=max_energie-'30'".$energie." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		if ($build[bev_pro] > 0) {
			if (($data[bev_used] <= $data[max_bev] - $build[bev_pro]) && ($field[aktiv] == 1)) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$build[bev_pro]." WHERE id='".$data[id]."'",$this->dblink);
			elseif (($data[bev_used] > $data[max_bev] - $build[bev_pro]) && ($field[aktiv] == 1)) {
				$return[msg] = "Das Gebäude konnte nicht demontiert werden da von dem Wohnraum noch Siedler arbeiten";
				return $return;
		}
		}
		if ($build[lager] > 0) mysql_query("UPDATE stu_colonies SET max_storage=max_storage-".$build[lager]." WHERE id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_fields SET buildings_id='0',aktiv='0',integrity='0',name='' WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'",$this->dblink);
		if ($field[aktiv] == 1) mysql_query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use]." WHERE id='".$data[id]."'",$this->dblink);
		if ($build[id] == 21) {
			$orbit= $this->getcolorbit($colId);
			for ($i=0;$i<count($orbit);$i++) {
				if ($orbit[$i][aktiv] == 1) {
					mysql_query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE field_id='".$orbit[$i][field_id]."' AND colonies_id=".$colId."",$this->dblink);
					$binfo = $this->getbuildbyid($orbit[$i][buildings_id]);
					if ($binfo[bev_use] > 0) mysql_query("UPDATE stu_colonies SET bev_used=bev_used-".$binfo[bev_use].",bev_free=bev_free+".$binfo[bev_use]." WHERE id='".$colId."'",$this->dblink);
					if ($binfo[bev_pro] > 0) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$binfo[bev_pro]." WHERE id='".$colId."'",$this->dblink);
				}
			}
		}
		if ($build[id] == 38) {
			$ground = $this->getcolunderground($colId);
			for ($i=0;$i<count($ground);$i++) {
				if ($ground[$i][aktiv] == 1) {
					mysql_query("UPDATE stu_colonies_fields SET aktiv=0 WHERE field_id='".$ground[$i][field_id]."'",$this->dblink);
					$binfo = $this->getbuildbyid($ground[$i][buildings_id]);
					if ($binfo[bev_use] > 0) mysql_query("UPDATE stu_colonies SET bev_used=bev_used-".$binfo[bev_use].",bev_free=bev_free+".$binfo[bev_use]." WHERE id='".$colId."'",$this->dblink);
					if ($binfo[bev_pro] > 0) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$binfo[bev_pro]." WHERE id='".$colId."'",$this->dblink);
		}
			}
			mysql_query("UPDATE stu_colonies_underground SET buildings_id=0,aktiv=0,integrity=0 WHERE field_id=13 ANd colonies_id='".$colId."'",$this->dblink);
		}
		$this->returncost($build[id],$userId,$colId);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." demontiert";
		return $return;
	}
	
	function getgoodsbybuilding($buildingId) {
		
		$result = mysql_query("SELECT * FROM stu_buildings_goods WHERE buildings_id='".$buildingId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function goodlist() {
	
		$result = mysql_query("SELECT * FROM stu_goods ORDER BY id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function getstoragebygoodid($goodsId,$colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies_storage WHERE goods_id='".$goodsId."' AND colonies_id='".$colId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	}
	
	function getbuildingcostbyid($buildingId) {
	
		$result = mysql_query("SELECT * FROM stu_buildings_cost WHERE buildings_id='".$buildingId."' ORDER BY goods_id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$data[$i][good] = mysql_fetch_array(mysql_query("SELECT * FROM stu_goods WHERE id='".$data[$i][goods_id]."'",$this->dblink));
		}
		return $data;
	}
	
	function getbuildinggoodsbyid($buildingId) {
	
		$result = mysql_query("SELECT * FROM stu_buildings_goods WHERE buildings_id='".$buildingId."' ORDER BY goods_id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$data[$i][good] = mysql_fetch_array(mysql_query("SELECT * FROM stu_goods WHERE id='".$data[$i][goods_id]."'",$this->dblink));
		}
		return $data;
	
	}
	
	function mincost($buildingId,$userId,$colId) {
	
		$result = $this->getbuildingcostbyid($buildingId);
		if ($result == 0) return 1;
		for ($i=0;$i<count($result);$i++) {
			$r_ress = mysql_query("SELECT * FROM stu_colonies_storage WHERE goods_id='".$result[$i][goods_id]."' AND count>=".$result[$i]['count']." AND colonies_id='".$colId."' AND user_id='".$userId."'",$this->dblink);
			if (mysql_num_rows($r_ress) == 0) {
				$gname = mysql_fetch_array(mysql_query("SELECT name FROM stu_goods WHERE id='".$result[$i][goods_id]."'",$this->dblink));
				$count = $this->getcountbygoodid($result[$i][goods_id],$colId);
				if ($count == -1) $count = 0;
				else $count = $count['count'];
				$return[code] = 0;
				$return[msg] = "Zum Bau werden ".$result[$i]['count']." ".$gname[name]." benötigt - Vorhanden sind aber nur ".$count."";
				return $return;
			}
			$data[$i] = mysql_fetch_array($r_ress);
		}
		for ($i=0;$i<count($result);$i++) {
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$result[$i]['count']." WHERE goods_id='".$data[$i][goods_id]."' AND colonies_id='".$colId."' AND count>".$result[$i]['count']."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id='".$data[$i][goods_id]."' AND colonies_id='".$colId."'",$this->dblink);
		}
		$return[code] = 1;
		return $return;
	}
	
	function returncost($buildingId,$userId,$colId) {
	
		$colData = $this->getcolonydatabyid($colId,$userId);
		$result = $this->getbuildingcostbyid($buildingId);
		if ($result == 0) return 1;
		$data = mysql_fetch_array(mysql_query("SELECT SUM(count) as sum_count FROM stu_colonies_storage WHERE colonies_id='1'",$this->dblink));
		$sum = $data[0];
		for ($i=0;$i<count($result);$i++) {
			$count = floor($result[$i]['count']/2);
			if ($colData[max_storage] < $count + $sum) $count = $colData[max_storage] - $sum;
			if ($count > 0) mysql_query("UPDATE stu_colonies_storage SET count=count+'".$count."' WHERE goods_id='".$result[$i][goods_id]."' AND colonies_id='".$colId."'",$this->dblink);
			if ((mysql_affected_rows() == 0) && ($count > 0)) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','".$result[$i][goods_id]."','".$count."')",$this->dblink);
			$sum = $sum + $count;
		}
		return 1;
	}
	
	function terraform($fieldId,$colId,$terraform,$userId) {
	
		$col = $this->getcolonydatabyid($colId,$userId);
		if ($col == 0) {
			$return[msg] = "Dies ist nicht Deine Kolonie";
			return $return;
		}
		$data = $this->getfielddatabyid($fieldId,$colId);
		if (($terraform == 16) && ($data[type] != 5) && ($data[type] != 7)) {
			$return[msg] = "Die Tiefensprengung kann aufgrund der planetaren Integrität nur auf Feld 31 erfolgen.";
			$return[code] = -1;
			return $return;
		}
		if ($terraform == 15) {
			$data = $this->getgroundfielddatabyid($fieldId,$colId);
			if ($data[type] != 14) {
				$return[msg] = "Es können nur Felsen gesprengt werden.";
				$return[code] = -1;
				return $return;
			}
		}
		if ($terraform == 19) {
			$data = $this->getgroundfielddatabyid($fieldId,$colId);
			if ($data[type] != 15) {
				$return[msg] = "Die Bohrungen können nur auf einem freigesprengten Feld durchgefürt werden.";
				$return[code] = -1;
				return $return;
			}
		}
		if (($data[type] != 1) && ($terraform == 4)) {
			$return[msg] = "Du kannst einen Wald nur auf einer Wiese pflanzen.";
			$return[code] = -1;
			return $return;
		}
		if ($data[buildings_id] > 0) {
			$return[msg] = "Ein bebautes Feld kann nicht terraformed werden.";
			$return[code] = -1;
			return $return;
		}
		if (($col[energie] < 10) && ($terraform == 1)) {
			$return[msg] = "Nicht genügend Energie vorhanden.";
			$return[code] = -1;
			return $return;
		}
		if ($col[user_id] != $userId) {
			$return[msg] = "Dies ist nicht Deine Kolonie!";
			$return[code] = -1;
			return $return;
		}
		$good[na] = $this->getstoragebygoodid(1,$colId);
		$good[bm] = $this->getstoragebygoodid(3,$colId);
		$good[dura] = $this->getstoragebygoodid(6,$colId);
		$good[torp] = $this->getstoragebygoodid(7,$colId);
		if ($terraform == 4) {
			if (($good[bm]['count'] < 5) || ($good[na]['count'] < 30) || ($col[energie] < 15)) {
				$return[msg] = "Zu wenig Ressourcen oder Energie.";
				$return[code] = 0;
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET energie=energie-15 WHERE id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_fields SET type=4 WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'",$this->dblink);
			mysql_query("UPDATE stu_user SET symp=symp+15 WHERE id='".$userId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-5 WHERE goods_id='3' AND colonies_id='".$colId."' AND count>5",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id=3 AND colonies_id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-30 WHERE goods_id='1' AND colonies_id='".$colId."' AND count>30",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id=1 AND colonies_id='".$colId."'",$this->dblink);
			$return[msg] = "Leite Terraforming ein....Wald gepflanzt.";
			$return[code] = 1;
			return $return;
		} elseif (($terraform == 1) && ($data[type] == 4)) {
			if ($col[energie] < 10) {
				$return[msg] = "Es wird 10 Energie benötigt, vorhanden wird nur ".$col[energie];
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET energie=energie-10 WHERE id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_fields SET type=1 WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'",$this->dblink);
			mysql_query("UPDATE stu_user SET symp=symp-30 WHERE id='".$userId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count+5 WHERE goods_id='3' AND colonies_id='".$colId."'",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','3','5')",$this->dblink);
			$return[msg] = "Leite Terraforming ein....Wald abgeholzt.";
			$return[code] = 1;
			return $return;
		} elseif (($terraform == 1) && ($data[type] == 3)) {
			if ($col[energie] < 35) {
				$return[msg] = "Es wird 35 Energie benötigt, vorhanden wird nur ".$col[energie];
				return $return;
			}
			if ($good[bm]['count'] < 50) {
				$return[msg] = "Es wird 50 Baumaterial benötigt, vorhanden ist nur ".$good[bm];
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET energie=energie-35 WHERE id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_fields SET type=1 WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'",$this->dblink);
			mysql_query("UPDATE stu_user SET symp=symp-30 WHERE id='".$userId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-50 WHERE goods_id='3' AND colonies_id='".$colId."' AND count>50",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id=3 AND colonies_id=".$colId."",$this->dblink);
			$return[msg] = "Leite Terraforming ein....Wasserfläche zugeschüttet.";
			$return[code] = 1;
			return $return;
		} elseif (($terraform == 1) && ($data[type] == 2)) {
			if ($col[energie] < 35) {
				$return[msg] = "Es wird 35 Energie benötigt, vorhanden ist aber nur ".$col[energie];
				return $return;
			}
			if ($good[bm]['count'] < 50) {
				$return[msg] = "Es wird 50 Baumaterial benötigt, vorhanden ist nur ".$good[bm];
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET energie=energie-35 WHERE id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_fields SET type=1 WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-50 WHERE goods_id='3' AND colonies_id='".$colId."' AND count>50",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id=3 AND colonies_id=".$colId."",$this->dblink);
			$return[msg] = "Leite Terraforming ein...Eisfläche zu Wiese umgewandelt.";
			$return[code] = 1;
			return $return;
		} elseif ($terraform == 16) {
			if ($good[torp]['count'] < 25) {
				$return[msg] = "Es werden 25 Photonentorpedos benötigt - Vorhanden sind nur ".$good[torp]['count'];
				return $return;
			}
			if ($col[energie] < 30) {
				$return[msg] = "Nicht genügend Energie vorhanden";
				return $return;
			}
			if ($col[colonies_classes_id] == 4) $type = 17;
			elseif ($col[colonies_classes_id] == 4) $type = 18;
			else $type = 16;
			mysql_query("UPDATE stu_colonies SET energie=energie-30 WHERE id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_fields SET type=".$type." WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_underground SET type=15 WHERE field_id=13 ANd colonies_id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-25 WHERE goods_id='7' AND colonies_id='".$colId."' AND count>25",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=7",$this->dblink);
			$return[msg] = "Sprengungen eingeleitet...Sprengung erfolgreich, Schacht freigelegt.";
			$return[code] = 1;
			return $return;
		} elseif ($terraform == 15) {
			if ($good[torp]['count'] < 25) {
				if ($good[torp]['count'] == 0) $count = 0;
				else $count = $good[torp]['count'];
				$return[msg] = "Es werden 25 Photonentorpedos benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			if ($col[energie] < 30) {
				$return[msg] = "Nicht genügend Energie vorhanden";
				return $return;
			}
			$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=38 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
			if (mysql_num_rows($result) == 0) {
				$return[msg] = "Zum sprengen wird ein aktivierter Untergrundlift benötigt";
				$return[code] = 0;
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET energie=energie-30 WHERE id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_underground SET type=15 WHERE field_id=".$fieldId." ANd colonies_id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-25 WHERE goods_id='7' AND colonies_id='".$colId."' AND count>25",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=7",$this->dblink);
			$return[msg] = "Sprengungen eingeleitet...Sprengung erfolgreich.";
			$return[code] = 1;
			return $return;
		} elseif ($terraform == 19) {
			if ($this->checkgroundfield(19,$colId) == 3) {
				$return[msg] = "Es sind bereits drei Lavaspalten vorhanden";
				return $return;
			}
			if ($good[torp]['count'] < 15) {
				if ($good[torp]['count'] == 0) $count = 0;
				else $count = $good[torp]['count'];
				$return[msg] = "Es werden 15 Photonentorpedos benötigt - Vorhanden sind nur ".$count;
				return $return;
			}
			if ($good[dura]['count'] < 15) {
				if ($good[dura]['count'] == 0) $count = 0;
				else $count = $good[dura]['count'];
				$return[msg] = "Es wird 15 Duranium benötigt - Vorhanden ist nur ".$count;
				return $return;
			}
			if ($col[energie] < 30) {
				$return[msg] = "Nicht genügend Energie vorhanden";
				return $return;
			}
			$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=38 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
			if (mysql_num_rows($result) == 0) {
				$return[msg] = "Zum sprengen wird ein aktivierter Untergrundlift benötigt";
				$return[code] = 0;
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET energie=energie-30 WHERE id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_underground SET type=19 WHERE field_id=".$fieldId." ANd colonies_id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-15 WHERE goods_id='6' AND colonies_id='".$colId."' AND count>15",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=6",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-15 WHERE goods_id='7' AND colonies_id='".$colId."' AND count>15",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=7",$this->dblink);
			$return[msg] = "Sprengungen eingeleitet...Sprengung erfolgreich.";
			$return[code] = 1;
			return $return;
		}
	}
	
	
	function beamfrom($id,$colId,$goodId,$count) {
	
		global $myShip,$grafik,$user,$pass;
		$shipdata = $myShip->getdatabyid($id);
		$coldata = $this->getcolonybyid($colId);
		if ($shipdata[user_id] == $user) {
			$transadd = "von der <a href=main.php?page=ship&section=showship&id=".$shipId2."&user=".$user."&pass=".$pass.">".$shipdata[name]."</a>";
			$img = "<a href=?page=ship&section=showship&id=".$shipdata[id]."&user=".$user."&pass=".$pass."><img src=".$grafik."/ships/".$shipdata[ships_classes_id].".gif border=0></a>";
		} else {
			$transadd = "von der ".$shipdata[name];
			$img = "<img src=".$grafik."/ships/".$shipdata[ships_classes_id].".gif>";
		}
		if ($count < 0) {
			$return[msg] = "Es wurde eine falsche Anzahl eingegeben";
			return $return;
		}
		if (($shipdata == 0) || ($coldata == 0)) {
			$return[msg] = "Kolonie oder Schiff nicht vorhanden.";
			$return[code] = -1;
			return $return;
		}
		if (($coldata[coords_x] != $shipdata[coords_x]) || ($coldata[coords_y] != $shipdata[coords_y])) {
			$return[msg] = "Schiff und Kolonie müssen sich im selben Sektor befinden.";
			$return[code] = -1;
			return $return;
		}
		if ($shipdata[schilde_aktiv] == 1) {
			$return[msg] = "Die ".$shipdata[name]." hat die Schilde aktiviert";
			return $return;
		}
		$stor = $myShip->getcountbygoodid($goodId,$id);
		if ($stor == 0) {
			$return[msg] = "Ware nicht vorhanden.";
			$return[code] = 0;
			return $return;
		}
		if ($coldata[energie] == 0) {
			$return[msg] = "Keine Energie vorhanden.";
			$return[code] = 0;
			return $return;
		}
		if ($stor['count'] < $count) $count = $stor['count'];
		$result = mysql_query("SELECT SUM(count) FROM stu_colonies_storage WHERE colonies_id='".$colId."'",$this->dblink);
		$sum = mysql_result($result,0);
		if ($coldata[max_storage] < $sum + $count) $beam = $coldata[max_storage]-$sum;
		else $beam = $count;
		$energie = ceil($beam/30);
		if ($energie > $coldata[energie]) {
			$energie = $coldata[energie];
			$beam = $coldata[energie] * 30;
		}
		if ($beam < 1) {
			$return[msg] = "Kein Lagerraum vorhanden.";
			$return[code] = 0;
			return $return;
		}
		mysql_query("UPDATE stu_ships_storage SET count=count-".$beam." WHERE goods_id='".$goodId."' AND ships_id='".$shipdata[id]."' AND count > ".$beam."",$this->dblink);
		if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_ships_storage WHERE ships_id='".$shipdata[id]."' AND goods_id='".$goodId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_storage SET count=count+".$beam." WHERE goods_id='".$goodId."' AND colonies_id='".$colId."' AND user_id='".$coldata[user_id]."'",$this->dblink);
		if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$coldata[user_id]."','".$goodId."','".$beam."')",$this->dblink);
		mysql_query("UPDATE stu_colonies SET energie=energie-".$energie." WHERE id='".$colId."'",$this->dblink);
		$name = mysql_fetch_array(mysql_query("SELECT * from stu_goods WHERE id='".$goodId."'",$this->dblink));
		global $user,$pass;
		$return[msg] = "<table bgcolor=#262323><tr><td class=tdmainobg><img src=".$grafik."/planets/".$coldata[colonies_classes_id].".gif></td>
					    <td class=tdmainobg width=20 align=Center><img src=".$grafik."/buttons/b_from2.gif></td>
					    <td class=tdmainobg align=center>".$img."</td>
						<td class=tdmainobg>".$beam." ".$name[name]." ".$transadd." gebeamt - ".$energie." Energie verbraucht</td></tr></table>";
		$return[code] = 1;
		return $return;
	}
	
	function getorbititems($x,$y) {
	
		$result = mysql_query("SELECT * FROM stu_ships WHERE coords_x='".$x."' AND coords_y='".$y."' AND cloak=0",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function transfercrew($shipid,$colId,$crew,$way,$userId) {
	
		global $myShip;
		$coldata = $this->getcolonybyid($colId);
		$shipdata = $myShip->getdatabyid($shipid);
		$class = $myShip->getclassbyid($shipdata[ships_classes_id]);
		if (($shipdata == 0) || ($coldata == 0)) {
			$return[msg] = "Kolonie oder Schiff nicht vorhanden.";
			$return[code] = -1;
			return $return;
		}
		if (($coldata[coords_x] != $shipdata[coords_x]) || ($coldata[coords_y] != $shipdata[coords_y])) {
			$return[msg] = "Schiff und Kolonie müssen sich im selben Sektor befinden.";
			$return[code] = -1;
			return $return;
		}
		if ($way == "to") {
			if ($crew > $coldata[bev_free]) $crew = $coldata[bev_free];
			if ($crew > $class[crew] - $shipdata[crew]) $crew = $class[crew] - $shipdata[crew];
		} elseif ($way == "from") {
			if ($crew > $shipdata[crew]) $crew = $shipdata[crew];
			if ($crew > $coldata[max_bev]-$coldata[bev_used]-$coldata[bev_free]) $crew = $coldata[max_bev]-$coldata[bev_used]-$coldata[bev_free];
		}
		if ($crew == 0) {
			$return[msg] = "Alle Quartiere des Schiffes sind belegt.";
			$return[code] = -1;
			return $return;
		}
		$energie = ceil($crew/4);
		if ($energie > $coldata[energie]) {
			$energie = $coldata[energie];
			$crew = $energie*4;
		}
		if ($way == "to") {
			$part = "bev_free=bev_free-".$crew;
			$part2 = "crew=crew+".$crew;
		} elseif ($way == "from") {
			$part = "bev_free=bev_free+".$crew;
			$part2 = "crew=crew-".$crew;
		}
		mysql_query("UPDATE stu_colonies SET energie=energie-".$energie.",".$part." WHERE id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_ships SET ".$part2." WHERE id='".$shipid."'",$this->dblink);
		$return[msg] = $crew." Crew gebeamt - ".$energie." Energie verbraucht";
		$return[code] = 1;
		return $return;
	}
	
	function etransfer($id,$colId,$count,$userId) {
	
		global $myShip;
		$coldata = $this->getcolonydatabyid($colId,$userId);
		$shipdata = $myShip->getdatabyid($id);
		$class = $myShip->getclassbyid($shipdata[ships_classes_id]);
		if (($shipdata == 0) || ($coldata == 0)) {
			$return[msg] = "Kolonie oder Schiff nicht vorhanden.";
			$return[code] = -1;
			return $return;
		}
		if (($coldata[coords_x] != $shipdata[coords_x]) || ($coldata[coords_y] != $shipdata[coords_y])) {
			$return[msg] = "Schiff und Kolonie müssen sich im selben Sektor befinden.";
			$return[code] = -1;
			return $return;
		}
		if ($count == "max") $count = $coldata[energie];
		if ($coldata[energie] < $count) $count = $coldata[energie];
		if ($shipdata[energie] + $count > $class[energie]) $count = $class[energie] - $shipdata[energie];
		mysql_query("UPDATE stu_colonies SET energie=energie-".$count." WHERE id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_ships SET energie=energie+".$count." WHERE id='".$id."'",$this->dblink);
		if ($coldata[user_id] != $shipdata[user_id]) mysql_query("INSERT INTO stu_pms (sender,recipient,message,date) VALUES ('".$coldata[user_id]."','".$shipdata[user_id]."','Die ".$coldata[name]." transferiert in Sektor ".$coldata[coords_x]."/".$coldata[coords_y]." ".$add." Energie zu ".$shipdata[name]."',NOW())",$this->dblink);
		$return[msg] = $count." Energie zum Schiff transferiert.";
		$return[code] = 1;
		return $return;
	}
	
	function getclassm($x,$y) {
	
		$result = mysql_query("SELECT * FROM stu_colonies WHERE user_id=2 AND colonies_classes_id=1 AND coords_x BETWEEN ".($x-15)." AND ".($x+15)." AND coords_y BETWEEN ".($y-15)." AND ".($y+15)."",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function upgradebuilding($fieldId,$colId,$userId) {
	
		$data = $this->getfielddatabyid($fieldId,$colId);
		if ($data[buildings_id] == 3) {
			global $myUser;
			$userdata = $myUser->getuserbyid($userId);
			$build1 = $this->getbuildbyid(3);
			$build2 = $this->getbuildbyid(16);
			if ($build2[level] > $userdata[level]) {
				$return[msg] = "Städte können erst ab Level ".$build2[level]." errichtet werden";
				$return[code] = -1;
				return $return;
			}
			if ($data[buildings_id] != 3) {
				$return[msg] = "Es können nur Häuser zu einer Stadt upgegradet werden";
				$return[code] = -1;
				return $return;
			}
			$coldata = $this->getcolonybyid($colId);
			if ($coldata[energie] - $build2[eps_cost] < 0) {
				$return[msg] = "Es ist nicht genügend Energie vorhanden";
				$return[code] = 0;
				return $return;
			}
			if ($data[buildings_id] == 3) $result = $this->mincost(16,$userId,$colId);
			if ($result[code] == 0) {
				$return[msg] = $result[msg];
				$return[code] = 0;
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$build1[bev_pro]." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost].",max_bev=max_bev+".$build2[bev_pro]." WHERE id='".$colId."' ANd user_id='".$userId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_fields SET buildings_id='16' WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'",$this->dblink);
		} elseif ($data[buildings_id] == 14) {
			$build1 = $this->getbuildbyid(14);
			$build2 = $this->getbuildbyid(15);
			if ($data[buildings_id] != 14) {
				$return[msg] = "Auf diesem Feld befindet sich kein Teilchenbeschleuniger";
				$return[code] = -1;
				return $return;
			}
			$coldata = $this->getcolonybyid($colId);
			if ($coldata[energie] - $build2[eps_cost] < 0) {
				$return[msg] = "Es wird ".$build2[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$coldata[energie];
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost(15,$userId,$colId);
			if ($result[code] == 0) {
				$return[msg] = $result[msg];
				$return[code] = 0;
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost].",bev_used=bev_used-".$build1[bev_use].",bev_free=bev_free+".$build1[bev_use]." WHERE id='".$colId."' ANd user_id='".$userId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies SET bev_used=bev_used+".$build2[bev_use].",bev_free=bev_free-".$build2[bev_use]." WHERE id='".$colId."' ANd user_id='".$userId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_fields SET buildings_id='15' WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'",$this->dblink);
		}
		$return[msg] = "Gebäudeupgrade von ".$build1[name]." auf ".$build2[name]." erfolgreich";
		$return[code] = 1;
		return $return;
	}
	
	function orbitupgrade($fieldId,$colId,$userId) {
	
		$data = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($data[buildings_id] == 26) {
			$build1 = $this->getbuildbyid(26);
			global $myUser;
			$userdata = $myUser->getuserbyid($userId);
			if ($userdata[rasse] == 1) $building = 27;
			elseif ($userdata[rasse] == 2) $building = 28;
			elseif ($userdata[rasse] == 3) $building = 29;
			elseif ($userdata[rasse] == 4) $building = 30;
			$build2 = $this->getbuildbyid($building);
			if ($this->getuserresearch(5,$userId) != 1) {
				$return[msg] = "Die erweiterte Werft wurde noch nicht erforscht";
				return $return;
			}
			if ($data[buildings_id] != 26) {
				$return[msg] = "Es kann nur eine Werft zu einer erweiterten Werft upgegradet werden";
				$return[code] = -1;
				return $return;
			}
			$coldata = $this->getcolonybyid($colId);
			if ($coldata[energie] - $build2[eps_cost] < 0) {
				$return[msg] = "Es ist nicht genügend Energie vorhanden";
				$return[code] = 0;
				return $return;
			}
			$result = $this->mincost($building,$userId,$colId);
			if ($result[code] == 0) {
				$return[msg] = $result[msg];
				$return[code] = 0;
				return $return;
			}
			mysql_query("UPDATE stu_colonies SET energie=energie-".$build2[eps_cost].",max_bev=max_bev+".($build2[bev_pro]-$build1[bev_pro])." WHERE id='".$colId."' ANd user_id='".$userId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_orbit SET buildings_id='".$building."' WHERE field_id='".$data[field_id]."' AND colonies_id='".$colId."'",$this->dblink);
		}
		$return[msg] = "Gebäudeupgrade von ".$build1[name]." auf ".$build2[name]." erfolgreich";
		$return[code] = 1;
		return $return;
	}
	
	function getcolorbit($colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies_orbit WHERE colonies_id='".$colId."' ORDER BY field_id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function getcolorbitbybuilding($colId) {
	
		$result = mysql_query("SELECT stu_colonies_orbit.* FROM stu_colonies_orbit LEFT JOIN stu_buildings ON stu_colonies_orbit.buildings_id=stu_buildings.id WHERE stu_colonies_orbit.colonies_id='".$colId."' ORDER BY stu_buildings.name,stu_buildings.id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function orbitbuild($fieldId,$buildingId,$colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=21 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zum Orbitbau wird ein aktivierter Raumbahnhof benötigt";
			$return[code] = 0;
			return $return;
		}
		if ($buildingId == 26) {
			$result = mysql_query("SELECT id FROM stu_colonies_orbit WHERE buildings_id=26 AND colonies_id=".$colId."",$this->dblink);
			if (mysql_num_rows($result) == 1) {
				$return[msg] = "Es ist bereits eine Werft vorhanden";
				$return[code] = 0;
				return $return;
			}
		}
		if ($buildingId == 47) {
			$result = mysql_query("SELECT id FROM stu_colonies_orbit WHERE buildings_id=47 AND colonies_id=".$colId."",$this->dblink);
			if (mysql_num_rows($result) == 1) {
				$return[msg] = "Es ist bereits eine Wetterkontrollstation vorhanden";
				$return[code] = 0;
				return $return;
			}
		}
		$field = $this->getorbitfielddatabyid($fieldId,$colId);
		$building = $this->getbuildbyid($buildingId);
		if ($field[buildings_id] > 0) {
			$return[msg] = "Dieses Feld ist bereits bebaut";
			$return[code] = 0;
			return $return;
		}
		$possible = mysql_query("SELECT id FROM stu_field_build WHERE type='".$field[type]."' AND buildings_id='".$buildingId."'",$this->dblink);
		if (mysql_num_rows($possible) == 0) return 0;
		if (($data[energie] - $building[eps_cost]) < 0) {
			$return[msg] = "Nicht genügend Energie vorhanden";
			$return[code] = 0;
			return $return;
		}
		$cost = $this->mincost($buildingId,$userId,$colId);
		if ($cost[code] == 0) {
			$return[msg] = $cost[msg];
			$return[code] = 0;
			return $return;
		}
		mysql_query("UPDATE stu_colonies_orbit SET buildings_id='".$buildingId."',integrity='".$building[integrity]."' WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies SET energie=energie-".$building[eps_cost]." WHERE id='".$colId."'",$this->dblink);
		if ($building[eps] > 0) mysql_query("UPDATE stu_colonies SET max_energie=max_energie+".$building[eps]." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		if ($building[lager] > 0) mysql_query("UPDATE stu_colonies SET max_storage=max_storage+".$building[lager]." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		if ($buildingId != 26) $this->activateorbitBuilding($fieldId,$colId,$userId);
		$return[msg] = $building[name]." auf Feld ".($field[field_id]+1)." errichtet";
		$return[code] = 1;
		return $return;
	
	}
	
	function getorbitfielddatabyid($fieldId,$colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies_orbit WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	}
	
	function activateorbitBuilding($fieldId,$colId,$userId) {
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=21 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zum aktivieren wird ein aktivierter Raumbahnhof benötigt";
			$return[code] = 0;
			return $return;
		}
		$field = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($field[aktiv] == 1) {
			$return[msg] = "Das Gebäude ist bereits aktiviert";
			return $return;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($build[id] == 26) || ($build[id] == 27) || ($build[id] == 28) || ($build[id] == 29) || ($build[id] == 30) || ($build[id] == 53)) {
			$return[msg] = "Das Gebäude besitzt keine Aktivierungsfunktion";
			return $return;
		}
		if (($build[bev_use] <= $data[bev_free]) && ($field[aktiv] == 0)) {
			if ($field[buildings_id] != 4) mysql_query("UPDATE stu_colonies_orbit SET aktiv=1 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."'",$this->dblink);
			if ($build[bev_pro] > 0) $part = ",max_bev=max_bev+".$build[bev_pro];
			mysql_query("UPDATE stu_colonies SET bev_free=bev_free-".$build[bev_use].",bev_used=bev_used+".$build[bev_use]."".$part." WHERE id='".$data[id]."'",$this->dblink);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." aktiviert";
		$return[code] = 1;
		} else $return[msg] = "Es werden ".$build[bev_use]." freie Arbeiter benötigt";
		return $return;
	
	}
	function getorbitfieldbyid($fieldId,$colId) {
		
		$field[data] = mysql_fetch_array(mysql_query("SELECT * FROM stu_colonies_orbit WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink));
		$field[build] = $this->getbuildbyid($field[data][buildings_id]);
		$field[possible] = $this->getpossiblebuildings($field[data][type]);
		return $field;
	}
	
	function deactivateorbitBuilding($fieldId,$colId,$userId) {
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$field = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($field[aktiv] == 0) {
			$return[msg] = "Das Gebäude ist bereits deaktiviert";
			return $return;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		if ($build[bev_pro] > 0) {
			if ($build[bev_pro] + $data[bev_used] <= $data[max_bev]) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$build[bev_pro]." WHERE id='".$data[id]."'",$this->dblink);
			else {
				$return[msg] = "Das Gebäude konnte nicht deaktiviert werden da von dem Wohnraum noch Siedler arbeiten";
				$return[code] = 0;
				return $return;
			}
		}
		mysql_query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."' AND aktiv=1",$this->dblink);
		if (mysql_affected_rows() == 1) mysql_query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use]." WHERE id='".$data[id]."'",$this->dblink);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." deaktiviert";
		$return[code] = 1;
		return $return;
	}
	
	function deleteorbitbuilding($fieldId,$colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$field = $this->getorbitfielddatabyid($fieldId,$colId);
		if ($field == 0) return -1;
		$build = $this->getbuildbyid($field[buildings_id]);
		if ($data[energie] > ($data[max_energie] - 40)) $energie = ",energie=".($data[max_energie] - 40);
		if ($build[bev_pro] > 0) {
			if ($build[bev_pro] + $data[bev_used] <= $data[max_bev]) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$build[bev_pro]." WHERE id='".$data[id]."'",$this->dblink);
			else {
				$return[msg] = "Das Gebäude konnte nicht demontiert werden da von dem Wohnraum noch Siedler arbeiten";
				return $return;
			}
		}
		mysql_query("UPDATE stu_colonies_orbit SET buildings_id='0',aktiv='0',integrity='0',name='' WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'",$this->dblink);
		if ($field[aktiv] == 1) mysql_query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use]." WHERE id='".$data[id]."'",$this->dblink);
		$this->returncost($build[id],$userId,$colId);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." wurde demontiert";
		return $return;
	}
	
	function getcolunderground($colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies_underground WHERE colonies_id='".$colId."' ORDER BY field_id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function getcolundergroundbybuilding($colId) {
	
		$result = mysql_query("SELECT stu_colonies_underground.* FROM stu_colonies_underground LEFT JOIN stu_buildings ON stu_colonies_underground.buildings_id=stu_buildings.id WHERE stu_colonies_underground.colonies_id='".$colId."' ORDER BY stu_buildings.name,stu_buildings.id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function groundbuild($fieldId,$buildingId,$colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=38 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zum Bau im Untergrund wird ein aktivierter Untergrundlift benötigt";
			$return[code] = 0;
			return $return;
		}
		if ($buildingId == 39) {
			$result = mysql_query("SELECT id FROM stu_colonies_underground WHERE buildings_id=39 AND colonies_id=".$colId."",$this->dblink);
			if (mysql_num_rows($result) == 1) {
				$return[msg] = "Es ist bereits ein Untergrundlift vorhanden";
				$return[code] = 0;
				return $return;
			}
		}
		$field = $this->getgroundfielddatabyid($fieldId,$colId);
		$building = $this->getbuildbyid($buildingId);
		if ($field[buildings_id] > 0) {
			$return[msg] = "Dieses Feld ist bereits bebaut";
			$return[code] = 0;
			return $return;
		}
		$possible = mysql_query("SELECT id FROM stu_field_build WHERE type='".$field[type]."' AND buildings_id='".$buildingId."'",$this->dblink);
		if (mysql_num_rows($possible) == 0) return 0;
		if (($data[energie] - $building[eps_cost]) < 0) {
			$return[msg] = "Nicht genügend Energie vorhanden";
			$return[code] = 0;
			return $return;
		}
		if (($building[id] == 7) || ($building[id] == 17) || ($building[id] == 33) || ($building[id] == 34)) {
			$class = $this->getclassbyid($data[colonies_classes_id]);
			$count = mysql_num_rows(mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=".$building[id]." AND colonies_id=".$colId."",$this->dblink));
			if ($count >= $class[$building[id]]) {
				if ($class[$building[id]] == 0) $return[msg] = $building[name]." ist auf diesem Planeten nicht baubar";
				else $return[msg] = "Es können keine weiteren Gebäude von diesem Typ (".$building[name].") errichtet werden";
				return $return;
			}
		}
		$cost = $this->mincost($buildingId,$userId,$colId);
		if ($cost[code] == 0) {
			$return[msg] = $cost[msg];
			$return[code] = 0;
			return $return;
		}
		mysql_query("UPDATE stu_colonies_underground SET buildings_id='".$buildingId."',integrity='".$building[integrity]."' WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies SET energie=energie-".$building[eps_cost]." WHERE id='".$colId."'",$this->dblink);
		if ($building[eps] > 0) mysql_query("UPDATE stu_colonies SET max_energie=max_energie+".$building[eps]." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		if ($building[lager] > 0) mysql_query("UPDATE stu_colonies SET max_storage=max_storage+".$building[lager]." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		$this->activategroundBuilding($fieldId,$colId,$userId);
		$return[msg] = $building[name]." auf Feld ".($field[field_id]+1)." errichtet";
		$return[code] = 1;
		return $return;
	
	}
	
	function getgroundfielddatabyid($fieldId,$colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies_underground WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	}
	
	function activategroundBuilding($fieldId,$colId,$userId) {
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=38 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zum aktivieren wird ein aktivierter Untergrundlift benötigt";
			$return[code] = 0;
			return $return;
		}
		$field = $this->getgroundfielddatabyid($fieldId,$colId);
		if ($field[aktiv] == 1) {
			$return[msg] = "Das Gebäude ist bereits aktiviert";
			return $return;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		if (($build[id] == 26) || ($build[id] == 35) || ($build[id] == 37) || ($build[lager]> 0)) {
			$return[msg] = "Das Gebäude besitzt keine Aktivierungsfunktion";
			return $return;
		}
		if ($data[bev_free] < $build[bev_use]) {
			$return[msg] = "Zum aktivieren des Gebäudes werden ".$build[bev_use]." Arbeiter benötigt";
			return $return;
		}
		if ($build[id] == 39) {
			$fieldoId = 31;
			$act = $this->activateBuilding($fieldId,$colId,$userId);
		}
		if (($build[id] == 39) && ($act[code] != 1)) {
			$return[msg] = $act[msg];
			return $return;
		}
		if (($build[bev_use] <= $data[bev_free]) && ($field[aktiv] == 0)) {
			if ($field[buildings_id] != 4) mysql_query("UPDATE stu_colonies_underground SET aktiv=1 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."'",$this->dblink);
			if ($build[bev_pro] > 0) $part = ",max_bev=max_bev+".$build[bev_pro];
			mysql_query("UPDATE stu_colonies SET bev_free=bev_free-".$build[bev_use].",bev_used=bev_used+".$build[bev_use]."".$part." WHERE id='".$data[id]."'",$this->dblink);
		}
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." aktiviert";
		$return[code] = 1;
		return $return;
	
	}
	
	function getgroundfieldbyid($fieldId,$colId) {
		
		$field[data] = mysql_fetch_array(mysql_query("SELECT * FROM stu_colonies_underground WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink));
		$field[build] = $this->getbuildbyid($field[data][buildings_id]);
		$field[possible] = $this->getpossiblebuildings($field[data][type]);
		return $field;
	}
	
	function deactivategroundBuilding($fieldId,$colId,$userId) {
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$field = $this->getgroundfielddatabyid($fieldId,$colId);
		if ($field[aktiv] == 0) {
			$return[msg] = "Das Gebäude ist bereits deaktiviert";
			return $return;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		if ($build[bev_pro] > 0) {
			if ($data[max_bev] - $build[bev_pro] >= $data[bev_used]) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$build[bev_pro]." WHERE id='".$data[id]."'",$this->dblink);
			else {
				$return[msg] = "Das Gebäude konnte nicht deaktiviert werden da von dem Wohnraum noch Siedler arbeiten";
				$return[code] = 0;
				return $return;
			}
		}
		mysql_query("UPDATE stu_colonies_underground SET aktiv=0 WHERE colonies_id='".$data[id]."' AND field_id='".$fieldId."' AND aktiv=1",$this->dblink);
		mysql_query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use]." WHERE id='".$data[id]."'",$this->dblink);
		if ($build[id] == 39) {
			$ground = $this->getcolunderground($colId);
			for ($i=0;$i<count($ground);$i++) {
				if ($ground[$i][aktiv] == 1) {
					mysql_query("UPDATE stu_colonies_underground SET aktiv=0 WHERE field_id='".$ground[$i][field_id]."' AND colonies_id=".$colId."",$this->dblink);
					$binfo = $this->getbuildbyid($ground[$i][buildings_id]);
					if ($binfo[bev_use] > 0) mysql_query("UPDATE stu_colonies SET bev_used=bev_used-".$binfo[bev_use].",bev_free=bev_free+".$binfo[bev_use]." WHERE id='".$colId."'",$this->dblink);
					if ($binfo[bev_pro] > 0) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$binfo[bev_pro]." WHERE id='".$colId."'",$this->dblink);
			}
			}
			$deakt = $this->getbuildbyid(38);
			mysql_query("UPDATE stu_colonies SET bev_used=bev_used-".$deakt[bev_use].",bev_free=bev_free+".$deakt[bev_use]." WHERE id='".$colId."'",$this->dblink);
			mysql_query("UPDATE stu_colonies_fields SET aktiv=0 WHERE field_id=31 ANd colonies_id='".$colId."'",$this->dblink);
		}
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." deaktiviert";
		$return[code] = 1;
		return $return;
	}
	
	function deletegroundbuilding($fieldId,$colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) return -1;
		$field = $this->getgroundfielddatabyid($fieldId,$colId);
		if ($field == 0) return -1;
		$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=38 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zum demontieren wird ein aktivierter Untergrundlift benötigt";
			$return[code] = 0;
			return $return;
		}
		$build = $this->getbuildbyid($field[buildings_id]);
		if ($data[energie] > ($data[max_energie] - 40)) $energie = ",energie=".($data[max_energie] - 40);
		if ($build[bev_pro] > 0) {
			if ($build[bev_pro] + $data[bev_used] <= $data[max_bev]) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$build[bev_pro]." WHERE id='".$data[id]."'",$this->dblink);
			else {
				$return[msg] = "Das Gebäude konnte nicht demontiert werden da von dem Wohnraum noch Siedler arbeiten";
				return $return;
			}
		}
		if ($build[lager] > 0) mysql_query("UPDATE stu_colonies SEt max_storage=max_storage-".$build[lager]." WHERE id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_underground SET buildings_id='0',aktiv='0',integrity='0',name='' WHERE colonies_id='".$colId."' AND field_id='".$fieldId."'",$this->dblink);
		if ($field[aktiv] == 1) mysql_query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use]." WHERE id='".$data[id]."'",$this->dblink);
		if ($build[id] == 39) {
			mysql_query("UPDATE stu_colonies SET max_storage=max_Storage-'150' WHERE colonies_id=".$colId."",$this->dblink);
			$ground = $this->getcolunderground($colId);
			for ($i=0;$i<count($ground);$i++) {
				if ($ground[$i][aktiv] == 1) {
					mysql_query("UPDATE stu_colonies_fields SET aktiv=0 WHERE field_id='".$ground[$i][field_id]."' AND colonies_id=".$colId."",$this->dblink);
					$binfo = $this->getbuildbyid($ground[$i][buildings_id]);
					if ($binfo[bev_use] > 0) mysql_query("UPDATE stu_colonies SET bev_used=bev_used-".$binfo[bev_use].",bev_free=bev_free+".$binfo[bev_use]." WHERE id='".$colId."'",$this->dblink);
					if ($binfo[bev_pro] > 0) mysql_query("UPDATE stu_colonies SET max_bev=max_bev-".$binfo[bev_pro]." WHERE id='".$colId."'",$this->dblink);
				}
			}
			mysql_query("UPDATE stu_colonies_fields SET buildings_id=0,aktiv=0,integrity=0 WHERE field_id=31 ANd colonies_id='".$colId."'",$this->dblink);
		}
		$this->returncost($build[id],$userId,$colId);
		$return[msg] = $build[name]." auf Feld ".($field[field_id]+1)." wurde demontiert";
		return $return;
	}
	
	function checkgroundfield($type,$colId) {
	
		return mysql_num_rows(mysql_query("SELECT id FROM stu_colonies_underground WHERE type='".$type."' AND colonies_id='".$colId."'",$this->dblink));
	
	}
	
	function getpossibleships($userId) {
	
		$result = mysql_query("SELECT * from stu_ships_build WHERE user_id='".$userId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function buildship($colId,$classId,$userId) {
	
		$result = mysql_query("SELECT * FROM stu_ships_build WHERE user_id='".$userId."' AND ships_classes_id='".$classId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Du darfst diesen Schiffstyp nicht bauen";
			return $return;
		}
		$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=21 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zum Bau eines Schiffes wird ein aktivierter Raumbahnhof benötigt";
			$return[code] = 0;
			return $return;
		}
		global $myShip;
		$class = $myShip->getclassbyid($classId);
		$result = mysql_query("SELECT id FROM stu_colonies_orbit WHERE buildings_id > 26 AND buildings_id < 31",$this->dblink);
		if ((mysql_num_rows($result) == 0) && ($class[ewwerft] == 1)) {
			$return[msg] = "Zum Bau eines Schiffes wird eine erweiterte Weerft benötigt";
			$return[code] = 0;
			return $return;
		}
		$data = $this->getcolonybyid($colId);
		if ($data[energie] - $class[eps_cost] < 0) {
			$return[msg] = "Es wird ".$class[eps_cost]." Energie benötigt - Vorhanden ist aber nur ".$data[energie];
			return $return;
		}
		$result = $this->shipmincost($classId,$userId,$colId);
		if ($result[code] == 0) {
			$return[msg] = $result[msg];
			return $return;
		}
		mysql_query("UPDATE stu_colonies SET energie=energie-".$class[eps_cost]." WHERE id='".$colId."'",$this->dblink);
		mysql_query("INSERT INTO stu_ships (name,ships_classes_id,user_id,coords_x,coords_y,huelle) VALUES ('Noname','".$class[id]."','".$userId."','".$data[coords_x]."','".$data[coords_y]."','".$class[huelle]."')",$this->dblink);
		$return[msg] = $class[name]." gebaut";
		return $return;
	}
	
	function shipmincost($classId,$userId,$colId) {
	
		$result = $this->getshipcostbyid($classId);
		if ($result == 0) return 1;
		for ($i=0;$i<count($result);$i++) {
			$r_ress = mysql_result(mysql_query("SELECT count FROM stu_colonies_storage WHERE goods_id='".$result[$i][goods_id]."' AND colonies_id='".$colId."' AND user_id='".$userId."'",$this->dblink),0);
			if ($result[$i]['count'] < $r_ress) {
				$goodname = mysql_result(mysql_query("SELECT name FROM stu_goods WHERE id=".$result[$i][goods_id]."",$this->dblink),0);
				$return[msg] = "Es werden ".$result[$i]['count']." ".$goodname." benötigt - Vorhanden sind nur ".$r_ress;
				$return[code] = 0;
				return $return;
			}
		}
		for ($i=0;$i<count($result);$i++) {
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$result[$i]['count']." WHERE goods_id='".$result[$i][goods_id]."' AND colonies_id='".$colId."' AND count>".$result[$i]['count']."",$this->dblink);			
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id='".$result[$i][goods_id]."' AND colonies_id='".$colId."'",$this->dblink);
		}
		return 1;
	}
	
	function getshipcostbyid($classId) {
	
		$result = mysql_query("SELECT * FROM stu_ships_cost WHERE ships_classes_id='".$classId."' ORDER BY goods_id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$data[$i][good] = mysql_fetch_array(mysql_query("SELECT * FROM stu_goods WHERE id='".$data[$i][goods_id]."'",$this->dblink));
		}
		return $data;
	}
	
	function getresearchlist($userId) {
		
		global $myUser;
		$user = $myUser->getuserbyid($userId);
		$result = mysql_query("SELECT * FROM stu_research_list WHERE rasse=0 OR rasse=".$user[rasse]." ORDER BY sort ASC",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$rru = mysql_query("SELECT * FROM stu_research_user WHERE research_id='".$data[$i][id]."' AND user_id='".$userId."'",$this->dblink);
			if (mysql_num_rows($rru) == 0) $data[$i][done] = 0;
			else $data[$i][done] = 1;
		}
		return $data;
	
	}
	
	function getresearchinfobyid($researchId,$userId) {
	
		global $myUser;
		$user = $myUser->getuserbyid($userId);
		$result = mysql_query("SELECT * FROM stu_research_list WHERE id='".$researchId."' AND (rasse=0 OR rasse=".$user[rasse].")",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		$data = mysql_fetch_array($result);
		$rdepencies = mysql_query("SELECT * FROM stu_research_depencies WHERE research_id='".$researchId."'",$this->dblink);
		for ($i=0;$i<mysql_num_rows($rdepencies);$i++) {
			$tmp = mysql_fetch_array($rdepencies);
			$data[depenc][$i] = $this->getresearchbyid($tmp[depency_id]);
			$result = mysql_query("SELECT * FROM stu_research_user WHERE research_id='".$tmp[depency_id]."' AND user_id='".$userId."'",$this->dblink);
			if (mysql_num_rows($result) == 1) $data[depenc][$i][done] = 1;
			else $data[depenc][$i][done] = 0;
		}
		$rpossible = mysql_query("SELECT * FROM stu_research_depencies WHERE depency_id='".$researchId."'",$this->dblink);
		for ($i=0;$i<mysql_num_rows($rpossible);$i++) {
			$tmp = mysql_fetch_array($rpossible);
			$data[possible][$i] = $this->getresearchbyid($tmp[research_id]);
			$result = mysql_query("SELECT * FROM stu_research_user WHERE research_id='".$tmp[research_id]."' AND user_id='".$userId."'",$this->dblink);
			if (mysql_num_rows($result) == 1) $data[possible][$i][done] = 1;
			else $data[possible][$i][done] = 0;
		}
		return $data;
	}
	
	function getresearchbyid($researchId) {
		
		global $myUser,$user;
		$userdat = $myUser->getuserbyid($user);
		$result = mysql_query("SELECT * FROM stu_research_list WHERE id='".$researchId."' AND (rasse=0 OR rasse=".$userdat[rasse].")",$this->dblink);
		return mysql_fetch_array($result);
	
	}
	
	function getcountbygoodid($goodId,$colId) {
	
		$result = mysql_query("SELECT count FROM stu_colonies_storage WHERE colonies_id='".$colId."' AND goods_id='".$goodId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return -1;
		return mysql_fetch_array($result);
	}
	
	function research($researchId,$colId,$userId) {
	
		$research = $this->getresearchbyid($researchId);
		$result = mysql_query("SELECT * FROM stu_research_user WHERE research_id='".$researchId."' AND user_id='".$userId."'",$this->dblink);
		if (mysql_num_rows($result) == 1) {
			$return[msg] = $research[name]." wurde bereits erforscht";
			return $return;
		}
		$count = $this->getcountbygoodid(10,$colId);
		if ($count['count'] < $research[cost]) {
			$return[msg] = "Nicht genügend Iso-Chips vorhanden";
			return $return;
		}
		$rdepencies = mysql_query("SELECT * FROM stu_research_depencies WHERE research_id='".$researchId."'",$this->dblink);
		for ($i=0;$i<mysql_num_rows($rdepencies);$i++) {
			$tmp = mysql_fetch_array($rdepencies);
			$data = $this->getresearchbyid($tmp[depency_id]);
			$result = mysql_query("SELECT * FROM stu_research_user WHERE research_id='".$tmp[depency_id]."' AND user_id='".$userId."'",$this->dblink);
			if (mysql_num_rows($result) == 0) {
				$return[msg] = $data[name]." wurde noch nicht erforscht";
				return $return;
			}
		}
		mysql_query("UPDATE stu_colonies_storage SET count=count-".$research[cost]." WHERE goods_id=10 AND colonies_id='".$colId."' AND user_id='".$userId."' AND count>".$research[cost]."",$this->dblink);
		if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id=10 AND colonies_id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		mysql_query("INSERT INTO stu_research_user (research_id,user_id) VALUES ('".$researchId."','".$userId."')",$this->dblink);
		if ($researchId == 5) {
			global $myUser;
			$userdata = $myUser->getuserbyid($userId);
			if ($userdata[rasse] == 1) mysql_query("INSERT INTO stu_ships_build (user_id,ships_classes_id) VALUES ('".$userId."','10')",$this->dblink);
			elseif ($userdata[rasse] == 2) mysql_query("INSERT INTO stu_ships_build (user_id,ships_classes_id) VALUES ('".$userId."','18')",$this->dblink);
			elseif ($userdata[rasse] == 3) mysql_query("INSERT INTO stu_ships_build (user_id,ships_classes_id) VALUES ('".$userId."','17')",$this->dblink);
			elseif ($userdata[rasse] == 4) mysql_query("INSERT INTO stu_ships_build (user_id,ships_classes_id) VALUES ('".$userId."','22')",$this->dblink);
		}
		if ($research[ships_id] > 0) mysql_query("INSERT INTO stu_ships_build (user_id,ships_classes_id) VALUES ('".$userId."','".$research[ships_id]."')",$this->dblink);
		$return[msg] = $research[name]." wurde erforscht";
		return $return;
	}
	
	function loadbatt($id,$colId,$count,$userId) {
	
		global $myShip;
		$coldata = $this->getcolonydatabyid($colId,$userId);
		$shipdata = $myShip->getdatabyid($id);
		$class = $myShip->getclassbyid($shipdata[ships_classes_id]);
		if (($shipdata == 0) || ($coldata == 0)) {
			$return[msg] = "Kolonie oder Schiff nicht vorhanden.";
			$return[code] = -1;
			return $return;
		}
		if (($coldata[coords_x] != $shipdata[coords_x]) || ($coldata[coords_y] != $shipdata[coords_y])) {
			$return[msg] = "Schiff und Kolonie müssen sich im selben Sektor befinden.";
			$return[code] = -1;
			return $return;
		}
		if ($count == "max") $count = $coldata[energie];
		if ($coldata[energie] < $count) $count = $coldata[energie];
		if ($shipdata[batt] + $count > $class[max_batt]) $count = $class[max_batt] - $shipdata[batt];
		mysql_query("UPDATE stu_colonies SET energie=energie-".$count." WHERE id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_ships SET batt=batt+".$count." WHERE id='".$id."'",$this->dblink);
		$return[msg] = "Die Ersatzbatterie der ".$shipdata[name]." wurde um ".$count." aufgeladen";
		$return[code] = 1;
		return $return;
	
	}
	
	function replikator($source,$end,$count,$colId,$userId) {
	
		if ($source == $end) {
			$return[msg] = "Die Ware kann nicht repliziert werden";
			return $return;
		}
		if (($source != 1) && ($source != 3) && ($source != 4) && ($source != "torpho") && ($source != "torpla") && ($source != "torqua") && ($source != 20)) {
			$return[msg] = "Es können nur Nahrung, Baumaterial, Biomimetisches Gel und Iridium-Erz zur Replikation genutzt werden";
			return $return;
		}
		if ($end == 7) {
			$result = mysql_query("SELECT id FROM stu_research_user WHERE research_id=10 AND user_id=".$userId."",$this->dblink);
			if (mysql_num_rows($result) == 0) {
				$return[msg] = "Du musst zuerst die Photonentorpedoreplikation erforschen";
				return $return;
			}
		} elseif ($end == 16) {
			$result = mysql_query("SELECT id FROM stu_research_user WHERE research_id=9 AND user_id=".$userId."",$this->dblink);
			if (mysql_num_rows($result) == 0) {
				$return[msg] = "Du musst zuerst die Plasmatorpedoreplikation erforschen";
				return $return;
			}
		} elseif ($end == 17) {
			$result = mysql_query("SELECT id FROM stu_research_user WHERE research_id=39 AND user_id=".$userId."",$this->dblink);
			if (mysql_num_rows($result) == 0) {
				$return[msg] = "Du musst zuerst die Quantentorpedoreplikation erforschen";
				return $return;
			}
		} elseif ($end == 19) {
			$result = mysql_query("SELECT id FROM stu_research_user WHERE research_id=38 AND user_id=".$userId."",$this->dblink);
			if (mysql_num_rows($result) == 0) {
				$return[msg] = "Du musst zuerst die Biogenetik erforschen";
				return $return;
			}
		}
		if (($source == 1) || ($source == 3)) {
			if (($end != 1) && ($end != 3)) {
				$return[msg] = "Replikation fehlgeschlagen";
				return $return;
			}
			$storcount = $this->getcountbygoodid($source,$colId);
			if ($storcount['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource nicht vorhanden";
				return $return;
			}
			$data = $this->getcolonybyid($colId);
			if ($data[energie] == 0) {
				$return[msg] = "Keine Energie vorhanden";
				return $return;
			}
			if ($storcount['count'] < $count) $count = $storcount['count'];
			if ($data[energie] < $count) $count = $data[energie];
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=".$source." AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=".$source." AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count+".$count." WHERE colonies_id=".$colId." AND goods_id=".$end." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','".$end."','".$count."')",$this->dblink);
			mysql_query("UPDATE stu_colonies SET energie=energie-".$count." WHERe id='".$colId."' AND user_id='".$userId."'",$this->dblink);
			$res = mysql_query("SELECT name FROM stu_goods WHERE id='".$source."'",$this->dblink);
			$good1 = mysql_fetch_array($res);
			$res = mysql_query("SELECT name FROM stu_goods WHERE id='".$end."'",$this->dblink);
			$good2 = mysql_fetch_array($res);
			$return[msg] = "Leite Replikation ein...".$count." ".$good1[name]." zu ".$count." ".$good2[name]." umgewandelt - ".$count." Energie verbraucht";
			return $return;
		} elseif (($source == "torpho") && ($end == 7)) {
			$storcount1 = $this->getcountbygoodid(2,$colId);
			if ($storcount1['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Deuterium nicht vorhanden";
				return $return;
			}
			if ($storcount1['count'] < $count) $count = $storcount1['count'];
			$storcount2 = $this->getcountbygoodid(3,$colId);
			if ($storcount2['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Baumaterial nicht vorhanden";
				return $return;
			}
			if ($storcount2['count'] < $count) $count = $storcount2['count'];
			$storcount3 = $this->getcountbygoodid(5,$colId);
			if ($storcount3['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Antimaterie nicht vorhanden";
				return $return;
			}
			if ($storcount3['count'] < $count) $count = $storcount3['count'];
			$storcount4 = $this->getcountbygoodid(6,$colId);
			if ($storcount4['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Duranium nicht vorhanden";
				return $return;
			}
			if ($storcount4['count'] < $count) $count = $storcount4['count'];
			$data = $this->getcolonybyid($colId);
			if ($data[energie] == 0) {
				$return[msg] = "Keine Energie vorhanden";
				return $return;
			}
			if ($data[energie] < $count) $count = $data[energie];
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=2 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=2 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=3 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=3 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=5 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=5 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=6 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=6 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count+".$count." WHERE colonies_id=".$colId." AND goods_id=7 AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','7','".$count."')",$this->dblink);
			mysql_query("UPDATE stu_colonies SET energie=energie-".$count." WHERe id='".$colId."' AND user_id='".$userId."'",$this->dblink);
			$return[msg] = "Leite Replikation ein...".$count." Deuterium,Baumaterial,Antimaterie und Duranium zu ".$count." Photonentorpedos umgewandelt - ".$count." Energie verbraucht";
			return $return;
		} elseif (($source == "torpla") && ($end == 16)) {
			$storcount1 = $this->getcountbygoodid(6,$colId);
			if ($storcount1['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Duranium nicht vorhanden";
				return $return;
			}
			if ($storcount1['count'] < $count) $count = $storcount1['count'];
			$storcount2 = $this->getcountbygoodid(3,$colId);
			if ($storcount2['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Baumaterial nicht vorhanden";
				return $return;
			}
			if ($storcount2['count'] < $count) $count = $storcount2['count'];
			$storcount3 = $this->getcountbygoodid(9,$colId);
			if ($storcount3['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Tritanium nicht vorhanden";
				return $return;
			}
			if ($storcount3['count'] < $count) $count = $storcount3['count'];
			$storcount4 = $this->getcountbygoodid(15,$colId);
			if ($storcount4['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Plasma nicht vorhanden";
				return $return;
			}
			if ($storcount4['count'] < $count) $count = $storcount4['count'];
			$data = $this->getcolonybyid($colId);
			if ($data[energie] == 0) {
				$return[msg] = "Keine Energie vorhanden";
				return $return;
			}
			if ($data[energie] < $count) $count = $data[energie];
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=3 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=3 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=6 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=6 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=9 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=9 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=15 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=15 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count+".$count." WHERE colonies_id=".$colId." AND goods_id=16 AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','16','".$count."')",$this->dblink);
			mysql_query("UPDATE stu_colonies SET energie=energie-".$count." WHERe id='".$colId."' AND user_id='".$userId."'",$this->dblink);
			$return[msg] = "Leite Replikation ein...".$count." Baumaterial, Tritanium, Duranium und Plasma zu ".$count." Plasmatorpedos umgewandelt - ".$count." Energie verbraucht";
			return $return;
		} elseif (($source == "torqua") && ($end == 17)) {
			$storcount1 = $this->getcountbygoodid(2,$colId);
			if ($storcount1['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Deuterium nicht vorhanden";
				return $return;
			}
			if ($storcount1['count'] < $count) $count = $storcount1['count'];
			$storcount2 = $this->getcountbygoodid(3,$colId);
			if ($storcount2['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Baumaterial nicht vorhanden";
				return $return;
			}
			if ($storcount2['count'] < $count) $count = $storcount2['count'];
			$storcount3 = $this->getcountbygoodid(9,$colId);
			if ($storcount3['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Tritanium nicht vorhanden";
				return $return;
			}
			if ($storcount3['count'] < $count) $count = $storcount3['count'];
			$storcount4 = $this->getcountbygoodid(6,$colId);
			if ($storcount4['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Duranium nicht vorhanden";
				return $return;
			}
			if ($storcount4['count'] < $count) $count = $storcount4['count'];
			$storcount5 = $this->getcountbygoodid(8,$colId);
			if ($storcount5['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Dilithium nicht vorhanden";
				return $return;
			}
			if ($storcount5['count'] < $count) $count = $storcount5['count'];
			$storcount6 = $this->getcountbygoodid(5,$colId);
			if ($storcount6['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Antimaterie nicht vorhanden";
				return $return;
			}
			if ($storcount6['count'] < $count) $count = $storcount6['count'];
			$data = $this->getcolonybyid($colId);
			if ($data[energie] == 0) {
				$return[msg] = "Keine Energie vorhanden";
				return $return;
			}
			if ($data[energie] < $count) $count = $data[energie];
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=2 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=2 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=3 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=3 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=5 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=5 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=6 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=6 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=8 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=8 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=9 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=9 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count+".$count." WHERE colonies_id=".$colId." AND goods_id=17 AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','17','".$count."')",$this->dblink);
			mysql_query("UPDATE stu_colonies SET energie=energie-".$count." WHERe id='".$colId."' AND user_id='".$userId."'",$this->dblink);
			$return[msg] = "Leite Replikation ein...".$count." Deuterium, Baumaterial, Antimterie, Duranium, Dilithium und Tritanium zu ".$count." Quantentorpedos umgewandelt - ".$count." Energie verbraucht";
			return $return;
		} elseif (($source == 20) && ($end == 19)) {
			$storcount1 = $this->getcountbygoodid(3,$colId);
			if ($storcount1['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Baumaterial nicht vorhanden";
				return $return;
			}
			if ($storcount1['count'] < $count) $count = $storcount1['count'];
			$storcount2 = $this->getcountbygoodid(20,$colId);
			if ($storcount2['count'] == 0) {
				$return[msg] = "Ausgangs-Ressource Baumaterial nicht vorhanden";
				return $return;
			}
			if ($storcount2['count'] < $count) $count = $storcount2['count'];
			$data = $this->getcolonybyid($colId);
			if ($data[energie] < $count*3) $count = floor($data[energie]/3);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=3 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=3 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=20 AND count>".$count." AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=20 AND count=".$count." AND user_id=".$userId."",$this->dblink);
			mysql_query("UPDATE stu_colonies_storage SET count=count+".$count." WHERE colonies_id=".$colId." AND goods_id=19 AND user_id=".$userId."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','19','".$count."')",$this->dblink);
			mysql_query("UPDATE stu_colonies SET energie=energie-".($count*3)." WHERe id='".$colId."' AND user_id='".$userId."'",$this->dblink);
			$return[msg] = "Leite Replikation ein...".$count." Baumaterial und Biomimetisches Gel zu ".$count." Gel-Packs umgewandelt - ".($count*3)." Energie verbraucht";
			return $return;
		}
	}
	
	function raffinerie($source,$count,$colId,$userId) {
		if ($this->getcolonyDatabyid($colId,$userId) == 0) {
			$return[msg] = "Dies ist nicht Deine Kolonie";
			return $return;
		}
		if (($source != 11) && ($source != 13)) {
			$return[msg] = "Es können nur Kelbonit-Erz und Nitrum-Erz veredelt werden";
			return $return;
		}
		if ($source == 11) $end = 12;
		else $end = 14;
		$storcount = $this->getcountbygoodid($source,$colId);
		if ($storcount['count'] == 0) {
			$return[msg] = "Ausgangs-Ressource nicht vorhanden";
			return $return;
		}
		$data = $this->getcolonybyid($colId);
		if ($data[energie] == 0) {
			$return[msg] = "Keine Energie vorhanden";
			return $return;
		}
		if ($storcount['count'] < $count) $count = $storcount['count'];
		if ($data[energie] < $count) $count = $data[energie];
		mysql_query("UPDATE stu_colonies_storage SET count=count-".$count." WHERE colonies_id=".$colId." AND goods_id=".$source." AND count>".$count." AND user_id=".$userId."",$this->dblink);
		if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id=".$colId." AND goods_id=".$source." AND count=".$count." AND user_id=".$userId."",$this->dblink);
		mysql_query("UPDATE stu_colonies_storage SET count=count+".$count." WHERE colonies_id=".$colId." AND goods_id=".$end." AND user_id=".$userId."",$this->dblink);
		if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (colonies_id,user_id,goods_id,count) VALUES ('".$colId."','".$userId."','".$end."','".$count."')",$this->dblink);
		mysql_query("UPDATE stu_colonies SET energie=energie-".$count." WHERe id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		$res = mysql_query("SELECT name FROM stu_goods WHERE id='".$source."'",$this->dblink);
		$good1 = mysql_fetch_array($res);
		$res = mysql_query("SELECT name FROM stu_goods WHERE id='".$end."'",$this->dblink);
		$good2 = mysql_fetch_array($res);
		$return[msg] = "Leite Veredelung ein...".$count." ".$good1[name]." zu ".$count." ".$good2[name]." umgewandelt - ".$count." Energie verbraucht";
		return $return;
	}
	
	function getuserresearch($researchId,$userId) {
	
		return mysql_num_rows(mysql_query("SELECT * FROM stu_research_user WHERE research_id=".$researchId." AND user_id=".$userId."",$this->dblink));
	
	}
	
	function getbestcols() {
	
		$result = mysql_query("SELECT * FROM stu_colonies WHERE user_id!=2 AND user_id!=5 AND user_id!=13 AND user_id!=30 ORDER BY bev_used+bev_free DESC LIMIT 10",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function getbestfleet() {
	
		$result = mysql_query("SELECT user_id,count(id) as idcount FROM stu_ships WHERE user_id!=2 AND user_id!=5 AND user_id!=13 AND user_id!=30 GROUP BY user_id ORDER BY idcount DESC,user_id ASC LIMIT 10",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function getbestresearch() {
	
		$result = mysql_query("SELECT user_id,count(id) as idcount FROM stu_research_user WHERE user_id!=2 AND user_id!=5 AND user_id!=13 AND user_id!=30 GROUP BY user_id ORDER BY idcount DESC,user_id ASC LIMIT 10",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function getrepaircost ($shipId,$colId) {
	
		global $myShip;
		$shipdata = $myShip->getdatabyid($shipId);
		$class = $myShip->getclassbyid($shipdata[ships_classes_id]);
		$huelle = $class[huelle] - $shipdata[huelle];
		$e = $class[eps_cost]/100;
		$bm = ((($class[huelle]*2)/3 + $class[shields]/3 + $class[cloak]*($class[huelle]/10) + $class[phaser] + $class[energie]/10))/100;
		$dur = (($class[huelle]/3) + ($class[shields]/4) + ($$class[cloak]*($class[huelle]/15)) + $class[motor] + $class[energie]/10)/100;
		if ($class[id] > 7) {
			$tri = ($class[huelle]/5 + $class[shields]/6)/100;
			$kel = ($class[huelle]/6)/100;
		}
		$cost[1] = ceil($e*$huelle);
		$cost[3] = ceil($bm*$huelle);
		$cost[6] = ceil($dur*$huelle);
		$cost[9] = ceil($tri*$huelle);
		$cost[12] = ceil($kel*$huelle);
		return $cost;
	}
	
	function repairship ($shipId,$colId,$userId) {
	
		global $myShip;
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) {
			$return[msg] = "Kolonie nicht vorhanden";
			return $return;
		}
		$shipdata = $myShip->getdatabyid($shipId);
		if ($shipdata == 0) {
			$return[msg] = "Schiff nicht vorhanden";
			return $return;
		}
		if (($shipdata[coords_x] != $data[coords_x]) || ($shipdata[coords_y] != $data[coords_y])) {
			$return[msg] = "Das Schiff muss sich im selben Sektor wie die Kolonie befinden";
			return $return;
		}
		$class = $myShip->getclassbyid($shipdata[ships_classes_id]);
		if ($class[huelle] == $shipdata[huelle]) {
			$return[msg] = "Das Schiff ist nicht beschädigt";
			return $return;
		}
		$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=21 AND colonies_id='".$colId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zur Reperatur wird ein aktivierter Raumbahnhof benötigt";
			return $return;
		}
		$result = mysql_query("SELECT id FROM stu_colonies_orbit WHERE buildings_id>25 AND buildings_id<31 AND colonies_id='".$colId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zur Reperatur wird eine Werft benötigt";
			return $return;
		}
		$cost = $this->getrepaircost($shipId,$colId);
		if ($cost[1] > $data[energie]) {
			$return[msg] = "Zur Reperatur wird ".$cost[1]." Energie benötigt - Vorhanden ist nur ".$data[energie];
		}
		$countbm = $this->getcountbygoodid(3,$colId);
		if ($cost[3] > $countbm['count']) {
			$return[msg] = "Zur Reperatur werden ".$cost[3]." Baumaterial benötigt - Vorhanden sind nur ".$count[bm]['count'];
			return $return;
		}
		$countdu = $this->getcountbygoodid(6,$colId);
		if ($cost[6] > $countdu['count']) {
			$return[msg] = "Zur Reperatur werden ".$cost[6]." Duranium benötigt - Vorhanden sind nur ".$count[du]['count'];
			return $return;
		}
		$counttr = $this->getcountbygoodid(9,$colId);
		if ($cost[9] > $countbm['count']) {
			$return[msg] = "Zur Reperatur werden ".$cost[9]." Tritanium benötigt - Vorhanden sind nur ".$count[tr]['count'];
			return $return;
		}
		$countke = $this->getcountbygoodid(12,$colId);
		if ($cost[12] > $countbm['count']) {
			$return[msg] = "Zur Reperatur werden ".$cost[12]." Kelbnoit benötigt - Vorhanden sind nur ".$count[ke]['count'];
			return $return;
		}
		if ($data[energie] < $cost[1]) {
			$return[msg] = "Zur Reperatur wird ".$cost[1]." Energie benötigt - Vorhanden ist nur ".$data[energie];
			return $return;
		}
		mysql_query("UPDATE stu_colonies SET energie=energie-".$cost[1]." WHERE id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_storage SET count=count-".$cost[3]." WHERE colonies_id=".$colId." AND goods_id=3 AND count>".$cost[3]."",$this->dblink);
		if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id='".$colId."' AND goods_id=3 ANd count>".$cost[3]."",$this->dblink);
		mysql_query("UPDATE stu_colonies_storage SET count=count-".$cost[6]." WHERE colonies_id=".$colId." AND goods_id=6 AND count>".$cost[6]."",$this->dblink);
		if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id='".$colId."' AND goods_id=6 ANd count>".$cost[6]."",$this->dblink);
		if ($cost[9] > 0) mysql_query("UPDATE stu_colonies_storage SET count=count-".$cost[9]." WHERE colonies_id=".$colId." AND goods_id=9 AND count>".$cost[9]."",$this->dblink);
		if ($cost[9] > 0) if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id='".$colId."' AND goods_id=9 ANd count>".$cost[9]."",$this->dblink);
		if ($cost[9] > 0) mysql_query("UPDATE stu_colonies_storage SET count=count-".$cost[12]." WHERE colonies_id=".$colId." AND goods_id=12 AND count>".$cost[12]."",$this->dblink);
		if ($cost[9] > 0) if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id='".$colId."' AND goods_id=12 ANd count>".$cost[12]."",$this->dblink);
		mysql_query("UPDATE stu_ships SET huelle=".$class[huelle]." WHERE id='".$shipId."'",$this->dblink);
		$return[msg] = "Das Schiff wurde repariert";
		return $return;
	}
	
	function evacuateCol($colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) {
			$return[msg] = "Dies ist nicht Deine Kolonie";
			return $return;
		}
		$bev = $data[bev_used] + $data[bev_free];
		if ($bev > 0) mysql_query("UPDATE stu_user SET symp=symp-".($bev*5)." WHERE id='".$userId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_fields SET aktiv=0 WHERE colonies_id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE colonies_id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_underground SET aktiv=0 WHERE colonies_id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies SET name='',bev_used=0,bev_free=0,user_id=2,energie=0,max_bev=0,spions=0,secur=0 WHERE id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_storage SET user_id=2 WHERE colonies_id='".$colId."'",$this->dblink);
		if ($bev > 0) $msg = "<br>".($bev*5)." Sympathie abgezogen";
		$return[msg] = "Die Kolonie wurde aufgegeben".$msg;
		return $return;
	}
	
	function destroyCol($colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) {
			$return[msg] = "Dies ist nicht Deine Kolonie";
			return $return;
		}
		$bev = $data[bev_used] + $data[bev_free];
		if ($bev > 0) mysql_query("UPDATE stu_user SET symp=symp-".($bev*15)." WHERE id='".$userId."'",$this->dblink);
		mysql_query("DELETE FROM stu_colonies_fields WHERE colonies_id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies_orbit SET aktiv=0,buildings_id=0 WHERE colonies_id='".$colId."'",$this->dblink);
		mysql_query("UPDATE stu_colonies SET name='',bev_used=0,bev_free=0,user_id=2,energie=0,max_storage=0,max_energie=0,max_bev=0,max_spy=0,def_spy=0,free_spy=0,off_spy=0 WHERE id='".$colId."'",$this->dblink);
		mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id='".$colId."'",$this->dblink);
		if ($data[colonies_classes_id] == 1) include_once("intern/admin/inc/m.inc.php");
		elseif ($data[colonies_classes_id] == 2) include_once("intern/admin/inc/l.inc.php");
		elseif ($data[colonies_classes_id] == 3) include_once("intern/admin/inc/n.inc.php");
		elseif ($data[colonies_classes_id] == 4) include_once("intern/admin/inc/g.inc.php");
		elseif ($data[colonies_classes_id] == 5) include_once("intern/admin/inc/k.inc.php");
		elseif ($data[colonies_classes_id] == 6) include_once("intern/admin/inc/d.inc.php");
		elseif ($data[colonies_classes_id] == 7) include_once("intern/admin/inc/h.inc.php");
		elseif ($data[colonies_classes_id] == 8) include_once("intern/admin/inc/x.inc.php");
		for ($i=0;$i<count($fields);$i++) mysql_query("INSERT INTO stu_colonies_fields (colonies_id,field_id,type) VALUES ('".$colId."','".$i."','".$fields[$i]."')",$this->dblink);
		if ($data[colonies_classes_id] == 1) include_once("intern/admin/inc/um.inc.php");
		elseif ($data[colonies_classes_id] == 2) include_once("intern/admin/inc/ul.inc.php");
		elseif ($data[colonies_classes_id] == 3) include_once("intern/admin/inc/un.inc.php");
		elseif ($data[colonies_classes_id] == 4) include_once("intern/admin/inc/ug.inc.php");
		elseif ($data[colonies_classes_id] == 5) include_once("intern/admin/inc/uk.inc.php");
		elseif ($data[colonies_classes_id] == 7) include_once("intern/admin/inc/uh.inc.php");
		elseif ($data[colonies_classes_id] == 8) include_once("intern/admin/inc/ux.inc.php");
		for ($i=0;$i<count($fields);$i++) mysql_query("INSERT INTO stu_colonies_underground (colonies_id,field_id,type) VALUES ('".$data[id]."','".$i."','".$fields[$i]."')",$this->dblink);
		if ($bev > 0) $msg = "<br>".($bev*15)." Sympathie abgezogen";
		$return[msg] = "Die Kolonie wurde gesprengt".$msg;
		return $return;
	}
	
	function ewopt($colId,$mode,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) {
			$return[msg] = "Dies ist nicht Deine Kolonie";
			return $return;
		}
		if (($mode != 1) && ($mode != 0)) {
			$return[msg] = "Parameterfehler";
			return $return;
		}
		mysql_query("UPDATE stu_colonies SET ewopt=".$mode." WHERE id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		$return[msg] = "Einwanderungseinstellung geändert";
		return $return;
	}
	
	function beammsg($goods,$id,$id2,$userId,$way) {
		
		$result = mysql_query("SELECT name,user_id,coords_x,coords_y from stu_colonies WHERE id='".$id."' AND user_id='".$userId."'", $this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		$data[] = mysql_fetch_array($result);
		$result = mysql_query("SELECT name,user_id from stu_ships WHERE id='".$id2."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		$data[] = mysql_fetch_array($result);
		if ($data[0][user_id] != $data[1][user_id]) {
			include_once("inc/dummymsg.inc.php");
			for ($i=0;$i<count($goods);$i++) {
				$goodData = mysql_fetch_array(mysql_query("SELECT name FROM stu_goods WHERE id='".$goods[$i][id]."'", $this->dblink));
				if ($goods[$i][id]) $dummygood .= $goods[$i]['count']."&nbsp;".$goodData[name]."<br>";
			}
			$message = str_replace("dummybla","der",$dummymsg[0]);
			if ($way == "to") $message = str_replace("dummyway","zu",$message);
			else $message = str_replace("dummyway","von",$message);
			$message = str_replace("dummyship1",$data[0][name],$message);
			$message = str_replace("dummyship2",$data[1][name],$message);
			$message = str_replace("dummygoods",$dummygood,$message);
			$message = str_replace("dummysektor",$data[0][coords_x]."/".$data[0][coords_y],$message);
			global $myComm;
			$myComm->sendpm($data[1][user_id],$data[0][user_id],$message);
		}
	}
	
	function demontship($colId,$shipId,$userId) {
	
		global $myShip;
		$shipdata = $myShip->getdatabyid($shipId);
		$coldata = $this->getcolonydatabyid($colId,$userId);
		if ($coldata == 0) {
			$return[msg] = "Dies ist nicht Deine Kolonie";
			return $return;
		}
		if ($shipdata[user_id] != $userId) {
			$return[msg] = "Das Schiff gehört Dir nicht";
			return $return;
		}
		if (($shipdata[coords_x] != $coldata[coords_x]) || ($shipdata[coords_y] != $coldata[coords_y])) {
			$return[msg] = "Das Schiff muss sich im selben Sektor wie die Kolonie befinden";
			return $return;
		}
		$cost = $this->getshipcostbyid($shipdata[ships_classes_id]);
		$class = $myShip->getclassbyid($shipdata[ships_classes_id]);
		$pro = (100/$class[huelle])*$shipdata[huelle];
		$msg = "Die ".$shipdata[name]." wird demontiert... Es wurden folgende Ressourcen dabei gewonnen<br>";
		for ($i=0;$i<count($cost);$i++) {
			if (($cost[$i][goods_id] == 3) || ($cost[$i][goods_id] == 6) || ($cost[$i][goods_id] == 9)) {
				mysql_query("UPDATE stu_colonies_storage SET count=count+".floor(($cost[$i]['count']/100)*$pro)." WHERE colonies_id='".$colId."' AND goods_id='".$cost[$i][goods_id]."'",$this->dblink);
				if (mysql_affected_rows() == 0) mysql_query("INSERT INTO stu_colonies_storage (user_id,colonies_id,goods_id,count) VALUES ('".$userId."','".$colId."','".$cost[$i][goods_id]."','".$cost[$i]['count']."')",$this->dblink);
				$msg .= floor(($cost[$i]['count']/100)*$pro)." ".$cost[$i][good][name]."<br>";
			}
		}
		mysql_query("DELETE FROM stu_ships_storage WHERE ships_id='".$shipId."' AND user_id='".$userId."'",$this->dblink);
		mysql_query("DELETE FROM stu_ships WHERE id='".$shipId."' AND user_id='".$userId."'",$this->dblink);
		mysql_query("DELETE FROM stu_ships_action WHERE ships_id='".$shipId."' OR ships_id2='".$shipId."'",$this->dblink);
		$return[msg] = $msg;
		return $return;
	}
	
	function teleskop($colId,$coordsx,$coordsy) {
	
		$result = mysql_query("SELECT id FROM stu_colonies_orbit WHERE colonies_id='".$colId."' AND buildings_id='53'",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Im Orbit dieses Planeten befindet sich kein Subraumteleskop";
			$return[code] = 0;
			return $return;
		}
		$result = mysql_query("SELECT id FROM stu_colonies_fields WHERE buildings_id=21 AND colonies_id=".$colId." AND aktiv=1",$this->dblink);
		if (mysql_num_rows($result) == 0) {
			$return[msg] = "Zum scannen wird ein aktivierter Raumbahnhof benötigt";
			$return[code] = 0;
			return $return;
		}
		$data = $this->getcolonybyid($colId);
		if ($data[energie] == 0) {
			$return[msg] = "Keine Energie auf der Kolonie vorhanden";
			$return[code] = 0;
			return $return;
		}
		mysql_query("UPDATE stu_colonies SET energie=energie-1 WHERE id='".$colId."'",$this->dblink);
		global $myMap;
		return $myMap->getfielddata($coordsx,$coordsy);
	}
	
	function checkbuilding($buildingId,$colId) {
	
		$result = mysql_query("SELECT * FROM stu_colonies_fields WHERE colonies_id=".$colId." AND buildings_id='".$buildingId."' AND aktiv=1",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return 1;
	}
	
	function getcolonybysektor($x,$y) {
	
		$result = mysql_query("SELECT * FROM stu_colonies WHERE coords_x='".$x."' AND coords_y='".$y."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	}
	
	function getspy($userId) {
	
		$result = mysql_query("SELECT * FROM stu_spy_action WHERE user_id='".$userId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	
	function getauftragbyid($auftragId,$userId) {
	
		$result = mysql_query("SELECT * FROM stu_spy_action WHERE user_id='".$userId."' AND id='".$auftragId."'",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	}
	
	function repairbuilding($colId,$mode,$fieldId,$userId) {
	
		$coldata = $this->getcolonydatabyid($colId,$userId);
		if ($coldata == 0) {
			$return[msg] == "Diese Kolonie existiert nicht";
			return $return;
		}
		if ($mode == "field") {
			$add = "fields";
			$fielddata = $this->getfielddatabyid($fieldId,$colId);
		} elseif ($mode == "orbit") {
			$add = "orbit";
			$fielddata = $this->getorbitfielddatabyid($fieldId,$colId);
		} elseif ($mode == "ground") {
			$add = "underground";
			$fielddata = $this->getgroundfielddatabyid($fieldId,$colId);
		} else {
			$return[msg] = "Fehler bei der Parameterübergabe";
			return $return;
		}
		$build = $this->getbuildbyid($fielddata[buildings_id]);
		$cost = $this->getbuildingcostbyid($build[id]);
		for ($i=0;$i<count($cost);$i++) {
			$count = $this->getcountbygoodid($cost[$i][goods_id],$colId);
			$rcost = ceil((($cost[$i]['count']/100)*((100/$build[integrity])*($build[integrity]-$fielddata[integrity]))));
			if ($count['count'] < $rcost) {
				if ($count == -1) $scount = 0;
				else $scount = $count['count'];
				$return[msg] = "Es wird ".$rcost." ".$cost[$i][good][name]." benötigt - Vorhanden ist nur ".$scount;
				return $return;
			}
		}
		for ($i=0;$i<count($cost);$i++) {
			mysql_query("UPDATE stu_colonies_storage SET count=count-".$rcost." WHERE goods_id=".$cost[$i][goods_id]." ANd colonies_id='".$colId."' AND user_id='".$userId."' AND count>".$rcost."",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id='".$cost[$i][goods_id]."' AND colonies_id='".$colId."' AND user_id='".$userId."'",$this->dblink);
		}
		mysql_query("UPDATE stu_colonies_".$add." SET integrity=".$build[integrity]." WHERE colonies_id=".$colId." AND field_id=".$fieldId."",$this->dblink);
		$return[msg] = $build[name]." auf Feld ".($fieldId+1)." repariert";
		return $return;
	}
	
	function defendcolony($colId,$shipId) {
	
		$coldata = $this->getcolonybyid($colId);
		if ($coldata[energie] == 0) {
			$return[msg] = "Energiemangel auf der Kolonie - Orbitalverteidigung offline";
			return $return;
		}
		global $myShip;
		$result = mysql_query("SELECT * FROM stu_colonies_orbit WHERE buildings_id=46 AND aktiv=1 AND colonies_id='".$colId."'",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$shipdata = $myShip->getdatabyid($shipId);
			$coldata = $this->getcolonybyid($colId);
			if ($coldata[energie] == 0) {
				$return[msg] = "Energiemangel auf der Kolonie - Orbitalverteidigung offline";
				return $return;
			}
			$data = mysql_fetch_array($result);
			$msg .= "Phaserbatterie  (Feld ".($data[field_id]+1).") schießt auf die ".$shipdata[name]."<br>";
			if ($shipdata[schilde_aktiv] == 1) {
				if ($shipdata[schilde] - 5 <= 0) {
					$huell = $shipdata[huelle] - (5 - $shipdata[schilde]);
					$myShip->deactivatevalue($shipId,"schilde_aktiv",$shipdata[user_id]);
					if ($huell > 0) {
						mysql_query("UPDATE stu_ships SET schilde=0 WHERE id='".$shipId."'",$this->dblink);
						$msg .= "Schilde brechen zusammen - Huelle bei ".$huell;
					} else $msg .= "Hüllenbruch - Das Schiff wurde zerstört";
				} else {
					$schilde = $shipdata[schilde] - 5;
					mysql_query("UPDATE stu_ships SET schilde=".$schilde." WHERE id='".$shipId."'",$this->dblink);
					$msg .= "Schilde bei ".$schilde;
					$huell = $shipdata[huelle];
				}
			} else {
				$huell = $shipdata[huelle] - 5;
				if ($huell < 0) $msg .= "Hüllenbruch - Das Schiff wurde zerstört";
				else {
					mysql_query("UPDATE stu_ships SET huelle=".$huell." WHERE id=".$shipId."",$this->dblink);
					$msg .= "Hülle bei ".$huell;
				}
			}
			if ($huell < 1) $myShip->trumfield($shipId);
			$msg .= "<br>";
			mysql_query("UPDATE stu_colonies SET energie=energie-1 WHERe id='".$colId."'",$this->dblink);
		}
		$result = mysql_query("SELECT * FROM stu_colonies_orbit WHERE buildings_id=48 OR buildings_id=49 OR buildings_id=50 OR aktiv=1 AND colonies_id='".$colId."'",$this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$shipdata = $myShip->getdatabyid($shipId);
			$coldata = $this->getcolonybyid($colId);
			if ($coldata[energie] == 0) {
				$return[msg] = "Energiemangel auf der Kolonie - Orbitalverteidigung offline";
				return $return;
			}
			$data = mysql_fetch_array($result);
			if ($data[buildings_id] == 48) {
				$schaden = 10;
				$type = "Photonentorpedo";
				$count = $this->getcountbygoodid(7,$colId);
				$good = 7;
			} elseif ($data[buildings_id] == 49) {
				$schaden = 13;
				$type = "Plasmatorpedo";
				$good = 16;
				$count = $this->getcountbygoodid(16,$colId);
			} else {
				$schaden = 16;
				$type = "Quantentorpedo";
				$good = 17;
				$count = $this->getcountbygoodid(17,$colId);
			}
			if ($count['count'] > 0) {
				$msg .= "Torpedoplattform  (Feld ".($data[field_id]+1).") schießt einen ".$type." auf die ".$shipdata[name]."<br>";
				if ($shipdata[schilde_aktiv] == 1) {
					if ($shipdata[schilde] - $schaden <= 0) {
						$huell = $shipdata[huelle] - ($schaden - $shipdata[schilde]);
						$myShip->deactivatevalue($shipId,"schilde_aktiv",$shipdata[user_id]);
						if ($huell > 0) {
							mysql_query("UPDATE stu_ships SET schilde=0 WHERE id='".$shipId."'",$this->dblink);
							$msg .= "Schilde brechen zusammen - Huelle bei ".$huell;
						} else $msg .= "Hüllenbruch - Das Schiff wurde zerstört";
					} else {
						$schilde = $shipdata[schilde] - $schaden;
						mysql_query("UPDATE stu_ships SET schilde=".$schilde." WHERE id='".$shipId."'",$this->dblink);
						$msg .= "Schilde bei ".$schilde;
						$huell = $shipdata[huelle];
					}
				} else {
					$huell = $shipdata[huelle] - $schaden;
					if ($huell < 0) $msg .= "Hüllenbruch - Das Schiff wurde zerstört";
					else {
						mysql_query("UPDATE stu_ships SET huelle=".$huell." WHERE id=".$shipId."",$this->dblink);
						$msg .= "Hülle bei ".$huell;
					}
				}
				if ($huell < 1) $myShip->trumfield($shipId);
				$msg .= "<br>";
				mysql_query("UPDATE stu_colonies SET energie=energie-1 WHERe id='".$colId."'",$this->dblink);
			}
			mysql_query("UPDATE stu_colonies_storage SET count=count-1 WHERE colonies_id=".$colId." AND goods_id=".$good." AND count>1",$this->dblink);
			if (mysql_affected_rows() == 0) mysql_query("DELETE FROM stu_colonies_storage WHERE goods_id=".$good." AND colonies_id=".$colId."",$this->dblink);
			unset($count);
		}
		$return[msg] = $msg;
		return $return;
	}
	
	function buildcheck($colId,$userId) {
	
		$data = $this->getcolonydatabyid($colId,$userId);
		if ($data == 0) {
			$return[msg] = "Dies ist nicht Deine Kolonie";
			$return[code] = -1;
			return $return;
		}
		$result = mysql_query("SELECT * FROM stu_colonies_fields WHERE (buildings_id=1 OR buildings_id=23) AND colonies_id=".$colId."",$this->dblink);
		if (mysql_num_rows($result) == 0) $return[code] = 0;
		else $return[code] = 1;
		$return[msg] = "Die Koloniezentrale auf dieser Kolonie wurde zersört.";
		return $return;
	}
	
	function newkolozent($colId,$userId) {
		
		$res = $this->buildcheck($colId,$userId);
		if ($res[code] == 0) {
			$data = $this->getcolonydatabyid($colId,$userId);
			mysql_query("UPDATE stu_colonies SET max_bev=max_bev+5 WHERE id='".$colId."'",$this->dblink);
			if ($data[colonies_classes_id] == 1) mysql_query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id='".$colId."'",$this->dblink);
			elseif ($data[colonies_classes_id] == 2) mysql_query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id='".$colId."'",$this->dblink);
			elseif ($data[colonies_classes_id] == 3) mysql_query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id='".$colId."'",$this->dblink);
			elseif ($data[colonies_classes_id] == 4) mysql_query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id='".$colId."'",$this->dblink);
			elseif ($data[colonies_classes_id] == 5) mysql_query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id='".$colId."'",$this->dblink);
			elseif ($data[colonies_classes_id] == 6) mysql_query("UPDATE stu_colonies_fields SET buildings_id='23',aktiv=1,integrity=100 WHERE field_id='17' AND colonies_id='".$colId."'",$this->dblink);
			elseif ($data[colonies_classes_id] == 7) mysql_query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id='".$colId."'",$this->dblink);
			elseif ($data[colonies_classes_id] == 8) mysql_query("UPDATE stu_colonies_fields SET buildings_id='1',aktiv=1,integrity=100 WHERE field_id='22' AND colonies_id='".$colId."'",$this->dblink);
			$return[msg] = "Es wurde eine neue Koloniezentrale errichtet";
			return $return;
		}
	}
	
	function changebuildname($colId,$mode,$name,$fieldId,$userId) {
	
		if ($mode == "field") $add = "fields";
		elseif ($mode == "orbit") $add = "orbit";
		elseif ($mode == "ground") $add = "ground";
		else {
			$return[msg] = "Fehler bei der Parameterübergabe";
			return $return;
		}
		mysql_query("UPDATE stu_colonies_".$add." SET name='".$name."' WHERE field_id='".$fieldId."' AND colonies_id='".$colId."'",$this->dblink);
		$return[msg] = "Der Name wurde geändert";
		return $return;	
	}
	
	function addplanet($x,$y,$type,$wese) {
		
		global $myMap,$global_path;
		$coords = $myMap->getfieldbycoords($x,$y,$wese);
		if (($type != 6 && $coords[type] != 1) || ($type == 6 && $coords[type] != 5))
		{
			$return[msg] = "Der Planet kann nur im freien Raum eingefügt werden";
			return $return;
		}
		if ($type < 1 || $type > 10)
		{
			$return[msg] = "Ungültiger Typ";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_colonies WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese,1) != 0)
		{
			$return[msg] = "Hier befindet sich bereits eine Kolonie";
			return $return;
		}
		$id = $this->db->query("INSERT INTO stu_colonies (colonies_classes_id,coords_x,coords_y,user_id,wese) VALUES ('".$type."','".$x."','".$y."','2','".$wese."')",5);
		if ($type == 1) include_once($global_path."intern/inc/m.inc.php");
		elseif ($type == 2) include_once($global_path."intern/inc/l.inc.php");
		elseif ($type == 3) include_once($global_path."intern/inc/n.inc.php");
		elseif ($type == 4) include_once($global_path."intern/inc/g.inc.php");
		elseif ($type == 5) include_once($global_path."intern/inc/k.inc.php");
		elseif ($type == 6) include_once($global_path."intern/inc/d.inc.php");
		elseif ($type == 7) include_once($global_path."intern/inc/h.inc.php");
		elseif ($type == 8) include_once($global_path."intern/inc/x.inc.php");
		elseif ($type == 9) include_once($global_path."intern/inc/j.inc.php");
		elseif ($type == 10) include_once($global_path."intern/inc/r.inc.php");
		for ($i=0;$i<count($fields);$i++) $this->db->query("INSERT INTO stu_colonies_fields (colonies_id,field_id,type) VALUES ('".$id."','".$i."','".$fields[$i]."')");
		if (($type == 6) || ($type == 9)) $mul = 14;
		else $mul = 18;
		if ($type == 9)
		{
			include_once($global_path."intern/inc/oj.inc.php");
			for ($i=0;$i<$mul;$i++) $this->db->query("INSERT INTO stu_colonies_orbit (colonies_id,field_id,type) VALUES ('".$id."','".$i."','".$fields[$i]."')");
		}
		else
		{
			for ($i=0;$i<$mul;$i++) $this->db->query("INSERT INTO stu_colonies_orbit (colonies_id,field_id,type) VALUES ('".$id."','".$i."','12')");
		}
		if (($type != 6) && ($type != 9)) {
			if ($type == 1) include_once($global_path."intern/inc/um.inc.php");
			elseif ($type == 2) include_once($global_path."intern/inc/ul.inc.php");
			elseif ($type == 3) include_once($global_path."intern/inc/un.inc.php");
			elseif ($type == 4) include_once($global_path."intern/inc/ug.inc.php");
			elseif ($type == 5) include_once($global_path."intern/inc/uk.inc.php");
			elseif ($type == 7) include_once($global_path."intern/inc/uh.inc.php");
			elseif ($type == 8) include_once($global_path."intern/inc/ux.inc.php");
			elseif ($type == 10) include_once($global_path."intern/inc/ur.inc.php");
			for ($i=0;$i<count($fields);$i++) $this->db->query("INSERT INTO stu_colonies_underground (colonies_id,field_id,type) VALUES ('".$id."','".$i."','".$fields[$i]."')");
		}
		if ($type == 1)
		{
			$this->db->query("UPDATE stu_map_fields SET type=6 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(90,110)/100);
		}
		elseif ($type == 2)
		{
			$this->db->query("UPDATE stu_map_fields SET type=7 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(86,115)/100);
		}
		elseif ($type == 3)
		{
			$this->db->query("UPDATE stu_map_fields SET type=8 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(85,115)/100);
		}
		elseif ($type == 4)
		{
			$this->db->query("UPDATE stu_map_fields SET type=10 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(80,120)/100);
		}
		elseif ($type == 5)
		{
			$this->db->query("UPDATE stu_map_fields SET type=9 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(80,120)/100);
		}
		elseif ($type == 6) $grav = (rand(10,40)/100);
		elseif ($type == 7)
		{
			$this->db->query("UPDATE stu_map_fields SET type=23 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(75,100)/100);
		}
		elseif ($type == 8)
		{
			$this->db->query("UPDATE stu_map_fields SET type=24 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(100,140)/100);
		}
		elseif ($type == 9)
		{
			$this->db->query("UPDATE stu_map_fields SET type=25 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(500,800)/100);
		}
		elseif ($type == 10)
		{
			$this->db->query("UPDATE stu_map_fields SET type=29 WHERE coords_x='".$x."' AND coords_y='".$y."' AND wese=".$wese);
			$grav = (rand(86,115)/100);
		}
		echo mysql_error();
		if ($type == 6) $dur = rand(50400,86400);
		elseif ($type == 9) $dur = rand(10800,32400);
		elseif ($type == 10) $dur = 0;
		else $dur = rand(32400,54000);
		$this->db->query("UPDATE stu_colonies SET dn_duration=".$dur.",dn_nextchange=".(time()+$dur).",gravi=".$grav." WHERE id=".$id);
		$return[msg] = "Planet erstellt";
		return $return;
	}

	function getmodulebytype($type) {
	
		$result = mysql_query("SELECT id,name,lvl,wirt,buildtime,huell,eps,phaser,torp_evade,reaktor,phaser_chance,lss_range,shields,goods_id,ecost,besonder FROM stu_ships_modules WHERE type=".$type." ORDER BY lvl ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}

	function getmissionships() {
	
		$result = mysql_query("SELECT a.*,b.goods_id FROM stu_ships as a left outer join stu_ships_storage as b on a.id = b.ships_id WHERE (b.goods_id >= 300 OR b.goods_id = 210) AND a.user_id > 100 ORDER BY b.goods_id ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}

	function getracespaceships() {
	
		$result = mysql_query("SELECT a.*,b.race FROM stu_ships as a left outer join stu_map_fields as b on a.coords_x = b.coords_x WHERE a.coords_y = b.coords_y AND a.wese = b.wese AND b.race != 0 AND b.race != 16 AND a.user_id > 100 ORDER BY b.race ASC",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}

	function getpufferx($y) {
	
		$result = mysql_query("SELECT coords_x from stu_map_fields WHERE coords_y='".$y."' AND wese=2 AND race=27 ORDER by coords_x ASC", $this->dblink);
		return mysql_fetch_array($result);
	}
	
	function makepuffer($x,$y) {
	
		mysql_query("UPDATE stu_map_fields SET race=98 WHERE wese=2 AND coords_x=".$x." AND coords_y=".$y."",$this->dblink);
	}

	function getuserids() {
	
		$result = mysql_query("SELECT id, rasse from stu_user WHERE id > 100 ORDER by id ASC", $this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}
	function getmodids() {
	
		$result = mysql_query("SELECT id from stu_ships_modules WHERE view = 1 ORDER by id ASC", $this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	}

	function giverump($rump,$user)
	{
		$result = mysql_query("SELECT id from stu_ships_build WHERE ships_rumps_id = ".$rump." AND user_id = ".$user." ORDER by id ASC", $this->dblink);
		if (mysql_num_rows($result) == 0) $this->db->query("INSERT INTO stu_ships_build (ships_rumps_id,user_id) VALUES ('".$rump."','".$user."')",$this->dblink);
	}

	function givecost($module)
	{
		$result = mysql_query("SELECT id from stu_ships_modules_cost WHERE modules_id = ".$module." ORDER by id ASC", $this->dblink);
		if (mysql_num_rows($result) == 0) $this->db->query("INSERT INTO stu_ships_modules_cost (modules_id,goods_id,count) VALUES ('".$module."','3','1')",$this->dblink);
	}
}
?>
