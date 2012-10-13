<?php
if ($_POST['set_pwd'] == 1 && $_POST['pwd_1'] == "" && $_POST['pwd_2'] == "") {
  //Remove refence to htpasswd file
  exec("sed -i 's/^.*global_passwords_file/#global_passwords_file/' /wymedia/usr/etc/mongoose.conf");
  //Remove password file
  exec("rm -f /wymedia/usr/etc/mongoose_htpasswd");
  //Restart mongoose
  exec("extras restart wymodcp");
  header("refresh:1;url=../../index.php");
  echo "<script type=\"text/javascript\">alert(\"Password blanked, redirect to Home.\")</script>";
  exit;
} elseif ($_POST['set_pwd'] == 1 && !empty($_POST['pwd_1']) && !empty($_POST['pwd_2'])) {
  //Add refence to htpasswd file
  exec("sed -i 's/^.*global_passwords_file/global_passwords_file/' /wymedia/usr/etc/mongoose.conf");
  //Set password into the passwd file
  exec("mongoose -A /wymedia/usr/etc/mongoose_htpasswd WYBOX admin \"".$_POST['pwd_2']."\"");
  //Free password variable :)
  unset($_POST['pwd_1']);
  unset($_POST['pwd_2']);
  //Restart mongoose
  exec("extras restart wymodcp");
  header("refresh:1;url=../../index.php");
  echo "<script type=\"text/javascript\">alert(\"Password changed, redirect to Home.\")</script>";
  exit;
}

if ($_POST['cifs_mount'] == 1 && !empty($_POST['cifs_server']) && !empty($_POST['cifs_share'])) {
  exec("modprobe cifs && mkdir -p /wymedia/My\ Videos/cifs");
  if (!empty($_POST['cifs_username'])) {
    $user_pass=",user=".$_POST['cifs_username'].",pass=".$_POST['cifs_password'];
  } else {
    $user_pass = "";
  }

  exec("mount.cifs //".$_POST['cifs_server']."/".$_POST['cifs_share']." /wymedia/My\ Videos/CIFS-Shares -o rw".$user_pass);
  exec("ln -s /wymedia/My\ Videos/CIFS-Shares /wymedia/My\ Music/CIFS-Shares");
  exec("ln -s /wymedia/My\ Videos/CIFS-Shares /wymedia/My\ Photos/CIFS-Shares");

  header("refresh:1;url=../../index.php");
  echo "<script type=\"text/javascript\">alert(\"Mounting to ".$_POST['cifs_server'].", redirect to Home.\")</script>";
  exit;
}

if ($_POST['cifs_umount'] == 1 & !empty($_POST['cifs_mountfolder'])) {
  exec("umount ".$_POST['cifs_mountfolder']);
  exec("rm -f /wymedia/My\ Music/CIFS-Shares");
  exec("rm -f /wymedia/My\ Photos/CIFS-Shares");

  header("refresh:1;url=../../index.php");
  echo "<script type=\"text/javascript\">alert(\"Umount ".$_POST['cifs_mountfolder'].", redirect to Home.\")</script>";
  exit;
}

if (!empty($_GET['wyclim_temp']) && $_GET['wyclim_temp'] >= 40 && $_GET['wyclim_temp'] <= 50) {
  //Get current temp target
  $actual_temp = exec("cat /etc/wyclim/pid.conf | grep maxtemp | cut -b 9-10");
  //Backup wyclim config file
  exec("cp /etc/wyclim/pid.conf /etc/wyclim/pid.conf.bak");
  //Create the new wyclim config file
  exec("sed -i '/maxtemp/ {s/".$actual_temp."000/".$_GET['wyclim_temp']."000/g}' /etc/wyclim/pid.conf");
  //Restart wyclim daemon
  exec("ngrestart wyclimd");
  header("refresh:1;url=../../index.php");
  echo "<script type=\"text/javascript\">alert(\"Target temperature set to ".$_GET['wyclim_temp'].", redirect to Home.\")</script>";
  unset($_GET['wyclim_temp']);
  exit;
}

include("func.extras.php");
?>
<h1>Configuration</h1><br />

