#!/wymedia/usr/bin/php-cgi
<html>
<head><script src="../js/wydev.js" type="text/javascript"></script></head>
<h2>Home</h2>
<hr>

<table> 
<tr><td>Manufacturer: </td><td><b><?php system("cat /proc/wybox/MN")?>


</b></td></tr>
<tr><td>Target: </td><td><?php system("cat /proc/wybox/WC")?>
</td></tr>
<tr><td>Serial Number: </td><td> <?php system("cat /proc/wybox/SN")?> </td></tr>
<tr><td>Time / Uptime: </td><td> <?php system("uptime |cut -f1 -d,")?> </td></tr>
<tr><td>Fan Speed: </td><td> <?php system("temp |cut -f2 -d: |cut -f1")?> </td></tr>
<tr><td>Cpu Temp: </td><td> <?php system("temp |cut -f3 -d: |cut -f1")?> </td></tr>
<tr><td>HD Temp: </td><td> <?php system("temp |cut -f4 -d: |cut -f1")?> </td></tr>
<tr><td>dm-0 Slave: </td><td> <?php system("ls /sys/block/dm-0/slaves")?> </td></tr>
<tr><td>Board: </td><td> <?php system("cat /proc/fb |cut -c3-")?> </td></tr>
<tr><td>Target: </td><td> <?php system("strings /dev/mtd2 |grep WC |cut -d= -f2")?> </td></tr>
<tr><td>Net Settings: </td><td> <?php system("ifconfig | grep inet |grep -v 127.0.0.1 |cut -d: -f2-4")?></td></tr>
</table>

<pre>
<?php system("strings /dev/mtd2")?> 
</pre>



<hr>

<b>Suggest improvements on <a href="http://foro.wydev.es">forum</a>




<body onload="history.back();">
</body>
</html>
