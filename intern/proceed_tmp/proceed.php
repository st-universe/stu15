<?php
function deaship($id)
{
	global $myDB;
	$myDB->query("UPDATE stu_ships SET crew=0,lss=0,kss=0,replikator=0,traktormode=0,traktor=0,cloak=0,schilde_aktiv=0,actscan=0 WHERE id=".$id);
}
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;
if ($myDB->query("SELECT value FROM stu_game WHERE fielddescr='wartung'",1) == 1) exit;
include_once($global_path."class/game.class.php");
$myGame = new game;
$myGame->endround();
include_once($global_path."class/history.class.php");
$myHistory = new history;
include_once($global_path."class/user.class.php");
$myUser = new user;
$myDB->query("UPDATE stu_game SET value=1 WHERE fielddescr='tick'");
$ships = $myDB->query("SELECT a.id,a.ships_rumps_id,a.name,a.user_id,a.energie,a.lss,a.kss,a.crew,a.warpcore,a.replikator,a.cloak,a.reaktormodlvl,a.epsmodlvl,a.waffenmodlvl,a.schilde_aktiv,a.actscan,b.replikator as creplikator,b.storage,b.epsmod,b.waffenmod_min,b.waffenmod_max,b.fusion,b.crew_min FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE b.id!=2 AND b.probe=0 AND a.user_id>100");
while($data=mysql_fetch_assoc($ships))
{
	if ($data[crew] == 0 && $data[ships_rumps_id] != 88)
	{
		deaship($data[id]);
		continue;
	}
	if ($myUser->getfield("vac",$data[user_id]) == 1) continue;
	if ($data[reaktormodlvl] != 0 && $myDB->query("SELECT ships_id2 FROM stu_ships_action WHERE ships_id=".$data[id]." AND mode='readef'",1) == 0 && $data[warpcore] > 0) $reaktor = $myDB->query("SELECT reaktor FROM stu_ships_modules WHERE id=".$data[reaktormodlvl],1);
	$epsmod = $myDB->query("SELECT eps FROM stu_ships_modules WHERE id=".$data[epsmodlvl],1);
	$data[waffenmodlvl] != 0 ? $plu = $data[waffenmod_max]-$myDB->query("SELECT lvl FROM stu_ships_modules WHERE id=".$data[waffenmodlvl],1) : $plu = 1+($data[waffenmod_max]-$data[waffenmod_min]);
	if ($data[ships_rumps_id] == 88) $plu = 0;
	$maxenergie = $data[epsmod]*$epsmod;
	$reaktor == 0 ? $ee = $data[fusion] : $ee = $reaktor+$data[fusion];
	if ($ee != 0) $ee += $plu;
	if ($reaktor > 0 && $data[warpcore] <= $ee) $ee = $data[warpcore];
	if (($data[reaktormodlvl] > 0 && $ee > 0 && $data[warpcore] == 0) || $data[reaktormodlvl] == 0)
	{
		$storres = $myDB->query("SELECT count FROM stu_ships_storage WHERE goods_id='2' AND ships_id=".$data[id],1);
		if ($ee > $storres) $ee = $storres;
	}
	$nahr = $myDB->query("SELECT count FROM stu_ships_storage WHERE goods_id=1 AND ships_id=".$data[id],1);
	$data[crew] != 0 ? $c = $data[crew] : $c = 0;
	if ($nahr != 0 && $c > 0 && ($data[replikator] == 0 || ($data[replikator] == 1 && $data[energie]+$ee < ceil($c/5))))
	{
		$need = ceil($c/5);
		if ($nahr < $need)
		{
			$myDB->query("DELETE FROM stu_ships_storage WHERE goods_id=1 AND ships_id=".$data[id]);
			$c -= ($nahr*5);
		}
		elseif ($nahr == $need)
		{
			$myDB->query("DELETE FROM stu_ships_storage WHERE goods_id=1 AND ships_id=".$data[id]);
			$c = 0;
		}
		else
		{
			$myDB->query("UPDATE stu_ships_storage SET count=count-".$need." WHERE goods_id=1 AND ships_id=".$data[id]);
			$c = 0;
		}
	}
	if ($c > 0 && $data[creplikator] == 1)
	{
		$ve = $data[energie]+$ee;
		$need = ceil($c/5);
		if ($ve < $need)
		{
			$re = $need;
			$c -= ($ve*5);
		}
		else
		{
			$re = $need;
			$c = 0;
		}
	}
	if ($c > 0)
	{
		if ($data[crew] - $c == 0)
		{
			deaship($data[id]);
			$myDB->query("INSERT INTO stu_pms (recipient,sender,message,date,cate) VALUES ('".$data[user_id]."','2','Aufgrund von Nahrungsmangel sind alle Crewmitglieder mit Rettungskapseln von der ".addslashes($data[name])." geflohen',NOW(),2)");
			continue;
		}
		$myDB->query("UPDATE stu_ships SET crew=".($data[crew]-$c)." WHERE id=".$data[id]);
	}
	if ($data[cloak] == 1) $re + 3 > $ee + $data[energie] ? $part = ",cloak=0" : $re += 3;
	if ($data[lss] == 1) $re + 1 > $ee + $data[energie] ? $part .= ",lss=0" : $re++;
	if ($data[kss] == 1) $re + 1 > $ee + $data[energie] ? $part .= ",kss=0" : $re++;
	if ($data[schilde_aktiv] == 1) $re + 1 > $ee + $data[energie] ? $part .= ",schilde_aktiv=0" : $re++;
	if ($data[actscan] == 1) $re +5 > $ee + $data[energie] ? $part .= ",actscan=0" : $re++;
	if ($data[energie]-$re+$ee < 0) $ne = 0;
	elseif ($data[energie]-$re+$ee > $maxenergie) $ne = $maxenergie;
	else $ne = ($data[energie]-$re)+$ee;
	if ($data[energie]-$re+$ee > $maxenergie) $ee = $maxenergie-$data[energie]+$re;
	$myDB->query("UPDATE stu_ships SET energie=".$ne.$part." WHERE id=".$data[id]);
	if ($ee > 0)
	{
		if ($reaktor == 0)
		{
			$aff = $myDB->query("UPDATE stu_ships_storage SET count=count-".$ee." WHERE ships_id=".$data[id]." AND goods_id=2 AND count>".$ee."",6);
			if ($aff == 0) $myDB->query("DELETE FROM stu_ships_storage WHERE ships_id=".$data[id]." AND goods_id=2");
		}
		else $myDB->query("UPDATE stu_ships SET warpcore=warpcore-".$ee." WHERE id=".$data[id]);
	}
	unset($data);
	unset($storres);
	unset($waff);
	unset($plu);
	unset($ee);
	unset($re);
	unset($he);
	unset($reaktor);
	unset($part);
}

