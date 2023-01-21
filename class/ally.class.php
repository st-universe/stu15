<?php
class ally
{
	function ally()
	{
		global $myDB,$user;
		$this->db = $myDB;
		$this->user = $user;
	}
	
	function checkally() { return $this->db->query("SELECT allys_id FROM stu_user WHERE id=".$this->user,1); }
	
	function getallylist() { return $this->db->query("SELECT a.id,a.name,b.user as prae FROM stu_allys as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id ORDER BY a.id"); }
	
	function getallyulist() { return $this->db->query("SELECT id,name FROM stu_allys ORDER BY id"); }
	
	function getallybyid($allyId) { return $this->db->query("SELECT * FROM stu_allys WHERE id='".$allyId."'",4); }
	
	function joinally($allyId,$pass)
	{
		global $myUser;
		if ($myUser->ually > 0)
		{
			$return[msg] = "Du bist bereits in einer Allianz";
			return $return;
		}
		$data = $this->db->query("SELECT id,pass FROM stu_allys WHERE id='".$allyId."'",4);
		if ($data == 0)
		{
			$return[msg] = "Allianz nicht vorhanden";
			return $return;
		}
		if ($data[pass] != md5($pass))
		{
			$return[msg] = "Falsches Beitrittspasswort";
			return $return;
		}
		$this->db->query("UPDATE stu_user SET allys_id='".$allyId."' WHERE id=".$this->user);
		global $myUser;
		$userd = $myUser->getuserbyid($this->user);
		$this->db->query("INSERT INTO stu_allys_messages (subject,text,date,user_id,allys_id) VALUES ('Neues Mitglied','Der User ".addslashes($userd[user])." (".$this->user.") ist der Allianz beigetreten',NOW(),'2','".$allyId."')");
		$return[msg] = "Du bist der Allianz ".$data[name]." beigetreten";
		return $return;
	}
	
	function getallymembers($allyId) { return $this->db->query("SELECT id,user,rasse,lastloginround,UNIX_TIMESTAMP(lastaction) as last_tsp,vac FROM stu_user WHERE allys_id='".$allyId."' AND aktiv=1"); }
	
	function newally($name,$pass,$descr,$hp)
	{
		if (strlen($pass) < 5)
		{
			$return[msg] = "Das Beitrittspasswort muss aus mindestens 5 Zeichen bestehen";
			return $return;
		}
		global $myUser;
		if ($myUser->ually != 0)
		{
			$return[msg] = "Du bist bereits in einer Allianz";
			return $return;
		}
		$name = strip_tags($name,"<font><b></font></b>");
		$name = str_replace("size","",$name);
		$string1 = substr_count($name,"<font");
		$string2 = substr_count($name,"</font>");
		if ($string1 > $string2) $multi = $string1-$string2;
		if ($multi>0) for ($z=1;$z<=$multi;$z++) $addstring .= "</font>";
		$id = $this->db->query("INSERT INTO stu_allys (name,pass,user_id,descr,hp) VALUES ('".addslashes($name.$addstring)."','".md5($pass)."','".$this->user."','".addslashes($descr)."','".addslashes($hp)."')",5);
		$this->db->query("UPDATE stu_user SET allys_id='".$id."' WHERE id=".$this->user);
		$return[msg] = "Die Allianz wurde gegründet";
		return $return;
	}
	
	function leaveally($allyId)
	{
		global $myUser;
		if ($myUser->ually != $allyId)
		{
			$return[msg] = "Du bist nicht in dieser Allianz";
			return $return;
		}
		$ally = $this->getallybyid($allyId);
		if ($ally[user_id] == $this->user)
		{
			if ($ally[vize] == 0)
			{
				$this->delally($allyId);
				$this->db->query("UPDATE stu_user SET allys_id=0 WHERE id=".$this->user);
				$return[msg] = "Die Allianz wurde gelöscht";
				return $return;
			}
			else $this->db->query("UPDATE stu_allys SET user_id=".$ally[vize].",vize=0 WHERE id=".$ally[id]);
		}
		$this->db->query("UPDATE stu_user SET allys_id=0 WHERE id=".$this->user);
		$this->db->query("UPDATE stu_allys SET vize=0 WHERE vize=".$this->user);
		$this->db->query("UPDATE stu_allys SET diplo=0 WHERE diplo=".$this->user);
		$return[msg] = "Du hast die Allianz verlassen";
		return $return;
	}
	
