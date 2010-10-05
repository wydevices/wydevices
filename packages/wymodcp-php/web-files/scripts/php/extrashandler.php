<html>
<head><script src="../js/wydev.js" type="text/javascript"></script></head>
<body>
<h2> Extras Action Result </h2>

<?php

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
<a href="javascript:history.back(1)">Volver Atrás</a>