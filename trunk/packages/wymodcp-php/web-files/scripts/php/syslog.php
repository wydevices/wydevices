<?php
echo "<h1>Syslog</h1>";
echo "<dl><dd>Show the 100 last lines from /wymedia/usr/var/log/syslog.log</dl>";
echo "<textarea rows=\"101\" cols=\"130\" wrap=\"off\" readonly>";
system("tail -n100 /wymedia/usr/var/log/syslog.log");
echo "</textarea>";
?>
