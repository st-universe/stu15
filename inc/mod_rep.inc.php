<?php
function writemoduleoption($modId)
{
		global $myColony, $grafik;
		$modcost = $myColony->getmodulecostbyid($modId);
		$modinfo = $myColony->getModuleById($modId);
		$modoption = "<tr><td class=tdmainobg colspan=2 align=center>".$modinfo[name]."</td></tr>
		<tr><td class=tdmainobg><input type=radio name=end value=".$modinfo[goods_id]."> ".$modinfo[ecost]."<img src=".$grafik."/buttons/e_trans2.gif title='Energie'>";
		for ($i=0;$i<count($modcost);$i++) $modoption .= " ".$modcost[$i][count]."<img src=".$grafik."/goods/".$modcost[$i][goods_id].".gif title='".$modcost[$i][name]."'>";
		$modoption .= "<td class=tdmainobg><input type=text size=2 name=count[".$modinfo[goods_id]."] class=text>&nbsp;<img src=".$grafik."/goods/".$modinfo[goods_id].".gif  title='".$modinfo[name]."'></td></tr>";
		return $modoption;
}


if (($fieldData[build][id] >= 107) && ($fieldData[build][id] <= 113))
{
	$replikator = "<form action=main.php mehtod=post name=mod>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=fabrik>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=buildid value=".$fieldData[build][id].">
	<table width=350 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td colspan=2 class=tdmain align=center><strong>Waffen-Produktion</strong></td>
	</tr>";


	$mods = $myColony->getmodbytype(4);
	for ($i=0;$i<count($mods);$i++)
	{
		$replikator .= writemoduleoption($mods[$i][id]);
	}

	$replikator .= "<tr>
		<td colspan=2 align=center class=tdmain><input type=submit value=Herstellen class=button name=send onClick=\"this.disabled=true;document.mod.submit()\"></td>
	</tr>
	</table></form>";
}
if ($fieldData[build][id] >= 114 && $fieldData[build][id] <= 120)
{
	$replikator = "<form action=main.php mehtod=post name=mod>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=fabrik>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=buildid value=".$fieldData[build][id].">
	<table width=350 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td colspan=2 class=tdmain align=center><strong>Hüllen/Schild-Produktion</strong></td>
	</tr>";


	$mods = $myColony->getmodbytype(1);
	for ($i=0;$i<count($mods);$i++)
	{
		$replikator .= writemoduleoption($mods[$i][id]);
	}
	$mods = $myColony->getmodbytype(3);
	for ($i=0;$i<count($mods);$i++)
	{
		$replikator .= writemoduleoption($mods[$i][id]);
	}

	$replikator .= "<tr>
		<td colspan=2 align=center class=tdmain><input type=submit value=Herstellen class=button name=send onClick=\"this.disabled=true;document.mod.submit()\"></td>
	</tr>
	</table></form>";
}
if (($fieldData[build][id] >= 121) && ($fieldData[build][id] <= 127))
{
	$replikator = "<form action=main.php mehtod=post name=mod>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=fabrik>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=buildid value=".$fieldData[build][id].">
	<table width=350 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td colspan=2 class=tdmain align=center><strong>Reaktor/EPS-Produktion</strong></td>
	</tr>";


	$mods = $myColony->getmodbytype(8);
	for ($i=0;$i<count($mods);$i++)
	{
		$replikator .= writemoduleoption($mods[$i][id]);
	}
	$mods = $myColony->getmodbytype(6);
	for ($i=0;$i<count($mods);$i++)
	{
		$replikator .= writemoduleoption($mods[$i][id]);
	}

	$replikator .= "<tr>
		<td colspan=2 align=center class=tdmain><input type=submit value=Herstellen class=button name=send onClick=\"this.disabled=true;document.mod.submit()\"></td>
	</tr>
	</table></form>";
}
if (($fieldData[build][id] >= 128) && ($fieldData[build][id] <= 134))
{
	$replikator = "<form action=main.php mehtod=post name=mod>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=fabrik>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=buildid value=".$fieldData[build][id].">
	<table width=350 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td colspan=2 class=tdmain align=center><strong>Reaktor/EPS-Produktion</strong></td>
	</tr>";


	$mods = $myColony->getmodbytype(2);
	for ($i=0;$i<count($mods);$i++)
	{
		$replikator .= writemoduleoption($mods[$i][id]);
	}
	$mods = $myColony->getmodbytype(5);
	for ($i=0;$i<count($mods);$i++)
	{
		$replikator .= writemoduleoption($mods[$i][id]);
	}
	$mods = $myColony->getmodbytype(7);
	for ($i=0;$i<count($mods);$i++)
	{
		$replikator .= writemoduleoption($mods[$i][id]);
	}

	$replikator .= "<tr>
		<td colspan=2 align=center class=tdmain><input type=submit value=Herstellen class=button name=send onClick=\"this.disabled=true;document.mod.submit()\"></td>
	</tr>
	</table></form>";
}
?>