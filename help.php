<?php
if ($help == "c") $helptxt = "Das C steht für die Crew die sich auf dem Schiff befindet";
elseif ($help == "r") $helptxt = "Die Zahl steht für die Anzahl der Runden, für die der jeweilige Rohstoff noch reicht. Erste Zahl: Nahrung - Zweite Zahl: Deuterium";
echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=center><strong>Hilfe</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg align=Center>".$helptxt."</td>
	</tr>
	<tr>
		<td class=tdmain align=center>[<a href=static/leftbottom.php>OK</a>]</td>
	</tr>
</table>";
?>