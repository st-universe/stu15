<?php
include_once("inc/config.inc.php");
include_once("class/db.class.php");
$myDB = new db;
if ($sqlerr == 1) {
	echo "<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.01 Transitional//EN>
	<html>
	<head>
	<title>Star Trek Universe</title>
	</head>
	<link rel=\"STYLESHEET\" type=\"text/css\" href=\"gfx/css/style.css\">
	<table width=40% bgcolor=#262323 align=center cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=center>Login</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center>Verbindung zur Datenbank fehlgeschlagen</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center><a href=index.php?page=login>Zurück</a></td>
	</tr>
	</table></html>";
	exit;
}
include_once("class/user.class.php");
$myUser = new user;
include_once("class/usersess.class.php");
$mySession = new usersess;
include_once("class/game.class.php");
$myGame = new game;
session_set_cookie_params(0);
session_start();
$login = $mySession->login($luser,$lpass,$alog);
if ($login[code] == 1)
{
	$userdata = $myUser->getuserbyid($login[uid]);
	if ($userdata[status] == 9) $add = "&npcmenu=1";
	$grafik = $userdata[grafik];
	if ($userdata[mozilla] == 1) $css = "../gfx/css/style.css";
	else $css = $grafik."/css/style.css";
	echo "<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.01 Transitional//EN>
	<html>
	<head>
	<title>Star Trek Universe</title>
	<link rel=\"alternate\" type=\"application/rss+xml\" href=\"rss/rss.php\" title=\"STU KN-Feed\" />
	</head>
	<!-- frames -->
	<frameset cols=\"150,*\" framespacing=\"0\" frameborder=\"0\">
	    <frameset rows=\"290,*\" framespacing=0 frameborder=0>
	        <frame name=head src='static/nav.php?grafik=".$grafik.$add."&css=".$css."' marginwidth=0 marginheight=0 frameborder=0 noresize scrolling=\"no\">
	        <frame name=leftbottom src='static/leftbottom.php?grafik=".$grafik."&css=".$css."' marginwidth=0 marginheight=0 scrolling=auto frameborder=0 noresize>
	    </frameset>
		<frameset rows=\"50,*\" framespacing=0 frameborder=0>
	        <frame name=head src='static/head.php?css=".$css."' marginwidth=0 marginheight=0 scrolling=auto frameborder=0 noresize>
	        <frame name=main src='main.php' marginwidth=0 marginheight=0 scrolling=auto frameborder=0 noresize>
	    </frameset>
	</frameset>";
}
else
{
	$myGame->addlog(101,9,$luser,"Login fehlgeschlagen-");
	echo "<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.01 Transitional//EN>
	<html>
	<head>
	<title>Star Trek Universe</title>
	</head>
	<link rel=\"STYLESHEET\" type=\"text/css\" href=\"gfx/css/style.css\">
	<table width=40% bgcolor=#262323 align=center cellspacing=1 cellpadding=1>
	<tr>
		<td class=tdmain align=center>Login</td>
	</tr>
	<tr>
		<td class=tdmainobg align=center>".$login[msg]."<br>
		Bei Loginproblemen, siehe <a href=http://forum.stuniverse.de/viewtopic.php?t=19>hier</a></td>
	</tr>
	<tr>
		<td class=tdmain align=center><a href=index.php>Zurück</a></td>
	</tr>
	</table></html>";
}
?>
