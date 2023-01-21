<?php
if ($id)
{
	$myColony->gcc();
	if ($myColony->cshow == 0) exit;
}
if (!$id && $section) exit;
if ($myColony->cshow == 1 && $myColony->cmkolz == 1) $return = $myColony->newkolozent();
if (!$section)
{
	$result = $myColony->getcolonylist($user);
	echo '<script language="JavaScript" type="text/javascript" src="tooltip.js"></script>
	<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
	<tr>
		<td width=100% class=tdmain>/ <strong>Kolonien</strong></td>
	</tr>
	</table><br>
	<table width=100% bgcolor=#262323>
	<tr>
		<td class=tdmain width=32></td>
		<td class=tdmain width=150>Name</td>
		<td class=tdmain align=center>x/y</td>
		<td class=tdmain align=center>Energie</td>
		<td class=tdmain align=center>Sympathie</td>
		<td class=tdmain align=center>Waren</td>
		<td class=tdmain align=center>Wirtschaft</td>
		<td class=tdmain align=center>SF</td>
	</tr>';
	if (mysql_num_rows($result) == 0) echo '<tr><td class=tdmainobg colspan=8 align=center>Keine Kolonien vorhanden</td></tr>';
	else
	{
		$re = $myDB->query("SELECT id,name,secretimage FROM stu_goods ORDER BY sort ASC");
		while($cols=mysql_fetch_assoc($result))
		{
			$tts = "<b>Produktion/Verbrauch</b>";
			$data = $myColony->getcolonybyid($cols[id]);
			$wirt += $data[wirtschaft];
			$symp += $myColony->getnrsbyid($data[id]);
			$eps += $myColony->getnrebyid($data[id]);
			$addpoint += $myColony->getnrwbyid($data[id]);
			$pw = $myColony->getfnrpgbyid($data[id]);
			while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] += $pg[gs];
			$pw = $myColony->getfnrvgbyid($data[id]);
			while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] -= $pg[gs];
			$pw = $myColony->getonrpgbyid($data[id]);
			while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] += $pg[gs];
			$pw = $myColony->getonrvgbyid($data[id]);
			while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] -= $pg[gs];
			$pw = $myColony->getunrpgbyid($data[id]);
			while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] += $pg[gs];
			$pw = $myColony->getunrvgbyid($data[id]);
			while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] -= $pg[gs];
			$goods[1] += $myColony->getwksnbyid($data[id]);
			$goods[1] += $myColony->getregnbyid($data[id]);
			$goods[1] -= ceil(($data[bev_free]+$data[bev_used])/5);
			if ($data[max_bev] < $data[bev_used] + $data[bev_free]) $over = $data[bev_used] + $data[bev_free] - $data[max_bev];
			$symp += @floor($data[bev_used]/10) - $over;
			if ($eps < 0) $e_show = '<font color=red>'.$eps.'</font>';
			elseif ($eps == 0) $e_show = $eps;
			else $e_show = '<font color=Green>+'.$eps.'</font>';
			if ($symp < 0) $s_show = '<font color=Red>'.$symp.'</font>';
			elseif ($symp == 0) $s_show = $symp;
			else $s_show = '<font color=Green>+'.$symp.'</font>';
			while($goodlist=mysql_fetch_assoc($re))
			{
				$id = $goodlist[id];
				$allgoods[$id] += $goods[$id];
				if ($goods[$id] > 0 || $goods[$id] < 0) 
				{
					if ($goodlist[secretimage] != "0")
					{
						$tts .= "<br><img src=http://www.stuniverse.de/gfx/secret/".$goodlist[secretimage].".gif title='".$goodlist[name]."'> ".($goods[$id] > 0 ? "+".$goods[$id] : $goods[$id]);
					}
					else
					{
						$tts .= "<br><img src=".$grafik."/goods/".$id.".gif title='".$goodlist[name]."'> ".($goods[$id] > 0 ? "+".$goods[$id] : $goods[$id]);
					}
				}
				if ($goods[$id] < 0)
				{
					$r = @floor($myColony->getcountbygoodid($goodlist[id],$data[id])/abs($goods[$id]));
					if ($r < 2) $r = '<font color=red>'.$r.'</font>';
					elseif (($r > 1) && ($r < 6)) $r = '<font color=Yellow>'.$r.'</font>';
					if ($goodlist[secretimage] != "0")
					{
						$res .= '<img src=http://www.stuniverse.de/gfx/secret/'.$goodlist[secretimage].'.gif title="'.$goodlist[name].'"> '.$r.'<br>';
					}
					else
					{
						$res .= '<img src='.$grafik.'/goods/'.$goodlist[id].'.gif title="'.$goodlist[name].'"> '.$r.'<br>';
					}
				}
			}
			$data[schilde_aktiv] == 1 ? $sadd = "s" : $sadd = "";
			$data[wese] == 2 ? $wadd = " (2)" : $wadd = "";
			$data[wese] == 3 ? $wadd = " (3)" : $wadd = "";
			$cs = $myDB->query("SELECT SUM(count) FROM stu_colonies_storage WHERE colonies_id=".$data[id],1);
			echo '<tr><td class=tdmainobg align=center><a href=main.php?page=colony&section=showcolony&id='.$data[id].'><img src='.$grafik.'/planets/'.$data[colonies_classes_id].$sadd.'.gif border=0></a></td>
					  <td class=tdmainobg><a href=main.php?page=colony&section=showcolony&id='.$data[id].'>'.stripslashes($data[name]).'</a> ('.$data[id].')</td>
					  <td class=tdmainobg align=center>'.$data[coords_x].'/'.$data[coords_y].$wadd.'</td>
					  <td class=tdmainobg align=center>'.$data[energie].'/'.$data[max_energie].' ('.$e_show.')</td>
					  <td class=tdmainobg align=Center>'.$s_show.'</td>
					  <td class=tdmainobg><img src='.$grafik.'/buttons/lager.gif class="tooltip" title="'.$tts.'"> '.($cs >= $data[max_storage] ? "<font folor=#ff0000>100%</font>" : round((100/$data[max_storage])*$cs,2)."%").'<br>'.$res.'</td>
					  <td class=tdmainobg align=center>'.$addpoint.'</td>
					  <td class=tdmainobg align=center><a href=?page=colony&section=showsectorflights&id='.$data[id].'>'.$myColony->getflightcount($data[id]).'</a></td>
					  </tr>';
			$addsymp += $symp;
			$points += $addpoint;
			unset($s_show);
			unset($e_show);
			unset($symp);
			unset($eps);
			unset($over);
			unset($goods);
			unset($res);
			unset($wks);
			unset($farm);
			unset($addpoint);
			mysql_data_seek($re,0);
		}
		if ($addsymp < 0) $s_show = '<font color=Red>'.$addsymp.'</font>';
		elseif ($addsymp == 0) $s_show = $addsymp;
		else $s_show = '<font color=Green>+'.$addsymp.'</font>';
		echo '<tr>
			<td class=tdmain colspan=4></td>
			<td class=tdmainobg align=center>= '.$s_show.'</td>
			<td class=tdmain></td>
			<td class=tdmainobg align=center>= '.$points.' ('.$wirt.')</td>
		</tr></table><br>
		<table width=70 bgcolor=#262323>
		<tr>
			<td class=tdmain><strong>Produktion</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg>';
			while($goodlist=mysql_fetch_assoc($re))
			{
				if ($allgoods[$goodlist[id]] != 0)
				{
					$allgoods[$goodlist[id]] > 0 ? $showgood = '+'.$allgoods[$goodlist[id]] : $showgood = $allgoods[$goodlist[id]];
					if ($goodlist[secretimage] != "0")
					{
						echo '<img src=http://www.stuniverse.de/gfx/secret/'.$goodlist[secretimage].'.gif title=\''.$$goodlist[name].'\'> '.$showgood.'<br>';
					}
					else
					{
						echo '<img src='.$grafik.'/goods/'.$goodlist[id].'.gif title=\''.$$goodlist[name].'\'> '.$showgood.'<br>';
					}
				}
			}
			echo '</td>
		</tr>
		</table><br><table width=600 bgcolor=#262323>
		<tr><td class=tdmain colspan=2><strong>Baustatus</strong></td></tr>';
		$result = $myColony->getcolonylist($user);
		while($data=mysql_fetch_assoc($result))
		{
			$builds = $myColony->getunfinishedprocesses($data[id]);
			if (is_array($builds))
			{
				echo '<tr>
					<td class=tdmainobg colspan=2><img src='.$grafik.'/planets/'.$data[colonies_classes_id].'.gif> '.stripslashes($data[name]).'</td>
				</tr>';
				for ($j=0;$j<count($builds);$j++)
				{
					$build = $myColony->getbuildbyid($builds[$j][buildings_id]);

					if ($builds[$j][type] == 43 || $builds[$j][type] == 45)
					{
						echo '<tr>
							<td class=tdmainobg width=30><img src='.$grafik.'/buildings/n/'.$builds[$j][buildings_id].'_'.$builds[$j][type].'.gif></td>
							<td class=tdmainobg>'.$build[name].' im Bau bis: '.date('d.m.Y H:i:s',$builds[$j][buildtime]).'</td>
						</tr>';
					}
					elseif ($builds[$j][buildings_id] == 218)
					{
						echo '<tr>
							<td class=tdmainobg width=30><img src='.$grafik.'/buildings/'.$builds[$j][buildings_id].'_'.$builds[$j][type].'.gif></td>
							<td class=tdmainobg>Technikerteam ist bereit am: '.date('d.m.Y H:i:s',$builds[$j][buildtime]).'</td>
						</tr>';
					}
					else
					{
						if ($build[secretimage] != "0")
						{
							$bimg = "<img src=http://www.stuniverse.de/gfx/secret/".$builds[$j][buildings_id]."_".$builds[$j][type].".gif>";
						}
						else
						{
							$bimg = "<img src=".$grafik."/buildings/".$builds[$j][buildings_id]."_".$builds[$j][type].".gif>";
						}
						echo '<tr>
							<td class=tdmainobg width=30>'.$bimg.'</td>
							<td class=tdmainobg>'.$build[name].' im Bau bis: '.date('d.m.Y H:i:s',$builds[$j][buildtime]).'</td>
						</tr>';
					}
				}
			}
		}
	}
}
elseif ($section == "showcolony")
{
	if ($action == "replikator")
	{
		if ($count[7] > 0)
		{
			$count = $count[7];
			$end = 7;
		}
		if ($count[16] > 0)
		{
			$count = $count[16];
			$end = 16;
		}
		if ($count[17] > 0)
		{
			$count = $count[17];
			$end = 17;
		}
		if ($count[26] > 0)
		{
			$count = $count[26];
			$end = 26;
		}
		if ($count[27] > 0)
		{
			$count = $count[27];
			$end = 27;
		}
		if ($count[29] > 0)
		{
			$count = $count[29];
			$end = 29;
		}
		if ($count[40] > 0)
		{
			$count = $count[40];
			$end = 40;
		}
		if ($count[41] > 0)
		{
			$count = $count[41];
			$end = 41;
		}
		if ($count[201] > 0)
		{
			$count = $count[201];
			$end = 201;
		}
		if ($count[202] > 0)
		{
			$count = $count[202];
			$end = 202;
		}
		if ($count[203] > 0)
		{
			$count = $count[203];
			$end = 203;
		}
		if ($count[205] > 0)
		{
			$count = $count[205];
			$end = 205;
		}
		if ($count[209] > 0)
		{
			$count = $count[209];
			$end = 209;
		}

	}
	if ($rename) $result = $myColony->renameCol($rename);
	if (($action == "groundbuild") && ($field > -1) && $building) $result = $myColony->groundbuild($field,$building,$id);
	if (($action == "orbitbuild") && ($field > -1) && $building) $result = $myColony->orbitbuild($field,$building,$id);
	if (($action == "build") && ($field > -1) && $building) $result = $myColony->buildonfield($field,$building,$id);
	if (($action == "deactivate") && ($field > -1)) $result = $myColony->deactivateBuilding($field,$id,$user);
	if (($action == "activate") && ($field > -1)) $result = $myColony->activateBuilding($field,$id,$user);
	if (($action == "orbitdeactivate") && ($field > -1)) $result = $myColony->deactivateorbitBuilding($field,$id,$user);
	if (($action == "orbitactivate") && ($field > -1)) $result = $myColony->activateorbitBuilding($field,$id,$user);
	if (($action == "grounddeactivate") && ($field > -1)) $result = $myColony->deactivategroundBuilding($field,$id,$user);
	if (($action == "groundactivate") && ($field > -1)) $result = $myColony->activategroundBuilding($field,$id,$user);
	if (($action == "delete") && ($field > -1)) $result = $myColony->deletebuilding($field,$id,$user);
	if (($action == "orbitdelete") && ($field > -1)) $result = $myColony->deleteorbitbuilding($field,$id,$user);
	if (($action == "grounddelete") && ($field > -1)) $result = $myColony->deletegroundbuilding($field,$id,$user);
	if (($action == "upgrade") && ($field > -1)) $result = $myColony->upgradebuilding($field,$id,$user);
	if (($action == "orbitupgrade") && ($field > -1)) $result = $myColony->orbitupgrade($field,$id);
	if (($action == "groundupgrade") && ($field > -1)) $result = $myColony->groundupgrade($field,$id,$user);
	if (($action == "buildship") && $class) $result = $myColony->buildship($id,$class,$user);
	if (($action == "replikator") && $end && $count && is_numeric($count) && ($count > 0)) $result = $myColony->replikator(0,$end,$count);
	if (($action == "fabrik") && $end && $count[$end] && $buildid && is_numeric($count[$end]) && ($count[$end] > 0)) $result = $myColony->fabrik($end,$count[$end],$buildid);
	if (($action == "raffinerie") && $source && $count && is_numeric($count) && ($count > 0)) $result = $myColony->raffinerie($source,$count,$id,$user);
	if (($action == "etrans") && $id && $shipid && $count && (is_numeric($count) || ($count == "max"))) $result = $myColony->etransfer($shipid,$id,$count,$user);
	if (($action == "loadbatt") && $id && $shipid && $count && (is_numeric($count) || ($count == "max"))) $result = $myColony->loadbatt($shipid,$count);
	if (($action == "repairship") && $id && $shipid) $result = $myColony->repairship($shipid,$id,$user);
	if (($action == "evcol") && $id) $result = $myColony->evacuateCol($id,$user);
	if (($action == "detcol") && $id) $result = $myColony->destroyCol($id,$user);
	if (($action == "ewopt") && $id) $result = $myColony->ewopt($mode);
	if (($action == "demontship") && $id && $shipid) $result = $myColony->demontship($shipid);
	if (($action == "repairbuilding") && $id && $mode && ($field>=0)) $result = $myColony->repairbuilding($mode,$field);
	if (($action == "newkolozent") && $id) $result = $myColony->newkolozent($id,$user);
	if (($action == "changebuildname") && $id && $buildname) $result = $myColony->changebuildname($mode,strip_tags($buildname),$field);
	if (($action == "set_ewstop") && $id && $stop_count && (is_numeric($stop_count) || ($stop_count == "reset"))) $result = $myColony->set_ewstop($stop_count);
	if (($action == "sperr") && $id && (($mode ==1) || ($mode == 0))) $result = $myColony->setsperrung($mode);
	if (($action == "shipupgrade") && $id && $id2 && $upgrade) $result = $myColony->shipupgrade($id2,$upgrade);
	if (($action == "activateshields") && $id) $result = $myColony->activateshields();
	if (($action == "deactivateshields") && $id) $result = $myColony->deactivateshields();
	if (($action == "setshieldfreq") && $id && $freq1 && $freq2 && is_numeric($freq1) && is_numeric($freq2)) $result = $myColony->setshieldfreq($freq1,$freq2);
	if (($action == "loadshields") && $id && $count && (is_numeric($count) || ($count == "max"))) $result = $myColony->loadshields($count);
	if (($action == "chbam") && $id && $field >= 0 && (($m == 1) || ($m == 2))) $result = $myColony->chbam($field,$m);
	if (($action == "chobam") && $id && $field >= 0 && (($m == 1) || ($m == 2))) $result = $myColony->chobam($field,$m);
	if (($action == "chubam") && $id && $field >= 0 && (($m == 1) || ($m == 2))) $result = $myColony->chubam($field,$m);
	if (($action == "makelat") && $id && is_numeric($count) && $count > 0) $result = $myColony->makelatinum($id,$count);
	if (($action == "robotik") && $id && $count[$sonId] && $sonId  && is_numeric($count[$sonId]) && ($count[$sonId] > 0)) $result = $myColony->robotik($sonId,$count[$sonId]);
	if (($action == "materialanalyse") && $id && $goodId) $result = $myColony->analyse($goodId);
	if (($action == "swapbuilding") && $id && ($field > -1) && $newid) $result = $myColony->swapbuilding($field,$id,$newid);
	if (($action == "movecloak") && $id && $cloakfield) $result = $myColony->movecloakfield($id,($cloakfield-1));
	if (($action == "embowner") && $id && $field) $result = $myColony->changeembassyowner($id,$field,$embassy);
	if (($action == "jemhadar") && $id && $field && $type) $result = $myColony->jemhadarfunction($field,$type);
	if ($action == "beam" && $id && $shipid && $way == "to")
	{
		if ($crew && is_numeric($crew)) $result = $myColony->transferCrew($shipid,$id,$crew,$way);
		if (is_array($beam))
		{
			foreach($beam as $key => $value)
			{
				if (!is_numeric($value) || !is_numeric($good[$key])) continue;
				$res = $myColony->beamto($shipid,$good[$key],$value);
				$result[msg] .= $res[msg];
				if ($res[code] < 1) break;
				if ($res[code] == 1)
				{
					$dummygood[$j][id] = $good[$key];
					$dummygood[$j]['count'] = $res[beamed];
					$j++;
				}
			}
		}
		if (is_array($dummygood)) $myColony->beammsg($dummygood,$shipid,"to");
	}
	if ($action == "beam" && $id && $shipid && $way == "from")
	{
		if ($crew && is_numeric($crew)) $result = $myColony->transferCrew($shipid,$id,$crew,$way);
		if (is_array($beam))
		{
			foreach($beam as $key => $value)
			{
				if (!is_numeric($value) || !is_numeric($good[$key])) continue;
				$res = $myColony->beamfrom($shipid,$good[$key],$value);
				$result[msg] .= $res[msg];
				if ($res[code] < 1) break;
				if ($res[code] == 1)
				{
					$dummygood[$j][id] = $good[$key];
					$dummygood[$j]['count'] = $res[beamed];
					$j++;
				}
			}
		}
		if (is_array($dummygood)) $myColony->beammsg($dummygood,$shipid,"from");
	}
	if (($action == "terraform") && ($field > -1) && ($terraform > 0) && $mode) $result = $myColony->terraform($field,$id,$terraform,$mode);
	if ($action || $rename) $myColony->gcc();
	$class = $myColony->getclassbyid($myColony->ccolonies_classes_id);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain width=100%>/ <a href=?page=colony>Kolonien</a> / <strong>".$myColony->cname."</strong></td>
	</tr>
	</table><br>";
	if (is_array($result)) echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table><br>";
	$bevo = 0;
	$nre = $myColony->getnrebyid($myColony->cid);
	if ($nre > 0)
	{
		if ($myColony->cenergie+$nre > $myColony->cmax_energie) $ep = $myColony->cmax_energie-$myColony->cenergie;
		else
		{
			$ep = $nre;
			$ela = "<img src=".$grafik."/el_t.gif width=".($myColony->cmax_energie-$nre-$myColony->cenergie)." height=9>";
		}
		if ($myColony->cenergie > 0) $epa = "<img src=".$grafik."/ev_t.gif width=".$myColony->cenergie." height=9>";
		if ($ep > 0) $epa .= "<img src=".$grafik."/ep_t.gif height=9 width=".$ep.">";
		$epa .= $ela;
		$epm = "+";
	}
	if ($nre < 0)
	{
		$em = abs($nre);
		$myColony->cenergie < $em ? $ema = "<img src=".$grafik."/em_t.gif height=9 width=".$myColony->cenergie.">" : $ema = "<img src=".$grafik."/ev_t.gif height=9 width=".($myColony->cenergie-$em)."><img src=".$grafik."/em_t.gif height=9 width=".$em.">";
		if ($myColony->cenergie < $myColony->cmax_energie) $ema .= "<img src=".$grafik."/el_t.gif height=9 width=".($myColony->cmax_energie-$myColony->cenergie).">";
	}
	if ($nre == 0)
	{
		if ($myColony->cenergie > 0) $epa = "<img src=".$grafik."/ev_t.gif height=9 width=".$myColony->cenergie.">";
		if ($myColony->cenergie < $myColony->cmax_energie) $epa .= "<img src=".$grafik."/el_t.gif height=9 width=".($myColony->cmax_energie-$myColony->cenergie).">";
	}
	$eps = $epa.$ema;
	(($myColony->ccolonies_classes_id < 4) || ($myColony->ccolonies_classes_id == 10)) ? $bev_add_show = ceil(($myColony->cmax_bev-$myColony->cbev_used-$myColony->cbev_free)/2)."/".$myColony->cbev_free : $bev_add_show = $myColony->cbev_free;
	echo "<table bgcolor=#262323>
	<tr>
		<td class=tdmainobg>Bevölkerung: ".($myColony->cbev_used + $myColony->cbev_free)."/".$myColony->cmax_bev." (".$bev_add_show.")</td>
	</tr>
	<tr>
		<td class=tdmainobg>";
		$myColony->cbev_used + $myColony->cbev_free > $myColony->cmax_bev ? $freebev = $myColony->cmax_bev - $myColony->cbev_used : $freebev = $myColony->cbev_free;
		$free = ceil(($myColony->cmax_bev-$myColony->cbev_used)/2);
		$j = 0;
		$myColony->cmax_bev < $myColony->cbev_used+$myColony->cbev_free ? $over = $myColony->cbev_used + $myColony->cbev_free - $myColony->cmax_bev : $over = 0;
		$ub = $myColony->cbev_used;
		$fb = $myColony->cbev_free;
		$wr = $myColony->cmax_bev-$myColony->cbev_used-$myColony->cbev_free;
		if ($user == 24) 
		{
			$bevpath = "http://www.stuniverse.de/gfx/secret/";
			$bevrace = "v";
		}
		else 
		{
			$bevpath = $grafik;
			$bevrace = $myUser->urasse;
		}
		for ($i=1;$i<=$myColony->cmax_bev;$i++)
		{
			if ($ub > 0)
			{
				if ($ub >= 10 && $gb + 10 <= $myColony->cmax_bev) { $beva .= "<img src=".$bevpath."/bev_used_5_".$bevrace.".gif border=0>"; $ub-=10; $j+=26; $gb+=10; }
				else { $beva .= "<img src=".$bevpath."/bev_used_1_".$bevrace.".gif border=0>"; $ub-=1; $j+=11; $gb+=1; }
			}
			if ($fb > 0 && $ub == 0)
			{
				if ($fb >= 10 && $gb + 10 <= $myColony->cmax_bev) { $beva .= "<img src=".$bevpath."/bev_unused_5_".$bevrace.".gif border=0>"; $fb-=10; $j+=26; $gb+=10; }
				else { $beva .= "<img src=".$bevpath."/bev_unused_1_".$bevrace.".gif border=0>"; $fb-=1; $j+=11; $gb+=1; }
			}
			if ($wr > 0 && $ub == 0 && $fb == 0)
			{
				if ($wr >= 10) { $beva .= "<img src=".$bevpath."/bev_free_5_".$bevrace.".gif border=0>"; $wr-=10; $j+=26; $gb+=10; }
				else { $beva .= "<img src=".$bevpath."/bev_free_1_".$bevrace.".gif border=0>"; $wr-=1; $j+=11; $gb+=1; }
			}
			if ($j >= 600) { $beva.="<br>"; $j=0; }
			if ($gb >= $myColony->cmax_bev) break;
		}
		if ($fb > 0 || $ub > 0)
		{
			$ob = $fb+$ub;
			for($i=1;$i<=$ob;$i++)
			{
				if ($ob >= 10) { $beva .= "<img src=".$bevpath."/bev_over_5_".$bevrace.".gif border=0>"; $ob-=10; $j+=26; }
				else { $beva .= "<img src=".$bevpath."/bev_over_1_".$bevrace.".gif border=0>"; $ob-=1; $j+=11; }
				if ($j >= 600) { $beva.="<br>"; $j=0; }
			}
		}
		echo $beva;
		$goods[1] -= ceil(($myColony->cbev_used+$myColony->cbev_free)/5);
		echo "</td>
	</tr>
	</table><br>";
	if ($myColony->cmax_schilde > 0)
	{
		echo "<table bgcolor=#262323><tr>
		<td class=tdmainobg>Schilde: ".$myColony->cschilde."/".$myColony->cmax_schilde." Frequenz: ".$myColony->cschild_freq1.",".$myColony->cschild_freq2."<br>";
		$r = $myColony->cmax_schilde-$myColony->cschilde;
		$myColony->cschilde_aktiv == 1 ? $sadd = "o" : $sadd = "f";
		echo "<img src=".$grafik."/s_v".$sadd.".gif height=9 width=".$myColony->cschilde.">";
		if ($r > 0) echo "<img src=".$grafik."/s_l.gif height=9 width=".$r.">";
		echo "</td></tr></table><br>";
	}
	$myColony->cmax_bev-$myColony->cbev_free-$myColony->cbev_used >= 0 ? $wr = $myColony->cmax_bev-$myColony->cbev_free-$myColony->cbev_used : $wr = 0;
	echo "<table bgcolor=#262323><tr>
		<td class=tdmainobg>Energie: ".$myColony->cenergie."/".$myColony->cmax_energie." (".$epm.$nre.")<br>".$eps."</td>
	</tr>
	</table><br>
	<table cellpadding=1 cellspacing=1 width=780>
	<tr>
		<td valign=top width=300>".$myColony->rendercolony($id,1)."</td>
		<td valign=top width=480>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>
		<tr>
			<td class=tdmainobg valign=top width=230><b>Informationen</b><br>
			<img src=".$grafik."/planets/".$class[id].".gif> ".$class[name]."<br>";
			if ($myColony->cweather == 1) { $wt = "bew"; $walt = "Bewölkung"; }
			if ($myColony->cweather == 2) { $wt = "gewitt"; $walt = "Gewitter"; }
			if ($myColony->cweather == 3) { $wt = "meteor"; $walt = "Meteroitenschauer"; }
			if ($myColony->cweather == 4) { $wt = "regen"; $walt = "Regen"; }
			if ($myColony->cweather == 5) { $wt = "sauere"; $walt = "Säureregen"; }
			if ($myColony->cweather == 6) { $wt = "schnee"; $walt = "Schneefall"; }
			if ($myColony->cweather == 7) { $wt = "sonne"; $walt = "Sonnenschein"; }
			if ($myColony->cweather == 8) { $wt = "ssand"; $walt = "Sandsturm"; }
			if ($myColony->cweather == 9) { $wt = "sschnee"; $walt = "Schneesturm"; }
			$symp = $myColony->getnrsbyid($myColony->cid);
			if ($class[id] != 10) 
			{	
				$myColony->cdn_mode == 1 ? $dnm = "<img src=".$grafik."/buttons/su.gif title='Sonnenuntergang'> ".date("H:i",$myColony->cdn_nextchange) : $dnm = "<img src=".$grafik."/buttons/sa.gif title='Sonnenaufgang'> ".date("H:i",$myColony->cdn_nextchange);
			}
			else
			{
				$dnm = "<img src=".$grafik."/buttons/su.gif title='Tag- und Nachtwechsel'> kein Wechsel";
			}
			echo "Wirtschaft: ".$myColony->getnrwbyid($id)." (".$myColony->cwirtschaft.")<br>
			Sympathie: ".($symp+floor($myColony->cbev_used/10)-$over)."<br>&nbsp;<br>
			<table cellpadding=1 cellspacing=1 bgcolor=#000000 width=230>
			<tr><td class=tdmainobg  width=80><img src=".$grafik."/buttons/w_".$wt.".gif title=".$walt."> ".$myColony->ctemp."°C </td><td class=tdmainobg  width=170>".$dnm."</td></tr>
			<tr><td class=tdmainobg  width=80><img src=".$grafik."/buttons/grav.gif title='Gravitation'> ".$myColony->cgravi."</td>";
			if ($class[id] != 10) 
			{	
				$timeh = floor((2*$myColony->cdn_duration)/3600);
				$timed = 2*$myColony->cdn_duration-($timeh*3600);
				$timem = floor($timed/60);
				$times = $timed-($timem*60);
	 			$time = $timeh."h ".$timem."m ".$times."s";
				echo "<td class=tdmainobg width=170><img src=".$grafik."/buttons/time.gif title='Rotationsdauer'> ".$time."</td></tr>";
			}
			else
			{
				echo "<td class=tdmainobg width=170></td></tr>";
			}
			echo "<tr><td class=tdmainobg colspan=2><a href=main.php?page=colony&section=showsectorflights&id=".$id." onMouseOver=\"cp('secflight','buttons/secflight2')\" onMouseOut=\"cp('secflight','buttons/secflight1')\"><img src=".$grafik."/buttons/secflight1.gif name=secflight border=0> Sektordurchflüge</a> (".$myColony->getflightcount($myColony->cid).")</td></tr>";
			$myColony->csperrung == 1 ? print("<tr><td class=tdmainobg colspan=2><a href=?page=colony&section=showcolony&action=sperr&mode=0&id=".$id." onMouseOver=\"cp('sperrung','buttons/sperrung1')\" onMouseOut=\"cp('sperrung','buttons/sperrung2')\"><img src=".$grafik."/buttons/sperrung2.gif name=sperrung border=0> Sektor freigeben</a></td></tr>") : print("<tr><td class=tdmainobg colspan=2><a href=?page=colony&section=showcolony&action=sperr&mode=1&id=".$id." onmouseover=cp('ss','buttons/sperrung2') onmouseout=cp('ss','buttons/sperrung1')><img src=".$grafik."/buttons/sperrung1.gif name=ss border=0> Sektor sperren</a></td></tr>");
			echo "<tr><td class=tdmainobg colspan=2><img src=".$grafik."/buttons/leavecol1.gif name=leavecol border=0> Kolonie <a href=?page=colony&section=evcol&id=".$id." onMouseOver=\"cp('leavecol','buttons/leavecol2')\" onMouseOut=\"cp('leavecol','buttons/leavecol1')\">aufgeben</a> / <a href=?page=colony&section=detcol&id=".$id." onMouseOver=\"cp('leavecol','buttons/leavecol3')\" onMouseOut=\"cp('leavecol','buttons/leavecol1')\">sprengen</a></td></tr></table>
			</td>
			<td class=tdmainobg valign=top width=250><b>Bevölkerung</b><br>
			<img src=".$grafik."/bev_free_1_".$myUser->urasse.".gif> Wohnraum: ".$wr."/".$myColony->cmax_bev."<br>
			<img src=".$grafik."/bev_used_1_".$myUser->urasse.".gif> Arbeiter: ".$myColony->cbev_used."<br>
			<img src=".$grafik."/bev_unused_1_".$myUser->urasse.".gif> Arbeitslose: ".$myColony->cbev_free."<br>
			<img src=".$grafik."/bev_over_1_".$myUser->urasse.".gif> Obdachlose: ".$over."<br><br>";
			$myColony->cewopt == 0 ? print("<a href=\"?page=colony&section=showcolony&action=ewopt&mode=1&id=".$id."\" title=\"erlauben\" onMouseOver=\"cp('einwand','buttons/einwand1')\" onMouseOut=\"cp('einwand','buttons/einwand0')\"><img src=".$grafik."/buttons/einwand0.gif name=einwand border=0> Einwanderung: <font color=#FF0000>nein</font></a><br>") : print("<a href=\"?page=colony&section=showcolony&action=ewopt&mode=0&id=".$id."\" title=\"verbieten\" onMouseOver=\"cp('einwand','buttons/einwand0')\" onMouseOut=\"cp('einwand','buttons/einwand1')\"><img src=".$grafik."/buttons/einwand1.gif name=einwand border=0> Einwanderung: <font color=#38CE13>ja</font></a><br>");
			echo "&nbsp;<br>Einwanderungsgrenze<br>
			<form action=main.php method=post>
			<input type=hidden name=page value=colony>
			<input type=hidden name=section value=showcolony>
			<input type=hidden name=action value=set_ewstop>
			<input type=hidden name=id value=".$id.">
			<input type=text size=3 name=stop_count class=text value=".$myColony->cbev_stop_count."> <input type=submit value=set class=button> <input type=submit name=stop_count value=reset class=button>
			<br>&nbsp;
			</form>
			</td>
		</tr>
		</table><table cellpadding=1 cellspacing=1 bgcolor=#262323 width=100%>";
		$res = $myDB->query("SELECT id,name FROM stu_ships WHERE user_id=".$user." AND coords_x=".$myColony->ccoords_x." AND coords_y=".$myColony->ccoords_y." AND wese=".$myColony->cwese." ORDER BY fleets_id DESC,".$myUser->getslsorting());
		if (mysql_num_rows($res) != 0)
		{
			echo "<form action=main.php method=post>
				<input type=hidden name=page value=ship>
				<input type=hidden name=section value=showship>
			<tr><td class=tdmainobg>Schiffe im Orbit</td></tr>
			<tr>
				<td class=tdmainobg><select name=id class=select>";
				while($items=mysql_fetch_assoc($res)) echo "<option value=".$items[id].">".strip_tags(stripslashes($items[name]))."</option>";
				echo "</select> <input type=submit value=go class=button>
				</td>
			</tr>
			</form>";
		}
		$res = $myColony->getcolonylist();
		if (mysql_num_rows($res) > 1)
		{
			echo "<form action=main.php method=post>
				<input type=hidden name=page value=colony>
				<input type=hidden name=section value=showcolony>
			<tr><td class=tdmainobg>Andere Kolonien</td></tr>
			<tr>
				<td class=tdmainobg><select name=id class=select>";
				while($items=mysql_fetch_assoc($res)) if ($items[id] != $id) echo "<option value=".$items[id].">".strip_tags(stripslashes($items[name]))."</option>";
				echo "</select> <input type=submit value=go class=button>
				</td>
			</tr>
			</form>";
		}
		echo "<tr><td class=tdmainobg>Name ändern</td></tr>
		<tr>
			<form action=main.php method=post>
			<input type=hidden name=page value=colony>
			<input type=hidden name=section value=showcolony>
			<input type=hidden name=id value=".$id.">
			<td class=tdmainobg><input type=text name=rename class=text size=50 value=\"".htmlentities(stripslashes($myColony->cname))."\"> <input type=submit class=button value=ändern></td>
		</tr></form>";
		if ($myColony->cmax_schilde > 0) echo "<form action=main.php method=post>
			<input type=hidden name=page value=colony>
			<input type=hidden name=section value=showcolony>
			<input type=hidden name=action value=setshieldfreq>
			<input type=hidden name=id value=".$id.">
			<tr><td class=tdmainobg>Schildfrequenz</td></tr>
			<tr><td class=tdmainobg><input type=text size=2 maxlength=2 class=text name=freq1 value='".$myColony->cschild_freq1."'>,<input type=text size=1 maxlength=1 class=text name=freq2 value='".$myColony->cschild_freq2."'> <input type=submit value=Ändern class=button></td></tr>
			</form>";
		echo "</table></td>
	</tr>
	</table>";
	unset($goods);
	$goodlist = $myColony->goodlist();
	$pw = $myColony->getfnrpgbyid($myColony->cid);
	while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] += $pg[gs];
	$pw = $myColony->getfnrvgbyid($myColony->cid);
	while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] -= $pg[gs];
	$pw = $myColony->getonrpgbyid($myColony->cid);
	while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] += $pg[gs];
	$pw = $myColony->getonrvgbyid($myColony->cid);
	while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] -= $pg[gs];
	$pw = $myColony->getunrpgbyid($myColony->cid);
	while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] += $pg[gs];
	$pw = $myColony->getunrvgbyid($myColony->cid);
	while($pg=mysql_fetch_assoc($pw)) $goods[$pg[goods_id]] -= $pg[gs];
	$goods[1] -= ceil(($myColony->cbev_used+$myColony->cbev_free)/5);
	$goods[1] += $myColony->getwksnbyid($myColony->cid);
	$goods[1] += $myColony->getregnbyid($myColony->cid);
	$result = $myDB->query("SELECT id,name,secretimage FROM stu_goods ORDER BY sort ASC");
	while($goodlist=mysql_fetch_assoc($result))
	{
		$gid = $goodlist[id];
		$stor = $myColony->getstoragebygoodid($gid);
		if ($stor == 0 && $goods[$gid] == 0) continue;
		$lager .= "<tr>";
		if ($goods[$gid] != 0 && $stor != 0)
		{
			if ($goods[$gid] > 0)
			{
				$r = $stor['count'];
				$k = floor($r/1000);
				for ($j=0;$j<$k;$j++)
				{
					$hla .= "<img src=".$grafik."/l_t.gif>";
					$r -= 1000;
				}
				$k = floor($r/100);
				for ($j=0;$j<$k;$j++)
				{
					$hla .= "<img src=".$grafik."/l_h.gif>";
					$r -= 100;
				}
				if ($goodlist[secretimage] != "0")
				{
					$lager .= "<td class=tdmainobg width=60><img src=http://www.stuniverse.de/gfx/secret/".$goodlist[secretimage].".gif title='".$goodlist[name]."'> ".$stor['count']."</td><td class=tdmainobg>".$hla."<img src=".$grafik."/l_s.gif height=12 width=".$r."><img src=".$grafik."/l_sg.gif height=12 width=".$goods[$gid]."></td><td class=tdmainobg><font color=Green>+".$goods[$gid]."</font></td>";
				}
				else
				{
					$lager .= "<td class=tdmainobg width=60><img src=".$grafik."/goods/".$gid.".gif title='".$goodlist[name]."'> ".$stor['count']."</td><td class=tdmainobg>".$hla."<img src=".$grafik."/l_s.gif height=12 width=".$r."><img src=".$grafik."/l_sg.gif height=12 width=".$goods[$gid]."></td><td class=tdmainobg><font color=Green>+".$goods[$gid]."</font></td>";
				}
			}
			if ($goods[$gid] < 0)
			{
				$r = $stor['count']-abs($goods[$gid]);
				$k = floor($r/1000);
				for ($j=0;$j<$k;$j++)
				{
					$hla .= "<img src=".$grafik."/l_t.gif>";
					$r -= 1000;
				}
				$k = floor($r/100);
				for ($j=0;$j<$k;$j++)
				{
					$hla .= "<img src=".$grafik."/l_h.gif>";
					$r -= 100;
				}
				$rm = floor(abs($goods[$gid])/100);
				for ($j=0;$j<$rm;$j++) $la .= "<img src=".$grafik."/l_hr.gif>";
				$stor['count'] > abs($goods[$gid]) ? $rla = "<img src=".$grafik."/l_s.gif height=12 width=".$r.">" : $rla = "";
				if ($goodlist[secretimage] != "0")
				{
					$lager .= "<td class=tdmainobg width=60><img src=http://www.stuniverse.de/gfx/secret/".$goodlist[secretimage].".gif title='".$goodlist[name]."'> ".$stor['count']."</td><td class=tdmainobg>".$hla.$rla.$la."<img src=".$grafik."/l_sr.gif height=12 width=".(abs($goods[$gid])-$rm*100)."></td><td class=tdmainobg><font color=Red>".$goods[$gid]."</font></td>";
				}
				else
				{
					$lager .= "<td class=tdmainobg width=60><img src=".$grafik."/goods/".$gid.".gif title='".$goodlist[name]."'> ".$stor['count']."</td><td class=tdmainobg>".$hla.$rla.$la."<img src=".$grafik."/l_sr.gif height=12 width=".(abs($goods[$gid])-$rm*100)."></td><td class=tdmainobg><font color=Red>".$goods[$gid]."</font></td>";
				}
			}
		}
		if ($goods[$gid] != 0 && $stor == 0)
		{
			if ($goods[$gid] > 0)
			{
				for ($j=0;$j<floor($goods[$gid]/100);$j++) $hla .= "<img src=".$grafik."/l_hg.gif>";
				if ($goodlist[secretimage] != "0")
				{
					$lager .= "<td class=tdmainobg width=60><img src=http://www.stuniverse.de/gfx/secret/".$goodlist[secretimage].".gif title='".$goodlist[name]."'> 0</td><td class=tdmainobg>".$hla."<img src=".$grafik."/l_sg.gif height=12 width=".($goods[$gid]-floor($goods[$gid]/100)*100)."></td><td class=tdmainobg><font color=Green>+".$goods[$gid]."</font></td>";
				}
				else
				{
					$lager .= "<td class=tdmainobg width=60><img src=".$grafik."/goods/".$gid.".gif title='".$goodlist[name]."'> 0</td><td class=tdmainobg>".$hla."<img src=".$grafik."/l_sg.gif height=12 width=".($goods[$gid]-floor($goods[$gid]/100)*100)."></td><td class=tdmainobg><font color=Green>+".$goods[$gid]."</font></td>";
				}
			}
			if ($goods[$gid] < 0)
			{
				for ($j=0;$j<floor(abs($goods[$gid])/100);$j++) $hla .= "<img src=".$grafik."/l_hr.gif>";
				if ($goodlist[secretimage] != "0")
				{
					$lager .= "<td class=tdmainobg width=60><img src=http://www.stuniverse.de/gfx/secret/".$goodlist[secretimage].".gif title='".$goodlist[name]."'> 0</td><td class=tdmainobg>".$hla."<img src=".$grafik."/l_sr.gif height=12 width=".(abs($goods[$gid])-floor(abs($goods[$gid])/100)*100)."></td><td class=tdmainobg><font color=Red>".$goods[$gid]."</font></td>";
				}
				else
				{
					$lager .= "<td class=tdmainobg width=60><img src=".$grafik."/goods/".$gid.".gif title='".$goodlist[name]."'> 0</td><td class=tdmainobg>".$hla."<img src=".$grafik."/l_sr.gif height=12 width=".(abs($goods[$gid])-floor(abs($goods[$gid])/100)*100)."></td><td class=tdmainobg><font color=Red>".$goods[$gid]."</font></td>";
				}
			}
		}
		if ($goods[$gid] == 0 && $stor != 0)
		{
			$r = $stor['count']-abs($goods[$gid]);
			$k = floor($r/1000);
			for ($j=0;$j<$k;$j++)
			{
				$hla .= "<img src=".$grafik."/l_t.gif>";
				$r -= 1000;
			}
			$k = floor($r/100);
			for ($j=0;$j<$k;$j++)
			{
				$hla .= "<img src=".$grafik."/l_h.gif>";
				$r -= 100;
			}
			if ($goodlist[secretimage] != "0")
			{
				$lager .= "<td class=tdmainobg width=60><img src=http://www.stuniverse.de/gfx/secret/".$goodlist[secretimage].".gif title='".$goodlist[name]."'> ".$stor['count']."</td><td class=tdmainobg>".$hla."<img src=".$grafik."/l_s.gif height=12 width=".($stor['count']-floor($stor['count']/100)*100)."></td><td class=tdmainobg>0</td>";
			}
			else
			{
				$lager .= "<td class=tdmainobg width=60><img src=".$grafik."/goods/".$gid.".gif title='".$goodlist[name]."'> ".$stor['count']."</td><td class=tdmainobg>".$hla."<img src=".$grafik."/l_s.gif height=12 width=".($stor['count']-floor($stor['count']/100)*100)."></td><td class=tdmainobg>0</td>";
			}
		}
		$lager .= "</tr>";
		unset($hla);
		unset($la);
		$sumgoods += $goods[$gid];
		$insgstor += $stor['count'];
	}
	$insgstor > $myColony->cmax_storage ? $storproz = "<font color=#ff0000>100</font>" : $storproz = @round((@100/$myColony->cmax_storage)*$insgstor);
	$sumproz = @round(((@100/$myColony->cmax_storage)*$sumgoods),1);
	echo "<br><table bgcolor=#262323 width=570 cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain width=60 valign=middle><img src=".$grafik."/buttons/lager.gif title='Lager'> ".$insgstor."</td>";
		if ($sumgoods < 0) $spr = "<font color=red>".$sumproz."%</font>";
		else $spr = "<font color=green>+".$sumproz."%</font>";
		echo "<td class=tdmain width=500>".$storproz."% (".$spr.") von ".$myColony->cmax_storage." </td><td class=tdmainobg width=30>".$sumgoods."
		</td>
	</tr>".$lager."
	</table>";
}
elseif ($section == "field")
{
	$fieldData = $myColony->getfieldbyid($field,$id);
	$spalte = $myColony->getcolspaltzahl($id);
	if ($fieldData[data][type] == 1) $feld = "Wiese";
	if ($fieldData[data][type] == 2) $feld = "Eis";
	if ($fieldData[data][type] == 3) $feld = "Wasser";
	if ($fieldData[data][type] == 4) $feld = "Wald";
	if ($fieldData[data][type] == 5) $feld = "Berg";
	if ($fieldData[data][type] == 6) $feld = "Wüste";
	if ($fieldData[data][type] == 7) $feld = "Berg";
	if ($fieldData[data][type] == 8) $feld = "Berg";
	if ($fieldData[data][type] == 9) $feld = "Gestein";
	if ($fieldData[data][type] == 10) $feld = "Krater";
	if ($fieldData[data][type] == 11) $feld = "Gas";
	if ($fieldData[data][type] == 13) $feld = "Eisformationen";
	if ($fieldData[data][type] == 16) $feld = "Schacht";
	if ($fieldData[data][type] == 17) $feld = "Schacht";
	if ($fieldData[data][type] == 18) $feld = "Schacht";
	if ($fieldData[data][type] == 20) $feld = "Ödland";
	if ($fieldData[data][type] == 21) $feld = "Berg";
	if ($fieldData[data][type] == 22) $feld = "Felsformationen";
	if ($fieldData[data][type] == 23) $feld = "Wüste";
	if ($fieldData[data][type] == 24) $feld = "Schacht";
	if ($fieldData[data][type] == 25) $feld = "Turbulenzen";
	if ($fieldData[data][type] == 26) $feld = "Lavaspalten";
	if ($fieldData[data][type] == 27) $feld = "Lavaspalten";
	if ($fieldData[data][type] == 28) $feld = "Lavasee";
	if ($fieldData[data][type] == 38) $feld = "Gerüst";
	if ($fieldData[data][type] == 39) $feld = "Graben";
	if ($fieldData[data][type] == 40) $feld = "Strand";
	if ($fieldData[data][type] == 41) $feld = "Strand";
	if ($fieldData[data][type] == 42) $feld = "Hitzeableitende Plattform";
	if ($fieldData[data][type] == 43) $feld = "Biolumineszenter Wald";
	if ($fieldData[data][type] == 44) $feld = "Umwachsene Lavaspalten";
	if ($fieldData[data][type] == 45) $feld = "Lavaspalten";
	if ($fieldData[build] == 0)
	{
		$status = "nicht bebaut";
		$tadd = $feld;
	}
	else
	{
		if ((($fieldData[build][id] < 210) ||($fieldData[build][id] > 215)) && ($fieldData[build][id] != 218)) $chgn = "Name: <input class=text type=text size=25 name=buildname value=\"".$fieldData[data][name]."\"> <input type=submit class=button value=Ändern>";
		$tadd = "<strong>".$fieldData[build][name]."</strong>";
		if ($fieldData[build][id] != 218)
		{
			$fieldData[data][aktiv] == 0 ? $status = "<a href=main.php?page=colony&section=showcolony&id=".$id."&field=".$field."&action=activate onMouseOver=document.gebact.src='".$grafik."/buttons/gebact2.gif' onMouseOut=document.gebact.src='".$grafik."/buttons/gebact1.gif'><img src=".$grafik."/buttons/gebact1.gif name=gebact border=0> Aktivieren</a>" : $status = "<a href=main.php?page=colony&section=showcolony&id=".$id."&field=".$field."&action=deactivate onMouseOver=document.gebdact.src='".$grafik."/buttons/gebdact2.gif' onMouseOut=document.gebdact.src='".$grafik."/buttons/gebdact1.gif'><img src=".$grafik."/buttons/gebdact1.gif name=gebdact border=0> Deaktivieren</a>";
			$status .= " <a href=main.php?page=colony&section=delete&id=".$id."&field=".$field."><img src=".$grafik."/buttons/demont.gif border=0> <font color=red>Demontieren</font></a> <a href=?page=colony&section=repairbuilding&mode=field&field=".$field."&id=".$id." onMouseOver=document.gebrep.src='".$grafik."/buttons/rep2.gif' onMouseOut=document.gebrep.src='".$grafik."/buttons/rep1.gif'><img src=".$grafik."/buttons/rep1.gif name=gebrep border=0> Reparieren</a>";
		}
	}
	if ($fieldData[data][buildtime] == 0)
	{
		if ($fieldData[build][id] == 14) $upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zum Doppelteilchenbeschleuniger</a> (<a href=main.php?page=showinfo&id=15&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		if (($fieldData[build][id] == 51) && ($myColony->getuserresearch(114,$user) == 1)) $upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Modulationscomputer installieren</a> (<a href=main.php?page=showinfo&id=81&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		if (($fieldData[build][id] == 1) && ($myColony->getuserresearch(81,$user) == 1) && ($myColony->ccolonies_classes_id == 1))
		{
			if ($myUser->urasse == 1) $newbuild = 63;
			if ($myUser->urasse == 2) $newbuild = 64;
			if ($myUser->urasse == 3) $newbuild = 65;
			if ($myUser->urasse == 4) $newbuild = 66;
			if ($myUser->urasse == 5) $newbuild = 89;
			$upbuilddat = $myColony->getbuildbyid($newbuild);
			$upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zu ".$upbuilddat[name]."</a> (<a href=main.php?page=showinfo&id=".$newbuild."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		}
		if ($fieldData[build][id] == 107 && $myColony->getuserresearch(161,$user) == 1)
		{
			if ($myUser->urasse == 1) $newbuild = 108;
			if ($myUser->urasse == 2) $newbuild = 109;
			if ($myUser->urasse == 3) $newbuild = 110;
			if ($myUser->urasse == 4) $newbuild = 111;
			if ($myUser->urasse == 5) $newbuild = 112;
			$upbuilddat = $myColony->getbuildbyid($newbuild);
			$upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zu ".$upbuilddat[name]."</a> (<a href=main.php?page=showinfo&id=".$newbuild."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		}
		if (($fieldData[build][id] == 114) && ($myColony->getuserresearch(162,$user) == 1))
		{
			if ($myUser->urasse == 1) $newbuild = 115;
			if ($myUser->urasse == 2) $newbuild = 116;
			if ($myUser->urasse == 3) $newbuild = 117;
			if ($myUser->urasse == 4) $newbuild = 117;
			if ($myUser->urasse == 5) $newbuild = 119;
			$upbuilddat = $myColony->getbuildbyid($newbuild);
			$upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zu ".$upbuilddat[name]."</a> (<a href=main.php?page=showinfo&id=".$newbuild."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		}
		if (($fieldData[build][id] == 121) && ($myColony->getuserresearch(163,$user) == 1))
		{
			if ($myUser->urasse == 1) $newbuild = 122;
			if ($myUser->urasse == 2) $newbuild = 123;
			if ($myUser->urasse == 3) $newbuild = 124;
			if ($myUser->urasse == 4) $newbuild = 125;
			if ($myUser->urasse == 5) $newbuild = 126;
			$upbuilddat = $myColony->getbuildbyid($newbuild);
			$upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zu ".$upbuilddat[name]."</a> (<a href=main.php?page=showinfo&id=".$newbuild."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		}
		if ($fieldData[build][id] == 128 && $myColony->getuserresearch(164,$user) == 1)
		{
			if ($myUser->urasse == 1) $newbuild = 129;
			if ($myUser->urasse == 2) $newbuild = 130;
			if ($myUser->urasse == 3) $newbuild = 131;
			if ($myUser->urasse == 4) $newbuild = 132;
			if ($myUser->urasse == 5) $newbuild = 133;
			$upbuilddat = $myColony->getbuildbyid($newbuild);
			$upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zu ".$upbuilddat[name]."</a> (<a href=main.php?page=showinfo&id=".$newbuild."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		}
		if ($fieldData[build][id] == 36 && $myColony->getuserresearch(88,$user) == 1)
		{
			$upbuilddat = $myColony->getbuildbyid(78);
			$upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zum ".$upbuilddat[name]."</a> (<a href=main.php?page=showinfo&id=78&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		}
		if ($fieldData[build][id] == 21 && $myColony->getuserresearch(211,$user) == 1)
		{
			$upbuilddat = $myColony->getbuildbyid(168);
			$upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zum ".$upbuilddat[name]."</a> (<a href=main.php?page=showinfo&id=168&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
		}
	}
	if (($fieldData[build][id] == 3) && ($myUser->ulevel >= 5)) $upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=upgrade&id=".$id."&field=".$field.">Upgrade zur Stadt</a> (<a href=main.php?page=showinfo&id=16&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
	if (($fieldData[build][id] == 1) || ($fieldData[build][id] == 23) || ($fieldData[build][id] == 63) || ($fieldData[build][id] == 64) || ($fieldData[build][id] == 65) || ($fieldData[build][id] == 66) || ($fieldData[build][id] == 89) || ($fieldData[build][id] == 163) || ($fieldData[build][id] == 178)) $kolzen = "<br><br>
			<a href=main.php?page=colony&section=beam&way=to&id=".$id." onMouseOver=document.b_up.src='".$grafik."/buttons/b_up2.gif' onMouseOut=document.b_up.src='".$grafik."/buttons/b_up1.gif'><img src=".$grafik."/buttons/b_up1.gif name=b_up border=0> Hochbeamen</a><br>
			<a href=main.php?page=colony&section=beam&way=from&id=".$id." onMouseOver=document.b_down.src='".$grafik."/buttons/b_down2.gif' onMouseOut=document.b_down.src='".$grafik."/buttons/b_down1.gif'><img src=".$grafik."/buttons/b_down1.gif name=b_down border=0> Runterbeamen</a><br>
			<a href=main.php?page=colony&section=etrans&id=".$id." onMouseOver=document.e_trans.src='".$grafik."/buttons/e_trans2.gif' onMouseOut=document.e_trans.src='".$grafik."/buttons/e_trans1.gif'><img src=".$grafik."/buttons/e_trans1.gif name=e_trans border=0> Energietransfer</a><br>
			<a href=main.php?page=colony&section=ebuild&id=".$id." onMouseOver=document.e_build.src='".$grafik."/buttons/e_trans2.gif' onMouseOut=document.e_build.src='".$grafik."/buttons/e_trans1.gif'><img src=".$grafik."/buttons/e_trans1.gif name=e_build border=0> Gebäudeschaltung</a>";
	if ($fieldData[build][id] == 32 || $fieldData[build][id] == 151) $research = "<br><br><a href=main.php?page=colony&section=researchlist&id=".$id." onMouseOver=cp('forsch','buttons/forsch2') onMouseOut=cp('forsch','buttons/forsch1')><img src=".$grafik."/buttons/forsch1.gif name=forsch border=0> Forschung</a>";
	if ($fieldData[build][id] == 32 || $fieldData[build][id] == 151) include_once("inc/unkres.inc.php");
	if ($fieldData[build][id] == 35)
	{
		$replikator = "<form action=main.php mehtod=post name=raff>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=raffinerie>
		<input type=hidden name=id value=".$id.">
		<table width=230 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain colspan=3 align=center><strong>Veredelung</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg align=center>von</td>
			<td class=tdmainobg></td>
			<td class=tdmainobg align=center>zu</td>
		</tr>
		<tr>
			<td class=tdmainobg><input type=radio name=source value=11> <img src=".$grafik."/goods/11.gif title='Kelbonit-Erz'> <img src=".$grafik."/goods/11.gif title='Kelbonit-Erz'> <img src=".$grafik."/goods/11.gif title='Kelbonit-Erz'></td>
			<td class=tdmainobg></td>
			<td class=tdmainobg><img src=".$grafik."/goods/12.gif title='Kelbonit'></td>
		</tr>
		<tr>
			<td class=tdmainobg></td>
			<td valign=middle class=tdmainobg>+ <input type=text size=2 name=count class=text> Energie</td>
			<td class=tdmainobg></td>
		</tr>
		<tr>
			<td class=tdmainobg><input type=radio name=source value=13> <img src=".$grafik."/goods/13.gif title='Nitrium-Erz'> <img src=".$grafik."/goods/13.gif title='Nitrium-Erz'> <img src=".$grafik."/goods/13.gif title='Nitrium-Erz'></td>
			<td class=tdmainobg></td>
			<td class=tdmainobg><img src=".$grafik."/goods/14.gif title='Nitrium'></td>
		</tr>
		<tr>
			<td colspan=3 align=center class=tdmain><input name=send type=submit value=Raffinierung class=button onClick=\"this.disabled=true;document.raff.submit()\"></td>
		</tr></table>";
	}
	//if ($fieldData[build][id] == 55) $spy = "<br><br><a href=?page=colony&section=spy&id=".$id." onMouseOver=document.spy.src='".$grafik."/buttons/lupe2.gif' onMouseOut=document.spy.src='".$grafik."/buttons/lupe1.gif'><img src=".$grafik."/buttons/lupe1.gif name=spy border=0> Spionage</a>";
	if (($fieldData[build][id] == 51) || ($fieldData[build][id] == 81))
	{
		$shield = "<br><br><a href=main.php?page=colony&section=loadshields&id=".$id." onMouseOver=document.shldp.src='".$grafik."/buttons/shldp2.gif' onMouseOut=document.shldp.src='".$grafik."/buttons/shldp1.gif'><img src=".$grafik."/buttons/shldp1.gif name=shldp border=0> Schilde aufladen</a>";
		if ($myColony->cschilde_aktiv == 0) $shield .= " <a href=main.php?page=colony&section=showcolony&action=activateshields&id=".$id." onMouseOver=document.shldac.src='".$grafik."/buttons/shldac2.gif' onMouseOut=document.shldac.src='".$grafik."/buttons/shldac1.gif'><img src=".$grafik."/buttons/shldac1.gif name=shldac border=0> Schilde aktivieren</a>";
		else $shield .= " <a href=main.php?page=colony&section=showcolony&action=deactivateshields&id=".$id." onMouseOver=document.shldac.src='".$grafik."/buttons/shldac1.gif' onMouseOut=document.shldac.src='".$grafik."/buttons/shldac2.gif'><img src=".$grafik."/buttons/shldac2.gif name=shldac border=0> Schilde deaktivieren</a>";
	}

	if ($fieldData[build][id] == 218)
	{
		if ($fieldData[data][buildtime] == 0)
		{
			if ($fieldData[data][integrity] == 250) $shield .= "<a href=main.php?page=colony&section=showcolony&action=jemhadar&type=5&field=$field&id=".$id." onMouseOver=document.jem5.src='".$grafik."/buttons/einwand1.gif' onMouseOut=document.jem5.src='".$grafik."/buttons/einwand0.gif'><img src=".$grafik."/buttons/einwand0.gif name=jem5 border=0> Schiff erkunden</a>";
			if ($fieldData[data][integrity] < 250)
			{
				if ($fieldData[data][aktiv] == 0) 
				{
					$shield .= "<br><a href=main.php?page=colony&section=showcolony&action=jemhadar&type=1&field=$field&id=".$id." onMouseOver=document.jem1.src='".$grafik."/buttons/e_trans2.gif' onMouseOut=document.jem1.src='".$grafik."/buttons/e_trans1.gif'><img src=".$grafik."/buttons/e_trans1.gif name=jem1 border=0> Reaktor hochfahren</a>";
				}
				else
				{
					$shield .= "<br><a href=main.php?page=colony&section=showcolony&action=jemhadar&type=2&field=$field&id=".$id." onMouseOver=document.jem2.src='".$grafik."/buttons/e_trans1.gif' onMouseOut=document.jem2.src='".$grafik."/buttons/e_trans2.gif'><img src=".$grafik."/buttons/e_trans2.gif name=jem2 border=0> Reaktor runterfahren</a>";
				}
				$shield .= "<br><a href=main.php?page=colony&section=showcolony&action=jemhadar&type=3&field=$field&id=".$id."><img src=".$grafik."/goods/98.gif name=jem3 border=0> Waffen demontieren</a>";
				$shield .= "<br><a href=main.php?page=colony&section=showcolony&action=jemhadar&type=4&field=$field&id=".$id."><img src=".$grafik."/buttons/gefecht.gif name=jem4 border=0> Zugriff auf Hauptcomputer</a>";
			}
		}
	}

	if ($fieldData[build][id] == 201) $swap = "<br><br><a href=main.php?page=colony&section=showcolony&action=swapbuilding&id=".$id."&field=".$field."&newid=202> <img src=".$grafik."/goods/12.gif name=Kelbonit border=0> Auf Kelbonitverarbeitung umstellen</a><br>";
	if ($fieldData[build][id] == 202) $swap = "<br><br><a href=main.php?page=colony&section=showcolony&action=swapbuilding&id=".$id."&field=".$field."&newid=201> <img src=".$grafik."/goods/14.gif name=Nitrium border=0> Auf Nitriumverarbeitung umstellen</a><br>";
	if ($fieldData[build][id] == 88) $cloakf = "<br><br><a href=main.php?page=colony&section=movecloak&id=".$id." onMouseOver=document.mcloa.src='".$grafik."/buttons/graviton2.gif' onMouseOut=document.mcloa.src='".$grafik."/buttons/graviton1.gif'><img src=".$grafik."/buttons/graviton1.gif name=mcloa border=0> Tarnfeld ausrichten</a>";
	include_once("inc/mod_rep.inc.php");
	if ($fieldData[build][id] == 37)
	{


		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Photonentorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[7] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/7.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Plasmatorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[16] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/16.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Quantentorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[17] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/17.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Merculittorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[26] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/26.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Polarontorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[27] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/27.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Positrontorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[29] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/29.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Adv. Photonentorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[40] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/40.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Raketen</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[41] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/41.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Quad Adv. Photonentorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[201] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/201.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Quad Quantentorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[202] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/202.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Tripel Plasmatorpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[203] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/203.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Isolytische Torpedos</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[205] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/205.gif></td>
                          </tr>";
		$torp .= "<tr><td class=tdmainobg colspan=3 align=center>Drohnen</td></tr><tr>
                              <td class=tdmainobg><img src=".$grafik."/goods/3.gif></td>
		              <td class=tdmainobg>+ <input type=text size=2 name=count[209] class=text> Energie</td>
			      <td class=tdmainobg><img src=".$grafik."/goods/209.gif></td>
                          </tr>";
		$replikator = "<form action=main.php mehtod=post name=repl>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=replikator>
		<input type=hidden name=id value=".$id.">
		<table width=450 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr><td class=tdmain align=Center colspan=3><strong>Replikation</strong></td></tr>
		<tr>
			<td class=tdmainobg align=center>von</td>
			<td class=tdmainobg align=center></td>
			<td class=tdmainobg align=center>zu</td>
		</tr>

		".$torp."
		<tr>
			<td colspan=3 align=center class=tdmain><input name=send type=submit value=Replikation class=button onClick=\"this.disabled=true;document.repl.submit()\"></td>
		</tr>
		</table></form>";
	}
	if ($fieldData[data][buildings_id] == 157)
	{
		$replikator = "<form action=main.php mehtod=post name=lat>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=makelat>
		<input type=hidden name=id value=".$id.">
		<table width=350 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr><td class=tdmain align=Center colspan=3><strong>Latinumpressung</strong></td></tr>
		<tr>
		<td class=tdmainobg>60 <img src=".$grafik."/goods/34.gif title='Ring-Materie'> 1 <img src=".$grafik."/goods/3.gif title='Baumaterial'> 10 <img src=".$grafik."/buttons/e_trans2.gif title='Energie'></td>
		<td valign=middle class=tdmainobg><input type=text size=2 name=count class=text></td>
		<td class=tdmainobg>1 <img src=".$grafik."/goods/24.gif title='Latinum'></td>
		</tr>
		<tr>
			<td colspan=3 align=center class=tdmain><input type=submit value=pressen class=button name=send onClick=\"this.disabled=true;document.lat.submit()\"></td>
		</tr>
		</table></form>";
	}
	if ($fieldData[data][buildings_id] >= 210 && $fieldData[data][buildings_id] <= 215)
	{
		$choices = $myAlly->getembassyoptions($myUser->ually);
		$botschaft = "<br><br><br><br><form action=main.php mehtod=post name=botschaft>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=embowner>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=field value=".$field.">
		<table width=350 bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr><td class=tdmainobg align=Center colspan=2><strong>Botschaft an Allianz übergeben</strong></td></tr>";
		$belegt = $myAlly->getembassyownerbycolony($id,$field);
		if ($belegt != 0)
		{
			$owner = $myAlly->getallybyid($belegt);
			$botschaft .= "<tr>
				<td align=center class=tdmainobg width=20><input type=radio name=embassy value=$owner[id] disabled=yes></td>
				<td align=center class=tdmainobg>".stripslashes($owner[name])."</td>
			</tr>";
		}
		if ($belegt == 0)
		{
			$botschaft .= "<tr>
			<td align=center class=tdmainobg width=20><input type=radio name=embassy value=0 disabled=yes></td>
			<td align=center class=tdmainobg>Niemand</td>
			</tr>";
		}
		while($allys=mysql_fetch_array($choices))
		{
			$botschaft .= "<tr>
				<td align=center class=tdmainobg width=20><input type=radio name=embassy value=$allys[id]></td>
				<td align=center class=tdmainobg>".stripslashes($allys[name])."</td>
			</tr>";
		}
		if ($belegt != 0)
		{
			$botschaft .= "<tr>
			<td align=center class=tdmainobg width=20><input type=radio name=embassy value=0></td>
			<td align=center class=tdmainobg>Botschafter ausweisen</td>
			</tr>";
		}
		$botschaft .= "<tr>
			<td colspan=2 align=center class=tdmainobg><input type=submit value=übergeben class=button name=send ></td>
		</tr>
		</table></form>";
	}
	if ($fieldData[data][buildtime] > 0 && time() < $fieldData[data][buildtime])
	{
		if ($fieldData[data][buildings_id] == 218)
		{
			$badd = "Technikerteam noch nicht wieder verfügbar.<br>Verfügbar am ".date("d.m.Y H:i",$fieldData[data][buildtime])." <script type=\"text/javascript\">";
		}
		else
		{
			$badd = "Dieses Gebäude befindet sich zur Zeit in Bau.<br>Fertigstellung am ".date("d.m.Y H:i",$fieldData[data][buildtime])." <script type=\"text/javascript\">";

		}
		$badd .= "var NS6 = (!document.all && document.getElementById) ? true : false;
		var NS = document.layers ? 1:0;
		var IE = document.all ? 1:0;
		var gecko = document.getElementById ? 1:0;
		var wielang = \"".($fieldData[data][buildtime]-time())."\";
		var target=\"bzeit\";
		function countdown()
		{
		    sekunden=wielang;
		    stunden=Math.floor(sekunden/3600);
		    sekunden-=stunden*3600;
		    minuten=Math.floor(sekunden/60);
		    sekunden-=minuten*60;
			if (sekunden<10) sekunden = \"0\"+sekunden;
			if (minuten<10) minuten = \"0\"+minuten;
			if (stunden<10) stunden = \"0\"+stunden;
			if (stunden == 0) {
				text=\"Restzeit: \"+minuten+\":\"+sekunden;
		    } else {
				text=\"Restzeit: \"+stunden+\":\"+minuten+\":\"+sekunden;
		    }
			if (NS)
		    {
		        x = document.layers[target];
		        text2 = '<p>' + text + '</p>';
		        x.document.open();
		        x.document.write(text2);
		        x.document.close();
		    }
		    else if (IE)
		    {
		        x = document.all[target];
		        x.innerHTML = text;
		    }
		    else if (gecko)
		    {
		        x = document.getElementById(target);
		        x.innerHTML = text;
		    }
		    wielang--;
		    if (wielang!=0) window.setTimeout(\"countdown();\",1000)
		}
		</script><br><div id=\"bzeit\"><script type=\"text/javascript\">countdown(".($fieldData[data][buildtime]-time()).");</script></div><br>";
		$sid = 106;
		if ($fieldData[data][buildings_id] != 218)
		{
			$fieldData[data][aktiv] == 0 ? $anb = "Ja - <a href=?page=colony&section=showcolony&id=".$id."&field=".$field."&action=chbam&m=2>Ändern</a>" : $anb = "Nein - <a href=?page=colony&section=showcolony&id=".$id."&field=".$field."&action=chbam&m=1>Ändern</a>";
			$bdadd = "Aktiviert nach Bau: ".$anb."<br><br>";
		}
	}
	else $sid = $fieldData[build][id];
	if ($myColony->cdn_mode == 2 && $myColony->ccolonies_classes_id != 9) $n = "n/";
	if ($fieldData[build][id] == 0)
	{
		$pic = "<img src=".$grafik."/fields/".$n.$fieldData[data][type].".gif>";
	}
	elseif ($fieldData[build][secretimage] != "0")
	{
		if ($sid != 106) $sid = $fieldData[build][secretimage];
		$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$n.$sid."_".$fieldData[data][type].".gif>";
	}
	else
	{
		$pic = "<img src=".$grafik."/buildings/".$n.$sid."_".$fieldData[data][type].".gif>";
	}
	$fieldData[data][name] != "" ? $nadd = " / ".$fieldData[data][name] : $nadd = "";
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Oberflächenfeld ".($field+1)."</strong>".$nadd."</td>
	</tr>
	</table><br>
	<table width=600 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<form action=main.php method=post>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=changebuildname>
	<input type=hidden name=mode value=field>
	<input type=hidden name=field value=".$fieldData[data][field_id].">
	<input type=hidden name=id value=".$id.">
	<tr>
		<td valign=top class=tdmainobg width=330>".$tadd."<br>".$pic."<br>";
	if ($fieldData[build][id] > 0) echo "Integrität: ".$fieldData[data][integrity]."/".$fieldData[build][integrity]."<br><br>";
	echo $badd.$bdadd.$status.$kolzen.$upgrade.$research.$spy.$shield.$cloakf.$swap.$botschaft."<br>".$chgn;
	echo "</td><td valign=top class=tdmainobg width=210><b>Baumenü</b><br>";
	if ($fieldData[build][id] == 0)
	{
		$user < 100 ? $result = $myDB->query("SELECT a.type,b.id,b.research_id,b.name FROM stu_field_build as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.type=".$fieldData[data][type]." AND a.buildings_id!=1 AND a.buildings_id!=23 AND a.buildings_id!=89 AND (a.buildings_id<63 OR a.buildings_id>66) ORDER by b.name") : $result = $myDB->query("SELECT a.buildings_id,a.type,b.id,b.research_id,b.name FROM stu_field_build as a LEFT OUTER JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.type=".$fieldData[data][type]." AND a.buildings_id!=1 AND a.buildings_id!=23 AND a.buildings_id!=89 AND (a.buildings_id<63 OR a.buildings_id>66) AND b.view=1 AND b.level<=".$myUser->ulevel." ORDER by b.name");
		if (mysql_num_rows($result) == 0) echo "Keine Gebäude für dieses Feld vorhanden";
		else
		{
			while($data=mysql_fetch_assoc($result))
			{
				if ($data[id] == 210)
				{
					if ($myAlly->checkembassybuild() != 0)
					{
						$echo = 1;
					}
					else $echo = 0;
				}
				else
				{
					if ($data[research_id] > 0) if ($myDB->query("SELECT COUNT(user_id) FROM stu_research_user WHERE research_id=".$data[research_id]." AND user_id=".$user,1) == 1) $echo = 1;
					if ($data[research_id] == 0) $echo = 1;
				}
				if (($data[id] == 2) && ($myColony->ccolonies_classes_id == 10))
				{
					$echo = 0;
				}
				if (($data[id] == 207) && ($myColony->ccolonies_classes_id != 10))
				{
					$echo = 0;
				}
				if ($echo == 1) echo "<a href=main.php?page=colony&section=showcolony&field=".$field."&building=".$data[id]."&action=build&id=".$id.">".$data[name]."</a> (<a href=main.php?page=showinfo&id=".$data[id]."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)<br>";
				$echo = 0;
			}
		}
		$terraform = $myColony->getpossibleterraforming($fieldData[data][type]);
		if (is_array($terraform))
		{
			echo "<br><br><b>Terraforming</b><br>";
			for ($i=0;$i<count($terraform);$i++) echo "<a href=main.php?page=colony&section=showcolony&action=terraform&id=".$id."&field=".$field."&terraform=".$terraform[$i][id]."&mode=field>".$terraform[$i][name]."</a> (<a href=main.php?page=showinfo&id=".$terraform[$i][id]."&section=terraform target=leftbottom>?</a>)<br>";
		}
	}
	echo "</td>
	<td class=tdmainobg valign=top width=70><img src=".$grafik."/buttons/e_trans2.gif> ".$myColony->cenergie."<br>";
	$g = $myDB->query("SELECT a.id,a.name,b.count,a.secretimage FROM stu_goods as a LEFT JOIN stu_colonies_storage as b ON a.id=b.goods_id WHERE a.id<50 AND a.id!=16 AND a.id!=17 AND a.id!=24 AND b.colonies_id=".$id." ORDER BY a.sort");
	while($good=mysql_fetch_assoc($g)) 
	{
		if ($good[secretimage] != "0")
		{
			echo "<img src=http://www.stuniverse.de/gfx/secret/".$good[secretimage].".gif title='".$good[name]."'> ".$good['count']."<br>";
		}
		else
		{
			echo "<img src=".$grafik."/goods/".$good[id].".gif title='".$good[name]."'> ".$good['count']."<br>";
		}
	}
	echo "</td>
	</tr>
	</form></table>
	<br>
	".$replikator;
}
elseif ($section == "orbitfield")
{
	$fieldData = $myColony->getorbitfieldbyid($field,$id);
	if ($fieldData[data][type] == 12) $feld = "Weltraum";
	if ($fieldData[data][type] == 37) $feld = "Asteroidengürtel";
	if ($fieldData[build] == 0)
	{
		$status = "nicht bebaut";
		$tadd = $feld;
	}
	else
	{
		$chgn = "Name: <input class=text type=text size=25 name=buildname value=\"".$fieldData[data][name]."\"> <input type=submit class=button value=Ändern>";
		$tadd = "<strong>".$fieldData[build][name]."</strong>";
		$fieldData[data][aktiv] == 0 ? $status = "<a href=main.php?page=colony&section=showcolony&id=".$id."&field=".$field."&action=orbitactivate onMouseOver=document.gebact.src='".$grafik."/buttons/gebact2.gif' onMouseOut=document.gebact.src='".$grafik."/buttons/gebact1.gif'><img src=".$grafik."/buttons/gebact1.gif name=gebact border=0> Aktivieren</a>" : $status = "<a href=main.php?page=colony&section=showcolony&id=".$id."&field=".$field."&action=orbitdeactivate onMouseOver=document.gebdact.src='".$grafik."/buttons/gebdact2.gif' onMouseOut=document.gebdact.src='".$grafik."/buttons/gebdact1.gif'><img src=".$grafik."/buttons/gebdact1.gif name=gebdact border=0> Deaktivieren</a>";
		$status .= " <a href=main.php?page=colony&section=orbitdelete&id=".$id."&field=".$field."><img src=".$grafik."/buttons/demont.gif border=0> <font color=red>Demontieren</font></a>  <a href=?page=colony&section=repairbuilding&mode=orbit&field=".$field."&id=".$id." onMouseOver=document.gebrep.src='".$grafik."/buttons/rep2.gif' onMouseOut=document.gebrep.src='".$grafik."/buttons/rep1.gif'><img src=".$grafik."/buttons/rep1.gif name=gebrep border=0> Reparieren</a>";
		if ($fieldData[build][id] == 26 || $fieldData[build][id] == 27 || $fieldData[build][id] == 28 || $fieldData[build][id] == 29 || $fieldData[build][id] == 30 || $fieldData[build][id] == 135 || $fieldData[build][id] == 149)
		{
			$werft = "<br><a href=main.php?page=colony&section=loadbatt&id=".$id." onMouseOver=document.batt.src='".$grafik."/buttons/battp2.gif' onMouseOut=document.batt.src='".$grafik."/buttons/battp1.gif'><img src=".$grafik."/buttons/battp1.gif name=batt border=0> Ersatzbatterie aufladen</a>
			<br><a href=main.php?page=colony&section=repairship&id=".$id." onMouseOver=document.rep.src='".$grafik."/buttons/rep2.gif' onMouseOut=document.rep.src='".$grafik."/buttons/rep1.gif'><img src=".$grafik."/buttons/rep1.gif name=rep border=0> Schiff reparieren</a>
			<br><a href=?page=colony&section=demontship&id=".$id." onMouseOver=document.demontship.src='".$grafik."/buttons/demship2.gif' onMouseOut=document.demontship.src='".$grafik."/buttons/demship1.gif'><img src=".$grafik."/buttons/demship1.gif name=demontship border=0> Schiff demontieren</a>";
			if ($fieldData[build][id] != 26 && $myColony->getuserresearch(111,$user) == 1) $werft .= "<br><a href=main.php?page=colony&section=shipupgrade&id=".$id." onMouseOver=document.upgradeship.src='".$grafik."/buttons/upgr2.gif' onMouseOut=document.upgradeship.src='".$grafik."/buttons/upgr1.gif'><img src=".$grafik."/buttons/upgr1.gif name=upgradeship border=0> Schiffsupgrade</a>";
		}
	}
	include_once("inc/mod_orep.inc.php");
	if ($fieldData[build][id] == 53) $teleskop = "<br><a href=main.php?page=colony&section=teleskop&id=".$id." onMouseOver=document.scan.src='".$grafik."/buttons/lupe2.gif' onMouseOut=document.scan.src='".$grafik."/buttons/lupe1.gif'><img src=".$grafik."/buttons/lupe1.gif name=scan border=0> Sektorscan</a>";
	if ($fieldData[build][id] == 84) $sensornetz = "<br><a href=main.php?page=colony&section=sensornetz&id=".$id." onMouseOver=document.scan.src='".$grafik."/buttons/lupe2.gif' onMouseOut=document.scan.src='".$grafik."/buttons/lupe1.gif'><img src=".$grafik."/buttons/lupe1.gif name=scan border=0> Sensorabtastung</a>";
	if ($fieldData[build][id] == 102) $horchposten = "<br><a href=main.php?page=colony&section=horchposten&id=".$id." onMouseOver=document.scan.src='".$grafik."/buttons/lupe2.gif' onMouseOut=document.scan.src='".$grafik."/buttons/lupe1.gif'><img src=".$grafik."/buttons/lupe1.gif name=scan border=0> Langstreckenscan</a>";
	if (($fieldData[data][buildtime] > 0) && (time() < $fieldData[data][buildtime]))
	{
		$badd = "<br>Dieses Gebäude befindet sich zur Zeit in Bau.<br>Fertigstellung am ".date("d.m.Y H:i",$fieldData[data][buildtime])." <script type=\"text/javascript\">
		var NS6 = (!document.all && document.getElementById) ? true : false;
		var NS = document.layers ? 1:0;
		var IE = document.all ? 1:0;
		var gecko = document.getElementById ? 1:0;
		
		var wielang = \"".($fieldData[data][buildtime]-time())."\";
		var target=\"bzeit\";
		function countdown()
		{
		    sekunden=wielang;
		    stunden=Math.floor(sekunden/3600);
		    sekunden-=stunden*3600;
		    minuten=Math.floor(sekunden/60);
		    sekunden-=minuten*60;
			if (sekunden<10) sekunden = \"0\"+sekunden;
			if (minuten<10) minuten = \"0\"+minuten;
			if (stunden<10) stunden = \"0\"+stunden;
			if (stunden == 0) {
				text=\"Restzeit: \"+minuten+\":\"+sekunden;
		    } else {
				text=\"Restzeit: \"+stunden+\":\"+minuten+\":\"+sekunden;
		    }
			if (NS)
		    {
		        x = document.layers[target];
		        text2 = '<p>' + text + '</p>';
		        x.document.open();
		        x.document.write(text2);
		        x.document.close();
		    }
		    else if (IE)
		    {
		        x = document.all[target];
		        x.innerHTML = text;
		    }
		    else if (gecko)
		    {
		        x = document.getElementById(target);
		        x.innerHTML = text;
		    }
		    wielang--;
		    if (wielang!=0) window.setTimeout(\"countdown();\",1000)
		}
		</script><br><div id=\"bzeit\"><script type=\"text/javascript\">countdown(".($fieldData[data][buildtime]-time()).");</script></div><br>";
		$sid = 106;
		$fieldData[data][aktiv] == 0 ? $anb = "Ja - <a href=?page=colony&section=showcolony&id=".$id."&field=".$field."&action=chobam&m=2>Ändern</a>" : $anb = "Nein - <a href=?page=colony&section=showcolony&id=".$id."&field=".$field."&action=chobam&m=1>Ändern</a>";
		$bdadd = "Aktiviert nach Bau: ".$anb."<br><br>";
	}
	else $sid = $fieldData[build][id];
	if ($fieldData[build][id] == 0)
	{
		$pic = "<img src=".$grafik."/fields/".$fieldData[data][type].".gif>";
	}
	elseif ($fieldData[build][secretimage] != "0")
	{
		if ($sid != 106) $sid = $fieldData[build][secretimage];
		$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$sid."_".$fieldData[data][type].".gif>";
	}
	else
	{
		$pic = "<img src=".$grafik."/buildings/".$sid."_".$fieldData[data][type].".gif>";
	}
	$redat = $myColony->getuserresearch(5,$user);
	if (($redat == 1) && ($fieldData[build][id] == 26))
	{
		if ($myUser->urasse == 1) $showid = 27;
		elseif ($myUser->urasse == 2) $showid = 28;
		elseif ($myUser->urasse == 3) $showid = 29;
		elseif ($myUser->urasse == 4) $showid = 30;
		elseif ($myUser->urasse == 5) $showid = 135;
		$upgrade = "<br><a href=main.php?page=colony&section=showcolony&action=orbitupgrade&field=".$field."&id=".$id.">Upgrade zur erweiterten Werft</a> (<a href=main.php?page=showinfo&id=".$showid."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
	}
	if ($fieldData[build][id] == 45)
	{
		$research = "<br><br><a href=main.php?page=colony&section=researchlist&id=".$id." onMouseOver=cp('forsch','buttons/forsch2') onMouseOut=cp('forsch','buttons/forsch1')><img src=".$grafik."/buttons/forsch1.gif name=forsch border=0> Forschung</a>";
		include_once("inc/unkres.inc.php");
	}
	if (($fieldData[build][id] > 25 && $fieldData[build][id] < 31) || $fieldData[build][id] == 135)
	{
		$sprog = $myColony->getProgressShips($id);
		$class = $myShip->getclassbyid($sprog[ships_rumps_id]);
		if ($sprog != 0) $spadd = "<br><br>Schiff in Bau (".$class[name].")<br>Fertigstellung: ".date("d.m.Y H:i:s",$sprog[buildtime]);
	}
	$fieldData[data][name] != "" ? $nadd = " / ".$fieldData[data][name] : $nadd = "";
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Orbitfeld ".($field+1)."</strong>".$nadd."</td>
	</tr>
	</table><br>
	<table width=600 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<form action=main.php method=post>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=changebuildname>
	<input type=hidden name=mode value=orbit>
	<input type=hidden name=field value=".$fieldData[data][field_id].">
	<input type=hidden name=id value=".$id.">
	<tr>
		<td valign=top class=tdmainobg width=330>".$tadd."<br>".$pic."<br>";
	if ($fieldData[build][id] > 0) echo "Integrität: ".$fieldData[data][integrity]."/".$fieldData[build][integrity]."<br><br>";
	echo $status.$werft.$upgrade.$research.$teleskop.$sensornetz.$horchposten.$badd.$bdadd.$spadd."<br>".$chgn;
	echo "</td><td valign=top class=tdmainobg width=200><b>Baumenü</b><br>";
	if ($fieldData[build] == 0)
	{
		$user < 100 ? $result = $myDB->query("SELECT a.type,b.id,b.research_id,b.name FROM stu_field_build as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.type=".$fieldData[data][type]." AND a.buildings_id!=1 AND a.buildings_id!=23 AND a.buildings_id!=89 AND (a.buildings_id<63 OR a.buildings_id>66) ORDER by b.name") : $result = $myDB->query("SELECT a.buildings_id,a.type,b.id,b.research_id,b.name FROM stu_field_build as a LEFT OUTER JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.type=".$fieldData[data][type]." AND a.buildings_id!=1 AND a.buildings_id!=23 AND a.buildings_id!=89 AND (a.buildings_id<63 OR a.buildings_id>66) AND b.view=1 AND b.level<=".$myUser->ulevel." ORDER by b.name");
		if (mysql_num_rows($result) == 0) echo "Keine Gebäude für dieses Feld vorhanden";
		else
		{
			while($data=mysql_fetch_assoc($result))
			{
				if ($data[research_id] > 0) if ($myDB->query("SELECT COUNT(user_id) FROM stu_research_user WHERE research_id=".$data[research_id]." AND user_id=".$user,1) == 1) $echo = 1;
				if ($data[research_id] == 0) $echo = 1;
				if ((($data[id] == 5) || ($data[id] == 25)) && ($myColony->ccolonies_classes_id == 10))
				{
					$echo = 0;
				}
				if ($echo == 1) echo "<a href=main.php?page=colony&section=showcolony&field=".$field."&building=".$data[id]."&action=orbitbuild&id=".$id.">".$data[name]."</a> (<a href=main.php?page=showinfo&id=".$data[id]."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)<br>";
				$echo = 0;
			}
		}
	}
	echo "</td>
	<td class=tdmainobg valign=top width=70><img src=".$grafik."/buttons/e_trans2.gif> ".$myColony->cenergie."<br>";
	$g = $myDB->query("SELECT a.id,a.name,b.count,a.secretimage FROM stu_goods as a LEFT JOIN stu_colonies_storage as b ON a.id=b.goods_id WHERE a.id<50 AND a.id!=1 AND a.id!=16 AND a.id!=17 AND a.id!=24 AND b.colonies_id=".$id." ORDER BY a.sort");
	while($good=mysql_fetch_assoc($g)) 
	{
		if ($good[secretimage] != "0")
		{
			echo "<img src=http://www.stuniverse.de/gfx/secret/".$good[secretimage].".gif title='".$good[name]."'> ".$good['count']."<br>";
		}
		else
		{
			echo "<img src=".$grafik."/goods/".$good[id].".gif title='".$good[name]."'> ".$good['count']."<br>";
		}
	}
	echo "</td>
	<tr></form>
	</table>
	<br><table width=300 cellpadding=1 cellspacing=1 bgcolor=#262323>";
	if (($fieldData[build][id] > 25 && $fieldData[build][id] < 31) || $fieldData[build][id] == 135)
	{
		$ships = $myColony->getpossibleships($user);
		if (mysql_num_rows($ships) != 0)
		{
			echo "<tr><td colspan=2 class=tdmain><strong>Baubare Schiffe</strong></td></tr>";
			while($sd=mysql_fetch_assoc($ships))
			{
				if (($fieldData[build][id] == 26 && $sd[ewerft] == 0) || (($fieldData[build][id] > 26 && $fieldData[build][id] < 31) || $fieldData[build][id] == 135))
				{
					if ($sd[secretimage] != "0")
					{
						$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$sd[secretimage].".gif>";
					}
					else
					{
						$shippic = "<img src=".$grafik."/ships/".$sd[id].".gif>";
					}
					echo "<tr>
						<td class=tdmainobg>".$shippic." ".$sd[name]." (<a href=main.php?page=shiphelp&section=rump&class=".$sd[id]." target=leftbottom>?</a>)</td>
						<td class=tdmainobg><a href=main.php?page=colony&section=buildship&id=".$id."&classid=".$sd[id].">bauen</a></td>
					</tr>";
				}
			}
		}
	}
	echo "</table><br>".$replikator;
}
elseif ($section == "groundfield")
{
	$myColony->gcc();
	if ($myColony->cshow == 0) exit;
	$fieldData = $myColony->getgroundfieldbyid($field,$id);
	if ($fieldData[data][type] == 14) $feld = "Felsen";
	if ($fieldData[data][type] == 15) $feld = "Freigesprengtes Feld";
	if ($fieldData[data][type] == 19) $feld = "Lavaspalt";
	if ($fieldData[data][type] == 32) $feld = "Unterirdischer Lavasee";
	if ($fieldData[build] == 0)
	{
		$status = "nicht bebaut";
		$tadd = $feld;
	}
	else
	{
		$chgn = "Name: <input class=text type=text size=25 name=buildname value=\"".$fieldData[data][name]."\"> <input type=submit class=button value=Ändern>";
		$tadd = "<strong>".$fieldData[build][name]."</strong>";
		$fieldData[data][aktiv] == 0 ? $status = "<a href=main.php?page=colony&section=showcolony&id=".$id."&field=".$field."&action=groundactivate onMouseOver=document.gebact.src='".$grafik."/buttons/gebact2.gif' onMouseOut=document.gebact.src='".$grafik."/buttons/gebact1.gif'><img src=".$grafik."/buttons/gebact1.gif name=gebact border=0> Aktivieren</a>" : $status = "<a href=main.php?page=colony&section=showcolony&id=".$id."&field=".$field."&action=grounddeactivate onMouseOver=document.gebdact.src='".$grafik."/buttons/gebdact2.gif' onMouseOut=document.gebdact.src='".$grafik."/buttons/gebdact1.gif'><img src=".$grafik."/buttons/gebdact1.gif name=gebdact border=0> Deaktivieren</a>";
		$status .= " <a href=main.php?page=colony&section=grounddelete&id=".$id."&field=".$field."><img src=".$grafik."/buttons/demont.gif border=0> <font color=red>Demontieren</font></a>  <a href=?page=colony&section=repairbuilding&mode=ground&field=".$field."&id=".$id." onMouseOver=document.gebrep.src='".$grafik."/buttons/rep2.gif' onMouseOut=document.gebrep.src='".$grafik."/buttons/rep1.gif'><img src=".$grafik."/buttons/rep1.gif name=gebrep border=0> Reparieren</a>";
	}
	if ($fieldData[build][id] == 35)
	{
		$replikator = "<form action=main.php mehtod=post>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=raffinerie>
		<input type=hidden name=id value=".$id.">
		<tr><td colspan=2 class=tdmain>
		<table align=center>
		<tr>
			<td class=tdmain align=center>von</td>
			<td class=tdmain align=center><strong>Veredelung</strong></td>
			<td class=tdmain align=center>zu</td>
		</tr>
		<tr>
			<td class=tdmain><input type=radio name=source value=11> <img src=".$grafik."/goods/11.gif title='Kelbonit-Erz'> <img src=".$grafik."/goods/11.gif title='Kelbonit-Erz'> <img src=".$grafik."/goods/11.gif title='Kelbonit-Erz'></td>
			<td class=tdmain></td>
			<td class=tdmain><img src=".$grafik."/goods/12.gif title='Kelbonit'></td>
		</tr>
		<tr>
			<td class=tdmain></td>
			<td valign=middle class=tdmain>+ <input type=text size=2 name=count class=text> Energie</td>
			<td class=tdmain></td>
		</tr>
		<tr>
			<td class=tdmain><input type=radio name=source value=13> <img src=".$grafik."/goods/13.gif title='Nitrium-Erz'> <img src=".$grafik."/goods/13.gif title='Nitrium-Erz'> <img src=".$grafik."/goods/13.gif title='Nitrium-Erz'></td>
			<td class=tdmain></td>
			<td class=tdmain><img src=".$grafik."/goods/14.gif title='Nitrium'></td>
		</tr>
		<tr>
			<td colspan=3 align=center class=tdmain><input type=submit value=Raffinierung class=button></td>
		</tr>";
	}
	if (($fieldData[build][id] == 36) && ($myColony->getuserresearch(88,$user) == 1)) $upgrade = "<br><br><a href=main.php?page=colony&section=showcolony&action=groundupgrade&id=".$id."&field=".$field.">Upgrade zum Doppelkonverter</a> (<a href=main.php?page=showinfo&id=78&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)";
	if (($fieldData[data][buildtime] > 0) && (time() < $fieldData[data][buildtime]))
	{
		$badd = "<br>Dieses Gebäude befindet sich zur Zeit in Bau.<br>Fertigstellung am ".date("d.m.Y H:i",$fieldData[data][buildtime])." <script type=\"text/javascript\">
		var NS6 = (!document.all && document.getElementById) ? true : false;
		var NS = document.layers ? 1:0;
		var IE = document.all ? 1:0;
		var gecko = document.getElementById ? 1:0;
		var wielang = \"".($fieldData[data][buildtime]-time())."\";
		var target=\"bzeit\";
		function countdown()
		{
		    sekunden=wielang;
		    stunden=Math.floor(sekunden/3600);
		    sekunden-=stunden*3600;
		    minuten=Math.floor(sekunden/60);
		    sekunden-=minuten*60;
			if (sekunden<10) sekunden = \"0\"+sekunden;
			if (minuten<10) minuten = \"0\"+minuten;
			if (stunden<10) stunden = \"0\"+stunden;
			if (stunden == 0) {
				text=\"Restzeit: \"+minuten+\":\"+sekunden;
		    } else {
				text=\"Restzeit: \"+stunden+\":\"+minuten+\":\"+sekunden;
		    }
			if (NS)
		    {
		        x = document.layers[target];
		        text2 = '<p>' + text + '</p>';
		        x.document.open();
		        x.document.write(text2);
		        x.document.close();
		    }
		    else if (IE)
		    {
		        x = document.all[target];
		        x.innerHTML = text;
		    }
		    else if (gecko)
		    {
		        x = document.getElementById(target);
		        x.innerHTML = text;
		    }
		    wielang--;
		    if (wielang!=0) window.setTimeout(\"countdown();\",1000)
		}
		</script><br><div id=\"bzeit\"><script type=\"text/javascript\">countdown(".($fieldData[data][buildtime]-time()).");</script></div><br>";
		$sid = 106;
		if ($fieldData[data][aktiv] == 0) $anb = "Ja - <a href=?page=colony&section=showcolony&id=".$id."&field=".$field."&action=chubam&m=2>Ändern</a>";
		else $anb = "Nein - <a href=?page=colony&section=showcolony&id=".$id."&field=".$field."&action=chubam&m=1>Ändern</a>";
		$bdadd = "Aktiviert nach Bau: ".$anb."<br><br>";
	}
	else $sid = $fieldData[build][id];
	if ($fieldData[build][id] == 0)
	{
		$pic = "<img src=".$grafik."/fields/".$fieldData[data][type].".gif>";
	}
	elseif ($fieldData[build][secretimage] != "0")
	{
		if ($sid != 106) $sid = $fieldData[build][secretimage];
		$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$sid."_".$fieldData[build][secretimage].".gif>";
	}
	else
	{
		$pic = "<img src=".$grafik."/buildings/".$sid."_".$fieldData[data][type].".gif>";
	}
	$fieldData[data][name] != "" ? $nadd = " / ".$fieldData[data][name] : $nadd = "";
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Untergrundfeld ".($field+1)."</strong>".$nadd."</td>
	</tr>
	</table><br>
	<table width=600 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<form action=main.php method=post>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=changebuildname>
	<input type=hidden name=mode value=ground>
	<input type=hidden name=field value=".$fieldData[data][field_id].">
	<input type=hidden name=id value=".$id.">
	<tr>
		<td valign=top class=tdmainobg width=330>".$tadd."<br>".$pic."<br>";
	if ($fieldData[build][id] > 0) echo "Integrität: ".$fieldData[data][integrity]."/".$fieldData[build][integrity]."<br><br>";
	echo $status.$upgrade.$badd.$bdadd."<br>".$chgn."</td><td valign=top class=tdmainobg width=200><b>Baumenü</b><br>";
	if ($fieldData[build] == 0)
	{
		$terraform = $myColony->getpossibleterraforming($fieldData[data][type]);
		$user < 100 ? $result = $myDB->query("SELECT a.type,b.id,b.research_id,b.name FROM stu_field_build as a LEFT JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.type=".$fieldData[data][type]." AND a.buildings_id!=1 AND a.buildings_id!=23 AND a.buildings_id!=89 AND (a.buildings_id<63 OR a.buildings_id>66) ORDER by b.name") : $result = $myDB->query("SELECT a.buildings_id,a.type,b.id,b.research_id,b.name FROM stu_field_build as a LEFT OUTER JOIN stu_buildings as b ON a.buildings_id=b.id WHERE a.type=".$fieldData[data][type]." AND a.buildings_id!=1 AND a.buildings_id!=23 AND a.buildings_id!=89 AND (a.buildings_id<63 OR a.buildings_id>66) AND b.view=1 AND b.level<=".$myUser->ulevel." ORDER by b.name");
		if (mysql_num_rows($result) == 0) echo "Keine Gebäude für dieses Feld vorhanden";
		else
		{
			while($data=mysql_fetch_assoc($result))
			{
				if ($data[research_id] > 0) if ($myDB->query("SELECT COUNT(user_id) FROM stu_research_user WHERE research_id=".$data[research_id]." AND user_id=".$user,1) == 1) $echo = 1;
				if ($data[research_id] == 0) $echo = 1;
				if ($echo == 1) echo "<a href=main.php?page=colony&section=showcolony&field=".$field."&building=".$data[id]."&action=groundbuild&id=".$id.">".$data[name]."</a> (<a href=main.php?page=showinfo&id=".$data[id]."&section=building&field=".$field."&col=".$id." target=leftbottom>?</a>)<br>";
				$echo = 0;
			}
		}
		if (is_array($terraform))
		{
			echo "<br><br><strong>Terraforming</strong><br>";
			for ($i=0;$i<count($terraform);$i++) echo "<a href=main.php?page=colony&section=showcolony&action=terraform&id=".$id."&field=".$field."&terraform=".$terraform[$i][id]."&mode=ground>".$terraform[$i][name]."</a> (<a href=main.php?page=showinfo&id=".$terraform[$i][id]."&section=terraform target=leftbottom>?</a>)";
			echo "</td>";
		}
	}
	echo "</td>
	<td class=tdmainobg valign=top width=70><img src=".$grafik."/buttons/e_trans2.gif> ".$myColony->cenergie."<br>";
	$g = $myDB->query("SELECT a.id,a.name,b.count,a.secretimage FROM stu_goods as a LEFT JOIN stu_colonies_storage as b ON a.id=b.goods_id WHERE a.id<50 AND a.id!=1 AND a.id!=16 AND a.id!=17 AND a.id!=24 AND b.colonies_id=".$id." ORDER BY a.sort");
	while($good=mysql_fetch_assoc($g)) 
	{
		if ($good[secretimage] != "0")
		{
			echo "<img src=http://www.stuniverse.de/gfx/secret/".$good[secretimage].".gif title='".$good[name]."'> ".$good['count']."<br>";
		}
		else
		{
			echo "<img src=".$grafik."/goods/".$good[id].".gif title='".$good[name]."'> ".$good['count']."<br>";
		}
	}
	echo "</td></form>
	<tr></table>
	<br><table width=300 cellpadding=1 cellspacing=1 bgcolor=#262323>
	".$replikator."
	</table>";
}
elseif ($section == "delete")
{
	$data = $myColony->getfielddatabyid($field,$id);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <a href=?page=colony&section=field&field=".$field."&id=".$id.">Oberflächenfeld ".($field+1)."</a> / <strong>Gebäude demontieren</strong></td>
	</tr>
	</table><br>
	<table width=500 cellpadding=1 cellspacing=1 bgcolor=#262323>";
	if ($data == 0) echo "<tr><td align=center class=tdmainobg width=100%>Kein Gebäude vorhanden</td></tr>";
	else
	{
		$building = $myColony->getbuildbyid($data[buildings_id]);
		if ($myColony->cdn_mode == 2) $n = "n/";
		if ($building[secretimage] != "0")
		{
			$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$n.$building[secretimage]."_".$data[type].".gif>";
		}
		else
		{
			$pic = "<img src=".$grafik."/buildings/".$n.$building[id]."_".$data[type].".gif>";
		}
		echo "<tr>
			<td rowspan=2 class=tdmainobg>".$pic."</td>
			<td class=tdmainobg align=center><strong>".$building[name]."</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg>Soll das Gebäude wirklich demontiert werden?<br>
			<a href=main.php?page=colony&section=showcolony&action=delete&id=".$id."&field=".$field."><font color=Red>Bestätigung</font></a></td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "orbitdelete")
{
	$data = $myColony->getorbitfielddatabyid($field,$id);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <a href=?page=colony&section=orbitfield&field=".$field."&id=".$id.">Orbitfeld ".($field+1)."</a> / <strong>Gebäude demontieren</strong></td>
	</tr>
	</table><br>
	<table width=500 cellpadding=1 cellspacing=1 bgcolor=#262323>";
	if ($data == 0) echo "<tr><td colspan=2 align=Center class=tdmainobg>Kein Gebäude vorhanden</td></tr>";
	else
	{
		$building = $myColony->getbuildbyid($data[buildings_id]);
		if ($building[secretimage] != "0")
		{
			$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$building[secretimage]."_".$data[type].".gif>";
		}
		else
		{
			$pic = "<img src=".$grafik."/buildings/".$building[id]."_".$data[type].".gif>";
		}
		echo "<tr>
			<td rowspan=2 class=tdmainobg>".$pic."</td>
			<td class=tdmainobg align=center><strong>".$building[name]."</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg>Soll das Gebäude wirklich demontiert werden?<br>
			<a href=main.php?page=colony&section=showcolony&action=orbitdelete&id=".$id."&field=".$field."><font color=Red>Bestätigung</font></a></td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "grounddelete")
{
	$data = $myColony->getgroundfielddatabyid($field,$id);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <a href=?page=colony&section=groundfield&field=".$field."&id=".$id.">Untergrundfeld ".($field+1)."</a> / <strong>Gebäude demontieren</strong></td>
	</tr>
	</table><br>
	<table width=500 cellpadding=1 cellspacing=1 bgcolor=#262323>";
	if ($data == 0) echo "<tr><td colspan=2 align=Center class=tdmainobg>Kein Gebäude vorhanden</td></tr>";
	else
	{
		$building = $myColony->getbuildbyid($data[buildings_id]);
		if ($building[secretimage] != "0")
		{
			$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$building[secretimage]."_".$data[type].".gif>";
		}
		else
		{
			$pic = "<img src=".$grafik."/buildings/".$building[id]."_".$data[type].".gif>";
		}
		echo "<tr>
			<td rowspan=2 class=tdmainobg>".$pic."</td>
			<td class=tdmainobg align=center><strong>".$building[name]."</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg>Soll das Gebäude wirklich demontiert werden?<br>
			<a href=main.php?page=colony&section=showcolony&action=grounddelete&id=".$id."&field=".$field."><font color=Red>Bestätigung</font></a></td>
		</tr>";
	}
	echo "</table>";
}
elseif ($section == "beam")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Beamen</strong></td>
	</tr>
	</table><br>";
	if (!$shipid)
	{
		$result = $myDB->query("SELECT a.id,a.ships_rumps_id,a.name,a.energie,a.fleets_id,a.user_id,a.batt,b.max_batt,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.huellmod*c.huell as maxhuell,b.trumfield,b.storage,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myColony->ccoords_x." AND a.coords_y=".$myColony->ccoords_y." AND a.wese=".$myColony->cwese." AND a.cloak=0 ORDER BY b.slots DESC,a.fleets_id DESC,".$myUser->getslsorting($sq="a."));
		echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain colspan=4>Zielobjekt auswählen</td>
		</tr>";
		if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg colspan=4>Es befinden sich keine Schiffe im Orbit</td></tr>";
		else
		{
			while ($data=mysql_fetch_assoc($result))
			{
				$data[huelldam] < 40 && $data[trumfield] == 0 && $data[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
				if ($data[fleets_id] > 0 && $lf != $data[fleets_id])
				{
					echo "<tr><td class=tdmainobg colspan=4>".stripslashes($myDB->query("SELECT name FROM stu_fleets WHERE id=".$data[fleets_id],1))."</td></tr>";
					$lf = $data[fleets_id];
				}
				elseif ($data[fleets_id] == 0 && $lf != -1)
				{
					echo "<tr><td class=tdmainobg colspan=4>Einzelschiffe</td></tr>";
					$lf = -1;
				}
				$st = $myDB->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$data[id],1);
				if (!$st) $st = 0;
				$user == $data[user_id] ? $stl = "<a href=?page=showinfo&section=showstorage&id=".$data[id]." target=leftbottom>".($st >= $data[storage] ? "<font color=yellow>".$st."</font>" : $st)."</a>" : $stl = ($st >= $data[storage] ? "<font color=yellow>".$st."</font>" : $st);
				if ($data[secretimage] != "0")
				{
					$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif border=0>";
				}
				else
				{
					$shippic = "<img src=".$grafik."/ships/".$mpf.$data[ships_rumps_id].".gif border=0>";
				}
				echo "<tr>
					<td class=tdmainobg width=50><a href=main.php?page=colony&section=beam&id=".$id."&shipid=".$data[id]."&way=".$way.">".$shippic."</a></td>
					<td class=tdmainobg><a href=main.php?page=colony&section=beam&id=".$id."&shipid=".$data[id]."&way=".$way.">".stripslashes($data[name])."</a></td>
					<td class=tdmainobg>".$stl."/".$data[storage]."</td>
					<td class=Tdmainobg>".stripslashes($myUser->getfield("user",$data[user_id]))."</td>
				</tr>";
			}
		}
		echo "</table>";
	}
	else
	{
		is_numeric($shipid) ? $shipdata = $myDB->query("SELECT a.name,a.ships_rumps_id,a.coords_x,a.coords_y,a.wese,a.user_id,a.crew,b.crew as mcrew,b.trumfield,b.huellmod,c.huell,a.huelle,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.id=".$shipid,4) : exit;
		if ($myColony->cwese != $shipdata[wese] || $myColony->ccoords_x != $shipdata[coords_x] || $myColony->ccoords_y != $shipdata[coords_y]) exit;
		echo "<table cellspacing=1 cellpadding=1>
		<form action=main.php method=post>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=shipid value=".$shipid.">
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=beam>
		<input type=hidden name=way value=".$way.">
		<tr><td valign=top><table bgcolor=#262323>";
		echo "<tr><td class=tdmainobg colspan=2>Ziel: ".stripslashes($shipdata[name])."</td></tr>";
		if ($shipdata[huelldam] < 40 && $shipdata[trumfield] == 0 && $shipdata[ships_rumps_id] != 111) $mpf = "d/";
		if ($way == "to")
		{
			$stor = $myDB->query("SELECT a.goods_id,a.count,b.name,b.secretimage FROM stu_colonies_storage as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.colonies_id=".$id." ORDER BY b.sort");
			$crew = "<img src=".$grafik."/buttons/crew.gif title='Freie Einwohner auf der Kolonioe'> ".$myColony->cbev_free." | <img src=".$grafik."/bev_unused_1_".$myUser->urasse.".gif title='Freie Crewquartiere auf dem Schiff'> ".($shipdata[mcrew]-$shipdata[crew]);
			$beam = "b_to1.gif";
			$nw = "from";
		}
		else
		{
			$stor = $myDB->query("SELECT a.goods_id,a.count,b.name,b.secretimage FROM stu_ships_storage as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.ships_id=".$shipid." ORDER BY b.sort");
			$crew = "<img src=".$grafik."/buttons/crew.gif title='Verfügbare Crew auf dem Schiff'> ".$shipdata[crew]." | <img src=".$grafik."/bev_unused_1_".$myUser->urasse.".gif title='Freier Wohnraum auf der Kolonie'> ".($myColony->cbev_used+$myColony->cbev_free > $myColony->cmav_bev ? "0" : $myColony->cmav_bev-$myColony->cbev_used-$myColony->cbev_free);
			$beam = "b_from1.gif";
			$nw = "to";
		}
		if ($user == $shipdata[user_id])
		{
			echo "<tr>
				<td class=tdmainobg>".$crew."</td>
				<td class=tdmainobg><input type=text name=crew class=text size=2></td>
			</tr>";
		}
		if (mysql_num_rows($stor) == 0) echo "<tr><td class=tdmainobg colspan=2 align=center>Keine Waren vorhanden</td></tr>";
		else
		{
			while($sd = mysql_fetch_assoc($stor))
			{
				if ($sd[secretimage] != "0")
				{
					$storpic = "<img src=http://www.stuniverse.de/gfx/secret/".$sd[secretimage].".gif title='".$sd[name]."'>";
				}
				else
				{
					$storpic = "<img src=".$grafik."/goods/".$sd[goods_id].".gif title='".$sd[name]."'>";
				}
				echo "<tr>
					<td class=tdmainobg>".$storpic." ".$sd['count']."</td>
					<td class=tdmainobg><input type=hidden name=good[] value=".$sd[goods_id]."><input type=text size=2 name=beam[] class=text></td>
				</tr>";
			}
		}
		if ($shipdata[secretimage] != "0")
		{
			$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$shipdata[secretimage].".gif>";
		}
		else
		{
			$shippic = "<img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif>";
		}
		echo "<tr>
			<td colspan=2 align=center class=tdmainobg><input type=submit value=Beamen class=button></td>
		</tr></table></td><td valign=top>
		<table bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain colspan=3 align=Center>Modus</td>
		</tr>
		<tr>
			<td class=tdmainobg><img src=".$grafik."/planets/".$myColony->ccolonies_classes_id.".gif></td>
			<td width=50 align=center class=tdmainobg><a href=main.php?page=colony&section=beam&id=".$id."&shipid=".$shipid."&way=".$nw."><img src=".$grafik."/buttons/".$beam." border=0></a></td>
			<td align=right class=tdmainobg>".$shippic."</td>
		</tr>
		</table></td></form></tr></table>";
	}
}
elseif ($section == "etrans")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Energietransfer</strong></td>
	</tr>
	</table><br>";
	if (!$shipid)
	{
		$result = $myDB->query("SELECT a.id,a.ships_rumps_id,a.name,a.energie,a.fleets_id,a.user_id,a.batt,b.max_batt,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.huellmod*c.huell as maxhuell,b.trumfield,a.epsmodlvl,b.epsmod,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myColony->ccoords_x." AND a.coords_y=".$myColony->ccoords_y." AND a.wese=".$myColony->cwese." AND a.cloak=0 ORDER BY b.slots DESC,a.fleets_id DESC,".$myUser->getslsorting($sq="a."));
		echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1 width=100%>
		<tr>
			<td class=tdmain colspan=4>Zielobjekt auswählen</td>
		</tr>";
		if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg colspan=4>Es befinden sich keine Schiffe im Orbit</td></tr>";
		else
		{
			while($data=mysql_fetch_assoc($result))
			{
				$data[huelldam] < 40 && $data[trumfield] == 0 && $data[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
				if ($data[fleets_id] > 0 && $lf != $data[fleets_id])
				{
					echo "<tr><td class=tdmainobg colspan=4>".stripslashes($myDB->query("SELECT name FROM stu_fleets WHERE id=".$data[fleets_id],1))."</td></tr>";
					$lf = $data[fleets_id];
				}
				elseif ($data[fleets_id] == 0 && $lf != -1)
				{
					echo "<tr><td class=tdmainobg colspan=4>Einzelschiffe</td></tr>";
					$lf = -1;
				}
				if ($data[secretimage] != "0")
				{
					$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif border=0>";
				}
				else
				{
					$shippic = "<img src=".$grafik."/ships/".$mpf.$data[ships_rumps_id].".gif border=0>";
				}
				$me = $myDB->query("SELECT eps FROM stu_ships_modules WHERE id=".$data[epsmodlvl],1)*$data[epsmod];
				echo "<tr>
					<td class=tdmainobg width=50><a href=main.php?page=colony&section=etrans&id=".$id."&shipid=".$data[id].">".$shippic."</a></td>
					<td class=tdmainobg><a href=main.php?page=colony&section=etrans&id=".$id."&shipid=".$data[id].">".stripslashes($data[name])."</a></td>
					<td class=tdmainobg>".($data[energie] < $me ? "<font color=yellow>".$data[energie]."</font>" : $data[energie])."/".$me."</td>
					<td class=tdmainobg>".stripslashes($myUser->getfield("user",$data[user_id]))."</td>
				</tr>";
			}
		}
		echo "</table>";
	}
	else
	{
		is_numeric($shipid) ? $shipdata = $myDB->query("SELECT a.name,a.ships_rumps_id,a.coords_x,a.coords_y,a.wese,b.trumfield,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.id=".$shipid,4) : exit;
		if ($myColony->cwese != $shipdata[wese] || $myColony->ccoords_x != $shipdata[coords_x] || $myColony->ccoords_y != $shipdata[coords_y]) exit;
		if ($shipdata[huelldam] < 40 && $shipdata[trumfield] == 0 && $shipdata[ships_rumps_id] != 111) $mpf = "d/";
		if ($shipdata[secretimage] != "0")
		{
			$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$shipdata[secretimage].".gif>";
		}
		else
		{
			$shippic = "<img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif>";
		}
		echo "<table bgcolor=#262323 cellspacing=1 cellpadding=1>
		<form action=main.php method=post>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=shipid value=".$shipid.">
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=etrans>
		<tr>
			<td class=tdmainobg>Ziel: ".stripslashes($shipdata[name])."</td>
		</tr>
		<tr>
			<td class=tdmainobg align=center><input type=text size=2 class=text name=count> / ".$myColony->cenergie." <input type=submit class=button value=Transfer> <input type=submit name=count value=max class=button></td>
		</tr></form></table><br>
		<table bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain colspan=3 align=Center>Modus</td>
		</tr>
		<tr>
			<td class=tdmainobg><img src=".$grafik."/planets/".$myColony->ccolonies_classes_id.".gif></td>
			<td width=50 align=center class=tdmainobg><img src=".$grafik."/buttons/b_to1.gif></td>
			<td align=right class=tdmainobg>".$shippic."</td>
		</tr></table>";
	}
}
elseif ($section == "researchlist")
{
	if ($action == "research" && $researchid) $result = $myColony->research($researchid,$id,$user);
	$rl = $myColony->getresearchlist($user);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Forschung</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr>
	<tr><td class=tdmainobg>".$result[msg]."</td></tr></table>";
	echo "<table width=500 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg width=400><strong>Forschung</strong></td>
		<td class=tdmainobg><strong>Iso-Chips</strong></td>
	</tr>";
	while($data=mysql_fetch_assoc($rl))
	{
		$rdepenc = $myColony->getresearchdepencies($data[id],$user);
		$show = 1;
		if ($rdepenc == 0) $show = 1;
		else
		{
			for ($j=0;$j<count($rdepenc[depenc]);$j++)
			{
				if ($rdepenc[depenc][$j][done] == 0) $show = 0;
				if ($show == 0) break;
			}
		}
		if ($show == 1)
		{
			if ($myDB->query("SELECT id FROM stu_research_user WHERE research_id='".$data[id]."' AND user_id=".$user,1) > 0)
			{
				$res = "<font color=#00CC50>".$data[name]."</font>";
				$cost = "<font color=#00CC50>".$data[cost]."</font>";
			}
			else
			{
				$res = $data[name];
				$cost = $data[cost];
			}
			echo "<tr>
				<td class=tdmainobg><a href=main.php?page=colony&section=researchinfo&researchid=".$data[id]."&id=".$id.">".$res."</a></td>
				<td class=tdmainobg>".$cost."</td>
			</tr>";
		}
	}
}
elseif ($section == "researchinfo")
{
	$data = $myColony->getresearchinfobyid($researchid,$user);
	$rdepenc = $myColony->getresearchdepencies($researchid,$user);
	$show = 1;
	if ($rdepenc == 0) $show = 1;
	else
	{
		for ($j=0;$j<count($rdepenc);$j++)
		{
			if ($rdepenc[depenc][$j][done] == 0) $show = 0;
			if ($show == 0) break;
		}
	}
	if ($show == 0) exit;
	$gc = $myColony->getcountbygoodid(10,$id);
	$rstat = $myColony->getuserresearch($researchid,$user);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <a href=?page=colony&section=researchlist&id=".$id.">Forschung</a> / <strong>".$data[name]."</strong></td>
	</tr>
	</table><br>
	<table width=600>
	<tr>
		<td class=tdmainobg valign=top>
		<table bgcolor=#262323>
			<tr>
				<td class=tdmain><strong>Beschreibung</strong></td>
			</tr>
			<tr>
				<td class=tdmainobg>".$data[descr]."<br><br>
				<strong>Kosten: ".$data[cost]." Iso Chips (".$gc." vorhanden)</strong><br><br>";
				$rstat == 1 ? print("<font color=#00CC50>erforscht</font>") : print("<a href=main.php?page=colony&section=researchlist&action=research&researchid=".$researchid."&id=".$id.">Erforschen</a>");
				echo "</td>
			</tr>
		</table>
		</td>
		<td width=10></td>
		<td valign=top class=tdmainobg>
		<table bgcolor=#262323>
			<tr>
				<td class=tdmain><strong>Vorraussetzungen</strong></td>
			</tr>
			<tr>
				<td class=tdmainobg>";
				for ($i=0;$i<count($data[depenc]);$i++)
				{
					$data[depenc][$i][done] == 1 ? $res = "<font color=#00CC50>".$data[depenc][$i][name]."</font>" : $res = $data[depenc][$i][name];
					if ($data[depenc][$i][id] > 0) echo "<a href=main.php?page=colony&section=researchinfo&researchid=".$data[depenc][$i][id]."&id=".$id.">".$res."</a><br>";
					unset($res);
				}
				echo "</td>
			</tr>
		</table>
		</td>";
		if ($data[pic] != "") echo "<td width=10></td><td valign=top class=tdmainobg><table bgcolor=#262323>
			<tr>
				<td class=tdmain>Bild</td>
			</tr>
			<tr>
				<td class=tdmainobg><img src=".$grafik."/".$data[pic].".gif title='".$data[name]."'></td>
			</tr>
		</table>
		</td>
	</tr></table>";
}
elseif ($section == "loadbatt")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Ersatzbatterie aufladen</strong></td>
	</tr>
	</table><br>";
	if (!$shipid)
	{
		$result = $myDB->query("SELECT a.id,a.ships_rumps_id,a.name,a.fleets_id,a.user_id,a.batt,b.max_batt,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.trumfield,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myColony->ccoords_x." AND a.coords_y=".$myColony->ccoords_y." AND a.wese=".$myColony->cwese." AND a.cloak=0 AND b.slots=0 ORDER BY a.fleets_id DESC,".$myUser->getslsorting($sq="a."));
		echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain colspan=4>Zielobjekt auswählen</td>
		</tr>";
		if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg colspan=4>Es befinden sich keine Schiffe im Orbit</td></tr>";
		else
		{
			while($data=mysql_fetch_assoc($result))
			{
				$data[huelldam] < 40 && $data[trumfield] == 0 && $data[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
				if ($data[secretimage] != "0")
				{
					$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif border=0>";
				}
				else
				{
					$shippic = "<img src=".$grafik."/ships/".$mpf.$data[ships_rumps_id].".gif border=0>";
				}
				if ($data[fleets_id] > 0 && $lf != $data[fleets_id])
				{
					echo "<tr><td class=tdmainobg colspan=4>".stripslashes($myDB->query("SELECT name FROM stu_fleets WHERE id=".$data[fleets_id],1))."</td></tr>";
					$lf = $data[fleets_id];
				}
				elseif ($data[fleets_id] == 0 && $lf != -1)
				{
					echo "<tr><td class=tdmainobg colspan=4>Einzelschiffe</td></tr>";
					$lf = -1;
				}
				echo "<tr>
					<td class=tdmainobg width=50><a href=main.php?page=colony&section=loadbatt&id=".$id."&shipid=".$data[id].">".$shippic."</a></td>
					<td class=tdmainobg><a href=main.php?page=colony&section=loadbatt&id=".$id."&shipid=".$data[id].">".stripslashes($data[name])."</a></td>
					<td class=tdmainobg>".($data[batt] < $data[max_batt] ? "<font color=yellow>".$data[batt]."</font>" : $data[batt])."/".$data[max_batt]."</td>
					<td class=tdmainobg>".stripslashes($myUser->getfield("user",$data[user_id]))."</td>
				</tr>";
			}
		}
		echo "</table>";
	}
	else
	{
		is_numeric($shipid) ? $shipdata = $myDB->query("SELECT a.name,a.ships_rumps_id,a.wese,a.coords_x,a.coords_y,b.trumfield,ROUND(100/(b.huellmod*c.huell))*a.huelle as huelldam,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.id=".$shipid,4) : exit;
		if ($myColony->cwese != $shipdata[wese] || $myColony->ccoords_x != $shipdata[coords_x] || $myColony->ccoords_y != $shipdata[coords_y]) exit;
		if ($shipdata[huelldam] < 40 && $shipdata[trumfield] == 0) $mpf = "d/";
		if ($shipdata[secretimage] != "0")
		{
			$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$shipdata[secretimage].".gif>";
		}
		else
		{
			$shippic = "<img src=".$grafik."/ships/".$mpf.$shipdata[ships_rumps_id].".gif>";
		}
		echo "<form action=main.php method=post>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=shipid value=".$shipid.">
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=loadbatt><table bgcolor=#262323 cellspacing=1 cellpadding=1><tr>
		<td class=tdmainobg>Ziel: ".stripslashes($shipdata[name])."</td></tr>
		<tr>
			<td class=tdmainobg align=center><input type=text size=2 class=text name=count> / ".$myColony->cenergie." <input type=submit class=button value=Aufladen> <input type=submit name=count value=max class=button></td>
		</tr></form></table><br>
		<table bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain colspan=3 align=Center>Modus</td>
		</tr>
		<tr>
			<td class=tdmainobg><img src=".$grafik."/planets/".$myColony->ccolonies_classes_id.".gif></td>
			<td width=50 class=tdmainobg align=center><img src=".$grafik."/buttons/b_to1.gif></td>
			<td align=right class=tdmainobg>".$shippic."</td>
		</tr></table>";
	}
}
elseif ($section == "repairship")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Schiff reparieren</strong></td>
	</tr>
	</table><br>";
	if (!$shipid)
	{
		$result = $myDB->query("SELECT a.id,a.huelle,a.ships_rumps_id,a.name,a.fleets_id,a.user_id,a.batt,b.max_batt,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.huellmod*c.huell as maxhuell,b.trumfield,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myColony->ccoords_x." AND a.coords_y=".$myColony->ccoords_y." AND a.wese=".$myColony->cwese." AND (b.huellmod*c.huell)>a.huelle AND a.cloak=0 ORDER BY a.fleets_id DESC,".$myUser->getslsorting($sq="a."));
		echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain colspan=4>Zielobjekt auswählen</td>
		</tr>";
		if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg colspan=4>Es befinden sich keine beschädigten Schiffe im Orbit</td></tr>";
		else
		{
			while($data=mysql_fetch_assoc($result))
			{
				if ($data[huelldam] >= 100) continue;
				$data[huelldam] < 40 && $data[trumfield] == 0 && $data[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
				if ($data[secretimage] != "0")
				{
					$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif border=0>";
				}
				else
				{
					$shippic = "<img src=".$grafik."/ships/".$mpf.$data[ships_rumps_id].".gif border=0>";
				}
				if ($data[fleets_id] > 0 && $lf != $data[fleets_id])
				{
					echo "<tr><td class=tdmainobg colspan=4>".stripslashes($myDB->query("SELECT name FROM stu_fleets WHERE id=".$data[fleets_id],1))."</td></tr>";
					$lf = $data[fleets_id];
				}
				elseif ($data[fleets_id] == 0 && $lf != -1)
				{
					echo "<tr><td class=tdmainobg colspan=4>Einzelschiffe</td></tr>";
					$lf = -1;
				}
				echo "<tr>
					<td class=tdmainobg width=50><a href=main.php?page=colony&section=repairship&id=".$id."&shipid=".$data[id].">".$shippic."</a></td>
					<td class=tdmainobg><a href=main.php?page=colony&section=repairship&id=".$id."&shipid=".$data[id].">".$data[name]."</a></td>
					<td class=tdmainobg>".($data[huelldam] < 100 ? "<font color=yellow>".$data[huelle]."</font>" : $data[huelle])."/".$data[maxhuell]."</td>
					<td class=tdmainobg>".stripslashes($myUser->getfield("user",$data[user_id]))."</td>
				</tr>";
			}
		}
		echo "</table>";
	}
	else
	{
		$shipdata = $myShip->getdatabyid($shipid);
		if ($myColony->cwese != $shipdata[wese] || $myColony->ccoords_x != $shipdata[coords_x] || $myColony->ccoords_y != $shipdata[coords_y]) exit;
		$cost = $myColony->getrepaircost($shipid,$id);
		echo "<form action=main.php method=post>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=shipid value=".$shipid.">
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=showcolony>
		<input type=hidden name=action value=repairship><table><tr>
		<td class=tdmain align=Center>Schiffsreparatur: ".stripslashes($shipdata[name])." (Hülle: ".$shipdata[huelle]."/".$shipdata[maxhuell].")</td></tr>
		<tr>
			<td class=tdmain align=left><strong>Reparaturkosten</strong><br>";
			if ($cost[0] > 0)
			{
				$myColony->cenergie < $cost[0] ? $menge = "<font color=red>".$myColony->cenergie."</font>" : $menge = $myColony->cenergie;
				echo "<img src=".$grafik."/buttons/e_trans2.gif title='Energie'> ".$cost[0]."/".$menge."<br>";
			}
			if ($cost[3] > 0)
			{
				$stor = $myColony->getcountbygoodid(3,$id);
				$stor < $cost[3] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid(3)." ".$cost[3]."/".$menge."<br>";
			}
			if ($cost[6] > 0)
			{
				$stor = $myColony->getcountbygoodid(6,$id);
				$stor < $cost[6] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid(6)." ".$cost[6]."/".$menge."<br>";
			}
			if ($cost[9] > 0)
			{
				$stor = $myColony->getcountbygoodid(9,$id);
				$stor < $cost[9] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid(9)." ".$cost[9]."/".$menge."<br>";
			}
			if ($cost[10] > 0)
			{
				$stor = $myColony->getcountbygoodid(10,$id);
				$stor < $cost[10] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid(10)." ".$cost[10]."/".$menge."<br>";
			}
			if ($cost[12] > 0)
			{
				$stor = $myColony->getcountbygoodid(12,$id);
				$stor < $cost[12] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid(12)." ".$cost[12]."/".$menge."<br>";
			}
			if ($cost[14] > 0)
			{
				$stor = $myColony->getcountbygoodid(14,$id);
				$stor < $cost[14] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid(14)." ".$cost[14]."/".$menge."<br>";
			}
			if ($cost[15] > 0)
			{
				$stor = $myColony->getcountbygoodid(15,$id);
				$stor < $cost[15] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid(15)." ".$cost[15]."/".$menge."<br>";
			}
			if ($cost[19] > 0)
			{
				$$stor = $myColony->getcountbygoodid(19,$id);
				$stor < $cost[19] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid(19)." ".$cost[19]."/".$menge."<br>";
			}
			if ($cost[modules][huellem] > 0)
			{
				$stor = $myColony->getcountbygoodid($cost[modules][huelleg],$id);
				$stor < $cost[modules][huellec] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid($cost[modules][huelleg])." ".$cost[modules][huellec]."/".$menge."<br>";
			}
			if ($cost[modules][schildem] > 0)
			{
				$stor = $myColony->getcountbygoodid($cost[modules][schildeg],$id);
				$stor < $cost[modules][schildec] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid($cost[modules][schildeg])." ".$cost[modules][schildec]."/".$menge."<br>";
			}
			if ($cost[modules][sensorm] > 0)
			{
				$stor = $myColony->getcountbygoodid($cost[modules][sensorg],$id);
				$stor < $cost[modules][sensorc] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid($cost[modules][sensorg])." ".$cost[modules][sensorc]."/".$menge."<br>";
			}
			if ($cost[modules][waffenm] > 0)
			{
				$stor = $myColony->getcountbygoodid($cost[modules][waffeng],$id);
				$stor < $cost[modules][waffenc] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid($cost[modules][waffeng])." ".$cost[modules][waffenc]."/".$menge."<br>";
			}
			if ($cost[modules][antriebm] > 0)
			{
				$stor = $myColony->getcountbygoodid($cost[modules][antriebg],$id);
				$stor < $cost[modules][antriebc] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid($cost[modules][antriebg])." ".$cost[modules][antriebc]."/".$menge."<br>";
			}
			if ($cost[modules][reaktorm] > 0)
			{
				$stor = $myColony->getcountbygoodid($cost[modules][reaktorg],$id);
				$stor < $cost[modules][reaktorc] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid($cost[modules][reaktorg])." ".$cost[modules][reaktorc]."/".$menge."<br>";
			}
			if ($cost[modules][computerm] > 0)
			{
				$stor = $myColony->getcountbygoodid($cost[modules][computerg],$id);
				$stor < $cost[modules][computerc] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid($cost[modules][computerg])." ".$cost[modules][computerc]."/".$menge."<br>";
			}
			if ($cost[modules][epsm] > 0)
			{
				$stor = $myColony->getcountbygoodid($cost[modules][epsg],$id);
				$stor < $cost[modules][epsc] ? $menge = "<font color=red>".$stor."</font>" : $menge = $stor;
				echo $myColony->getgoodpicbyid($cost[modules][epsg])." ".$cost[modules][epsc]."/".$menge."<br>";
			}
			echo "</td>
		</tr>
		<tr>
			<td class=tdmain align=center><input type=submit class=button value=Reparieren></td>
		</tr></form></table>";
	}
}
elseif ($section == "evcol")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Kolonie aufgeben</strong></td>
	</tr>
	</table><br>
	<table width=350 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><strong>Meldung</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg>Soll die Kolonie ".$myColony->cname." wirklich aufgegeben werden (".($myColony->cbev_free+$myColony->cbev_used*5)." Sympathie werden abgezogen)?<br>
		<a href=?page=colony&section=showcolony&action=evcol&id=".$id."><font color=red>Bestätigung</font></a></td>
	</tr>
	</table>";
}
elseif ($section == "detcol")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Kolonie sprengen</strong></td>
	</tr>
	</table><br>
	<table width=350 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><strong>Meldung</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg>Soll die Kolonie ".$myColony->cname." wirklich gesprengt werden (".($myColony->cbev_free+$myColony->cbev_used*5)." Sympathie werden abgezogen)?<br>
		<a href=?page=colony&section=showcolony&action=detcol&id=".$id."><font color=red>Bestätigung</font></a></td>
	</tr>";
}
elseif ($section == "demontship")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Schiff demontieren</strong></td>
	</tr>
	</table><br>";
	if (!$shipid)
	{
		$result = $myDB->query("SELECT a.id,a.ships_rumps_id,a.name,a.fleets_id,a.user_id,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.huellmod*c.huell as maxhuell,b.trumfield,b.storage,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myColony->ccoords_x." AND a.coords_y=".$myColony->ccoords_y." AND a.wese=".$myColony->cwese." AND a.cloak=0 AND a.user_id=".$user." ORDER BY b.slots DESC,a.fleets_id DESC,".$myUser->getslsorting($sq="a."));
		echo "<table width=500 cellpadding=1 cellspacing=1 bgcolor=#262323><tr>
		<td class=tdmain colspan=3>Zielobjekt auswählen</td></tr>
		<tr><td class=tdmain align=center><form action=main.php method=post>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=demontship>";
		if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg colspan=3>Es befinden sich keine Schiffe im Orbit</td></tr>";
		else
		{
			while ($data=mysql_fetch_assoc($result))
			{
				$data[huelldam] < 40 && $data[trumfield] == 0 && $data[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
				if ($data[fleets_id] > 0 && $lf != $data[fleets_id])
				{
					echo "<tr><td class=tdmainobg colspan=3>".stripslashes($myDB->query("SELECT name FROM stu_fleets WHERE id=".$data[fleets_id],1))."</td></tr>";
					$lf = $data[fleets_id];
				}
				elseif ($data[fleets_id] == 0 && $lf != -1)
				{
					echo "<tr><td class=tdmainobg colspan=3>Einzelschiffe</td></tr>";
					$lf = -1;
				}
				if ($data[secretimage] != "0")
				{
					$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif border=0>";
				}
				else
				{
					$shippic = "<img src=".$grafik."/ships/".$mpf.$data[ships_rumps_id].".gif border=0>";
				}
				echo "<tr>
					<td class=tdmainobg width=50><a href=main.php?page=colony&section=demontship&id=".$id."&shipid=".$data[id].">".$shippic."</a></td>
					<td class=tdmainobg><a href=main.php?page=colony&section=demontship&id=".$id."&shipid=".$data[id].">".$data[name]."</a></td>
					<td class=tdmainobg>".stripslashes($myUser->uuser)."</td>
				</tr>";
			}
		}
	}
	else
	{
		$shipdata = $myShip->getdatabyid($shipid);
		if ($myColony->cwese != $shipdata[wese] || $myColony->ccoords_x != $shipdata[coords_x] || $myColony->ccoords_y != $shipdata[coords_y]) exit;
		echo "<table width=350 cellpadding=1 cellspacing=1 bgcolor=#262323>
			<tr><td class=tdmain><strong>Meldung</strong></td></tr>
			<tr>
			<td class=tdmainobg align=Center>Soll die ".$shipdata[name]." wirklich demontiert werden?<br>
			<a href=?page=colony&section=showcolony&action=demontship&id=".$id."&shipid=".$shipid."><font color=Red>Bestätigung</font></a></td></tr></table>";
	}
}
elseif ($section == "ebuild")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Gebäudeschaltung</strong></td>
	</tr>
	</table><br>";
	if ($sent == 1)
	{
		if (is_array($_REQUEST[fields]))
		{
			$field = $_REQUEST[fields];
			$tab = 1;
		}
		elseif (is_array($_REQUEST[orbit]))
		{
			$field = $_REQUEST[orbit];
			$tab = 2;
		}
		elseif (is_array($_REQUEST[ground]))
		{
			$field = $_REQUEST[ground];
			$tab = 3;
		}
		for ($i=0;$i<count($field);$i++)
		{
			$msg .= "<tr><td class=tdmainobg>";
			if ($tab == 1)
			{
				if (isset($an)) $bla = $myColony->activatebuilding($field[$i],$id,$user);
				if (isset($aus)) $bla = $myColony->deactivatebuilding($field[$i],$id,$user);
			}
			if ($tab == 2)
			{
				if (isset($an)) $bla = $myColony->activateorbitbuilding($field[$i],$id,$user);
				if (isset($aus)) $bla = $myColony->deactivateorbitbuilding($field[$i],$id,$user);
			}
			if ($tab == 3)
			{
				if (isset($an)) $bla = $myColony->activategroundbuilding($field[$i],$id,$user);
				if (isset($aus)) $bla = $myColony->deactivategroundbuilding($field[$i],$id,$user);
			}
			$msg .= $bla[msg];
			if ($bla[code] == 1)
			{
				if ($tab == 1) $fielddata = $myColony->getfielddatabyid($field[$i],$id);
				if ($tab == 2) $fielddata = $myColony->getorbitfielddatabyid($field[$i],$id);
				if ($tab == 3) $fielddata = $myColony->getgroundfielddatabyid($field[$i],$id);
				$build = $myColony->getbuildbyid($fielddata[buildings_id]);
				if (isset($an)) $add = "+";
				if (isset($an)) $add2 = "-";
				if (isset($aus)) $add = "-";
				if (isset($aus)) $add2 = "+";
				if ($build[eps_min] > 0) $msg .= "<br>".$add2." ".$build[eps_min]." Energie";
				if ($build[eps_pro] > 0) $msg .= "<br>".$add." ".$build[eps_pro]." Energie";
				if ($build[bev_use] > 0) $msg .= "<br>".$add2." ".$build[bev_use]." freie Einwohner";
				if ($build[bev_pro] > 0) $msg .= "<br>".$add." ".$build[bev_pro]." Wohnraum";
				$bgr = $myDB->query("SELECT a.mode,a.goods_id,a.count,b.name FROM stu_buildings_goods as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.buildings_id=".$fielddata[buildings_id]);
				if (mysql_num_rows($bgr) != 0)
				{
					while($bd=mysql_fetch_assoc($bgr))
					{
						if (isset($an) && $bd[mode] == 1) $add = "+";
						if (isset($an) && $bd[mode] == 2) $add = "-";
						if (isset($aus) && $bd[mode] == 1) $add = "-";
						if (isset($aus) && $bd[mode] == 2) $add = "+";
						$msg .= "<br>".$add." ".$bd['count']." ".$bd[name];
					}
				}
			}
			$msg .= "</td></tr>";
		}
		echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323>
		<tr><td class=tdmain><strong>Meldung</strong></td></tr>".$msg."</table>";
	}
	echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323>
		<form action=main.php>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=ebuild>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=sent value=1>
	<tr><td class=tdmain align=center>Gebäude auf der Oberfläche</td></tr>";
	$fr = $myColony->getcolfieldsbybuilding($id);
	$or = $myColony->getcolorbitbybuilding($id);
	if ($myColony->ccolonies_classes_id != 6 && $myColony->ccolonies_classes_id != 9) $ur = $myColony->getcolundergroundbybuilding($id);
	echo "<tr><td class=tdmain><select name=fields[] size=10 multiple class=select>";
	while ($data=mysql_fetch_assoc($fr)) echo "<option value=".$data[field_id].">".$data[name]." ".($data[aktiv] == 0 ? "(aus)" : "(an)")."</option>";
	echo "</select></td></tr>
		<tr><td class=tdmain align=center><input type=submit name=an value=Anschalten class=button> <input type=submit name=aus value=Abschalten class=button></td></tr></table></form>
		<form action=main.php>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=ebuild>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=sent value=1>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain align=center>Gebäude im Orbit</td></tr><tr><td class=tdmain>
		<select name=orbit[] size=10 multiple class=select>";
	while ($data=mysql_fetch_assoc($or)) echo "<option value=".$data[field_id].">".$data[name]." ".($data[aktiv] == 0 ? "(aus)" : "(an)")."</option>";
	echo "</select></td></tr>
		<tr><td class=tdmain align=center><input type=submit name=an value=Anschalten class=button> <input type=submit name=aus value=Abschalten class=button></td></tr></table></form>";
	if ($myColony->ccolonies_classes_id != 6 && $myColony->ccolonies_classes_id != 9)
	{
		echo "<form action=main.php>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=ebuild>
		<input type=hidden name=id value=".$id.">
		<input type=hidden name=sent value=1>
		<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain align=center>Gebäude im Untergrund</td></tr><tr><td class=tdmain>
		<select name=ground[] size=10 multiple class=select>";
		while ($data=mysql_fetch_assoc($ur)) echo "<option value=".$data[field_id].">".$data[name]." ".($data[aktiv] == 0 ? "(aus)" : "(an)")."</option>";
		echo "</select></td></tr>
		<tr><td class=tdmain align=center><input type=submit name=an value=Anschalten class=button> <input type=submit name=aus value=Abschalten class=button></td></tr></form>
		</table>";
	}
}
elseif ($section == "teleskop")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Subraumteleskop</strong></td>
	</tr>
	</table><br>";
	if (!$coords_x || !$coords_y || ($coords_x < 1) || ($coords_y < 1))
	{
		echo "<table width=150 cellpadding=1 cellspacing=1 bgcolor=#262323>
		<form action=main.php>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=teleskop>
		<input type=hidden name=id value=".$id.">
		<tr><td class=tdmain colspan=2>Koordinaten</td></tr>
		<tr><td class=tdmainobg align=center><input type=text size=3 class=text name=coords_x> / <input type=text size=3 name=coords_y class=text>
		<input type=submit value=Scan class=button>
		</td></tr></form>";
	}
	else
	{
		$ships = $myColony->teleskop($coords_x,$coords_y);
		echo "<table width=600 cellpadding=1 cellspacing=1 bgcolor=#262323>";
		if ($ships[msg] != "") echo "<tr><td colspan=6 class=tdmain align=center>".$ships[msg]."</td></tr>";
		else
		{
			echo "<tr><td class=tdmain colspan=6>Scanergebnis von ".$coords_x."/".$coords_y."</td></tr>
				<tr><td class=tdmainobg></td>
				<td class=tdmainobg width=35% align=center><strong>Name</strong></td>
				<td class=tdmainobg width=8%><strong>Zustand</strong></td>
				<td class=tdmainobg width=8%><strong>Waffenstatus</strong></td>
				<td class=tdmainobg width=10%><strong>Schilde</strong></td>
				<td class=tdmainobg><strong>Besitzer</strong></td></tr>";
			$col = $myMap->getfieldcol($coords_x,$coords_y,$myColony->cwese);
			if ($col != 0)
			{
				echo "<tr>
					<td class=tdmainobg><img src=".$grafik."/planets/".$col[colonies_classes_id].".gif title='".$col[classname]."'></td>
					<td class=tdmainobg>".stripslashes($col[name])."</td>
					<td class=tdmainobg align=center>-</td>
					<td class=tdmainobg width=8% align=center>-</td>
					<td class=tdmainobg width=10% align=center>-</td>
					<td class=tdmainobg>".stripslashes($myUser->getfield("user",$col[user_id]))."</td>
				</tr>";
			}
			$cloaked = 0;
			if ($ships != 0)
			{
				while($data=mysql_fetch_assoc($ships))
				{
					$userdata = $myUser->getuserbyid($data[user_id]);
					if ($data[cloak] != 1 || ($myUser->ually == $userdata[allys_id] && $myUser->ually != 0 && $userdata[allys_id] != 0) || $data[user_id] == $user)
					{
						$data[huelldam] < 40 && $data[trumfield] == 0 && $data[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
						if ($data[secretimage] != "0")
						{
							if ($data[ships_rumps_id] == 3 && $data[trumoldrump] != 3)
							{
								$trumrump = $myShip->getclassbyid($data[trumoldrump]);
								$shippic = "<img src=http://www.stuniverse.de/gfx/secret/t/".$data[secretimage].".gif title=\"".stripslashes($data[classname])." (".$trumrump[name].")\">";
							}
							else
							{
								$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif title=\"".stripslashes($data[classname])."\">";
							}
						}
						else
						{
							if ($data[ships_rumps_id] == 3 && $data[trumoldrump] != 3)
							{
								$trumrump = $myShip->getclassbyid($data[trumoldrump]);
								$shippic = "<img src=".$grafik."/ships/t/".$data[trumoldrump].".gif title=\"".stripslashes($data[classname])." (".$trumrump[name].")\">";
							}
							else
							{
								$shippic = "<img src=".$grafik."/ships/".$mpf.$data[ships_rumps_id].".gif title=\"".stripslashes($data[classname])."\">";
							}
						}
						echo "<tr><td class=tdmainobg>".$shippic."</td>";
						$data[cloak] == 1 ? print("<td class=tdcloakobg>".$data[name]."</td>") :print( "<td class=tdmainobg>".stripslashes($data[name])."</td>");
						$data[alertlevel] > 1 ? $level = "!" : $level = "-";
						$data[schilde_aktiv] == 1 ? $schilde = "! (<font color=#00D5D5>".$data[schilde]."</font>)" : $schilde = "-";
						if ($data[cloak] == 1) echo "<td class=tdcloakobg>".$data[huelle]."/".$data[maxhuell]."</td>";
						else echo "<td class=tdmainobg>".$data[huelle]."/".$data[maxhuell]."</td>";
						echo "<td class=tdmainobg align=center>".$level."</td>
							  <td class=tdmainobg>".$schilde."</td>";
						echo " <td class=tdmainobg>".stripslashes($userdata[user]).($userdata[vac] == 1 ? "<font color=yellow>*</font>" : "")."</td>";
					}
					else $cloaked++;
				}
				if ($cloaked > 0) echo "<tr><td colspan=6 class=tdmain>Es befinden sich nicht scanbare Objekte in diesem Sektor</td></tr>";
			}
		}
	}
	echo "</table>";
}
elseif ($section == "repairbuilding")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Gebäudereperatur</strong></td>
	</tr>
	</table><br>";
	if ($mode == "field") $fielddata = $myColony->getfielddatabyid($field,$id);
	elseif ($mode == "orbit") $fielddata = $myColony->getorbitfielddatabyid($field,$id);
	elseif ($mode == "ground") $fielddata = $myColony->getgroundfielddatabyid($field,$id);
	if (!$fielddata) exit;
	if ($fielddata[buildings_id] == 0) exit;
	$build = $myColony->getbuildbyid($fielddata[buildings_id]);
	if ($build[secretimage] != "0")
	{
		$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$build[secretimage]."_".$fielddata[type].".gif>";
	}
	else
	{
		$pic = "<img src=".$grafik."/buildings/".$fielddata[buildings_id]."_".$fielddata[type].".gif>";
	}
	include_once("inc/buildcost.inc.php");
	$cost = getbuildingcostbyid($build[id]);
	echo "<table width=200 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr><td class=tdmain align=center colspan=2>Reparaturkosten</td></tr>
	<tr><td valign=middle align=center class=tdmainobg>".$pic."<br>".$fielddata[integrity]."/".$build[integrity]."</td>
	<td class=tdmainobg>";
	for ($i=0;$i<count($cost);$i++) echo $myColony->getgoodpicbyid($cost[$i][goods_id])." ".ceil((($cost[$i]['count']/100)*((100/$build[integrity])*($build[integrity]-$fielddata[integrity]))))." (".$myColony->getcountbygoodid($cost[$i][goods_id],$id).") ";
	echo "</td></tr><tr><td class=tdmainobg colspan=2 align=center><a href=?page=colony&section=showcolony&action=repairbuilding&mode=".$mode."&field=".$field."&id=".$id.">Reparieren</a></td></tr></table>";
}
elseif ($section == "newkolzent")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Koloniezentraler</strong></td>
	</tr>
	</table><br>
	<table width=300 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain><strong>Meldung</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg>Die Koloniezentrale auf diesem Planeten wurde zerstört<br>
		<a href=main.php?page=colony&section=showcolony&action=newkolozent&id=".$id.">Neue Koloniezentrale errichten</a></td>
	</tr></table>";
}
elseif ($section == "showsectorflights")
{
	$flights = $myColony->getflights();
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Sektordurchflüge</strong></td>
	</tr>
	</table><br>
	<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>";
	if (mysql_num_rows($flights) == 0) echo "<tr><td class=tdmainobg align=center colspan=3>Keine Durchflüge</td></tr>";
	else
	{
		while ($data=mysql_fetch_assoc($flights))
		{
			if ($data[date_tsp] > time()-10800)
			{
				$classdata = $myShip->getclassbyid($data[ships_rumps_id]);
				if ($classdata[secretimage] != "0")
				{
					$pic = "<img src=http://www.stuniverse.de/gfx/secret/".$classdata[secretimage].".gif border=0 title=\"".stripslashes($classdata[name])."\">";
				}
				else
				{
					$pic = "<img src=".$grafik."/ships/".$data[ships_rumps_id].".gif border=0 title=\"".stripslashes($classdata[name])."\">";
				}
			}
			else
			{
				$pic = "?";
			}
			echo "<tr><td class=tdmainobg align=center>".$pic."</td>
				<td class=tdmainobg>".stripslashes($data[user])." (".$data[user_id].")</td>
				<td class=tdmainobg>".date("d.m.Y H:i",$data[date_tsp])."</td></tr>";
		}
	}
	echo "</table>";
}
elseif ($section == "shipupgrade")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Schiffsupgrade</strong></td>
	</tr>
	</table><br>";
	if ($shipid) $shipdata = $myShip->getdatabyid($shipid);
	if (!$shipid || $shipdata == 0)
	{
		$result = $myDB->query("SELECT a.id,a.ships_rumps_id,a.name,a.energie,a.fleets_id,a.user_id,a.batt,a.tachyon,b.max_batt,ROUND((100/(b.huellmod*c.huell))*a.huelle) as huelldam,b.huellmod*c.huell as maxhuell,b.trumfield,b.storage,b.secretimage FROM stu_ships as a LEFT JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id LEFT JOIN stu_ships_modules as c ON a.huellmodlvl=c.id WHERE a.coords_x=".$myColony->ccoords_x." AND a.coords_y=".$myColony->ccoords_y." AND a.wese=".$myColony->cwese." AND b.slots=0 AND b.trumfield=0 AND a.user_id=".$user." AND b.tachyon=1 AND a.cloak=0 ORDER BY b.slots DESC,a.fleets_id DESC,".$myUser->getslsorting($sq="a."));
		echo "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
		<tr>
			<td class=tdmain colspan=5>Zielobjekt auswählen</td>
		</tr>";
		if (mysql_num_rows($result) == 0) echo "<tr><td class=tdmainobg colspan=5>Es befinden sich keine Schiffe im Orbit</td></tr>";
		else
		{
			echo "<tr><td class=tdmainobg></td><td class=tdmainobg></td><td class=tdmainobg></td><td class=tdmainobg>eingebaut</td><td class=tdmainobg>verfügbar</td></tr>";
			while ($data=mysql_fetch_assoc($result))
			{
				$data[huelldam] < 40 && $data[trumfield] == 0 && $data[ships_rumps_id] != 111 ? $mpf = "d/" : $mpf = "";
				if ($data[secretimage] != "0")
				{
					$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$mpf.$data[secretimage].".gif border=0>";
				}
				else
				{
					$shippic = "<img src=".$grafik."/ships/".$mpf.$data[ships_rumps_id].".gif border=0>";
				}
				if ($data[fleets_id] > 0 && $lf != $data[fleets_id])
				{
					echo "<tr><td class=tdmainobg colspan=5>".stripslashes($myDB->query("SELECT name FROM stu_fleets WHERE id=".$data[fleets_id],1))."</td></tr>";
					$lf = $data[fleets_id];
				}
				elseif ($data[fleets_id] == 0 && $lf != -1)
				{
					echo "<tr><td class=tdmainobg colspan=5>Einzelschiffe</td></tr>";
					$lf = -1;
				}
				$st = $myDB->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$data[id],1);
				if (!$st) $st = 0;
				echo "<tr>
					<td class=tdmainobg width=50><a href=main.php?page=colony&section=shipupgrade&id=".$id."&shipid=".$data[id].">".$shippic."</a></td>
					<td class=tdmainobg><a href=main.php?page=colony&section=shipupgrade&id=".$id."&shipid=".$data[id].">".stripslashes($data[name])."</a></td>
					<td class=tdmainobg>".stripslashes($myUser->getfield("user",$data[user_id]))."</td>
					<td class=tdmainobg>".($data[tachyon] == 1 ? "<img src=".$grafik."/buttons/decloak.gif title='Tachyon-Emitter installiert'>" : "")."</td>
					<td class=tdmainobg>".($data[tachyon] == 0 ? "<img src=".$grafik."/buttons/decloak.gif title='Tachyon-Emitter Upgrade verfügbar'>" : "")."</td>
				</tr>";
			}
		}
		echo "</table>";
	}
	else
	{
		echo "<table width=500 cellpadding=1 cellspacing=1 bgcolor=#262323>
		<tr>
			<td width=45% class=tdmainobg><strong>Verfügbare Upgrades</strong></td>
			<td width=1%></td>
			<td class=tdmainobg><strong>Kosten</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg><img src=".$grafik."/buttons/decloak.gif title='Tachyon Emitter'> <a href=main.php?page=colony&section=showcolony&action=shipupgrade&id=".$id."&id2=".$shipid."&upgrade=1>Tachyon Emitter</a></td>
			<td align=center></td>
			<td class=tdmainobg>10<img src=".$grafik."/buttons/e_trans2.gif title='Energie'> 10".$myColony->getgoodpicbyid(3)."  10".$myColony->getgoodpicbyid(6)." 5".$myColony->getgoodpicbyid(10)." 2".$myColony->getgoodpicbyid(3)."</td>
		</tr></table>
		<br>
		<table width=500 cellpadding=1 cellspacing=1 bgcolor=#262323>
		<tr>
			<td width=100% class=tdmainobg><strong>Eingebaute Upgrades</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg>";
			if ($shipdata[tachyon] == 1) echo "<img src=".$grafik."/buttons/decloak.gif title='Tachyon Emitter'>";
			echo "</td>
		</tr></table>";
	}
}
elseif ($section == "sensornetz")
{
	$result = $myColony->decloakgeb($id,$user);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Sensornetz</strong></td>
	</tr>
	</table><br>
	<table width=600 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr><td class=tdmainobg align=center>".$result[msg]."</td></tr></table>";
}
elseif ($section == "horchposten")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Horchposten</strong></td>
	</tr>
	</table><br>";
	if (!$coords_x || !$coords_y || ($coords_x < 1) || ($coords_y < 1) || ($coords_x > $mapfields[max_x]) || ($coords_y > $mapfields[max_y]))
	{
		echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323>
		<form action=main.php>
		<input type=hidden name=page value=colony>
		<input type=hidden name=section value=horchposten>
		<input type=hidden name=id value=".$id.">
		<tr><td align=center class=tdmainobg colspan=6>Koordinaten eingeben</td></tr>
		  <tr><td class=tdmainobg colspan=6 align=center><input type=text size=3 class=text name=coords_x> / <input type=text size=3 name=coords_y class=text></td></tr>
		  <tr><td class=tdmainobg colspan=6 align=center><input type=submit value=Scan class=button></td></tr></form></table>";
	}
	else
	{
		$result = $myColony->horchposten($coords_x,$coords_y,$myColony->cwese);
		$result[code] != 0 ? print($result[msg]) : print("<table cellpadding=1 cellspacing=1 bgcolor=#262323><tr><td class=tdmain><strong>Meldung</strong></td></tr><tr><td class=tdmainobg>".$result[msg]."</td></tr></table>");
	}
}
elseif ($section == "loadshields")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Schilde laden</strong></td>
	</tr>
	</table><br>
	<table width=300 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmainobg>Verfügbare Energie: ".$myColony->cenergie." - Schildladung: ".$myColony->cschilde."/".$myColony->cmax_schilde."</td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=colony>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=loadshields>
	<tr>
		<td class=tdmainobg align=center>Schilde um <input type=text size=3 maxlength=3 name=count class=text> <input type=submit name=count value=max class=button> <input type=submit value=Aufladen class=button></td>
	</tr>
	</form></table>";
}
elseif ($section == "movecloak")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Tarnfeld ausrichten</strong></td>
	</tr>
	</table><br>
	<table width=300 cellpadding=1 cellspacing=1 bgcolor=#262323>
	<form action=main.php method=post>
	<input type=hidden name=page value=colony>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=section value=showcolony>
	<input type=hidden name=action value=movecloak>
	<tr>
		<td class=tdmainobg align=center>Auf Feld <input type=text size=3 maxlength=3 name=cloakfield class=text> <input type=submit value=ausrichten class=button></td>
	</tr>
	</form></table>";
}
elseif ($section == "buildship")
{
	if ($submit == "Bauen") $result = $myColony->buildship($huellmod,$schildmod,$waffenmod,$sensormod,$antriebmod,$reaktormod,$epsmod,$computermod,$classid,$id,$user);
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Schiffbau</strong></td>
	</tr>
	</table><br>";
	if ($result) echo "<table cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr><td class=tdmain><strong>Meldung</strong></td></tr>
	<tr><td class=tdmainobg>".$result[msg]."</td></tr></table>";
	echo "<table width=600 cellpadding=1 cellspacing=1 bgcolor=#262323>";
	$ship = $myShip->getclassbyid($classid);
	$modules = $myColony->getshipmodules($classid,$user);
	$buildtime = $ship[buildtime];
	$torp_evade = $ship[torp_evade];
	$timem = floor($ship[buildtime]/60);
	$times = $ship[buildtime]-($timem*60);
	$time = $timem."m ".$times."s";
	$reaktor = $ship[fusion];
	$points = $ship[points];
	echo "<tr>
		<td class=tdmainobg colspan=2><strong>".$ship[name]." Klasse</strong></td>
		<td class=tdmainobg width=70>".$time."</td>
		<td class=tdmainobg width=40>".$ship[points]."</td>
		<td class=tdmainobg></td>
	</tr>
	<tr>
		<td class=tdmainobg colspan=5><strong>Hülle</strong></td>
	</tr>
	<form action=main.php method=post>
	<input type=hidden name=page value=colony>
	<input type=hidden name=section value=buildship>
	<input type=hidden name=classid value=".$classid.">
	<input type=hidden name=id value=".$id.">";
	for ($i=0;$i<count($modules[huelle]);$i++)
	{
		$stor = $myColony->getstoragebygoodid($modules[huelle][$i][goods_id],$id);
		if ($stor == 0)
		{
			$vshow = "<font color=Red>0</font>";
			$dis = " disabled=yes";
		}
		elseif ($stor['count'] < $modules[huelle][$i][c])
		{
			$vshow = "<font color=Red>".$stor['count']."</font>";
			$dis = " disabled=yes";
		}
		else
		{
			$vshow = $stor['count'];
			$dis = "";
		}
		if ($huellmod == $modules[huelle][$i][id])
		{
			$sel = " checked";
			$thism = $modules[huelle][$i];
			$buildtime += $thism[buildtime]*$thism[c];
			$points += $thism[wirt]*$thism[c];
			$huelle += $thism[huell]*$thism[c];
			$shields += $thism[shields]*$thism[c];
			$torp_evade += $thism[torp_evade]*$thism[c];
			$eps += $thism[eps]*$thism[c];
			$reaktor += $thism[reaktor]*$thism[c];
			$phaser_chance += $thism[phaser_chance]*$thism[c];
			$sensor += $thism[lss_range]*$thism[c];
		}
		else unset($sel);
		$timem = floor($modules[huelle][$i][buildtime]/60);
		$times = $modules[huelle][$i][buildtime]-($timem*60);
		$time = $timem."m ".$times."s";
		if (($modules[huelle][$i][view] == 1) || ($stor != 0))
			echo "<tr><td class=tdmainobg width=300><input type=radio name=huellmod value=\"".$modules[huelle][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[huelle][$i][goods_id])." ".$modules[huelle][$i][name]."</td>
			 <td class=tdmainobg width=50>".$vshow."/".$modules[huelle][$i][c]."</td>
			 <td class=tdmainobg width=80>".$time."</td>
			 <td class=tdmainobg>".$modules[huelle][$i][wirt]."</td>
			 <td class=tdmainobg width=200>Hülle: ".$modules[huelle][$i][huell]."</td></tr>";
	}
	echo "<tr><td class=tdmainobg colspan=5><strong>Computer</strong></td></tr>";
	for ($i=0;$i<count($modules[computer]);$i++)
	{
		$stor = $myColony->getstoragebygoodid($modules[computer][$i][goods_id],$id);
		if ($stor == 0)
		{
			$vshow = "<font color=Red>0</font>";
			$dis = " disabled=yes";
		}
		elseif ($stor['count'] < $modules[computer][$i][c])
		{
			$vshow = "<font color=Red>".$stor['count']."</font>";
			$dis = " disabled=yes";
		}
		else
		{
			$vshow = $stor['count'];
			$dis = "";
		}
		if ($computermod == $modules[computer][$i][id])
		{
			$sel = " checked";
			$thism = $modules[computer][$i];
			$buildtime += $thism[buildtime]*$thism[c];
			$points += $thism[wirt]*$thism[c];
			$huelle += $thism[huell]*$thism[c];
			$shields += $thism[shields]*$thism[c];
			$torp_evade += $thism[torp_evade]*$thism[c];
			$eps += $thism[eps]*$thism[c];
			$reaktor += $thism[reaktor]*$thism[c];
			$phaser_chance += $thism[phaser_chance]*$thism[c];
			$sensor += $thism[lss_range]*$thism[c];
		}
		else unset($sel);
		$timem = floor($modules[computer][$i][buildtime]/60);
		$times = $modules[computer][$i][buildtime]-($timem*60);
		$time = $timem."m ".$times."s";
		if (($modules[computer][$i][view] == 1) || ($stor != 0))
		echo "<tr><td class=tdmainobg><input type=radio name=computermod value=\"".$modules[computer][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[computer][$i][goods_id])." ".$modules[computer][$i][name]."</td>
			 <td class=tdmainobg>".$vshow."/".$modules[computer][$i][c]."</td>
			 <td class=tdmainobg>".$time."</td>
			 <td class=tdmainobg>".($modules[computer][$i][wirt]*$modules[computer][$i][c])."</td>
			 <td class=tdmainobg>Ausweichchance: ".$modules[computer][$i][torp_evade]."%<br>
			 Trefferchance: ".$modules[computer][$i][phaser_chance]."%</td></tr>";
	}
	echo "<tr><td class=tdmainobg colspan=5><strong>Schilde</strong></td></tr>";
	for ($i=0;$i<count($modules[schilde]);$i++)
	{
		$stor = $myColony->getstoragebygoodid($modules[schilde][$i][goods_id],$id);
		if ($stor == 0)
		{
			$vshow = "<font color=Red>0</font>";
			$dis = " disabled=yes";
		}
		elseif ($stor['count'] < $modules[schilde][$i][c])
		{
			$vshow = "<font color=Red>".$stor['count']."</font>";
			$dis = " disabled=yes";
		}
		else
		{
			$vshow = $stor['count'];
			$dis = "";
		}
		if ($schildmod == $modules[schilde][$i][id])
		{
			$sel = " checked";
			$thism = $modules[schilde][$i];
			$buildtime += $thism[buildtime]*$thism[c];
			$points += $thism[wirt]*$thism[c];
			$huelle += $thism[huell]*$thism[c];
			$shields += $thism[shields]*$thism[c];
			$torp_evade += $thism[torp_evade]*$thism[c];
			$eps += $thism[eps]*$thism[c];
			$reaktor += $thism[reaktor]*$thism[c];
			$phaser_chance += $thism[phaser_chance]*$thism[c];
			$sensor += $thism[lss_range]*$thism[c];
		}
		else unset($sel);
		$timem = floor(($modules[schilde][$i][buildtime]*$modules[schilde][$i][c])/60);
		$times = ($modules[schilde][$i][buildtime]*$modules[schilde][$i][c])-($timem*60);
		$time = $timem."m ".$times."s";
		if (($modules[schilde][$i][view] == 1) || ($stor != 0))
		echo "<tr><td class=tdmainobg><input type=radio name=schildmod value=\"".$modules[schilde][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[schilde][$i][goods_id])." ".$modules[schilde][$i][name]."</td>
			 <td class=tdmainobg>".$vshow."/".$modules[schilde][$i][c]."</td>
			 <td class=tdmainobg>".$time."</td>
			 <td class=tdmainobg>".($modules[schilde][$i][wirt]*$modules[schilde][$i][c])."</td>
			 <td class=tdmainobg>Schilde: ".$modules[schilde][$i][shields]."</td></tr>";
	}
	echo "<tr><td class=tdmainobg colspan=5><strong>Waffen</strong></td></tr>";
	if ($waffenmod == 0)
	{
		$sel = " checked";
		$plu = 1 + ($ship[waffenmod_max]-$ship[waffenmod_min]);
	}
	echo "<tr><td class=tdmainobg><input type=radio name=waffenmod value=0".$sel."> Keine</td>
		 <td class=tdmainobg></td>
		 <td class=tdmainobg></td>
		 <td class=tdmainobg></td>
		 <td class=tdmainobg></td></tr>";
	for ($i=0;$i<count($modules[waffen]);$i++)
	{
		$stor = $myColony->getstoragebygoodid($modules[waffen][$i][goods_id],$id);
		if ($stor == 0)
		{
			$vshow = "<font color=Red>0</font>";
			$dis = " disabled=yes";
		}
		elseif ($stor['count'] < $modules[waffen][$i][c])
		{
			$vshow = "<font color=Red>".$stor['count']."</font>";
			$dis = " disabled=yes";
		}
		else
		{
			$vshow = $stor['count'];
			$dis = "";
		}
		if ($waffenmod == $modules[waffen][$i][id])
		{
			$sel = " checked";
			$thism = $modules[waffen][$i];
			$plu = $ship[waffenmod_max]-$thism[lvl];
			$buildtime += $thism[buildtime]*$thism[c];
			$points += $thism[wirt]*$thism[c];
			$huelle += $thism[huell]*$thism[c];
			$shields += $thism[shields]*$thism[c];
			$torp_evade += $thism[torp_evade]*$thism[c];
			$eps += $thism[eps]*$thism[c];
			$reaktor += $thism[reaktor]*$thism[c];
			$phaser_chance += $thism[phaser_chance];
			$sensor += $thism[lss_range]*$thism[c];
			$phaser += round($modules[waffen][$i][phaser] * (1+($modules[waffen][$i][c]-1)/3));
		}
		else unset($sel);
		$timem = floor($modules[waffen][$i][buildtime]/60);
		$times = $modules[waffen][$i][buildtime]-($timem*60);
		$time = $timem."m ".$times."s";
		if (($modules[waffen][$i][view] == 1) || ($stor != 0))
		echo "<tr><td class=tdmainobg><input type=radio name=waffenmod value=\"".$modules[waffen][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[waffen][$i][goods_id])." ".$modules[waffen][$i][name]."</td>
			 <td class=tdmainobg>".$vshow."/".$modules[waffen][$i][c]."</td>
			 <td class=tdmainobg>".$time."</td>
			 <td class=tdmainobg>".$modules[waffen][$i][wirt]."</td>
			 <td class=tdmainobg>Schaden: ".round($modules[waffen][$i][phaser] * (1+($modules[waffen][$i][c]-1)/3))."<br>
			 Trefferchance: ".$modules[waffen][$i][phaser_chance]."%</td></tr>";
	}
	echo "<tr><td class=tdmainobg colspan=5><strong>Antrieb</strong></td></tr>";
	for ($i=0;$i<count($modules[antrieb]);$i++)
	{
		$stor = $myColony->getstoragebygoodid($modules[antrieb][$i][goods_id],$id);
		if ($stor == 0)
		{
			$vshow = "<font color=Red>0</font>";
			$dis = " disabled=yes";
		}
		elseif ($stor['count'] < $modules[antrieb][$i][c])
		{
			$vshow = "<font color=Red>".$stor['count']."</font>";
			$dis = " disabled=yes";
		}
		else
		{
			$vshow = $stor['count'];
			$dis = "";
		}
		if ($antriebmod == $modules[antrieb][$i][id])
		{
			$sel = " checked";
			$thism = $modules[antrieb][$i];
			$buildtime += $thism[buildtime]*$thism[c];
			$points += $thism[wirt]*$thism[c];
			$huelle += $thism[huell]*$thism[c];
			$shields += $thism[shields]*$thism[c];
			$torp_evade += $thism[torp_evade]*$thism[c];
			$eps += $thism[eps]*$thism[c];
			$reaktor += $thism[reaktor]*$thism[c];
			$phaser_chance += $thism[phaser_chance]*$thism[c];
			$sensor += $thism[lss_range]*$thism[c];
		}
		else unset($sel);
		$timem = floor($modules[antrieb][$i][buildtime]/60);
		$times = $modules[antrieb][$i][buildtime]-($timem*60);
		$time = $timem."m ".$times."s";
		if (($modules[antrieb][$i][view] == 1) || ($stor != 0))
		echo "<tr><td class=tdmainobg><input type=radio name=antriebmod value=\"".$modules[antrieb][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[antrieb][$i][goods_id])." ".$modules[antrieb][$i][name]."</td>
			 <td class=tdmainobg>".$vshow."/".$modules[antrieb][$i][c]."</td>
			 <td class=tdmainobg>".$time."</td>
			 <td class=tdmainobg>".$modules[antrieb][$i][wirt]."</td>
			 <td class=tdmainobg>Trefferchance: ".$modules[antrieb][$i][phaser_chance]."%<br>
			 Ausweichchance: ".$modules[antrieb][$i][torp_evade]."%</td></tr>";
	}
	echo "<tr><td class=tdmainobg colspan=5><strong>EPS-Gitter</strong></td></tr>";
	for ($i=0;$i<count($modules[eps]);$i++)
	{
		$stor = $myColony->getstoragebygoodid($modules[eps][$i][goods_id],$id);
		if ($stor == 0)
		{
			$vshow = "<font color=Red>0</font>";
			$dis = " disabled=yes";
		}
		elseif ($stor['count'] < $modules[eps][$i][c])
		{
			$vshow = "<font color=Red>".$stor['count']."</font>";
			$dis = " disabled=yes";
		}
		else
		{
			$vshow = $stor['count'];
			$dis = "";
		}
		if ($epsmod == $modules[eps][$i][id])
		{
			$sel = " checked";
			$thism = $modules[eps][$i];
			$buildtime += $thism[buildtime]*$thism[c];
			$points += $thism[wirt]*$thism[c];
			$huelle += $thism[huell]*$thism[c];
			$shields += $thism[shields]*$thism[c];
			$torp_evade += $thism[torp_evade]*$thism[c];
			$eps += $thism[eps]*$thism[c];
			$reaktor += $thism[reaktor]*$thism[c];
			$phaser_chance += $thism[phaser_chance]*$thism[c];
			$sensor += $thism[lss_range]*$thism[c];
		}
		else unset($sel);
		$timem = floor($modules[eps][$i][buildtime]/60);
		$times = $modules[eps][$i][buildtime]-($timem*60);
		$time = $timem."m ".$times."s";
		if (($modules[eps][$i][view] == 1) || ($stor != 0))
		echo "<tr><td class=tdmainobg><input type=radio name=epsmod value=\"".$modules[eps][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[eps][$i][goods_id])." ".$modules[eps][$i][name]."</td>
			 <td class=tdmainobg>".$vshow."/".$modules[eps][$i][c]."</td>
			 <td class=tdmainobg>".$time."</td>
			 <td class=tdmainobg>".$modules[eps][$i][wirt]."</td>
			 <td class=tdmainobg>EPS: ".$modules[eps][$i][eps]."</td></tr>";
	}
	echo "<tr><td class=tdmainobg colspan=5><strong>Sensoren</strong></td></tr>";
	for ($i=0;$i<count($modules[sensor]);$i++)
	{
		$stor = $myColony->getstoragebygoodid($modules[sensor][$i][goods_id],$id);
		if ($stor == 0)
		{
			$vshow = "<font color=Red>0</font>";
			$dis = " disabled=yes";
		}
		elseif ($stor['count'] < $modules[sensor][$i][c])
		{
			$vshow = "<font color=Red>".$stor['count']."</font>";
			$dis = " disabled=yes";
		}
		else
		{
			$vshow = $stor['count'];
			$dis = "";
		}
		if ($sensormod == $modules[sensor][$i][id])
		{
			$sel = " checked";
			$thism = $modules[sensor][$i];
			$buildtime += $thism[buildtime]*$thism[c];
			$points += $thism[wirt]*$thism[c];
			$huelle += $thism[huell]*$thism[c];
			$shields += $thism[shields]*$thism[c];
			$torp_evade += $thism[torp_evade]*$thism[c];
			$eps += $thism[eps]*$thism[c];
			$reaktor += $thism[reaktor]*$thism[c];
			$phaser_chance += $thism[phaser_chance]*$thism[c];
			$sensor = $thism[lss_range] + ($thism[c]-1);
		}
		else unset($sel);
		$timem = floor($modules[sensor][$i][buildtime]/60);
		$times = $modules[sensor][$i][buildtime]-($timem*60);
		$time = $timem."m ".$times."s";
		if (($modules[sensor][$i][view] == 1) || ($stor != 0))
		echo "<tr><td class=tdmainobg><input type=radio name=sensormod value=\"".$modules[sensor][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[sensor][$i][goods_id])." ".$modules[sensor][$i][name]."</td>
			 <td class=tdmainobg>".$vshow."/".$modules[sensor][$i][c]."</td>
			 <td class=tdmainobg>".$time."</td>
			 <td class=tdmainobg>".$modules[sensor][$i][wirt]."</td>
			 <td class=tdmainobg>LSS-Range: ".$modules[sensor][$i][lss_range]."</td></tr>";
	}
	echo "<tr><td class=tdmainobg colspan=5><strong>Warpkern</strong></td></tr>";
	if ($reaktormod == 0) $sel = " checked";
	echo "<tr><td class=tdmainobg><input type=radio name=reaktormod value=0".$sel."> Keiner</td>
		 <td class=tdmainobg></td>
		 <td class=tdmainobg></td>
		 <td class=tdmainobg></td>
		 <td class=tdmainobg></td></tr>";
	for ($i=0;$i<count($modules[reaktor]);$i++)
	{
		$stor = $myColony->getstoragebygoodid($modules[reaktor][$i][goods_id],$id);
		if ($stor == 0)
		{
			$vshow = "<font color=Red>0</font>";
			$dis = " disabled=yes";
		}
		elseif ($stor['count'] < $modules[reaktor][$i][c])
		{
			$vshow = "<font color=Red>".$stor['count']."</font>";
			$dis = " disabled=yes";
		}
		else
		{
			$vshow = $stor['count'];
			$dis = "";
		}
		if ($reaktormod == $modules[reaktor][$i][id])
		{
			$sel = " checked";
			$thism = $modules[reaktor][$i];
			$buildtime += $thism[buildtime]*$thism[c];
			$points += $thism[wirt]*$thism[c];
			$huelle += $thism[huell]*$thism[c];
			$shields += $thism[shields]*$thism[c];
			$torp_evade += $thism[torp_evade]*$thism[c];
			$eps += $thism[eps]*$thism[c];
			$reaktor += $thism[reaktor]*$thism[c];
			$phaser_chance += $thism[phaser_chance]*$thism[c];
			$sensor += $thism[lss_range]*$thism[c];
		}
		else unset($sel);
		$timem = floor($modules[reaktor][$i][buildtime]/60);
		$times = $modules[reaktor][$i][buildtime]-($timem*60);
		$time = $timem."m ".$times."s";
		if (($modules[reaktor][$i][view] == 1) || ($stor != 0))
		echo "<tr><td class=tdmainobg><input type=radio name=reaktormod value=\"".$modules[reaktor][$i][id]."\"".$dis."".$sel."> ".$myColony->getgoodpicbyid($modules[reaktor][$i][goods_id])." ".$modules[reaktor][$i][name]."</td>
			 <td class=tdmainobg>".$vshow."/".$modules[reaktor][$i][c]."</td>
			 <td class=tdmainobg>".$time."</td>
			 <td class=tdmainobg>".$modules[reaktor][$i][wirt]."</td>
			 <td class=tdmainobg>Reaktor: ".$modules[reaktor][$i][reaktor]."</td></tr>";
	}
	$timem = floor($buildtime/60);
	$times = $buildtime-($timem*60);
	$time = $timem."m ".$times."s";
	if ($ship[secretimage] != "0")
	{
		$shippic = "<img src=http://www.stuniverse.de/gfx/secret/".$ship[secretimage].".gif border=0>";
	}
	else
	{
		$shippic = "<img src=".$grafik."/ships/".$ship[id].".gif border=0>";
	}
	echo "<tr><td colspan=3><input type=submit name=Vorschau value=Vorschau class=button> <input type=submit name=submit value=Bauen class=button></td></tr></table>
	<br>
	<table width=750 bgcolor=#262323>
	<tr>
		<td rowspan=2><img src=".$grafik."/ships/".$ship[id].".gif></td>
		<td class=tdmain><strong>Hülle</strong></td>
		<td class=tdmain><strong>EPS</strong></td>
		<td class=tdmain><strong>Schilde</strong></td>
		<td class=tdmain><strong>Schaden</strong></td>
		<td class=tdmain><strong>Ausweichchance</strong></td>
		<td class=tdmain><strong>Reaktor</strong></td>
		<td class=tdmain><strong>Trefferchance</strong></td>
		<td class=tdmain><strong>LSS</strong></td>
		<td class=tdmain><strong>Frachtraum</strong></td>
		<td class=tdmain><strong>Bauzeit</strong></td>
		<td class=tdmain><strong>Punkte</strong></td>
	</tr>
	<tr>
		<td class=tdmainobg align=center>".$huelle."</td>
		<td class=tdmainobg align=center>".$eps."</td>
		<td class=tdmainobg align=center>".$shields."</td>
		<td class=tdmainobg align=center>".$phaser."</td>";
		if ($waffenmod == 99)
		{
			$pchan2 = 100 * (1 - ((1 - ($torp_evade / 100)) * 0.8));
			echo "<td class=tdmainobg align=center>".$torp_evade."% (".$pchan2."%)</td>";
		}
		else echo "<td class=tdmainobg align=center>".$torp_evade."%</td>";
		echo "<td class=tdmainobg align=center>".($reaktor+$plu)."</td>
		<td class=tdmainobg align=center>".$phaser_chance."%</td>
		<td class=tdmainobg align=center>".$sensor."</td>
		<td class=tdmainobg align=center>".$ship[storage]."</td>
		<td class=tdmainobg align=center>".$time."</td>
		<td class=tdmainobg align=center>".$points."</td>
	</tr></table>";
}
elseif ($section == "analyse")
{
	echo "<table width=100% cellpadding=1 cellspacing=1 bgcolor=#262323>
	<tr>
		<td class=tdmain>/ <a href=?page=colony>Kolonien</a> / <a href=main.php?page=colony&section=showcolony&id=".$id.">".$myColony->cname."</a> / <strong>Modulanalyse</strong></td>
	</tr>
	</table><br>
	<table cellpadding=1 cellspacing=1 bgcolor=#262323>";
	$mods = $myColony->getunknownmodulest($id);
	if ($mods != 0)
	{
		for ($i=0;$i<count($mods);$i++)
		{
			if ($mods[$i][type] == 1)
			{
				$lmod = "Hülle";
				$av = $mods[$i][huell];
			}
			if ($mods[$i][type] == 2 || $mods[$i][type] == 5)
			{
				$lmod = "Tr-%/Au-%";
				$av = $mods[$i][phaser_chance]." / ".$mods[$i][torp_evade];
			}
			if ($mods[$i][type] == 3)
			{
				$lmod = "Schilde";
				$av = $mods[$i][shields];
			}
			if ($mods[$i][type] == 4)
			{
				$lmod = "Phaser/Tr-%";
				$av = $mods[$i][phaser]." / ".$mods[$i][phaser_chance];
			}
			if ($mods[$i][type] == 6)
			{
				$lmod = "EPS";
				$av = $mods[$i][eps];
			}
			if ($mods[$i][type] == 7)
			{
				$lmod = "LSS";
				$av = $mods[$i][lss_range];
			}
			if ($mods[$i][type] == 8)
			{
				$lmod = "Reaktor";
				$av = $mods[$i][reaktor];
			}
			if ($lam != $mods[$i][type])
			{
				$lam = $mods[$i][type];
				echo "<tr><td class=tdmainobg colspan=6 height=12></td></tr><tr>
				<td class=tdmainobg width=30%><strong>Name</strong></td>
				<td class=tdmainobg width=6% align=center><strong>Lvl</strong></td>
				<td class=tdmainobg width=6% align=center><strong>".$lmod."</strong></td>
				<td class=tdmainobg width=11% align=center><strong>Punkte</strong></td>
				<td class=tdmainobg width=12% align=center><strong>Bauzeit</strong></td>
				<td class=tdmainobg width=30% align=center><strong>Besonderheiten</strong></td>
				</tr>";
			}
			echo "<tr>
				<td class=tdmainobg width=30%>".$mods[$i][name]."</td>
				<td class=tdmainobg width=6% align=center>".$myColony->getgoodpicbyid($mods[$i][goods_id])."</td>
				<td class=tdmainobg width=11% align=center>".$av."</td>
				<td class=tdmainobg width=11% align=center>".$mods[$i][wirt]."</td>
				<td class=tdmainobg width=12% align=center>".$mods[$i][buildtime]."s</td>
				<td class=tdmainobg width=30%>".$mods[$i][besonder]."</td>
			</tr>";
		}
	}
	echo "</table>";
}
?>
