<?php
if ($chk || $user) {
	setcookie("user", "");
	setcookie("chk", "");
}
include_once("inc/config.inc.php");
include_once("class/db.class.php");
$myDB = new db;
include_once("class/game.class.php");
$myGame = new game;
switch($page)
{
	default:
		$inc = "main/main.inc.php";
	case "main":
		$inc = "main/main.inc.php";
		break;
	case "register":
		$inc = "main/register.inc.php";
		break;
	case "story":
		$inc = "main/story.inc.php";
		break;
	case "rules":
		$inc = "main/rules.inc.php";
		break;
	case "media":
		$inc = "main/media.inc.php";
		break;
	case "con":
		$inc = "main/stucon.inc.php";
		break;
	case "team":
		$inc = "main/team.inc.php";
		break;
	case "chat":
		$inc = "main/chat.inc.php";
		break;
	case "spenden":
		$inc = "main/spende.inc.php";
		break;
	case "doc":
		$inc = "doc/index.php";
		break;
	case "lostpass":
		$inc = "main/lostpass.inc.php";
		break;
	case "wap":
		$inc = "main/wap.inc.php";
		break;
}
function createStats()
{
	global $myDB;
	$data[user] = $myDB->query("SELECT count(id) FROM stu_user WHERE id>100",1);
	$data[ouser] = $myDB->query("SELECT count(id) FROM stu_user WHERE UNIX_TIMESTAMP(lastaction)>".(time()-300)." AND id>100",1);
	$data[runden] = $myDB->query("SELECT max(runde) FROM stu_game_rounds",1);
	$tick = $myDB->query("SELECT value FROM stu_game WHERE fielddescr='tick'",1);
	$wart = $myDB->query("SELECT value FROM stu_game WHERE fielddescr='wartung'",1);
	$data[chatonline] = $myDB->query("SELECT value FROM stu_game WHERE fielddescr='chatonline'",1);
	$data[nuser] = $myDB->query("SELECT user FROM stu_user WHERE aktiv=1 ORDER BY id DESC LIMIT 1",1);
	if ($wart == 1) $data[status] = "<font color=Red>Wartung</font>";
	else $tick == 1 ? $data[status] = "<font color=Yellow>Tick</font>" : $data[status] = "<font color=Green>Online</font>";
	return $data;
}
$data = createStats();
?>
<html>
<head>
	<title>Star Trek Universe</title>
<style>
#navi {
	border-bottom: 1px solid #4c4c4c;
	width: 94px;
	padding: 0 0 0 0;
}
#navi ul {
	list-style: none;
	padding: 0 0 0 0;
	margin: 0;
}
#navi li {
		text-align: left;
}
#navi li.end {
		background-color: transparent;
		color: #111;
		font-size: 2px;
}
#navi li a {
	display: block;
	padding: 2px 2px 2px 4px;
	background-color: #111;
	border-top: 1px solid #4c4c4c;
	border-left: 1px solid #4c4c4c;
	border-right: 1px solid #4c4c4c;
	color: #8897cf;
	text-decoration: none;
	width: 100%;
}
html>body #navi li a {
		width: auto;
}
#navi li a:hover {
		background-color: #262323;
		color: #c2b942;
}
table.right {
	background: #262323;
}
td.top {
	background: #000000;
}
td.tdmain {
	background: #262323;
	color: #8897cf;
	font-family: Verdana, Arial;
	font-size: 9pt;
}
td.tdmainobg {
	background: #000000;
	color: #8897cf;
	font-family: Verdana,Arial;
	font-size: 9pt;
}
td.tdmainobglp {
	background: #000000;
	color: #8897cf;
	font-family: Verdana,Arial;
	font-size: 7pt;
}
a:link {
	color: #8897cf;
	TEXT-DECORATION: none;
}
a:visited {
	color: #8897cf;
	TEXT-DECORATION: none;
}
a:hover {
	color: #c2b942;
}
a:active {
	color: #c2b942;
}

input.text {
	background-color: #111;
	color: #8897cf;
	font-size: 9pt;
	border: 1px solid #4c4c4c;
}
.button {
	background-color: #262323;
	color: #8897cf;
	border: 1px outset #ffffff;
	font-size: 9pt;
}
.select {
	background-color: #262323;
	color: #8897cf;
	border: 1px outset #ffffff 
}
</style>
</head>
<body bgcolor="#000000" leftmargin="0" topmargin="0">
<table width="800">
<tr>
	<td class="top" colspan="3" height="80"><img src=gfx/banner2.jpg></td>
