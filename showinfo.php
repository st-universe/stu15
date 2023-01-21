<?php
if ($section == "building")
{
	$build = $myColony->getbuildbyid($id);
	if (($build[view] == 0 && $user > 100) || $build == 0) exit;
	if (($build[id] == 171)  && ($myColony->getuserresearch(223,$user) == 0)) exit;
	if (($build[id] == 172)  && ($myColony->getuserresearch(224,$user) == 0)) exit;
	if (($build[id] == 173)  && ($myColony->getuserresearch(225,$user) == 0)) exit;
	if (($build[id] == 175)  && ($myColony->getuserresearch(229,$user) == 0)) exit;
	if (($build[id] == 174)  && ($myColony->getuserresearch(243,$user) == 0)) exit;
	if (($build[id] == 197)  && ($myColony->getuserresearch(241,$user) == 0)) exit;
	if (($id == 16) || ($id == 15)  || ($id == 81)  || ($id == 89) || ($id == 78)) $field = 1;
	elseif ($id == 39) $field = 15;
	elseif ($id == 135) $field = 12;
	elseif (($id >= 108) && ($id <= 112)) $field = 1;
	elseif (($id >= 115) && ($id <= 119)) $field = 1;
	elseif (($id >= 122) && ($id <= 126)) $field = 1;
	elseif (($id >= 129) && ($id <= 133)) $field = 1;
	elseif ($id == 168) $field = 1;
	elseif (($id > 26) && ($id < 31)) $field = 12;
	elseif (($id > 62) && ($id < 67)) $field = 1;
	else $field = $myDB->query("SELECT type FROM stu_field_build WHERE buildings_id='".$id."' LIMIT 1",1);
	$timem = floor($build[buildtime]/60);
	$times = $build[buildtime]-($timem*60);
	$time = $timem."m ".$times."s";
	include_once("inc/buildcost.inc.php");
	$cost = getbuildingcostbyid($id);
	$goods = $myColony->getbuildinggoodsbyid($id);
	if ($build[id] == 80) $build[eps_pro] = $myColony->getgravenergy($col);
	if ($field < 43 && $build[id] != 207) $bildchen = $grafik."/buildings/".$id."_".$field.".gif";
	else $bildchen = $grafik."/buildings/n/".$id."_".$field.".gif";
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=center>".$build[name]."</td>
	</tr>
	<tr>
		<td class=tdmainobg align=Center><img src=".$bildchen."></td>
	</tr>
	<tr>
		<td class=tdmainobg><strong>Baukosten</strong><br>
		<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'> ".$build[eps_cost]."<br>";
		for ($i=0;$i<count($cost);$i++) echo "<img src=".$grafik."/goods/".$cost[$i][goods_id].".gif title='".htmlspecialchars($cost[$i][name])."'> ".$cost[$i]['count']."<br>";
		if ($build[bev_use] > 0) echo "<img src=".$grafik."/bev_used_1_".$myUser->urasse.".gif alt='Arbeiter'> ".$build[bev_use];
		echo "</td>
	</tr>
	<tr>
		<td class=tdmainobg><strong>+/-</strong><br>";
		if ($build[id] == 26) echo "Schiffbau";
		if ($build[id] == 169) echo "Sondenbau";
		if ($build[id] == 10) echo "+1 Sympathie";
		if ($build[id] == 206) echo "+3 Sympathie";
		if ($build[eps] > 0) echo "<img src=".$grafik."/buttons/eps.gif alt='Energiespeicher'> +".$build[eps]."<br>";
		if ($build[eps_min] > 0) echo "<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'> -".$build[eps_min]."<br>";
		if ($build[eps_pro] > 0) echo "<img src=".$grafik."/buttons/e_trans2.gif alt='Energie'> +".$build[eps_pro]."<br>";
		if ($build[lager] > 0) echo "<img src=".$grafik."/buttons/lager.gif alt='Lagerraum'> +".$build[lager]."<br>";
		if ($build[bev_pro] > 0) echo "<img src=".$grafik."/buttons/crew.gif alt='Wohnraum'> +".$build[bev_pro]."<br>";
		if ($goods != 0)
		{
			for ($i=0;$i<count($goods);$i++)
			{
				if ($goods[$i][mode] == 1) $m = "+";
				elseif ($goods[$i][mode] == 2) $m = "-";
				echo "<img src=".$grafik."/goods/".$goods[$i][goods_id].".gif title='".$goods[$i][name]."'> ".$m.$goods[$i]['count']."<br>";
			}
		}
		echo "</td>
	</tr>
	<tr>
		<td class=tdmainobg><img src=".$grafik."/buttons/time.gif title='Bauzeit'> ".$time."</td>
	</tr>
	<tr>
		<td class=tdmainobg><img src=".$grafik."/buttons/points.gif title='Wirtschaftspunkte'> ";
		$build[points] == 0 ? print(0) : print($build[points]);
	echo "</td>
	</tr>
	<tr>
		<td class=tdmain align=center>[<a href=static/leftbottom.php>OK</a>]</td>
	</tr>
	</table>";
}
elseif ($section == "terraform")
{
	$tf = $myColony->gettfbyid($id);
	if ($tf == 0) exit;
	$result = $myColony->gettfcost($id);
	if ($id == 16) $add = " <img src=".$grafik."/fields/17.gif> <img src=".$grafik."/fields/18.gif>";
	if ($id == 22 || $id == 23 || $id == 24)
	{
		$bilder = "<img src=".$grafik."/fields/n/".$tf[v_feld].".gif> => <img src=".$grafik."/fields/n/".$tf[z_feld].".gif>";
	}
	else
	{
		$bilder = "<img src=".$grafik."/fields/".$tf[v_feld].".gif> => <img src=".$grafik."/fields/".$tf[z_feld].".gif>";
	}
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=center><strong>Terraforming</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center>".$bilder."</td>
	</tr>
	<tr>
		<td class=tdmainobg><strong>Kosten</strong><br>
		<img src=".$grafik."/buttons/e_trans2.gif title='Energie'> ".$tf[ecost]."<br>";
		if ($tf[symp_min] > 0) echo $tf[symp_min]." Sympathie<br>";
		while($tc=mysql_fetch_assoc($result)) echo "<img src=".$grafik."/goods/".$tc[goods_id].".gif title='".htmlspecialchars($tc[name])."'> ".$tc['count']."<br>";
	echo "</td></tr>
	<tr>
		<td class=tdmain align=center>[<a href=static/leftbottom.php>OK</a>]</td>
	</tr></table>";
}
elseif ($section == "ship")
{
	$class = $myShip->getclassbyid($classid);
	if ($class == 0 || ($class[view] == 0 && $user > 100)) exit;
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=center><strong>".$class[name]."</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center><img src=".$grafik."/ships/".$classid.".gif></td>
	</tr>
	<tr>
		<td class=tdmainobg><strong>Kosten</strong><br>
		<img src=".$grafik."/buttons/e_trans2.gif> ".$class[eps_cost]."<br>";
		include_once("inc/shipcost.inc.php");
		$cost = getcostbyclass($classid);
		for ($i=0;$i<count($cost);$i++) echo "<img src=".$grafik."/goods/".$cost[$i][good][id].".gif title='".$cost[$i][good][name]."'> ".$cost[$i]['count']."<br>";
		echo "</td>
	</tr>
	<tr>
		<td class=tdmain align=center>[<a href=static/leftbottom.php>OK</a>]</td>
	</tr>
	</table>";
}
elseif ($section == "showstorage")
{
	if (!$id || !is_numeric($id)) exit;
	$ship = $myDB->query("SELECT name FROM stu_ships WHERE id=".$id." AND user_id=".$user,1);
	if (is_numeric($ship)) exit;
	$result = $myShip->getshipstorage($id);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=Center><strong>Ladung der ".stripslashes($ship)."</strong></td>
	</tr>";
	if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg>Keine Ladung vorhanden</td></tr>";
	else
	{
		echo "<tr><td class=tdmainobg>";
		while($data=mysql_fetch_assoc($result)) echo "<img src=".$grafik."/goods/".$data[goods_id].".gif title='".$data[name]."'> ".$data['count']."<br>";
		echo "</td></tr>";
	}
	echo "<tr>
		<td class=tdmain align=center>[<a href=static/leftbottom.php>OK</a>]</td>
	</tr>
	</table>";
}
?>

