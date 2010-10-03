
<form id="reboot" method="get" action="./scripts/php/commitreboot.php">
<ul>
	<li>
		<span>
		<input id="rebootwydevice" name="reboot"  type="radio" value="rebootwydevice" />
			Reboot Wydevice
		<input id="rebootsplash" name="reboot"  type="radio" value="rebootsplash" />
			Reboot Splash
		<input id="shutdownsplash" name="reboot"  type="radio" value="shutdownsplash" />
			Shutdown Splash
			<input id="rebootplayer" name="reboot"  type="radio" value="rebootplayer" />
			Restart Player
		<input id="shutdown" name="reboot"  type="radio" value="shutdown" />
			Shutdown Wydevice
		</span>
			
	</li>


	<h2>Reboot Types explanation</h2>

<table><tr><td>
	<ul>
	      <li>Reboot Wydevice it's the same than using the clip. Full Reboot</li>
		  <li>Reboot Splash restarts the python GUI, will free mem and less time than a full reboot. Do not restarts transmission.</li>
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
 system("ngstatus wyplayer"); ?>

</pre>