$i=0;
$ships = $myDB->query("SELECT a.id,a.ships_rumps_id,a.energie,a.epsmodlvl,a.reaktormodlvl,b.epsmod,b.fusion FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps AS b ON a.ships_rumps_id=b.id WHERE a.user_id<100 AND a.user_id!=2 AND b.trumfield=0 AND b.probe=0 AND b.id!=154");
while($data=mysql_fetch_assoc($ships))
{
	$eps = $myDB->query("SELECT eps FROM stu_ships_modules WHERE id=".$data[epsmodlvl],1);
	if ($data[reaktormodlvl] != 0) $wk = $myDB->query("SELECT reaktor FROM stu_ships_modules WHERE id=".$data[reaktormodlvl],1);
	$maxe = $data[epsmod]*$eps;
	$data[reaktormodlvl] == 0 ? $en = $data[fusion] : $en = $data[fusion]+$wk;
	if ($en > $maxe - $data[energie]) $en = $maxe - $data[energie];
	$myDB->query("UPDATE stu_ships SET energie=energie+".$en." WHERE id=".$data[id]);
}

$ships = $myDB->query("SELECT a.id,a.energie,a.lss,a.kss,a.name FROM stu_ships as a LEFT OUTER JOIN stu_ships_rumps as b ON a.ships_rumps_id=b.id WHERE b.probe=1");
while($data=mysql_fetch_assoc($ships))
{
	$data[kss] == 1 || $data[lss] == 1 ? $v = 1 : $v = 0;
	$data[energie] - $v < 0 || $data[energie] == 0 ? $myDB->query("DELETE FROM stu_ships WHERE id=".$data[id]) : $myDB->query("UPDATE stu_ships SET energie=energie-1 WHERE id=".$data[id]);
}

