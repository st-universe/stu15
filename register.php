<?php
/*$ip[0] = "217.232.199";
$ip[1] = "217.232.200";
$cip = getenv("REMOTE_ADDR");
$tok = strtok($cip,".");
echo $cip." - ".$tok[0].".".$tok[1].".".$tok[2];
for ($i=0;$i<count($ip);$i++) if ($ip[$i] == $tok[0].".".$tok[1].".".$tok[2]) exit;
if ($sent == 1) {
	if (!$login) $error[login] = "Du hast keinen Loginnamen angegeben.";
	if (!$user) $error[user] = "Du hast keinen Spielernamen angegeben.";
	if (!$email) $error[email] = "Du hast keine Emailadresse angegeben.";
	if (!$pw) $error[pw] = "Du hast kein Passwort angegeben.";
	if (!$pw2) $error[pw2] = "Du hast das Passwort nicht bestätigt.";
	if ($pw && $pw2 && ($pw != $pw2)) $error[pw2] = "Die Passwörter stimmen nicht überein.";
	if (strlen($pw) < 5) $error[pw3] = "Das Passwort muss aus mindestens 5 Zeichen bestehen.";
	if (!$error) {
		include_once("inc/config.inc.php");
		include_once("class/db.class.php");
		$myDB = new db;
		include_once("class/game.class.php");
		$myGame = new game;
		include_once("class/user.class.php");
		$myUser = new user;
		$runde = $myGame->getcurrentround();
		$result = $myUser->addUser($login,$user,$email,$pw,$seite,$runde[runde]);
		if ($result == -1) $error[register] = "Es sind derzeit keine weiteren Anmeldungen möglich";
		elseif ($result == 0) $error[register] = "Es existiert schon ein User mit diesem Login oder mit dieser Emailadresse";
		else {
			include_once("inc/functions.inc.php");
			addlog("500","6",$result,getenv("SCRIPT_NAME"),"-");
		}
	}
}
if (!$result || $result == 0) {
	echo "<table width=500 align=center>
	<tr>
		<td align=center colspan=2><b>Registrierung</b></td>
	</tr>
	<form action=register.php mehtod=post>
	<input type=hidden name=sent value=1>";
	if ($error[register]) echo "<tr><td colspan=2 align=center><font color=Red>".$error[register]."</font></td></tr>";
	echo "<tr>
		<td>Loginname</td>
		<td><input type=text name=login size=15 value='".$login."'></td>
	</tr>";
	if ($error[login]) echo "<tr><td colspan=2 align=center><font color=Red>".$error[login]."</font></td></tr>";
	echo "<tr>
		<td>Spielername</td>
		<td><input type=text name=user size=15 value='".$user."'></td>
	</tr>";
	if ($error[user]) echo "<tr><td colspan=2 align=center><font color=Red>".$error[user]."</font></td></tr>";
	echo "<tr>
		<td>Emailadresse</td>
		<td><input type=text name=email size=15 value='".$email."'></td>
	</tr>";
	if ($error[email]) echo "<tr><td colspan=2 align=center><font color=Red>".$error[email]."</font></td></tr>";
	echo "<tr>
		<td>Passwort</td>
		<td><input type=password name=pw size=15></td>
	</tr>";
	if ($error[pw]) echo "<tr><td colspan=2 align=center><font color=Red>".$error[pw]."</font></td></tr>";
	if ($error[pw3]) echo "<tr><td colspan=2 align=center><font color=Red>".$error[pw3]."</font></td></tr>";
	echo "<tr>
		<td>Passwort wiederholen</td>
		<td><input type=password name=pw2 size=15></td>
	</tr>";
	if ($error[pw2]) echo "<tr><td colspan=2 align=center><font color=Red>".$error[pw2]."</font></td></tr>";
	echo "<tr>
		<td>Seite</td>
		<td><select name=seite>
		<option value=1 SELECTED>Federation
		<option value=2>Romulaner
		<option value=3>Klingonen
		<option value=4>Cardassianer
	</select></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit value=Abschicken></form></td>
	</tr>";
} elseif ($result > 0) {
	echo "<table width=300 align=center>
	<tr>
		<td align=center><b>Registrierung</b></td>
	</tr>
	<tr>
		<td>Die Anmeldung war erfolgreich. Du kannst Dich nun mit '".$login."' und Deinem Passwort einloggen.<br>
		Viel Spaß!</td>
	</tr>";
}*/
?>