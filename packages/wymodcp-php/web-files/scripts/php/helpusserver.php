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

system ("wget http://foro.wydev.es/wydevware/updates/latest.tar.gz");
system ("mv latest.tar.gz /wymedia/usr/share/updates/");

?>
<hr>
<a href="./currentvm/Wybox-OVF-v0.1.zip">Download Wybox Virtual Machine</a>
<hr>
