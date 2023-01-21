<?php
$rounds = $myGame->getcurrentround();
$time = time() - $rounds[start_tsp];
$sek = $time - (floor($time/60)*60);
$min = floor($time/60) - (floor(floor($time/60)/60)*60);
$day = floor(floor(floor($time/60)/60)/24);
$hour = floor(floor($time/60)/60) - ($day*24);
if ($day > 0) $day = $day." Tagen, ";
$result = $myComm->getmaindeskInfo($user);
echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
<tr>
	<td class=tdmain>/ <strong>Maindesk</strong></td>
</tr>
</table><br><table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
<tr>
	<td class=tdmainobg width=300 valign=top align=center><img src=".$grafik."/rassen/".$myUser->urasse.".jpg></td>
	<td class=tdmainobg valign=top><strong>Spielerstatus</strong><br>
	Eingeloggt: ".$myUser->uuser." (".$user.")<br>
	Dein Kolonistenlevel: ".$myUser->ulevel."<br>
	Sympathie bei der Handelsallianz: ".$myUser->usymp;
	if ($myUser->uwirtmin > 0) echo "<br>Aufzuholende Wirtschaftspunkte: ".$myUser->uwirtmin;
	if ($myUser->ulastloginround < $rounds[runde]) echo "<br>Inaktivität: ".($rounds[runde] - $myUser->ulastloginround)." Runde(n)";
	if ($myUser->udelmark == 1) echo "<br><font color=Red>Dein Account wurde zum löschen markiert</font>";
	if ($myUser->ulevel == 0) echo "<br><br><strong>Kolonisation</strong><br>Dein Kolonistenlevel ist 0.<br>Um in das nächste Level zu gelangen musst Du ein <a href=main.php?page=hally&section=getship>Kolonieschiff beantragen</a>";
	if ($mdvm == 1) echo "<br><font color=red>Der Urlaubsmodus wurde deaktiviert</font>";
	if (($result[lz] > 0) || ($result[alz] > 0) || ($result[pm] > 0))
	{
		echo "<br><br><strong>Kommunikationsmeldungen</strong>";
		if ($result[lz] > 0) echo "<br><a href=main.php?page=comm&section=kn&mark=".$myUser->ukn_lz.">KN-Beiträge nach Lesezeichen</a>: ".$result[lz];
		if ($result[alz] > 0) echo "<br><a href=main.php?page=comm&section=allykn&mark=".$myUser->ukn_allylz.">AKN-Beiträge nach Lesezeichen</a>: ".$result[alz];
		if ($result[pm] > 0) echo "<br><a href=main.php?page=comm&section=pm>Neue PMs</a>: ".$result[pm];
	}
	echo "<br><br><strong>Rundenstatistiken</strong><br>
	Aktuelle Runde: ".$rounds[runde]." (<a href=main.php?page=stats&section=tick>Verlauf</a>)<br>
	Laufzeit: ".$hour."h, ".$min."m, ".$sek."s</td>
</tr>
</table>";
$result = $myComm->getmaindeskpms();
if (mysql_num_rows($result) != 0)
{
	echo "<br><table width=350 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr><td class=tdmain align=center><strong>Die letzten 5 privaten PMs</strong></td></tr>";
	while($pm=mysql_fetch_assoc($result)) echo "<tr><td class=tdmainobg>".nl2br(stripslashes($pm[msg]))."...</td></tr><tr><td class=tdmainobg>".date("d.m.Y H:i",$pm[date_tsp])." von ".$pm[user]."</td></tr>";
	echo "</table>";
}
$myUser->setlastround($rounds[runde],$user);
?>