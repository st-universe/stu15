<?php
//include_once("inc/func.inc.php");
include_once("inc/config.inc.php");
include_once("class/db.class.php");
$db = new db;

$i = 57;
$result = $db->query("SELECT systems_id FROM stu_systems");
while($data=mysql_fetch_assoc($result))
{
	$db->query("UPDATE stu_systems SET systems_id=".$i." WHERE systems_id=".$data[systems_id]);
	$db->query("UPDATE stu_sys_map SET systems_id=".$i." WHERE systems_id=".$data[systems_id]);
	$i++;
}

?>