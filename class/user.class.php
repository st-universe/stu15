<?php
class user
{
	function user()
	{
		global $myDB,$user;
		$this->db = $myDB;
		if ($user > 0 && is_numeric($user))
		{
			$this->uid = $user;
			$data = $this->db->query("SELECT a.user,a.allys_id,a.status,a.kn_lz,a.kn_allylz,a.rasse,a.level,a.symp,a.delmark,a.wirtmin,a.wirtplus,a.lastloginround,a.mozilla,a.picture,a.grafik,a.vac,a.pvac,a.hasperr,a.knsperr,a.halfnpc,a.knanl,b.icq FROM stu_user as a LEFT OUTER JOIN stu_user_profiles as b ON a.id=b.user_id WHERE a.id=".$user,4);
			$this->uuser = stripslashes($data[user]);
			$this->ually = $data[allys_id];
			$this->ustatus = $data[status];
			$this->ukn_lz = $data[kn_lz];
			$this->ukn_allylz = $data[kn_allylz];
			$this->urasse = $data[rasse];
			$this->ulevel = $data[level];
			$this->usymp = $data[symp];
			$this->uwirtmin = $data[wirtmin];
			$this->udelmark = $data[delmark];
			$this->uvac = $data[vac];
			$this->upvac = $data[pvac];
			$this->ulastloginround = $data[lastloginround];
			$this->uwirtplus = $data[wirtplus];
			$this->uicq = $data[icq];
			$this->umozilla = $data[mozilla];
			$this->upicture = $data[picture];
			$this->ugrafik = $data[grafik];
			$this->uhasperr = $data[hasperr];
			$this->uknsperr = $data[knsperr];
			$this->uhalfnpc = $data[halfnpc];
			$this->uknanl = $data[knanl];
		}
		$this->user = $user;
	}
	
	function getUserByID($userId) { return $this->db->query("SELECT *,UNIX_TIMESTAMP(lastaction) as last_tsp from stu_user WHERE id='".$userId."'",4); }
	
	function getMainUserByID($userId) { return $this->db->query("SELECT grafik,mozilla,lastloginround from stu_user WHERE id='".$userId."'",4); }
	
	function updateUserById($userId,$value,$field) { $this->db->query("UPDATE stu_user SET ".$field."='".str_replace("\"","",$value)."' WHERE id='".$userId."'"); }
	