	function delally($allyId)
	{
		$data = $this->getallybyid($allyId);
		if ($this->checkally() != $allyId)
		{
			$return[msg] = "Du bist nicht in dieser Allianz";
			return $return;
		}
		if ($this->checkfounder() == 0)
		{
			$return[msg] = "Keine Berechtigung";
			return $return;
		}
		if ($data[vize] != 0)
		{
			$this->db->query("UPDATE stu_allys SET vize=0,user_id=".$data[vize]." WHERE id=".$allyId);
			$this->db->query("UPDATE stu_user SET allys_id=0 WHERE id=".$this->user);
			return 1;
		}
		$this->db->query("DELETE FROM stu_allys WHERE id=".$allyId);
		$this->db->query("UPDATE stu_user SET allys_id=0 WHERE allys_id=".$allyId);
		$this->db->query("DELETE FROM stu_allys_beziehungen WHERE allys_id1=".$allyId." OR allys_id2=".$allyId);
		$this->db->query("DELETE FROM stu_allys_bez_angebot WHERE allys_id1=".$allyId." OR allys_id2=".$allyId);
		$this->db->query("DELETE FROM stu_allys_messages WHERE allys_id=".$allyId);
		$this->db->query("DELETE FROM stu_allys_embassys WHERE allys_id1=".$allyId);
		$this->db->query("DELETE FROM stu_allys_embassys WHERE allys_id2=".$allyId);
		return 1;
	}
	
	function delfromally($allyId,$delId)
	{
		if ($this->checkAlly($this->user) != $allyId)
		{
			$return[msg] = "Du bist nicht in dieser Allianz";
			return $return;
		}
		if ($this->checkfounder() == 0)
		{
			$return[msg] = "Keine Berechtigung";
			return $return;
		}
		$this->db->query("UPDATE stu_user SET allys_id=0 WHERE id=".$delId." AND allys_id=".$allyId);
		$this->db->query("UPDATE stu_allys SET diplo=0 WHERE diplo=".$delId." AND id=".$allyId);
		$this->db->query("UPDATE stu_allys SET vize=0 WHERE vize=".$delId." AND id=".$allyId);
		return 1;
	}
	
	function updateally($allyId,$field,$value,$userId) {
	
		if ($field == "name")
		{
			$name = strip_tags($value,"<font><b></font></b>");
			$name = str_replace("size","",$name);
			$string1 = substr_count($name,"<font");
			$string2 = substr_count($name,"</font>");
			if ($string1 > $string2) $multi = $string1-$string2;
			if ($multi>0) for ($z=1;$z<=$multi;$z++) $addstring .= "</font>";
			$value = addslashes($value.$addstring);
			$return[msg] = "Der Allianzname wurde geändert";
		}
		if ($field == "pass")
		{
			if (strlen($value) < 5)
			{
				$return[msg] = "Das Passwort muss aus mindestens 5 Zeichen bestehen";
				return $return;
			}
			$return[msg] = "Das Beitrittspasswort wurde geändert";
		}
		if ($field == "descr")
		{
			$return[msg] = "Die Allianzbeschreibung wurde geändert";
			$value = addslashes($value);
		}
		if ($field == "hp")
		{
			$return[msg] = "Die Homepage wurde geändert";
			$value = addslashes($value);
		}
		$this->db->query("UPDATE stu_allys SET ".$field."='".$value."' WHERE id='".$allyId."' AND (user_id='".$userId."' OR vize=".$userId.")");
		return $return;
	}
	
	function checkfounder()
	{
		global $myUser;
		$data = $myUser->getuserbyid($this->user);
		return $this->db->query("SELECT id FROM stu_allys WHERE id=".$data[allys_id]." AND user_id=".$this->user,1);
	}
	
	function setwork($allyId,$type,$value)
	{
		$data = $this->getallybyid($allyId);
		if ($this->db->query("SELECT allys_id FROM stu_user WHERE id=".$value,1) != $allyId)
		{
			$return[msg] = "Dieser Spieler befindet sich nicht in dieser Allianz";
			return $return;
		}
		if ($this->checkfounder($this->user) == 0)
		{
			$return[msg] = "Du bist nicht Präsident dieser Allianz";
			return $return;
		}
		if ($data[user_id] == $value)
		{
			$return[msg] = "Du bist der Präsident der Allianz und kannst keinen weiteren Posten übernehmen";
			return $return;
		}
		global $myUser;
		if ($type == "user_id") $job = "Präsident";
		if ($type == "vize") $job = "Vize-Präsident";
		if ($type == "diplo") $job = "Außenminister";
		$this->db->query("INSERT INTO stu_allys_messages (subject,text,date,user_id,allys_id) VALUES ('Neuer ".$job."','".addslashes($this->db->query("SELECT user FROM stu_user WHERE id=".$value,1))." (".$data[id].") wurde als neuer ".$job." bestimmt',NOW(),'2','".$allyId."')");
		$this->db->query("UPDATE stu_allys SET ".$type."=".$value." WHERE id=".$allyId);
		$return[msg] = "Der Posten (".$job.") wurde vergeben";
		return $return;
	}
	
