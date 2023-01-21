<BODY bgcolor="#000000" text="#FFFFFF">
<?php
function writeblueprintoption($num)
{
	global $myShip,$grafik,$sys1,$sys2,$sys3,$sys4,$sys5,$sys6,$sys7,$sys8,$sys9,$sys10,$rump,$name;
	echo "<tr><td class=tdmainobg width=10><input type=radio name=number value=".$num."></td>
	<td class=tdmainobg width=100><img src=".$grafik."/ships/".$rump.".gif></td>
	<td class=tdmainobg width=*>".$name."</td>";
	echo "<input type=hidden name=id[".$num."] value=".$rump.">";
	echo "<input type=hidden name=huell[".$num."] value=".$sys1.">";
	$module = $myShip->getmodulebyid($sys1);
	$mods = "<img src=".$grafik."/goods/".$module[goods_id].".gif> ";
	echo "<input type=hidden name=computer[".$num."] value=".$sys2.">";
	$module = $myShip->getmodulebyid($sys2);
	$mods .= "<img src=".$grafik."/goods/".$module[goods_id].".gif> ";
	echo "<input type=hidden name=schild[".$num."] value=".$sys3.">";
	$module = $myShip->getmodulebyid($sys3);
	$mods .= "<img src=".$grafik."/goods/".$module[goods_id].".gif> ";
	echo "<input type=hidden name=waffen[".$num."] value=".$sys4.">";
	if ($sys4 > 0) { $module = $myShip->getmodulebyid($sys4);
	$mods .= "<img src=".$grafik."/goods/".$module[goods_id].".gif> ";
	}
	echo "<input type=hidden name=antrieb[".$num."] value=".$sys5.">";
	if ($sys5 > 0) { $module = $myShip->getmodulebyid($sys5);
	$mods .= "<img src=".$grafik."/goods/".$module[goods_id].".gif> ";
	}
	echo "<input type=hidden name=eps[".$num."] value=".$sys6.">";
	$module = $myShip->getmodulebyid($sys6);
	$mods .= "<img src=".$grafik."/goods/".$module[goods_id].".gif> ";
	echo "<input type=hidden name=sensor[".$num."] value=".$sys7.">";
	$module = $myShip->getmodulebyid($sys7);
	$mods .= "<img src=".$grafik."/goods/".$module[goods_id].".gif> ";
	echo "<input type=hidden name=reaktor[".$num."] value=".$sys8.">";
	$module = $myShip->getmodulebyid($sys8);
	$mods .= "<img src=".$grafik."/goods/".$module[goods_id].".gif> ";
	echo "<input type=hidden name=torp[".$num."] value=".$sys9.">";
	if ($sys9 > 0) $mods .= "<img src=".$grafik."/goods/".$sys9.".gif> ";
	echo "<input type=hidden name=torpc[".$num."] value=".$sys10.">
	<td class=tdmainobg width=250>".$mods."</td>";
	echo "</tr>";
}
if ($myUser->ustatus != 9 || !$myUser) $section = "error";
if (!$section || ($section == "main"))
{
	if ($id && ($action == "addship")) $return = $myShip->addnpcship($id,$coords_x,$coords_y,$huell,$sensor,$schild,$eps,$antrieb,$waffen,$reaktor,$computer,$wese);
	if ($number && ($action == "addblueprintship")) $return = $myShip->addnpcblueprintship($id[$number],$coords_x,$coords_y,$huell[$number],$sensor[$number],$schild[$number],$eps[$number],$antrieb[$number],$waffen[$number],$reaktor[$number],$computer[$number],$wese,$torp[$number],$torpc[$number]);

	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
		<td width=75% class=tdmain align=center><strong>NPC-Menü</strong></td>
	</tr>";
	if ($return) echo "<tr><td class=tdmainobg align=center>".$return[msg]."</td></tr>";
	if ($myUser->uhalfnpc != 1) 
	{
		echo "<form action=main.php method=post>
		<input type=hidden name=page value=npc>
		<input type=hidden name=action value=selectm>
		<tr>
			<td class=tdmainobg>Schiff erstellen: <select name=id>";
		$ships = $myShip->getnpcships();
		for ($i=0;$i<count($ships);$i++)
		{
			echo "<option value=".$ships[$i][id].">".$ships[$i][name]."</option>";
			if ($ships[$i]['sort'] != $ships[$i+1]['sort']) echo "<option value=0>----------------</option>";
		}
		echo "</select> Koords: <input type=text size=3 name=coords_x class=text> / <input type=text size=3 name=coords_y class=text> - Sektor: <input type=text size=1 name=wese class=text> <input type=submit value=Ausrüsten class=button></td><td class=tdmainobg align=right><a href=main.php?page=npc&section=shipclassesdb>Schiffsklassen Datenbank</a></td>
		</tr><tr><td class=tdmainobg></td><td class=tdmainobg align=right><a href=main.php?page=npc&section=buildingsdb&user=".$user."&pass=".$pass.">Gebäude Datenbank</a></td></tr></form></table>";

	}
	if ($myUser->uhalfnpc == 1) 
	{
		echo "<tr>
			<td class=tdmainobg></td>
		</tr></table>";

	}
	echo "<br><br><table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=center><strong>Blaupausen</strong></td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=npc>
	<input type=hidden name=action value=addblueprintship>
	<tr>
		<td class=tdmainobg>Koords: <input type=text size=3 name=coords_x class=text> / <input type=text size=3 name=coords_y class=text> - Sektor: <input type=text size=1 name=wese class=text></td>
	</tr></table>";
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>";

	if (($user == 27) || ($user == 29))
	{
		
		$sys1  = 3;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 104;		// Waffe
		$sys5  = 102;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 35;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 194;		// Schiffsklasse
		$name  = 'Ocelot';	// Name
		writeblueprintoption(1);
		
		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 104;		// Waffe
		$sys5  = 102;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 209;		// Warenid
		$sys10 = 20;		// Warenzahl
		$rump  = 190;		// Schiffsklasse
		$name  = 'Leopard';	// Name
		writeblueprintoption(2);

		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 108;		// Waffe
		$sys5  = 102;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 209;		// Warenid
		$sys10 = 40;		// Warenzahl
		$rump  = 196;		// Schiffsklasse
		$name  = 'Tiger';	// Name
		writeblueprintoption(3);

		$sys1  = 3;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 11;		// Schild
		$sys4  = 104;		// Waffe
		$sys5  = 102;		// Antrieb
		$sys6  = 31;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 215;		// Warenid
		$sys10 = 8;		// Warenzahl
		$rump  = 209;		// Schiffsklasse
		$name  = 'Caracal';	// Name
		writeblueprintoption(4);

		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 104;		// Waffe
		$sys5  = 102;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 308;		// Warenid
		$sys10 = 20;		// Warenzahl
		$rump  = 211;		// Schiffsklasse
		$name  = 'Kzinrett';	// Name
		writeblueprintoption(5);

	}
	if ($user == 12) 
	{
		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 107;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 29;		// Schiffsklasse
		$name  = 'BRel';	// Name
		writeblueprintoption(1);

		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 79;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 15;		// Schiffsklasse
		$name  = 'Vorcha';	// Name
		writeblueprintoption(2);

		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 79;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 16;
		$name  = 'neghvara';	// Name
		writeblueprintoption(3);
	}
	if ($user == 23) 
	{
		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 96;		// Waffe
		$sys5  = 98;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 17;		// Warenid
		$sys10 = 15;		// Warenzahl
		$rump  = 181;		// Schiffsklasse
		$name  = 'Dest';	// Name
		writeblueprintoption(1);

		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 97;		// Waffe
		$sys5  = 98;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 205;		// Warenid
		$sys10 = 25;		// Warenzahl
		$rump  = 182;		// Schiffsklasse
		$name  = 'BShip';	// Name
		writeblueprintoption(2);
	}
	if ($user == 11) 
	{
		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 66;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 41;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 20;		// Schiffsklasse
		$name  = 'Griffin';	// Name
		writeblueprintoption(1);

		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 66;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 41;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 19;		// Schiffsklasse
		$name  = 'DDeridex';	// Name
		writeblueprintoption(2);

		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 79;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 41;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 85;		// Schiffsklasse
		$name  = 'Norexan';	// Name
		writeblueprintoption(3);
	}
	if ($user == 13) 
	{

		$sys1  = 4;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 11;		// Schild
		$sys4  = 24;		// Waffe
		$sys5  = 27;		// Antrieb
		$sys6  = 31;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 17;		// Warenid
		$sys10 = 19;		// Warenzahl
		$rump  = 31;		// Schiffsklasse
		$name  = 'Sartan - veraltet';	// Name
		writeblueprintoption(10);

		$sys1  = 3;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 24;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 17;		// Warenid
		$sys10 = 19;		// Warenzahl
		$rump  = 31;		// Schiffsklasse
		$name  = 'Sartan';	// Name
		writeblueprintoption(11);

		$sys1  = 4;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 11;		// Schild
		$sys4  = 67;		// Waffe
		$sys5  = 27;		// Antrieb
		$sys6  = 31;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 17;		// Warenid
		$sys10 = 30;		// Warenzahl
		$rump  = 23;		// Schiffsklasse
		$name  = 'Brinok - veraltet';	// Name
		writeblueprintoption(12);

		$sys1  = 3;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 67;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 17;		// Warenid
		$sys10 = 30;		// Warenzahl
		$rump  = 23;		// Schiffsklasse
		$name  = 'Brinok';	// Name
		writeblueprintoption(13);

		$sys1  = 4;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 11;		// Schild
		$sys4  = 67;		// Waffe
		$sys5  = 27;		// Antrieb
		$sys6  = 31;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 203;		// Warenid
		$sys10 = 50;		// Warenzahl
		$rump  = 21;		// Schiffsklasse
		$name  = 'Galor - veraltet';	// Name
		writeblueprintoption(14);

		$sys1  = 3;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 67;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 203;		// Warenid
		$sys10 = 50;		// Warenzahl
		$rump  = 21;		// Schiffsklasse
		$name  = 'Galor';	// Name
		writeblueprintoption(15);

		$sys1  = 4;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 11;		// Schild
		$sys4  = 67;		// Waffe
		$sys5  = 27;		// Antrieb
		$sys6  = 31;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 203;		// Warenid
		$sys10 = 50;		// Warenzahl
		$rump  = 90;		// Schiffsklasse
		$name  = 'Keldon - veraltet';	// Name
		writeblueprintoption(16);

		$sys1  = 3;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 67;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 203;		// Warenid
		$sys10 = 50;		// Warenzahl
		$rump  = 90;		// Schiffsklasse
		$name  = 'Keldon';	// Name
		writeblueprintoption(17);
	}
	if ($user == 15) 
	{
		$sys1  = 5;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 43;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 50;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 17;		// Warenid
		$sys10 = 40;		// Warenzahl
		$rump  = 143;		// Schiffsklasse
		$name  = 'Breen Kriegsschiff';	// Name
		writeblueprintoption(6);

		$sys1  = 5;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 107;		// Waffe
		$sys5  = 0;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 50;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 189;		// Schiffsklasse
		$name  = 'Breen Waffenplattform';	// Name
		writeblueprintoption(7);

		$sys1  = 5;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 0;		// Waffe
		$sys5  = 0;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 50;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 153;		// Schiffsklasse
		$name  = 'Breen Sensorenphalanx';	// Name
		writeblueprintoption(8);

	}
	if ($user == 17) 
	{
		$sys1  = 54;		// Hülle
		$sys2  = 55;		// Comp
		$sys3  = 56;		// Schild
		$sys4  = 53;		// Waffe
		$sys5  = 57;		// Antrieb
		$sys6  = 58;		// EPS
		$sys7  = 52;		// Sensor
		$sys8  = 59;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 144;		// Schiffsklasse
		$name  = 'Unbekannte Lebensform';	// Name
		writeblueprintoption(1);
	}
	if ($user == 22) 
	{
		$sys1  = 85;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 86;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 87;		// Sensor
		$sys8  = 84;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 172;		// Schiffsklasse
		$name  = 'Zerstörer';	// Name
		writeblueprintoption(1);
	}
	if ($user == 18) 
	{
		$sys1  = 3;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 11;		// Schild
		$sys4  = 15;		// Waffe
		$sys5  = 71;		// Antrieb
		$sys6  = 31;		// EPS
		$sys7  = 35;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 40;		// Warenid
		$sys10 = 8;		// Warenzahl
		$rump  = 133;		// Schiffsklasse
		$name  = 'Bajoranischer Jäger';	// Name
		writeblueprintoption(1);

		$sys1  = 3;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 11;		// Schild
		$sys4  = 15;		// Waffe
		$sys5  = 71;		// Antrieb
		$sys6  = 31;		// EPS
		$sys7  = 35;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 40;		// Warenid
		$sys10 = 12;		// Warenzahl
		$rump  = 69;		// Schiffsklasse
		$name  = 'Bajoranischer Raider';	// Name
		writeblueprintoption(2);
	}
	if ($user == 19) 
	{
		$sys1  = 95;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 72;		// Schild
		$sys4  = 73;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 94;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 29;		// Warenid
		$sys10 = 25;		// Warenzahl
		$rump  = 150;		// Schiffsklasse
		$name  = 'Kessok Zerstörer';	// Name
		writeblueprintoption(1);

		$sys1  = 95;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 72;		// Schild
		$sys4  = 93;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 94;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 29;		// Warenid
		$sys10 = 50;		// Warenzahl
		$rump  = 180;		// Schiffsklasse
		$name  = 'Kessok Kriegsschiff';	// Name
		writeblueprintoption(2);

		$sys1  = 95;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 11;		// Schild
		$sys4  = 0;		// Waffe
		$sys5  = 0;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 33;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 29;		// Warenid
		$sys10 = 50;		// Warenzahl
		$rump  = 210;		// Schiffsklasse
		$name  = 'Kessok Torpedogeschütz';	// Name
		writeblueprintoption(3);
	}
	if ($user == 26) 
	{
		$sys1  = 3;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 16;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 198;		// Schiffsklasse
		$name  = 'Yridianischer Zerstörer';	// Name
		writeblueprintoption(5);

		$sys1  = 3;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 49;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 132;		// Schiffsklasse
		$name  = 'Nausicaanischer Zerstörer';	// Name
		writeblueprintoption(6);

		$sys1  = 3;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 69;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 135;		// Schiffsklasse
		$name  = 'Nausicaanisches Kriegsschiff';	// Name
		writeblueprintoption(7);
	}
	if ($user == 28) 
	{
		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 107;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 29;		// Schiffsklasse
		$name  = 'BRel';	// Name
		writeblueprintoption(1);

		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 79;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 40;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 15;		// Schiffsklasse
		$name  = 'Vorcha';	// Name
		writeblueprintoption(2);
	}
	if ($user == 10) 
	{
		$sys1  = 80;		// Hülle
		$sys2  = 8;		// Comp
		$sys3  = 81;		// Schild
		$sys4  = 65;		// Waffe
		$sys5  = 28;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 36;		// Sensor
		$sys8  = 41;		// Reaktor
		$sys9  = 202;		// Warenid
		$sys10 = 50;		// Warenzahl
		$rump  = 12;		// Schiffsklasse
		$name  = 'Akira';	// Name
		writeblueprintoption(1);
	}
	if ($user == 30) 
	{
		$sys1  = 3;		// Hülle
		$sys2  = 7;		// Comp
		$sys3  = 12;		// Schild
		$sys4  = 61;		// Waffe
		$sys5  = 27;		// Antrieb
		$sys6  = 32;		// EPS
		$sys7  = 35;		// Sensor
		$sys8  = 39;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 213;		// Schiffsklasse
		$name  = 'Karemma';	// Name
		writeblueprintoption(1);
	}
	if ($user == 24) 
	{
		$sys1  = 1;		// Hülle
		$sys2  = 6;		// Comp
		$sys3  = 9;		// Schild
		$sys4  = 45;		// Waffe
		$sys5  = 25;		// Antrieb
		$sys6  = 29;		// EPS
		$sys7  = 33;		// Sensor
		$sys8  = 0;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 193;		// Schiffsklasse
		$name  = 'Transporter';	// Name
		writeblueprintoption(1);

		$sys1  = 2;		// Hülle
		$sys2  = 44;		// Comp
		$sys3  = 10;		// Schild
		$sys4  = 45;		// Waffe
		$sys5  = 26;		// Antrieb
		$sys6  = 30;		// EPS
		$sys7  = 34;		// Sensor
		$sys8  = 37;		// Reaktor
		$sys9  = 41;		// Warenid
		$sys10 = 50;		// Warenzahl
		$rump  = 200;		// Schiffsklasse
		$name  = 'Fregatte';	// Name
		writeblueprintoption(2);

		$sys1  = 2;		// Hülle
		$sys2  = 44;		// Comp
		$sys3  = 10;		// Schild
		$sys4  = 45;		// Waffe
		$sys5  = 26;		// Antrieb
		$sys6  = 30;		// EPS
		$sys7  = 34;		// Sensor
		$sys8  = 37;		// Reaktor
		$sys9  = 0;		// Warenid
		$sys10 = 0;		// Warenzahl
		$rump  = 214;		// Schiffsklasse
		$name  = 'Aufklärer';	// Name
		writeblueprintoption(3);
	}
	echo "</table><br><input type=submit value=Erstellen class=button></form>";
	if ($action == "selectm")
	{
		$class = $myShip->getclassbyid($id);
		echo "<br><br><form action=main.php method=post>
		<input type=hidden name=page value=npc>
		<input type=hidden name=action value=addship>
		<input type=hidden name=coords_x value=".$coords_x.">
		<input type=hidden name=coords_y value=".$coords_y.">
		<input type=hidden name=wese value=".$wese.">
		<input type=hidden name=id value=".$id.">
		<table width=300 cellpadding=1 cellspacing=1 bgcolor=#262323>
		<tr>
			<td class=tdmain><strong>Schiff ausrüsten</strong></td>
		</tr>";
		$data = $myShip->getmodulesbytype(1,$user);
		if ($data != 0)
		{
			echo "<tr><td class=tdmain>Hüllenmodul</td></tr>";
			for ($i=0;$i<count($data);$i++)
			{
				if ($class[huellmod_max] < $data[$i][lvl]) $dis = " disabled";
				else unset($dis);
				echo "<tr>
					<td class=tdmainobg><input type=radio name=huell value=".$data[$i][id].$dis."> ".$data[$i][name]."</td>
				</tr>";
			}
		}
		$data = $myShip->getmodulesbytype(2,$user);
		if ($data != 0)
		{
			echo "<tr><td class=tdmain>Computermodul</td></tr>";
			for ($i=0;$i<count($data);$i++)
			{
				if ($class[computermod_max] < $data[$i][lvl]) $dis = " disabled";
				else unset($dis);
				echo "<tr>
					<td class=tdmainobg><input type=radio name=computer value=".$data[$i][id].$dis."> ".$data[$i][name]."</td>
				</tr>";
			}
		}
		$data = $myShip->getmodulesbytype(3,$user);
		if ($data != 0)
		{
			echo "<tr><td class=tdmain>Schildmodul</td></tr>";
			for ($i=0;$i<count($data);$i++)
			{
				if ($class[schildmod_max] < $data[$i][lvl]) $dis = " disabled";
				else unset($dis);
				echo "<tr>
					<td class=tdmainobg><input type=radio name=schild value=".$data[$i][id].$dis."> ".$data[$i][name]."</td>
				</tr>";
			}
		}
		$data = $myShip->getmodulesbytype(4,$user);
		if ($data != 0)
		{
			echo "<tr><td class=tdmain>Waffenmodul</td></tr>";
			for ($i=0;$i<count($data);$i++)
			{
				if ($class[waffenmod_max] < $data[$i][lvl]) $dis = " disabled";
				else unset($dis);
				echo "<tr>
					<td class=tdmainobg><input type=radio name=waffen value=".$data[$i][id].$dis."> ".$data[$i][name]."</td>
				</tr>";
			}
		}
		$data = $myShip->getmodulesbytype(5,$user);
		if ($data != 0)
		{
			echo "<tr><td class=tdmain>Antriebsmodul</td></tr>";
			for ($i=0;$i<count($data);$i++)
			{
				if ($class[antriebsmod_max] < $data[$i][lvl]) $dis = " disabled";
				else unset($dis);
				echo "<tr>
					<td class=tdmainobg><input type=radio name=antrieb value=".$data[$i][id].$dis."> ".$data[$i][name]."</td>
				</tr>";
			}
		}
		$data = $myShip->getmodulesbytype(6,$user);
		if ($data != 0)
		{
			echo "<tr><td class=tdmain>EPS-Gittermodul</td></tr>";
			for ($i=0;$i<count($data);$i++)
			{
				if ($class[epsmod_max] < $data[$i][lvl]) $dis = " disabled";
				else unset($dis);
				echo "<tr>
					<td class=tdmainobg><input type=radio name=eps value=".$data[$i][id].$dis."> ".$data[$i][name]."</td>
				</tr>";
			}
		}
		$data = $myShip->getmodulesbytype(7,$user);
		if ($data != 0)
		{
			echo "<tr><td class=tdmain>Sensorenmodul</td></tr>";
			for ($i=0;$i<count($data);$i++)
			{
				if ($class[sensormod_max] < $data[$i][lvl]) $dis = " disabled";
				else unset($dis);
				echo "<tr>
					<td class=tdmainobg><input type=radio name=sensor value=".$data[$i][id].$dis."> ".$data[$i][name]."</td>
				</tr>";
			}
		}
		$data = $myShip->getmodulesbytype(8,$user);
		if ($data != 0)
		{
			echo "<tr><td class=tdmain>Reaktormodul</td></tr>";
			for ($i=0;$i<count($data);$i++)
			{
				if ($class[reaktormod_max] < $data[$i][lvl]) $dis = " disabled";
				else unset($dis);
				echo "<tr>
					<td class=tdmainobg><input type=radio name=reaktor value=".$data[$i][id].$dis."> ".$data[$i][name]."</td>
				</tr>";
			}
		}
		echo "<tr>
			<td class=tdmainobg align=center><input type=submit value=Erstellen class=button></td>
		</tr>";
		echo "</table>";
	}
} elseif ($section == "error") {
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain align=center><strong>NPC-Menü</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg align=Center>Schutzverletzung!</td>
	</tr>
	</table>";
} elseif ($section == "buildingsdb") {
	echo "<table width=100% cellpadding=0 cellspacing=0>
	<tr>
		<td width=100% class=tdmain align=center><b>Gebäudeübersicht</b></td>
	</tr>
	</table>
	<br>
	<table width=400>";
	$buildings = $myDB->query("SELECT a.*,b.type FROM stu_buildings as a LEFT JOIN stu_field_build as b ON a.id=b.buildings_id WHERE a.view=1 GROUP BY a.id ORDER by a.level,a.name ASC",2);
	$bla = 3;
	for ($i=0;$i<count($buildings);$i++) {
		if ($i==0) echo "<tr>";
		if ($buildings[$i][id] == 16) $pic = "<img src=".$grafik."/buildings/16_1.gif>";
		elseif ($buildings[$i][id] == 15) $pic = "<img src=".$grafik."/buildings/15_1.gif>";
		elseif ($buildings[$i][id] == 81) $pic = "<img src=".$grafik."/buildings/81_1.gif>";
		elseif ($buildings[$i][id] == 39) $pic = "<img src=".$grafik."/buildings/39_15.gif>";
		elseif (($buildings[$i][id] > 26) && ($buildings[$i][id] < 31)) $pic = "<img src=".$grafik."/buildings/".$buildings[$i][id]."_12.gif>";
		elseif (($buildings[$i][id] > 62) && ($buildings[$i][id] < 67)) $pic = "<img src=".$grafik."/buildings/".$buildings[$i][id]."_1.gif>";
		elseif ($buildings[$i][id] == 78) $pic = "<img src=".$grafik."/buildings/78_1.gif>";
		elseif ($buildings[$i][id] == 89) $pic = "<img src=".$grafik."/buildings/89_1.gif>";
		elseif ($buildings[$i][id] == 71) $pic = "<img src=".$grafik."/buildings/71_12.gif>";
		else $pic = "<img src=".$grafik."/buildings/".$buildings[$i][id]."_".$buildings[$i][type].".gif>";
		if ($buildings[$i][id] != 39) {
			$goods = $myColony->getgoodsbybuilding($buildings[$i][id]);
			echo "<td class=tdmainobg width=200 height=330 valign=top><table bgcolor=#262323 height=330 width=200><tr><td class=tdmainobg height=330 width=200 valign=top><strong>".$buildings[$i][name]."</strong><br>
			 ".$pic."<br>&nbsp;<br>";
			$pf = $myColony->getfieldsbybuilding($buildings[$i][id]);
			if (mysql_num_rows($pf) != 0) while($p=mysql_fetch_assoc($pf)) if ($p[type] != 42) echo "<img src=".$grafik."/fields/".$p[type].".gif width=16 height=16 border=0> ";
			else echo " Upgrade ";
			 echo "<br>&nbsp;<br> Ab Level ".$buildings[$i][level]."<br>
			 <strong>Baukosten</strong><br>
			 <img src=".$grafik."/buttons/e_trans2.gif alt='Energie'> ".$buildings[$i][eps_cost]."<br>";
			 for ($j=0;$j<count($buildings[$i][cost]);$j++) {
			 	echo "<img src=".$grafik."/goods/".$buildings[$i][cost][$j][good][id].".gif alt='".$buildings[$i][cost][$j][good][name]."'> ".$buildings[$i][cost][$j]['count']."<br>";
			 }
			 echo "<br>+/-<br>";
			 if ($buildings[$i][lager] > 0) echo "<img src=".$grafik."/buttons/lager.gif>+".$buildings[$i][lager]."<br>";
			 if ($buildings[$i][eps_pro] > 0) echo "<img src=".$grafik."/buttons/e_trans2.gif>+".$buildings[$i][eps_pro]."<br>";
			 elseif ($buildings[$i][eps_min] > 0) echo "<img src=".$grafik."/buttons/e_trans2.gif>-".$buildings[$i][eps_min]."<br>";
			 
if ($buildings[$i][bev_pro] > 0) echo "<img src=".$grafik."/buttons/crew.gif>+".$buildings[$i][bev_pro]."<br>";
			 
if ($buildings[$i][bev_use] > 0) echo "<img src=".$grafik."/buttons/crew.gif>-".$buildings[$i][bev_use]."<br>";
			 
for ($j=0;$j<count($goods);$j++) {
			 	
if ($goods[$j][mode] == 1) echo "<img src=".$grafik."/goods/".$goods[$j][goods_id].".gif>+". $goods[$j]['count']."<br>";
			 	
elseif ($goods[$j][mode] == 2) echo "<img src=".$grafik."/goods/".$goods[$j][goods_id].".gif>-". $goods[$j]['count']."<br>";
			 }
if ($buildings[$i][schilde] > 0) echo "<img src=".$grafik."/buttons/shld.gif>+".$buildings[$i][schilde]."<br>";			 

if ($buildings[$i][points] > 0) echo "<img src=".$grafik."/buttons/points.gif>+".$buildings[$i][points]."<br>";


echo "</td></tr></table></td>";
		} 


else $bla = $bla+1;
		if ($i == $bla) {
			echo "</tr><tr>";
			$bla = $bla+4;
		}
	}
	echo "</table><br>&nbsp;<br><table width=100% cellpadding=0 cellspacing=0>
	<tr>
		<td width=100% class=tdmain align=center><b>NPC-Gebäude / nicht baubar</b></td>
	</tr>
	</table>
	<br>
	<table width=400>";
	$buildings = $myColony->getnpcbuildings();
	$bla = 3;
	for ($i=0;$i<count($buildings);$i++) {
		if ($i==0) echo "<tr>";
		if ($buildings[$i][id] == 16) $pic = "<img src=".$grafik."/buildings/16_1.gif>";
		elseif ($buildings[$i][id] == 15) $pic = "<img src=".$grafik."/buildings/15_1.gif>";
		elseif ($buildings[$i][id] == 81) $pic = "<img src=".$grafik."/buildings/81_1.gif>";
		elseif ($buildings[$i][id] == 39) $pic = "<img src=".$grafik."/buildings/39_15.gif>";
		elseif (($buildings[$i][id] > 26) && ($buildings[$i][id] < 31)) $pic = "<img src=".$grafik."/buildings/".$buildings[$i][id]."_12.gif>";
		elseif (($buildings[$i][id] > 62) && ($buildings[$i][id] < 67)) $pic = "<img src=".$grafik."/buildings/".$buildings[$i][id]."_1.gif>";
		elseif ($buildings[$i][id] == 78) $pic = "<img src=".$grafik."/buildings/78_1.gif>";
		elseif ($buildings[$i][id] == 89) $pic = "<img src=".$grafik."/buildings/89_1.gif>";
		elseif ($buildings[$i][id] == 71) $pic = "<img src=".$grafik."/buildings/71_12.gif>";
		else $pic = "<img src=".$grafik."/buildings/".$buildings[$i][id]."_".$buildings[$i][field].".gif>";
		if ($buildings[$i][id] != 39) {
			$goods = $myColony->getgoodsbybuilding($buildings[$i][id]);
			echo "<td class=tdmainobg width=200 height=330 valign=top><table bgcolor=#262323 height=330 width=200><tr><td class=tdmainobg height=330 width=200 valign=top><strong>".$buildings[$i][name]."</strong><br>
			".$pic."<br>&nbsp;<br>";
			$pf = $myColony->getfieldsbybuilding($buildings[$i][id]);
			if (mysql_num_rows($pf) != 0)
			{
				while($p=mysql_fetch_assoc($pf))
				{
			 		echo "<img src=".$grafik."/fields/".$p[type].".gif width=16 height=16 border=0> ";
				}
			 } else {
		 		echo " Upgrade ";
			 }	
			 echo "<br>&nbsp;<br> Ab Level ".$buildings[$i][level]."<br>
			 <strong>Baukosten</strong><br>
			 <img src=".$grafik."/buttons/e_trans2.gif alt='Energie'> ".$buildings[$i][eps_cost]."<br>";
			 for ($j=0;$j<count($buildings[$i][cost]);$j++) {
			 	echo "<img src=".$grafik."/goods/".$buildings[$i][cost][$j][good][id].".gif alt='".$buildings[$i][cost][$j][good][name]."'> ".$buildings[$i][cost][$j]['count']."<br>";
			 }
			 echo "<br>+/-<br>";
			 if ($buildings[$i][lager] > 0) echo "<img src=".$grafik."/buttons/lager.gif>+".$buildings[$i][lager]."<br>";
			 if ($buildings[$i][eps_pro] > 0) echo "<img src=".$grafik."/buttons/e_trans2.gif>+".$buildings[$i][eps_pro]."<br>";
			 elseif ($buildings[$i][eps_min] > 0) echo "<img src=".$grafik."/buttons/e_trans2.gif>-".$buildings[$i][eps_min]."<br>";
			 
if ($buildings[$i][bev_pro] > 0) echo "<img src=".$grafik."/buttons/crew.gif>+".$buildings[$i][bev_pro]."<br>";
			 
if ($buildings[$i][bev_use] > 0) echo "<img src=".$grafik."/buttons/crew.gif>-".$buildings[$i][bev_use]."<br>";
			 
for ($j=0;$j<count($goods);$j++) {
			 	
if ($goods[$j][mode] == 1) echo "<img src=".$grafik."/goods/".$goods[$j][goods_id].".gif>+". $goods[$j]['count']."<br>";
			 	
elseif ($goods[$j][mode] == 2) echo "<img src=".$grafik."/goods/".$goods[$j][goods_id].".gif>-". $goods[$j]['count']."<br>";
			 }
if ($buildings[$i][schilde] > 0) echo "<img src=".$grafik."/buttons/shld.gif>+".$buildings[$i][schilde]."<br>";			 

if ($buildings[$i][points] > 0) echo "<img src=".$grafik."/buttons/points.gif>+".$buildings[$i][points]."<br>";


echo "</td></tr></table></td>";
		} 


else $bla = $bla+1;
		if ($i == $bla) {
			echo "</tr><tr>";
			$bla = $bla+4;
		}
	}
} elseif ($section == "shipclassesdb") {
	$ships = $myShip->getnpcClasses();
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain align=center colspan=10><b>Datenbank -> Schiffsklassen</b></td>
	</tr>
	<tr>
		<td class=tdmainobg></td>
		<td class=tdmainobg><strong>Name</strong></td>
		<td class=tdmainobg align=center><strong>Crew</strong></td>
		<td class=tdmainobg align=center><strong>Ladung</strong></td>
		<td class=tdmainobg align=center></td></td>
	</tr>";
	for ($i=0;$i<count($ships);$i++) {
		echo "<tr>
			<td class=tdmainobg><img src=".$grafik."/ships/".$ships[$i][id].".gif></td>
			<td class=tdmainobg>".$ships[$i][name]."</td>
			<td class=tdmainobg align=center>".$ships[$i][crew_min]."/".$ships[$i][crew]."</td>
			<td class=tdmainobg align=center>".$ships[$i][storage]."</td>
			<td class=tdmainobg align=center>(<a href=?page=shiphelp&class=".$ships[$i][id]."&user=".$user."&pass=".$pass." target=leftbottom>?</a>)</td>
		</tr>";
	}
}
?>
</body>