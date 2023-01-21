<?php
include_once("class/user.class.php");
$myUser = new user;
if (!$section || ($section == "main")) {
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain>/ <a href=page=main>STU</a> / <strong>Password Recovery</strong></td>
	</tr>
	</Table><br>
	<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<form action=index.php method=post>
	<input type=hidden name=page value=lostpass>
	<input type=hidden name=section value=receive>
	<tr>
		<td class=tdmainobg>User-ID</td>
		<td class=tdmainobg><input type=login size=10 name=userid class=text></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit value=Recover class=button></td>
	</tr>
	</table>
	</form>";
} elseif ($section == "receive") {
	if ($userid) $result = $myUser->passrecovery($userid);
	echo "
	<table bgcolor=#262323>
	<form action=index.php method=post>
	<input type=hidden name=page value=lostpass>
	<input type=hidden name=section value=receive>
	<tr>
		<td align=center class=tdmain><strong>Passwort Recovery</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center>".$result[msg]."</td>
	</tr>
	<tr>
		<td align=center class=tdmainobg><a href=index.php?page=lostpass&section=changepass>Passwort ändern</a></td>
	</tr>
	</table>
	</form>";
} elseif ($section == "changepass") {
	if ($pass && $pass2 && $code) $result = $myUser->editpass($code,$pass,$pass2);
	echo "
	<table bgcolor=#262323>
	<form action=index.php method=post>
	<input type=hidden name=page value=lostpass>
	<input type=hidden name=section value=changepass>
	<tr>
		<td colspan=2 align=center class=tdmain><strong>Passwort Recovery</strong></td>
	</tr>";
	if ($result) echo "<tr><td colspan=2 class=tdmainobg>".$result[msg]."</td></tr>";
	echo "<tr>
		<td class=tdmainobg>Aktivierungscode</td>
		<td class=tdmainobg><input type=text size=10 name=code class=text></td>
	</tr>
	<tr>
		<td class=tdmainobg>Neues Passwort</td>
		<td class=tdmainobg><input type=password size=10 name=pass class=text></td>
	</tr>
	<tr>
		<td class=tdmainobg>Passwort wiederholen</td>
		<td class=tdmainobg><input type=password size=10 name=pass2 class=text></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit value=Ändern class=button></td>
	</tr>
	</table>
	</form>";
}
?>
