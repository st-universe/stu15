<?php
if (($action == "joinally") && $id && $join_pass) $result = $myAlly->joinally($id,$join_pass);
if (!$section)
{
	$return = $myAlly->checkAlly($user);
	$return > 0 ? $section = "allyoptions" : $section = "overview";
}
if ($section == "overview")
{
	$res = $myAlly->getallylist();
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <strong>Allianzliste</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	if ($myUser->ually == 0) echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg><a href=main.php?page=ally&section=newally>Allianz gründen</a></td></tr></table><br>";
	echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg><strong>Name</strong></td>
		<td class=tdmainobg><strong>Präsident</strong></td>
		<td class=tdmainobg><strong>Mitglieder</strong></td>
		<td class=tdmainobg></td>
	</tr>";
	if (mysql_num_rows($res) == 0) echo "<tr><td colspan=4 align=center class=tdmainobg>Keine Allianzen vorhanden</td></tr>";
	else
	{
		while($allys=mysql_fetch_assoc($res))
		{
			echo "<tr>
				<td class=tdmainobg>".$allys[name]." (".$allys[id].")</td>
				<td class=tdmainobg>".stripslashes($allys[prae])."</td>
				<td class=tdmainobg>".mysql_num_rows($myAlly->getallymembers($allys[id]))."</td>
				<td class=tdmainobg><a href=main.php?page=ally&section=showally&id=".$allys[id].">Details</a></td>
			</tr>";
		}
	}
	echo "</table><br>";
}
elseif ($section == "allyoptions")
{
	if (!$return) $return = $id;
	$ally = $myAlly->getallybyid($return);
	$allymembers = $myAlly->getallymembers($return);
	$ally[vize] != 0 ? $vize = stripslashes($myUser->getfield("user",$ally[vize])) : $vize = "-";
	$ally[diplo] > 0 ? $diplo = stripslashes($myUser->getfield("user",$ally[diplo])) : $diplo = "-";
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <strong>".$ally[name]."</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table width=800>
	<tr>
		<td valign=top width=350>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
			<tr>
				<td class=tdmainobg width=30%>Präsident</td>
				<td class=tdmainobg>".stripslashes($myUser->getfield("user",$ally[user_id]))."</td>
			</tr>
			<tr>
				<td class=tdmainobg>Vize-Präsident</td>
				<td class=tdmainobg>".$vize."</td>
			</tr>
			<tr>
				<td class=tdmainobg>Außenminister</td>
				<td class=tdmainobg>".$diplo."</td>
			</tr>";
			if ($ally[hp] != "") echo "<tr><td class=tdmainobg colspan=2><a href='".stripslashes($ally[hp])."' target=_blank>Homepage</a></td></tr>";
			echo "</table><br>
			<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
			<tr>
				<td class=tdmainobg>".nl2br(stripslashes($ally[descr]))."</td>
			</tr>
			</table><br>
			<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
			<tr>
				<td class=tdmainobg><a href=main.php?page=ally&section=overview>Allianzliste</a>";
			if ($ally[diplo] == $user || $ally[user_id] == $user || $ally[vize] == $user) echo "<br><a href=main.php?page=ally&section=diplo&id=".$ally[id].">Diplomatie</a>";
			echo "<br><a href=main.php?page=ally&section=botschaften&id=".$ally[id].">Botschaften</a>";
			if ($ally[user_id] == $user || $ally[vize] == $user) echo "<br><a href=main.php?page=ally&section=options&id=".$ally[id].">Einstellungen</a>";
			echo "<br><a href=?page=ally&section=allybez>Beziehungen</a>
			<br><br><a href=main.php?page=ally&section=leaveally>Allianz verlassen</a></td>
			</tr>
		</table>
		</td>
		<td valign=top width=450>
			<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>";
			$gameData = $myGame->getcurrentround();
			while ($a=mysql_fetch_assoc($allymembers))
			{
				time() - 300 > $a[last_tsp] ? $status = "<img src=".$grafik."/buttons/alert3.gif>" : $status = "<img src=".$grafik."/buttons/alert1.gif>";
				echo "<tr><td class=tdmainobg><img src=".$grafik."/rassen/".$a[rasse]."s.gif> ".stripslashes($a[user]).($a[vac] == 1 ? " <font color=yellow>*</font>" : "");
				if (($ally[user_id] == $user || $ally[vize] == $user) && $a[id] != $user) echo " (".($gameData[runde]-$a[lastloginround]).")";
				echo "</td>
				<td class=tdmainobg width=60>".$status."
				<a href=main.php?page=comm&section=writepm&recipient=".$a[id]." onMouseOver=document.msg".$i.".src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg".$i.".src='".$grafik."/buttons/msg1.gif'><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0></a>";
				if ($ally[user_id] == $user && $a[id] != $user) echo " <a href=main.php?page=ally&section=delfromally&id=".$a[id]." onMouseOver=document.del".$i.".src='".$grafik."/buttons/x2.gif' onMouseOut=document.del".$i.".src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del".$i." border=0></a> ";
				echo "</td></tr>";
			}
			echo "</table>
		</td>
	</tr>
	</table><br>";
}
elseif ($section == "showally")
{
	$ally = $myAlly->getallybyid($id);
	if ($ally == 0) exit;
	$allymembers = $myAlly->getallymembers($id);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <a href=?page=ally&section=overview>Allianzliste</a> / <strong>Details: ".$ally[name]."</strong></td>
	</tr>
	</table><br>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr>
		<td colspan=3 class=tdmain><strong>Beitreten</strong></td>
	</tr>
	<tr>
		<form action=main.php method=post>
		<input type=hidden name=page value=ally>
		<input type=hidden name=action value=joinally>
		<input type=hidden name=id value=".$ally[id].">
		<td colspan=3 class=tdmainobg>Passwort <input class=text type=password size=10 name=join_pass> <input class=button type=submit value=go></td>
		</form>
	</tr>
	</table><br>
	<table cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg width=80>Präsident</td>
		<td class=tdmainobg width=410>".stripslashes($myUser->getfield("user",$ally[user_id]))."</td>
		<td class=tdmainobg width=10><a href=main.php?page=comm&section=writepm&recipient=".$ally[user_id]." onMouseOver=document.msg.src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg.src='".$grafik."/buttons/msg1.gif'><img src=".$grafik."/buttons/msg1.gif name=msg border=0></a></td>
	</tr>
	<tr>
		<td class=tdmainobg>Vize-Präsident</td>";
		$ally[vize] != 0 ? print("<td class=tdmainobg>".stripslashes($myUser->getfield("user",$ally[vize]))."</td><td class=tdmainobg><a href=main.php?page=comm&section=writepm&recipient=".$ally[vize]." onMouseOver=document.msg1.src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg1.src='".$grafik."/buttons/msg1.gif'><img src=".$grafik."/buttons/msg1.gif name=msg1 border=0></a></td>") : print("<td class=tdmainobg colspan=2 align=center>-</td>");
	echo "</tr>
	<tr>
		<td class=tdmainobg>Außenminister/Diplomatie</td>";
		$ally[diplo] != 0 ? print("<td class=tdmainobg>".stripslashes($myUser->getfield("user",$ally[diplo]))."</td><td class=tdmainobg><a href=main.php?page=comm&section=writepm&recipient=".$ally[diplo]." onMouseOver=document.msg2.src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg2.src='".$grafik."/buttons/msg1.gif'><img src=".$grafik."/buttons/msg1.gif name=msg2 border=0></a></td>") : print("<td class=tdmainobg colspan=2 align=center>-</td>");
	echo "</tr>
	<tr><td colspan=3 class=tdmainobg><a href=?page=ally&section=allybez&aid=".$ally[id].">Beziehungen</a>".($ally[hp] != "" ? " | <a href=".stripslashes($ally[hp])." target=_blank>Homepage</a>" : "")."</td></tr>
	</table><br>
	<table cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg align=Center width=350 height=10 valign=top><strong>Beschreibung</strong></td>
		<td rowspan=3 class=tdmainobg valign=top width=400><table cellpadding=0 cellspacing=0 width=100%><tr><td class=tdmain align=center>Mitglieder</td></tr>";
		while ($a=mysql_fetch_assoc($allymembers))
		{
			echo "<tr><td class=tdmainobg><img src=".$grafik."/rassen/".$a[rasse]."s.gif> ".stripslashes($a[user])."</td></tr>";
		}
		echo "</table></td>
	</tr>
	<tr>
		<td class=tdmainobg valign=top>".nl2br(stripslashes($ally[descr]))."</td>
	</tr>";
	echo "</table>";
	$builtembassys = $myAlly->getbuiltembassys($ally[id]);
	$ownedembassys = $myAlly->getownedembassys($ally[id]);
	echo "<br><br><table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
	<tr>
		<td colspan=3 class=tdmain><strong>Eigene Botschaften</strong></td>
	</tr>";
	if (mysql_num_rows($ownedembassys) != 0)
	{
		while($allys=mysql_fetch_array($ownedembassys))
		{
			$baustil = $myColony->getfieldbyid($allys[field_id],$allys[colonies_id]);
			echo "<tr>
				<td class=tdmainobg width=40><center><img src=".$grafik."/buildings/".$baustil[build][id]."_1.gif></center></td>
				<td class=tdmainobg width=350>".stripslashes($allys[allyname])." (".$allys[allys_id2].")</td>
				<td class=tdmainobg>".stripslashes($allys[colname])." (".$allys[colonies_id].")</td>
			</tr>";
		}
	}
	else
	{
		echo "<tr>
			<td colspan=3 class=tdmainobg>keine</td>
		</tr>";
	}
	echo "</table><br><table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
	<tr>
		<td colspan=3 class=tdmain><strong>Bereitgestellte Botschaften</strong></td>
	</tr>";
	if (mysql_num_rows($builtembassys) != 0)
	{
		while($allys=mysql_fetch_array($builtembassys))
		{
			$baustil = $myColony->getfieldbyid($allys[field_id],$allys[colonies_id]);
			echo "<tr>
				<td class=tdmainobg width=40><center><img src=".$grafik."/buildings/".$baustil[build][id]."_1.gif></center></td>
				<td class=tdmainobg width=350>".stripslashes($allys[allyname])." (".$allys[allys_id2].")</td>
				<td class=tdmainobg>".stripslashes($allys[colname])." (".$allys[colonies_id].")</td>
			</tr>";
		}
	}
	else
	{
		echo "<tr>
			<td colspan=3 class=tdmainobg>keine</td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "newally")
{
	if ($ally_name && $ally_pass && $ally_descr) $result = $myAlly->newally($ally_name,$ally_pass,$ally_descr,$ally_hp);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <a href=?page=ally&section=overview>Allianzliste</a> / <strong>Allianz gründen</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table width=500 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<form action=main.php method=post>
	<input type=hidden name=page value=ally>
	<input type=hidden name=section value=newally>
	<tr>
		<td class=tdmainobg>Name der Allianz: <input class=text type=text size=50 name=ally_name></td>
	</tr>
	<tr>
		<td class=tdmainobg>Beispiel: < font color=#ffffff>Allianzname< /font></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center>Beschreibung</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center><textarea cols=50 rows=8 name=ally_descr></textarea></td>
	</tr>
	<tr>
		<td class=tdmainobg>Passwort (mind. 5 Zeichen): <input class=text type=password name=ally_pass size=10></td>
	</tr>
	<tr>
		<td class=tdmainobg>Homepage (optional): <input class=text type=text size=50 name=ally_hp></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center><input class=button type=submit value='Allianz gründen'></td>
	</tr></form>
	</table>";
}
elseif ($section == "leaveally")
{
	$id = $myAlly->checkally();
	$ally = $myAlly->getallybyid($id);
	if ($id && $confirm) $result = $myAlly->leaveally($id);
	if ($id && $confirm && ($ally[user_id] == $user)) $result = $myAlly->delally($id);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <a href=?page=ally&section=overview>Allianz ".$data[name]."</a> / <strong>Allianz verlassen</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	if ($confirm == 0)
	{
		echo "<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain><strong>Meldung</strong></td>
		</tr>
		<tr>
		<td class=tdmainobg>";
		$ally[user_id] == $user ? print("Du bist der Präsident der Allianz.<br>Wenn Du die Allianz verlässt wird die Führung den Vizepräsidenten abgegben oder gelöscht. Willst Du das wirklich?<br><a href=main.php?page=ally&section=leaveally&id=".$id."&confirm=1><font color=red>Bestätigung</font></a>") : print("Willst Du die Allianz wirklich verlassen?<br><a href=main.php?page=ally&section=leaveally&id=".$id."&confirm=1><font color=red>Bestätigung</font></a>");
		echo "</td></tr></table><br>";
	}
}
elseif ($section == "delfromally")
{
	$allyId = $myAlly->checkally();
	$ally = $myAlly->getallybyid($allyId);
	if ($id && $confirm) $result = $myAlly->delFromAlly($allyId,$id);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <a href=?page=ally&section=overview>Allianz ".$data[name]."</a> / <strong>Mitglied rausschmeissen</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmainobg>";
	$id && $confirm == 0 ? print("Willst Du das Mitglied wirklich rausschmeissen?<br><a href=main.php?page=ally&section=delfromally&id=".$id."&confirm=1>Bestätigung</a>") : print("Mitglied entfernt");
	echo "</td></tr></table>";
}
elseif ($section == "diplo")
{
	$data = $myAlly->getallybyid($id);
	if ($data == 0 || ($data[diplo] != $user && $data[user_id] != $user && $data[vize] != $user)) exit;
	if (($action == "addbez") && $id && $id2 && $type) $result = $myAlly->addbez($id,$id2,$type);
	if (($action == "editbez") && $id && $id2 && $type) $result = $myAlly->editbez($id,$id2,$type);
	if (($action == "takebez") && $id && $bezid) $result = $myAlly->takebez($id,$bezid);
	if (($action == "delbez") && $bezid && $id && $user) $result = $myAlly->delbez($id,$bezid);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <a href=?page=ally>Allianz ".$data[name]."</a> / <strong>Diplomatie</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table width=300 bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323>";
	$bez = $myAlly->getbez($id);
	if ($bez == 0) echo "<tr><td class=tdmainobg colspan=4 align=center>Keine Beziehungen vorhanden</td></tr>";
	else
	{
		echo "<tr><td class=tdmainobg width=300><strong>Allianz</strong></td>
			<td class=tdmainobg><strong>Status</strong></td>
			<td class=tdmainobg><strong>seit</strong></td>
			<td class=tdmainobg width=200></td></tr>";
		for ($i=0;$i<count($bez);$i++)
		{
			if ($bez[$i][allys_id1] == $id)
			{
				$id2 = $bez[$i][allys_id2];
				$allyd = $myAlly->getallybyid($bez[$i][allys_id2]);
			}
			else
			{
				$id2 = $bez[$i][allys_id1];
				$allyd = $myAlly->getallybyid($bez[$i][allys_id1]);
			}
			if ($bez[$i][type] == 1) $type = "<font color=red>Krieg</font>";
			if ($bez[$i][type] == 2) $type = "<font color=yellow>Handelsvertrag</font>";
			if ($bez[$i][type] == 3) $type = "<font color=green>Freundschaft</font>";
			if ($bez[$i][type] == 4) $type = "<font color=#ffffff>Bündnis</font>";
			echo "<form action=main.php method=post>
				<input type=hidden name=page value=ally>
				<input type=hidden name=section value=diplo>
				<input type=hidden name=action value=editbez>
				<input type=hidden name=id value=".$id.">
				<input type=hidden name=id2 value=".$id2.">
				<tr><td class=tdmainobg>".$allyd[name]."</td>
				<td class=tdmainobg>".$type."</td>
				<td class=tdmainobg>".date("d.m.Y",$bez[$i][date_tsp])."</td>
				<td class=tdmainobg><select name=type>";
				if ($bez[$i][type] != 1) 
				{
					echo "<option value=1>Krieg</option>
					<option value=2>Handelsvertrag</option>
					<option value=3>Freundschaft</option>
					<option value=4>Bündnis</option>
					<option value=5>Löschen</option>";
				}
				else
				{
					echo "<option value=6>Frieden</option>";
				}
				echo "</select> <input type=submit value=Ändern class=button></td></tr></form>";
		}
	}
	$bez = $myAlly->getangebote($id);
	if ($bez == 0) echo "<tr><td class=tdmainobg colspan=4 align=center>Keine Angebote vorhanden</td></tr>";
	else
	{
		echo "<tr><td class=tdmainobg width=300><strong>von Allianz</strong></td>
			<td class=tdmainobg><strong>Angebot</strong></td>
			<td class=tdmainobg colspan=2></td></tr>";
		for ($i=0;$i<count($bez);$i++)
		{
			$allyd = $myAlly->getallybyid($bez[$i][allys_id1]);
			if ($bez[$i][type] == 1) $type = "<font color=red>Krieg</font>";
			if ($bez[$i][type] == 2) $type = "<font color=yellow>Handelsvertrag</font>";
			if ($bez[$i][type] == 3) $type = "<font color=green>Freundschaft</font>";
			if ($bez[$i][type] == 4) $type = "<font color=#ffffff>Bündnis</font>";
			if ($bez[$i][type] == 6) $type = "<font color=#00ff00>Frieden</font>";
			if ($bez[$i][type] == 9) $type = "<font color=#AAAAAA>Botschaften</font>";
			echo "<tr><td class=tdmainobg>".$allyd[name]."</td>
				<td class=tdmainobg>".$type."</td>
				<td class=tdmainobg></td>
				<td class=tdmainobg><a href=main.php?page=ally&section=diplo&action=takebez&bezid=".$bez[$i][id]."&id=".$id.">annehmen</a> | <a href=main.php?page=ally&section=diplo&action=delbez&id=".$id."&bezid=".$bez[$i][id]." onMouseOver=document.del".$i.".src='".$grafik."/buttons/x2.gif' onMouseOut=document.del".$i.".src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del".$i." border=0 alt='Löschen'></a></td></tr>";
		}
	}
	$bez = $myAlly->getsangebote($id);
	if ($bez == 0) echo "<tr><td class=tdmainobg colspan=4 align=center>Keine Verträge angeboten</td></tr>";
	else
	{
		echo "<tr><td class=tdmainobg width=300><strong>an Allianz</strong></td>
			<td class=tdmainobg><strong>Angebot</strong></td>
			<td class=tdmainobg colspan=2></td></tr>";
		for ($i=0;$i<count($bez);$i++)
		{
			$bez[$i][allys_id1] == $id ? $allyd = $myAlly->getallybyid($bez[$i][allys_id2]) : $allyd = $myAlly->getallybyid($bez[$i][allys_id1]);
			if ($bez[$i][type] == 1) $type = "<font color=red>Krieg</font>";
			if ($bez[$i][type] == 2) $type = "<font color=yellow>Handelsvertrag</font>";
			if ($bez[$i][type] == 3) $type = "<font color=green>Freundschaft</font>";
			if ($bez[$i][type] == 4) $type = "<font color=#ffffff>Bündnis</font>";
			if ($bez[$i][type] == 6) $type = "<font color=#00ff00>Frieden</font>";
			if ($bez[$i][type] == 9) $type = "<font color=#AAAAAA>Botschaften</font>";
			echo "<tr><td class=tdmainobg>".$allyd[name]."</td>
				<td class=tdmainobg>".$type."</td>
				<td class=tdmainobg colspan=2>Warte auf Antwort</td></tr>";
		}
	}
	echo "</table><br><table width=400 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmainobg><strong>Beziehung ändern/erstellen</strong></td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ally>
	<input type=hidden name=section value=diplo>
	<input type=hidden name=action value=addbez>
	<input type=hidden name=id value=".$id.">
	<tr>
		<td class=tdmainobg alig=center>Ally-ID <input type=text size=3 class=text name=id2> <select name=type>
		<option value=1>Krieg</option>
		<option value=2>Handelsvertrag</option>
		<option value=3>Freundschaft</option>
		<option value=4>Bündnis</option>
		<option value=5>Löschen</option>
		</select> <input type=submit value=Erstellen class=button></td>
	</tr></table></form>";
	echo "<br><table width=400 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmainobg><strong>Botschaften beantragen</strong></td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=ally>
	<input type=hidden name=section value=diplo>
	<input type=hidden name=action value=addbez>
	<input type=hidden name=type value=9>
	<input type=hidden name=id value=".$id.">
	<tr>
		<td class=tdmainobg alig=center>Ally-ID <input type=text size=3 class=text name=id2>  <input type=submit value=Beantragen class=button></td>
	</tr></table></form>";
}
elseif ($section == "options")
{
	$data = $myAlly->getallybyid($id);
	if ($data == 0 || ($data[user_id] != $user && $data[vize] != $user)) exit;
	if ($sent == 1)
	{
		if ($new_name && $id && $new_name != $data[name]) $result[] = $myAlly->updateally($id,"name",str_replace("\"","",strip_tags($new_name,"<font></font><b></b>")),$user);
		if ($new_pw && $id) $result[] = $myAlly->updateally($id,"pass",md5($new_pw),$user);
		if ($new_descr && $id && $new_descr != $data[descr]) $result[] = $myAlly->updateally($id,"descr",addslashes($new_descr),$user);
		if ($id && $new_url != $data[hp]) $result[] = $myAlly->updateally($id,"hp",$new_url,$user);
		if (is_numeric($new_vize) && $id) $result[] = $myAlly->setwork($id,"vize",$new_vize,$user);
		if (is_numeric($new_diplo) && $id) $result[] = $myAlly->setwork($id,"diplo",$new_diplo,$user);
		if (is_numeric($new_presi) && $id) $result[] = $myAlly->setwork($id,"user_id",$new_presi,$user);
		if (($action == "unsetwork") && $type && $id) $result = $myAlly->unsetwork($id,$type,$user);
	}
	$data = $myAlly->getallybyid($id);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <a href=?page=ally>".$data[name]."</a> / <strong>Einstellungen</strong></td>
	</tr>
	</table><br>";
	if (is_array($result))
	{
		echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>";
		for ($i=0;$i<count($result);$i++) echo $result[$i][msg]."<br>";
		echo"</td></tr></table>";
	}
	echo "<table width=50% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<form action=main.php method=post>
	<input type=hidden name=page value=ally>
	<input type=hidden name=section value=options>
	<input type=hidden name=id value=".$data[id].">
	<input type=hidden name=sent value=1>
	<tr>
		<td class=tdmainobg>Name: <input type=text name=new_name size=50 class=text value=\"".htmlspecialchars($data[name])."\"></td>
	</tr>
	<tr>
		<td class=tdmainobg>HTML erlaubt.</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center>Beschreibung</td>
	</tr>
	<tr>
		<td class=tdmainobg><textarea name=new_descr cols=40 rows=7>".htmlspecialchars(stripslashes($data[descr]))."</textarea></td>
	</tr>
	<tr>
		<td class=tdmainobg>Homepage: <input type=text name=new_url class=text size=50 value='".htmlspecialchars(stripslashes($data[hp]))."'></td>
	</tr>
	<tr>
		<td class=tdmainobg>Neues Passwort: <input type=password name=new_pw size=6 class=text></td>
	</tr>
	<tr>
		<td class=tdmainobg>Das Passwort muss aus mindestens 5 Zeichen bestehen</td>
	</tr>";
	echo "<tr>
		<td class=tdmainobg align=center><input type=submit class=button value='Einstellungen ändern'></td>
	</tr></form>
	</table><br>
	<table width=50% cellpadding=1 cellspacing=1 bgcolor=#262323>";
	$vize = $myUser->getuserbyid($data[vize]);
	$diplo = $myUser->getuserbyid($data[diplo]);
	if ($data[vize] != 0) echo "<tr>
		<td class=tdmain align=Center colspan=3><strong>Posten</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg>Vizepräsident</td>
		<td class=tdmainobg>".stripslashes($vize[user])."</td>
		<td class=tdmainobg><a href=main.php?page=ally&section=options&action=unsetwork&id=".$data[id]."&type=vize&sent=1 onMouseOver=document.del1.src='".$grafik."/buttons/x2.gif' onMouseOut=document.del1.src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del1 border=0></a></td>
	</tr>";
	else echo "<form action=main.php method=post>
	<input type=hidden name=page value=ally>
	<input type=hidden name=section value=options>
	<input type=hidden name=sent value=1>
	<input type=hidden name=id value=".$data[id].">
	<tr>
		<td class=tdmainobg colspan=3>Neuer Vizepräsident - User-ID: <input type=text size=5 name=new_vize class=text> <input type=submit value=Ernennen class=button></td>
	</tr>
	</form>";
	if ($data[diplo] != 0) echo "<tr>
		<td class=tdmainobg>Außenminister</td>
		<td class=tdmainobg>".stripslashes($diplo[user])."</td>
		<td class=tdmainobg><a href=main.php?page=ally&section=options&action=unsetwork&id=".$data[id]."&type=diplo&sent=1 onMouseOver=document.del2.src='".$grafik."/buttons/x2.gif' onMouseOut=document.del2.src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del2 border=0></a></td>
	</tr>";
	else echo "<form action=main.php method=post>
	<input type=hidden name=page value=ally>
	<input type=hidden name=section value=options>
	<input type=hidden name=sent value=1>
	<input type=hidden name=id value=".$data[id].">
	<tr>
		<td class=tdmainobg colspan=3>Neuer Außenminister - User-ID: <input type=text size=5 name=new_diplo class=text> <input type=submit value=Ernennen class=button></td>
	</tr>
	</form>";
	if ($user == $data[user_id])
	{
		echo "<form action=main.php method=post>
		<input type=hidden name=page value=ally>
		<input type=hidden name=section value=options>
		<input type=hidden name=sent value=1>
		<input type=hidden name=id value=".$data[id].">
		<tr>
			<td class=tdmainobg colspan=3><font color=#FF0000>Präsidentschaft abgeben</font> - User-ID: <input type=text size=5 name=new_presi class=text> <input type=submit value=Abgeben class=button></td>
		</tr>
		</form>";
	}
	echo "</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain align=center><a href=main.php?page=ally>Übersicht</a> - <a href=http://stu.weers-online.net/main.php?page=ally&section=overview>Allianzliste</a></td>
	</tr>
	</table>";
}
elseif ($section == "botschaften")
{
	$data = $myAlly->getallybyid($id);
	if ($data == 0) exit;
	if ($data[user_id] != $user && $data[vize] != $user && $data[diplo] != $user) $change = 0;
	$builtembassys = $myAlly->getbuiltembassys($data[id]);
	$ownedembassys = $myAlly->getownedembassys($data[id]);
	$unbuiltembassys = $myAlly->getunbuiltembassys($data[id]);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <a href=?page=ally>".$data[name]."</a> / <strong>Botschaften</strong></td>
	</tr>
	</table><br>";
	echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
	<tr>
		<td colspan=3 class=tdmain><strong>Eigene Botschaften</strong></td>
	</tr>";
	if (mysql_num_rows($ownedembassys) != 0)
	{
		while($allys=mysql_fetch_array($ownedembassys))
		{
			$baustil = $myColony->getfieldbyid($allys[field_id],$allys[colonies_id]);
			if ($change = 1)
			{
				echo "<tr>
					<td class=tdmainobg width=40><center><a href=main.php?page=ally&section=botschaftenstyle&id=".$data[id]."&embassy=".$allys[id]."><img src=".$grafik."/buildings/".$baustil[build][id]."_1.gif border=0></a></center></td>
					<td class=tdmainobg width=350>".stripslashes($allys[allyname])." (".$allys[allys_id1].")</td>
					<td class=tdmainobg>".stripslashes($allys[colname])." (".$allys[colonies_id].")</td>
				</tr>";
			}
			else
			{
				echo "<tr>
					<td class=tdmainobg width=40><center><img src=".$grafik."/buildings/".$baustil[build][id]."_1.gif></center></td>
					<td class=tdmainobg width=350>".stripslashes($allys[allyname])." (".$allys[allys_id1].")</td>
					<td class=tdmainobg>".stripslashes($allys[colname])." (".$allys[colonies_id].")</td>
				</tr>";
			}
		}
	}
	else
	{
		echo "<tr>
			<td colspan=3 class=tdmainobg>keine</td>
		</tr>";
	}
	echo "</table><br><table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
	<tr>
		<td colspan=3 class=tdmain><strong>Bereitgestellte Botschaften</strong></td>
	</tr>";
	if (mysql_num_rows($builtembassys) != 0)
	{
		while($allys=mysql_fetch_array($builtembassys))
		{
			$baustil = $myColony->getfieldbyid($allys[field_id],$allys[colonies_id]);
			echo "<tr>
				<td class=tdmainobg width=40><center><img src=".$grafik."/buildings/".$baustil[build][id]."_1.gif></center></td>
				<td class=tdmainobg width=350>".stripslashes($allys[allyname])." (".$allys[allys_id2].")</td>
				<td class=tdmainobg>".stripslashes($allys[colname])." (".$allys[colonies_id].")</td>
			</tr>";
		}
	}
	else
	{
		echo "<tr>
			<td colspan=3 class=tdmainobg>keine</td>
		</tr>";
	}
	echo "</table><br><table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
	<tr>
		<td colspan=3 class=tdmain><strong>Genehmigte Botschaften</strong></td>
	</tr>";
	if (mysql_num_rows($builtembassys) != 0)
	{
		while($allys=mysql_fetch_array($unbuiltembassys))
		{
			echo "<tr>
				<td class=tdmainobg width=40></td>
				<td class=tdmainobg width=350>".stripslashes($allys[allyname])." (".$allys[allys_id2].")</td>
				<td class=tdmainobg></td>
			</tr>";
		}
	}
	else
	{
		echo "<tr>
			<td colspan=3 class=tdmainobg>keine</td>
		</tr>";
	}
	echo "</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain align=center><a href=main.php?page=ally>Übersicht</a> - <a href=http://stu.weers-online.net/main.php?page=ally&section=overview>Allianzliste</a></td>
	</tr>
	</table>";
}
elseif ($section == "botschaftenstyle")
{
	$data = $myAlly->getallybyid($id);
	if ($data == 0 || $embassy == 0) exit;
	if (($action == 1) && $id && $style && $embassy) $result = $myAlly->changeembassystyle($embassy,$style);
	$data = $myAlly->getallybyid($id);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ Allianzschirm / <a href=?page=ally>".$data[name]."</a> / <a href=main.php?page=ally&section=botschaften&id=".$data[id].">Botschaften</a> / <strong>Baustil verändern</strong></td>
	</tr>
	</table><br>";
	if ($result != 0)
	{
		echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>";
		echo $result[msg]."<br>";
		echo"</td></tr></table>";
	}
	$data2 = mysql_fetch_array($myAlly->getembassybyid($embassy));
	$type = $myColony->getfieldbyid($data2[field_id],$data2[colonies_id]);
	echo "<br><img src=".$grafik."/buildings/".$type[build][id]."_1.gif> Gewählter Baustil<br>";
	echo "<br><a href=main.php?page=ally&section=botschaftenstyle&id=".$data[id]."&embassy=".$embassy."&style=210&action=1><img src=".$grafik."/buildings/210_1.gif border=0> Neutral";
	echo "<br><a href=main.php?page=ally&section=botschaftenstyle&id=".$data[id]."&embassy=".$embassy."&style=211&action=1><img src=".$grafik."/buildings/211_1.gif border=0> Föderation";
	echo "<br><a href=main.php?page=ally&section=botschaftenstyle&id=".$data[id]."&embassy=".$embassy."&style=212&action=1><img src=".$grafik."/buildings/212_1.gif border=0> Romulaner";
	echo "<br><a href=main.php?page=ally&section=botschaftenstyle&id=".$data[id]."&embassy=".$embassy."&style=213&action=1><img src=".$grafik."/buildings/213_1.gif border=0> Klingonen";
	echo "<br><a href=main.php?page=ally&section=botschaftenstyle&id=".$data[id]."&embassy=".$embassy."&style=215&action=1><img src=".$grafik."/buildings/215_1.gif border=0> Ferengi";

}
elseif ($section == "allybez")
{
	$alist = $myAlly->getallyulist();
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=ally>Allianzschirm</a> / <strong>Beziehungen</strong></td>
		</tr>
		</table><br>
		<form method=post action=main.php>
		<input type=hidden name=page value=ally>
		<input type=hidden name=section value=allybez>
		<table align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>Allianz auswählen <select name=aid>";
			while ($d=mysql_fetch_assoc($alist)) echo "<option value=".$d[id].">".stripslashes(strip_tags($d[name]));
			echo "</select> <input type=submit value=anzeigen class=button></td>
		</tr></form></table>";
		if ($aid)
		{
			$d = $myAlly->getallybyid($aid);
			if ($d == 0) exit;
			echo "<br><table align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
			<tr><td class=tdmain>Beziehungen der Allianz ".stripslashes($d[name])."</td></tr>";
			$bez = $myAlly->getubez($aid);
			if ($bez == 0) echo "<td class=tdmainobg align=center>Keine Beziehungen vorhanden</td>";
			else
			{
				while($bd=mysql_fetch_assoc($bez))
				{
					if ($bd[type] == 1) $b = "<font color=red>Krieg</font>";
					if ($bd[type] == 2) $b = "Handelsvertrag";
					if ($bd[type] == 3) $b = "<font color=green>Freundschaft</font>";
					if ($bd[type] == 4) $b = "<font color=#ffffff>Bündnis</font>";
					$aid == $bd[allys_id1] ? $bad = $myAlly->getallybyid($bd[allys_id2]) : $bad = $myAlly->getallybyid($bd[allys_id1]);
					echo "<tr><td class=tdmainobg>".$b." mit ".stripslashes($bad[name])."</td></tr>";
				}
			}
			echo "</table>";
		}
}
?>