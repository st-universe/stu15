<?php
if (!$errorid) $msg = "Fehler bei der Parameterübergabe!";
elseif ($errorid == 999) $msg = "Derzeit ist der Wartungsmodus aktiv!<br>Das Spiel ist derzeit offline - Ticks wurden ausgesetzt!";
elseif ($errorid == 998) $msg = "Datenbankverbindung fehlgeschlagen!";
elseif ($errorid == 997) $msg = "Falsche Userdaten!<br>
								Login-Probleme? -> News lesen";
elseif ($errorid == 996) $msg = "Session abgelaufen - Bitte neu einloggen<br>
								<a href=http://www.stuniverse.de target=_parent>Login</a>";
elseif ($errorid == 100) $msg = "Derzeit ist der Rundenwechsel aktiv. Bitte etwas Geduld.<br>
								 Falls euch langweilig ist, lest doch die <a href=http://scout.stuniverse.de target=_blank>STU-Zeitung</a>";
elseif ($errorid == 700) $msg = "Der Urlaubsmodus wurde aktiviert - Du wurdest automatisch ausgeloggt<br>
								 Der Modus wird beim nächsten Login automatisch wieder deaktiviert";
elseif ($errorid == 404)
{
	echo "<head>
	<link rel=\"STYLESHEET\" type=\"text/css\" href=gfx/css/style.css>
</head>";
	$msg = "404 - Seite nicht gefunden";
}
echo "<table width=400 cellspacing=1 cellpadding=1 bgcolor=#262323 align=center>
<tr>
	<td width=100% align=center class=tdmaintop><strong>Fehler</strong></td>
</tr>
<tr>
	<td class=tdmainobg align=center width=100%>".$msg."</td>
</tr>
</table>";
?>