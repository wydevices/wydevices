<h2> Reboot Options </h2>
<form id="reboot" method="get" action="./scripts/php/commitreboot.php">
<blockquote>
<table><tr><td>

		<input id="rebootwydevice" name="reboot"  type="radio" value="rebootwydevice" />
			Reboot Wydevice </td></tr><tr><td>
		<input id="rebootsplash" name="reboot"  type="radio" value="rebootsplash" />
			Reboot Splash </td></tr><tr><td>
		<input id="shutdownsplash" name="reboot"  type="radio" value="shutdownsplash" />
			Shutdown Splash </td></tr><tr><td> 
		<input id="startsplash" name="reboot"  type="radio" value="startsplash" />
			Start Splash </td></tr><tr><td> 
		<input id="rebootplayer" name="reboot"  type="radio" value="rebootplayer" />
			Restart Player </td></tr><tr><td>
		<input id="shutdown" name="reboot"  type="radio" value="shutdown" />
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
 <pre>
 <?php 
 
 system ("ngstatus pygui");
 system("ngstatus wyplayer");
 system("ngstatus system/splash/start");
 ?>

</pre>
