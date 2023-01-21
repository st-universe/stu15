<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.01 Transitional//EN>
<html>
<head>
<title>Starmap</title>
</head>
<link rel=STYLESHEET type=text/css href=../gfx/css/style.css>
<?php
if (!$wese)
{
	echo "<table cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ Sternenkarte / <strong>Sektorwahl</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg><a href=starmap.php?wese=1>Sektor 1</a> - <a href=starmap.php?wese=2>Sektor 2</a></td>
	</tr>
	</table><br>";
	exit;
}
include_once("../inc/config.inc.php");
include_once("../class/db.class.php");
$myDB = new db;
include_once("class/map.class.php");
$myMap = new map;
include_once("class/colony.class.php");
$myColony = new colony;
if (!$section)
{
	echo "<table width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr>
		<td width=100% class=tdmain>/ <a href=starmap.php>Sternenkarte</a> / <b>Sektor ".$wese."</b></td>
	</tr>
	</table><br>
	<table border=0 cellspacing=1 cellpadding=1 bgcolor=#26232>";
	$t = 20;
	if ($wese == 1)
	{
		$maxx = 300;
		$maxy = 200;
	}
	elseif ($wese == 2)
	{
		$maxx = $mapfields2[max_x];
		$maxy = $mapfields2[max_y];
	}
	echo "<tr><td class=tdmainobg align=Center valign=middle width=70 height=50>x/y</td>";
	for ($j=0;$j<$maxx/$t;$j++)
	{
		echo "<td class=tdmain align=Center width=70>".($j*$t+1)." - ".($j*$t+$t)."</td>";
	}
	echo "</tr>";
	for ($i=0;$i<$maxy/$t;$i++)
	{
		echo "<tr>
		<td class=tdmain width=70 height=50>".($i*$t+1)."<br><br>".($i*$t+$t)."</td>";
		for ($j=0;$j<$maxx/$t;$j++)
		{
			echo "<td align=center class=tdmainobg><a href=starmap.php?section=showsektor&wese=".$wese."&x1=".($j*$t+1)."&x2=".($j*$t+$t)."&y1=".($i*$t+1)."&y2=".($i*$t+$t).">Click</a></td>";
		}
		echo "</tr>";
	}
	echo "
	<tr>
		<td colspan=".($maxx/$t+1)." class=tdmainobg><a href=starmap.php?section=showall&wese=".$wese.">Gesamtansicht</a></td>
	</tr>
	</table>";
} elseif ($section == "showsektor") {
	if ($action == "addplanet") $result = $myColony->addplanet($x,$y,$type,$wese);
	if ($action == "editfield") $myDB->query("UPDATE stu_map_fields SET type=".$type." WHERE coords_x=".$x." AND coords_y=".$y." AND wese=".$wese);
	if ($action == "editrace") $myDB->query("UPDATE stu_map_fields SET race=".$race." WHERE coords_x=".$x." AND coords_y=".$y." AND wese=".$wese);
	echo $result[msg]."<br>";
	$span=0;
	if ($wese == 1)
	{
		$maxx = 300;
		$maxy = 200;
	}
	elseif ($wese == 2)
	{
		$maxx = $mapfields2[max_x];
		$maxy = $mapfields2[max_y];
	}
	if ($x2-$x1 > 25) $x2 = 25;
	if ($y2-$y1 > 25) $y2 = 25;
	if (($x1 > 1) && ($x2 > 1)) {
		if ($x1-20 < 1) {
			$newx1 = 1;
			$newx2 = 20;
		} else {
			$newx1 = $x1-20;
			$newx2 = $x1;
		}
		$span++;
		$links = "<td class=tdmainobg rowspan=".($x2+1)."><a href=?page=starmap&section=showsektor&x1=".$newx1."&x2=".$newx2."&y1=".$y1."&y2=".$y2."&wese=".$wese.">links</a></td>
		<td class=tdmainobg rowspan=".($x2+1).">&nbsp;</td>";
	}
	if (($x1 < $maxx) && ($x2 < $maxx)) {
		if ($x2+20 > $maxx) {
			$newx1 = $x1+20;
			$newx2 = $maxx;
		} else {
			$newx1 = $x1+20;
			$newx2 = $x2+20;
		}
		$span++;
		$rechts = "<td class=tdmainobg rowspan=".($x2+1)."><a href=?page=starmap&section=showsektor&x1=".$newx1."&x2=".$newx2."&y1=".$y1."&y2=".$y2."&wese=".$wese.">rechts</a></td>
		<td class=tdmainobg rowspan=".($x2+1).">&nbsp;</td>";
	}
	for ($i=$x1;$i<=$x2;$i++) $span++;
	if (($y1 > 1) && ($y2 > 1)) {
		if ($y1-20 < 1) {
			$newy1 = 1;
			$newy2 = 20;
		} else {
			$newy1 = $y1-20;
			$newy2 = $y1;
		}
		$hoch = "<tr><td class=tdmainobg colspan=".($span+1)." align=center><a href=?page=starmap&section=showsektor&x1=".$x1."&x2=".$x2."&y1=".$newy1."&y2=".$newy2."&wese=".$wese.">hoch</a></td></tr>";
	}
	if (($y1 < $mapfields[max_y]) && ($y2 < $maxy)) {
		if ($y2+20 > $maxy) {
			$newy1 = $y1+20;
			$newy2 = $maxy;
		} else {
			$newy1 = $y2;
			$newy2 = $y2+20;
		}
		$runter = "<tr><td class=tdmainobg colspan=".($span+1)." align=center><a href=?page=starmap&section=showsektor&x1=".$x1."&x2=".$x2."&y1=".$newy1."&y2=".$newy2."&wese=".$wese.">runter</a></td></tr>";
	}
	echo "<table cellspacing=1 cellpadding=1 bgcolor=#262323 width=100%>
	<tr>
		<td width=100% class=tdmain>/ <a href=starmap.php>Sternenkarte</a> / <a href=starmap.php?wese=".$wese.">Sektor ".$wese."</a> / <b>Anzeige ".$x1." - ".$x2." / ".$y1." - ".$y2."</b></strong></td>
	</tr>
	</table><br><table>
	".$hoch."
	<tr>".$links."
	<td class=tdmain></td>";
	$bla = $y1;
	for ($i=$x1;$i<=$x2;$i++) echo "<td class=tdmain align=center>".$i."</td>";
	echo $rechts."</tr>";
	if (($x1 > $maxx) || ($x2 > $maxx) || ($y1 > $maxy) || ($y2 > $maxy)) echo "<tr><td class=Tdmain align=Center>Fehler!</td></tr>";
	else {
		while($y1<=$y2) {
			$row = $myMap->getrow($x1,$x2,$y1,$wese);
			echo "<tr><td class=tdmain height=20 width=20>".$y1."</td>";
			for ($i=0;$i<count($row);$i++) 
			{
				if ($row[$i][race] == 15) $border = "bordercolor=#424A4A style='border: 1 solid #424A4A'";
				elseif ($row[$i][race] == 10) $border = "bordercolor=#0088FF style='border: 1px solid #0088FF'";
				elseif ($row[$i][race] == 11) $border = "bordercolor=#417B40 style='border: 1px solid #417B40'";
				elseif ($row[$i][race] == 13) $border = "bordercolor=#DDDD00 style='border: 1px solid #DDDD00'";
				elseif ($row[$i][race] == 16) $border = "bordercolor=#D61FC4 style='border: 1px solid #D61FC4'";
				elseif ($row[$i][race] == 22) $border = "bordercolor=#BB60BB style='border: 1px solid #BB60BB'";
				elseif ($row[$i][race] == 24) $border = "bordercolor=#FF0000 style='border: 1px solid #FF0000'";
				elseif ($row[$i][race] == 27) $border = "bordercolor=#B54A29 style='border: 1px solid #B54A29'";
				elseif ($row[$i][race] == 99) $border = "bordercolor=#AEAEAE style='border: 1px solid #AEAEAE'";
				else $border = "style='border: 1px solid #000000'";
				echo "<td class=tdmain height=20 width=20 ".$border."><a href=starmap.php?section=editfield&x=".$row[$i][coords_x]."&y=".$row[$i][coords_y]."&x1=".$x1."&x2=".$x2."&y1=".$bla."&y2=".$y2."&wese=".$wese."><img src=../gfx/map/".$row[$i][type].".gif border=0></td>";
			}
			echo "</tr>";
			$y1++;
		}
	}
	echo $runter."</table>";
} elseif ($section == "editfield") {
	$field = $myMap->getfieldbycoords($x,$y,$wese);
	echo "<table width=100% width=50% cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr>
		<td width=50% class=tdmain align=center><strong>Feld editieren ".$x."/".$y."</strong></td>
	</tr>
	</table><br>
	<table width=50% width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr>
		<td class=tdmain align=center>Aktuell</td>
	</tr>
	<tr>
		<td class=tdmainobg>Aktueller Feldtyp <img src=../gfx/map/".$field[type].".gif></td>
	</tr>
	</table><br>
	<table width=50% width=100% cellspacing=1 cellpadding=1 bgcolor=#262323>
	<tr>
		<td class=tdmain align=center>Neues Feld</td>
	</tr>
	<tr>
		<td class=tdmainobg><a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=1&wese=".$wese."><img src=../gfx/map/1.gif border=0></a>
	    <a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=2&wese=".$wese."><img src=../gfx/map/2.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=3&wese=".$wese."><img src=../gfx/map/3.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=4&wese=".$wese."><img src=../gfx/map/4.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=5&wese=".$wese."><img src=../gfx/map/5.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=11&wese=".$wese."><img src=../gfx/map/11.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=12&wese=".$wese."><img src=../gfx/map/12.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=13&wese=".$wese."><img src=../gfx/map/13.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=14&wese=".$wese."><img src=../gfx/map/14.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=15&wese=".$wese."><img src=../gfx/map/15.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=16&wese=".$wese."><img src=../gfx/map/16.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=17&wese=".$wese."><img src=../gfx/map/17.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=19&wese=".$wese."><img src=../gfx/map/19.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=21&wese=".$wese."><img src=../gfx/map/21.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=22&wese=".$wese."><img src=../gfx/map/22.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=26&wese=".$wese."><img src=../gfx/map/26.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=27&wese=".$wese."><img src=../gfx/map/27.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=28&wese=".$wese."><img src=../gfx/map/28.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=30&wese=".$wese."><img src=../gfx/map/30.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=31&wese=".$wese."><img src=../gfx/map/31.gif border=0></a>
		<a href=?section=showsektor&action=editfield&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&type=32&wese=".$wese."><img src=../gfx/map/32.gif border=0></a></td>
	</tr>
	<form action=starmap.php method=post>
	<input type=hidden name=section value=showsektor>
	<input type=hidden name=action value=addplanet>
	<input type=hidden name=x1 value=".$x1.">
	<input type=hidden name=y1 value=".$y1.">
	<input type=hidden name=x2 value=".$x2.">
	<input type=hidden name=y2 value=".$y2.">
	<input type=hidden name=x value=".$x.">
	<input type=hidden name=y value=".$y.">
	<input type=hidden name=wese value=".$wese.">
	<tr>
		<td class=tdmainobg>Planet erstellen: 	<select name=type><option value=1>Klasse M</option>
		<option value=2>Klasse L</option>
		<option value=3>Klasse N</option>
		<option value=4>Klasse G</option>
		<option value=5>Klasse K</option>
		<option value=6>Klasse D</option>
		<option value=7>Klasse H</option>
		<option value=8>Klasse X</option>
		<option value=9>Klasse J</option></select>
	<input type=submit value=erstellen class=button></td>
	</tr>
	<tr>
		<td class=tdmainobg>Rassengebiet: <a href=?section=showsektor&action=editrace&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&race=15&wese=".$wese.">Breen</a><br>
		<a href=?section=showsektor&action=editrace&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&race=10&wese=".$wese.">Föderation</a><br>
		<a href=?section=showsektor&action=editrace&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&race=11&wese=".$wese.">Romulaner</a><br>
		<a href=?section=showsektor&action=editrace&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&race=16&wese=".$wese.">Arcadia-Bund</a><br>
		<a href=?section=showsektor&action=editrace&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&race=27&wese=".$wese.">Kzinti</a><br>
		<a href=?section=showsektor&action=editrace&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&race=99&wese=".$wese.">Neutrale-Zone</a><br>
		<a href=?section=showsektor&action=editrace&x=".$x."&y=".$y."&x1=".$x1."&x2=".$x2."&y1=".$y1."&y2=".$y2."&race=0&wese=".$wese.">Keine</a></td>
	</tr>
	</form>
	</table>";
} elseif ($section == "showall") {
	echo "<table cellpadding=0 cellspacing=0>";
	if ($wese == 1)
	{
		$maxx = 300;
		$maxy = 200;
	}
	elseif ($wese == 2)
	{
		$maxx = $mapfields2[max_x];
		$maxy = $mapfields2[max_y];
	}
	for($j=1;$j<=$maxx;$j++)
	{
		echo "<tr>";
		$row = $myMap->getrow(1,$maxx,$j,$wese);
		for ($i=0;$i<count($row);$i++)
		{
			if ($row[$i][race] == 15) $border = "bordercolor=#424A4A style='border: 1 solid #424A4A'";
			elseif ($row[$i][race] == 10) $border = "bordercolor=#0088FF style='border: 1px solid #0088FF'";
			elseif ($row[$i][race] == 11) $border = "bordercolor=#417B40 style='border: 1px solid #417B40'";
			elseif ($row[$i][race] == 13) $border = "bordercolor=#DDDD00 style='border: 1px solid #DDDD00'";
			elseif ($row[$i][race] == 16) $border = "bordercolor=#D61FC4 style='border: 1px solid #D61FC4'";
			elseif ($row[$i][race] == 22) $border = "bordercolor=#BB60BB style='border: 1px solid #BB60BB'";
			elseif ($row[$i][race] == 24) $border = "bordercolor=#FF0000 style='border: 1px solid #FF0000'";
			elseif ($row[$i][race] == 27) $border = "bordercolor=#B54A29 style='border: 1px solid #B54A29'";
			elseif ($row[$i][race] == 99) $border = "bordercolor=#AEAEAE style='border: 1px solid #AEAEAE'";
			else $border = "style='border: 1px solid #000000'";
			echo "<td ".$border."><img src=../gfx/map/".$row[$i][type].".gif width=15 height=15></td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
?>
</html>