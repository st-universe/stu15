<?php
include_once("/srv/www/htdocs/web1/html/inc/config.inc.php");
include_once($global_path."class/db.class.php");
$myDB = new db;

$result = $myDB->query("SELECT a.*,UNIX_TIMESTAMP(a.date) as date_t,b.user FROM stu_allys_messages as a LEFT JOIN stu_user as b ON a.user_id=b.id WHERE a.allys_id=180 ORDER BY a.id ASC");
while($m=mysql_fetch_assoc($result))
{
	$i++;
	$b++;
	$kn .= "<table width=800 border=1 cellspacing=2 cellpadding=2 align=center bordercolor=#000000 bgcolor=#262323>
			<tr>
				<td rowspan=3 class=tdmainobg align=center width=70>
				<font style=\"font: 9px sans-serif; color:Gray;\">".$m[id]."</font></td>
			    <td colspan=2 align=center class=tdmain width=730>".stripslashes($m[subject])."</td>
			</tr>
			<tr>
			    <td width=610 class=tdmainobg valign=top>".$m[rasse].stripslashes($m[user])."</td>
			    <td width=120 class=tdmain valign=top>".date("d.m.Y H:i",$m[date_t])."</td>
			</tr>
			<tr>
			    <td colspan=2 class=tdmain width=730 height=50 valign=top>".stripslashes(nl2br($m[text]))."</td>
			</tr>
			</table>
		<br>";
	if ($i == 50 || $b == mysql_num_rows($result))
	{
		$j++;
		if (file_exists($global_path."/kna/seite".$j.".html")) unlink($global_path."/kna/seite".$j.".html");
		$fp = fopen($global_path."/kna/seite".$j.".html","a+");
		fwrite($fp,"<html><body><head><link rel=\"STYLESHEET\" type=\"text/css\" href=style.css></head>".$kn."</body></html>");
		fclose($fp);
		$kn = "";
		$i = 0;
		$bz .= $global_path."kna/seite".$j.".html";
	}
}
unlink($global_path."/kna/index.html");
$fp = fopen($global_path."/kna/index.html","a+");
$a = "<table width=100% bgcolor=#262323 cellspacing=1 cellpadding=1>
<tr><td colspan=6 class=tdmain align=center>KN-Archiv</td></tr><tr>";
for($i=1;$i<=$j;$i++)
{
	$k++;
	$a .= "<td class=tdmainobg><a href=seite".$i.".html>Seite ".$i."</a></td>";
	if ($k == 6)
	{
		$k = 0;
		$a .= "</tr><tr>";
	}
}
$a .= "</tr></table>";
fwrite($fp,"<html><body><head><link rel=\"STYLESHEET\" type=\"text/css\" href=style.css></head>".$a."</body></html>");
fclose($fp);
?>