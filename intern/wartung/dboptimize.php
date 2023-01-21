<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;
$result = mysql_listtables("usr_web1_1",$myDB->dblink);
for ($i=0;$i<mysql_num_rows($result);$i++) $myDB->query("OPTIMIZE TABLE ".mysql_tablename($result,$i));
?>