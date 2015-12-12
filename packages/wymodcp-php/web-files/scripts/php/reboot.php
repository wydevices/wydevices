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
system("ngzap splash");
system("ngstop splash");
system("ngstop wyplayer");
system("ngstop wyscan");
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
<table cellspacing=12>
<tr>
<td>
<input id="rebootwydevice" name="reboot" class="button" type="button" style="width: 200px" value="Reboot Wydevice" <?php echo"onclick=\"Reboot('reboot=rebootwydevice')\"";?> /> </td><td>
Reboot Wydevice: Reboots the device. All processes are stopped and then restarted. Live recordings and ices streams are not restarted </td></tr><tr><td>
<input id="rebootsplash" name="reboot" class="button" type="button" style="width: 200px" value="Reboot Splash (GUI)" <?php echo"onclick=\"Reboot('reboot=rebootsplash')\"";?>/>
</td><td> Reboot Splash (GUI): Restarts the python GUI, will free memory and does not stop wyradio recordings or ices streaming, only Splash (Graphical User Interface) is affected. In addition, it's quicker than a full reboot. Do not restarts transmission </td></tr><tr><td>
<input id="shutdownsplash" name="reboot" class="button" type="button" style="width: 200px" value="Stop Splash (GUI)" <?php echo"onclick=\"Reboot('reboot=shutdownsplash')\"";?>/>
</td><td> Stop Splash (GUI): Stop Splash GUI to save resources. Does not affect transmission, ices, or Wyradio live recordings and it's useful for saving system resources when Graphical User Interface is not used, e.g. kodi gui on raspberry systems... </td></tr><tr><td> 
<input id="startsplash" name="reboot" class="button" type="button" style="width: 200px" value="Start Splash (GUI)" <?php echo"onclick=\"Reboot('reboot=startsplash')\"";?>/>
</td><td> Start Splash (GUI): Starts Splash GUI from a previous stop operation </td></tr><tr><td> 
<input id="rebootplayer" name="reboot" class="button" type="button" style="width: 200px" value="Reset player" <?php echo"onclick=\"Reboot('reboot=rebootplayer')\"";?>/>
</td><td> Reset Player: This method might be useful when GUI player hangs. Player is restarted and reinitialized </td></tr><tr><td>
</td></tr> </table>
</blockquote>


</form> 

<h2> Next Generation Init Status </h2>

Shows the ngstatus for pygui, wyplayer and splash.

<div class="console">
<pre><?php
system ("ngstatus pygui");
system("ngstatus wyplayer");
system("ngstatus system/splash/start");?>
</pre>
</div>
