	<script src='./scripts/js/wydev.js' type='text/javascript'></script>
		<br>
        <h1><strong>News:</strong></h1>
		Last available firmware: <a href="http://foro.wydev.es/viewtopic.php?f=20&t=366">3.1</a><br>
        Last update released version: <a href="http://foro.wydev.es/wydevware/updates/r249.tar.gz">r249</a><br>
		 
		 <b>R249 Description:</b><br>
		 <small>Corrected TV Channel bugfixes and added improvements. Amazing records control panel, more info at home, new cloud wydevware integration... stills working in progress!!</small><br>
<br>    <a href="http://foro.wydev.es/wydevware/ViewTargetsStatistics.php"> View Statistics! </a><br>

<?php 
echo "<br>Downloaded version:<br>";
$downloaded = system("cat /wymedia/usr/share/updates/wydev-mod-updaterelease");
echo "<br>Current version:<br>";
$current = system("cat /wymedia/usr/etc/wydev-mod-updaterelease");
echo "<br>Download latest:<br>";
system ("wget http://foro.wydev.es/wydevware/updates/latest.txt -q");
echo "<br>Show Downloaded:<br>";
$latest = system ("cat latest.txt");

if ($downloaded != $latest) {
	echo "<br>Downloading version:<br>";
	system ("wget http://foro.wydev.es/wydevware/updates/latest.tar.gz -q && mv latest.tar.gz /wymedia/usr/share/updates/");
	system ("mv latest.txt /wymedia/usr/share/updates/wydev-mod-updaterelease");
	system ("rm /wymedia/usr/share/wymodcp/scripts/php/update.php");
	system ("wget http://wydevices.googlecode.com/svn/trunk/packages/wymodcp-php/web-files/scripts/php/update.php");
	system ("mv update.php /wymedia/usr/share/wymodcp/scripts/php/");
	}
else {
	 echo "<br>Already Downloaded version:<br>";
	 system("cat /wymedia/usr/share/updates/wydev-mod-updaterelease");
	 echo ("as latest available");
	 echo "<table><tr><td><button onclick='updatefromlocal()'></td><td><b>Update from /wymedia/usr/share/updates/</b></td></tr></table>";
}

?>
<hr>
<a href="./currentvm/Wybox-OVF-v0.1.zip">Download Wybox Virtual Machine</a>
<hr>
