<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."/class/db.class.php");
$myDB = new db;

$myDB->query("SELECT value FROM stu_game WHERE fielddescr='wartung'",1) == 0 ? $m = 1 : $m = 0;
$myDB->query("UPDATE stu_game SET value='".$m."' WHERE fielddescr='wartung'");
?>