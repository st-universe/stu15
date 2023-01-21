<?php
if ($section == "write")
{
	if ($myUser->uknsperr == 1)
	{
		$return[msg] = "Schreibzugriff für das Kommunikationsnetzwerk wurde verweigert";
		$section = "kn";
	}
	else 
	{
		if (($sent == 1) && !$message) {
			$errormsg = "Du hast das Nachrichten-Feld nicht ausgefüllt";
			$section = "write";
		}
		if (($sent == 1) && !$errormsg)
		{
			if (!$subject) $subject = "";
			$message = addslashes($message);
			$subject = addslashes($subject);
			$myComm->addkn(strip_tags($subject,"<b><font><i>"),strip_tags($message,"<b><font><i>"),$off);
			$return[msg] = "Beitrag hinzugefügt";
			$section = "kn";
		}
	}
}
if ($section == "allywrite")
{
	if (($sent == 1) && !$message)
	{
		$errormsg = "Du hast das Nachrichten-Feld nicht ausgefüllt";
		$section = "allywrite";
	}
	if (($sent == 1) && !$errormsg)
	{
		if (!$subject) $subject = "";
		$message = addslashes($message);
		$subject = addslashes($subject);
		$myComm->addallykn(strip_tags($subject,"<b><font><i>"),strip_tags($message,"<b><font><i>"));
		$return[msg] = "Beitrag hinzugefügt";
		$section = "allykn";
	}
}
if ($section == "edit")
{
	if (($sent == 1) && !$message) {
		$errormsg = "Du hast das Nachrichten-Feld nicht ausgefüllt";
		$section = "editkn";
	}
	if (($sent == 1) && !$errormsg)
	{
		if (!$subject) $subject = "";
		$return = $myComm->editkn(strip_tags($subject,"<b><font><i>"),strip_tags($message,"<b><font><i>"),$id);
		$section = "kn";
	}
}
$lzcount = $myComm->getlzcount();
$lzcount == 0 ? $lzcount = "" : $lzcount = "<a href=main.php?page=comm&section=kn&mark=".$myUser->ukn_lz.">Beiträge ab Lesezeichen</a>: ".$lzcount."<br>";
$allylzcount = $myComm->getallylzcount();
$allylzcount == 0 ? $allylzcount = "" : $allylzcount = "<a href=main.php?page=comm&section=allykn&mark=".$myUser->ukn_allylz.">Beiträge ab Lesezeichen</a>: ".$allylzcount."<br>";
if ($action == "knanlr")
{
	$myDB->query("UPDATE stu_user SET knanl=0 WHERE id=".$user);
	$myUser->uknanl = 0;
}
if ($myUser->uknanl == 1 && ($section == "kn" || $section == "write")) $section = "knanl";
if (!$section)
{
	if ($myUser->ually > 0) $ally = '<td valign=top width=30%>
		<table width=100% align=Center cellspacing=1 cellpadding=1 align=center bgcolor=#262323>
			<tr>
				<td class=tdmain align=center><strong>Allianz Komm.-Netzwerk</strong></td>
			</tr>
			<form action=main.php method=post>
			<input type=hidden name=page value=comm>
			<input type=hidden name=section value=searchakn>
			<tr>
				<td class=tdmainobg><a href=main.php?page=comm&section=allykn>Beiträge lesen</a><br>
			'.$allylzcount.'
			<a href=main.php?page=comm&section=allywrite>Beitrag schreiben</a><br>
			Volltextsuche <input type=text size=10 name=txt class=text> <input type=submit value=suchen class=button></td>
			</tr>
			</form>
		</table></td>';
	echo '<table width=100% cellspacing=1 cellpadding=1 align=center bgcolor=#262323>
			<tr>
				<td class=tdmain colspan=2>/ <strong>Kommunikation</strong></td>
			</tr>
			</table><br>
			<table width=100% align=Center cellspacing=1 cellpadding=1 align=center>
			<tr>
				<td valign=top width=30%>
				<table width=100% align=Center cellspacing=1 cellpadding=1 align=center bgcolor=#262323>
					<tr>
						<td align=center class=tdmain><strong>Kommunikations Netzwerk</strong></td>
					</tr>
					<form action=main.php method=post>
					<tr>
						<td class=tdmainobg><a href=main.php?page=comm&section=kn>Beiträge lesen</a><br>
					'.$lzcount.'
					<a href=main.php?page=comm&section=write>Beitrag schreiben</a><br>
					<a href=main.php?page=comm&section=kn&off=1>Nur offizielle Beiträge lesen</a><br>
					<input type=hidden name=page value=comm>
					<input type=hidden name=section value=kn>
					Beitrag ID: <input type=text size=5 class=text name=vmark> <input type=submit value=anzeigen class=button name=sb>
					</form><form action=main.php method=post>
					<input type=hidden name=page value=comm>
					<input type=hidden name=section value=viewknpost>
					Volltextsuche <input type=text size=10 name=txt class=text> <input type=submit value=suchen class=button name=sb>
					</form><form action=main.php method=post>
					<input type=hidden name=page value=comm>
					<input type=hidden name=section value=knbyuser>
					Autorensuche <input type=text size=4 name=tuid class=text> <input type=submit value=suchen class=button name=sb><br>
					<a href=?page=comm&section=knanl>KN-Leitfaden</a></td>
					</tr>
					</form>
				</table>
				</td>
				<td width=5%>&nbsp;</td>
				<td valign=top width=30%>
				<table width=100% align=Center cellspacing=1 cellpadding=1 align=center bgcolor=#262323>
					<tr>
						<td align=center class=tdmain><strong>Private Nachrichten</strong></td>
					</tr>
					<tr>
						<td class=tdmainobg><a href=main.php?page=comm&section=pm>Posteingang</a><br>
						<a href=main.php?page=comm&section=outpm>Postausgang</a><br>
						<a href=main.php?page=comm&section=writepm>Neue Nachricht schreiben</a>';
					if ($myUser->ustatus == 8) echo "<br><a href=?page=comm&section=viewmsgs>PM-Archiv</a>";
					echo '</td></tr>
				</table>
				</td>';
				!$ally ? print("<td width=40%>&nbsp;</td>") : print("<td width=5%>&nbsp;</td>".$ally);
			echo '</tr>
			</table><br>
			<table width=100% align=Center cellspacing=1 cellpadding=1 align=center>
			<tr>
				<td valign=top width=30%>
				<table width=100% align=Center cellspacing=1 cellpadding=1 align=center bgcolor=#262323>
					<tr>
						<td align=center class=tdmain><strong>Externe Links</strong></td>
					</tr>
					<tr>
						<td class=tdmainobg><a href=http://forum.stuniverse.de target=_blank>Forum</a><br>
						<a href=http://scout.stuniverse.de target=_blank>Zeitung</a> ("The Scout")<br>
						Chat: <a href=irc://irc.euirc.net/stu target=_blank>irc.euirc.net</a> #stu</td>
					</tr>
				</table>
				</td>
				<td width=5%>&nbsp;</td>
				<td valign=top width=30%>
				<table width=100% align=Center cellspacing=1 cellpadding=1 align=center bgcolor=#262323>
					<tr>
						<td align=center class=tdmain><strong>Diplomatie</strong></td>
					</tr>
					<tr>
						<td class=tdmainobg><a href=javascript:openfl()>Kontaktliste</a><br>
						<a href=?page=comm&section=editcontacts>Kontakte editieren</a><br>
						<a href=?page=ally&section=allybez>Allianz-Beziehungen</a></td>
					</tr>
				</table>
				</td>
				<td width=35%>&nbsp;</td>
			</tr>
		</table>';
}
elseif ($section == "kn")
{
	if ($vmark) $mark = $vmark+4;
	if (($action == "delmsg") && $id) $return = $myComm->delknmsg($id,$user);
	if ($setmark) $return = $myComm->setlz($setmark,$user);
	if (!$mark) $mark = $myComm->getknmaxid();
	$off == 1 ? $msg = $myComm->getknbylzoff($mark) : $msg = $myComm->getknbylz($mark);
	$i=0;
	$back = $myDB->query("SELECT id FROM stu_kn_messages WHERE id<".$mark." ORDER by date DESC LIMIT 4,1",1);
	$count = $myDB->query("SELECT count(id) FROM stu_kn_messages WHERE id>".$mark." ORDER by date DESC",1);
	$forward = $myDB->query("SELECT id FROM stu_kn_messages WHERE id>".$mark." ORDER by date DESC LIMIT ".($count-5 > 0 ? $count-5 : "0").", 1",1);
	if ($msg == 0) $kn = "<table align=center cellpadding=1 cellspacin=1 bgcolor=#262323><tr><td class=tdmainobg align=center>Keine Beiträge vorhanden</td></tr></table>";
	else
	{
		while($m=mysql_fetch_assoc($msg))
		{
			$m[picture] != "" ? $m[picture] = stripslashes($m[picture]) : $m[picture] = $grafik."/rassen/".$m[rasse]."kn.gif";
			$m[status] == 9 ? $m[us] = "<strong>NPC</strong>" : $m[us] = "(".$m[user_id].")";
			$m[status] == 9 ? $m[rasse] = "" : $m[rasse] = "<img src=".$grafik."/rassen/".$m[rasse]."s.gif> ";
			if ($m[date_tsp]+300 > time() && $m[user_id] == $user) $m[edit] = "<a href=main.php?page=comm&section=editkn&id=".$m[id]." onMouseOver=cp('edit".$i."','buttons/knedit2') onMouseOut=cp('edit".$i."','buttons/knedit1')><img src=".$grafik."/buttons/knedit1.gif name=edit".$i." border=0 title='Beitrag editieren'></a>";
			$myUser->ustatus == 8 ? $del = "<a href=?page=comm&section=kn&action=delmsg&id=".$m[id]." onMouseOver=cp('del".$i."','buttons/x2') onMouseOut=cp('del".$i."','buttons/x1')><img src=".$grafik."/buttons/x1.gif name=del".$i." border=0></a>" : $del = "";
			$m['date'] = date("d.m.",$m[date_tsp]).(date("Y",$m[date_tsp])+375).date(" H:i",$m[date_tsp]);
			$kn .= "<table width=800 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
			<tr>
				<td rowspan=3 class=tdmainobg align=center width=70>
				<img src=".$m[picture]." width=64 height=64 border=0><br>
				<font style=\"font: 9px sans-serif; color:Gray;\">".$m[id]."</font></td>
			    <td colspan=2 align=center class=tdmain width=730>".stripslashes($m[subject])."</td>
			</tr>
			<tr>
			    <td width=610 class=tdmainobg valign=top>".$m[rasse].stripslashes($m[user])." ".$m[us]." <a href=main.php?page=comm&section=writepm&recipient=".$m[user_id]." onMouseOver=cp('msg".$i."','buttons/msg2') onMouseOut=cp('msg".$i."','buttons/msg1')><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0 title='private Nachricht schreiben'></a> ".$m[edit]." ".$del." <a href=main.php?page=hally&section=sinfo&id=".$m[user_id]." onMouseOver=cp('sinfo".$i."','buttons/info2') onMouseOut=cp('sinfo".$i."','buttons/info1')><img src='".$grafik."/buttons/info1.gif' name=sinfo".$i." border=0 title='Spielerprofil'></a> <a href=main.php?page=comm&section=kn&setmark=".$m[id]."  onMouseOver=cp('lese".$i."','buttons/lese2') onMouseOut=cp('lese".$i."','buttons/lese1')><img src=".$grafik."/buttons/lese1.gif name=lese".$i." border=0 title='Lesezeichen setzen'></a></td>
			    <td width=120 class=tdmain valign=top>".$m['date']."</td>
			</tr>
			<tr>
			    <td colspan=2 class=tdmain width=730 height=50 valign=top>".stripslashes(nl2br($m[text]))."</td>
			</tr>
			</table>
			<br>";
			$i++;
		}
	}
	if (!$back) $back = $m[id];
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <strong>Kommunikations Netzwerk</strong></td>
		</tr>
	</table><br>";
	if ($return) echo "<table cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$return[msg]."</td></tr></table><br>";
	echo "<table cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr>";
	echo "<td class=tdmain><a href=main.php?page=comm&section=kn&mark=".$forward."&off=".$off." onMouseOver=cp('vor','buttons/b_from2') onMouseOut=cp('vor','buttons/b_from1')><img src='".$grafik."/buttons/b_from1.gif' name=vor border=0 title='vorblättern'></a> <a href=main.php?page=comm&section=kn&mark=".$back."&off=".$off." onMouseOver=cp('zuruck','buttons/b_to2') onMouseOut=cp('zuruck','buttons/b_to1')><img src='".$grafik."/buttons/b_to1.gif' name=zuruck border=0 title='zurückblättern'></a> <a href=main.php?page=comm&section=write onMouseOver=document.write.src='".$grafik."/buttons/knedit2.gif' onMouseOut=document.write.src='".$grafik."/buttons/knedit1.gif'><img src='".$grafik."/buttons/knedit1.gif' name=write border=0 title='Beitrag schreiben'></a></td>";
	echo "</tr>
	</table><br>".$kn;
	echo "<table cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><a href=main.php?page=comm&section=kn&mark=".$forward."&off=".$off." onMouseOver=cp('vor1','buttons/b_from2') onMouseOut=cp('vor1','buttons/b_from1')><img src='".$grafik."/buttons/b_from1.gif' name=vor1 border=0 title='vorblättern'></a> <a href=main.php?page=comm&section=kn&mark=".$back."&off=".$off." onMouseOver=cp('zuruck1','buttons/b_to2') onMouseOut=cp('zuruck1','buttons/b_to1')><img src='".$grafik."/buttons/b_to1.gif' name=zuruck1 border=0 title='zurückblättern'></a> <a href=main.php?page=comm&section=write onMouseOver=document.write1.src='".$grafik."/buttons/knedit2.gif' onMouseOut=document.write1.src='".$grafik."/buttons/knedit1.gif'><img src='".$grafik."/buttons/knedit1.gif' name=write1 border=0 title='Beitrag schreiben'></a></td>
	</tr>
	</table><br>";
}
elseif ($section == "write")
{
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <a href=?page=comm&section=kn>Kommunikations Netzwerk</a> / <strong>Nachricht schreiben</strong></td>
		</tr>
		</table><br>";
	if ($errormsg) echo "<table width=250 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmain>".$errormsg."</td></tr></table><br>";
	echo "<table width=500 cellspacing=1 cellpadding=1 bgcolor=#262323>
		<form action=main.php method=post>
		<input type=hidden name=page value=comm>
		<input type=hidden name=section value=write>
		<input type=hidden name=sent value=1>
		<tr>
			<td class=tdmain>Überschrift: <input class=text type=text name=subject maxlength=255 size=40 value='".$subject."'></td>
		</tr>
		<tr>
			<td class=tdmain><input type=checkbox name=off value=1 checked> Offizielle Nachricht</td>
		</tr>
		<tr>
			<td class=tdmain><textarea cols=60 rows=15 name=message>".$message."</textarea></td>
		</tr>
		<tr>
			<td class=tdmain><input class=button type=submit value=Hinzufügen> <input class=button type=reset></td>
		</tr></form>
		</table>";
}
elseif ($section == "editkn")
{
	$msg = $myComm->getknmsgbyid($id);
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <a href=?page=comm&section=kn>Kommunikations Netzwerk</a> / <strong>Nachricht editieren</strong></td>
		</tr>
		</table><br>";
	if ($msg == 0) $tmperr = "Beitrag nicht vorhanden";
	if ($msg[date_tsp]+300 < time()) $tmperr = "Die Edit-Zeitspanne ist bereits verstrichen";
	if ($tmperr) echo "<table width=300 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$tmperr."</td></tr></table><br>";
	else
	{
		if ($errormsg) echo "<table width=250 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmain>".$errormsg."</td></tr></table><br>";
		echo "<table width=500 cellspacing=1 cellpadding=1 bgcolor=#262323>
			<form action=main.php method=post>
			<input type=hidden name=section value=edit>
			<input type=hidden name=sent value=1>
			<input type=hidden name=page value=comm>
			<input type=hidden name=id value=".$id.">
			<tr>
				<td class=tdmain>Überschrift: <input class=text type=text name=subject maxlength=255 size=40 value=\"".stripslashes($msg[subject])."\"></td>
			</tr>
			<tr>
				<td class=tdmain><textarea cols=60 rows=15 name=message>".stripslashes($msg[text])."</textarea></td>
			</tr>
			<tr>
				<td class=tdmain><input class=button type=submit value=Editieren> <input class=button type=reset></td>
			</tr></form>
			</table>";
	}
}
elseif ($section == "viewknpost")
{
	if ($sb == "suchen" || !$vmark) $vt = 1;
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <a href=?page=comm&section=kn>Kommunikations Netzwerk</a> / <strong>Beitragssuche</strong></td>
		</tr>
		</table><br>";
	$vt == 1 ? $msg = $myComm->getknmsgbytxt($txt) : $msg = $myComm->getknmsgbyid($vmark);
	if ($msg == 0) echo "<table cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg align=center>Keinen Beitrag gefunden</td></tr></table><br>";
	else
	{
		if ($vt == 0)
		{
			if ($msg[status] == 9)
			{
				$id = "<strong>NPC</strong>";
				unset($rasse);
			}
			else
			{
				$id = "(".$msg[user_id].")";
				$rasse = "<img src=".$grafik."/rassen/".$msg[rasse]."s.gif> ";
			}
			$msg[picture] != "" ? $kngfx = $msg[picture] : $kngfx = $grafik."/rassen/".$msg[rasse]."kn.gif";
			echo "<table width=800 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
				<tr>
					<td rowspan=3 class=tdmainobg align=center width=70>
					<img src=\"".$kngfx."\" width=64 height=64 border=0><br>
					<font style=\"font: 9px sans-serif; color:Gray;\">".$msg[id]."</font></td>
				    <td colspan=2 align=center class=tdmain width=730>".stripslashes($msg[subject])."</td>
				</tr>
				<tr>
				    <td width=610 class=tdmainobg valign=top>".$rasse."".$msg[user]." ".$id." <a href=main.php?page=comm&section=writepm&recipient=".$msg[user_id]." onMouseOver=document.msg".$i.".src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg".$i.".src='".$grafik."/buttons/msg1.gif'><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0 title='private Nachricht schreiben'></a> <a href=main.php?page=hally&section=sinfo&id=".$msg[user_id]." onMouseOver=document.sinfo".$i.".src='".$grafik."/buttons/info2.gif' onMouseOut=document.sinfo".$i.".src='".$grafik."/buttons/info1.gif'><img src='".$grafik."/buttons/info1.gif' name=sinfo".$i." border=0 title='Spielerprofil'></a> <a href=main.php?page=comm&section=kn&setmark=".$msg[id]."  onMouseOver=document.lese".$i.".src='".$grafik."/buttons/lese2.gif' onMouseOut=document.lese".$i.".src='".$grafik."/buttons/lese1.gif'><img src=".$grafik."/buttons/lese1.gif name=lese".$i." border=0 title='Lesezeichen setzen'></a></td>
				    <td width=120 class=tdmain valign=top>".date("d.m.",$msg[date_tsp]).(date("Y",$msg[date_tsp])+375).date(" H:i",$msg[date_tsp])."</td>
				</tr>
				<tr>
				    <td colspan=2 class=tdmain width=730 height=50 valign=top>".stripslashes(nl2br($msg[text]))."</td>
				</tr>
				</table><br>";
		}
		else
		{
			for ($i=0;$i<count($msg);$i++)
			{
				if ($msg[$i][status] == 9)
				{
					$id = "<strong>NPC</strong>";
					unset($rasse);
				}
				else
				{
					$id = "(".$msg[$i][user_id].")";
					$rasse = "<img src=".$grafik."/rassen/".$msg[$i][rasse]."s.gif> ";
				}
				$msg[$i][picture] != "" ? $kngfx = $msg[$i][picture] : $kngfx = $grafik."/rassen/".$msg[$i][rasse]."kn.gif";
				echo "<table width=800 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
				<tr>
					<td rowspan=3 class=tdmainobg align=center width=70>
					<img src=\"".$kngfx."\" width=64 height=64 border=0><br>
					<font style=\"font: 9px sans-serif; color:Gray;\">".$msg[$i][id]."</font></td>
				    <td colspan=2 align=center class=tdmain width=730>".stripslashes($msg[$i][subject])."</td>
				</tr>
				<tr>
				    <td width=610 class=tdmainobg valign=top>".$rasse."".$msg[$i][user]." ".$id." <a href=main.php?page=comm&section=writepm&recipient=".$msg[$i][user_id]." onMouseOver=document.msg".$i.".src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg".$i.".src='".$grafik."/buttons/msg1.gif'><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0 title='private Nachricht schreiben'></a> <a href=main.php?page=hally&section=sinfo&id=".$msg[$i][user_id]." onMouseOver=document.sinfo".$i.".src='".$grafik."/buttons/info2.gif' onMouseOut=document.sinfo".$i.".src='".$grafik."/buttons/info1.gif'><img src='".$grafik."/buttons/info1.gif' name=sinfo".$i." border=0 title='Spielerprofil'></a> <a href=main.php?page=comm&section=kn&setmark=".$msg[$i][id]."  onMouseOver=document.lese".$i.".src='".$grafik."/buttons/lese2.gif' onMouseOut=document.lese".$i.".src='".$grafik."/buttons/lese1.gif'><img src=".$grafik."/buttons/lese1.gif name=lese".$i." border=0 title='Lesezeichen setzen'></a></td>
				    <td width=120 class=tdmain valign=top>".date("d.m.",$msg[$i][date_tsp]).(date("Y",$msg[$i][date_tsp])+375).date(" H:i",$msg[$i][date_tsp])."</td>
				</tr>
				<tr>
				    <td colspan=2 class=tdmain width=730 height=50 valign=top>".stripslashes(nl2br($msg[$i][text]))."</td>
				</tr>
				</table><br>";
			}
		}
	}
}
elseif ($section == "searchakn")
{
	if ($myUser->ually == 0) exit;
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <a href=?page=comm&section=allykn>Allianz Kommunikations Netzwerk</a> / <strong>Beitragssuche</strong></td>
		</tr>
		</table><br>";
	$msg = $myComm->getaknmsgbytxt($txt);
	if ($msg == 0) echo "<table cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg align=center>Keinen Beitrag gefunden</td></tr></table><br>";
	else
	{
		for ($i=0;$i<count($msg);$i++)
		{
			if ($msg[$i][status] == 9)
			{
				$id = "<strong>NPC</strong>";
				unset($rasse);
			}
			else
			{
				$id = "(".$msg[$i][user_id].")";
				$rasse = "<img src=".$grafik."/rassen/".$msg[$i][rasse]."s.gif> ";
			}
			$msg[$i][picture] != "" ? $kngfx = $msg[$i][picture] : $kngfx = $grafik."/rassen/".$msg[$i][rasse]."kn.gif";
			echo "<table width=800 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
			<tr>
				<td rowspan=3 class=tdmainobg align=center width=70>
				<img src=\"".$kngfx."\" width=64 height=64 border=0><br>
				<font style=\"font: 9px sans-serif; color:Gray;\">".$msg[$i][id]."</font></td>
			    <td colspan=2 align=center class=tdmain width=730>".stripslashes($msg[$i][subject])."</td>
			</tr>
			<tr>
			    <td width=610 class=tdmainobg valign=top>".$rasse."".$msg[$i][user]." ".$id." <a href=main.php?page=comm&section=writepm&recipient=".$msg[$i][user_id]." onMouseOver=cp('msg".$i."','buttons/msg2') onMouseOut=cp('msg".$i."','buttons/msg1')><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0 title='private Nachricht schreiben'></a> <a href=main.php?page=hally&section=sinfo&id=".$msg[$i][user_id]." onMouseOver=cp('sinfo".$i."','buttons/info2') onMouseOut=cp('sinfo".$i."','buttons/info1')><img src='".$grafik."/buttons/info1.gif' name=sinfo".$i." border=0 title='Spielerprofil'></a> <a href=main.php?page=comm&section=allykn&setmark=".$msg[$i][id]."  onMouseOver=cp('lese".$i."','buttons/lese2') onMouseOut=cp('lese".$i."','buttons/lese1')><img src=".$grafik."/buttons/lese1.gif name=lese".$i." border=0 title='Lesezeichen setzen'></a></td>
			    <td width=120 class=tdmain valign=top>".date("d.m.",$msg[$i][date_tsp]).(date("Y",$msg[$i][date_tsp])+375).date(" H:i",$msg[$i][date_tsp])."</td>
			</tr>
			<tr>
			    <td colspan=2 class=tdmain width=730 height=50 valign=top>".stripslashes(nl2br($msg[$i][text]))."</td>
			</tr>
			</table><br>";
		}
	}
}
elseif ($section == "knbyuser")
{
	if (!is_numeric($tuid) || $tuid < 1) exit;
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <a href=?page=comm&section=kn>Kommunikations Netzwerk</a> / <strong>Autorensuche</strong></td>
		</tr>
		</table><br>";
	$res = $myComm->getknmsgbyuser($tuid);
	if (mysql_num_rows($res) == 0) echo "<table cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg align=center>Keinen Beitrag gefunden</td></tr></table><br>";
	else
	{
		while($msg=mysql_fetch_assoc($res))
		{
			if ($msg[status] == 9)
			{
				$id = "<strong>NPC</strong>";
				unset($rasse);
			}
			else
			{
				$id = "(".$msg[user_id].")";
				$rasse = "<img src=".$grafik."/rassen/".$msg[rasse]."s.gif> ";
			}
			$msg[picture] != "" ? $kngfx = $msg[picture] : $kngfx = $grafik."/rassen/".$msg[rasse]."kn.gif";
			echo "<table width=800 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
			<tr>
				<td rowspan=3 class=tdmainobg align=center width=70>
				<img src=\"".$kngfx."\" width=64 height=64 border=0><br>
				<font style=\"font: 9px sans-serif; color:Gray;\">".$msg[id]."</font></td>
			    <td colspan=2 align=center class=tdmain width=730>".stripslashes($msg[subject])."</td>
			</tr>
			<tr>
			    <td width=610 class=tdmainobg valign=top>".$rasse."".$msg[user]." ".$id." <a href=main.php?page=comm&section=writepm&recipient=".$msg[user_id]." onMouseOver=document.msg".$i.".src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg".$i.".src='".$grafik."/buttons/msg1.gif'><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0 title='private Nachricht schreiben'></a> <a href=main.php?page=hally&section=sinfo&id=".$msg[user_id]." onMouseOver=document.sinfo".$i.".src='".$grafik."/buttons/info2.gif' onMouseOut=document.sinfo".$i.".src='".$grafik."/buttons/info1.gif'><img src='".$grafik."/buttons/info1.gif' name=sinfo".$i." border=0 title='Spielerprofil'></a> <a href=main.php?page=comm&section=kn&setmark=".$msg[id]."  onMouseOver=document.lese".$i.".src='".$grafik."/buttons/lese2.gif' onMouseOut=document.lese".$i.".src='".$grafik."/buttons/lese1.gif'><img src=".$grafik."/buttons/lese1.gif name=lese".$i." border=0 title='Lesezeichen setzen'></a></td>
			    <td width=120 class=tdmain valign=top>".date("d.m.",$msg[date_tsp]).(date("Y",$msg[date_tsp])+375).date(" H:i",$msg[date_tsp])."</td>
			</tr>
			<tr>
			    <td colspan=2 class=tdmain width=730 height=50 valign=top>".stripslashes(nl2br($msg[text]))."</td>
			</tr>
			</table><br>";
		}
	}
}
elseif ($section == "pm")
{
	if (!$cat) $cat = 1;
	if ($action == "markasread") $myComm->markallasread($cat,$user);
	elseif ($action == "delall") $myComm->delallpms($cat,$user);
	if (!$begin) $begin = 0;
	if ($del) $myComm->delpm($del);
	echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <strong>Posteingang</strong></td>
		</tr>
		</table><br>";
	$maxId = $myComm->getknmaxid();
	$begin ? $res = $myComm->getpmsmark($begin,$cat) : $res = $myComm->getpms($cat);
	$i=0;
	echo "<table width=100%>
	<tr>
		<td valign=top>";
		if (mysql_num_rows($res) == 0) echo "<table width=600 align=center bgcolor=#26232><tr><td align=center class=tdmain>Keine Nachrichten vorhanden</td></tr></table>";
		else
		{
			while($msg=mysql_fetch_assoc($res))
			{
				$myComm->markasread($msg[id]);
				$msg[status] == 9 ? $npc = "<strong>NPC</strong> " : $npc = "(".$msg[sender].")";
				echo "<table width=600 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
				<tr>
				    <td width=60% class=tdmainobg>von: ".stripslashes($msg[user])." ".$npc." <a href=main.php?page=comm&section=writepm&recipient=".$msg[sender]." onMouseOver=document.msg".$i.".src='".$grafik."/buttons/msg2.gif' onMouseOut=document.msg".$i.".src='".$grafik."/buttons/msg1.gif'><img src=".$grafik."/buttons/msg1.gif name=msg".$i." border=0 title='Antworten'></a> <a href=main.php?page=hally&section=sinfo&id=".$msg[sender]." onMouseOver=document.sinfo".$i.".src='".$grafik."/buttons/info2.gif' onMouseOut=document.sinfo".$i.".src='".$grafik."/buttons/info1.gif'><img src='".$grafik."/buttons/info1.gif' name=sinfo".$i." border=0></a> <a href=main.php?page=comm&section=pm&del=".$msg[id]."&recipient=".$msg[sender]."&cat=".$cat." onMouseOver=document.del".$i.".src='".$grafik."/buttons/x2.gif' onMouseOut=document.del".$i.".src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del".$i." border=0 title='Löschen'></a></td>
				    <td class=tdmain>Zeit:</td>
				    <td class=tdmainobg>".date("d.m.",$msg[date_tsp]).(date("Y",$msg[date_tsp])+375).date(" H:i",$msg[date_tsp])."</td>
				</tr>
				<tr>
				    <td colspan=3 class=tdmain>".stripslashes(nl2br($msg[message]))."</td>
				</tr>
				</table><br>";
				$i++;
			}
		}
		echo "</td><td valign=top>
			<table bgcolor=#262323 width=200>
			<tr>
				<td class=tdmain><strong>Steuerung</strong></td>
			</tr>
			<tr>
				<td class=tdmainobg>";
		$vor = $begin - 10;
		$back = $begin + 10;
		if ($vor < 0) $vor = 0;
		if (mysql_num_rows($res) < 10) $back = $begin;
		echo "<a href=main.php?page=comm&section=pm&cat=".$cat."&begin=".$vor." onMouseOver=document.vor.src='".$grafik."/buttons/b_from2.gif' onMouseOut=document.vor.src='".$grafik."/buttons/b_from1.gif'><img src=".$grafik."/buttons/b_from1.gif name=vor border=0 title='vorblättern'></a>
		<a href=main.php?page=comm&section=pm&cat=".$cat."&begin=".$back." onMouseOver=document.zur.src='".$grafik."/buttons/b_to2.gif' onMouseOut=document.zur.src='".$grafik."/buttons/b_to1.gif'><img src=".$grafik."/buttons/b_to1.gif name=zur border=0 title='zurückblättern'></a>
		<a href=main.php?page=comm&section=pm&cat=".$cat."&begin=".$begin."&action=markasread onMouseOver=document.ma.src='".$grafik."/buttons/urgs2.gif' onMouseOut=document.ma.src='".$grafik."/buttons/urgs1.gif'><img src=".$grafik."/buttons/urgs1.gif name=ma border=0 title='Alle Nachrichten als gelesen markieren'></a>
		<a href=main.php?page=comm&section=pm&cat=".$cat."&action=delall onMouseOver=document.del.src='".$grafik."/buttons/x2.gif' onMouseOut=document.del.src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del border=0 title='Alle Nachrichten löschen'></a>
		<a href=?page=comm&section=writepm onMouseOver=document.writea.src='".$grafik."/buttons/knedit2.gif' onMouseOut=document.writea.src='".$grafik."/buttons/knedit1.gif'><img src='".$grafik."/buttons/knedit1.gif' name=writea border=0 title='Neue Nachricht schreiben'></a><br><br>";
		$cated = $myComm->getpmcatmsg(1,$user);
		$cated['new'] > 0 ? $new = "<font color=Red>".$cated['new']."</font>" : $new = 0;
		$cat == 1 ? print("<font color=Yellow>Privat</font> (".$cated[ges]."/".$new.")<br>") : print("<a href=main.php?page=comm&section=pm&cat=1>Privat</a> (".$cated[ges]."/".$new.")<br>");
		$cated = $myComm->getpmcatmsg(2,$user);
		$cated['new'] > 0 ? $new = "<font color=Red>".$cated['new']."</font>" : $new = 0;
		$cat == 2 ? print("<font color=Yellow>Schiffe</font> (".$cated[ges]."/".$new.")<br>") : print("<a href=main.php?page=comm&section=pm&cat=2>Schiffe</a> (".$cated[ges]."/".$new.")<br>");
		$cated = $myComm->getpmcatmsg(3,$user);
		$cated['new'] > 0 ? $new = "<font color=Red>".$cated['new']."</font>" : $new = 0;
		$cat == 3 ? print("<font color=Yellow>Handel</font> (".$cated[ges]."/".$new.")<br>") : print("<a href=main.php?page=comm&section=pm&cat=3>Handel</a> (".$cated[ges]."/".$new.")<br>");
		$cated = $myComm->getpmcatmsg(4,$user);
		$cated['new'] > 0 ? $new = "<font color=Red>".$cated['new']."</font>" : $new = 0;
		$cat == 4 ? print("<font color=Yellow>Kolonien</font> (".$cated[ges]."/".$new.")") : print("<a href=main.php?page=comm&section=pm&cat=4>Kolonien</a> (".$cated[ges]."/".$new.")");
		echo "<br><br><a href=?page=comm&section=outpm>Postausgang</a></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>";
}
elseif ($section == "writepm")
{
	if (($sent == 1) && $message)
	{
		$return = $myComm->sendpm($recipient,$user,$message);
		unset($recipient);
	}
	echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <strong>Neue Nachricht schreiben</strong></td>
		</tr>
		</table><br>";
	if ($return) echo "<table width=300 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$return[msg]."</td></tr></table><br>";
	echo "<table width=400 cellspacing=1 cellpadding=1 bgcolor=#26232>
		<form action=main.php method=post name=pm>";
	if ($recipient)
	{
		echo "<input type=hidden name=recipient value=".$recipient."><tr>
			<td border=1 class=tdmainobg>Empfänger: ".stripslashes($myUser->getfield("user",$recipient))." (".$recipient.")</td>
		</tr>";
	}
	else
	{
		$list = $myComm->getcontacts($user);
		if ($list != 0)
		{
			echo "<script language=Javascript>
			function ausgabe() {
				var number=document.pm.contacts.selectedIndex;
 					if ((number<0)||(number>=document.pm.contacts.options.length)) {
					document.pm.recipient.value=\"\";
				} else {
					var Text=document.pm.contacts.options[number].value;
					document.pm.recipient.value=Text;
				}
			}
			</script>";
		}
		echo "<tr><td class=tdmainobg>Empfänger-ID: <input class=text type=text name=recipient size=6>&nbsp";
				if ($list != 0)
				{
					echo "<select name=contacts onChange=ausgabe();><option value=></option>";
					for($i=0;$i<count($list);$i++) {
						echo "<option value=".$list[$i][recipient].">";
						$recdat = $myUser->getuserbyid($list[$i][recipient]);
						echo stripslashes(strip_tags($recdat[user]))." (".$recdat[id].")</option>";
					}
					echo "</select>";
				}
				echo " <a href=?page=comm&section=editcontacts>Kontakte editieren</a></td></tr>";
	}
	echo "<input type=hidden name=page value=comm>
		<input type=hidden name=section value=writepm>
		".$add."
		<input type=hidden name=sent value=1>
		<tr>
			<td class=tdmainobg><textarea cols=60 rows=15 name=message></textarea></td>
		</tr>
		<tr>
			<td class=tdmainobg align=center><input class=button type=submit value=Abschicken> <input class=button type=reset></td>
		</tr></form>
		</table>";
}
elseif ($section == "allykn")
{
	if ($myUser->ually == 0) exit;
	if ($setmark) $return = $myComm->setallylz($setmark);
	if ($del > 0) $return = $myComm->delallymsg($del);
	echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <strong>Allianz Kommunikations Netzwerk</strong></td>
		</tr>
		</table><br>";
	if ($return) echo "<table width=300 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$return[msg]."</td></tr></table><br>";
	if (!$mark) $mark = $myComm->getallyknmaxid();
	if (!$mark) $mark = 0;
	$rmsg = $myComm->getallyknbylz($mark);
	$i=0;
	$back = $myDB->query("SELECT id FROM stu_allys_messages WHERE id<".$mark." AND allys_id=".$myUser->ually." ORDER by date DESC LIMIT 4,1",1);
	$count = $myDB->query("SELECT count(id) FROM stu_allys_messages WHERE id>".$mark." AND allys_id=".$myUser->ually." ORDER by date DESC",1);
	$forward = $myDB->query("SELECT id FROM stu_allys_messages WHERE id>".$mark." AND allys_id=".$myUser->ually." ORDER by date DESC LIMIT ".($count-5 > 0 ? $count-5 : "0").", 1",1);
	$myDB->query("SELECT id FROM stu_allys WHERE user_id=".$user,1) != 0 ? $dm = 1 : $dm = 0;
	if (mysql_num_rows($rmsg) == 0) $kn = "<table align=center cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmainobg align=center>Keine Beiträge vorhanden</td></tr></table>";
	else
	{
		while($msg=mysql_fetch_assoc($rmsg))
		{
			if ($dm == 1) $del = " <a href=main.php?page=comm&section=allykn&del=".$msg[id]." onMouseOver=cp('del".$i."','buttons/x2') onMouseOut=cp('del".$i."','buttons/x1')><img src=".$grafik."/buttons/x1.gif name=del".$i." border=0 title='Löschen'></a>";
			$msg[status] == 9 ? $id = "<strong>NPC</strong>" : $id = "(".$msg[user_id].")";
			$msg[picture] != "" ? $kngfx = $msg[picture] : $kngfx = $grafik."/rassen/".$msg[rasse]."kn.gif";
			$kn .= "<table width=800 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
			<tr>
				<td class=tdmainobg width=70 align=center rowspan=3>
				<img src=".$kngfx." width=64 height=64 border=0>
				<font style=\"font: 9px sans-serif; color:Gray;\">".$msg[id]."</font></td>
			    <td colspan=2 align=center class=tdmain width=730>".stripslashes($msg[subject])."</td>
			</tr>
			<tr>
			    <td width=610 valign=top class=tdmainobg>".$rasse."".stripslashes($msg[user])." ".$id." ".$edit." ".$del." <a href=main.php?page=hally&section=sinfo&id=".$msg[user_id]." onMouseOver=cp('sinfo".$i."','buttons/info2') onMouseOut=cp('sinfo".$i."','buttons/info1')><img src='".$grafik."/buttons/info1.gif' name=sinfo".$i." border=0></a> <a href=main.php?page=comm&section=allykn&setmark=".$msg[id]."  onMouseOver=cp('lese".$i."','buttons/lese2') onMouseOut=cp('lese".$i."','buttons/lese1')><img src=".$grafik."/buttons/lese1.gif name=lese".$i." border=0 title='Lesezeichen setzen'></a></td>
			    <td width=120 class=tdmain valign=top>".date("d.m.",$msg[date_tsp]).(date("Y",$msg[date_tsp])+375).date(" H:i",$msg[date_tsp])."</td>
			</tr>
			<tr>
			    <td colspan=2 class=tdmain width=800 height=50 valign=top>".stripslashes(nl2br($msg[text]))."</td>
			</tr>
			</table></td>
			</tr></table><br>";
			$i++;
		}
	}
	if (!$back) $back = $msg[id];
	echo "<table border=1 bordercolor=#000000 bgcolor=#262323>
	<tr><td class=tdmain><a href=main.php?page=comm&section=allykn&mark=".$forward." onMouseOver=cp('vor','buttons/b_from2') onMouseOut=cp('vor','buttons/b_from1')><img src='".$grafik."/buttons/b_from1.gif' name=vor border=0 title='vorblättern'></a> <a href=main.php?page=comm&section=allykn&mark=".$back." onMouseOver=cp('zuruck','buttons/b_to2') onMouseOut=cp('zuruck','buttons/b_to1')><img src='".$grafik."/buttons/b_to1.gif' name=zuruck border=0 title='zurückblättern'></a> <a href=main.php?page=comm&section=allywrite onMouseOver=cp('write','buttons/knedit2') onMouseOut=cp('write','buttons/knedit1')><img src='".$grafik."/buttons/knedit1.gif' name=write border=0 title='Beitrag schreiben'></a></td></tr>
	</table><br>".$kn."<table cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr><td class=tdmain><a href=main.php?page=comm&section=allykn&mark=".$forward." onMouseOver=cp('vora','buttons/b_from2') onMouseOut=cp('vora','buttons/b_from1')><img src='".$grafik."/buttons/b_from1.gif' name=vora border=0 title='vorblättern'></a> <a href=main.php?page=comm&section=allykn&mark=".$back." onMouseOver=cp('zurucka','buttons/b_to2') onMouseOut=cp('zurucka','buttons/b_to1')><img src='".$grafik."/buttons/b_to1.gif' name=zurucka border=0 title='zurückblättern'></a> <a href=main.php?page=comm&section=allywrite onMouseOver=cp('writea','buttons/knedit2') onMouseOut=cp('writea','buttons/knedit1')><img src='".$grafik."/buttons/knedit1.gif' name=writea border=0 title='Beitrag schreiben'></a></td></tr>
	</table>";
}
elseif ($section == "allywrite")
{
	if ($myUser->ually == 0) exit;
	echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <a href=?page=comm&section=allykn>Allianz Kommunikations Netzwerk</a> / <strong>Beitrag schreiben</strong></td>
		</tr>
		</table><br>";
		if ($errormsg) echo "<table width=300 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$errormsg."</td></tr></table><br>";
		echo "<table width=500 cellspacing=1 cellpadding=1 bgcolor=#262323>
		<form action=main.php method=post>
			<input type=hidden name=page value=comm>
			<input type=hidden name=section value=allywrite>
			<input type=hidden name=sent value=1>
			<tr>
				<td class=tdmain>Überschrift: <input class=text type=text name=subject maxlength=255 size=40 value='".$subject."'></td>
			</tr>
			<tr>
				<td class=tdmain><textarea cols=60 rows=15 name=message>".$message."</textarea></td>
			</tr>
			<tr>
				<td class=tdmain><input class=button type=submit value=Hinzufügen> <input class=button type=reset></td>
			</tr></form>
			</table>";
}
elseif ($section == "editcontacts")
{
	if ($action == "del" && $recipient > 0) $return = $myComm->delcontact($recipient,$user);
	if ($action == "dellist") $return = $myComm->dellist($user);
	if ($action == "add" && $recipient > 0 && ($type == "user" || $type == "ally")) $return = $myComm->addcontact($recipient,$status,$type);
	echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <strong>Kontakte</strong></td>
		</tr>
		</table><br>";
	if ($return) echo "<table width=250 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$return[msg]."</td></tr></table><br>";
	echo "<table width=500 cellspacing=1 cellpadding=1 bgcolor=#262323>
	<form action=main.php method=post>
	<input type=hidden name=page value=comm>
	<input type=hidden name=section value=editcontacts>
	<input type=hidden name=action value=add>
	<tr>
		<td class=tdmainobg colspan=2><input type=radio name=type value=user CHECKED> User <input type=radio name=type value=ally> Allianz 
		ID: <input type=text class=text size=5 name=recipient>
		Verhältnis
		<select name=status>
		<option value=0>Neutral</option>
		<option value=1>Freund</option>
		<option value=2>Feind</option>
		</select>
		<input type=submit value=Hinzufügen class=button> <a href=?page=comm&section=editcontacts&action=dellist onmouseover=cp('dela','buttons/x2') onmouseout=cp('dela','buttons/x1')><img src=".$grafik."/buttons/x1.gif title='Liste leeren' name=dela border=0></a></td>
	</tr>";
	$list = $myComm->getcontacts($user);
	if ($list == 0) echo "<tr><td class=tdmainobg align=center colspan=2>Keine Kontakte vorhanden</td></tr>";
	else
	{
		for ($i=0;$i<count($list);$i++)
		{
			if ($list[$i][behaviour] == 0) $status = "Neutral";
			if ($list[$i][behaviour] == 1) $status = "<font color=Green>Freund</font>";
			if ($list[$i][behaviour] == 2) $status = "<font color=Red>Feind</font>";
			echo "<tr><td width=5% class=tdmainobg><a href=main.php?page=comm&section=editcontacts&action=del&recipient=".$list[$i][recipient]." onMouseOver=document.del".$i.".src='".$grafik."/buttons/x2.gif' onMouseOut=document.del".$i.".src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del".$i." border=0 title='Löschen'></a></td>
			<td class=tdmainobg width=95%>".stripslashes($myUser->getfield("user",$list[$i][recipient]))." (ID: ".$list[$i][recipient]." - Status: ".$status.")</td></tr>";
		}
	}
	echo "</table><br>";
}
elseif ($section == "viewmsgs")
{
	echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <strong>PM-Archiv</strong></td>
		</tr>
		</table><br>";
	if ($myUser->ustatus != 8) echo "<table width=200 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>Zugriff verweigert!</td></tr></table><br>";
	else
	{
		if (!$action)
		{
			echo "<table width=200 cellspacing=1 cellpadding=1 bgcolor=#262323>
			<tr>
				<td class=tdmainobg><a href=?page=comm&section=viewmsgs&action=searchmsg>PMs durchsuchen</a><br>
				<a href=?page=comm&section=viewmsgs&action=usermsg>PMs eines Users ansehen</a></td>
			</tr>";
		}
		elseif ($action == "searchmsg")
		{
			if ($search) {
				$msg = $myComm->getpmsbystring($search);
				echo "<table width=300 cellspacing=1 cellpadding=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>Die Suche nach <b>".$search."</b> ergab ";
				$msg == 0 ? print(0) : print(count($msg));
				echo " Treffer</td></tr></table><br>";
			}
			else echo "<table width=300 cellspacing=1 cellpadding=1 bgcolor=#262323>
			<form action=main.php method=post>
						 <input type=hidden name=page value=comm>
						 <input type=hidden name=section value=viewmsgs>
						 <input type=hidden name=action value=searchmsg>
						 <tr><td class=tdmainobg>Suchbegriff eingeben: <input type=text class=text size=10 name=search> <input type=submit class=button value=Search></td></tr></form></table>";
			if ($msg != 0)
			{
				for ($i=0;$i<count($msg);$i++)
				{
					$user1 = $myUser->getuserbyid($msg[$i][sender]);
					$user2 = $myUser->getuserbyid($msg[$i][recipient]);
					echo "<br><table width=500 cellspacing=1 cellpadding=1 bgcolor=#262323>
						  <tr>
						  	<td class=tdmainobg width=450>von/an: ".$user1[user]."/".$user2[user]."</td>
							<td class=tdmain>Zeit</td>
							<td class=tdmainobg>".date("d.m.Y H:i",$msg[$i][date_tsp])."</td>
						  </tr>
						  <tr>
						  	<td class=tdmainobg colspan=3>";
							$test = explode(" ",$search);
							if (count($test) > 1)
							{
								$newmsg = $msg[$i][message];
								for ($j=0;$j<count($test);$j++) $newmsg = str_replace($test[$j],"<b>".$test[$j]."</b>",$newmsg);
							}
							else $newmsg = str_replace($search,"<b>".$search."</b>",$msg[$i][message]);
							echo $newmsg."</td>
						  </tr>
						  </table>";
				}
			}
		}
		elseif ($action == "usermsg")
		{
			if ($search)
			{
				$msg = $myComm->getpmsbyuserid($search);
				echo "<tr><td class=tdmainobg>Nachrichten von UserID <b>".$search."</b> (".$myUser->getfield("user",$search)."): ";
				if ($msg == 0) echo 0;
				else echo count($msg);
				echo "</td></tr></table><br>";
			}
			else
			{
				echo "<table width=300 cellspacing=1 cellpadding=1 bgcolor=#262323>
					<form action=main.php method=post>
					<input type=hidden name=page value=comm>
					<input type=hidden name=section value=viewmsgs>
					<input type=hidden name=action value=usermsg>
					<tr><td class=tdmainobg>UserId eingeben: <input type=text class=text size=10 name=search> <input type=submit class=button value=Anzeige></td></tr></form></table>";
			}
			if ($msg != 0)
			{
				for ($i=0;$i<count($msg);$i++)
				{
					$user1 = $myUser->getuserbyid($msg[$i][sender]);
					$user2 = $myUser->getuserbyid($msg[$i][recipient]);
					echo "<br><table width=600 cellspacing=1 cellpadding=1 bgcolor=#262323>
						  <tr>
						  	<td class=tdmainobg width=450>von/an: ".$user1[user]."/".$user2[user]."</td>
							<td class=tdmain>Zeit</td>
							<td class=tdmainobg>".date("d.m.Y H:i",$msg[$i][date_tsp])."</td>
						  </tr>
						  <tr>
						  	<td class=tdmainobg colspan=3>".$msg[$i][message]."</td>
						  </tr>
						  </table>";
				}
			}
		}
	}
}
elseif ($section == "outpm") 
{
	if ($action == "delall") $myComm->delalloutpms($cat,$user);
	if (!$begin) $begin = 0;
	if ($del) $myComm->delpm($del,$user);
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <strong>Postausgang</strong></td>
		</tr>
		</table><br>";
	$begin ? $msg = $myComm->getoutpmsmark($begin,$user,$cat) : $msg = $myComm->getoutpms($cat);
	$i=0;
	echo "<table width=100%>
	<tr>
		<td valign=top>";
		if ($msg == 0) echo "<table width=600 align=center bgcolor=#26232><tr><td align=center class=tdmain>Keine Nachrichten vorhanden</td></tr></table>";
		else
		{
			while($i<count($msg))
			{
				$msg[$i][status] == 9 ? $npc = "<strong>NPC</strong> " : $npc = "(".$msg[$i][recipient].")";
				echo "<table width=600 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
				<tr>
				    <td width=60% class=tdmainobg>an: ".stripslashes($msg[$i][user])." ".$npc." <a href=main.php?page=comm&section=outpm&del=".$msg[$i][id]." onMouseOver=document.del".$i.".src='".$grafik."/buttons/x2.gif' onMouseOut=document.del".$i.".src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del".$i." border=0 title='Löschen'></a></td>
				    <td class=tdmain>Zeit:</td>
				    <td class=tdmainobg>".date("d.m.",$msg[$i][date_tsp]).(date("Y",$msg[$i][date_tsp])+375).date(" H:i",$msg[$i][date_tsp])."</td>
				</tr>
				<tr>
				    <td colspan=3 class=tdmain>".stripslashes(nl2br($msg[$i][message]))."</td>
				</tr>
				</table><br>";
				$i++;
			}
		}
		echo "</td>
		<td valign=top>
		<table bgcolor=#262323 width=100>
		<tr><td class=tdmain><strong>Steuerung</strong></td></tr>";
		$vor = $begin - 10;
		$back = $begin + 10;
		if ($vor < 0) $vor = 0;
		if (count($msg) < 10) $back = $begin;
		echo "<tr><td class=tdmainobg><a href=main.php?page=comm&section=outpm&begin=".$vor." onMouseOver=document.vora.src='".$grafik."/buttons/b_from2.gif' onMouseOut=document.vora.src='".$grafik."/buttons/b_from1.gif'><img src='".$grafik."/buttons/b_from1.gif' name=vora border=0 title='vorblättern'></a>
		<a href=main.php?page=comm&section=outpm&begin=".$back." onMouseOver=document.zurucka.src='".$grafik."/buttons/b_to2.gif' onMouseOut=document.zurucka.src='".$grafik."/buttons/b_to1.gif'><img src='".$grafik."/buttons/b_to1.gif' name=zurucka border=0 title='zurückblättern'></a>
		<a href=?page=comm&section=writepm onMouseOver=document.writea.src='".$grafik."/buttons/knedit2.gif' onMouseOut=document.writea.src='".$grafik."/buttons/knedit1.gif'><img src='".$grafik."/buttons/knedit1.gif' name=writea border=0 title='Neue Nachricht schreiben'></a>
		<a href=main.php?page=comm&section=outpm&action=delall onMouseOver=document.del.src='".$grafik."/buttons/x2.gif' onMouseOut=document.del.src='".$grafik."/buttons/x1.gif'><img src='".$grafik."/buttons/x1.gif' name=del border=0 title='Alle Nachrichten löschen'></a><br><br>
		<a href=?page=comm&section=pm>Posteingang</a></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>";
}
elseif ($section == "knanl")
{
	echo "<table width=100% align=Center cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr>
			<td class=tdmain>/ <a href=?page=comm>Kommunikation</a> / <strong>KN-Leitfaden</strong></td>
		</tr>
		</table><br>
	<table cellspacing=1 cellpadding=1 bgcolor=#262323>
		<tr><td class=tdmain><b>Leitfaden für das Kommunikationsnetzwerk (KN)</b></td></tr>
		<tr><td class=tdmainobg>Das Kommunikationsnetzwerk stellt eine der wichtigsten Funktionen im Spiel dar. Die meisten wichtigen Ereignisse spielen sich hier ab.
		Aufgrund der großen Bedeutung des KN wurde dieser Leitfaden erstellt, um Neulingen und anderen zu helfen, sich damit zurechtzufinden.<br><br>
		1. RPG<br>
		Im Kommunikationsnetzwerk findet RPG (Rollenspiel) statt. Jeder Spieler schlüpft hier in die Rolle, die er sich für das Spiel ausgesucht hat. Diktatoren, Volkspräsidenten, ja ganze Imperien alle treffen hier aufeinander.<br><br>
		2. Das KN ist kein Forum<br>
		Alles, was ausserhalb der RPG-Ebene stattfindet, ist im KN fehlt am Platz. Bugberichte, Offtopic, etc gehören ins Forum.<br><br>
		3. Gewählte Ausdrucksweise<br>
		Jede Nachricht im KN ist von allen Spielern in ihren Rollen lesbar.Daher ist auf eine möglichst korrekte Rechtschreibung und Grammatik zu achten. Groß- und Kleinschreibung, Satzzeichen, etc. sind keine Schikane, sondern für einen gepflegten Umgang im KN absolut notwendig.
		Sich zu bemühen, korrekte, verständliche Sätze zu machen, sollte für jeden eine Selbstverständlichkeit sein.<br><br>
		4. Respekt<br>
		Jedem Spieler ist ein Grundmaß an Respekt gegenüber zu bringen. Daher sind zB <b>persönliche</b> Beleidigungen zu unterlassen.<br><br>
		5. Offiziell / Inoffiziell<br>
		Beim Erstellen einer Nachricht steht zur Auswahl, ob sie offiziell oder inoffiziell sein soll.<br>
		Offiziell bedeutet, es handelt sich um eine öffentliche Stellungnahme, man spricht zum gesamten Sektor.<br>
		Inoffiziell ist für alles, das nicht öffentlich sichtbar ist. Dies können zB Ereignisse innerhalb eines RPG sein, die auf Planeten oder Schiffen stattfinden, und Hintergründe erläutern.<br><br>
		Dieser Leitfaden erscheint automatisch beim ersten Aufruf des Kommunikationsnetzwerks. Danach ist er per Link im Bildschrim Kommunikation erreichbar.</td></tr>
		</table>";
	if ($myUser->uknanl == 1) echo "<br><form action=main.php action=post><input type=hidden name=page value=comm><input type=hidden name=action value=knanlr><input type=submit value=Bestätigung class=button></form>";
}
?>
