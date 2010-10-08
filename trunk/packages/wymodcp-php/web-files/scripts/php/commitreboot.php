<html>
<head><script src="../js/wydev.js" type="text/javascript"></script></head>
<body>
<h2> Reboot Results</h2>
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
			case "shutdownsplash": 
				echo "<td>shutdownsplash</td>";
				system("mv /usr/bin/splash.py /usr/bin/unsplash.py");
				system("killall python2.5");
				system("sleep 3");
				system("mv /usr/bin/unsplash.py /usr/bin/splash.py");
				break;
			case "startsplash": 
				echo "<td>starting</td>";
				system("ngc -z system/splash/start");
				system("ngstart system/splash/start");
				break;
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



