<?php 

$cb = $_REQUEST["clickedbutton"];

switch ($cb){
case "wheel_rwd":
			system ("empty.sh WHEEL_FWD");
			break;
case "wheel_fwd":
			system ("empty.sh WHEEL_FWD");
			break;
case "left":
			system ("empty.sh LEFT");
			break;
case "right":
			system ("empty.sh RIGHT");
			break;
case "up":
			system ("empty.sh UP");
			break;
case "down":
			system ("empty.sh DOWN");
			break;
case "select":
			system ("empty.sh SELECT");
			break;
case "toggle_menu":
			system ("empty.sh TOGGLE_MENU");
			break;
case "action_menu":
			system ("empty.sh ACTION_MENU");
			break;
case "volume_up":
			system ("empty.sh VOLUME_UP");
			break;
case "volume_down":
			system ("empty.sh VOLUME_DOWN");
			break;
case "mute":
			system ("empty.sh MUTE");
			break;
case "record":
			system ("empty.sh RECORD");
			break;
case "stop":
			system ("empty.sh STOP");
			break;
case "info":
			system ("empty.sh INFO");
			break;
case "home":
			system ("empty.sh HOME");
			break;
case "marker":
			system ("empty.sh MARKER");
			break;
case "power":
			system ("empty.sh POWER");
			break;
case "exit":
			system ("empty.sh EXIT");
			break;
case "sleep":
			system ("empty.sh SLEEP");
			break;
default :
			echo "DEFAULT";
}

?>
