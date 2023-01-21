<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Head-Navigation</title>
</head>
<meta http-equiv="REFRESH" content="1200; url=http://www.stuniverse.de/static/head.php?css=<?php echo $css ?>">
<link rel="STYLESHEET" type="text/css" href="<?php echo $css; ?>">
<script LANGUAGE="JavaScript" TYPE="text/javascript">
<!--
var digital = new Date( "<?php echo date("M, d Y G:i:s") ?>");
function clock() {
	if(!document.all && !document.getElementById) return;
	var hours   = digital.getHours();
	var minutes = digital.getMinutes();
	var seconds = digital.getSeconds();
	digital.setSeconds( seconds+1 );
	if(hours <= 9) hours = "0" + hours;
	if(minutes <= 9) minutes = "0" + minutes;
	if(seconds <= 9) seconds = "0" + seconds;
	dispTime = hours + ":" + minutes + ":" + seconds;
	if(document.getElementById) {
	    document.getElementById("uhrzeit").innerHTML = dispTime;
	} else if(document.all) {
	   	uhrzeit.innerHTML = dispTime;
	}
	setTimeout("clock()", 1000);
}
//--></script>
<body bgcolor="#000000" onload="clock()">
<table width=100%>
<tr>
	<td class=tdhead align=center><div id="head">
	<strong>Star Trek Universe</strong> 
	<script language="JavaScript">
	document.write(' - Serverzeit: ');
	</script><span id="uhrzeit"></span>
</div>
	</td>
</tr>
</table>
</body>
</html>
