<?php
function deaorbit()
{
	global $myDB,$data,$wirt,$eps,$goods;
	$orbit = $myDB->query("SELECT buildings_id,aktiv FROM stu_colonies_orbit WHERE aktiv=1 AND colonies_id=".$data[id]);
	if (mysql_num_rows($orbit) == 0) return 0;
	while($orbdat=mysql_fetch_assoc($orbit))
	{
		$build = $myDB->query("SELECT bev_use,bev_pro,eps_min,eps_pro,points FROM stu_buildings WHERE id=".$orbdat[buildings_id],4);
		$wirt -= $build[points];
		if ($orbdat[aktiv] == 1)
		{
			$stor = $myDB->query("SELECT goods_id,count,mode FROM stu_buildings_goods WHERE buildings_id=".$orbdat[buildings_id]);
			while($stordat = mysql_fetch_assoc($stor))
			{
				if ($stordat[mode] == 1) $goods[$stordat[goods_id]] -= $stordat['count'];
				if ($stordat[mode] == 2) $goods[$stordat[goods_id]] += $stordat['count'];
			}
			$eps += $build[eps_min];
			$eps -= $build[eps_pro];
			$myDB->query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use].",max_bev=max_bev-".$build[bev_pro]." WHERE id=".$data[id]);
		}
	}
	$myDB->query("UPDATE stu_colonies_orbit SET aktiv=0 WHERE colonies_id=".$data[id]);
}
function deaground()
{
	global $myDB,$data,$wirt,$eps,$goods;
	$ground = $myDB->query("SELECT buildings_id,aktiv FROM stu_colonies_underground WHERE aktiv=1 AND colonies_id=".$data[id]);
	if (mysql_num_rows($ground) == 0) return 0;
	while($grounddata=mysql_fetch_assoc($ground))
	{
		$build = $myDB->query("SELECT bev_use,bev_pro,eps_min,eps_pro,points FROM stu_buildings WHERE id=".$grounddata[buildings_id],4);
		$wirt -= $build[points];
		if ($grounddata[aktiv] == 1)
		{
			$stor = $myDB->query("SELECT goods_id,count,mode FROM stu_buildings_goods WHERE buildings_id=".$grounddata[buildings_id]);
			while($stordat = mysql_fetch_assoc($stor))
			{
				if ($stordat[mode] == 1) $goods[$stordat[goods_id]] -= $stordat['count'];
				if ($stordat[mode] == 2) $goods[$stordat[goods_id]] += $stordat['count'];
			}
			$eps += $build[eps_min];
			$eps -= $build[eps_pro];
			$myDB->query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use].",max_bev=max_bev-".$build[bev_pro]." WHERE id=".$data[id]);
		}
	}
	$myDB->query("UPDATE stu_colonies_underground SET aktiv=0 WHERE colonies_id=".$data[id]);
}
function deabuild($id)
{
	global $myDB,$goods,$goodlist,$data,$myColony,$symp,$wirt,$eps,$trm,$gl;
	if ($id != "eps") $return = $myDB->query("SELECT a.buildings_id,b.field_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_underground as b USING (buildings_id) WHERE a.goods_id=".$id." AND a.mode=2 AND b.aktiv=1 AND b.colonies_id=".$data[id]." LIMIT 1",4);
	if ($id == "eps") $return = $myDB->query("SELECT a.id as buildings_id,b.field_id FROM stu_buildings as a LEFT JOIN stu_colonies_underground as b ON a.id=b.buildings_id WHERE a.eps_min>0 AND b.aktiv=1 AND b.colonies_id=".$data[id]." ORDER BY a.eps_min DESC LIMIT 1",4);
	$outfield = "stu_colonies_underground";
	$addmsg = "im Untergrund ";
	if ($return == 0)
	{
		if ($id != "eps") $return = $myDB->query("SELECT a.buildings_id,b.field_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_orbit as b USING (buildings_id) WHERE a.goods_id=".$id." AND a.mode=2 AND b.aktiv=1 AND b.colonies_id=".$data[id]." LIMIT 1",4);
		if ($id == "eps") $return = $myDB->query("SELECT a.id as buildings_id,b.field_id FROM stu_buildings as a LEFT JOIN stu_colonies_orbit as b ON a.id=b.buildings_id WHERE a.eps_min>0 AND b.aktiv=1 AND b.colonies_id=".$data[id]." ORDER BY a.eps_min DESC LIMIT 1",4);
		$outfield = "stu_colonies_orbit";
		$addmsg = "im Orbit ";
	}
	if ($return == 0)
	{
		if ($id != "eps") $return = $myDB->query("SELECT a.buildings_id,b.field_id FROM stu_buildings_goods as a LEFT JOIN stu_colonies_fields as b USING (buildings_id) WHERE a.goods_id=".$id." AND a.mode=2 AND b.aktiv=1 AND b.colonies_id=".$data[id]." LIMIT 1",4);
		if ($id == "eps") $return = $myDB->query("SELECT a.id as buildings_id,b.field_id FROM stu_buildings as a LEFT JOIN stu_colonies_fields as b ON a.id=b.buildings_id WHERE a.eps_min>0 AND b.aktiv=1 AND b.colonies_id=".$data[id]." ORDER BY a.eps_min DESC LIMIT 1",4);
		$outfield = "stu_colonies_fields";
		$addmsg = "auf der Oberfläche ";
	}
	if ($return == 0) return 0;
	$outid = $return[buildings_id];
	$build = $myDB->query("SELECT id,name,bev_use,bev_pro,eps_min,eps_pro,points FROM stu_buildings WHERE id=".$outid,4);
	$colbuildd = $return[field_id];
	$myDB->query("UPDATE ".$outfield." SET aktiv=0 WHERE colonies_id='".$data[id]."' AND field_id='".$colbuildd."' AND aktiv=1");
	$myDB->query("UPDATE stu_colonies SET bev_free=bev_free+".$build[bev_use].",bev_used=bev_used-".$build[bev_use].",max_bev=max_bev-".$build[bev_pro]." WHERE id=".$data[id]);
	if ($outid == 51 || $outid == 81) $myDB->query("UPDATE stu_colonies SET schilde_aktiv=0 WHERE id=".$data[id]);
	$stor = $myDB->query("SELECT goods_id,count,mode FROM stu_buildings_goods WHERE buildings_id=".$outid);
	while($stordat = mysql_fetch_assoc($stor))
	{
		if ($stordat[mode] == 1) $goods[$stordat[goods_id]] -= $stordat['count'];
		if ($stordat[mode] == 2) $goods[$stordat[goods_id]] += $stordat['count'];
	}
	$build[id] == 80 ? $eps -= $myColony->getgravenergy($data[id]) : $eps -= $build[eps_pro];
	$eps += $build[eps_min];
	$wirt -= $build[points];
	$id == "eps" ? $trm .= "<br>".addslashes(stripslashes($data[name])).": ".addslashes($build[name])." (".$addmsg." auf Feld ".($colbuildd+1).") deaktiviert (Energiemangel)" : $trm .= "<br>".addslashes(stripslashes($data[name])).": ".addslashes($build[name])." (".$addmsg." auf Feld ".($colbuildd+1).") deaktiviert (".$gl[$id]."-Mangel)";
	if ($outid == 21 || $outid == 168) deaorbit();
	if ($outid == 38) deaground();
}

