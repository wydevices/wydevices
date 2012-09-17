<?php 

$video_resolution = exec("fbset -i -fb /dev/fb0 | grep mode | head -n 1");

unset($cb);
unset($remote_action);

if (!empty($_REQUEST["clickedbutton"])){

  $cb = $_REQUEST["clickedbutton"];

  switch ($cb){
    case "wheel_rwd": $remote_action = "WHEEL_RWD"; break;
    case "wheel_fwd": $remote_action = "WHEEL_FWD"; break;
    case "left": $remote_action = "LEFT"; break;
    case "right": $remote_action = "RIGHT"; break;
    case "up": $remote_action = "UP"; break;
    case "down": $remote_action = "DOWN"; break;
    case "select": $remote_action = "SELECT"; break;
    case "toggle_menu": $remote_action = "TOGGLE_MENU"; break;
    case "action_menu": $remote_action = "ACTION_MENU"; break;
    case "volume_up": $remote_action = "VOLUME_UP"; break;
    case "volume_down": $remote_action = "VOLUME_DOWN"; break;
    case "mute": $remote_action = "MUTE"; break;
    case "record": $remote_action = "RECORD"; break;
    case "stop": $remote_action = "STOP"; break;
    case "info": $remote_action = "INFO"; break;
    case "home": $remote_action = "HOME"; break;
    case "marker": $remote_action = "MARKER"; break;
    case "power": $remote_action = "POWER"; break;
    case "exit": $remote_action = "EXIT"; break;
    case "sleep": $remote_action = "SLEEP"; break;
    default : unset($remote_action); echo "DEFAULT";
  }

  system("/wymedia/usr/bin/empty.sh \"".$remote_action."\"");

}

?>

<table>

<td>
<div id="wyremote_table">
  <img src="./style/wyremote.png" width="300" height="786" border="0" usemap="#map" />
  <map name="map">
    <!-- #$-:Image map file created by GIMP Image Map plug-in -->
    <!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
    <!-- #$-:Please do not edit lines starting with "#$" -->
    <!-- #$VERSION:2.3 -->
    <!-- #$AUTHOR:minukab -->
    <area shape="circle" coords="146,96,18" alt="home" title="home" href="#" onClick="PressButton('home');" />
    <area shape="circle" coords="88,166,18" alt="wheel_rwd" title="wheel_rwd" href="#" onClick="PressButton('wheel_rwd');" />
    <area shape="circle" coords="201,164,17" alt="wheel_fwd" title="wheel_fwd" href="#" onClick="PressButton('wheel_fwd');" />
    <area shape="circle" coords="144,196,19" alt="up" title="up" href="#" onClick="PressButton('up');" />
    <area shape="circle" coords="144,301,18" alt="down" title="down" href="#" onClick="PressButton('down');" />
    <area shape="circle" coords="88,252,17" alt="left" title="left" href="#" onClick="PressButton('left');" />
    <area shape="circle" coords="199,249,17" alt="right" title="right" href="#" onClick="PressButton('right');" />
    <area shape="circle" coords="144,250,24" alt="select" title="select" href="#" onClick="PressButton('select');" />
    <area shape="circle" coords="85,345,23" alt="marker" title="marker" href="#" onClick="PressButton('marker');" />
    <area shape="circle" coords="144,357,24" alt="action_menu" title="action_menu" href="#" onClick="PressButton('action_menu');" />
    <area shape="circle" coords="203,342,22" alt="toggle_menu" title="toggle_menu" href="#" onClick="PressButton('toggle_menu');" />
    <area shape="circle" coords="83,421,22" alt="record" title="record" href="#" onClick="PressButton('record');" />
    <area shape="circle" coords="143,419,23" alt="stop" title="stop" href="#" onClick="PressButton('stop');" />
    <area shape="circle" coords="204,418,24" alt="info" title="info" href="#" onClick="PressButton('info');" />
    <area shape="circle" coords="82,471,22" alt="mute" title="mute" href="#" onClick="PressButton('mute');" />
    <area shape="circle" coords="143,469,22" alt="volume_down" title="volume_down" href="#" onClick="PressButton('volume_down');" />
    <area shape="circle" coords="205,469,23" alt="volume_up" title="volume_up" href="#" onClick="PressButton('volume_up');" />
    <area shape="circle" coords="143,575,24" alt="power" title="power" href="#" onClick="PressButton('power');" />
  </map>
</div>
</td>

<td width="100%" align="right">
  <!-- TODO : Detect resolution ratio for resize image to target ratio -->
  <!--<img src="./capture.png" width="720" height="406">--><!-- For 16:9 -->
  <!-- <img src="./scripts/php/capture.png" width="720" height="576"> --><!-- For 4:3 -->
<?php
$time = time();
system ("rm -f captures/*");
system ("fb2png captures/\"".$time."\".png");
$capture = exec("ls captures");
echo "<img src='./scripts/php/captures/$capture' width='720' height='406'>";
?>
<br /><br />
<table>
<tr><td>
FrameBuffer original resolution <?php echo $video_resolution; ?><br />
Last action : <?php if (!$cb) {echo "nothing";} else {echo $cb;} ?>
</td></tr>
<tr><td>
<form><input value="Reload screen capture" onclick="ShowWyRemote();" class="button" type="button" style="width: 200px" /></form>
</td></tr>
</table>
</td>

</table>
