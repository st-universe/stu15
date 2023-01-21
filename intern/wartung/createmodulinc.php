<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;

unlink($global_path."class/mod.class.php");
$fw = fopen($global_path."class/mod.class.php","a+");
fwrite($fw,'<?php
function getmodulebyid($modId)
{
	switch($modId)
	{');
$result = $myDB->query("SELECT * FROM stu_ships_modules ORDER BY id");
while($data=mysql_fetch_assoc($result))
{
	fwrite($fw,'
		case '.$data[id].':
			$return[type] = '.$data[type].';
			$return[name] = "'.$data[name].'";
			$return[lvl] = '.$data[lvl].';
			$return[wirt] = '.$data[wirt].';
			$return[buildtime] = '.$data[buildtime].';
			$return[huell] = '.$data[huell].';
			$return[eps] = '.$data[eps].';
			$return[phaser] = '.$data[phaser].';
			$return[torp_evade] = '.$data[torp_evade].';
			$return[reaktor] = '.$data[reaktor].';
			$return[phaser_chance] = '.$data[phaser_chance].';
			$return[lss_range] = '.$data[lss_range].';
			$return[shields] = '.$data[shields].';
			$return[goods_id] = '.$data[goods_id].';
			$return[ecost] = '.$data[ecost].';
			$return[view] = '.$data[view].';
			$return[demontchg] = '.$data[demontchg].';
			$return[besonder] = "'.$data[besonder].'";
		break;');
}
fwrite($fw,'
	}
	return $return;
}
?>');
fclose($fw);
?>