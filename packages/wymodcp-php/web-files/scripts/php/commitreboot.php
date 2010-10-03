<html>
<head><script src="../js/wydev.js" type="text/javascript"></script></head>
<body>

<pre>
<?php

$reboottype=$_REQUEST["reboot"];

		switch ($reboottype) {
			case "rebootwydevice": 
				echo "<td>rebootwydevice</td></tr>";
				system ("reboot");
				break;
			case "rebootplayer": 
				echo "<tr><td>rebootplayer</td>";
				system("ngc -restart wyplayer");
				break;
			case "rebootsplash": 
				echo "<td>rebootsplash</td>";
				system("killall python2.5");
				break;
#			case "shutdownsplash": 
#				echo "<td>shutdownsplash</td>";
#				system("ngzap pygui");
#				system("ngstop pygui");
#				system("killall python2.5");
#				break;
			case "shutdown": 
				echo "<td>rebootshutdown</td>";
				system("/sbin/poweroff");
				break;
			default: echo "default";
				break;
		 }
system ("ngstatus");


?>
</pre>
<a href="javascript:history.back(1)">Volver Atrás</a>



