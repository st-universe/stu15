<?php
if ($login != 1) exit;
if ($action == "catread") $myComm->markcatasread($cat,$user);
if (($myComm->checknewmsg($user) == 0) && !$tpa) exit;
?>
<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
<tr>
	<td class=tdmain align=Center><strong>Nachrichten</strong></td>
</tr>
<tr>
	<td class=tdmainobg>
	<table cellspacing=1 cellpadding=1>
	<?php
	$cated = $myComm->getpmcatmsg(1,$user);
	if ($cated['new'] > 0) $new = "<font color=Red>".$cated['new']."</font>";
	else $new = 0;
	echo "<tr>
		<td class=tdmainobg><a href=main.php?page=comm&section=pm&cat=1 target=main>Privat</a></td>
		<td class=tdmainobg>".$cated[ges]."/".$new."</td>
		<td class=tdmainobg><a href=main.php?page=npm&action=catread&cat=1 onMouseOver=document.del1.src='".$grafik."/buttons/x2.gif' onMouseOut=document.del1.src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del1 border=0 alt='Als gelesen markieren'></a></td>
	</tr>";
	$cated = $myComm->getpmcatmsg(2,$user);
	if ($cated['new'] > 0) $new = "<font color=Red>".$cated['new']."</font>";
	else $new = 0;
	echo "<tr>
		<td class=tdmainobg><a href=main.php?page=comm&section=pm&cat=2 target=main>Schiffe</a></td>
		<td class=tdmainobg>".$cated[ges]."/".$new."</td>
		<td class=tdmainobg><a href=main.php?page=npm&action=catread&cat=2 onMouseOver=document.del2.src='".$grafik."/buttons/x2.gif' onMouseOut=document.del2.src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del2 border=0 alt='Als gelesen markieren'></a></td>
	</tr>";
	$cated = $myComm->getpmcatmsg(3,$user);
	if ($cated['new'] > 0) $new = "<font color=Red>".$cated['new']."</font>";
	else $new = 0;
	echo "<tr>
		<td class=tdmainobg><a href=main.php?page=comm&section=pm&cat=3 target=main>Handel</a></td>
		<td class=tdmainobg>".$cated[ges]."/".$new."</td>
		<td class=tdmainobg><a href=main.php?page=npm&action=catread&cat=3 onMouseOver=document.del3.src='".$grafik."/buttons/x2.gif' onMouseOut=document.del3.src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del3 border=0 alt='Als gelesen markieren'></a></td>
	</tr>";
	$cated = $myComm->getpmcatmsg(4,$user);
	if ($cated['new'] > 0) $new = "<font color=Red>".$cated['new']."</font>";
	else $new = 0;
	echo "<tr>
		<td class=tdmainobg><a href=main.php?page=comm&section=pm&cat=4 target=main>Kolonien</a></td>
		<td class=tdmainobg>".$cated[ges]."/".$new."</td>
		<td class=tdmainobg><a href=main.php?page=npm&action=catread&cat=4 onMouseOver=document.del4.src='".$grafik."/buttons/x2.gif' onMouseOut=document.del4.src='".$grafik."/buttons/x1.gif'><img src=".$grafik."/buttons/x1.gif name=del4 border=0 alt='Als gelesen markieren'></a></td>
	</tr>";
	?>
	</table>
	</td>
</tr>
<tr>
	<td class=tdmainobg align=center>[<a href=static/leftbottom.php>Schlieﬂen</a>]</td>
</tr>
</table><br>
<?php
if ($tid) $id = $tid;
if ($tsec) $section = $tsec;
if ($tcol) $col = $tcol;
if ($tfie) $field = $tfie;
if ($tcla) $class = $tcla;
if ($tsid) $shipid = $tsid;
if ($tclai) $classid = $tclai;
if ($tpa == "shiphelp") $incla = "shiphelp.php";
if ($tpa == "help") $incla = "help.php";
if ($tpa == "showinfo") $incla = "showinfo.php";
if ($incla) include_once($incla);
?>