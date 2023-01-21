<?php

	function getbasechancebyclass($c) 
	{

		if (($c == 1) || ($c == 11))
		{
			$s[count]     =  6;
			$s[1][chance] = 45;
			$s[2][chance] = 50;
			$s[3][chance] = 40;
			$s[6][chance] = 25;
			$s[5][chance] = 12;
			$s[4][chance] = 15;
			$s[1][type]   =  1;
			$s[2][type]   =  2;
			$s[3][type]   =  3;
			$s[6][type]   =  4;
			$s[5][type]   =  6;
			$s[4][type]   =  5;
		} elseif (($c == 2) || ($c == 12))
		{
			$s[count]     =  5;
			$s[1][chance] = 30;
			$s[2][chance] = 20;
			$s[3][chance] = 20;
			$s[5][chance] = 45;
			$s[4][chance] = 10;
			$s[1][type]   =  1;
			$s[2][type]   =  2;
			$s[3][type]   =  3;
			$s[5][type]   =  4;
			$s[4][type]   =  5;
		} elseif (($c == 3) || ($c == 13))
		{
			$s[count]     =  5;
			$s[1][chance] = 30;
			$s[2][chance] = 20;
			$s[3][chance] = 60;
			$s[5][chance] = 20;
			$s[4][chance] =  6;
			$s[1][type]   =  1;
			$s[2][type]   =  2;
			$s[3][type]   =  3;
			$s[5][type]   =  4;
			$s[4][type]   =  5;
		} elseif (($c == 4) || ($c == 14))
		{
			$s[count]     =  2;
			$s[1][chance] = 60;
			$s[2][chance] = 40;
			$s[1][type]   =  6;
			$s[2][type]   =  7;
		} elseif (($c == 5) || ($c == 15))
		{
			$s[count]     =  3;
			$s[1][chance] = 60;
			$s[2][chance] = 25;
			$s[3][chance] = 30;
			$s[1][type]   =  2;
			$s[2][type]   =  8;
			$s[3][type]   = 13;
		} elseif (($c == 6) || ($c == 16))
		{
			$s[count]     =  5;
			$s[1][chance] = 45;
			$s[2][chance] = 50;
			$s[3][chance] = 15;
			$s[4][chance] = 15;
			$s[5][chance] = 20;
			$s[1][type]   = 20;
			$s[2][type]   =  2;
			$s[3][type]   = 22;
			$s[4][type]   = 21;
			$s[5][type]   = 23;
		} elseif (($c == 7) || ($c == 17))
		{
			$s[count]     =  5;
			$s[1][chance] = 45;
			$s[2][chance] =  6;
			$s[3][chance] = 15;
			$s[4][chance] = 15;
			$s[5][chance] = 20;
			$s[1][type]   = 20;
			$s[2][type]   = 27;
			$s[3][type]   = 22;
			$s[4][type]   = 21;
			$s[5][type]   = 28;
		} elseif ($c == 9)
		{
			$s[count]     =  2;
			$s[1][chance] = 80;
			$s[2][chance] = 20;
			$s[1][type]   = 11;
			$s[2][type]   = 25;

		} elseif ($c == 20)
		{
			$s[count]     =  2;
			$s[1][chance] = 80;
			$s[2][chance] = 20;
			$s[1][type]   = 44;
			$s[2][type]   = 45;
		} elseif ($c == 19)
		{
			$s[count]     =  2;
			$s[1][chance] = 80;
			$s[2][chance] = 20;
			$s[1][type]   = 9;
			$s[2][type]   = 10;
		} elseif (($c == 8) || ($c == 18))
		{
			$s[count]     =  2;
			$s[1][chance] = 80;
			$s[2][chance] = 20;
			$s[1][type]   = 43;
			$s[2][type]   = 7;
		}

		return $s;
	}

	function modifychancebyrow($s,$y,$c) 
	{

		// Planeten

		// Eis raus
		if (($c == 1) || ($c == 2) || ($c == 3) || ($c == 6))
		{
			if (($y == 3) || ($y == 10))
			{
				$s[2][chance] = $s[2][chance]/2;
			}
			elseif (($y > 3) && ($y < 10))
			{
				$s[2][chance] = 0;
			}
		}
		// Wüsten raus
		if (($c == 1) || ($c == 6))
		{
			if (($y == 4) || ($y == 9))
			{
				$s[5][chance] = $s[5][chance]/2;
			}
			elseif (($y < 4) || ($y > 9))
			{
				$s[5][chance] = 0;
			}
		}
		// Berge -> Weisse Berge
		if (($c == 1) || ($c == 2) || ($c == 3) || ($c == 6))
		{
			if (($y < 3) || ($y > 10))
			{
				$s[4][type] = 8;
			}
		}

		// Monde

		// Eis raus
		if (($c == 11) || ($c == 12) || ($c == 13) || ($c == 16))
		{
			if (($y == 2) || ($y == 7))
			{
				$s[2][chance] = $s[2][chance]/2;
			}
			elseif (($y > 2) && ($y < 7))
			{
				$s[2][chance] = 0;
			}
		}
		// Wüsten raus
		if (($c == 11) || ($c == 16))
		{
			if (($y == 3) || ($y == 6))
			{
				$s[5][chance] = $s[5][chance]/2;
			}
			elseif (($y < 3) || ($y > 6))
			{
				$s[5][chance] = 0;
			}
		}
		return $s;
	}

	function selectfromfieldsbychance($s) 
	{

		$s[ges] = 0;
		for ($i=1;$i<=$s[count];$i++) { 
			$s[ges] = $s[ges] + $s[$i][chance];
		}
		$rand = rand(1,$s[ges]);
		$chs[1] = 0;		
		for ($i=2;$i<=$s[count];$i++) { 
			$chs[$i] = $chs[$i-1] + $s[$i-1][chance];
		}
		for ($i=1;$i<=$s[count];$i++) { 
			if ($rand > $chs[$i]) $field = $s[$i][type];
		}
		return $field;
	}

	function getfieldbyxyw($x,$y,$w) 
	{
		return (($y - 1) * $w) + ($x - 1);
	}

	function getxybyfw($f,$w) 
	{
		$data[y] = floor($f/$w) + 1;		
		$data[x] = floor($f%$w) + 1;

		return $data;

	}

	function getsizebyclass($c) 
	{
		$size[w] = 18;
		$size[h] = 12;
		$size[g] = 1;
		if ((($c >= 11) && ($c <= 19)) || ($c == 9))
		{
			$size[w] = 12;
			$size[h] = 8;
			$size[g] = 0;
		} 
		if ($c == 20) $size[g] = 0;
		return $size;
	}

	function getgroundbyfield($f) 
	{
		if ($f == 3) return 29;
		elseif (($f == 2) || ($f == 26)) return 30;
		elseif ($f == 28) return 32;
		else return 14;
	}

	function generatefields($c)
	{
		$size = getsizebyclass($c);
		$s = getbasechancebyclass($c);
		for ($y=0;$y<=$size[h];$y++) 
		{ 
			$s2 = modifychancebyrow($s,$y,$c);
			for ($x=0;$x<=$size[w];$x++) 
			{ 
				$i = getfieldbyxyw($x,$y,$size[w]);
				$f[$i] = selectfromfieldsbychance($s2);
			}
		}
		return $f;
	}

	function generategroundfromfields($c,$f)
	{
		$size = getsizebyclass($c);
		$size[h] = $size[h] / 2;
		for ($y=0;$y<=$size[h];$y++) 
		{ 
			for ($x=0;$x<=$size[w];$x++) 
			{ 
				$t[1] = getfieldbyxyw($x,(2*$y - 1),$size[w]);
				$t[2] = getfieldbyxyw($x,(2*$y),$size[w]);
				$i = getfieldbyxyw($x,$y,$size[w]);
				if (($f[$t[1]] == 5) || ($f[$t[1]] == 7) || ($f[$t[1]] == 8) || ($f[$t[1]] == 21) || ($f[$t[2]] == 5) || ($f[$t[2]] == 7) || ($f[$t[2]] == 8) || ($f[$t[2]] == 21))
				{
					$g[$i] = 14;
				}
				else
				{
					$r = rand(1,2);
					$g[$i] = getgroundbyfield($f[$t[$r]]);
				}
			}
		}
		return $g;
	}

	function getcoloradius($f,$sel,$size)
	{

		$data = getxybyfw($sel,$size[w]);
		$data2 = getxybyfw($f,$size[w]);


		if ((($data2[x] >= $data[x]-4) && ($data2[x] <= $data[x]+4)) && (($data2[y] > $data[y]-3) && ($data2[y] <= $data[y]+3))) return 1;
		if ($data[x] <= 4)
		{
			$dist = 4- $data[x];
			if (($data2[x] >= 18-$dist) && (($data2[y] > $data[y]-3) && ($data2[y] <= $data[y]+3))) return 1;
		}
		if ($data[x] >= 14)
		{
			$dist = $data[x] - 14;
			if (($data2[x] <= $dist) && (($data2[y] > $data[y]-3) && ($data2[y] <= $data[y]+3))) return 1;
		}

		return 0;
	}
	function getgroundradius($f,$sel,$size)
	{

		$data = getxybyfw($sel,$size[w]);
		$data2 = getxybyfw($f,$size[w]);


		if ((($data2[x] >= $data[x]-4) && ($data2[x] <= $data[x]+4)) && (($data2[y] >= $data[y]-1) && ($data2[y] <= $data[y]+1))) return 1;
		if ($data[x] <= 4)
		{
			$dist = 4- $data[x];
			if (($data2[x] >= 18-$dist) && (($data2[y] >= $data[y]-1) && ($data2[y] <= $data[y]+1))) return 1;
		}
		if ($data[x] >= 14)
		{
			$dist = $data[x] - 14;
			if (($data2[x] <= $dist) && (($data2[y] >= $data[y]-1) && ($data2[y] <= $data[y]+1))) return 1;
		}

		return 0;
	}
	echo "<body bgcolor=#000000>";
	$gfx = "http://home.arcor.de/omega-sektion/stuv2";
	if ($c == 0) $c = 1;

	// --------------------------

	$size = getsizebyclass(1);

	// --------------------------

	for ($i=0;$i<216;$i++) {
		$f[$i] = 1;
	}
	$g = generategroundfromfields($c,$f);
	$data = getxybyfw($sel,$size[w]);
	echo "<font color=green><br> X: ".$data[x]." Y: ".$data[y];
	echo "<br><br>";
	if ($data[y] < 3) $data[y] = 3;
	if ($data[y] > 9) $data[y] = 9;
	$sel = getfieldbyxyw($data[x],$data[y],$size[w]);
	$rowcount = 1;
	for ($i=0;$i<($size[w] * $size[h]);$i++) { 
		if ($i == ($rowcount * ($size[w]))) { 
			echo "<br>";
			$rowcount = $rowcount + 1;
		}
		$qvc = getcoloradius($i,$sel,$size);
		if ($qvc == 1) $f[$i] = 2;
		if ($i == $sel) $f[$i] = 3;
		echo "<a href='http://www.stuniverse.de/intern/col3.php?sel=".$i."'><img src='".$gfx."/fields/".$f[$i].".gif' border=0></a> ";
	}
	$blubb = ceil($data[y]/2);
	$gsel = getfieldbyxyw($data[x],$blubb,$size[w]);
	echo "<br>";
	if ($size[g] == 1) 
	{
		$rowcount = 1;
		for ($i=0;$i<($size[w] * ($size[h]/2));$i++) { 
			if ($i == ($rowcount * ($size[w]))) { 
				echo "<br>";
				$rowcount = $rowcount + 1;
			}
		$qvc = getgroundradius($i,$gsel,$size);
		if ($qvc == 1) $g[$i] = 30;
		if ($i == $gsel) $g[$i] = 29;
		echo "<img src='".$gfx."/fields/".$g[$i].".gif' border=0></a> ";
		}
	}
	$data = getxybyfw($sel,$size[w]);
	echo "<font color=green><br> X: ".$data[x]." Y: ".$data[y];
?>