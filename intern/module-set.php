<?php
include_once("../inc/config.inc.php");
include_once("../class/db.class.php");
$myDB = new db;
if (!$id)
{
	echo "<form action=module-set.php action=post>
	User-ID: <input type=text size=5 name=id> <input type=submit value=Edit>
	</form>";
}
else
{
	if ($sent == 1)
	{
		mysql_query("DELETE FROM stu_modules_user WHERE user_id=".$id."",$myDB->dblink);
		for ($i=0;$i<count($mod);$i++) mysql_query("INSERT INTO stu_modules_user (user_id,modules_id) VALUES ('".$id."','".$mod[$i]."')",$myDB->dblink);
	}
	$result = mysql_query("SELECT * FROM stu_ships_modules ORDER BY type,lvl",$myDB->dblink);
	echo "<form action=module-set.php action=post>
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=sent value=1>";
	while($data=mysql_fetch_array($result))
	{
		if (mysql_num_rows(mysql_query("SELECT id FROM stu_modules_user WHERE user_id=".$id." AND modules_id=".$data[id]."",$myDB->dblink)) == 0) $chk = "";
		else $chk = " CHECKED";
		echo "<input type=checkbox name=mod[] value=".$data[id]."".$chk."> ".$data[name]."<br>";
	}
	echo "<br><input type=submit value=Edit></form>";
}
?>