<?php
$ulist = $myComm->getfolist($user);
$pm = $myComm->getnewpmcount($user);
echo "<table width=100% height=100% bgcolor=#262323>
<tr>
	<td class=tdmain align=center colspan=2><strong>Kontaktliste</strong></td>
</tr>
<tr>
	<td class=tdmainobg colspan=2><a href=http://www.stuniverse.de target=_blank>STU Login</a> - Neue PMs: ".$pm."</td>
</tr>";
if ($ulist == 0) echo "<tr><td class=tdmainobg align=Center colspan=2>Du hast keine Freunde eingetragen</td></tr>";
else
{
	echo "<tr>
		<td class=tdmainobg align=center colspan=2><strong><font color=Green>Online</font></strong></td>
	</tr>";
	$off = 0;
	for ($i=0;$i<count($ulist);$i++)
	{
		if ($ulist[$i][last_tsp] < (time()-300) && $off == 0)
		{
			echo "<tr>
				<td class=tdmainobg align=center colspan=2><strong><font color=Red>Offline</font></strong></td>
			</tr>";
			$off = 1;
		}
		echo "<tr>
		<td class=tdmainobg width=40><a href=main.php?page=comm&section=writepm&recipient=".$ulist[$i][rid]." onMouseOver=document.msg".$i.".src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg".$i.".src='".$grafik."/buttons/msg1.gif' target=main><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0></a>&nbsp;<img src=".$grafik."/rassen/".$ulist[$i][rasse]."s.gif></td>
		<td class=tdmainobg>".stripslashes($ulist[$i][user])."</td></tr>";
	}
}
echo "<tr><td class=tdmainobg height=100% colspan=2>&nbsp;</td></tr>
<tr>
	<td class=tdmainobg align=Center colspan=2><a href=\"javascript:self.close()\">[Schlieﬂen]</a></td>
</tr></table>";
?>