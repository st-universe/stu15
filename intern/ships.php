<?php
if (!$section || ($section == "main")) {
	echo "<table><tr><td class=tdmain>".$return[msg]."</td></tr></table>";
	?>
	<table width=100%>
	<tr>
		<td class=tdmaintop colspan=5 align=center width=100%>Schiffe</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
	<td class=tdnav><center><a href=index.php?page=ships&section=shipmoddb>Modulliste</a></center></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?php
	$ships = $myColony->getmissionships();
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain colspan=9><strong><center>Missionsschiffe</center></strong></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center  width=20><strong>L</strong></td>
		<td class=tdmainobg align=center width=80><strong>Typ</strong></td>
		<td class=tdmainobg><strong>Name</strong></td>
		<td class=tdmainobg align=center width=60><strong>ID</strong></td>
		<td class=tdmainobg align=center width=60><strong>x/y</strong></td>
		<td class=tdmainobg align=center width=60><strong>Zustand</strong></td>
		<td class=tdmainobg align=center width=60><strong>E</strong></td>
		<td class=tdmainobg align=center width=60><strong>Schilde</strong></td>
		<td class=tdmainobg align=Center width=60><strong>Crew</strong></td>
		<td class=tdmainobg align=Center width=60><strong>K/L/T</strong></td>
	</tr>";
	for ($i=0;$i<count($ships);$i++)
	{
		echo "	<tr>
			<td class=tdmainobg align=center><img src=".$grafik."/goods/".$ships[$i][goods_id].".gif border=0></td>
			<td class=tdmainobg align=center><img src=".$grafik."/ships/".$ships[$i][ships_rumps_id].".gif border=0></td>
			<td class=tdmainobg>".$ships[$i][name]."</td>
			<td class=tdmainobg align=center>".$ships[$i][id]."</td>
			<td class=tdmainobg align=center>".$ships[$i][coords_x]."/".$ships[$i][coords_y]." (".$ships[$i][wese].")</td>
			<td class=tdmainobg align=center>".$ships[$i][huelle]."</td>
			<td class=tdmainobg align=center>".$ships[$i][energie]."</td>
			<td class=tdmainobg align=center>".$ships[$i][schilde]."</td>
			<td class=tdmainobg align=Center>".$ships[$i][crew]."</td>
			<td class=tdmainobg align=Center>".$ships[$i][kss]."/".$ships[$i][lss]."/".$ships[$i][cloak]."</td>
		</tr>";
	}
	echo "</table>";
	$ships = $myColony->getracespaceships();
	echo "<br><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain colspan=9><strong><center>Schiffe in Rassenraum</center></strong></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center  width=35><strong>R</strong></td>
		<td class=tdmainobg align=center width=80><strong>Typ</strong></td>
		<td class=tdmainobg><strong>Name</strong></td>
		<td class=tdmainobg align=center width=60><strong>ID</strong></td>
		<td class=tdmainobg align=center width=60><strong>x/y</strong></td>
		<td class=tdmainobg align=center width=60><strong>Zustand</strong></td>
		<td class=tdmainobg align=center width=60><strong>E</strong></td>
		<td class=tdmainobg align=center width=60><strong>Schilde</strong></td>
		<td class=tdmainobg align=Center width=60><strong>Crew</strong></td>
		<td class=tdmainobg align=Center width=60><strong>K/L/T</strong></td>
	</tr>";
	for ($i=0;$i<count($ships);$i++)
	{
		echo "	<tr>
			<td class=tdmainobg align=center><img src=file:///D|/stu/map/r".$ships[$i][race].".gif border=0></td>
			<td class=tdmainobg align=center><img src=".$grafik."/ships/".$ships[$i][ships_rumps_id].".gif border=0></td>
			<td class=tdmainobg>".$ships[$i][name]."</td>
			<td class=tdmainobg align=center>".$ships[$i][id]."</td>
			<td class=tdmainobg align=center>".$ships[$i][coords_x]."/".$ships[$i][coords_y]." (".$ships[$i][wese].")</td>
			<td class=tdmainobg align=center>".$ships[$i][huelle]."</td>
			<td class=tdmainobg align=center>".$ships[$i][energie]."</td>
			<td class=tdmainobg align=center>".$ships[$i][schilde]."</td>
			<td class=tdmainobg align=Center>".$ships[$i][crew]."</td>
			<td class=tdmainobg align=Center>".$ships[$i][kss]."/".$ships[$i][lss]."/".$ships[$i][cloak]."</td>
		</tr>";
	}
	echo "</table>";
} elseif ($section == "shipmoddb")
{
	$mods1 = $myColony->getmodulebytype(1);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><strong>Schiffsmodule</strong></td>
	</tr>
	</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain align=center colspan=6>Hüllenmodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Hülle</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonder</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		for ($j=0;$j<count($modcost);$j++) $mods1[$i][besonder] .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][huell]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(3);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=6>Schildmodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Schilde</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonder</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		for ($j=0;$j<count($modcost);$j++) $mods1[$i][besonder] .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][shields]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(6);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=6>EPS-Module</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>EPS</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonder</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		for ($j=0;$j<count($modcost);$j++) $mods1[$i][besonder] .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][eps]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(2);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=7>Computermodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Treffer</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Ausweich</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonder</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		for ($j=0;$j<count($modcost);$j++) $mods1[$i][besonder] .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][phaser_chance]."%</td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][torp_evade]."%</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(5);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=7>Antriebsmodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Treffer</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Ausweich</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonder</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		for ($j=0;$j<count($modcost);$j++) $mods1[$i][besonder] .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][phaser_chance]."%</td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][torp_evade]."%</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(7);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=6>Sensormodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>LSS</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonder</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		for ($j=0;$j<count($modcost);$j++) $mods1[$i][besonder] .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][lss_range]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(8);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=6>Reaktormodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Reaktor</strong></td>
		<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonder</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		for ($j=0;$j<count($modcost);$j++) $mods1[$i][besonder] .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][reaktor]."</td>
			<td class=tdmainobg width=11% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=12% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	$mods1 = $myColony->getmodulebytype(4);
	echo "</table><br><table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=7>Waffenmodule</td>
	</tr>
	<tr>
		<td class=tdmainobg width=30%><strong>Name</strong></td>
		<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Schaden</strong></td>
		<td class=tdmainobg width=8% align=center><strong>Treffer</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Punkte</strong></td>
		<td class=tdmainobg width=9% align=center><strong>Bauzeit</strong></td>
		<td class=tdmainobg width=30% align=center><strong>Besonder</strong></td>
	</tr>";
	for ($i=0;$i<count($mods1);$i++)
	{
		for ($j=0;$j<count($modcost);$j++) $mods1[$i][besonder] .= " ".$modcost[$j][count]."<img src=".$grafik."/goods/".$modcost[$j][goods_id].".gif alt='".$modcost[$j][name]."'>";
		echo "<tr>
			<td class=tdmainobg width=30%>".$mods1[$i][name]."</td>
			<td class=tdmainobg width=6% align=center><img src=".$grafik."/goods/".$mods1[$i][goods_id].".gif></td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][phaser]."</td>
			<td class=tdmainobg width=8% align=center>".$mods1[$i][phaser_chance]."%</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][wirt]."</td>
			<td class=tdmainobg width=9% align=center>".$mods1[$i][buildtime]."s</td>
			<td class=tdmainobg width=30%>".$mods1[$i][besonder]."</td>
		</tr>";
	}
	echo "</table>";
}
?>