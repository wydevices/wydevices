		<br>
        <h1><strong>News:</strong></h1>
		<h3> Last available firmware: <a href="http://foro.wydev.es/viewtopic.php?f=20&t=366">3.1</a><br></h3>
         <h3>Last update released version: <a href="http://foro.wydev.es/wydevware/updates/r249.tar.gz">r249</a></h3>
		 
		 <b>R249 Description:</b>
		 <small>Corrected TV Channel bugfixes and added improvements. Amazing records control panel, more info at home, new cloud wydevware integration... stills working in progress!!</small>
<br>    <a href="http://foro.wydev.es/wydevware/ViewTargetsStatistics.php"> View Statistics! </a>
        <h1> </h1>
<?php 

system("cat /wymedia/usr/etc/wydev-mod-version");
$current = system("cat /wymedia/usr/etc/wydev-mod-updaterelease");
system ("wget http://foro.wydev.es/wydevware/updates/latest.txt -q");
$latest = system ("cat latest.txt");

if ($current != $latest) {
	system ("wget http://foro.wydev.es/wydevware/updates/latest.tar.gz -q && mv latest.tar.gz /wymedia/usr/share/updates/");
	echo "<script src="./scripts/js/wydev.js" type="text/javascript"></script>";
	echo "<table><tr><td><button onclick="updatefromlocal()"></td><td><b>Update from /wymedia/usr/share/updates/</b></td></tr></table>";
	system ("mv latest.txy /wymedia/usr/etc/wydev-mod-updaterelease");
	}
else {
	echo ("System up to date");
}

?>
<hr>
<a href="./currentvm/Wybox-OVF-v0.1.zip">Download Wybox Virtual Machine</a>
<hr>