include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;
if ($myDB->query("SELECT value FROM stu_game WHERE fielddescr='wartung'",1) == 1) exit;
include_once($global_path."class/game.class.php");
$myGame = new game;
include_once($global_path."class/comm.class.php");
$myComm = new comm;
include_once($global_path."class/colony.class.php");
$myColony = new colony;
$myDB->query("UPDATE stu_game SET value='".$myDB->query("SELECT SUM(wirtschaft) FROM stu_colonies WHERE user_id>100",1)."' WHERE fielddescr='lrw'");

$result = $myDB->query("SELECT id,name FROM stu_goods WHERE id<40 ORDER BY id");
while($tm=mysql_fetch_assoc($result)) $gl[$tm[id]] = $tm[name];

$cols = $myDB->query("SELECT a.*,a.bev_used+a.bev_free as gbv FROM stu_colonies as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.user_id!=2 AND b.vac=0 ORDER BY a.user_id");
while($data=mysql_fetch_assoc($cols))
{
	if ($luid != $data[user_id] && $trm != "")
	{
		$myComm->sendpm($luid,2,"<strong>Tickreport ".date("d.m.Y H:i",time())."</strong><br>".$trm,4);
		$trm = "";
	}
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
	$wirt = $myColony->getnrwbyid($data[id]);
	$eps = $myColony->getnrebyid($data[id]);
	$symp = $myColony->getnrsbyid($data[id]);
	if ($data[max_bev] < $data[bev_used] + $data[bev_free])	$symp -= $data[bev_used] + $data[bev_free] - $data[max_bev];
	$goods[1] += $myColony->getwksnbyid($data[id]);
	$goods[1] += $myColony->getregnbyid($data[id]);
	$symp += floor($data[bev_used]/10);
	$x = 0;
	unset($id);
	while($x<2)
	{
		for ($z=1;$z<=30;$z++)
		{
			if ($goods[$z] < 0)
			{
				if ($myColony->getcountbygoodid($z,$data[id]) - abs($goods[$z]) < 0)
				{
					$checked = 1;
					$deaid = $z;
				}
			}
		}
		if ($eps < 0)
		{
			if ($data[energie] - abs($eps) < 0)
			{
				$deaid = "eps";
				$checked = 1;
			}
		}
		if ($checked == 0 && $deaid == 0) break;
		deabuild($deaid);
		$checked = 0;
		$deaid = 0;
	}
	if ($goods[1]+$myDB->query("SELECT count FROM stu_colonies_storage WHERE goods_id=1 AND colonies_id=".$data[id],1) < ceil($data[gbv]/5))
	{
		$streik = 1;
		$trm .= "<br>Aufgrund des Nahrungsmangels auf der Kolonie ".addslashes(stripslashes($data[name]))." ist die Produktion um 80% eingeschränkt.<br>Es sind ".$myDB->query("SELECT bev_free FROM stu_colonies WHERE id=".$data[id],1)." Einwohner ausgewandert";
		$myDB->query("UPDATE stu_colonies SET bev_free=0 WHERE id=".$data[id]);
		$symp -= ($data[gbv]*2);
		$wirt *= 0.2;
	}
	else $goods[1] -= ceil($data[gbv]/5);
	foreach($goods as $key => $value) if ($value < 0) $myColony->lowerstoragebygoodid(abs($value),$key,$data[id]);
	$istor = $myDB->query("SELECT SUM(count) FROM stu_colonies_storage WHERE colonies_id=".$data[id],1);
	foreach($goods as $key => $value)
	{
		if ($value > 0)
		{
			if ($istor >= $data[max_storage]) break;
			$value <= $data[max_storage]-$istor ? $sta = $value : $sta = $data[max_storage] - $istor;
			if ($streik == 1) $sta *= 0.2;
			$myColony->upperstoragebygoodid(floor($sta),$key,$data[id],$data[user_id]);
			$istor += floor($sta);
		}
	}
	if ($eps < 0) $myDB->query("UPDATE stu_colonies SET energie=energie-".abs($eps)." WHERE id=".$data[id]);
	if (($eps > 0) && ($data[energie] < $data[max_energie]))
	{
		if ($eps+$data[energie] > $data[max_energie]) $eps = $data[max_energie]-$data[energie];
		$myDB->query("UPDATE stu_colonies SET energie=energie+".$eps." WHERE id=".$data[id]);
	}
	if (($data[ewopt] == 1) && ($streik != 1) && ($data[gbv] < $data[max_bev]) && (($data[gbv] < $data[bev_stop_count]) || ($data[bev_stop_count] == 0)))
	{
		$nf = ceil(($data[max_bev]-$data[gbv])/2);
		if (($nf+$data[gbv] > $data[bev_stop_count]) && ($data[bev_stop_count] != 0)) $nf = $data[bev_stop_count]-$data[gbv];
		if ($nf > 0 && ($data[colonies_classes_id] == 1 || $data[colonies_classes_id] == 2 || $data[colonies_classes_id] == 3 || $data[colonies_classes_id] == 10 || $myDB->query("SELECT id FROM stu_colonies_fields WHERE colonies_id=".$data[id]." AND buildings_id=168 AND aktiv=1",1) != 0)) $myDB->query("UPDATE stu_colonies SET bev_free=bev_free+".$nf." WHERE id=".$data[id]);
	}
	if (($streik == 1) && ($data[colonies_classes_id] < 4) && ($data[ewopt] == 1) && ($data[bev_used] == 0)) $myDB->query("UPDATE stu_colonies SET bev_free=bev_free+2 WHERE id=".$data[id]);
	$myDB->query("UPDATE stu_colonies SET wirtschaft=".$wirt." WHERE id=".$data[id]);
	$myDB->query("UPDATE stu_user SET symp=symp+".$symp." WHERE id=".$data[user_id]);
	if (($data[max_bev] - $data[bev_used] < $data[bev_free]) && ($data[max_bev] > $data[bev_used])) $myDB->query("UPDATE stu_colonies SET bev_free=".($data[max_bev]-$data[bev_used])." WHERE id=".$data[id]);
	$luid = $data[user_id];
	unset($id);
	unset($data);
	unset($bev);
	unset($eps);
	unset($goods);
	unset($stor);
	unset($build);
	unset($symp);
	unset($delbev);
	unset($wirt);
	unset($sta);
	unset($streik);
	$i++;
}
$myDB->query("DELETE FROM stu_colonies_storage WHERE count=0");
$result = $myDB->query("SELECT id,wirtmin,symp,wirtplus FROM stu_user WHERE id>100 AND vac=0");
while($data=mysql_fetch_assoc($result))
{
	$points = $myDB->query("SELECT SUM(points) FROM stu_ships WHERE user_id=".$data[id],1);
	$sp = floor($data[symp]/2500)+$myDB->query("SELECT SUM(wirtschaft) FROM stu_colonies WHERE user_id=".$data[id],1);
	if ($points > $sp)
	{
		$wimin = $points-$sp;
		if ($wimin < $data[wirtplus]) $myDB->query("UPDATE stu_user SET wirtplus=wirtplus-".$wimin." WHERE id=".$data[id]);
		else
		{
			$myDB->query("UPDATE stu_user SET wirtplus=0 WHERE id=".$data[id]);
			$myDB->query("UPDATE stu_user SET wirtmin=wirtmin+".($wimin-$data[wirtplus])." WHERE id=".$data[id]);
		}
	}
	else
	{
		if ($data[wirtmin] > 0 && $data[wirtmin]-($sp-$points) > 0) $myDB->query("UPDATE stu_user SET wirtmin=wirtmin-".($sp-$points)." WHERE id=".$data[id]);
		if ($data[wirtmin] > 0 && $data[wirtmin]-($sp-$points) <= 0) $myDB->query("UPDATE stu_user SET wirtmin=0 WHERE id=".$data[id]);
		if ($data[wirtmin] == 0 && $sp-$points > 0)
		{
			if ($data[wirtplus] + ($sp-$points) > 10000) $myDB->query("UPDATE stu_user SET wirtplus=10000 WHERE id=".$data[id]);
			else $myDB->query("UPDATE stu_user SET wirtplus=wirtplus+".($sp-$points)." WHERE id=".$data[id]);
		}
	}
}
if (date("d") == 01 && date("H") == 00) $myDB->query("UPDATE stu_user SET pvac=2");
$w = $myDB->query("SELECT SUM(wirtschaft) FROM stu_colonies WHERE user_id>100",1);
$lrw = round(((100/$myDB->query("SELECT value FROM stu_game WHERE fielddescr='lrw'",1))*$w)-100,2);
$myDB->query("UPDATE stu_game SET value='".$lrw."' WHERE fielddescr='g_wirt'");
$myDB->query("UPDATE stu_game SET value=0 WHERE fielddescr='tick'",$myDB->dblink);
$myGame->startround();
?>