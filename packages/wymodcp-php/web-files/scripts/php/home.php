#!/wymedia/usr/bin/php-cgi
<script src="../js/wydev.js" type="text/javascript"></script>
<script type="text/javascript" src="./scripts/js/niftycube.js"></script>
<script src="./scripts/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./style/wymod.css" />


<h2>Home</h2>
<h3>console</h3>
<div id="consoledivid" name="consoledivname" style="display:none;">
<pre>

<iframe height="490" width="750" src="./scripts/php/shell.php"></iframe>
</pre>
</div>
<input type="button" onClick="$('#consoledivid').slideToggle();" class="wydevslidebutton"/></input>


<h3> Data </h3>

<div id="datadivid" name="datadivname">
<table> 
<tr><td>Manufacturer: </td><td><b><?php system("cat /proc/wybox/MN")?></td></tr>
<tr><td>WydevFirm Version: </td><td><b><?php system("cat /wymedia/usr/etc/wydev-mod-version")?></td></tr>
<tr><td>Bubble Update Version: </td><td><b><?php  system("cat /wymedia/usr/etc/wydev-mod-updaterelease"); ?></td></tr>
<tr><td>Modded Target: </td><td><?php system("cat /proc/wybox/WC")?></td></tr>
<tr><td>Real Target: </td><td> <?php system("strings /dev/mtd2 |grep WC |cut -d= -f2")?> </td></tr>
<tr><td>Serial Number: </td><td> <?php system("cat /proc/wybox/SN")?> </td></tr>
<tr><td>Time / Uptime: </td><td> <?php system("uptime |cut -f1 -d,")?> </td></tr>
<tr><td>Fan Speed & Temperatures: </td><td> <?php system("/wymedia/usr/bin/temp") ?> </td></tr>
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
<pre>
<?php system("strings /etc/wynetwork.conf")?> 
</pre>
</div>
<input type="button" onClick="$('#netdivid').slideToggle();" class="wydevslidebutton"/></input>

<h3>local_conf</h3>
<div id="confdivid" name="confdivname" style="display:none;">
<pre>
<?php system("strings /etc/local_conf.py")?> 
</pre>
</div>
<input type="button" onClick="$('#confdivid').slideToggle();" class="wydevslidebutton"/></input>


<hr>

<b>Suggest improvements on <a href="http://foro.wydev.es">forum</a>





