<?php
$tmpdata = $myUser->getUserById($user);
$tmpprofile = $myUser->getUserProfile($user);
if ($tmpdata[grafik] == $new_grafik) unset($new_grafik);
if ($tmpdata[user] == $username) unset($username);
if ($tmpdata[picture] == $new_picture) $new_picture = "stop";
if (($sent == 1) && (strlen($username) > 0) && ($username != $myUser->uuser))
{
	if (strlen(strip_tags($username)) > 40) $msg[] = "Der Name darf nur maximal 40 Zeichen ohne HTML enthalten";
	else
	{
		$username = str_replace("\"","",strip_tags($username,"<font><b></font></b><sup></sup>"));
		$username = str_replace("size=+","",$username);
		$username = str_replace("size=","",$username);
		$myUser->updateUserById($user,$myGame->completetags(addslashes(str_replace("background","",$username))),"user");
		$msg[] = "Username geändert";
	}
}
if (($sent == 1) && $new_pass)
{
	if (strlen($new_pass) < 5) $msg[] = "Das Passwort muss aus mindestens 5 Zeichen bestehen";
	else
	{
		$myUser->updateUserById($user,md5($new_pass),"pass");
		$msg[] = "<font color=red>Passwort geändert<br>Du musst Dich neu einloggen</font>";
	}
}
if (($sent == 1) && ($icq != "") && ($icq != $myUser->uicq))
{
	$myUser->updateProfileById($user,$icq,"icq");
	$msg[]= "ICQ-No geändert";
}
if (($sent == 1) && ($new_grafik) && ($new_grafik != $grafik))
{
	$myUser->updateUserById($user,$new_grafik,"grafik");
	$msg[] = "Grafikpfad geändert";
}
if (($sent == 1) && $new_picture && ($new_picture != "stop"))
{
	if (substr_count($new_picture,"http://") == 0) $msg .= "Die Bild-URL ist fehlerhaft";
	else
	{
		$myUser->updateUserById($user,str_replace("\"","",$new_picture),"picture");
		$msg[] = "KN-/Profil-Bild URL geändert";
	}
}
if (($sent == 1) && ($new_picture != "http://") && ($new_picture == ""))
{
	$myUser->updateUserById($user,addslashes(str_replace("\"","",$new_picture)),"picture");
	$msg[] = "KN-/Profil-Bild UR gelöscht";
}
if (($sent == 1) && $delmark) {
	$myUser->updateUserById($user,1,"delmark");
	$msg[] = "Dein Account wurde zum löschen markiert";
}
if (($sent == 2) && ($regi != $tmpprofile[regierung]))
{
	$myUser->updateProfileById($user,addslashes(stripslashes($regi)),"regierung");
	$msg[] = "Regierungsform geändert";
}
if (($sent == 2) && ($rpgtxt != $tmpprofile[rpgtxt]))
{
	$myUser->updateProfileById($user,addslashes(stripslashes($rpgtxt)),"rpgtxt");
	$msg[] = "RPG-Text upgedated";
}
if ($sent == 1)
{
	if ($mozilla == "on" && $myUser->umozilla != 1)
	{
		$myUser->updateUserById($user,1,"mozilla");
		$msg[] = "Mozilla-Modus aktiviert";
	}
	if ($mozilla == "" && $myUser->umozilla != 0)
	{
		$myUser->updateUserById($user,0,"mozilla");
		$msg[] = "Mozilla-Modus deaktiviert";
	}
	$myUser = new user;
}
?>
<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
<tr>
	<td class=tdmain>/ <strong>Einstellungen</strong></td>
