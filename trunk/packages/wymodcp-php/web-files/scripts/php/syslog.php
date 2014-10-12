<?php
echo "<h1>Syslog</h1>";
echo "<dl><dd>Show the 100 last lines from /wymedia/usr/var/log/syslog.log</dl>";
echo "<textarea rows=\"101\" cols=\"130\" wrap=\"off\" readonly>";
system("tail -n100 /wymedia/usr/var/log/syslog.log");
echo "</textarea>";
echo "<h1>mediatomb</h1>";
echo "<dl><dd>Show the 100 last lines from /wymedia/usr/var/log/mediatomb.log</dl>";
echo "<textarea rows=\"101\" cols=\"130\" wrap=\"off\" readonly>";
system("tail -n100 /wymedia/usr/var/log/mediatomb.log");
echo "</textarea>";
?>