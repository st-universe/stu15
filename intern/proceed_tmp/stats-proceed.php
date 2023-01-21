<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;
$myDB->query("TRUNCATE TABLE stu_stats");
$result = $myDB->query("SELECT id FROM stu_user");
while($data=mysql_fetch_assoc($result)) $myDB->query("INSERT INTO stu_stats (user_id) VALUES ('".$data[id]."')");
$result = $myDB->query("SELECT user_id,count(id) as idcount FROM stu_ships WHERE user_id >100 AND crew>0 GROUP BY user_id ORDER BY idcount DESC,user_id ASC");
while($data=mysql_fetch_assoc($result)) $myDB->query("UPDATE stu_stats SET ship_count=".$data[idcount]." WHERE user_id=".$data[user_id]);
$result = $myDB->query("SELECT sum(wirtschaft) as maxsum,user_id FROM stu_colonies WHERE user_id>100 GROUP BY user_id ORDER BY maxsum DESC,user_id ASC");
while($data=mysql_fetch_assoc($result)) $myDB->query("UPDATE stu_stats SET wirtschaft=".$data[maxsum]." WHERE user_id=".$data[user_id]);
$result = $myDB->query("SELECT sum(bev_free)+sum(bev_used) as maxsum,user_id FROM stu_colonies WHERE user_id>100 GROUP BY user_id ORDER BY maxsum DESC,user_id ASC");
while($data=mysql_fetch_assoc($result)) $myDB->query("UPDATE stu_stats SET bev=".$data[maxsum]." WHERE user_id=".$data[user_id]);
?>