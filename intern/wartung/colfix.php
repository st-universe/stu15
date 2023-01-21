<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;
include_once($global_path."class/colony.class.php");
$myColony = new colony;
include_once($global_path."class/game.class.php");
$myGame = new game;
$bev_used = 0;
$bev_pro = 0;
$eps = 0;
$lager = 0;
$schilde = 0;
$cols = $myDB->query("SELECT id,name,max_energie,max_bev,bev_used,bev_free,max_storage,max_schilde FROM stu_colonies WHERE user_id!=2 AND id!=3064 ORDER BY id");
while($data=mysql_fetch_assoc($cols))
{
	$fields = $myColony->getcolfields($data[id]);
	$schilde = 0;
	for ($j=0;$j<54;$j++) {
		$build = $myColony->getbuildbyid($fields[$j][buildings_id]);
		if ($build[eps] > 0) $eps = $eps + $build[eps];
		if ($build[lager] > 0) $lager = $lager + $build[lager];
		if ($build[schilde] > 0) $schilde = $schilde + $build[schilde];
		if ($fields[$j][aktiv] == 1) {
			if ($build[bev_use] > 0) $bev_used = $bev_used + $build[bev_use];
			if ($build[bev_pro] > 0) $bev_pro = $bev_pro + $build[bev_pro];
			//echo "Oberfläche-Feld: ".$j." - Gebäude: ".$build[name]." - bev_use: ".$build[bev_use]." - Insgesamt: ".$bev_used."<br>";
		}
	}
	$orbit = $myColony->getcolorbit($data[id]);
	for ($j=0;$j<18;$j++) {
		$build = $myColony->getbuildbyid($orbit[$j][buildings_id]);
		if ($orbit[$j][aktiv] == 1) {
			if ($build[bev_use] > 0) $bev_used = $bev_used + $build[bev_use];
			if ($build[bev_pro] > 0) $bev_pro = $bev_pro + $build[bev_pro];
			//echo "Orbit-Feld: ".$j." - Gebäude: ".$build[name]." - bev_use: ".$build[bev_use]." - Insgesamt: ".$bev_used."<br>";
		}
	}
	$ground = $myColony->getcolunderground($data[id]);
	for ($j=0;$j<27;$j++) {
		$build = $myColony->getbuildbyid($ground[$j][buildings_id]);
		if ($build[lager] > 0) $lager = $lager + $build[lager];
		if ($build[eps] > 0) $eps = $eps + $build[eps];
		if ($build[schilde] > 0) $schilde = $schilde + $build[schilde];
		if ($ground[$j][aktiv] == 1) {
			if ($build[bev_use] > 0) $bev_used = $bev_used + $build[bev_use];
			if ($build[bev_pro] > 0) $bev_pro = $bev_pro + $build[bev_pro];
			//echo "Untergrund-Feld: ".$j." - Gebäude: ".$build[name]." - bev_use: ".$build[bev_use]." - Insgesamt: ".$bev_used."<br>";
		}
	}
	//echo "<b>".$data[id]." - ".strip_tags($data[name])." - DB-Stand (bev-use): ".$data[bev_used]." -> Real: ".$bev_used." - DB-Stand (max-bev): ".$data[max_bev]." -> Real: ".$bev_pro." -  - DB-Stand (lager) ".$data[max_storage]." -> Real: ".$lager." - - DB-Stand (eps) ".$data[max_energie]." -> Real ".$eps." - - DB-Stand (schilde) ".$data[max_schilde]." -> Real ".$schilde."</b><br>";
	if (!$bev_used) $bev_used = 0;
	if (($bev_pro < $bev_used) || ($data[max_bev] != $bev_pro) || ($data[bev_used] != $bev_used) || ($lager != $data[max_storage]) || ($eps != $data[max_energie]) || ($schilde != $data[max_schilde])) {
		//echo "<strong><font color=red>Alarm</font></strong><br>";
		$myDB->query("UPDATE stu_colonies SET bev_used=".$bev_used.",max_bev=".$bev_pro.",max_storage=".$lager.",max_schilde=".$schilde.",max_energie=".$eps." WHERE id=".$data[id]);
	}
	$bev_used = 0;
	$bev_pro = 0;
	$eps = 0;
	$lager = 0;
	$schilde = 0;
}
?>