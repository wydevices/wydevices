#!/wymedia/usr/bin/php-cgi
<script src="../js/wydev.js" type="text/javascript"></script>
<script type="text/javascript" src="./scripts/js/niftycube.js"></script>
<script src="./scripts/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./style/wymod.css" />

<h2> Home </h2>
<table> 
<tr><td>Manufacturer: </td><td><b><?php system("cat /proc/wybox/MN")?></td></tr>
<tr><td>WydevFirm Version: </td><td><b><?php system("cat /wymedia/usr/etc/wydev-mod-version")?></td></tr>
<tr><td>Modded Target: </td><td><?php system("cat /proc/wybox/WC")?></td></tr>
<tr><td>Real Target: </td><td> <?php system("strings /dev/mtd2 |grep WC |cut -d= -f2")?> </td></tr>
<tr><td>Serial Number: </td><td> <?php system("cat /proc/wybox/SN")?> </td></tr>
<tr><td>Time / Uptime: </td><td> <?php system("uptime |cut -f1 -d,")?> </td></tr>
<tr><td>Fan Speed & Temperatures: </td><td> <?php system("/wymedia/usr/bin/temp") ?> </td></tr>
<tr><td>dm-0 Slave: </td><td> <?php system("ls /sys/block/dm-0/slaves")?> </td></tr>
<tr><td>Board: </td><td> <?php system("cat /proc/fb |cut -c3-")?> </td></tr>
<tr><td>Net Settings: </td><td> <?php system("ifconfig | grep inet |grep -v 127.0.0.1 |cut -d: -f2-4")?></td></tr>
<tr><td>Route table: </td><td><pre> <?php system("route")?></pre></td></tr>

</table>

<h3> MTD2 Dumped Data </h3>
<pre>
<?php system("strings /dev/mtd2")?> 
</pre>



<hr>

<b>Suggest improvements on <a href="http://foro.wydev.es">forum</a>





