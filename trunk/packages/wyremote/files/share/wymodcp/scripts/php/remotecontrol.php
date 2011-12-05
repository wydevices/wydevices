<?php 
$cb = $_REQUEST["clickedbutton"];
echo $cb;
switch ($cb){

case "wheel_rwd":
			system ("wget http://127.0.0.1:81/scripts/expect/WHEEL_RWD.cgi");
			break;

case "wheel_fwd":
			system ("wget http://127.0.0.1:81/scripts/expect/WHEEL_FWD.cgi");
			break;
case "left":
			system ("wget http://127.0.0.1:81/scripts/expect/LEFT.cgi");
			break;
case "right":
			system ("wget http://127.0.0.1:81/scripts/expect/RIGHT.cgi");
			break;
case "up":
			system ("wget http://127.0.0.1:81/scripts/expect/UP.cgi");
			break;
case "down":
			system ("wget http://127.0.0.1:81/scripts/expect/DOWN.cgi");
			break;
case "select":
			system ("wget http://127.0.0.1:81/scripts/expect/SELECT.cgi");
			break;
case "toggle_menu":
			system ("wget http://127.0.0.1:81/scripts/expect/TOGGLE_MENU.cgi");
			break;
case "action_menu":
			system ("wget http://127.0.0.1:81/scripts/expect/ACTION_MENU.cgi");
			break;
case "volume_up":
			system ("wget http://127.0.0.1:81/scripts/expect/VOLUME_UP.cgi");
			break;
case "volume_down":
			system ("wget http://127.0.0.1:81/scripts/expect/VOLUME_DOWN.cgi");
			break;
case "mute":
			system ("wget http://127.0.0.1:81/scripts/expect/MUTE.cgi");
			break;
case "record":
			system ("wget http://127.0.0.1:81/scripts/expect/RECORD.cgi");
			break;
case "stop":
			system ("wget http://127.0.0.1:81/scripts/expect/STOP.cgi");
			break;
case "info":
			system ("wget http://127.0.0.1:81/scripts/expect/INFO.cgi");
			break;
case "home":
			system ("wget http://127.0.0.1:81/scripts/expect/HOME.cgi");
			break;
case "marker":
			system ("wget http://127.0.0.1:81/scripts/expect/MARKER.cgi");
			break;
case "power":
			system ("wget http://127.0.0.1:81/scripts/expect/POWER.cgi");
			break;
case "exit":
			system ("wget http://127.0.0.1:81/scripts/expect/EXIT.cgi");
			break;
case "sleep":
			system ("wget http://127.0.0.1:81/scripts/expect/SLEEP.cgi");
			break;
default :
			echo "DEFAULT";
}
?>
