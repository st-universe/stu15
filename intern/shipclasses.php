<?php
if (!$section || ($section == "overview")) {
	if (($action == "editclass") && $classid) $result = $myShip->editclass($classid,$name,$huelle,$energie,$max_batt,$shields,$motor,$bussard,$erz,$phaser,$phaser_multi,$crew,$cloak,$slots,$lss_range,$storage,$view);
	if ($action == "addclass") $result = $myShip->addclass($id,$name,$huelle,$energie,$max_batt,$shields,$motor,$bussard,$erz,$phaser,$phaser_multi,$crew,$cloak,$slots,$lss_range,$storage,$view,$eps_cost,$tri,$kel,$nit,$iso,$pla,$gel,$sort,$usort,$torps);
	if ($result) echo "<table><tr><td class=tdmain>".$result[msg]."</td></tr></table>";
	echo "<table width=100%>
	<tr>
		<td class=tdmaintop colspan=5 align=center width=100%>Schiffsklassen</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<table>
	<table width=90% bgcolor=#262323>
	<tr>
		<td class=tdmain colspan=7 align=center><a href=?page=shipclasses&section=addclass>Schiffsklasse einfügen</a></td>
	</tr>";
	$classes = $myShip->getclasses();
	for ($i=0;$i<count($classes);$i=$i+2) {
		echo "<tr>
			<td class=tdmainobg><img src=http://home.arcor.de/omega-sektion/stu/ships/".$classes[$i][id].".gif></td>
			<td class=tdmainobg>".$classes[$i][name]."</td>
			<td class=tdmainobg><a href=index.php?page=shipclasses&section=edit&classid=".$classes[$i][id].">editieren</a></td>
			<td class=tdmain>&nbsp;</td>";
			if ($classes[$i+1][id] > 0) echo "<td class=tdmainobg><img src=http://home.arcor.de/omega-sektion/stu/ships/".$classes[$i+1][id].".gif></td>
			<td class=tdmainobg>".$classes[$i+1][name]."</td>
			<td class=tdmainobg><a href=index.php?page=shipclasses&section=edit&classid=".$classes[$i+1][id].">editieren</a></td>";
			else echo "<td class=tdmain></td><td class=tdmain></td><td class=tdmain></td>";
		echo "</tr>";
	}
	echo "</table>";
} elseif ($section == "edit") {
	$class = $myShip->getclassbyid($classid);
	echo "<table width=100%>
	<form action=index.php method=post>
	<input type=hidden name=page value=shipclasses>
	<input type=hidden name=section value=overview>
	<input type=hidden name=action value=editclass>
	<input type=hidden name=classid value=".$classid.">
	<tr>
		<td class=tdmaintop colspan=5 align=center width=100%>Schiffsklassen - Editieren</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class=tdmain><img src=../../gfx/ships/".$class[id].".gif> Klassen-ID: ".$class[id]."</td>
	</tr>
	<table>
	<table>
	<tr>
		<td class=tdmain>Name</td>
		<td class=tdmain><input type=text name=name value=\"".$class[name]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Hülle</td>
		<td class=tdmain><input type=text name=huelle value=\"".$class[huelle]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Energie</td>
		<td class=tdmain><input type=text name=energie value=\"".$class[energie]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Batterie</td>
		<td class=tdmain><input type=text name=max_batt value=\"".$class[max_batt]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Schilde</td>
		<td class=tdmain><input type=text name=shields value=\"".$class[shields]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Reaktorleistung</td>
		<td class=tdmain><input type=text name=motor value=\"".$class[motor]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Bussard Kollektoren</td>
		<td class=tdmain><input type=text name=bussard value=\"".$class[bussard]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Erz Kollektoren</td>
		<td class=tdmain><input type=text name=erz value=\"".$class[erz]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Phaserstärke</td>
		<td class=tdmain><input type=text name=phaser value=\"".$class[phaser]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Phaser Multi</td>
		<td class=tdmain><input type=text name=phaser_multi value=\"".$class[phaser_multi]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Crew</td>
		<td class=tdmain><input type=text name=crew value=\"".$class[crew]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Tarnung</td>
		<td class=tdmain><input type=text name=cloak value=\"".$class[cloak]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Dockplätze</td>
		<td class=tdmain><input type=text name=slots value=\"".$class[slots]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>LSS-Range</td>
		<td class=tdmain><input type=text name=lss_range value=\"".$class[lss_range]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Ladung</td>
		<td class=tdmain><input type=text name=storage value=\"".$class[storage]."\" class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Anzeige in Klassenliste?</td>
		<td class=tdmain><input type=text name=view value=\"".$class[view]."\" class=text></td>
	</tr>
	<tr>
		<td colspan=2 align=Center class=tdmain><input type=submit value=Editieren class=button> <input type=reset value=Zurücksetzen class=button></td>
	</tr>
	</form>
	</table>";
} elseif ($section == "addclass") {
	echo "<table width=100%>
	<form action=index.php method=post>
	<input type=hidden name=page value=shipclasses>
	<input type=hidden name=section value=overview>
	<input type=hidden name=action value=addclass>
	<tr>
		<td class=tdmaintop colspan=5 align=center width=100%>Schiffsklasse hinzufügen</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
	<table>
	<tr>
		<td class=tdmain>ID</td>
		<td class=tdmain><input type=text name=id class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Name</td>
		<td class=tdmain><input type=text name=name class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Hülle</td>
		<td class=tdmain><input type=text name=huelle class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Energie</td>
		<td class=tdmain><input type=text name=energie class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Batterie</td>
		<td class=tdmain><input type=text name=max_batt class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Schilde</td>
		<td class=tdmain><input type=text name=shields class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Reaktorleistung</td>
		<td class=tdmain><input type=text name=motor class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Bussard Kollektoren</td>
		<td class=tdmain><input type=text name=bussard class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Erz Kollektoren</td>
		<td class=tdmain><input type=text name=erz class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Phaserstärke</td>
		<td class=tdmain><input type=text name=phaser class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Phaser Multi</td>
		<td class=tdmain><input type=text name=phaser_multi class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Crew</td>
		<td class=tdmain><input type=text name=crew class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Tarnung</td>
		<td class=tdmain><input type=text name=cloak class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Dockplätze</td>
		<td class=tdmain><input type=text name=slots class=text></td>
	</tr>
	<tr>
		<td class=tdmain>LSS-Range</td>
		<td class=tdmain><input type=text name=lss_range class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Ladung</td>
		<td class=tdmain><input type=text name=storage class=text></td>
	</tr>
	<tr>
		<td class=tdmain>max Torpedos</td>
		<td class=tdmain><input type=text name=torps class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Anzeige in Klassenliste?</td>
		<td class=tdmain><input type=text name=view class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Sortierung in Klassenlist?</td>
		<td class=tdmain><input type=text name=sort class=text></td>
	</tr>
	<tr>
		<td class=tdmain>2. Sortierung in Klassenliste</td>
		<td class=tdmain><input type=text name=usort class=text></td>
	</tr>
	<tr>
		<td class=tdmain>Tritanium</td>
		<td class=tdmain><input type=checkbox name=tri></td>
	</tr>
	<tr>
		<td class=tdmain>Kelbonit</td>
		<td class=tdmain><input type=checkbox name=kel></td>
	</tr>
	<tr>
		<td class=tdmain>Nitrium</td>
		<td class=tdmain><input type=checkbox name=nit></td>
	</tr>
	<tr>
		<td class=tdmain>Iso-Chips</td>
		<td class=tdmain><input type=checkbox name=iso></td>
	</tr>
	<tr>
		<td class=tdmain>Plasma</td>
		<td class=tdmain><input type=checkbox name=pla></td>
	</tr>
	<tr>
		<td class=tdmain>Biomimetisches Gel</td>
		<td class=tdmain><input type=checkbox name=gel></td>
	</tr>
	<tr>
		<td colspan=2 align=Center class=tdmain><input type=submit value=Hinzufügen class=button> <input type=reset value=Zurücksetzen class=button></td>
	</tr>
	</form>
	</table>";
}
?>