include_once($global_path."class/trade.class.php");
$myTrade = new trade;
$userId = 0;
$traderes = $myDB->query("SELECT id,user_id FROM stu_trade_offers WHERE UNIX_TIMESTAMP(date)<".(time()-604800)." ORDER BY user_id");
while($trade=mysql_fetch_assoc($traderes))
{
	$myTrade->deloffer($trade[id],$trade[user_id]);
	if ($trade[user_id] == $userId) $pm .= "Ihr Angebot ".$trade[id]." war länger als 7 Tage in der Warenbörse und wurde deshalb gelöscht";
	if ($trade[user_id] != $userId)
	{
		if ($userId != 0) $myDB->query("INSERT INTO stu_pms (sender,recipient,message,date,cate) VALUES ('5','".$userId."','".$pm."',NOW(),3)");
		$userId = $trade[user_id];
		$pm = "Ihr Angebot ".$trade[id]." war länger als 7 Tage in der Warenbörse und wurde deshalb gelöscht<br>";
		$lid = $trade[id];
	}
}
if ($lid != $trade[id])
{
	$pm = "Ihr Angebot ".$trade[id]." war länger als 7 Tage in der Warenbörse und wurde deshalb gelöscht<br>";
	$myDB->query("INSERT INTO stu_pms (sender,recipient,message,date,cate) VALUES ('5','".$trade[user_id]."','".$pm."',NOW(),3)");
}
unset($userId);
unset($pm);
$goods = $myDB->query("SELECT a.user_id,a.count,a.goods_id,b.name FROM stu_trade_goods as a LEFT OUTER JOIN stu_goods as b ON a.goods_id=b.id WHERE a.trade_offers_id=0 AND a.status=0 AND a.user_id>100 AND UNIX_TIMESTAMP(a.date)<".(time()-86400)." ORDER BY a.user_id");
while($trade=mysql_fetch_assoc($goods))
{
	$abzug = ceil(($trade['count']/100)*5);
	if ($trade[user_id] == $userId)
	{
		if ($trade['count']-$abzug <= 0)
		{
			$pm .= "Aus ihrem Warenkonto wurden die restlichen ".$trade['count']." ".$trade[name]." entfernt<br>";
			$myDB->query("DELETE FROM stu_trade_goods WHERE user_id=".$trade[user_id]." AND goods_id=".$trade[goods_id]." AND trade_offers_id=0 LIMIT 1");
		}
		else $pm .= "Aus ihrem Warenkonto wurden ".$abzug." ".$trade[name]." abgezogen<br>";
	}
	if ($trade[user_id] != $userId)
	{
		$myDB->query("INSERT INTO stu_pms (sender,recipient,message,date,cate) VALUES ('5','".$userId."','".$pm."',NOW(),3)");
		unset($pm);
		$userId = $trade[user_id];
		if ($trade['count']-$abzug <= 0)
		{
			$pm .= "Aus ihrem Warenkonto wurden die restlichen ".$trade['count']." ".$trade[name]." entfernt<br>";
			$myDB->query("DELETE FROM stu_trade_goods WHERE user_id=".$trade[user_id]." AND goods_id=".$trade[goods_id]." AND trade_offers_id=0 LIMIT 1");
		}
		else $pm .= "Aus ihrem Warenkonto wurden ".$abzug." ".$trade[name]." abgezogen<br>";
	}
	$myTrade->upperstoragebygoodid($abzug,$trade[goods_id],5);
}
$myDB->query("UPDATE stu_trade_goods SET count=FLOOR((count/100)*95),date=NOW() WHERE status=0 AND trade_offers_id=0 AND user_id>100 AND UNIX_TIMESTAMP(date)<".(time()-86400));
$result = $myDB->query("SELECT recipient,sender,message,date FROM stu_pms WHERE recip_del=1 AND send_del=1");
while($data=mysql_fetch_assoc($result)) $myDB->query("INSERT INTO stu_pm_saved (sender,recipient,message,date) VALUES ('".$data[sender]."','".$data[recipient]."','".addslashes(stripslashes($data[message]))."','".$data['date']."')");
$myDB->query("DELETE FROM stu_pms WHERE recip_del=1 AND send_del=1");
$myDB->query("DELETE FROM stu_trade_goods WHERE count=0");
$myDB->query("DELETE FROM stu_informants");
$myDB->query("DELETE FROM stu_ships_uncloaked");
$myDB->query("DELETE FROM stu_pms WHERE UNIX_TIMESTAMP(date) <".(time()-2678400));
$myDB->query("DELETE FROM stu_pms WHERE cate=4 AND UNIX_TIMESTAMP(date) <".(time()-604800));
$myDB->query("DELETE FROM stu_sector_flights WHERE UNIX_TIMESTAMP(date) < ".(time()-86400));
$myDB->query("DELETE FROM stu_trade_logs WHERE UNIX_TIMESTAMP(date) < ".(time()-604800));
$myDB->query("DELETE FROM stu_informants_user");
$round = $myGame->getcurrentround();
include_once($global_path."class/colony.class.php");
$myColony = new colony;
$result = $myDB->query("SELECT id FROM stu_user WHERE (((lastloginround<".($round[runde]-75)." AND vac=0) OR (lastloginround<".($round[runde]-160)." AND vac=1) OR (lastloginround<".($round[runde]-50)." AND level<2) OR (lastloginround<".($round[runde]-25)." AND level=0)) AND status=0)");
while($data=mysql_fetch_assoc($result)) $myUser->deltickuser($data[id]);
$result = $myDB->query("SELECT id FROM stu_user WHERE delmark=1");
while($data=mysql_fetch_assoc($result)) $myUser->deltickuser($data[id]);

