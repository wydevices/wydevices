<h2> Reboot Options </h2>

<?php

$reboottype=$_REQUEST["reboot"];

		switch ($reboottype) {
			case "rebootwydevice": 
				//echo "<td>rebootwydevice</td></tr>";
				system ("reboot");
				break;
			case "rebootplayer": 
				//echo "<tr><td>rebootplayer</td>";
				system("ngc -restart wyplayer");
				break;
			case "rebootsplash": 
				//echo "<td>rebootsplash</td>";
				system("killall python2.5");
				break;
			case "shutdownsplash": 
				//echo "<td>shutdownsplash</td>";
				system("mv /usr/bin/splash.py /usr/bin/unsplash.py");
				system("killall python2.5");
				system("sleep 3");
				system("mv /usr/bin/unsplash.py /usr/bin/splash.py");
				break;
			case "startsplash": 
				//echo "<td>starting</td>";
				system("ngc -z system/splash/start");
				system("ngstart system/splash/start");
				break;
			case "shutdown": 
				//echo "<td>rebootshutdown</td>";
				system("/sbin/poweroff");
				break;
			default: // echo "default";
				break;
		 }
?>


<form id="reboot" method="get" action="./scripts/php/reboot.php">
<blockquote>
<table><tr><td>

		<input id="rebootwydevice" name="reboot"  type="radio" value="rebootwydevice" <?php echo"onclick=\"Reboot('reboot=rebootwydevice')\"";?> />
			Reboot Wydevice </td></tr><tr><td>
		<input id="rebootsplash" name="reboot"  type="radio" value="rebootsplash" <?php echo"onclick=\"Reboot('reboot=rebootsplash')\"";?>/>
			Reboot Splash </td></tr><tr><td>
		<input id="shutdownsplash" name="reboot"  type="radio" value="shutdownsplash" <?php echo"onclick=\"Reboot('reboot=shutdownsplash')\"";?>/>
			Shutdown Splash </td></tr><tr><td> 
		<input id="startsplash" name="reboot"  type="radio" value="startsplash" <?php echo"onclick=\"Reboot('reboot=startsplash')\"";?>/>
			Start Splash </td></tr><tr><td> 
		<input id="rebootplayer" name="reboot"  type="radio" value="rebootplayer" <?php echo"onclick=\"Reboot('reboot=rebootplayer')\"";?>/>
			Restart Player </td></tr><tr><td>
		<input id="shutdown" name="reboot"  type="radio" value="shutdown" <?php echo"onclick=\"Reboot('reboot=shutdown')\"";?>/>
			Shutdown Wydevice </td></tr><tr><td>

</td>
</tr>
</table>
</blockquote>
	<h2>Reboot Types explanation</h2>

<table><tr><td>
	<ul>
	      <li>Reboot Wydevice it's the same than using the clip. Full Reboot</li>
		  <li>Reboot Splash restarts the python GUI, will free mem and less time than a full reboot. Do not restarts transmission.</li>
          <li>Shutdown Splash: stop GUI to save resources</li>
		  <li>Start Splash: resume GUI from a previous shutdown</li>
		  <li>Reboot Player is ok when the player is hung after a crash.</li>
		  <li>Shutdown Wydevice turns off device and HDD. Good for nights of before planning a trip, for example</li>
	</ul>
	<br />
	</td><td>
	<input type="submit"/>
	</tr>
	</ul>
	</tr>
	</table>
</form>	        

<div class="console">
<pre>
 <?php 
 
 system ("ngstatus pygui");
 system("ngstatus wyplayer");
 system("ngstatus system/splash/start");
 ?>
</pre>
</div>
