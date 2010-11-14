<?php
header("Cache-Control: no-cache");

$activateinadyn=$_REQUEST["activateinadyn"];
$activatemediatomb=$_REQUEST["activatemediatomb"];
$activatepureftpd=$_REQUEST["activatepure-ftpd"];
$activatersync=$_REQUEST["activatersync"];
$activatesambaclient=$_REQUEST["activatesamba-client"];
$activatesambaserver=$_REQUEST["activatesamba-server"];
$activatetransmission=$_REQUEST["activatetransmission"];
$activatewymodcp=$_REQUEST["activatewymodcp"];
$autostartinadyn=$_REQUEST["autostartinadyn"];
$autostartmediatomb=$_REQUEST["autostartmediatomb"];
$autostartpureftpd=$_REQUEST["autostartpure-ftpd"];
$autostartrsync=$_REQUEST["autostartrsync"];
$autostartsambaclient=$_REQUEST["autostartsamba-client"];
$autostartsambaserver=$_REQUEST["autostartsamba-server"];
$autostarttransmission=$_REQUEST["autostarttransmission"];
$autostartwymodcp=$_REQUEST["autostartwymodcp"];

$activatecrond=$_REQUEST["activatecrond"];
$autostartcrond=$_REQUEST["autostartcrond"];

$activatesyslogd=$_REQUEST["activatesyslogd"];
$autostartsyslogd=$_REQUEST["autostartsyslogd"];



// ################ syslogd ###############################

if ($autostartsyslogd || $activatesyslogd){
echo "<h3><strong> syslogd </strong> </h3>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartsyslogd == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartsyslogd == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/syslogd /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/syslogd");
	}
}

echo "</td><td>";

if ($activatesyslogd == "") { 
	echo "NULL";
	}
else
	{
	if ($activatesyslogd == "true") {
		system ("/wymedia/usr/etc/init.d/syslogd start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/syslogd stop");
	}
}
echo "</td></tr></table>";	

}

// ################ syslogd ###############################
// ################ crond ###############################

if ($autostartcrond || $activatecrond){
echo "<h3><strong> crond </strong> </h3>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartcrond == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartcrond == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/crond /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/crond");
	}
}

echo "</td><td>";

if ($activatecrond == "") { 
	echo "NULL";
	}
else
	{
	if ($activatecrond == "true") {
		system ("/wymedia/usr/etc/init.d/crond start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/crond stop");
	}
}
echo "</td></tr></table>";	

}

// ################ crond ###############################
// ################ Transmission ###############################

if ($autostarttransmission || $activatetransmission){
echo "<h3><strong> TRANSMISSION </strong> </h3>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostarttransmission == "") { 
	echo "NULL";
	}
else
	{
	if ($autostarttransmission == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/transmission /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/transmission");
	}
}

echo "</td><td>";

if ($activatetransmission == "") { 
	echo "NULL";
	}
else
	{
	if ($activatetransmission == "true") {
		system ("/wymedia/usr/etc/init.d/transmission start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/transmission stop");
	}
}
echo "</td></tr></table>";	

}

// ################ Transmission ###############################
// ################ wymodcp ###############################

if ($autostartwymodcp || $activatewymodcp){
echo "<h4><strong> WYMOD CONTROL PANEL </strong> </h4>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartwymodcp == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartwymodcp == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/wymodcp /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/wymodcp");
	}
}

echo "</td><td>";

if ($activatewymodcp == "") { 
	echo "NULL";
	}
else
	{
	if ($activatewymodcp == "true") {
		system ("/wymedia/usr/etc/init.d/wymodcp start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/wymodcp stop");
	}
}
echo "</td></tr></table>";	


}
// ################ wymodcp ###############################
// ################ pureftpd ###############################
if ($autostartpureftpd || $activatepureftpd){
echo "<h4><strong> PURE-FTPD </strong> </h4>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartpureftpd == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartpureftpd == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/pure-ftpd /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/pure-ftpd");
	}
}

echo "</td><td>";

if ($activatepureftpd == "") { 
	echo "NULL";
	}
else
	{
	if ($activatepureftpd == "true") {
		system ("/wymedia/usr/etc/init.d/pure-ftpd start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/pure-ftpd stop");
	}
}
echo "</td></tr></table>";	

}

// ################ pureftpd ###############################
// ################ sambaserver ###############################
if ($autostartsambaserver || $activatesambaserver){
echo "<h4><strong> SAMBA SERVER </strong> </h4>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartsambaserver == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartsambaserver == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/samba-server /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/samba-server");
	}
}

echo "</td><td>";

if ($activatesambaserver == "") { 
	echo "NULL";
	}
else
	{
	if ($activatesambaserver == "true") {
		system ("/wymedia/usr/etc/init.d/samba-server start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/samba-server stop");
	}
}
echo "</td></tr></table>";	


}
// ################ sambaserver ###############################
// ################ sambaclient ###############################
if ($autostartsambaclient || $activatesambaclient){
echo "<h4><strong> SAMBA CLIENT </strong> </h4>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartsambaclient == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartsambaclient == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/samba-client /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/samba-client");
	}
}

echo "</td><td>";

if ($activatesambaclient == "") { 
	echo "NULL";
	}
