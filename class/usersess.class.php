<?php
class usersess
{
	function usersess()
	{
		global $myDB;
		$this->db = $myDB;
	}
	
	function sessioncheck() { $this->checkstatus();	}
	
	function checkstatus()
	{
		if ($_SESSION["lgtime"] < time()-3600) $this->logout();
		if ($this->userdata[login] == 1) return 0;
		else
		{
			global $_SESSION;
			if ($_SESSION["user"] && $_SESSION["chk"]) return $this->checkcookie();
			else $this->logout();
		}
	}
	
	function checkcookie()
	{
		global $_SESSION;
		$data = $this->db->query("SELECT id,email,aktiv FROM stu_user WHERE id='".$_SESSION["user"]."'",4);
		if ($data[aktiv] == 2) $this->logout();
		$chksum = substr(md5($data[id].$data[email]),1,9);
		if ($chksum != $_SESSION["chk"])
		{
			$this->userdata[login] = 0;
			return 0;
		}
		$this->userdata[uid] = $data[id];
		$this->userdata[login] = 1;
		$this->db->query("UPDATE stu_user SET lastaction=NOW() WHERE id=".$data[id]);
		return 1;
	}
	
	function login ($user,$pass,$alog)
	{
		$data = $this->db->query("SELECT id,email,aktiv,vactime FROM stu_user WHERE login='".addslashes($user)."' AND pass='".md5($pass)."'",4);
		if ($data == 0)
		{
			$return[code] = 0;
			$return[msg] = "Falsche Userdaten!";
			return $return;
		}
		if ($data[aktiv] == 0)
		{
			$return[code] = 0;
			$return[msg] = "User noch nicht aktiviert!";
			return $return;
		}
		if ($this->db->query("SELECT COUNT(id) FROM stu_user WHERE UNIX_TIMESTAMP(lastaction)>".(time()-300)." AND id>100",1) >= 55 && $data[id] > 100)
		{
			$return[code] = 0;
			$return[msg] = "Es sind zur Zeit über 55 Spieler eingeloggt. Login nicht möglich. Bitte versuche es zu einem späteren Zeitpunkt nochmal!";
			return $return;
		}
		if ($data[aktiv] == 2)
		{
			$return[msg] = "Dein Account wurde wegen eines Regelverstoßes deaktiviert.<br>
							Sollte dies zu unrecht geschehen sein, melde Dich im <a href=http://forum.stuniverse.de/viewforum.php?f=13 target=_blank>Forum->Gerichtshof</a> bei dem entsprechenden Beitrag";
			$return[code] = 0;
			return $return;
		}
		if ($data[vactime]+86400 > time())
		{
			$return[msg] = "Der Urlaubsmodus muss mindestens 1 Tag laufen, bis er wieder beendet werden kann";
			$return[code] = 0;
			return $return;
		}
		$this->userdata['login'] = 1;
		$this->userdata['user'] = $data[id];
		$chk = substr(md5($data[id].$data[email]),1,9);
		global $_SESSION;
		$_SESSION["user"] = $data[id];
		$_SESSION["chk"] = $chk;
		$_SESSION["lgtime"] = time();
		$this->db->query("UPDATE stu_user SET lastaction=NOW() WHERE id=".$data[id]);
		$return[code] = 1;
		$return[uid] = $data[id];
		return $return;
	}
	
	function logout()
	{
		$this->userdata[login] = 0;
		$this->userdata[uid] = 0;
		session_destroy();
	}
}
?>