	function setnewpresi($allyId,$type,$value)
	{
		$data = $this->getallybyid($allyId);
		if ($this->checkAlly($value) == 0)
		{
			$return[msg] = "Dieser Spieler befindet sich nicht in der Allianz";
			return $return;
		}
		if ($this->checkfounder($this->user) == 0)
		{
			$return[msg] = "Du bist nicht Präsident dieser Allianz";
			return $return;
		}
		if ($data[user_id] == $value)
		{
			$return[msg] = "Du bist der Präsident der Allianz und kannst keinen weiteren Posten übernehmen";
			return $return;
		}
		$this->db->query("UPDATE stu_allys SET ".$type."=".$value." WHERE id=".$allyId);
		$return[msg] = "Der Posten wurde vergeben";
		return $return;
	}
	
	function unsetwork($allyId,$type)
	{
		if (($type != "vize") && ($type != "diplo")) return 0;
		if ($this->checkfounder($this->user) == 0)
		{
			$return[msg] = "Du bist nicht Präsident dieser Allianz";
			return $return;
		}
		if ($type == "vize")
		{
			$this->db->query("UPDATE stu_allys SET vize=0 WHERE id=".$allyId);
			$return[msg] = "Der Vizeminister wurde entlassen";
		}
		elseif ($type == "diplo") {
			$this->db->query("UPDATE stu_allys SET diplo=0 WHERE id=".$allyId);
			$return[msg] = "Der Außenminister wurde entlassen";
		}
		return $return;
	}
	
	function getbez($allyId) { return $this->db->query("SELECT *,UNIX_TIMESTAMP(date) as date_tsp FROM stu_allys_beziehungen WHERE allys_id1=".$allyId." OR allys_id2=".$allyId,2); }
	
	function getubez($allyId) { return $this->db->query("SELECT type,allys_id1,allys_id2 FROM stu_allys_beziehungen WHERE allys_id1=".$allyId." OR allys_id2=".$allyId); }
	
