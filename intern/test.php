<?php
include_once("../inc/config.inc.php");
include_once("../class/db.class.php");
$myDB = new db;
mysql_query("DELETE FROM stu_stats_iptable WHERE ende_tsp<".(time()-864000)."",$myDB->dblink);
if (!$mode)
	{
	echo "<strong>Account-Prüfung</strong><br>";
	$result = mysql_query("SELECT user_id,count(user_id) as idcount,ip FROM stu_stats_iptable WHERE user_id>100 GROUP by ip ORDER BY ende_tsp DESC",$myDB->dblink);
	while ($data=mysql_fetch_array($result))
	{
		if ($data[idcount] > 1)
		{
			echo $data[ip]." - <strong>".$data[idcount]."</strong><br>";
			$res2 = mysql_query("SELECT a.user_id,a.ende_tsp,a.start_tsp,a.agent,b.user FROM stu_stats_iptable as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.ip='".$data[ip]."' AND b.id>100",$myDB->dblink);
			while($data2=mysql_fetch_array($res2))
			{
				if ($cd[$data2[ip].$data2[start_tsp]] != 1)
				{
					echo "-> ".$data2[user_id]." ".strip_tags($data2[user])."<br>
															-- Erster Zugriff: ".date("d.m H:i",$data2[start_tsp])."<br>
															-- Letzter Zugriff: ".date("d.m H:i",$data2[ende_tsp])."<br>
															-- Browser: ".$data2[agent]."<br>";
					if (!$qrp) $qrp = "user_id=".$data2[user_id];
					else $qrp .= " OR user_id=".$data2[user_id];
					$cd[$data2[ip].$data2[start_tsp]] = 1;
				}
			}
			if ($qrp)
			{
				$res3 = mysql_query("SELECT ip,count(ip) as idcount FROM stu_stats_iptable WHERE (".$qrp.") AND user_id>100 AND ip!='".$data[ip]."' GROUP BY ip",$myDB->dblink);
				if (mysql_num_rows($res3) > 0)
				{
					while($data3=mysql_fetch_array($res3))
					{
						if ($data3[idcount] > 1)
						{
							echo "<strong>Ähnliche Vorfälle</strong><br>";
							$res4 = mysql_query("SELECT a.user_id,a.ende_tsp,a.start_tsp,a.agent,b.user FROM stu_stats_iptable as a LEFT OUTER JOIN stu_user as b ON a.user_id=b.id WHERE a.ip='".$data3[ip]."' AND b.id>100",$myDB->dblink);
							while($data4=mysql_fetch_array($res4))
							{
								if ($cd[$data4[ip].$data4[start_tsp]] != 1)
								{
									echo "  --> ".$data4[user_id]." ".strip_tags($data4[user])."<br>
																			&nbsp;&nbsp;--- Erster Zugriff: ".date("d.m H:i",$data4[start_tsp])."<br>
																			&nbsp;&nbsp;--- Letzter Zugriff: ".date("d.m H:i",$data4[ende_tsp])."<br>
																			&nbsp;&nbsp;--- Browser: ".$data4[agent]."<br>";
									$cd[$data4[ip].$data4[start_tsp]] = 1;
								}
							}
						}
					}
				}
			}
			echo "<br>";
			unset($qrp);
		}
	}
	echo "<br><br><strong>Passwort-Prüfung</strong><br>";
	$result = mysql_query("SELECT id,pass,count(id) as idcount FROM stu_user WHERE id>100 GROUP by pass ORDER BY idcount DESC",$myDB->dblink);
	while ($data=mysql_fetch_array($result))
	{
		if ($data[idcount] > 1)
		{
			$res2 = mysql_query("SELECT id,UNIX_TIMESTAMP(lastaction) as last_tsp,user FROM stu_user WHERE pass='".$data[pass]."' ORDER BY id",$myDB->dblink);
			echo $data[pass]." - <strong>".$data[idcount]."</strong><br>";
			while($data2=mysql_fetch_array($res2))
			{
				$res3 = mysql_query("SELECT ip,start_tsp,ende_tsp FROM stu_stats_iptable WHERE user_id=".$data2[id]."",$myDB->dblink);
				echo "-> ".$data2[id]." ".strip_tags($data2[user])." (".date("d.m.Y H:i",$data2[last_tsp]).")<br>";
				while($data3=mysql_fetch_array($res3)) echo "--> ".$data3[ip]." Start: ".date("d.m.Y H:i",$data3[start_tsp])." Ende: ".date("d.m.Y H:i",$data3[ende_tsp])."<br>";
			}
		}
	}
	unset($data);
	unset($data2);
	unset($data3);
}
elseif ($mode == "test")
{
	$u1 = 244;
	$u2 = 245;
	$result = mysql_query("SELECT user_id,ip,start_tsp,ende_tsp FROM stu_stats_iptable WHERE user_id=".$u1." OR user_id=".$u2." ORDER BY start_tsp DESC",$myDB->dblink);
	echo "<table>";
	while($data=mysql_fetch_array($result))
	{
		echo "<tr><td>".$data[user_id]."</td><td>".$data[ip]."</td><td>Start: ".date("d.m.Y H:i",$data[start_tsp])."</td><td>Ende: ".date("d.m.Y H:i",$data[ende_tsp])."</td></tr>";
		$bl = date("d",$data[start_tsp]);
		if ($bl != $bla)
		{
			$bla = $bl;
			echo "<tr><td colspan=4>&nbsp;</td></tr>";
		}
	}
	echo "</table>";
}
?>