$result = $myDB->query("SELECT user_id,count(id) as idcount FROM stu_ships WHERE user_id >100 AND crew>0 GROUP BY user_id ORDER BY idcount DESC,user_id ASC LIMIT 10");
while($data=mysql_fetch_assoc($result)) $myDB->query("INSERT INTO stu_stats_shipstopten (count,user_id,runde) VALUES ('".$data[idcount]."','".$data[user_id]."','".$round[runde]."')");

$result = $myDB->query("SELECT id FROM stu_goods WHERE id=3 OR id=5 OR id=6 OR id=8 OR id=9 OR id=10 OR id=20");
while($data=mysql_fetch_assoc($result))
{
	$r1 = rand(0,1);
	$r1 == 0 ? $r2 = 9 :$r2 = rand(0,1);
	$r2 == 1 && $r1 == 1 ? $r3 = 0 : $r3 = rand(0,9);
	$myDB->query("UPDATE stu_goods SET wfaktor=".($r1.".".$r2.$r3)." WHERE id=".$data[id]);
}
$result = $myDB->query("SELECT ships_id FROM stu_ships_action WHERE mode='deltrum' AND ships_id2<=".time());
while($data=mysql_fetch_assoc($result))
{
	$myDB->query("DELETE FROM stu_ships WHERE id=".$data[ships_id]." LIMIT 1");
	$myDB->query("DELETE FROM stu_ships_storage WHERE ships_id=".$data[ships_id]." LIMIT 1");
	$myDB->query("DELETE FROM stu_ships_action WHERE ships_id=".$data[ships_id]." LIMIT 1");
}
$myDB->query("DELETE FROM stu_user_passrec WHERE UNIX_TIMESTAMP(date)<".(time()-86400));
?>