<?php
if (!$limit) $limit = 30;
$result = $myHistory->gethistory($limit,$cat);
echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
<tr>
	<td width=100% class=tdmain>/ <strong>History</strong></td>
</tr>
</table><br>
<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>";
if (mysql_num_rows($result) == 0) echo "<tr><td colspan=2 class=tdmainobg align=center>Es wurden keine Geschehennisse gespeichert</td></tr>";
else
{
	echo "<form action=main.php method=post>
	<input type=hidden name=page value=history>
	<tr>
		<td class=tdmainobg colspan=2>Die letzten <input type=text size=4 name=limit class=text value=30> Events der Kategorie <select name=cat><option value=0>alle<option value=1>Kampfmeldungen<option value=2>Selbstzerstörung<option value=3>Diplomatie</select> <input type=submit value=anzeigen class=button></td>
	</tr>
	</form>";
	while($data=mysql_fetch_assoc($result))
	{
		echo "<tr><td class=tdmainobg width=15%>".date("d.m.",$data[date_tsp]).(date("Y",$data[date_tsp])+375).date(" H:i",$data[date_tsp])."</td>
			<td class=tdmainobg>".stripslashes($data[message])."</td></tr>";
	}
}
echo "</table>";
?>