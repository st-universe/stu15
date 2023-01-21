<?php
class comm
{
	function comm()
	{
		global $myDB,$user;
		$this->db = $myDB;
		$this->user = $user;
	}
	
	function getknbylz($mark) { return $this->db->query("SELECT a.*,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.status,b.rasse,b.picture from stu_kn_messages as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.id<=".$mark." ORDER BY a.date DESC LIMIT 5"); }
	
	function getknbylzoff($mark) { return $this->db->query("SELECT a.*,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.status,b.rasse,b.picture from stu_kn_messages as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.id<=".$mark." AND a.official=1 ORDER BY a.date DESC LIMIT 5"); }
	
	function getknmaxid() { return $this->db->query("SELECT max(id) FROM stu_kn_messages",1); }
	
	function setlz($mark)
	{
		$this->db->query("UPDATE stu_user SET kn_lz=".$mark." WHERE id='".$this->user."'");
		$return[msg] = "Das Lesezeichen wurde bei Beitrag ".$mark." gesetzt";
		return $return;
	}
	
	function getlzcount()
	{
		$lz = $this->db->query("SELECT kn_lz from stu_user WHERE id='".$this->user."'",1);
		if ($lz != 0) return $this->db->query("SELECT count(id) from stu_kn_messages WHERE id>".$lz,1);
		return 0;
	}
	
	function addkn($subject,$message,$off=1) { $this->db->query("INSERT INTO stu_kn_messages (user_id,subject,text,date,official) VALUES ('".$this->user."','".addslashes($subject)."','".addslashes($message)."',NOW(),'".$off."')"); }
	
	function getpms($cat) { return $this->db->query("SELECT a.id,a.sender,a.message,a.new,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.rasse,b.status FROM stu_pms as a LEFT OUTER JOIN stu_user as b ON a.sender=b.id WHERE a.recipient='".$this->user."' AND a.cate=".$cat." AND a.recip_del=0 ORDER BY a.date DESC LIMIT 0,10"); }
	
	function getpmsmark($begin,$cat=1) { return $this->db->query("SELECT a.id,a.sender,a.message,a.new,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.rasse,b.status FROM stu_pms as a LEFT OUTER JOIN stu_user as b ON a.sender=b.id WHERE a.recipient='".$this->user."' AND a.cate=".$cat." AND a.recip_del=0 ORDER BY a.date DESC LIMIT ".$begin.",10"); }
	