<h2>Extras Management</h2>
<form id="extrasform" method="get" action="./scripts/php/config.php">
	<div class="extrasclass"><p>Update extras configuration</p>
		<table>
      <tr><th align="left">Process</th><th>AutoStart</th><th>Status</th><th>Enable</th><th>Disable</th><th>Start</th><th>Stop</th></tr>
      <tr><td colspan="7"><hr width="100%" /></td></tr>
<?php
$initdfolder = '/wymedia/usr/etc/init.d/';
if ($handle = opendir($initdfolder)) {
  while (false !== ($file = readdir($handle))) {
    if ($file != "." && $file != "..") {
      $readstatus = exec($initdfolder.$file." status");
      $extraname  = split(" ",$readstatus);

      if (trim($extraname[1]) == "not") { $status = false; } else { $status = true; }
      if (file_exists("/wymedia/usr/etc/rc.d/".$file)) { $enable = true; } else { $enable = false; }

      echo "<tr><td><b>".$extraname[0]."</b></td>";

      if ($enable == true) {
        echo "<td align=\"center\"><img src=./style/available.png onclick=\"javascript:ExtrasHandler('autostart".$extraname[0]."=false')\" /></td>";
      } else {
        echo "<td align=\"center\"><img src=./style/process-stop.png onclick=\"javascript:ExtrasHandler('autostart".$extraname[0]."=true')\" /></td>";
      }
      if ($status == true) {
        echo "<td><div style=\"color:#0A0\"><b>running</b></div></td>";
      } else {
        echo "<td><div style=\"color:#F00\"><b>stopped</b></div></td>";
      }
      ?>
      <td><input id="<?php echo $extraname[0];?>" name="autostart<?php echo $extraname[0];?>" type="radio" class="extraenable" value="true"<?php echo"onclick=\"javascript:ExtrasHandler('autostart".$extraname[0]."=true')\"";?> />Enable</td>
      <td><input id="<?php echo $extraname[0];?>" name="autostart<?php echo $extraname[0];?>" type="radio" class="extraenable" value="false"<?php echo"onclick=\"javascript:ExtrasHandler('autostart".$extraname[0]."=false')\"";?> />Disable</td>
      <td><input id="<?php echo $extraname[0];?>" name="activate<?php echo $extraname[0];?>" type="radio" class="extraenable" value="true"<?php echo"onclick=\"javascript:ExtrasHandler('activate".$extraname[0]."=true')\"";?>/>Start</td>
      <td><input id="<?php echo $extraname[0];?>" name="activate<?php echo $extraname[0];?>" type="radio" class="extraenable" value="false"<?php echo"onclick=\"javascript:ExtrasHandler('activate".$extraname[0]."=false')\"";?> />Stop</td>	
      <?php
    }
  }
  closedir($handle);
}
/*
$readstatuswyremote = exec($initdfolder."wyremote status");
$extranamewyremote  = split(" ",$readstatuswyremote);
if (trim($extranamewyremote[1]) == "not") { $statuswyremote = false; } else { $statuswyremote = true; }
if ($statuswyremote == true) {
    echo "<tr><td></td></tr>";
    echo "<tr><td><b><a href=./wyremote.php target=_new>Access to wyremote</a></b></td></tr>";
}
*/
?>
    </table>
</form>	
<br />
<h2>Extras Usage</h2>
  <p>You can select up to 4 actions to apply to extras services</p>
<h2>Actions</h2>
<ul>
  <li><b>Enable:</b>Extra will be automatically started after reboot</li>
  <li><b>Start:</b>Extra will start now</li>
  <li><b>Stop:</b>Extra will stop now</li>
  <li><b>Disable:</b>Extra will not be automatically started after reboot</li>
</ul>
<br />

<form name="chgpassword" id="chgpassword" method="post" action="./scripts/php/config.php">
  <table>
  <tr><td></td><td align="left"><h1>Change web access password</h1></td></tr>
  <tr><td></td>
    <td align="left" colspan="2">
      <i>Leave blank for unset password<br />
      </i><br /><br />
    </td>
  </tr>
  <tr><td></td><td align="left">New password :</td><td align="left"><input type="password" name="pwd_1" /></td></tr>
  <tr><td></td><td align="left">Confirm password :</td><td align="left"><input type="password" name="pwd_2" /></td></tr>
  <tr><td></td><td align="right" colspan="2"><input type="hidden" name="set_pwd" value="1" /><input type="submit" class="button" style="width: 100px"/></td></tr>
  </table>
