<?php
if ($myUser->uhasperr == 1)
{
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td width=100% class=tdmain>/ <strong>Warenbörse</strong></td>
	</tr>
	</table><br>
	<table bgcolor=#262323 cellpadding=1 cellspacing=1 width=300>
	<tr>
		<td class=tdmainobg>Ihr Konto wurde von der Handelsallianz gesperrt</td>
	</tr>
	</table>";
	exit;
}
if (!$section || ($section == "boerse"))
{
	if (($action == "deloffer") && ($user == 5) && $userid && $id)
	{
		$return = $myTrade->deloffer($id,$userid);
		$myComm->sendpm($userid,5,"Ihr Angebot ".$id." wurde aufgrund eines Regelverstoßes gelöscht");
	}
	if (($action == "konfoffer") && ($user == 5) && $userid && $id)
	{
		$return = $myTrade->konfoffer($id,$userid);
		$myComm->sendpm($userid,5,"Die Handelsallianz hat Ihr Angebot ".$id." eingezogen, möglicherweise wegen wiederholten Verstoßes gegen die Handelsregeln.");
	}
	if ($offerid) $return = $myTrade->takeoffer($offerid);
	$tgoods = $myDB->query("SELECT id,name FROM stu_goods WHERE hide=0 ORDER BY sort");
	if ($mode == 1) $select1 = " selected";
	if ($mode == 2) $select2 = " selected";
	$seiten = "<tr><td class=tdmainobg colspan=4>Seite ";
	if ($mode == 1 || $mode == 2) 
	{
		if ($sort != 999) $ac = $myDB->query("SELECT COUNT(a.id) FROM stu_trade_offers as a LEFT OUTER JOIN stu_trade_goods as b ON a.id=b.trade_offers_id WHERE b.goods_id=".$sort." AND b.status=".$mode,1);
		else 
		{
			$ac = $myDB->query("SELECT DISTINCT COUNT(DISTINCT a.id) FROM stu_trade_offers as a LEFT JOIN stu_trade_goods as b ON a.id=b.trade_offers_id LEFT JOIN stu_goods as c ON c.id = b.goods_id WHERE c.hide = 1 AND b.status=".$mode,1);
		}
	}
	else $ac = $myDB->query("SELECT COUNT(id) FROM stu_trade_offers",1);
	if (!$se || $se < 1) $se = 1;
	for ($i=1;$i<=ceil($ac/50);$i++)
	{
		if ($se == $i) $seiten .= " ".($i != 1 ? "| " : "")."<b>".$i."</b>";
		else $seiten .= " ".($i != 1 ? "| " : "")."<a href=main.php?page=trade&section=boerse&mode=".$mode."&sort=".$sort."&se=".$i.">".$i."</a>";
	}
	$seiten .= " (".$ac." Angebote)</td></tr>";
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td width=100% class=tdmain>/ <strong>Warenbörse</strong></td>
	</tr>
	</table><br>";
	if ($return) echo "<table bgcolor=#262323 cellpadding=1 cellspacing=1><tr><td class=tdmain><strong>Meldung</strong></td></tr>
		<tr><td class=tdmainobg>".$return[msg]."</td></tr></table><br>";
	echo "<table bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td class=tdmainobg><a href=main.php?page=trade&section=konto>Konto einsehen</a><br>
		<a href=main.php?page=trade&section=newoffer>Angebot erstellen</a><br>
		<a href=main.php?page=trade&section=showoffers>Angebote einsehen</a></td>
	</tr>
	<tr>
		<td class=tdmainobg><a href=main.php?page=trade&section=merchandising><img src=http://www.stuniverse.de/gfx/secret/kahless.gif border=0> Merchandising-Artikel</a></td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=trade>
	<input type=hidden name=section value=boerse>
	<tr>
		<td class=tdmainobg>Sortierung <select name=mode><option value=1".$select1.">bietet</option><option value=2".$select2.">verlangt</option></select> <select name=sort>";
		while ($g=mysql_fetch_assoc($tgoods))
		{
			echo "<option value=".$g[id];
			if ($sort == $g[id]) echo " selected";
			echo ">".$g[name]."</option>";
		}
		echo "<option value=999>Andere</option>";
		echo "</select> <input type=submit class=button value=anzeigen> | <a href=main.php?page=trade>keine</a></td>
	</tr></form>";
	if ($user == 5) echo "<tr><td class=tdmainobg><a href=?page=trade&section=tradelogs>Handelslogs einsehen</a></td></tr><tr><td class=tdmainobg><a href=?page=trade&section=userkonto>Userkonten einsehen</a></td></tr>";
	echo "</table><br>
	<table width=700 bgcolor=#262323 cellpadding=1 cellspacing=1>".$seiten."
	<tr>
		<td class=tdmain align=center width=500><strong>User</strong></td>
		<td class=tdmain align=center><strong>Bietet</strong></td>
		<td class=tdmain align=center><strong>Verlangt</strong></td>
		<td class=tdmain></td>
	</tr>";
	$result = $myTrade->getOfferList($mode,$sort,$se);
	if (mysql_num_rows($result) != 0)
	{
		while($offers=mysql_fetch_assoc($result))
		{
			if ($dt != date("d",$offers[date_tsp]))
			{
				echo "<tr><td colspan=4 class=tdmain>".date("d.m.Y",$offers[date_tsp])."</td></tr>";
				$dt = date("d",$offers[date_tsp]);
			}
			echo "<tr>
				<td class=tdmainobg>".stripslashes($myUser->getfield("user",$offers[user_id]))."</td>
				<td class=tdmainobg>";
			$gr = $myTrade->getTradeGiveById($offers[id]);
			while($g=mysql_fetch_assoc($gr))
			{
				if ($g[secretimage] != "0")
				{
					echo "<img src=http://www.stuniverse.de/gfx/secret/".$g[secretimage].".gif title='".$g[name]."'> ".$g['count'];
				}
				else
				{
					echo "<img src=".$grafik."/goods/".$g[goods_id].".gif title='".$g[name]."'> ".$g['count'];
				}
				echo "<br>";
			}
			echo "</td><td class=tdmainobg>";
			$wr = $myTrade->getTradeWantById($offers[id]);
			while($w=mysql_fetch_assoc($wr))
			{
				if ($w[secretimage] != "0")
				{
					echo "<img src=http://www.stuniverse.de/gfx/secret/".$w[secretimage].".gif title='".$w[name]."'> ".$w['count'];
				}
				else
				{
					echo "<img src=".$grafik."/goods/".$w[goods_id].".gif title='".$w[name]."'> ".$w['count'];
				}
				echo "<br>";
			}
			echo "<td class=tdmainobg>
				<a href=main.php?page=trade&section=boerse&offerid=".$offers[id]."&mode=".$mode."&sort=".$sort." onMouseOver=cp('tr".$i."','buttons/fergtrade2') onMouseOut=cp('tr".$i."','buttons/fergtrade1')><img src=".$grafik."/buttons/fergtrade1.gif name=tr".$i." border=0 title='Angebot annehmen'></a>
				&nbsp;<a href=main.php?page=comm&section=writepm&recipient=".$offers[user_id]." onMouseOver=cp('pm".$i."','buttons/msg2') onMouseOut=cp('pm".$i."','buttons/msg1')><img src=".$grafik."/buttons/msg1.gif name=pm".$i." border=0 title='Nachricht schreiben'></a>";
			if ($user == 5) echo " | <a href=?page=trade&section=boerse&action=deloffer&userid=".$offers[user_id]."&id=".$offers[id]." onMouseOver=document.del".$i.".src='".$grafik."/buttons/x2.gif' onMouseOut=document.del".$i.".src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del".$i." border=0 title='Angebot canceln'></a>
				<a href=?page=trade&section=boerse&action=konfoffer&userid=".$offers[user_id]."&id=".$offers[id]."><img src=".$grafik."/buttons/x2.gif name=konf".$i." border=0 title='Angebot einziehen'></a>";
			echo "</td></tr>";
			$i++;
		}
	}
	else echo "<tr><td colspan=4 class=tdmainobg align=center>Keine Angebote vorhanden</td></tr>";
	echo $seiten."</table>";
}
elseif ($section == "showoffers")
{
	if ($offerid) $return = $myTrade->deloffer($offerid,$user);
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td width=100% class=tdmain>/ <a href=?page=trade>Warenbörse</a> / <strong>Angebote ansehen</strong></td>
	</tr>
	</table><br>";
	if ($return) echo "<table bgcolor=#262323 cellpadding=1 cellspacing=1><tr><td class=tdmain><strong>Meldung</strong></td></tr>
		<tr><td class=tdmainobg>".$return[msg]."</td></tr></table><br>";
	echo "<table width=400 bgcolor=#262323 cellpadding=1 cellspacing=1>";
	$result = $myTrade->getOfferListbyUser($user);
	if (mysql_num_rows($result) != 0)
	{
		echo "<tr><td class=tdmain>Datum</td>
			<td class=tdmain>biete</td>
			<td class=tdmain>verlange</td>
			<td></td></tr>";
		while($offers=mysql_fetch_assoc($result))
		{
			echo "<tr>
				<td class=tdmainobg>".date("d.m.Y H:i",$offers[date_tsp])."</td>
				<td class=tdmainobg>";
				$gw = $myTrade->getTradeGiveById($offers[id]);
				while($g=mysql_fetch_assoc($gw)) 
				{
					if ($g[secretimage] != "0")
					{
						echo "<img src=http://www.stuniverse.de/gfx/secret/".$g[secretimage].".gif title='".$g[name]."'> ".$g['count'];
					}
					else
					{
						echo "<img src=".$grafik."/goods/".$g[goods_id].".gif title='".$g[name]."'> ".$g['count'];
					}
				}
				echo "</td><td class=tdmainobg>";
				$wg = $myTrade->getTradeWantById($offers[id]);
				while($w=mysql_fetch_assoc($wg)) 
				{
					if ($w[secretimage] != "0")
					{	
						echo "<img src=http://www.stuniverse.de/gfx/secret/".$w[secretimage].".gif title='".$w[name]."'> ".$w['count'];
					}
					else
					{
						echo "<img src=".$grafik."/goods/".$w[goods_id].".gif title='".$w[name]."'> ".$w['count'];
					}
				}
				echo "<td class=tdmainobg>
					<a href=main.php?page=trade&section=showoffers&offerid=".$offers[id].">löschen</a>
				</td></tr>";
		}
	}
	echo "</table>";
}
elseif ($section == "konto")
{
	if (is_numeric($payout)) foreach($good as $key => $value) if (is_numeric($count[$key]) && $count[$key] > 0) $msg .= $myTrade->payout($value,$count[$key],$payout);
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td width=100% class=tdmain>/ <a href=?page=trade>Warenbörse</a> / <strong>Konto einsehen</strong></td>
	</tr>
	</table><br>";
	if ($msg) echo "<table bgcolor=#262323 cellpadding=1 cellspacing=1><tr><td class=tdmain><strong>Meldung</strong></td></tr>
		<tr><td class=tdmainobg>".$msg."</td></tr></table><br>";
	echo "<table width=200 bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td class=tdmain></td>
		<td class=tdmain>Konto</td>
		<td class=tdmain>auszahlen</td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=trade>
	<input type=hidden name=section value=konto>";
	$kr = $myTrade->getKontobyUser($user);
	if (mysql_num_rows($kr) == 0) echo "<tr><td class=tdmainobg align=center colspan=3>Keine Waren im Konto</td></tr>";
	else
	{
		while($konto=mysql_fetch_assoc($kr))
		{
			$i++;
			if ($konto[secretimage] != "0")
			{
				$kpic = "<img src=http://www.stuniverse.de/gfx/secret/".$konto[secretimage].".gif title='".$konto[name]."'>";
			}
			else
			{
				$kpic = "<img src=".$grafik."/goods/".$konto[goods_id].".gif title='".$konto[name]."'>";
			}
			echo "<tr>
				<td class=tdmainobg align=center>".$kpic."</td>
				<td class=tdmainobg>".$konto['count']."</td>
				<td class=tdmainobg><input type=hidden name=good[] value=".$konto[goods_id]."><input class=text type=text name=count[] size=3></td>
			</tr>";
		}
		echo "</table><br><table width=500 bgcolor=#262323 cellpadding=1 cellspacing=1>
		<tr>
			<td class=tdmain></td>
			<td class=tdmain align=center>Name</td>
			<td class=tdmain align=center>Koordinaten</td>
			<td class=tdmain></td>
		</tr>";
		$sr = $myDB->query("SELECT id,name,coords_x,coords_y,wese FROM stu_ships WHERE ships_rumps_id=2 ORDER BY id");
		while($data=mysql_fetch_assoc($sr))
		{
			echo "<tr><td class=tdmainobg align=center><img src=gfx/ships/2.gif></td>
				<td class=tdmainobg>".$data[name]."</td>
				<td class=tdmainobg align=center>".$data[coords_x]."/".$data[coords_y].($data[wese] == 2 ? " (2)" : "")."</td>
				<td class=tdmainobg><input type=radio name=payout value=".$data[id]."></td></tr>";
		}
		echo "</table><br><table width=50% cellpadding=1 cellspacing=1>
			<tr>
			<td colspan=4 align=center><input class=button type=submit value=Auszahlen></td>
			</tr></form>";
	}
	echo "</table>";
}
elseif ($section == "newoffer")
{
	if ($sent == 1)
	{
		if ($myTrade->offercount() >= 25) $msg = "Es sind nur 25 Angebote pro Siedler erlaubt";
		else
		{
			$error = 1;
			$gc = 0;
			foreach($good as $key => $value) 
			{
				if ($give[$key] > 0 && strlen($give[$key]) < 6)
				{
					$result = $myDB->query("SELECT count FROM stu_trade_goods WHERE goods_id=".$value." AND status=0 AND trade_offers_id=0 AND user_id=".$user,1);
					if ($result < $give[$key]) break;
					$gc++;
				}
				if ($want[$key] > 0 && strlen($want[$key]) < 6) $error = 0;
				$goodinfo = $myTrade->getgoodmaxoffer($value);
				if (($give[$key] > $goodinfo[maxoffer]) || ($want[$key] > $goodinfo[maxoffer])) $error = 2;
			}
			if ($error == 0)
			{
				$offerId = $myTrade->newOffer($user);
				foreach($good as $key => $value)
				{
					if ($give[$key] > 0 && strlen($give[$key]) < 6) $myTrade->addtooffer($value,$give[$key],$offerId,1);
					if ($want[$key] > 0 && strlen($want[$key]) < 6) $myTrade->addtooffer($value,$want[$key],$offerId,2);
				}
				$msg = "Angebot ".$offerId." erstellt";
			}
			elseif ($error == 2) $msg = "Warenmenge übersteigt handelbares Maximum";
			else $msg = "Angebot fehlerhaft";
		}
	}
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td width=100% class=tdmain>/ <a href=?page=trade>Warenbörse</a> / <strong>Angebot erstellen</strong></td>
	</tr>
	</table><br>";
	if ($msg) echo "<table bgcolor=#262323 cellpadding=1 cellspacing=1><tr><td class=tdmain><strong>Meldung</strong></td></tr>
		<tr><td class=tdmainobg>".$msg."</td></tr></table><br>";
	echo "<table width=500 bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td class=tdmain></td>
		<td class=tdmain><strong>Ware</strong></td>
		<td class=tdmain><strong>im Konto</strong></td>
		<td class=tdmain><strong>Höchstmenge</strong></td>
		<td class=tdmain><strong>anbieten</strong></td>
		<td class=tdmain><strong>verlangen</strong></td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=trade>
	<input type=hidden name=section value=newoffer>
	<input type=hidden name=sent value=1>";
	$kr = $myTrade->getgoodinfobyuser($user);
	$kvr = $myTrade->getinvisiblegoodInfobyUser($user);
	$j = 0;
	while ($konto=mysql_fetch_assoc($kr))
	{
		$j++;
		if ($konto[secretimage] != "0")
		{
			$kpic = "<img src=http://www.stuniverse.de/gfx/secret/".$konto[secretimage].".gif title='".$konto[name]."'>";
		}
		else
		{
			$kpic = "<img src=".$grafik."/goods/".$konto[id].".gif title='".$konto[name]."'>";
		}
		$maxoffer = $myTrade->getgoodmaxoffer($konto[id]);
		echo "<input type=hidden name=good[] value=".$konto[id]."><tr>
			<td class=tdmainobg>".$kpic."</td>
			<td class=tdmainobg>".$konto[name]."</td>
			<td class=tdmainobg>".(!$konto['count'] ? "0" : $konto['count'])."</td>
			<td class=tdmainobg>".$maxoffer[maxoffer]."</td>
			<td class=tdmainobg><input class=text type=text size=3 name=give[]></td>
			<td class=tdmainobg><input class=text type=text size=3 name=want[]></td>
		</tr>";
		if ($j == 18)
		{
			echo "<tr><td class=tdmainobg colspan=6 align=center><input type=submit class=button value='Angebot erstellen'></td></tr>";
			$j = 0;
		}
	}
	if (mysql_num_rows($kvr) != 0)
	{
		while ($konto=mysql_fetch_assoc($kvr))
		{
			$j++;
			if ($konto[secretimage] != "0")
			{
				$kpic = "<img src=http://www.stuniverse.de/gfx/secret/".$konto[secretimage].".gif title='".$konto[name]."'>";
			}
			else
			{
				$kpic = "<img src=".$grafik."/goods/".$konto[id].".gif title='".$konto[name]."'>";
			}
			$maxoffer = $myTrade->getgoodmaxoffer($konto[id]);
			echo "<input type=hidden name=good[] value=".$konto[id]."><tr>
				<td class=tdmainobg>".$kpic."</td>
				<td class=tdmainobg>".$konto[name]."</td>
				<td class=tdmainobg>".(!$konto['count'] ? "0" : $konto['count'])."</td>
				<td class=tdmainobg>".$maxoffer[maxoffer]."</td>
				<td class=tdmainobg><input class=text type=text size=3 name=give[]></td>
				<td class=tdmainobg>-</td>
			</tr>";
			if ($j == 18)
			{
				echo "<tr><td class=tdmainobg colspan=5 align=center><input type=submit class=button value='Angebot erstellen'></td></tr>";
				$j = 0;
			}
		}
	}
	echo "<tr>
		<td colspan=6 class=tdmainobg align=center><input class=button type=submit value='Angebot erstellen'></td>
	</tr></form>
	</table>";
}
elseif ($section == "tradelogs")
{
	if ($user != 5) exit;
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td width=100% class=tdmain>/ <a href=?page=trade>Warenbörse / <strong>Handelslogs</strong></a></td>
	</tr>
	</table><br>";
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>";
	$log = $myTrade->gettradelog();
	if ($log == 0) echo "<tr><td class=tdmain colspan=3 align=center>Keine Aktionen gespeichert</td></tr>";
	else
	{
		echo "<tr>
			<td class=tdmain align=center><strong>Spieler</strong></td>
			<td class=tdmain align=center><strong>Aktion</strong></td>
			<td class=tdmain align=center><strong>Datum</strong></td>
		</tr>";
		for ($i=0;$i<count($log);$i++)
		{
			$userdat = $myUser->getuserbyid($log[$i][user_id]);
			echo "<tr>
				<td class=tdmainobg>".$userdat[user]."</td>
				<td class=tdmainobg>".$log[$i][aktion]."</td>
				<td class=tdmainobg align=center>".date("d.m.Y H:i:s",$log[$i][date_tsp])."</td>
			</tr>";
		}
	}
	echo "</table>";
}
elseif ($section == "merchandising")
{
	if (($sbutton == "Kaufen")) $result = $myTrade->takeofferaction($user);
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ <a href=?page=trade>Warenbörse / <strong>Merchandising-Artikel</strong></a></td>
	</tr>
	</table><br>";
	if ($result) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";

	echo "Actionfiguren werden nun zum Verkauf angeboten. <br><br>Ein Paket kostet 25 Latinum, jedes Paket enthält eine zufällige, hochwertige Actionfigur.<br><br>Der komplette Satz umfasst zur Zeit: 22 Figuren, davon 3 seltene und 1 sehr seltene.<br><br><br>";

	echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1 width=400><tr><td class=tdmain colspan=3>Actionfiguren</td></tr>
        <tr>
	<td class=tdmainobg width> 1x <img src=".$grafik."/buttons/info1.gif title='Zufällige Actionfigur'></td>
	<td class=tdmainobg>25x <img src=".$grafik."/goods/24.gif title='Latinum'></td>
	<td class=tdmainobg><center><form action=main.php method=post>
	<input type=hidden name=page value=trade>
	<input type=hidden name=section value=merchandising>
	<input type=submit value=Kaufen name=sbutton class=button>
	</form></center></td>
	</tr></table><br>";

}
elseif ($section == "userkonto")
{
	if ($user != 5) exit;
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>
	<tr>
		<td width=100% class=tdmain>/ <a href=?page=trade>Warenbörse / <strong>Userkonten</strong></a></td>
	</tr>
	</table><br>";
	echo "<table width=100% bgcolor=#262323 cellpadding=1 cellspacing=1>";
	$log = $myTrade->gettradelog();
	if ($konto == 0) {
		
	} else {
		echo "<tr>
			<td class=tdmain align=center><strong>Spieler</strong></td>
			<td class=tdmain align=center><strong>Aktion</strong></td>
			<td class=tdmain align=center><strong>Datum</strong></td>
		</tr>";
		for ($i=0;$i<count($log);$i++) {
			$userdat = $myUser->getuserbyid($log[$i][user_id]);
			echo "<tr>
				<td class=tdmainobg>".$userdat[user]."</td>
				<td class=tdmainobg>".$log[$i][aktion]."</td>
				<td class=tdmainobg align=center>".date("d.m.Y H:i:s",$log[$i][date_tsp])."</td>
			</tr>";
		}
	}
	echo "</table>";
}
?>
</body>