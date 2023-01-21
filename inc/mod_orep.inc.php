<?php
if ($fieldData[build][id] == 169)
{
	if ($myColony->getuserresearch(212,$user) == 1)
	{
		$probecost = $myColony->getprobecostbylvl(1);
		$pro1 = "<input type=hidden name=endpro1 value=35>
		<tr><td class=tdmainobg colspan=2 align=center>Sonde Klasse 1</td></tr>
		<tr><td class=tdmainobg><input type=radio name=sonId value=1> 3 <img src=".$grafik."/buttons/e_trans2.gif title='Energie'>";
		for ($i=0;$i<count($probecost);$i++) $pro1 .= " ".$probecost[$i][count]."<img src=".$grafik."/goods/".$probecost[$i][goods_id].".gif title='".$probecost[$i][name]."'>";
		$pro1 .= "<td class=tdmainobg><input type=text size=2 name=count[1] class=text>&nbsp;<img src=".$grafik."/goods/35.gif  title='Sonde Klasse 1'></td></tr>";
	}
	if ($myColony->getuserresearch(213,$user) == 1)
	{
		$probecost = $myColony->getprobecostbylvl(2);
		$pro2 = "<input type=hidden name=endpro2 value=36>
		<tr><td class=tdmainobg colspan=2 align=center>Sonde Klasse 2</td></tr>
		<tr><td class=tdmainobg><input type=radio name=sonId value=2> 5 <img src=".$grafik."/buttons/e_trans2.gif title='Energie'>";
		for ($i=0;$i<count($probecost);$i++) $pro2 .= " ".$probecost[$i][count]."<img src=".$grafik."/goods/".$probecost[$i][goods_id].".gif title='".$probecost[$i][name]."'>";
		$pro2 .= "<td class=tdmainobg><input type=text size=2 name=count[2] class=text>&nbsp;<img src=".$grafik."/goods/36.gif  title='Sonde Klasse 2'></td></tr>";
	}
	if ($myColony->getuserresearch(214,$user) == 1)
	{
		$probecost = $myColony->getprobecostbylvl(3);
		$pro3 = "<input type=hidden name=endpro3 value=37>
		<tr><td class=tdmainobg colspan=2 align=center>Sonde Klasse 3</td></tr>
		<tr><td class=tdmainobg><input type=radio name=sonId value=3> 7 <img src=".$grafik."/buttons/e_trans2.gif title='Energie'>";
		for ($i=0;$i<count($probecost);$i++) $pro3 .= " ".$probecost[$i][count]."<img src=".$grafik."/goods/".$probecost[$i][goods_id].".gif title='".$probecost[$i][name]."'>";
		$pro3 .= "<td class=tdmainobg><input type=text size=2 name=count[3] class=text>&nbsp;<img src=".$grafik."/goods/37.gif  title='Sonde Klasse 3'></td></tr>";
	}
	if ($myColony->getuserresearch(230,$user) == 1)
	{
		$probecost = $myColony->getprobecostbylvl(4);
		$pro4 = "<input type=hidden name=endpro4 value=204>
		<tr><td class=tdmainobg colspan=2 align=center>Ionen-Sonde</td></tr>
		<tr><td class=tdmainobg><input type=radio name=sonId value=4> 10 <img src=".$grafik."/buttons/e_trans2.gif title='Energie'>";
		for ($i=0;$i<count($probecost);$i++) $pro4 .= " ".$probecost[$i][count]."<img src=".$grafik."/goods/".$probecost[$i][goods_id].".gif title='".$probecost[$i][name]."'>";
		$pro4 .= "<td class=tdmainobg><input type=text size=2 name=count[4] class=text>&nbsp;<img src=".$grafik."/goods/204.gif  title='Ionen-Sonde'></td></tr>";
	}
	$replikator = "<form action=main.php method=post name=mod>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=robotik>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=buildid value=".$fieldData[build][id].">
	<table width=350 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td colspan=2 class=tdmain align=center><strong>Sondenherstellung</strong></td>
	</tr>
	".$pro1."".$pro2."".$pro3."".$pro4."
	<tr>
		<td colspan=2 align=center class=tdmain><input type=submit value=Herstellen class=button name=send onClick=\"this.disabled=true;document.mod.submit()\"></td>
	</tr>
	</table></form>";
}
?>