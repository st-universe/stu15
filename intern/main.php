<table width=100%>
<tr>
	<td class=tdmaintop colspan=5 align=center width=100%>Hauptseite</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td class=tdmain width=20% align=center><strong>Spieler finden</strong></td>
	<td width=20%></td>
	<td class=tdmain width=20% align=center><strong>Schiff finden</strong></td>
	<td width=20%></td>
	<td class=tdmain width=20% align=center><strong>Kolonie finden</strong></td>
</tr>
<tr>
	<td class=tdmain align=center><form action=index.php method=post><input type=hidden name=page value=player>ID <input type=text class=text size=5 name=id> <input type=submit class=button value=Suche></form></td>
	<td></td>
	<td class=tdmain align=center><form action=index.php method=post><input type=hidden name=page value=ship>ID <input type=text class=text size=5 name=id> <input type=submit class=button value=Suche></form></td>
	<td></td>
	<td class=tdmain align=center><form action=index.php method=post><input type=hidden name=page value=colony> <input type=hidden name=section value=showcolony>ID <input type=text class=text size=5 name=id> <input type=submit class=button value=Suche></form></td>
</tr>
</table>
<br>
<table width=100%>
<tr>
	<td colspan=5 align=center>
	<table width=550 bgcolor=#262323>
	<tr>
		<td class=tdmaintop colspan=2 align=center width=100%>NPC Nachrichten</td>
	</tr>
	<?php
	$data = $myComm->getnpcmsg();
	for ($i=0;$i<count($data);$i++)
	{
		if ($data[$i][npm][1] == 0) $pnmp = 0;
		else $pnmp = "<font color=red>".$data[$i][npm][1]."</font>";
		if ($data[$i][npm][2] == 0) $snmp = 0;
		else $snmp = "<font color=red>".$data[$i][npm][2]."</font>";
		if ($data[$i][npm][3] == 0) $hnmp = 0;
		else $hnmp = "<font color=red>".$data[$i][npm][3]."</font>";
		if ($data[$i][npm][4] == 0) $knmp = 0;
		else $knmp = "<font color=red>".$data[$i][npm][4]."</font>";
		echo "<tr>
			<td class=tdmainobg>".$data[$i][user]."</td>
			<td class=tdmainobg>Privat: ".$pnmp." - Schiffe: ".$snmp." - Handel: ".$hnmp." - Kolonien: ".$knmp."</td>
		</tr>";
	}
	?>
	</table>
	</td>
</tr>
</table>