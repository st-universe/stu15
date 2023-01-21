<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;

$lc = 0;
unlink($global_path."inc/shipcost.inc.php");
$fw = fopen($global_path."inc/shipcost.inc.php","a+");
fwrite($fw,'<?php
# Last generated: '.date("d.m.Y H:i",time()).'
function getcostbyclass($classId)
{
	switch($classId)
	{
');
$result = $myDB->query("SELECT * FROM stu_ships_rumps ORDER BY id");
while($data=mysql_fetch_assoc($result))
{
	fwrite($fw,'		case '.$data[id].':
			# '.stripslashes($data[name]).'');
	$res = $myDB->query("SELECT a.*,b.name FROM stu_ships_cost as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.ships_rumps_id=".$data[id]);
	$i = 0;
	if ($res != 0)
	{
		while($rd=mysql_fetch_assoc($res))
		{
			fwrite($fw,'
			$return['.$i.'][goods_id] = '.$rd[goods_id].';
			$return['.$i.'][\'count\'] = '.$rd['count'].';
			$return['.$i.'][name] = "'.$rd[name].'";');
			$i++;
		}
	}
	fwrite($fw,'
		break;
');
}
fwrite($fw,'	}
	return $return;
}
?>');
fclose($fw);
unlink($global_path."inc/buildcost.inc.php");
$fw = fopen($global_path."inc/buildcost.inc.php","a+");
fwrite($fw,'
<?php
function getbuildingcostbyid($bid)
{
	switch($bid)
	{
');
$result = $myDB->query("SELECT * FROM stu_buildings ORDER BY id");
while($data=mysql_fetch_assoc($result))
{
	fwrite($fw,'		case '.$data[id].':
			# '.stripslashes($data[name]).'');
	$res = $myDB->query("SELECT a.*,b.name FROM stu_buildings_cost as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.buildings_id=".$data[id]);
	$i = 0;
	if ($res != 0)
	{
		while($rd=mysql_fetch_assoc($res))
		{
			fwrite($fw,'
			$return['.$i.'][goods_id] = '.$rd[goods_id].';
			$return['.$i.'][\'count\'] = '.$rd['count'].';
			$return['.$i.'][name] = "'.$rd[name].'";');
			$i++;
		}
	}
	fwrite($fw,'
		break;
');
}
fwrite($fw,'	}
	return $return;
}
?>');
fclose($fw);
?>