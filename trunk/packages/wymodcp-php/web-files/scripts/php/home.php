<h1>Home</h1><br />
<h3>Console</h3>
<div id="consoledivid" name="consoledivname" style="display:none;">
  <pre><iframe height="490" width="930" src="./scripts/php/shell.php"></iframe></pre>
</div>
<input type="button" onClick="$('#consoledivid').slideToggle();" class="wydevslidebutton"/></input>

<h3>Data</h3>
<div id="datadivid" name="datadivname">
  <table> 
    <tr><td>Manufacturer: </td><td><b><?php system("cat /proc/wybox/MN")?></td></tr>
    <tr><td>WydevFirm Version: </td><td><b><?php system("cat /wymedia/etc/wydev-mod-version")?></td></tr>
    <tr><td>Bubble Update Version: </td><td><b><?php  system("cat /wymedia/usr/etc/wydev-mod-updaterelease"); ?></td></tr>
    <tr><td>Modded Target: </td><td><?php system("cat /proc/wybox/WC");?></td></tr>
    <tr><td>Real Target: </td><td> <?php system("strings /dev/mtd2 |grep WC |cut -d= -f2");?> </td></tr>
    <tr><td>Serial Number: </td><td> <?php system("cat /proc/wybox/SN");?> </td></tr>
    <tr><td>Time / Uptime: </td><td> <?php system("uptime |cut -f1 -d,");?> </td></tr>
    <tr><td>Memory: </td><td> <?php 
      $mem[0] = trim(exec("free |grep Mem |cut -c 15-19"));
      $mem[1] = trim(exec("free |grep Mem |cut -c 28-32"));
      $mem_percent = round((intval($mem[1]) / intval($mem[0])) * 100, 0);
      $mem_color = "#3F3";
      if ($mem_percent > 90) $mem_color = "orange";
      if ($mem_percent > 95) $mem_color = "#F33";
      echo "<b>".$mem[1]."Ko / ".$mem[0]." Ko</b><div id=\"progress-outer\"><div id=\"progress-inner\" style=\"width: ".$mem_percent."%; background: ".$mem_color.";\"></div></div>"
    ?></td></tr>
    <tr><td>Swap: </td><td> <?php 
      $swap[0]= trim(exec("free |grep Swap |cut -c 14-19"));
      $swap[1]= trim(exec("free |grep Swap |cut -c 27-32"));
      $swap_percent = round((intval($swap[1]) / intval($swap[0])) * 100, 0);
      $swap_color = "#3F3";
      if ($swap_percent > 90) $swap_color = "orange";
      if ($swap_percent > 95) $swap_color = "#F33";
      echo "<b>".$swap[1]."Ko / ".$swap[0]." Ko</b><div id=\"progress-outer\"><div id=\"progress-inner\" style=\"width: ".$swap_percent."%; background: ".$swap_color.";\"></div></div>"
    ?></td></tr>
    <tr><td>Fan Speed: </td><?php 
      $fan_speed = exec("cat /sys/devices/platform/stm-pwm/pwm1 | tr -d");
      echo "<td><img src=\"./style/fan.png\" />&nbsp;".$fan_speed." RPM</td></tr>";
    ?>
    <tr><td>Temperatures: </td>
    <?php 
      $temp_cpu  = exec("cat /sys/bus/i2c/devices/0-0048/temp1_input | cut -b 1-2");
      $temp_hdd  = exec("/wymedia/usr/bin/hddtemp -n /dev/sda 2> /dev/null");

      echo "<td>";
      if ($temp_cpu > 50) {
        echo "<img src=\"./style/temperature-hot.png\" />";
      } elseif ($temp_cpu > 47 && $temp_cpu <= 50) {
        echo "<img src=\"./style/temperature-warn.png\" />";
      } else {
        echo "<img src=\"./style/temperature-ok.png\" />";
      }
      echo "&nbsp;CPU ".$temp_cpu." °C</td></tr>";

      echo "<tr><td></td><td>";
      if ($temp_hdd > 45) {
        echo "<img src=\"./style/temperature-hot.png\" />";
      } elseif ($temp_hdd > 40 && $temp_hdd <= 45) {
        echo "<img src=\"./style/temperature-warn.png\" />";
      } else {
        echo "<img src=\"./style/temperature-ok.png\" />";
      }
      echo "&nbsp;HDD ".$temp_hdd." °C</td></tr>";
    ?></td></tr>
    <tr><td>dm-0 Slave: </td><td> <?php system("ls /sys/block/dm-0/slaves")?> </td></tr>
    <tr><td>Board: </td><td> <?php system("cat /proc/fb |cut -c3-")?> </td></tr>
  </table>
</div>

<h3>mtd data</h3>
<div id="mtddivid" name="mtddivname" style="display:none;">
  <pre>
    <?php system("strings /dev/mtd")?>
    <?php system("strings /dev/mtd2")?>
  </pre>
</div>
<input type="button" onClick="$('#mtddivid').slideToggle();" class="wydevslidebutton"/></input>

<h3>net data</h3>
<div id="netdivid" name="netdivname" style="display:none;">
  <pre><?php system("strings /etc/wynetwork.conf")?></pre>
</div>
<input type="button" onClick="$('#netdivid').slideToggle();" class="wydevslidebutton"/></input>

<h3>local_conf</h3>
<div id="confdivid" name="confdivname" style="display:none;">
  <pre><?php system("strings /etc/local_conf.py")?></pre>
</div>
<input type="button" onClick="$('#confdivid').slideToggle();" class="wydevslidebutton"/></input>
