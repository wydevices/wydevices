<?php

echo "<h2> Syslog </h2>";
echo "<pre>";

 system("tail -n40 /wymedia/usr/var/log/syslog");

echo "</pre>";

 ?>
 <script src="./scripts/js/wydev.js" type="text/javascript"></script>
 
<table>
	<tr>
		<td><button onclick="updatefromlocal()"></td>
		<td><b>Update from /wymedia/usr/share/updates/</b></td>
	</tr>
</table>




