<?php
include_once("../inc/config.inc.php");
include_once("../class/db.class.php");
$myDB = new db;
	$colId = 2185;
	$result = mysql_query("SELECT * FROM stu_colonies WHERE id=".$colId."",$myDB->dblink);
	if (mysql_num_rows($result) == 0) exit;
	$data = mysql_fetch_array($result);
	mysql_query("UPDATE stu_colonies_orbit SET aktiv=0,buildings_id=0 WHERE colonies_id='".$colId."'",$myDB->dblink);
	mysql_query("UPDATE stu_colonies SET name='',bev_used=0,bev_free=0,user_id=2,energie=0,max_storage=0,max_energie=0,max_bev=0,max_schilde=0,schilde=0,max_spy=0,def_spy=0,free_spy=0,off_spy=0,wirtschaft=0,sperrung=0,schild_freq1=0,schild_freq2=0,schilde_aktiv=0 WHERE id=".$colId."",$myDB->dblink);
	echo mysql_error();
	mysql_query("DELETE FROM stu_colonies_storage WHERE colonies_id='".$colId."'",$myDB->dblink);
	if ($data[colonies_classes_id] == 1) include("inc/m.inc.php");
	if ($data[colonies_classes_id] == 2) include("inc/l.inc.php");
	if ($data[colonies_classes_id] == 3) include("inc/n.inc.php");
	if ($data[colonies_classes_id] == 4) include("inc/g.inc.php");
	if ($data[colonies_classes_id] == 5) include("inc/k.inc.php");
	if ($data[colonies_classes_id] == 6) include("inc/d.inc.php");
	if ($data[colonies_classes_id] == 7) include("inc/h.inc.php");
	if ($data[colonies_classes_id] == 8) include("inc/x.inc.php");
	if ($data[colonies_classes_id] == 9) include("inc/j.inc.php");
	for ($i=0;$i<count($fields);$i++)
	{
		mysql_query("UPDATE stu_colonies_fields SET type=".$fields[$i].",buildings_id=0,integrity=0,aktiv=0,name='',buildtime=0 WHERE field_id=".$i." AND colonies_id=".$colId."",$myDB->dblink);
		echo "UPDATE stu_colonies_fields SET type=".$fields[$i].",buildings_id=0,integrity=0,aktiv=0,name='',buildtime=0 WHERE field_id=".$i." AND colonies_id=".$colId."<br>";
	}
	echo count($fields);
	echo mysql_error();
	unset($fields);
	if ($data[colonies_classes_id] == 1) include("inc/um.inc.php");
	if ($data[colonies_classes_id] == 2) include("inc/ul.inc.php");
	if ($data[colonies_classes_id] == 3) include("inc/un.inc.php");
	if ($data[colonies_classes_id] == 4) include("inc/ug.inc.php");
	if ($data[colonies_classes_id] == 5) include("inc/uk.inc.php");
	if ($data[colonies_classes_id] == 7) include("inc/uh.inc.php");
	if ($data[colonies_classes_id] == 8) include("inc/ux.inc.php");
	for ($i=0;$i<count($fields);$i++) mysql_query("UPDATE stu_colonies_underground SET type=".$fields[$i].",buildings_id=0,integrity=0,aktiv=0,name='',buildtime=0 WHERE field_id=".$i." AND colonies_id=".$colId."",$myDB->dblink);
?>