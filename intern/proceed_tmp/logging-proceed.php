<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once("/srv/www/htdocs/web1/html/class/db.class.php");
$myDB = new db;
$file = fopen("/srv/www/htdocs/web1/html/tracking/logs/".date("dmY",time()).".html","w+");
fwrite($file,"<html><link rel=\"STYLESHEET\" type=\"text/css\" href=\"../../gfx/css/style.css\"><body bgcolor=#000000>
<table width=100% bgcolor=#262323>
<tr><td align=center width=100% class=tdmaintop>STU - Userlogfile vom ".date("d.m.Y (H:i)",time())."</td></tr>
</table><br>
<table width=100% bgcolor=#262323><tr>
	<td class=tdmain align=center colspan=4>User mit mehreren IP's (mehr als 1 IP)</td>
</tr><tr><td class=tdmainobg><strong>User-ID</strong></td><td class=tdmainobg><strong>IP</strong></td><td class=tdmainobg><strong>Erste Aktion</strong></td><td class=tdmainobg><strong>Letzte Aktion</strong></td><td class=tdmainobg>Agent</td></tr>");
$getr = mysql_query("SELECT *,count(id) AS idcount FROM stu_ip_table WHERE user_id!=13 ANd user_id!=2 ANd user_id!=5 AND user_id!=30 GROUP BY user_id",$myDB->dblink);
for ($i=0;$i<mysql_num_rows($getr);$i++) {
	$rdata = mysql_fetch_array($getr);
	if ($rdata[idcount] > 1) {
		$result = mysql_query("SELECT * FROM stu_ip_table WHERE user_id='".$rdata[user_id]."' AND user_id!=13 ANd user_id!=2 ANd user_id!=5 AND user_id!=30",$myDB->dblink);
		for ($j=0;$j<mysql_num_rows($result);$j++) {
			$data = mysql_fetch_array($result);
			fwrite($file,"<tr>
						  <td class=tdmainobg>".$data[user_id]."</td>
						  <td class=tdmainobg>".$data[ip]."</td>
						  <td class=tdmainobg>".date("d.m.Y H:i",$data[start])."</td>
						  <td class=tdmainobg>".date("d.m.Y H:i",$data[ende])."</td>
						  <td class=tdmainobg>".$data[agent]."</td></tr>");
		}
	}
}
fwrite($file,"</table><br><br><table width=100% bgcolor=#262323><tr>
	<td class=tdmain align=center colspan=5>Mehrere User pro IP (mehr als 1 User)</td>
</tr>
<tr><td class=tdmainobg><strong>User-ID</strong></td><td class=tdmainobg><strong>IP</strong></td><td class=tdmainobg><strong>Erste Aktion</strong></td><td class=tdmainobg><strong>Letzte Aktion</strong></td><td class=tdmainobg>Agent</td></tr>");
$getr = mysql_query("SELECT *,count(id) AS idcount FROM stu_ip_table WHERE user_id>100 GROUP BY ip ORDER BY user_id",$myDB->dblink);
for ($i=0;$i<mysql_num_rows($getr);$i++) {
	$rdata = mysql_fetch_array($getr);
	if ($rdata[idcount] > 1) {
		$result = mysql_query("SELECT * FROM stu_ip_table WHERE ip='".$rdata[ip]."' AND user_id!=13 ANd user_id!=2 ANd user_id!=5 AND user_id!=30",$myDB->dblink);
		for ($j=0;$j<mysql_num_rows($result);$j++) {
			$data = mysql_fetch_array($result);
			fwrite($file,"<tr>
						  <td class=tdmainobg>".$data[user_id]."</td>
						  <td class=tdmainobg>".$data[ip]."</td>
						  <td class=tdmainobg>".date("d.m.Y H:i",$data[start])."</td>
						  <td class=tdmainobg>".date("d.m.Y H:i",$data[ende])."</td>
						  <td class=tdmainobg>".$data[agent]."</td></tr>");
		}
	}
}
fwrite($file,"</table><br><br><table width=100% bgcolor=#262323><tr><td class=tdmain align=center colspan=5>Alle Zugriffe</td></tr>
<tr><td class=tdmainobg><strong>User-ID</strong></td><td class=tdmainobg><strong>IP</strong></td><td class=tdmainobg><strong>Erste Aktion</strong></td><td class=tdmainobg><strong>Letzte Aktion</strong></td><td class=tdmainobg>Agent</td></tr>");
$result = mysql_query("SELECT * FROM stu_ip_table ORDER BY start ASC",$myDB->dblink);
for ($i=0;$i<mysql_num_rows($result);$i++) {
	$data = mysql_fetch_array($result);
	if ($data[ende] == 0) $ende = "-";
	else $ende = date("d.m.Y H:i",$data[ende]);
	fwrite($file,"<tr><td class=tdmainobg>".$data[user_id]."</td><td class=tdmainobg>".$data[ip]."</td><td class=tdmainobg>".date("d.m.Y H:i",$data[start])."</td><td class=tdmainobg>".$ende."</td><td class=tdmainobg>".$data[agent]."</td></tr>");
}
fwrite($file,"</body></html>");
fclose($file);
mysql_query("DELETE FROM stu_ip_table",$myDB->dblink);
?>