<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
<tr>
	<td class=tdmain>/ <a href=page=main>STU</a> / <strong>Registrierung</strong></td>
</tr>
</Table><br>
<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
<?php
include_once("class/user.class.php");
$myUser = new user;
include_once("class/game.class.php");
$myGame = new game;
if ($sent == 1)
{
	if (!$login) $error[login] = "Du hast keinen Loginnamen angegeben.";
	if (!$user) $error[user] = "Du hast keinen Spielernamen angegeben.";
	if (strlen(strip_tags($user)) > 40) $error[user] = "Der Username darf ohne HTML nur aus maximal 40 Zeichen bestehen.";
	if (!$email) $error[email] = "Du hast keine Emailadresse angegeben.";
	if (!eregi("^[a-z0-9]+([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}",$email)) $error[email] = "Hierbei handelt es sich um keine gültige Emailadresse";
	if (!$pw) $error[pw] = "Du hast kein Passwort angegeben.";
	if (!$pw2) $error[pw2] = "Du hast das Passwort nicht bestätigt.";
	if (!$rules) $error[rules] = "Du hast die Regeln nicht bestätigt";
	if ($pw && $pw2 && ($pw != $pw2)) $error[pw2] = "Die Passwörter stimmen nicht überein.";
	if (strlen($pw) < 5) $error[pw3] = "Das Passwort muss aus mindestens 5 Zeichen bestehen.";
	if (!$error)
	{
		$runde = $myGame->getcurrentround();
		$result = $myUser->addUser($login,$user,$email,$pw,$seite,$runde[runde]);
		if ($result[code] != 1) $error[register] = $result[msg];
	}
}
if ($actcode != "")
{
	$result = $myUser->activateUser($actcode,$user);
	if ($result == 1)
	{
		echo "<tr>
			<td class=tdmain align=center>Aktivierung erfolgreich</td>
		</tr>
		<tr>
			<td class=tdmainobg width=400>Du kannst Dich nun mit Deinem Loginnamen und Deinem Passwort einloggen.<br>
			Viel Spaß!</td>
		</tr>";
	}
	else
	{
		echo "<tr>
			<td class=tdmain align=center>Aktivierung fehlgeschlagen</td>
		</tr>
		<tr>
			<td class=tdmainobg width=400>Sollte die Aktivierung trotz eines gültigen Aktivierungscodes fehlgeschlagen sein, setze Dich mit den Admins in Kontakt.</td>
		</tr>";
	}
	$s = 1;
}
if ($s != 1)
{
		echo "<tr>
			<td class=tdmain align=center>Aufgrund der hohen Belastung musste die Registrierung leider deaktiviert werden. Wir würden uns freuen, wenn wir alle Spieler, die jetzt leider keinen Platz mehr bekommen haben, in Kürze in Star Trek Universe 2 begrüßen dürfen.</td>
		</tr>";


}
?>
</table>