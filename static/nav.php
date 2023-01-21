<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled</title>
<?php echo "<link rel=\"STYLESHEET\" type=\"text/css\" href=\"".$css."\">"; ?>
</head>
<body>
<div id="navi">
<ul>
<?php
	echo "<li><a href=../main.php target=main>Maindesk</a></li>
	<li><a href=../main.php?page=colony target=main>Kolonien</a></li>
	<li><a href=../main.php?page=ship target=main>Schiffe</a></li>
	<li><a href=../main.php?page=comm target=main>Kommunikation</a></li>
	<li><a href=../main.php?page=trade&section=boerse target=main>Warenbörse</a></li>
	<li><a href=../main.php?page=ally target=main>Allianzschirm</a></li>
	<li><a href=../main.php?page=hally target=main>Handelsallianz</a></li>
	<li><a href=../main.php?page=options target=main>Einstellungen</a></li>
	<li><a href=../main.php?page=starmap target=main>Sternenkarte</a></li>
	<li><a href=../main.php?page=stats target=main>Statistiken</a></li>
	<li><a href=../main.php?page=history target=main>History</a></li>";
	if ($npcmenu == 1) echo "<li><a href=../main.php?page=npc target=main>NPC</a></li>";
	echo "<li><a href=../main.php?page=logout target=_parent>Logout</a></li>";
?>
</ul>
</div>
&nbsp;<br />
<input type=text size=3 class=text maxlength=3 /> | <input type=text size=3 class=text maxlength=3 />&nbsp;<a href=../main.php?page=hally&section=scl target=leftbottom><img src=http://gfx.stuniverse.de/buttons/classm.gif title="Kolonieliste" border=0></a>
</body>
</html>