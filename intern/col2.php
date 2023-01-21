<?php

	include_once("inc/gencol.inc.php");

	echo "<body bgcolor=#000000>";
	$gfx = "http://www.stuniverse.de/intern/v2img";
	if ($c == 0) $c = 1;

	// --------------------------

	$size = getsizebyclass($c);
	$f = generatefields($c);
	if ($size[g] == 1) $g = generategroundfromfields($c,$f);

	// --------------------------
	for ($i=1;$i<21;$i++) {
		echo "<a href='http://www.stuniverse.de/intern/col2.php?c=".$i."'><img src='".$gfx."/planets/".$i.".gif' border=0></a>";
	}
	echo "<br><br>";

	$rowcount = 1;
	for ($i=0;$i<($size[w] * $size[h]);$i++) { 
		if ($i == ($rowcount * ($size[w]))) { 
			echo "<br>";
			$rowcount = $rowcount + 1;
		}
		echo "<img src='".$gfx."/fields/".$f[$i].".gif'> ";
	}
	echo "<br>";
	if ($size[g] == 1) 
	{
		$rowcount = 1;
		for ($i=0;$i<($size[w] * ($size[h]/2));$i++) { 
			if ($i == ($rowcount * ($size[w]))) { 
				echo "<br>";
				$rowcount = $rowcount + 1;
			}
			echo "<img src='".$gfx."/fields/".$g[$i].".gif'> ";
		}
	}
?>