</form>
<br /><hr width="100%" />

<form name="cifs" id="cifs" method="post" action="./scripts/php/config.php">
  <table>
  <tr><td></td><td align="left"><h1>CIFS mount point</h1></td></tr>
  <tr><td></td>
    <td align="left" colspan="2">
      <i>Leave user/password blank if not required.</i><br /><br />
      <?php 
        $mounted_cifs = exec("mount | grep cifs");
        if (!empty($mounted_cifs)) {
          $tab_mounted_cifs = explode(" ", $mounted_cifs);
          echo "<b>".$tab_mounted_cifs[0]."</b> -> <b>".$tab_mounted_cifs[2]." ".$tab_mounted_cifs[3]."</b>";
          ?>
          <input type="hidden" name="cifs_umount" value="1" />
          <input type="hidden" name="cifs_mountfolder" value=<?php echo "\"".$tab_mounted_cifs[2]."\ ".$tab_mounted_cifs[3]."\""; ?> />
          &nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="umount" style="width: 100px"/><?php
        }
      ?>
      <br /><br />
    </td>
  </tr>
  <?php if (empty($mounted_cifs)) {?>
  <tr><td></td><td align="left">CIFS server :</td><td align="left"><input type="inputbox" name="cifs_server" /></td></tr>
  <tr><td></td><td align="left">Share name :</td><td align="left"><input type="inputbox" name="cifs_share" /></td></tr>
  <tr><td></td><td align="left">Username :</td><td align="left"><input type="inputbox" name="cifs_username" /></td></tr>
  <tr><td></td><td align="left">Password :</td><td align="left"><input type="password" name="cifs_password" /></td></tr>
  <!--<tr><td></td><td align="left">Permanent mount (Add entry to fstab)</td><td align="left"><input type="checkbox" name="cifs_fstab" /><i></i></td></tr>-->
  <tr><td></td><td align="right" colspan="2"><input type="hidden" name="cifs_mount" value="1" /><input type="submit" class="button" value="Mount" style="width: 100px"/></td></tr>
  <?php } ?>
  </table>
</form>
<br /><hr width="100%" />

<form id="chgwyclim" method="get" action="./scripts/php/config.php">
  <table>
  <tr><td></td><td align="left" colspan="2"><h1>Change CPU target temperature</h1></td></tr>
  <tr><td></td>
    <td align="left" colspan="2">
      <i>By default CPU temperature are target to 50 °C.<br />
      If you decrease this value, the fan rotate more speed and more noisy but the stability of wydevice seem to be better.<br /><br />
      <b><div style="color:#F00">When changing this parameter, the fan are disabled during few second (because wyclimd is restarted).</div></b></i><br /><br />
    </td>
  </tr>
  <tr>
      <td></td>
      <td align="left">CPU target temperature :</td>
      <td align="left"><?php
        system("cat /etc/wyclim/pid.conf | grep maxtemp | cut -b 9-10");
        echo " °C";?>
      </td>
  </tr>
  <tr>
      <td></td>
      <td align="left">Current CPU temperature :</td>
      <td align="left"><?php
        $temperature = exec("cat /sys/bus/i2c/devices/0-0048/temp1_input | cut -b 1-2");
        echo $temperature." °C";?>
      </td>
  </tr>
  <tr>
    <td></td>
    <td align="left">New CPU target temperature :</td>
    <td align="left">
      <select name="wyclim_temp">
        <option value="50" selected>50 (default)</option>
        <option value="49">49</option>
        <option value="48">48</option>
        <option value="47">47</option>
        <option value="46">46</option>
        <option value="45">45</option>
      </select>
    </td>
  </tr>
  <tr><td></td><td align="right" colspan="2"><input type="submit" class="button" style="width: 100px"/></td></tr>
  </table>
</form>
<br /><hr width="100%" />
