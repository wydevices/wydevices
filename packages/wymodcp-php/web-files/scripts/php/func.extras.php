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
$activatewyremote=$_REQUEST["activatewyremote"];
$autostartwyremote=$_REQUEST["autostartwyremote"];
$activatecifsclient=$_REQUEST["activatecifs-client"];
$autostartcifsclient=$_REQUEST["autostartcifs-client"];

// ################ syslogd ############################
if ($autostartsyslogd || $activatesyslogd){
  echo "<h3><b>syslogd</b></h3>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartsyslogd == "") {
    echo "NULL";
  } else {
    if ($autostartsyslogd == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/syslogd /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/syslogd");
    }
  }

  echo "</td><td>";
  if ($activatesyslogd == "") { 
    echo "NULL";
  } else {
    if ($activatesyslogd == "true") {
      system ("/wymedia/usr/etc/init.d/syslogd start");
    } else {
      system ("/wymedia/usr/etc/init.d/syslogd stop");
    }
  }
  echo "</td></tr></table>";
}
// ################ syslogd #############################
// ################ crond ###############################
if ($autostartcrond || $activatecrond) {
  echo "<h3><b> crond </b> </h3>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartcrond == "") { 
    echo "NULL";
  } else {
    if ($autostartcrond == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/crond /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/crond");
    }
  }

  echo "</td><td>";

  if ($activatecrond == "") { 
    echo "NULL";
  } else {
    if ($activatecrond == "true") {
      system ("/wymedia/usr/etc/init.d/crond start");
    } else {
      system ("/wymedia/usr/etc/init.d/crond stop");
    }
  }
  echo "</td></tr></table>";	
}
// ################ crond ###############################
// ################ Transmission ###############################
if ($autostarttransmission || $activatetransmission){
  echo "<h3><b> TRANSMISSION </b> </h3>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostarttransmission == "") { 
    echo "NULL";
  } else {
    if ($autostarttransmission == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/transmission /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/transmission");
    }
  }

  echo "</td><td>";

  if ($activatetransmission == "") { 
    echo "NULL";
  } else {
    if ($activatetransmission == "true") {
      system ("/wymedia/usr/etc/init.d/transmission start");
    } else {
      system ("/wymedia/usr/etc/init.d/transmission stop");
    }
  }
  echo "</td></tr></table>";	
}
// ################ Transmission ###############################
// ################ wymodcp ###############################
if ($autostartwymodcp || $activatewymodcp){
  echo "<h4><b> WYMOD CONTROL PANEL </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartwymodcp == "") { 
    echo "NULL";
  } else {
    if ($autostartwymodcp == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/wymodcp /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/wymodcp");
    }
  }

  echo "</td><td>";

  if ($activatewymodcp == "") { 
    echo "NULL";
  } else {
    if ($activatewymodcp == "true") {
      system ("/wymedia/usr/etc/init.d/wymodcp start");
    } else {
      system ("/wymedia/usr/etc/init.d/wymodcp stop");
    }
  }
  echo "</td></tr></table>";	
}
// ################ wymodcp ###############################
// ################ wyremote ###############################
if ($autostartwyremote || $activatewyremote){
  echo "<h4><b> WYREMOTE </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartwyremote == "") { 
    echo "NULL";
  } else {
    if ($autostartwyremote == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/wyremote /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/wyremote");
    }
  }

  echo "</td><td>";

  if ($activatewyremote == "") { 
    echo "NULL";
  } else {
    if ($activatewyremote == "true") {
      system ("/wymedia/usr/etc/init.d/wyremote start");
    } else {
      system ("/wymedia/usr/etc/init.d/wyremote stop");
    }
  }
  echo "</td></tr></table>";	
}
// ################ wyremote ###############################
// ################ pureftpd ###############################
if ($autostartpureftpd || $activatepureftpd){
  echo "<h4><b> PURE-FTPD </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartpureftpd == "") { 
    echo "NULL";
  } else {
    if ($autostartpureftpd == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/pure-ftpd /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/pure-ftpd");
    }
  }

  echo "</td><td>";

  if ($activatepureftpd == "") { 
    echo "NULL";
  } else {
    if ($activatepureftpd == "true") {
      system ("/wymedia/usr/etc/init.d/pure-ftpd start");
    } else {
      system ("/wymedia/usr/etc/init.d/pure-ftpd stop");
    }
  }
  echo "</td></tr></table>";	
}
// ################ pureftpd ###############################
// ################ sambaserver ###############################
if ($autostartsambaserver || $activatesambaserver){
  echo "<h4><b> SAMBA SERVER </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartsambaserver == "") { 
    echo "NULL";
  } else {
    if ($autostartsambaserver == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/samba-server /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/samba-server");
    }
  }

  echo "</td><td>";

  if ($activatesambaserver == "") { 
    echo "NULL";
  } else {
    if ($activatesambaserver == "true") {
      system ("/wymedia/usr/etc/init.d/samba-server start");
    } else {
      system ("/wymedia/usr/etc/init.d/samba-server stop");
    }
  }
  echo "</td></tr></table>";
}
// ################ sambaserver ###############################
// ################ cifsclient ###############################
if ($autostartcifsclient || $activatecifsclient){
  echo "<h4><b> CIFS Client </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartcifsclient == "") {
    echo "NULL";
  } else {
    if ($autostartcifsclient == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/cifs-client /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/cifs-client");
    }
  }

  echo "</td><td>";

  if ($activatecifsclient == "") {
    echo "NULL";
  } else {
    if ($activatecifsclient == "true") {
      system ("/wymedia/usr/etc/init.d/cifs-client start");
    } else {
      system ("/wymedia/usr/etc/init.d/cifs-client stop");
    }
  }
  echo "</td></tr></table>";
}
// ################ cifsclient ###############################
// ################ sambaclient ###############################
if ($autostartsambaclient || $activatesambaclient){
  echo "<h4><b> SAMBA CLIENT </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartsambaclient == "") { 
    echo "NULL";
  } else {
    if ($autostartsambaclient == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/samba-client /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/samba-client");
    }
  }

  echo "</td><td>";

  if ($activatesambaclient == "") { 
    echo "NULL";
  } else {
    if ($activatesambaclient == "true") {
      system ("/wymedia/usr/etc/init.d/samba-client start");
    } else {
      system ("/wymedia/usr/etc/init.d/samba-client stop");
    }
  }
  echo "</td></tr></table>";
}
// ################ sambaclient ###############################
// ################ mediatomb ###############################
if ($autostartmediatomb || $activatemediatomb){
  echo "<h4><b> MEDIATOMB </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartmediatomb == "") { 
    echo "NULL";
  } else {
    if ($autostartmediatomb == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/mediatomb /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/mediatomb");
    }
  }

  echo "</td><td>";

  if ($activatemediatomb == "") { 
    echo "NULL";
  } else {
    if ($activatemediatomb == "true") {
      system ("/wymedia/usr/etc/init.d/mediatomb start");
    } else {
      system ("/wymedia/usr/etc/init.d/mediatomb stop");
    }
  }
  echo "</td></tr></table>";
}
// ################ mediatomb ###############################
// ################ rsync ###############################
if ($autostartrsync || $activatersync){
  echo "<h4><b> RSYNC </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartrsync == "") { 
    echo "NULL";
  } else {
    if ($autostartrsync == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/rsync /wymedia/usr/etc/rc.d/");
    } else {
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/rsync");
    }
  }

  echo "</td><td>";

  if ($activatersync == "") { 
    echo "NULL";
  } else {
    if ($activatersync == "true") {
      system ("/wymedia/usr/etc/init.d/rsync start");
    } else {
      system ("/wymedia/usr/etc/init.d/rsync stop");
    }
  }
  echo "</td></tr></table>";
}
// ################ rsync ###############################
// ################ inadyn ###############################
if ($autostartinadyn || $activateinadyn){
  echo "<h4><b> INADYN </b> </h4>";
  echo "<table border=1><tr><td>AutoStart</td><td>Action</td></tr><tr>";
  echo "<td>";

  if ($autostartinadyn == "") { 
    echo "NULL";
  } else {
    if ($autostartinadyn == "true") {
      echo ("Enabled!");
      system ("ln -sf ../init.d/inadyn /wymedia/usr/etc/rc.d/");
    } else{
      echo ("Disabled!");
      system ("rm  /wymedia/usr/etc/rc.d/inadyn");
    }
  }

  echo "</td><td>";

  if ($activateinadyn == "") { 
    echo "NULL";
  } else {
    if ($activateinadyn == "true") {
      system ("/wymedia/usr/etc/init.d/inadyn start");
    } else {
      system ("/wymedia/usr/etc/init.d/inadyn stop");
    }
  }
  echo "</td></tr></table>";	
}
// ################ inadyn ###############################
?>