	function addUser($login,$user,$email,$pw,$seite,$runde)
	{
		$login = str_replace("\'","",$login);
		if ($this->db->query("SELECT COUNT(id) FROM stu_user WHERE id>100 AND aktiv=1",1) == 1000)
		{
			$return[msg] = "Das User-Limit von 1000 wurde erreicht";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_user WHERE login='".addslashes($login)."' OR email='".addslashes($email)."'",1) != 0)
		{
			$return[msg] = "Es ist existiert bereits ein Spieler mit der selben Emailadresse oder dem selben Loginnamen";
			return $return;
		}
		$actcode = md5((strlen($login.$seite.$runde))*144);
		$id = $this->db->query("INSERT INTO stu_user (user,pass,email,login,rasse,startrunde,aktiv,act_code,lastloginround) VALUES ('".addslashes($user)."','".md5($pw)."','".$email."','".str_replace("'","",$login)."','".$seite."','".$runde."','0','".$actcode."','".$runde."')",5);
		$this->db->query("INSERT INTO stu_user_profiles (user_id,sl_sorttype,sl_sortway) VALUES ('".$id."','class','down')");
		mail($email,"STU Anmeldung","Hallo ".$login."<br>
		Du erhälst hiermit Deinem Aktivierungscode für STU. Klicke einfach auf den Link<br>
		<a href=http://www.stuniverse.de/index.php?page=register&actcode=".$actcode."&user=".$id." target=_blank>Aktivierung</a><br><br>
		( Für AOL-User (oder wenn der Link nicht korrekt dargestellt wird): http://www.stuniverse.de/index.php?page=register&actcode=".$actcode."&user=".$id." )<br><br>
		Mit freundlichen Grüßen<br><br>
		Das STU-Team","From: Star Trek Universe <automail@stuniverse.de>
Content-Type: text/html");
		global $myGame;
		$myGame->addlog(100,8,$id,"Registrierung");
		$return[code] = 1;
		$return[msg] = "Der Account wurde angelegt.<br>Du erhälst in Kürze eine Email mit einem Aktivierungscode.<br><br>
		Viel Spaß";
		return $return;
	}
	
	function getcolship()
	{
		if ($this->db->query("SELECT COUNT(id) FROM stu_ships WHERE ships_rumps_id='1' AND user_id=".$this->user,1) < 1)
		{
			if ($this->db->query("SELECT COUNT(id) FROM stu_colonies WHERE colonies_classes_id='1' AND user_id=".$this->user,1) < 1) return 1;
			else return 0;
		}
		else return -1;
	}
	
	function getUser($sort,$way,$se)
	{
		if ($way == "up") $sql_way = "DESC";
		else $sql_way = "ASC";
		if ($sort == "id") $sql_order = "a.id";
		elseif ($sort == "name") $sql_order = "a.user";
		elseif ($sort == "symp") $sql_order = "a.symp";
		elseif ($sort == "round") $sql_order = "a.startrunde";
		elseif ($sort == "ally") $sql_order = "b.name";
		else $sql_order = "a.id";
		return $this->db->query("SELECT a.id,a.user,a.symp,a.startrunde,a.rasse,a.level,UNIX_TIMESTAMP(a.lastaction) as last_tsp,a.vac,b.name FROM stu_user as a LEFT OUTER JOIN stu_allys as b ON a.allys_id=b.id WHERE a.status!=9 AND a.aktiv=1 ORDER BY ".$sql_order." ".$sql_way." LIMIT ".(($se-1)*50).",50");
	}
	
	function findUser($txt,$sort,$way,$se)
	{
		if ($way == "up") $sql_way = "DESC";
		else $sql_way = "ASC";
		if ($sort == "id") $sql_order = "a.id";
		elseif ($sort == "name") $sql_order = "a.user";
		elseif ($sort == "symp") $sql_order = "a.symp";
		elseif ($sort == "round") $sql_order = "a.startrunde";
		elseif ($sort == "ally") $sql_order = "b.name";
		else $sql_order = "a.id";
		return $this->db->query("SELECT a.id,a.user,a.symp,a.startrunde,a.rasse,a.level,UNIX_TIMESTAMP(a.lastaction) as last_tsp,a.vac,b.name FROM stu_user as a LEFT OUTER JOIN stu_allys as b ON a.allys_id=b.id WHERE a.status!=9 AND a.aktiv=1 AND a.user LIKE '%".addslashes(strip_tags($txt))."%' ORDER BY ".$sql_order." ".$sql_way." LIMIT ".(($se-1)*50).",50");
	}
	
	function getnextlevel()
	{
		if ($this->ulevel == 8) return 0;
		return $this->db->query("SELECT level,symp FROM stu_user_levels WHERE level>".$this->ulevel." ORDER BY level ASC LIMIT 1",4);
	}
	
	function setlevel()
	{
		$data = $this->getnextlevel($this->level);
		if ($data == 0)
		{
			$return[msg] = "Du hast bereits das höchste Level (8)";
			return $return;
		}
		if ($this->ulevel == 0)
		{
			$return[msg] = "Um Level 1 zu erreichen musst Du ein Kolonieschiff beantragen";
			return $return;
		}
		if ($this->ulevel  == 1)
		{
			$return[msg] = "Um Level 2 zu erreichen musst Du einen Klasse-M Planeten kolonisieren";
			return $return;
		}
		if ($this->usymp < $data[symp])
		{
			$return[msg] = "Es wird ".$data[symp]." Sympathie für Level ".$data[level]." benötigt - Vorhanden ist nur ".$this->usymp;
			return $return;
		}
		$this->db->query("UPDATE stu_user SET level=".$data[level]." WHERE id=".$this->user);
		if ($this->ulevel == 7)
		{
			$this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','4')");
			$this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','5')");
			$this->db->query("INSERT INTO stu_ships_build (user_id,ships_rumps_id) VALUES ('".$this->user."','6')");
		}
		$return[msg] = "Neues Kolonistenlevel: ".$data[level];
		$this->ulevel = $data[level];
		return $return;
	}
	
	function setlastround($round) { $this->db->query("UPDATE stu_user SET lastloginround=".$round." WHERE id=".$this->uid); }
	
	function delTickUser ($userId)
	{
		$data = $this->db->query("SELECT * FROM stu_allys WHERE user_id=".$userId,4);
		if ($data != 0)
		{
			if ($data[vize] != 0)
			{
				$this->db->query("UPDATE stu_allys SET user_id=".$data[vize].",vize=0 WHERE id=".$data[id]);
				$this->db->query("UPDATE stu_user SET allys_id=0 WHERE id=".$userId);
			}
			else
			{
				$this->db->query("UPDATE stu_user SET allys_id=0 WHERE allys_id=".$data[id]);
				$this->db->query("DELETE FROM stu_allys WHERE id=".$data[id]);
				$this->db->query("DELETE FROM stu_allys_beziehungen WHERE allys_id1=".$data[id]." OR allys_id2=".$data[id]);
				$this->db->query("DELETE FROM stu_allys_bez_angebot WHERE allys_id1=".$data[id]." OR allys_id2=".$data[id]);
			}
		}
		$this->db->query("UPDATE stu_allys SET vize=0 WHERE vize=".$userId);
		$this->db->query("UPDATE stu_allys SET diplo=0 WHERE diplo=".$userId);
		$data = $this->db->query("SELECT id FROM stu_colonies WHERE user_id=".$userId,2);
		if ($data != 0) for ($i=0;$i<count($data);$i++)	$this->destroycol($data[$i][id],$userId);
		$this->db->query("DELETE FROM stu_contactlist WHERE user_id=".$userId." OR recipient=".$userId);
		$this->db->query("DELETE FROM stu_spy_action WHERE user_id=".$userId);
		$this->db->query("DELETE FROM stu_fleets WHERE user_id=".$userId);
		$this->db->query("UPDATE stu_kn_messages SET user_id=2 WHERE user_id=".$userId);
		$this->db->query("UPDATE stu_pms SET sender=2 WHERE sender=".$userId);
		$this->db->query("DELETE FROM stu_pms WHERE recipient=".$userId);
		$this->db->query("DELETE FROM stu_research_user WHERE user_id=".$userId);
		$result = $this->db->query("SELECT * FROM stu_ships WHERE user_id=".$userId);
		if ($result != 0)
		{
			while($data=mysql_fetch_assoc($result))
			{
				$this->db->query("DELETE FROM stu_dock_permissions WHERE ships_id=".$data[id]." OR id2=".$data[id]);
				$this->db->query("DELETE FROM stu_ships_action WHERE ships_id=".$data[id]." OR ships_id2=".$data[id]);
				$this->db->query("DELETE FROM stu_ships_storage WHERE ships_id=".$data[id]);
			}
		}
		$this->db->query("DELETE FROM stu_ships WHERE user_id=".$userId);
		$this->db->query("DELETE FROM stu_trade_offers WHERE user_id=".$userId);
		$this->db->query("DELETE FROM stu_trade_goods WHERE user_id=".$userId);
		$this->db->query("DELETE FROM stu_user_profiles WHERE user_id=".$userId);
		$this->db->query("DELETE FROM stu_user WHERE id=".$userId);
	}
	
	function destroyCol($colId,$userId)
	{
		global $myGame,$global_path;
		$data = $this->db->query("SELECT id,colonies_classes_id FROM stu_colonies WHERE id=".$colId,4);
		if ($data == 0) return 0;
		$ip = getenv("REMOTE_ADDR");
		$filename = date("d_m_y");
		$logfile = fopen($global_path."tracking/tick/".$filename.".log","a");
		fwrite($logfile,"[".date("H:i:s")."]%-%".$ip."%-%".$userId."%-%300%-%Koloniereset ".$colId."\n");
		@fclose($logfile);
		$this->db->query("UPDATE stu_colonies_orbit SET aktiv=0,buildings_id=0,name='',buildtime=0 WHERE colonies_id='".$colId."'");
		$this->db->query("UPDATE stu_colonies SET name='',bev_used=0,bev_free=0,user_id=2,energie=0,max_schilde=0,schilde=0,schilde_aktiv=0,max_storage=0,max_energie=0,max_bev=0,wirtschaft=0,sperrung=0 WHERE id=".$colId);
		$this->db->query("DELETE FROM stu_colonies_storage WHERE colonies_id='".$colId."'");
		if ($data[colonies_classes_id] == 1) include($global_path."intern/inc/m.inc.php");
		if ($data[colonies_classes_id] == 2) include($global_path."intern/inc/l.inc.php");
		if ($data[colonies_classes_id] == 3) include($global_path."intern/inc/n.inc.php");
		if ($data[colonies_classes_id] == 4) include($global_path."intern/inc/g.inc.php");
		if ($data[colonies_classes_id] == 5) include($global_path."intern/inc/k.inc.php");
		if ($data[colonies_classes_id] == 6) include($global_path."intern/inc/d.inc.php");
		if ($data[colonies_classes_id] == 7) include($global_path."intern/inc/h.inc.php");
		if ($data[colonies_classes_id] == 8) include($global_path."intern/inc/x.inc.php");
		if ($data[colonies_classes_id] == 9) include($global_path."intern/inc/j.inc.php");
		for ($i=0;$i<count($fields);$i++) $this->db->query("UPDATE stu_colonies_fields SET type=".$fields[$i].",buildings_id=0,integrity=0,aktiv=0,name='',buildtime=0 WHERE field_id=".$i." AND colonies_id=".$data[id]);
		unset($fields);
		if ($data[colonies_classes_id] == 1) include($global_path."intern/inc/um.inc.php");
		if ($data[colonies_classes_id] == 2) include($global_path."intern/inc/ul.inc.php");
		if ($data[colonies_classes_id] == 3) include($global_path."intern/inc/un.inc.php");
		if ($data[colonies_classes_id] == 4) include($global_path."intern/inc/ug.inc.php");
		if ($data[colonies_classes_id] == 5) include($global_path."intern/inc/uk.inc.php");
		if ($data[colonies_classes_id] == 7) include($global_path."intern/inc/uh.inc.php");
		if ($data[colonies_classes_id] == 8) include($global_path."intern/inc/ux.inc.php");
		for ($i=0;$i<count($fields);$i++) $this->db->query("UPDATE stu_colonies_underground SET type=".$fields[$i].",buildings_id=0,integrity=0,aktiv=0,name='',buildtime=0 WHERE field_id=".$i." AND colonies_id=".$data[id]);
	}
	
	function getusercount() { return $this->db->query("SELECT id FROM stu_user WHERE id>100",4); }
	
	function passrecovery($userId)
	{
		$data = $this->db->query("SELECT * FROM stu_user WHERE id='".$userId."'",4);
		if ($data == 0)
		{
			$return[msg] = "User nicht vorhanden";
			return $return;
		}
		$actlink = md5($data[user].$data[login]."added".$data[email]);
		$this->db->query("INSERT INTO stu_user_passrec (date,user_id,act) VALUES (NOW(),'".$userId."','".$actlink."')");
		mail($data[email],"Passwort Recovery","Hallo<br>
		Solltest Du Dein Passwort nicht vergessen haben, ignoriere diese Mail einfach.<br>
		Ansonsten ist hier Dein Aktivierungscode: ".$actlink."<br>
		Achtung! Dieser Code ist 24 Stunden lang gültig<br><br>
		Mit freundlichen Grüßen<br><br>
		Das STU-Team","From: Star Trek Universe <wolverine@stuniverse.de>
Content-Type: text/html");
		$return[msg] = "Du erhälst in wenigen Augenblicken eine Email mit einem RecoveryCode";
		return $return;
	}
	
	function editpass($code,$pass,$pass2)
	{
		$data = $this->db->query("SELECT *,UNIX_TIMESTAMP(date) as date_tsp FROM stu_user_passrec WHERE act='".$code."'",4);
		if ($data == 0)
		{
			$return[msg] = "Kein Eintrag vorhanden";
			return $return;
		}
		if ($data[date_tsp] < time() - 86400)
		{
			$return[msg] = "Der Recovery-Code ist abgelauden";
			return $return;
		}
		if ($pass != $pass2)
		{
			$return[msg] = "Die Passwörter stimmen nicht überein";
			return $return;
		}
		$this->db->query("UPDATE stu_user SET pass='".md5($pass)."' WHERE id=".$data[user_id]);
		$this->db->query("DELETE FROM stu_user_passrec WHERE user_id=".$data[user_id]);
		$return[msg] = "Das Passwort wurde erfolgreich geändert";
		return $return;
	}
	
	function getonlineusercount() { return $this->db->query("SELECT count(id) FROM stu_user WHERE UNIX_TIMESTAMP(lastaction)>=".(time()-300)." AND id>100",1); }
	
	function activateUser($actcode,$userId)
	{
		$userId = chop($userId);
		if (!is_numeric($userId)) return 0;
		$data = $this->getuserbyid($userId);
		if ($data[aktiv] == 1) exit;
		$act2 = md5((strlen($data[login].$data[rasse].$data[startrunde]))*144);
		if ($act2 != $actcode) return 0;
		$this->db->query("UPDATE stu_user SET act_code='',aktiv=1 WHERE id=".$userId);
		return 1;
	}
	
	function getUserProfile($userId) { return $this->db->query("SELECT * FROM stu_user_profiles WHERE user_id=".$userId,4); }
	
	function updateProfileById($userId,$value,$field) { $this->db->query("UPDATE stu_user_profiles SET ".$field."='".$value."' WHERE user_id=".$userId); }
	
	function getslsorting($sa="")
	{
		$ret = $this->db->query("SELECT sl_sorttype,sl_sortway FROM stu_user_profiles WHERE user_id=".$this->user,4);
		$way = $ret[sl_sortway];
		$sort = $ret[sl_sorttype];
		if ($way == "up") $sql_way = "DESC";
		else $sql_way = "ASC";
		if ($sort == "class") $sql_order = $sa."ships_rumps_id";
		elseif ($sort == "name") $sql_order = $sa."name";
		elseif ($sort == "coords") $sql_order = $sa."coords_x ".$sql_way.",".$sa."coords_y";
		elseif ($sort == "energie") $sql_order = $sa."energie";
		else $sql_order = $sa."ships_rumps_id";
		$sql_order .= ",".$sa."id";
		return $sql_order." ".$sql_way;
	}
	
	function getfield($value,$userId) { return $this->db->query("SELECT ".$value." FROM stu_user WHERE id=".$userId,1); }

	function avm()
	{
		$this->db->query("UPDATE stu_user SET vac=1,pvac=pvac-1,vactime=".time()." WHERE id=".$this->user);
		$this->db->query("UPDATE stu_ships SET alertlevel=1 WHERE user_id=".$this->user);
	}

	function dvm() { $this->db->query("UPDATE stu_user SET vac=0,vactime=0 WHERE id=".$this->user); }
}
?>