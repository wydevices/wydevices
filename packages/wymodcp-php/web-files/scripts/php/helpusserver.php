<br>
<h2> Help us sending statistic info </h2>
<?php
$targetcmd= "strings /dev/mtd2 |grep WC |cut -d= -f2";

echo "<h3> You will send to us this information for statistics. Thanks for submitting! </h3>";

?>

<table>
</table>

<form action="http://foro.wydev.es/wydevware/statistics.php" method="get" id="target" name="target">
  <table>
    <tr><td><b><input type="text" id="manufacturer" name="manufacturer" value="<?php system("cat /proc/wybox/MN")?>"> Manufacturer</td></tr>
    <tr><td><b><input type="text" id="wydevversion" name="wydevversion" value="<?php system("cat /wymedia/etc/wydev-mod-version")?>"> WydevFirmVersion</td></tr>
    <tr><td><b><input type="text" id="bubbleversion" name="bubbleversion" value="<?php  system("cat /wymedia/usr/etc/wydev-mod-updaterelease"); ?>"> Bubble Update Version</td></tr>
    <tr><td><input type="text" id="moddedtarget" name="moddedtarget" value="<?php system("cat /proc/wybox/WC")?>"> Modded Target</td></tr>
    <tr><td><input type="text" id="realtarget" name="realtarget" value="<?php system("strings /dev/mtd2 |grep WC |cut -d= -f2")?> "> Real Target</td></tr>
    <tr><td><input type="text" id="username" name="username"> Wydevelopment username </input></td></tr>
    <tr><td><input type="submit" onclick="javascript:document.getElementById('target').submit();" ></td></tr>
  </table>
</form>
		<hr />
        <h1><strong>News:</strong></h1>
  <!--Last available firmware: <a href="http://foro.wydev.es/viewtopic.php?f=20&t=464">wydev-mod-v4</a><br>-->
  <!--Last update released version: <a href="http://foro.wydev.es/wydevware/updates/latest.tar.gz">r308</a><br>-->
  <!--Other Downloads: <a href="http://foro.wydev.es/wydevware/updates/">Repository</a><br>-->
  Changes Detail: <a href="http://code.google.com/p/wydevices/source/list">Changes</a></small><br>
<br><!--<a href="http://foro.wydev.es/wydevware/ViewTargetsStatistics.php"> View Statistics! </a><br>-->

<?php 
echo "<br>Downloaded version:<br>";
$downloaded = system("cat /wymedia/usr/share/updates/wydev-mod-updaterelease");
echo "<br>Current version:<br>";
$current = system("cat /wymedia/usr/etc/wydev-mod-updaterelease");
echo "<br>Download latest:<br>";
system ("rm latest.txt");
//system ("wget http://foro.wydev.es/wydevware/updates/latest.txt -q");
echo "<br>Show Downloaded:<br>";
$latest = system ("cat latest.txt");
/*
if ($downloaded != $latest) {
	echo "<br>Downloading version:<br>";
	system ("wget http://foro.wydev.es/wydevware/updates/latest.tar.gz -q && mv latest.tar.gz /wymedia/usr/share/updates/");
	system ("mv latest.txt /wymedia/usr/share/updates/wydev-mod-updaterelease");
	system ("rm /wymedia/usr/share/wymodcp/scripts/php/update.php");
	system ("wget http://wydevices.googlecode.com/svn/trunk/packages/wymodcp-php/web-files/scripts/php/update.php");
	system ("mv update.php /wymedia/usr/share/wymodcp/scripts/php/");
} else {
	 echo "<br>Already Downloaded version:<br>";
	 system("cat /wymedia/usr/share/updates/wydev-mod-updaterelease");
	 echo ("as latest available");
	 echo "<table><tr><td><button onclick='updatefromlocal()'></td><td><b>BubbleUpdate from  /wymedia/usr/share/updates/</b></td></tr></table>";
}
*/
?>
<hr>
<table>
	<tr>
		<td><button onclick="updatefromstatic()"></td>
		<td><b>Update Static .tar.gz from /wymedia/usr/share/updates/static/</b></td>
	</tr>
</table>

<hr>
<!--<a href="http://foro.wydev.es/wydevware/currentvm/Wybox-OVF-v0.1.zip">Download Wybox Virtual Machine</a>-->
<hr>
<br><br><br><br><br><br><br><br><br>
