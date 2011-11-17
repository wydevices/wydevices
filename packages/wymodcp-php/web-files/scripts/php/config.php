<?php
if ($_POST['set_pwd'] == 1 && $_POST['pwd_1'] == "" && $_POST['pwd_2'] == "") {
  //Remove refence to htpasswd file
  exec("sed -i 's/global_passwords_file/#global_passwords_file/g' /wymedia/usr/etc/mongoose.conf");
  //Remove password file
  exec("rm -f /wymedia/usr/etc/mongoose_htpasswd");
  //Restart mongoose
  exec("extras restart wymodcp");
  header("refresh:1;url=../../index.php");
  echo "<script type=\"text/javascript\">alert(\"Password blanked, redirect to Home.\")</script>";
  exit;
} elseif ($_POST['set_pwd'] == 1 && !empty($_POST['pwd_1']) && !empty($_POST['pwd_2'])) {
  //Add refence to htpasswd file
  exec("sed -i 's/#global_passwords_file/global_passwords_file/g' /wymedia/usr/etc/mongoose.conf");
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

if (!empty($_GET['wyclim_temp']) && $_GET['wyclim_temp'] >= 40 && $_GET['wyclim_temp'] <= 50) {
  //Get current temp target
  $actual_temp = exec("cat /etc/wyclim/pid.conf | grep maxtemp | cut -b 9-10");
  //Backup wyclim config file
  exec("cp /etc/wyclim/pid.conf /etc/wyclim/pid.conf.bak");
  //Create the new wyclim config file
  exec("sed -i '/maxtemp/ {s/".$actual_temp."000/".$_GET['wyclim_temp']."000/g}' /etc/wyclim/pid.conf");
  //Restart wyclim daemon
  exec("ngrestart wyclimd");
  header("refresh:3;url=../../index.php");
  echo "<script type=\"text/javascript\">alert(\"Target temperature set to ".$_GET['wyclim_temp'].", redirect to Home.\")</script>";
  unset($_GET['wyclim_temp']);
  exit;
}
?>
<h1>Configuration</h1><br />

<form name="chgpassword" id="chgpassword" method="post" action="./scripts/php/config.php" onSubmit="return checkPass();">
  <table>
  <tr><td></td><td align="left"><h2>Change web access password</h2></td></tr>
  <tr><td></td>
    <td align="left" colspan="2">
      <i>Leave blank for unset password</i><br /><br />
    </td>
  </tr>
  <tr><td></td><td align="left">New password :</td><td align="left"><input type="password" name="pwd_1" /></td></tr>
  <tr><td></td><td align="left">Confirm password :</td><td align="left"><input type="password" name="pwd_2" /></td></tr>
  <tr><td></td><td align="right" colspan="2"><input type="hidden" name="set_pwd" value="1" /><input type="submit" /></td></tr>
  </table>
</form>
<br /><hr width="100%" />

<form id="chgwyclim" method="get" action="./scripts/php/config.php">
  <table>
  <tr><td></td><td align="left" colspan="2"><h2>Change CPU target temperature</h2></td></tr>
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
  <tr><td></td><td align="right" colspan="2"><input type="submit" /></td></tr>
  </table>
</form>
<br /><hr width="100%" />
