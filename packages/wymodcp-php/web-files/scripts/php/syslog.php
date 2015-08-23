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

echo "<h1>Transmission</h1>";
echo "<dl><dd>Show the 100 last lines from /wymedia/usr/var/log/transmission.log</dl>";
echo "<textarea rows=\"101\" cols=\"130\" wrap=\"off\" readonly>";
system("tail -n100 /wymedia/usr/var/log/transmission.log");
echo "</textarea>";

echo "<h1>Mongoose access</h1>";
echo "<dl><dd>Show the 100 last lines from /wymedia/usr/var/log/mongoose_access.log</dl>";
echo "<textarea rows=\"101\" cols=\"130\" wrap=\"off\" readonly>";
system("tail -n100 /wymedia/usr/var/log/mongoose_access.log");
echo "</textarea>";


echo "<h1>Mongoose error</h1>";
echo "<dl><dd>Show the 100 last lines from /wymedia/usr/var/log/mongoose_error.log</dl>";
echo "<textarea rows=\"101\" cols=\"130\" wrap=\"off\" readonly>";
system("tail -n100 /wymedia/usr/var/log/mongoose_error.log");
echo "</textarea>";

?>
