<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;
$fp = fopen("http://webeye.euirc.net/infopanel/?request=stu&key=jsadf","r");  
$fp_value = fread($fp,32768);  
$fp_value = preg_replace("°[^a-z] = °",'" => ',$fp_value);
fclose($fp);
eval ($fp_value);
eval ($fp_who_value);
$myDB->query("UPDATE stu_game SET value='".$irc_info[user]."' WHERE fielddescr='chatonline'");
?>