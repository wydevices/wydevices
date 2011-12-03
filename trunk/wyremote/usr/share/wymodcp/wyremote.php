<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">

<title>wyremote 0.2</title>

<script type="text/javascript">
function PressButton(clickedbutton) {
	var xmlhttp;
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if(xmlhttp.readyState==4) {
			//PressButton(xmlhttp.responseText);
		}
	}
	//alert (clickedbutton);
	xmlhttp.open("GET","./scripts/php/remotecontrol.php?clickedbutton="+clickedbutton,false);
	xmlhttp.send(null);
}
</script>

</head>

<body>

<table>

<td>
<img src="./style/wyremote.png" width="300" height="786" border="0" usemap="#map" />
<map name="map">
<!-- #$-:Image map file created by GIMP Image Map plug-in -->
<!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
<!-- #$-:Please do not edit lines starting with "#$" -->
<!-- #$VERSION:2.3 -->
<!-- #$AUTHOR:minukab -->
<area shape="circle" coords="146,96,18" alt="home" title="home" href="./wyremote.php" onClick="PressButton('home');" />
<area shape="circle" coords="88,166,18" alt="wheel_rwd" title="wheel_rwd" href="./wyremote.php" onClick="PressButton('wheel_rwd');" />
<area shape="circle" coords="201,164,17" alt="wheel_fwd" title="wheel_fwd" href="./wyremote.php" onClick="PressButton('wheel_fwd');" />
<area shape="circle" coords="144,196,19" alt="up" title="up" href="./wyremote.php" onClick="PressButton('up');" />
<area shape="circle" coords="144,301,18" alt="down" title="down" href="./wyremote.php" onClick="PressButton('down');" />
<area shape="circle" coords="88,252,17" alt="left" title="left" href="./wyremote.php" onClick="PressButton('left');" />
<area shape="circle" coords="199,249,17" alt="right" title="right" href="./wyremote.php" onClick="PressButton('right');" />
<area shape="circle" coords="144,250,24" alt="select" title="select" href="./wyremote.php" onClick="PressButton('select');" />
<area shape="circle" coords="85,345,23" alt="marker" title="marker" href="./wyremote.php" onClick="PressButton('marker');" />
<area shape="circle" coords="144,357,24" alt="action_menu" title="action_menu" href="./wyremote.php" onClick="PressButton('action_menu');" />
<area shape="circle" coords="203,342,22" alt="toggle_menu" title="toggle_menu" href="./wyremote.php" onClick="PressButton('toggle_menu');" />
<area shape="circle" coords="83,421,22" alt="record" title="record" href="./wyremote.php" onClick="PressButton('record');" />
<area shape="circle" coords="143,419,23" alt="stop" title="stop" href="./wyremote.php" onClick="PressButton('stop');" />
<area shape="circle" coords="204,418,24" alt="info" title="info" href="./wyremote.php" onClick="PressButton('info');" />
<area shape="circle" coords="82,471,22" alt="mute" title="mute" href="./wyremote.php" onClick="PressButton('mute');" />
<area shape="circle" coords="143,469,22" alt="volume_down" title="volume_down" href="./wyremote.php" onClick="PressButton('volume_down');" />
<area shape="circle" coords="205,469,23" alt="volume_up" title="volume_up" href="./wyremote.php" onClick="PressButton('volume_up');" />
<area shape="circle" coords="143,575,24" alt="power" title="power" href="./wyremote.php" onClick="PressButton('power');" />
</map>
</td>

<td>
<?php
system ("sleep 5");
system ("sh /wymedia/screen-capture/bin/screen-capture > /dev/null 2>&1");
?>
<img src="./capture.png" width="1000" height="563">
<form>
<input value="Reload page" onclick="javascript:window.location.reload();" type="button">
</form>
</td>

</table>

</body>

</html>
