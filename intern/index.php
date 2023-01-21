<?php
include_once("../inc/config.inc.php");
include_once("class/db.class.php");
$myDB = new db;
include_once("class/colony.class.php");
$myColony = new colony;
include_once("class/ship.class.php");
$myShip = new ship;
include_once("class/map.class.php");
$myMap = new map;
include_once("class/user.class.php");
$myUser = new user;
include_once("class/comm.class.php");
$myComm = new comm;
$grafik = "http://gfx.stuniverse.de";
if (!$page || ($page == "main")) $inc = "main.php";
elseif ($page == "player") $inc = "player.php";
elseif ($page == "colony") $inc = "colony.php";
elseif ($page == "multi") $inc = "multi.php";
elseif ($page == "ships") $inc = "ships.php";
else $page = "error.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Star Trek Universe - Adminbereich</title>
</head>
<link rel="STYLESHEET" type="text/css" href="http://www.stuniverse.de/gfx/css/style.css">
<body bgcolor="#000000" text="#FFFFFF" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<table cellspacing="0" cellpadding="0" border="0" width=100%>
<tr>
    <td width=100 valign=top>
	<table width=100%>
	<tr>
		<td class=tdnav><a href=index.php>Hauptseite</a></td>
	</tr>
	<tr>
		<td class=tdnav><a href=index.php?page=player>Spieler</a></td>
	</tr>
	<tr>
		<td class=tdnav><a href=index.php?page=colony>Kolonien</a></td>
	</tr>
	<tr>
		<td class=tdnav><a href=index.php?page=ships>Schiffe</a></td>
	</tr>
	<tr>
		<td class=tdnav><a href=index.php?page=multi>Multi</a></td>
	</tr>
	</table>
	</td>
    <td valign=top width=800>
	<?php include_once($inc); ?>
	</td>
</tr>
</table>
</body>
</html>
