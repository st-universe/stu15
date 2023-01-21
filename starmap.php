<?php
if (!$section)
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ <strong>Sternenkarte</strong></td>
	</tr>
	</table><br>
	<table cellspacing=1 cellpadding=1 bgcolor=#262323>";
	$map = $myMap->getmapsectors();
	$j=10;
	$x = $mapfields[max_x]/20;
	$y = $mapfields[max_y]/20;
	$xed=1;
	$yed=1;
	echo "<tr><td class=tdmainobg width=40 height=40 valign=middle align=center>x/y</td>";
	for($i=1;$i<=$x;$i++)
	{
		echo "<td class=tdmain width=40 height=40>".$xed."/".($xed+19)."</td>";
		$xed+=20;
	}
	for ($i=0;$i<count($map);$i++)
	{
		if ($j == 10)
		{
			echo "</tr><tr><td class=tdmain width=40 height=40>".$yed."/".($yed+19)."</td>";
			$yed+=20;
			$j=0;
		}
		if ($map[$i][hide] == 0)
		{
			if ($myMap->checksektor($map[$i][id]) == 1) $lk = "<font color=green>".$map[$i][id]."</font>";
			else $lk = $map[$i][id];
			echo "<td class=tdmainobg width=40 height=40 align=center><a href=?page=starmap&section=showsektor&id=".$map[$i][id].">".$lk."</a></td>";
		}
		else echo "<td class=tdmainobg width=40 height=40>&nbsp;</td>";
		$j++;
	}
	echo "</tr></table>";
}
elseif ($section == "showsektor")
{
	echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ <a href=?page=starmap>Sternenkarte</a> / <strong>Sektor ".$id."</strong></td>
	</tr>
	</table><br>";
	$return = $myMap->rendersektor($id);
	if (is_numeric($return)) echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>Du besitzt diesen Teil der Sternenkarte nicht</td></tr></table>";
	else echo $return;
}
?>