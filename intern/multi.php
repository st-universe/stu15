<?php
if (!$dt) $dt = time();
$d = date("d",$dt);
$m = date("m",$dt);
$y = date("Y",$dt);
$day = mktime(0,0,0,$m,$d,$y);
echo "<table width=700 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=700 class=tdmain align=center><strong>Multis - mehrfache Logins</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg><a href=?page=multi&section=user>by UserId</a> / <a href=?page=multi&section=ip>by IP</a></td>
	</tr>
	</table><br>";
if (!$section || ($section == "user"))
{
	echo "<table width=700 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmainobg>ID</td>
		<td class=tdmainobg width=150>User</td>
		<td class=tdmainobg width=500></td>
	</tr>";
	$dbl = $myUser->getdoublelogins($day);
	for ($i=0;$i<count($dbl);$i++)
	{
		if ($dbl[$i][nlg] == 2)
		{
			echo "<tr>
				<td class=tdmainobg>".$dbl[$i][id]."</td>
				<td class=tdmainobg>".$dbl[$i][user]."</td>
				<td class=tdmainobg>";
				for ($j=0;$j<count($dbl[$i][lg]);$j++)
				{
					if ($dbl[$i][lg][$j][ende_tsp] == 0) $ende = "-";
					else $ende = date("d.m H:i",$dbl[$i][lg][$j][ende_tsp]);
					echo "IP ".$dbl[$i][lg][$j][ip]." Start ".date("d.m H:i",$dbl[$i][lg][$j][start_tsp])." Ende ".$ende."<br>";
				}
			echo "</td>
			</tr><tr><td colspan=3 class=Tdmainobg>&nbsp;</td></tr>";
		}
	}
	echo "</table><br>";
}
elseif ($section == "ip")
{
	$time = time();
	$time2 = $time-864000;
	for ($i=1;$i<=10;$i++) $dl .= "<a href=?page=multi&section=ip&dt=".($time2+(86400*$i)).">".date("d.m",($time2+(86400*$i)))."</a> | ";
	echo "<table width=500 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td colspan=2 class=tdmainobg>".$dl."</td>
	</tr>
	<tr>
		<td class=tdmainobg width=150>IP (".date("d.m",$dt).")</td>
		<td class=tdmainobg width=350></td>
	</tr>";
	$dbl = $myUser->getiplogins($day);
	for ($i=0;$i<count($dbl);$i++)
	{
		if ($dbl[$i][nlg] == 1)
		{
			echo "<tr>
				<td class=tdmainobg>".$dbl[$i][ip]."</td>
				<td class=tdmainobg>";
				for ($j=0;$j<count($dbl[$i][ipl]);$j++)
				{
					if ($dbl[$i][ipl][$j][ende_tsp] == 0) $ende = "-";
					else $ende = date("H:i",$dbl[$i][ipl][$j][ende_tsp]);
					echo "ID ".$dbl[$i][ipl][$j][user_id]." ".date("H:i",$dbl[$i][ipl][$j][start_tsp])." - ".$ende."<br>";
				}
			echo "</td>
			</tr><tr><td colspan=3 class=Tdmainobg>&nbsp;</td></tr>";
		}
	}
	echo "</table><br>";
}
?>