</tr>
<tr>
	<td class="tdmainobg" width="100" valign="top">
		<table width="100" class="right">
		<tr>
			<td class="tdmain" align="center">Spiel</td>
		</tr>
		<tr>
			<td class="tdmainobg">
			<div id="navi">
			<ul>
			<li><a href=?page=main>Main</a></li>
			<li><a href=?page=register>Registrieren</a></li>
			<li><a href=?page=story>Story</a></li>
			<li><a href=?page=rules>Regeln</a></li>
			<li><a href=?page=media>Media</a></li>
			<li><a href=?page=con>STU Con</a></li>
			<li><a href=?page=wap>STU W@P</a></li>
			<li><a href=?page=team>Team</a></li>
			<li><a href=?page=spenden>Spenden</a></li>
			<li><a href=http://scout.stuniverse.de target=_blank>Zeitung</a></li>
			<li><a href=http://www.galors.net/bewertung/ target=_blank>Links</a></li>
			</ul></div>
			</td>
		</tr>
		</table><br />
		<table width="100" class="right">
		<tr>
			<td class="tdmain" align="center">Hilfe</td>
		</tr>
		<tr>
			<td class="tdmainobg">
			<div id="navi">
			<ul>
			<li><a href=http://wiki.stuniverse.de target=_blank>Wiki</a></li>
			<li><a href=http://forum.stuniverse.de target=_blank>Forum</a></li>
			</ul></div>
			</td>
		</tr>
		</table>
	</td>
	<td width="570" valign="top" align="center"><?php include_once($inc); ?></td>
	<td width="100" valign="top">
		<table width="130" class="right">
		<tr>
			<td class="tdmain" align="center">Login</td>
		</tr>
		<form action=index2.php method=post name=f>
		<tr>
			<td width=130>
			<table width=100% bgcolor=#000000 cellpadding=1 cellspacing=0>
			<tr>
				<td class=tdmainobg width=10>U</td>
				<td class=tdmainobg width=90 align=Center><input type=text size=15 name=luser class="text" /></td>
			</tr>
			<script>
document.f.luser.focus();
</script>
			<tr>
				<td class=tdmainobg width=10>P</td>
				<td class=tdmainobg width=90 align=center><input type=password size=15 name=lpass class="text" /></td>
			</tr>
			<tr>
				<td class=tdmainobg colspan=2 align=center><input type=submit class=button value="Login" /></td>
			</tr>
			<tr>
				<td class=tdmainobglp colspan=2 align=center><a href=?page=lostpass>Passwort vergessen?</a></td>
			</tr>
			</table>
			
			</td>
		</tr>
		</form>
		</table><br />
		<table width="130" class="right">
		<tr>
			<td class="tdmain" align="center">Chat</td>
		</tr>
		<tr>
			<td class="tdmainobg">
			<a href=irc://irc.euirc.net/stu>irc.euirc.net</a> #stu<br>
			<?php
			echo "User im Chat: ".$data[chatonline]."<br>";?>
			<a href=?page=chat>Javachat</a>
			</td>
		</tr>
		</table><br />
		<table width="130" class="right">
		<tr>
			<td class="tdmain" align="center">Stats</td>
		</tr>
		<tr>
			<td class="tdmainobg">
			<?php
			echo 'Spieler: '.$data[user].'<br />
			online: '.$data[ouser].'<br />
			Runden: '.$data[runden].'<br />
			Status: '.$data[status].'<br>
			Neuester Spieler: '.stripslashes($data[nuser]);
			?></td>
		</tr>
		</table>
	</td>
</tr>
</table><br>
<table width=800>
<tr>
	<td width=100>&nbsp;</td>
	<td width=590>
		<table bgcolor=#262323 width=590>
		<tr>
			<td class=tdmain align=Center><strong>Copyrighthinweis</strong></td>
		</tr>
		<tr>
			<td class=tdmainobg align=Center width=590>
			Star Trek™ is a registered trademark of Paramount Pictures.<br>
			This site is strictly non-profit.<br>
			No copyright infringement is intended.<br> 
			All other Content is copyrighted by the siteowner, unless otherwise noted. ©2003,2004,2005</td>
		</tr>
		</table>
	</td>
	<td width=110>&nbsp;</td>
</tr>
</table>
</body>
</html>