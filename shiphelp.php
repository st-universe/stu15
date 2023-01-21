<?php
if ($section == "ship")
{
	$ship = $myShip->getDataById($shipid);
	$class = $myShip->getclassbyid($ship[ships_rumps_id]);
	if ($ship == 0) echo "Schiff existiert nicht";
	else
	{
		if ($ship[user_id] != $user) exit;
		echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain align=center><img src=".$grafik."/ships/".$class[id].".gif alt=\"".$class[name]."\"></td>
		</tr>
			<tr>
			<td class=tdmainobg>";
		$mod = $myShip->getmodulebyid($ship[huellmodlvl]);
		echo "<img src=".$grafik."/goods/".$mod[goods_id].".gif border=0> Hülle: ".$ship[huelle]."/".$ship[maxhuell];
		$mod = $myShip->getmodulebyid($ship[epsmodlvl]);
		echo "<br><img src=".$grafik."/goods/".$mod[goods_id].".gif border=0> Energie: ".$ship[energie]."/".$ship[maxeps];
		$mod = $myShip->getmodulebyid($ship[schildmodlvl]);
		echo "<br><img src=".$grafik."/goods/".$mod[goods_id].".gif border=0> Schilde: ".$ship[schilde]."/".$ship[maxshields];
		$mod = $myShip->getmodulebyid($ship[waffenmodlvl]);
		if ($ship[maxphaser] > 0) echo "<br><img src=".$grafik."/goods/".$mod[goods_id].".gif border=0> Schaden: ".$ship[maxphaser];
		$waffe = $myShip->getModuleById($ship[waffenmodlvl]);
		if ($waffe[besonder] == "Pulswaffe") echo " x3";
		if ($waffe[besonder] == "Energiedisruptor") echo "<br><img src=".$grafik."/goods/".$mod[goods_id].".gif border=0> Schaden:  <font color='#FFFF00'>E</font>";
		if ($ship[waffenmodlvl] != 0) echo "<br><img src=".$grafik."/buttons/gefecht.gif border=0> Treffer: ".$ship[maxtreffer]." %";
		if (($class[size] == 1) || ($class[size] == 2) || ($class[size] == 3)) $torpclass = $class[size];
		elseif ($class[size] > 3) $torpclass = 9;
		else $torpclass = 1;
		echo "<br><img src=".$grafik."/buttons/t_torp".$torpclass."1.gif border=0 title='Torpedokapazität'> ".$class[torps];
		if ($class[probe_stor] > 0) echo "<br><img src=".$grafik."/goods/35.gif title='Sondenkapazität'> ".$class[probe_stor];
		if ($ship[maxausweichen] > 0) echo "<br><img src=".$grafik."/buttons/t_torp12.gif border=0> T.-Ausw.: ".$ship[maxausweichen]." %";
		$mod = $myShip->getmodulebyid($ship[reaktormodlvl]);
		$ship[reaktormodlvl] != 0 ? print("<br><img src=".$grafik."/goods/".$mod[goods_id].".gif border=0> Reaktor: ".$ship[maxreaktor]) : print("<br><img src=".$grafik."/buttons/battp2.gif border=0> Reaktor: ".$ship[maxreaktor]);
		$mod = $myShip->getmodulebyid($ship[sensormodlvl]);
		echo "<br><img src=".$grafik."/goods/".$mod[goods_id].".gif border=0> LSS: ".$ship[maxlss];
		if ($class[bussard] > 0) echo "<br><img src=".$grafik."/buttons/buss1.gif border=0> Bussard: ".$class[bussard];
		if ($class[erz] > 0) echo "<br><img src=".$grafik."/buttons/erz1.gif border=0> Erz: ".$class[erz];
		if ($ship[sensormodlvl] == 101) echo "<font color=yellow>+</font>";
		if ($class[replikator] == 1) echo "<br><img src=".$grafik."/buttons/repli.gif border=0> Replikator";
		if ($class[cloak] == 1) echo "<br><img src=".$grafik."/buttons/tarnv.gif border=0> Tarnung";
		if ($class[slots] > 0) echo "<br><img src=".$grafik."/buttons/dock1.gif border=0> Dockplätze: ".$class[slots];
		echo "<br><img src=".$grafik."/buttons/points.gif border=0> Punkte: ".$ship[points];
		echo "</td></tr></table>";
	}
}
elseif ($section == "rump")
{
	include_once("inc/shipcost.inc.php");
	$cost = getcostbyclass($class);
	$class = $myShip->getclassbyid($class);
	if ($class[view] != 1 && $user > 100) exit;
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=center><img src=".$grafik."/ships/".$class[id].".gif alt=\"".$class[name]."\"></td>
	</tr>
	<tr>
		<td class=tdmainobg>
	max. Torps: ".$class[torps];
	if ($class[bussard] > 0) echo "<br>Bussard: ".$class[bussard];
	if ($class[erz] > 0) echo "<br>Erz: ".$class[erz];
	if ($class[probe_stor] != 0) echo "<br>Sonden: ".$class[probe_stor];
	if ($class[size] > 0) echo "<br>Torpedoklasse: ".$class[size];
	if ($class[warpcore] == 1) echo "<br>Warpkern";
	if ($class[replikator] == 1) echo "<br>Replikator";
	if ($class[cloak] == 1) echo "<br>Tarnung";
	if ($class[tachyon] == 1) echo "<br>Tachyon-Slot";
	if ($class[slots] > 0) echo "<br>Dockplätze: ".$class[slots];
	if ($class[torp_evade] > 0) echo "<br>Torp.-Ausw.: ".$class[torp_evade]." %";
		echo "<br>Punkte: ".$class[points]."<br><br>
	<img src=".$grafik."/buttons/e_trans2.gif> ".$class[eps_cost]."<br>";
	for ($i=0;$i<count($cost);$i++) if ($cost[$i][goods_id] > 0) echo "<img src=".$grafik."/goods/".$cost[$i][goods_id].".gif> ".$cost[$i]['count']."<br>";
	echo "</td></tr></table>";
}
echo "
<div id=\"navi\">
<ul><li>
<li><div align=center><a href=static/leftbottom.php>[OK]</a></div></li>
</ul></div>";

?>