	function addbez($allyId,$allyId2,$type)
	{
		if ($allyId2 == $allyId) return 0;
		$data = $this->getallybyid($allyId2);
		$data2  = $this->getallybyid($allyId);
		if ($data == 0)
		{
			$return[msg] = "Diese Allianz existiert nicht";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_allys_bez_angebot WHERE (allys_id1=".$allyId." ANd allys_id2=".$allyId2.") OR (allys_id1=".$allyId2." ANd allys_id2=".$allyId.")",3) == 1)
		{
			$return[msg] = "Dieser Allianz wird bereits ein Vertrag angeboten";
			return $return;
		}
		global $myComm,$myHistory;
		if ($data[diplo] > 0) $recipient = $data[diplo];
		else $recipient = $data[user_id];
		if ($type == 1)
		{
			$this->db->query("DELETE FROM stu_allys_beziehungen WHERE (allys_id1=".$allyId." ANd allys_id2=".$allyId2.") OR (allys_id1=".$allyId2." AND allys_id2=".$allyId.")");
			$this->db->query("INSERT INTO stu_allys_beziehungen (allys_id1,allys_id2,type,date) VALUES ('".$allyId."','".$allyId2."','1',NOW())");
			$return[msg] = "Der Allianz ".$data[name]." wurde der Krieg erklärt";
			$myComm->sendpm($recipient,$this->user,"Deiner Allianz wurde von der Allianz ".$data2[name]." der Krieg erklärt");
			$myHistory->addEvent("Die Allianz ".addslashes($data2[name])." hat der Allianz ".addslashes($data[name])." den Krieg erklärt",$userId,3);
		}
		elseif ($type == 2)
		{
			$this->db->query("INSERT INTO stu_allys_bez_angebot (allys_id1,allys_id2,type) VALUES ('".$allyId."','".$allyId2."','2')");
			$return[msg] = "Der Allianz ".$data[name]." wurde ein Handelsvertrag angeboten";
			$myComm->sendpm($recipient,$this->user,"Deiner Allianz wurde von der Allianz ".$data2[name]." ein Handelsvertrag angeboten");
		}
		elseif ($type == 3)
		{
			$this->db->query("INSERT INTO stu_allys_bez_angebot (allys_id1,allys_id2,type) VALUES ('".$allyId."','".$allyId2."','3')");
			$return[msg] = "Der Allianz ".$data[name]." wurde die Freundschaft angeboten";
			$myComm->sendpm($recipient,$this->user,"Deiner Allianz wurde von der Allianz ".$data2[name]." die Freundschaft angeboten");
		}
		elseif ($type == 4)
		{
			$this->db->query("INSERT INTO stu_allys_bez_angebot (allys_id1,allys_id2,type) VALUES ('".$allyId."','".$allyId2."','4')");
			$return[msg] = "Der Allianz ".$data[name]." wurde ein Bündnis angeboten";
			$myComm->sendpm($recipient,$this->user,"Deiner Allianz wurde von der Allianz ".$data2[name]." ein Bündnis angeboten");
		}
		elseif ($type == 6)
		{
			$this->db->query("INSERT INTO stu_allys_bez_angebot (allys_id1,allys_id2,type) VALUES ('".$allyId."','".$allyId2."','6')");
			$return[msg] = "Der Allianz ".$data[name]." wurde der Frieden angeboten";
			$myComm->sendpm($recipient,$this->user,"Deiner Allianz wurde von der Allianz ".$data2[name]." der Frieden angeboten");
		}
		elseif ($type == 9)
		{
			$check = $this->getbezbetweenbyids($allyId,$allyId2);
			if (($check != 3) && ($check != 4))
			{
				$return[msg] = "Für dieses Angebot wird Freundschaft oder ein Bündnis benötigt";
				return $return;
			}
			if ($this->getembassybyallys($allyId,$allyId2) != 0)
			{
				$return[msg] = "Dieses Angebot wurde bereits angenommen";
				return $return;
			}
			$this->db->query("INSERT INTO stu_allys_bez_angebot (allys_id1,allys_id2,type) VALUES ('".$allyId."','".$allyId2."','9')");
			$return[msg] = "Die Allianz ".$data[name]." bittet um die Errichtung von Botschaften";
			$myComm->sendpm($recipient,$this->user,"Deine Allianz wurde von der Allianz ".$data2[name]." um die Errichtung von Botschaften gebeten");
		}
		return $return;
	}
	
	function getbezbyid($allyId) { return $this->db->query("SELECT * FROM stu_allys_beziehungen WHERE allys_id2=".$allyId,4); }

	function getbezbetweenbyids($allyId,$allyId2){ return $this->db->query("SELECT type FROM stu_allys_beziehungen WHERE (allys_id1=".$allyId." OR allys_id2=".$allyId.") AND (allys_id1=".$allyId2." OR allys_id2=".$allyId2.")",1); }
	
	function editbez($allyId,$allyId2,$type)
	{
		$data = $this->getallybyid($allyId2);
		$data2  = $this->getallybyid($allyId);
		if ($data == 0)
		{
			$return[msg] = "Diese Allianz existiert nicht";
			return $return;
		}
		$bez = $this->getbezbetweenbyids($allyId,$allyId2);
		if ($bez == 0)
		{
			$return[msg] = "Es besteht keine Beziehung zu dieser Allianz";
			return $return;
		}
		if ($data[diplo] > 0) $recipient = $data[diplo];
		else $recipient = $data[user_id];
		if ($type == 5)
		{
			global $myComm;
			if ($bez == 1) $msg = "Die Allianz ".$data2[name]." hat die Kriegserklärung zurückgenommen";
			if ($bez == 2) $msg = "Die Allianz ".$data2[name]." hat den Handelsvertrag gekündigt";
			if ($bez == 3) $msg = "Die Allianz ".$data2[name]." hat die Freundschaft gekündigt";
			if ($bez == 4) $msg = "Die Allianz ".$data2[name]." hat das Bündnis gekündigt";
			$myComm->sendpm($recipient,$this->user,$msg);
			$this->db->query("DELETE FROM stu_allys_beziehungen WHERE (allys_id1=".$allyId." AND allys_id2=".$allyId2.") OR (allys_id1=".$allyId2." AND allys_id2=".$allyId.")");
			$return[msg] = "Die Beziehung zu der Allianz ".$data[name]." wurde gelöscht";
			return $return;
		}
		$return = $this->addbez($allyId,$allyId2,$type);
		return $return;
	}
	
	function takebez($allyId,$bezId)
	{
		$angebot = $this->getangebotbyid($bezId);
		if ($angebot == 0)
		{
			$return[msg] = "Kein Angebot vorhanden";
			return $return;
		}
		$data = $this->getallybyid($allyId);
		if ($data == 0)
		{
			$return[msg] = "Diese Allianz existiert nicht";
			return $return;
		}
		$angebot[allys_id2] == $allyId ? $data2 = $this->getallybyid($angebot[allys_id1]) : $data2 = $this->getallybyid($angebot[allys_id2]);
		$data2[diplo] > 0 ? $recipient = $data2[diplo] : $recipient = $data2[user_id];
		global $myComm,$myHistory;
		if ($angebot[type] == 9) 
		{
			$myComm->sendpm($recipient,$this->user,"Die Allianz ".$data[name]." hat den Vorschlag angenommen");
			$this->db->query("DELETE FROM stu_allys_bez_angebot WHERE id=".$bezId);
			$this->db->query("INSERT INTO stu_allys_embassys (allys_id1,allys_id2) VALUES ('".$angebot[allys_id1]."','".$angebot[allys_id2]."')");
			$this->db->query("INSERT INTO stu_allys_embassys (allys_id1,allys_id2) VALUES ('".$angebot[allys_id2]."','".$angebot[allys_id1]."')");
			$return[msg] = "Bitte angenommen";
			return $return;
		}
		$myComm->sendpm($recipient,$this->user,"Die Allianz ".$data[name]." hat den Vertrag angenommen");
		if ($angebot[type] == 2) $msg = "Die Allianz ".$data2[name]." hat mit der Allianz ".$data[name]." einen Handelsvertrag abgeschlossen";
		if ($angebot[type] == 3) $msg = "Die Allianz ".$data2[name]." hat mit der Allianz ".$data[name]." Freundschaft geschlossen";
		if ($angebot[type] == 4) $msg = "Die Allianz ".$data2[name]." hat mit der Allianz ".$data[name]." ein Bündnis geschlossen";
		if ($angebot[type] == 6) $msg = "Der Krieg zwischen den Allianzen ".$data2[name]." und ".$data[name]." wurde beendet";
		$myHistory->addEvent(addslashes($msg),$this->user,3);
		$this->db->query("DELETE FROM stu_allys_bez_angebot WHERE id=".$bezId);
		$this->db->query("DELETE FROM stu_allys_beziehungen WHERE (allys_id1=".$angebot[allys_id1]." AND allys_id2=".$angebot[allys_id2].") OR (allys_id1=".$angebot[allys_id2]." AND allys_id2=".$angebot[allys_id1].")");
		if ($angebot[type] != 6) $this->db->query("INSERT INTO stu_allys_beziehungen (allys_id1,allys_id2,type,date) VALUES ('".$angebot[allys_id1]."','".$angebot[allys_id2]."','".$angebot[type]."',NOW())");
		$return[msg] = "Vertrag angenommen";
		return $return;
	}
	
	function getangebotbyid($bezId) { return $this->db->query("SELECT * FROM stu_allys_bez_angebot WHERE id=".$bezId,4); }
	
	function getangebote($allyId) { return $this->db->query("SELECT * FROM stu_allys_bez_angebot WHERE allys_id2=".$allyId,2); }
	
	function getsangebote($allyId) { return $this->db->query("SELECT * FROM stu_allys_bez_angebot WHERE allys_id1=".$allyId,2); }
	
	function checkbez ($allyId,$allyId2) { return $this->db->query("SELECT type FROM stu_allys_beziehungen WHERE (allys_id1=".$allyId." AND allys_id2=".$allyId2.") OR (allys_id1=".$allyId2." AND allys_id2=".$allyId.")",1); }
	
	function delbez($allyId,$bezId)
	{
		if ($this->getangebotbyid($bezId) == 0)
		{
			$return[msg] = "Kein Angebot vorhanden";
			return $return;
		}
		$data = $this->getallybyid($angebot[allys_id2]);
		if ($data[diplo] > 0) $recipient = $data[diplo];
		else $recipient = $data[user_id];
		if ($recipient == $this->user)
		{
			$data2 = $this->getallybyid($angebot[allys_id1]);
			if ($data2[diplo] > 0) $recipient = $data2[diplo];
			else $recipient = $data2[user_id];
		}
		global $myComm;
		$myComm->sendpm($recipient,$this->user,"Die Allianz ".$data[name]." hat den Vertrag abgelehnt");
		$this->db->query("DELETE FROM stu_allys_bez_angebot WHERE id=".$bezId." ANd allys_id2=".$allyId);
		$return[msg] = "Angebot abgelehnt";
		return $return;
	}
	
	function getfield($value,$allyId) { return $this->db->query("SELECT ".$value." FROM stu_allys WHERE id=".$allyId,1); }

	function getbuiltembassys($allyId) { return $this->db->query("SELECT a.*,b.name as allyname, c.name as colname FROM stu_allys_embassys as a left outer join stu_allys as b on a.allys_id2 = b.id left outer join stu_colonies as c on a.colonies_id = c.id WHERE a.allys_id1=".$allyId." AND a.colonies_id != 0 AND a.allys_id2 = b.id"); }
	
	function getownedembassys($allyId) { return $this->db->query("SELECT a.*,b.name as allyname, c.name as colname FROM stu_allys_embassys as a left outer join stu_allys as b on a.allys_id1 = b.id left outer join stu_colonies as c on a.colonies_id = c.id WHERE a.allys_id2=".$allyId." AND a.colonies_id != 0 AND a.allys_id1 = b.id"); }
	
	function getunbuiltembassys($allyId) { return $this->db->query("SELECT a.*,b.name as allyname, c.name as colname FROM stu_allys_embassys as a left outer join stu_allys as b on a.allys_id1 = b.id left outer join stu_colonies as c on a.colonies_id = c.id WHERE a.allys_id2=".$allyId." AND a.allys_id1 = b.id"); }
	
	function getembassybyallys($allyId,$allyId2) { return $this->db->query("SELECT * FROM stu_allys_embassys WHERE allys_id1=".$allyId." AND allys_id2=".$allyId2,2); }

	function getembassyownerbycolony($colId,$fieldId) { return $this->db->query("SELECT allys_id2 FROM stu_allys_embassys WHERE colonies_id=".$colId." AND field_id=".$fieldId,1); }

	function getembassybuilderbycolony($colId,$fieldId) { return $this->db->query("SELECT allys_id1 FROM stu_allys_embassys WHERE colonies_id=".$colId." AND field_id=".$fieldId,1); }

	function getembassyoptions($allyId) { return $this->db->query("SELECT a.*,b.name FROM stu_allys_embassys as a left outer join stu_allys as b on a.allys_id2 = b.id WHERE a.allys_id1=".$allyId." AND a.colonies_id = 0"); }

	function getembassybez($allyId) { return $this->db->query("SELECT * FROM stu_allys_beziehungen WHERE (allys_id1=".$allyId." OR allys_id2=".$allyId.") AND (type=3 OR type=4)",2); }

	function checkembassybuild() { return $this->db->query("SELECT a.id FROM `stu_allys` as a left outer join stu_user as b  left outer join stu_allys_embassys as c on b.allys_id = a.id on a.id = c.allys_id1 WHERE ( a.user_id = ".$this->user." OR a.vize = ".$this->user." OR a.diplo = ".$this->user." ) AND c.colonies_id = 0 GROUP by a.id",1); }

	function getembassybyid($embassy) { return $this->db->query("SELECT * FROM stu_allys_embassys WHERE id=".$embassy); }

	function changeembassystyle($embassy,$look)
	{
		$ally = $this->getallybyid($this->checkally());
		if ((($look < 210) || ($look > 213)) && ($look != 215))
		{
			$return[msg] = "Fehler bei der Parameterübergabe";
			return $return;
		}
		if (($ally[user_id] != $this->user) && ($ally[vize] != $this->user) && ($ally[diplo] != $this->user))
		{
			$return[msg] = "Dazu bist du nicht befugt";
			return $return;
		}
		$data = mysql_fetch_array($this->getembassybyid($embassy));
		$this->db->query("UPDATE stu_colonies_fields SET buildings_id=$look WHERE colonies_id=".$data[colonies_id]." AND field_id =".$data[field_id]);
		$return[msg] = "Baustil wurde geändert";
		return $return;
	}
	
}
?>