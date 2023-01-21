<?php
include_once("inc/config.inc.php");
include_once("class/db.class.php");
$myDB = new db;
$ship = mysql_fetch_array(mysql_query("SELECT * FROM stu_ships WHERE id=1",$myDB->dblink));
$rump = mysql_fetch_array(mysql_query("SELECT * FROM stu_ships_rumps WHERE id=18",$myDB->dblink));
// Hülle
$getmod = mysql_result(mysql_query("SELECT huell FROM stu_ships_modules WHERE type=1 AND lvl=".$ship[huellmodlvl]."",$myDB->dblink),0);
$huelle = $rump[huellmod]*$getmod;
// Schilde
$getmod = mysql_result(mysql_query("SELECT shields FROM stu_ships_modules WHERE type=3 AND lvl=".$ship[schildmodlvl]."",$myDB->dblink),0);
$schilde = $rump[schildmod]*$getmod;
// Reaktor
//$reaktor = mysql_result(mysql_query("SELECT shields FROM stu_ships_modules WHERE type=3 AND lvl=".$ship[reaktormodlvl]."",$myDB->dblink));
// Antrieb

echo "max Hülle: ".$huelle."<br>
max Schilde: ".$schilde."";
?>