	function sendpm($recipient,$sender,$message,$cat=1,$time="")
	{
		if (!$recipient)
		{
			$return[msg] = "Es wurde kein Empfänger angegeben";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_user WHERE id=".$recipient,1) == 0)
		{
			$return[msg] = "Es existiert kein Spieler mit der ID ".$recipient;
			return $return;
		}
		if (!$time || ($time == "")) $time = time();
		$this->db->query("INSERT INTO stu_pms (sender,recipient,message,date,cate) VALUES ('".$sender."','".$recipient."','".addslashes($message)."','".date("Y-m-d H:i:s",$time)."','".$cat."')");
		$return[msg] = "Die Nachricht wurde gesendet";
		return $return;
	}
	
	function markasread($pmId) { $this->db->query("UPDATE stu_pms SET new=0 WHERE id='".$pmId."'"); }
	
	function checknewmsg() { return $this->db->query("SELECT COUNT(id) from stu_pms WHERE new='1' AND recipient=".$this->user,1); }
	
	function delpm($pmId)
	{
		$data = $this->db->query("SELECT *,UNIX_TIMESTAMP(date) as date_tsp FROM stu_pms WHERE id=".$pmId."",4);
		if ($this->user == $data[sender]) $this->db->query("UPDATE stu_pms SET send_del=1 WHERE id=".$data[id]);
		if ($this->user == $data[recipient]) $this->db->query("UPDATE stu_pms SET recip_del=1 WHERE id=".$data[id]." ANd new=0");
	}
	
	function markallasread($cat) { $this->db->query("UPDATE stu_pms SET new=0 WHERE recipient='".$this->user."' AND cate=".$cat); }
	
	function delallpms($cat) { $this->db->query("UPDATE stu_pms SET recip_del=1,new=0 WHERE cate=".$cat." AND recipient=".$this->user); }
	
	function getallyknbylz($mark)
	{
		global $myUser;
		return $this->db->query("SELECT a.*,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.status,b.rasse,b.picture from stu_allys_messages as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.id<=".$mark." AND a.allys_id=".$myUser->ually." ORDER BY a.date DESC LIMIT 5");
	}
	
	function getallyknmaxid()
	{
		global $myUser;
		return $this->db->query("SELECT max(id) from stu_allys_messages WHERE allys_id='".$myUser->ually."'",1);
	}
	
	function getallylz() { return $this->db->query("SELECT kn_allylz from stu_user WHERE id='".$this->user."'",1); }
	
	function setallylz($mark)
	{
		$this->db->query("UPDATE stu_user SET kn_allylz=".$mark." WHERE id='".$this->user."'");
		$return[msg] = "Das Lesezeichen wurde bei Beitrag ".$mark." gesetzt";
		return $return;
	}
	
	function getallylzcount()
	{
		global $myUser;
		$lz = $this->getallylz();
		if ($lz == 0) return 0;
		return $this->db->query("SELECT count(id) from stu_allys_messages WHERE allys_id='".$myUser->ually."' AND id>".$lz,1);
	}
	
	function addallykn($subject,$message)
	{
		global $myUser;
		$this->db->query("INSERT INTO stu_allys_messages (user_id,subject,text,date,allys_id) VALUES ('".$this->user."','".addslashes($subject)."','".addslashes($message)."',NOW(),'".$myUser->ually."')");
		$return[msg] == "Beitrag hinzugefügt";
		return $return;
	}
	
	function delallymsg($del)
	{
		global $myAlly;
		if ($myAlly->checkfounder($this->user) == 0) return 0;
		$this->db->query("DELETE FROM stu_allys_messages WHERE id='".$del."'");
		$return[msg] = "Beitrag ".$del." gelöscht";
		return $return;
	}
	
	function getcontacts() { return $this->db->query("SELECT recipient,behaviour FROM stu_contactlist WHERE user_id='".$this->user."' ORDER BY recipient",2); }
	
	function delcontact($recipient)
	{
		$this->db->query("DELETE FROM stu_contactlist WHERE recipient=".$recipient." AND user_id='".$this->user."'");
		$return[msg] = "Spieler gelöscht";
		return $return;
	}
	
	function addcontact($recipient,$status,$type="user")
	{
		if ($status < 0 || $status > 2) return 0;
		if ($recipient == $this->user && $type != "ally") return 0;
		if ($recipient == 28 && $type != "ally")
		{
			$return[msg] = "Kann nicht der Kontaktliste hinzugefügt werden";
			return $return;
		}
		if ($type == "ally")
		{
			if ($this->db->query("SELECT id FROM stu_allys WHERE id=".$recipient,1) == 0)
			{
				$return[msg] = "Diese Allianz existiert nicht";
				return 0;
			}
			$result = $this->db->query("SELECT id FROM stu_user WHERE allys_id=".$recipient);
			while($d=mysql_fetch_assoc($result))
			{
				if ($this->db->query("SELECT id FROM stu_contactlist WHERE recipient=".$d[id]." AND user_id=".$this->user,3) == 1) continue;
				$this->db->query("INSERT INTO stu_contactlist (recipient,user_id,behaviour) VALUES ('".$d[id]."','".$this->user."','".$status."')");
			}
			$return[msg] = "Allianz hinzugefügt";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_user WHERE id=".$recipient,3) == 0)
		{
			$return[msg] = "Spieler nicht vorhanden";
			return $return;
		}
		$result = $this->db->query("SELECT id FROM stu_contactlist WHERE recipient=".$recipient." AND user_id=".$this->user,3);
		if ($result == 1)
		{
			$return[msg] = "Spieler schon vorhanden";
			return $return;
		}
		$this->db->query("INSERT INTO stu_contactlist (recipient,user_id,behaviour) VALUES ('".$recipient."','".$this->user."','".$status."')");
		$return[msg] = "Spieler hinzugefügt";
		return $return;
	}
	
	function getpmsbystring($string)
	{
		$test = explode(" ",$string);
		if (count($test) > 1)
		{
			$add = "WHERE message ";
			for ($i=0;$i<count($test);$i++)
			{
				$add .= "LIKE '%$test[$i]%'";
				if ($test[$i+1] != "") $add .= " AND message ";
			}
		}
		else $add = "WHERE message LIKE '%$string%'";
		return $this->db->query("SELECT sender,recipient,message,UNIX_TIMESTAMP(date) as date_tsp FROM stu_pms ".$add." ORDER BY date DESC",2);
	}
	
	function getpmsbyuserid($userId) { return $this->db->query("SELECT sender,recipient,message,UNIX_TIMESTAMP(date) as date_tsp FROM stu_pms WHERE recipient=".$userId." OR sender=".$userId." ORDER BY date DESC",2); }
	
	function delknmsg($msgId)
	{
		global $myUser;
		if ($myUser->ustatus != 8)
		{
			$return[msg] = "Zugriff verweigert";
			return $return;
		}
		$this->db->query("DELETE FROM stu_kn_messages WHERE id=".$msgId);
		$return[msg] = "Eintrag (ID: ".$msgId.") gelöscht";	
		return $return;
	}
	
	function getknmsgbyid($msgId) { return $this->db->query("SELECT a.id,a.user_id,a.subject,a.text,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.rasse,b.status,b.picture FROM stu_kn_messages as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.id=".$msgId,4); }
	
	function editkn($subject,$message,$msgId)
	{
		$data = $this->getknmsgbyid($msgId);
		if (($data == 0) || ($data[date_tsp]+300 < time()))
		{
			$return[msg] = "Zeitlimit überschritten";
			return $return;
		}
		if ($this->user != $data[user_id])
		{
			$return[msg] = "Du hast diese Nachricht nicht erstellt";
			return $return;
		}
		$this->db->query("UPDATE stu_kn_messages SET subject='".addslashes($subject)."',text='".addslashes($message)."' WHERE id=".$msgId);
		$return[msg] = "Nachricht editiert";
		return $return;
	}
	
	function checkcontact($user,$user2) { return $this->db->query("SELECT behaviour FROM stu_contactlist WHERE recipient=".$user." ANd user_id=".$user2,1); }
	
	function getpmcatmsg($cat)
	{
		$pm[ges] = $this->db->query("SELECT count(id) FROM stu_pms WHERE cate=".$cat." AND recipient=".$this->user." AND recip_del=0",1);
		$pm['new'] = $this->db->query("SELECT count(id) FROM stu_pms WHERE cate=".$cat." AND recipient=".$this->user." AND recip_del=0 AND new=1",1);
		return $pm;
	}
	
	function getknmsgbytxt($txt)
	{
		if (substr_count($txt," ") != 0)
		{
			$split = explode(" ",$txt);
			$wdd = "WHERE";
			for ($i=0;$i<count($split);$i++)
			{
				if ($i > 0) $wdd .= " AND";
				$wdd .= " a.text LIKE '%".addslashes($split[$i])."%'";
			}
		}
		else $wdd .= "WHERE text LIKE '%".addslashes($txt)."%'";
		return $this->db->query("SELECT a.id,a.user_id,a.subject,a.text,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.status,b.rasse,b.picture FROM stu_kn_messages as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id ".$wdd." ORDER BY a.date DESC",2);
	}
	
	function getaknmsgbytxt($txt)
	{
		global $myUser;
		if ($myUser->ually == 0) return 0;
		if (substr_count($txt," ") != 0)
		{
			$split = explode(" ",$txt);
			$wdd = "WHERE";
			for ($i=0;$i<count($split);$i++)
			{
				if ($i > 0) $wdd .= " AND";
				$wdd .= " a.text LIKE '%".addslashes($split[$i])."%'";
			}
		}
		else $wdd .= "WHERE text LIKE '%".addslashes($txt)."%'";
		return $this->db->query("SELECT a.id,a.user_id,a.subject,a.text,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.status,b.rasse,b.picture FROM stu_allys_messages as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id ".$wdd." AND a.allys_id=".$myUser->ually." ORDER BY a.date DESC",2);
	}
	
	function markallpmasread() { $this->db->query("UPDATE stu_pms SET new=0 WHERE recipient=".$this->user." AND new=1",$this->dblink); }
	
	function getmaindeskInfo()
	{
		global $myUser;
		$data[lz] = $this->getlzcount();
		if ($myUser->ually > 0) $data[alz] = $this->getallylzcount();
		$data[pm] = $this->db->query("SELECT count(id) FROM stu_pms WHERE new=1 AND recipient=".$this->user." AND recip_del=0",1);
		return $data;
	}
	
	function getoutpms() { return $this->db->query("SELECT a.id,a.message,a.recipient,UNIX_TIMESTAMP(a.date) as date_tsp,b.user FROM stu_pms as a LEFT OUTER JOIN stu_user as b ON a.recipient=b.id WHERE a.sender='".$this->user."' AND a.send_del=0 ORDER BY a.date DESC LIMIT 0,10",2); }
	
	function getoutpmsmark($begin) { return $this->db->query("SELECT a.id,a.message,a.recipient,UNIX_TIMESTAMP(a.date) as date_tsp,b.user FROM stu_pms as a LEFT OUTER JOIN stu_user as b ON a.recipient=b.id WHERE a.sender='".$this->user."' AND a.send_del=0 ORDER BY a.date DESC LIMIT ".$begin.",10",2); }
	
	function delalloutpms() { $this->db->query("UPDATE stu_pms SET send_del=1 WHERE sender=".$this->user); }
	
	function my_2d_sort(&$array, $sort, $d = 1) { usort ($array, create_function('$a,$b','return strcasecmp($a["'.$sort.'"],$b["'.$sort.'"])* '.$d.';')); }
	
	function getfolist()
	{
		global $myUser;
		if ($myUser->ually > 0) $add = " AND b.allys_id!=".$myUser->ually;
		$data = $this->db->query("SELECT a.recipient as rid,b.id,b.user,UNIX_TIMESTAMP(b.lastaction) as last_tsp,b.rasse FROM stu_contactlist as a LEFT OUTER JOIN stu_user as b ON a.recipient=b.id WHERE a.user_id=".$this->user." AND a.behaviour=1 AND a.recipient>100".$add." ORDER BY b.lastaction DESC",2);
		if ($myUser->ually > 0)
		{
			$adata = $this->db->query("SELECT user,id as rid,UNIX_TIMESTAMP(lastaction) as last_tsp,rasse FROM stu_user WHERE allys_id=".$myUser->ually." AND id!=".$this->user." ORDER BY lastaction DESC",2);
			if ($data == 0) $data = $adata;
			else $data = array_merge($data,$adata);
		}
		if (is_array($data))
		{
			$this->my_2d_sort($data,'last_tsp',-1);
			while(list($k, $v) = each($data)) $ndata[] = $v;
		}
		return $ndata;
	}
	
	function getnewpmcount() { return $this->db->query("SELECT COUNT(id) FROM stu_pms WHERE recipient=".$this->user." ANd new=1",1); }
	
	function markcatasread($catId) { $this->db->query("UPDATE stu_pms SET new=0 WHERE cate=".$catId." ANd recipient=".$this->user." ANd new=1"); }
	
	function getmaindeskpms() { return $this->db->query("SELECT LEFT(a.message,150) as msg,UNIX_TIMESTAMP(a.date) as date_tsp,b.user FROM stu_pms as a LEFT JOIN stu_user as b ON a.sender=b.id WHERE a.recipient=".$this->user." AND a.new=1 AND a.cate=1 ORDER BY a.id DESC LIMIT 5"); }
	
	function dellist()
	{
		$this->db->query("DELETE FROM stu_contactlist WHERE user_id=".$this->user);
		$return[msg] = "Kontaktliste geleert";
		return $return;
	}
	
	function getknmsgbyuser($userId) { return $this->db->query("SELECT a.id,a.user_id,a.subject,a.text,UNIX_TIMESTAMP(a.date) as date_tsp,b.user,b.status,b.rasse,b.picture FROM stu_kn_messages as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.user_id=".$userId." ORDER BY a.date DESC"); }
}
?>
