<?php
$grafik = "http://gfx.stuniverse.de";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Star Trek Universe - Test</title>
</head>
<link rel="STYLESHEET" type="text/css" href="http://www.stuniverse.de/gfx/css/style.css">
<body bgcolor="#000000" text="#FFFFFF" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<?php
	if ($section == page1)
	{
		echo $choice[1]." gewählt!<br>";
		echo "<form action=ttest.php method=post>
		<input type=hidden name=section value=page2>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=choice[1] value=".$choice[1].">
		<input type=radio name=choice[2] value=1>
		<input type=radio name=choice[2] value=2>
		<input type=radio name=choice[2] value=3>
		<input type=submit value=abschicken class=button>
		</form>";
	}
	elseif ($section == page2)
	{
		echo $choice[1]." gewählt!<br>";
		echo $choice[2]." gewählt!<br>";
		echo "Punkte: ".($choice1+$choice2);
	}
	else
	{

	echo "<form action=ttest.php method=post>
	<input type=hidden name=section value=page2>
	<table width=100% cols=3>
	<tr>
	<td width=100></td>
	<td>
		<br>
		<table width=100% cols=2>
		<tr>
			<td class=tdmain>Welches ist der beste Star-Trek-Film?</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[1] value=1> Star Trek: Nemesis</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[1] value=2> Star Trek VI: Das unentdeckte Land</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[1] value=3> Star Trek: Der erste Kontakt</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[1] value=4> Star Trek V: Am Rande des Universums</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[1] value=5> Star Trek III: Die Rache der Sith</a></td>
		</tr>
		</table>
		<br>
		<table width=100% cols=2>
		<tr>
			<td class=tdmain>Welche der Serien ist die Beste?</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[2] value=1> TOS</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[2] value=2> Next Generation</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[2] value=3> Deep Space Nine</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[2] value=4> Voyager</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[2] value=5> Enterprise</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[2] value=6> Wie, Serie? Das sind historische Aufzeichnungen!</a></td>
		</tr>
		</table>
		<br>
		<table width=100% cols=2>
		<tr>
			<td class=tdmain>Der beste Next-Generation-Schauspieler?</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[3] value=1> Patrick Stewart</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[3] value=2> Brent Spiner</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[3] value=5> William T. Riker</a></td>
		</tr>
		<tr>
			<td class=tdmainobg> <input type=radio name=choice[3] value=4> Die eine Tussi da</a></td>
		</tr>
		</table>
		<br>
		<table width=100% cols=2>
		<tr>
			<td class=tdmain colspan=2>Wer passt hier nicht?</a></td>
		</tr>
		<tr>
			<td class=tdmainobg width=200> <input type=radio name=choice[4] value=1> <img src=http://home.arcor.de/omega-sektion/riker.gif> Commander Riker</a></td>
			<td class=tdmainobg> <input type=radio name=choice[4] value=2> <img src=http://home.arcor.de/omega-sektion/janeway.gif> Captain Janeway</a></td>
		</tr>
		<tr>
			<td class=tdmainobg width=200> <input type=radio name=choice[4] value=3> <img src=http://home.arcor.de/omega-sektion/sisko.gif> Captain Sisko</a></td>
			<td class=tdmainobg> <input type=radio name=choice[4] value=4> <img src=http://home.arcor.de/omega-sektion/piett.gif> Captain Piett</a></td>
		</tr>
		</table>

		<br>
		<center><input type=submit value=abschicken class=button></center>
	</td>
	<td width=100></td></tr>
	</form>";
	}
?>
</body>
</html>
