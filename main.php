<?php
$qcount = 0;
include_once("inc/config.inc.php");
include_once("class/mod.class.php");
include_once("class/db.class.php");
session_start();
// Überprüfung der Cookies
if (!$_SESSION["user"] || !$_SESSION["chk"])
{
	$page = "error";
	$errorId = "996";
}
// Datenbankverbindung herstellen
$user = $_SESSION["user"];

$myDB = new db;
if ($sqlerr == 1)
{
	$page = "error";
	$errorid = 998;
	addlog("998","1",$user,"Datenbankfehler");
}
else
{
	// Start der Session bzw. Überprüfung einer bereitss bestehenden Session
	include_once("class/usersess.class.php");
	$mySession = new usersess;
	$login = $mySession->checkcookie();
	if ($login != 1)
	{
		$errorid = 996;
		echo "<head>
		<link rel=\"STYLESHEET\" type=\"text/css\" href=gfx/css/style.css>
	  </head>";
		include_once("error.php");
		exit;
	}
	// Include der restlichen Klassen für Schiffssteuerung, etc
	include_once("class/game.class.php");
	$myGame = new game;
	if ($myGame->getvalue('tick') == 1)
	{
		$errorid = 100;
		echo "<head>
		<link rel=\"STYLESHEET\" type=\"text/css\" href=gfx/css/style.css>
	  </head>";
		include_once("error.php");
		exit;
	}
	include_once("class/user.class.php");
	$myUser = new user;
	$grafik = $myUser->ugrafik;
	include_once("class/comm.class.php");
	$myComm = new comm;
	include_once("class/ship.class.php");
	$myShip = new ship;
	include_once("class/fleet.class.php");
	$myFleet = new fleet;
	include_once("class/colony.class.php");
	$myColony = new colony;
	include_once("class/map.class.php");
	$myMap = new map;
	include_once("class/ally.class.php");
	$myAlly = new ally;
	include_once("class/trade.class.php");
	$myTrade = new trade;
	include_once("class/history.class.php");
	$myHistory = new history;
	$myGame->loguser(getenv("REMOTE_ADDR"),getenv("HTTP_USER_AGENT"));
	if ($page != "main" && $page != "options" && $page != "logout" && $page && $myUser->udelmark == 1) $myUser->updateUserById($user,0,"delmark");
}
if ($page != "error") $result = $mySession->sessioncheck();
if ($page == "logout") $mySession->logout();
if ($page != "error") $wartung = $myGame->getvalue("wartung");
if (($user > 102) && ($wartung == 1)) $page = "wartung";
if (($page != "wartung") && ($page != "tick") && ($page != "error"))
{
	//$myColony->finishProcesses();
	//$myColony->setdaytime();
}
if ($myUser->umozilla == 1)
{
	$css = "gfx/css/style.css";
	$mcss = "../gfx/css/style.css";
}
else
{
	$css = $grafik."/css/style.css";
	$mcss = $grafik."/css/style.css";
}
if ($login == 1)
{
	if ($HTTP_POST_VARS["avm"] == "on" && $myUser->upvac > 0)
	{
		$mySession->logout();
		$myUser->avm();
		$errorid = 700;
		echo "<head><link rel=\"STYLESHEET\" type=\"text/css\" href=gfx/css/style.css></head>";
		include_once("error.php");
		$myGame->addlog("700","5",$user,"Urlaubsmodus aktiviert");
		exit;
	}
	if ($myUser->uvac == 1)
	{
		$mdvm = 1;
		$myUser->dvm();
	}
	$result = $myComm->checknewmsg($user);
	if ($action == "ignorepm")
	{
		$myComm->markallpmasread($user);
		echo "<script language=Javascript>parent.frames[1].location.href=\"static/leftbottom.php?grafik=$grafik&css=$mcss\";</script>";
		$result = 0;
	}
	if (($result != 0) && ($section != "delall") && ($page != "npm"))
	{
		if (($page == "showinfo") || ($page == "shiphelp") || ($page == "help"))
		{
			$tpa = $page;
			$tid = $id;
			$tsec = $section;
			$tfie = $field;
			$tcol = $col;
			$tcla = $class;
			$tclai = $classid;
			$tsid = $shipid;
		}
		echo "<script language=Javascript>parent.frames[1].location.href=\"main.php?page=npm&tpa=".$tpa."&tid=".$tid."&tsec=".$tsec."&tfie=".$tfie."&tcol=".$tcol."&tcla=".$tcla."&tclai=".$tclai."&tsid=".$tsid."\";</script>";
	}
}
switch($page)
{
	default:
		$inc = "desk.php";
	case "main":
		$inc = "desk.php";
		break;
	case "comm":
		$inc = "comm.php";
		break;
	case "options":
		$inc = "options.php";
		break;
	case "colony":
		$inc = "colony.php";
		break;
	case "ship":
		$inc = "ship.php";
		break;
	case "trade":
		$inc = "trade.php";
		break;
	case "ally":
		$inc = "ally.php";
		break;
	case "hally":
		$inc = "hally.php";
		break;
	case "showinfo":
		$inc = "showinfo.php";
		break;
	case "help":
		$inc = "help.php";
		break;
	case "shiphelp":
		$inc = "shiphelp.php";
		break;
	case "starmap":
		$inc = "starmap.php";
		break;
	case "history":
		$inc = "history.php";
		break;
	case "stats":
		$inc = "stats.php";
		break;
	case "npc":
		$inc = "npc.php";
		break;
	case "folist":
		$inc = "folist.php";
		break;
	case "npm":
		$inc = "npm.php";
		break;
	case "shiptest":
		$inc = "shiptest.php";
		break;
	case "logout":
		$inc = "logout.php";
		break;
	case "error":
		$inc = "error.php";
		break;
	case "wartung":
		$inc = "error.php";
		$errorid = 999;
		break;
	case "tick":
		$inc = "error.php";
		$errorid = 100;
		break;
}
if (!$grafik) $grafik = "gfx/";
echo "<head>
	<link rel=\"STYLESHEET\" type=\"text/css\" href=\"".$css."\">
	<SCRIPT LANGUAGE='JavaScript'><!--
	
	var Win = null;
	
	function openfl()
	{
	        str=\"main.php?page=folist\";
	        Win = window.open(str,'Win','width=300,height=400,resizeable=no,scrollbars=yes');
	        window.open(str,'Win','width=300,height=400');
	        Win.opener = self;
	}
	
	function cp(objekt,datei)
	{
		document.images[objekt].src = \"".$grafik."/\" + datei + \".gif\"
	}
	//-->
	</SCRIPT>";
if ($page == "folist") echo "<title>STU Kontaktliste</title>";
echo "</head>";
if ($page == "folist") echo "<meta http-equiv=\"REFRESH\" content=\"200; url=http://www.stuniverse.de/main.php?page=folist\">";
unset($result);
include_once($inc);
echo "<br>Queries: ".$qcount;
if (time() >= $myDB->query("SELECT value FROM stu_game WHERE fielddescr='proceed_time'",1))
{
	$myDB->query("UPDATE stu_game SET value=".(time()+15)." WHERE fielddescr='proceed_time'");
	$myDB->query("UPDATE stu_game SET value=".$user." WHERE fielddescr='proceed_user'");
}
?>