else
	{
	if ($activatesambaclient == "true") {
		system ("/wymedia/usr/etc/init.d/samba-client start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/samba-client stop");
	}
}
echo "</td></tr></table>";	


}
// ################ sambaclient ###############################
// ################ mediatomb ###############################
if ($autostartmediatomb || $activatemediatomb){
echo "<h4><strong> MEDIATOMB </strong> </h4>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartmediatomb == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartmediatomb == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/mediatomb /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/mediatomb");
	}
}

echo "</td><td>";

if ($activatemediatomb == "") { 
	echo "NULL";
	}
else
	{
	if ($activatemediatomb == "true") {
		system ("/wymedia/usr/etc/init.d/mediatomb start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/mediatomb stop");
	}
}
echo "</td></tr></table>";	

}
// ################ mediatomb ###############################
// ################ rsync ###############################
if ($autostartrsync || $activatersync){
echo "<h4><strong> RSYNC </strong> </h4>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartrsync == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartrsync == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/rsync /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/rsync");
	}
}

echo "</td><td>";

if ($activatersync == "") { 
	echo "NULL";
	}
else
	{
	if ($activatersync == "true") {
		system ("/wymedia/usr/etc/init.d/rsync start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/rsync stop");
	}
}
echo "</td></tr></table>";	

}

// ################ rsync ###############################
// ################ inadyn ###############################
if ($autostartinadyn || $activateinadyn){
echo "<h4><strong> INADYN </strong> </h4>";
echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
echo "<td>";

if ($autostartinadyn == "") { 
	echo "NULL";
	}
else
	{
	if ($autostartinadyn == "true") {
		echo ("Enabled!");
		system ("ln -sf ../init.d/inadyn /wymedia/usr/etc/rc.d/");
		}
	else{
		echo ("Disabled!");
		system ("rm  /wymedia/usr/etc/rc.d/inadyn");
	}
}

echo "</td><td>";

if ($activateinadyn == "") { 
	echo "NULL";
	}
else
	{
	if ($activateinadyn == "true") {
		system ("/wymedia/usr/etc/init.d/inadyn start");
		}
	else{
		system ("/wymedia/usr/etc/init.d/inadyn stop");
	}
}
echo "</td></tr></table>";	


}
// ################ inadyn ###############################



?>
<h2> Extras Management </h2>
<form id="extrasform" method="get" action="./scripts/php/extras.php">
	<div class="extrasclass"><p>Update extras configuration</p>
		<table>
	<tr><th>Actual Status</th><th>AutoStart</th><th>Enable</th><th>Disable</th><th>Start</th><th>Stop</th></tr>
<?php


$initdfolder = '/wymedia/usr/etc/init.d/';

if ($handle = opendir($initdfolder)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {

			echo "<tr><td><strong>";
                        $readstatus = system($initdfolder.$file." status");
                        $extraname = split(" ",$readstatus);
						$readenable = system("ls /wymedia/usr/etc/rc.d/".$file." 2>/dev/null >/dev/null", $retval);
			echo "</strong></td>";

                        if ($extraname[1] == "not"){
                                $status = false;
                                }
                        else
                                {
                                $status = true;
                                }

                        if ($retval == 1){
                                $enable = false;
                                }
                        else
                                {
                                $enable = true;
                        	}

			?>
			
			<td><?php 
			
			if ($enable == true) 
			{
			echo "<img src=./style/play.png onclick=\"ExtrasHandler('autostart".$extraname[0]."=false')\"></img>";
			} 
			else 
			{
			echo "<img src=./style/cross.png onclick=\"ExtrasHandler('autostart".$extraname[0]."=true')\"></img>";
			} ?>
			</td>
			
			<td><input id="<?php echo $extraname[0];?>" name="autostart<?php echo $extraname[0];?>" type="radio" class="extraenable" value="true"<?php echo"onclick=\"ExtrasHandler('autostart".$extraname[0]."=true')\"";?> />Enable</td>
			<td><input id="<?php echo $extraname[0];?>" name="autostart<?php echo $extraname[0];?>" type="radio" class="extraenable" value="false"<?php echo"onclick=\"ExtrasHandler('autostart".$extraname[0]."=false')\"";?> />Disable</td>
			
			<td><input id="<?php echo $extraname[0];?>" name="activate<?php echo $extraname[0];?>" type="radio" class="extraenable" value="true"<?php echo"onclick=\"ExtrasHandler('activate".$extraname[0]."=true')\"";?>/>Start</td>
			<td><input id="<?php echo $extraname[0];?>" name="activate<?php echo $extraname[0];?>" type="radio" class="extraenable" value="false"<?php echo"onclick=\"ExtrasHandler('activate".$extraname[0]."=false')\"";?> />Stop</td>	
			
			<?php
			
			}
    }
    closedir($handle);
}
	?>

					</table>
				</form>	

        <h1>Extras Usage</h1>
        <blockquote>
          <p>
            You can select up to 4 actions to apply to extras services
          </p>
        </blockquote>
        <h2>Actions</h2>
        <ul>
          <li><strong>Enable:</strong> Extra will be automatically started after reboot</li>
          <li><strong>Start:</strong> Extra will start now</li>
          <li><strong>Stop:</strong> Extra will stop now</li>
          <li><strong>Disable:</strong> Extra will not be automatically started after reboot</li>
        </ul>
        <br />
	</div>	
