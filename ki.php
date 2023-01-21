<?php
function rendermap($arr,$x1,$y1,$x2,$y2)
{
	global $myDB;
	if ($y2>$y1)
	{
		$y = $y2-$y1;
		$qy = $y1;
	}
	else
	{
		$y = $y1-$y2;
		$qy = $y2;
	}
	for($i=0;$i<=$y;$i++)
	{
		$tmp = mysql_query("SELECT coords_x,coords_y,type FROM stu_map_fields WHERE coords_x BETWEEN ".$x1." AND ".($x2+1)." AND coords_y=".($qy+$i)." ORDER BY coords_x",$myDB->dblink);
		for ($j=0;$j<mysql_num_rows($tmp);$j++) $fields[$i][] = mysql_fetch_array($tmp);
	}
	if ($x2 > $x1) $x = $x2-$x1;
	else $x = $x1-$x2;
	$map = "<table cellpadding=0 cellspacing=0 border=1>";
	$map .= "<tr><td></td>";
	for ($j=0;$j<$x;$j++) $map .= "<td>".$fields[0][$j][coords_x]."</td>";
	$map .= "</tr>";
	for ($i=0;$i<=$y;$i++)
	{
		$map .= "<tr><td align=center>".($i+$y1)."</td>";
		for ($j=0;$j<=($x+1);$j++)
		{
			if ($arr[$fields[$i][$j][coords_x].$fields[$i][$j][coords_y]] == 1) $border = "Red";
			else $border = "#808080";
			$map .= "<td bordercolor=".$border."><img src=gfx/map/".$fields[$i][$j][type].".gif></td>";
		}
		$map .= "</tr>";
	}
	$map .= "</table>";
	echo $map;
}

include_once("inc/config.inc.php");
include_once("class/db.class.php");
$myDB = new db;

$i = 0;
$arrival = 0;
$x1 = 20;
$y1 = 20;
$x2 = 120;
$y2 = 120;
$posx = $x1;
$posy = $y1;

while($arrival==0)
{
	$arr[$posx.$posy] = 1;
	if ($posx > $x2)
	{
		$cx = $myDB->query("SELECT coords_x FROM stu_map_fields WHERE type!=1 AND coords_y=".$posy." AND coords_x<".$posx." ORDER BY coords_x DESC LIMIT 1",1);
		$part = "BETWEEN ".$cx." AND ".$posx;
	}
	if ($posx < $x2)
	{
		$cx = $myDB->query("SELECT coords_x FROM stu_map_fields WHERE type!=1 AND coords_y=".$posy." AND coords_x>".$posx." ORDER BY coords_x ASC LIMIT 1",1);
		$part = "BETWEEN ".$posx." AND ".$cx;
	}
	echo $cx."<br>";
	$fieldsx = $myDB->query("SELECT COUNT(id) FROM stu_map_fields WHERE coords_y=".$posy." AND coords_x ".$part,1);
	if ($posy > $y2)
	{
		$cy = $myDB->query("SELECT coords_y FROM stu_map_fields WHERE type!=1 AND coords_x=".$posx." AND coords_y<".$posy." ORDER BY coords_y DESC LIMIT 1",1);
		$part = "BETWEEN ".$cy." AND ".$posy;
	}
	if ($posy < $y2)
	{
		$cy = $myDB->query("SELECT coords_y FROM stu_map_fields WHERE type!=1 AND coords_x=".$posx." AND coords_y>".$posy." ORDER BY coords_y ASC LIMIT 1",1);
		$part = "BETWEEN ".$posy." AND ".$cy;
	}
	$fieldsy = $myDB->query("SELECT COUNT(id) FROM stu_map_fields WHERE type=1 AND coords_y=".$posx." AND coords_x ".$part,1);
	if ($x2 == $posx && $y2 != $posy) $fieldsx = 0;
	if ($x2 != $posx && $y2 == $posy) $fieldsy = 0;
	echo $fieldsx." - ".$fieldsy."<br>";
	if ($fieldsx > $fieldsy)
	{
		if ($posx > $x2) $posx--;
		if ($posx < $x2) $posx++;
	}
	elseif ($fieldsx < $fieldsy)
	{
		if ($posy > $y2) $posy--;
		if ($posy < $y2) $posy++;
	}
	else
	{
		if ($x2 != $posx)
		{
			if ($x2 < $posx) $posx--;
			if ($x2 > $posx) $posx++;
		}
		elseif ($y2 != $posy)
		{
			if ($y2 < $posy) $posy--;
			if ($y2 > $posy) $posy++;
		}
		else break;
	}
	$i++;
	if ($i == 200) break;
}
rendermap($arr,$x1,$y1,$x2,$y2);
?>