</tr>
</table><br>
<?php
if (is_array($msg))
{
	echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr>
		<tr><td class=tdmainobg>";
	for ($i=0;$i<count($msg);$i++)
	{
		echo $msg[$i];
		if ($msg[$i+1] != "") echo "<br>";
	}
	echo "</td></tr></table><br>";
	$myUser = new user;
}
$profile = $myUser->getUserProfile($user);
if ($myUser->uholyday == 1) $holcheck = "CHECKED";
if ($myUser->udelmark == 1) $delcheck = "CHECKED";
if ($myUser->umozilla == 1) $mocheck = "CHECKED";
?>
<table bgcolor=#262323 cellspacing=1 cellpadding=1>
<tr>
<form action=main.php method=post>
<input type=hidden name=page value=options>
<input type=hidden name=sent value=1>
	<td class=tdmainobg>Spielername: <input class=text size=30 type=text name=username value="<?php echo $myUser->uuser ?>"></td><td class=tdmainobg>font-Tag erlaubt (max 40 Zeichen ohne HTML. max 255)</td></tr>
	<tr><td class=tdmainobg>Passwort ändern: <input class=text type=password name=new_pass size=15></td><td class=tdmainobg>mind 5 Zeichen</td></tr>
	<tr><td class=tdmainobg colspan=2>ICQ-No: <input type=text name=icq size=10 class=text value='<?php echo $myUser->uicq ?>'></td></tr>
	<tr><td class=tdmainobg>Grafikpfad: <input class=text type=text size=40 name=new_grafik value='<?php echo $grafik ?>'></td><td class=tdmainobg><input class=button type=button value='Standard' onClick="document.all.new_grafik.value = 'http://gfx.stuniverse.de'"> <a href=gfx/images.zip>Grafikpack downloaden</a></td></tr>
	<tr><td class=tdmainobg colspan=2>Beispiel: file:///Laufwerk|/Verzeichnis/stu</td></tr>
	<tr><td class=tdmainobg>KN-/Profil-Bild URL: <input class=text type=text size=40 name=new_picture value="<?php echo stripslashes($myUser->upicture) ?>"></td><td class=tdmainobg>max 64*64px</td></tr>
	<tr><td class=tdmainobg>Mozilla Modus <input type=checkbox name=mozilla <?php echo $mocheck; ?>></td><td class=tdmainobg>Behebt CSS-Probleme bei manchen Browsern</td></tr>
	<tr><td class=tdmainobg>Zum löschen markieren: <input type="checkbox" name="delmark" <?php echo $delcheck; ?>></td><td class=tdmainobg>Damit wird der Account zum nächsten Rundenwechsel gelöscht</td></tr>
	<tr><td class=tdmainobg>Urlaubsmodus aktivieren: <input type="checkbox" name="avm"<?php if ($myUser->upvac == 0) echo " disabled=yes"; ?>></td><td class=tdmainobg><a href=http://wiki.stuniverse.de/index.php/Urlaubsmodus target=_blank>Wiki-Artikel zum Urlaubsmodus</a></td></tr>
	<tr><td class=tdmainobg colspan=2>Du kannst den Urlaubsmodus in diesem Monat noch <?php echo $myUser->upvac ?> mal aktivieren</td></tr>
	<tr><td class=tdmainobg colspan=2><input type=submit name=submit value='Einstellungen ändern' class=button></td></form>
</tr>
</table><br>
<table width=700 cellpadding=1 cellspacing=1 bgcolor=#262323>
<form action=main.php method=post>
<input type=hidden name=page value=options>
<input type=hidden name=sent value=2>
<tr>
	<td class=tdmain align=center><strong>RPG-Info</strong></td>
</tr>
<tr>
	<td class=tdmainobg>Regierungsform <input type=text size=30 name=regi class=text value='<?php echo stripslashes($profile[regierung]); ?>'></td>
</tr>
<tr>
	<td class=tdmainobg>Hier könnt ihr einen kleinen RPG-Text über euch verfassen. Wer ihr seid, woher ihr kommt, etc</td>
</tr>
<tr>
	<td class=tdmainobg><textarea name=rpgtxt cols=80 rows=20><?php echo stripslashes($profile[rpgtxt]);?></textarea></td>
</tr>
<tr>
	<td class=tdmainobg><input type=submit class=button value=Ändern></td>
</tr>
</form>
</Table>