<?php

echo "<h2> Syslog </h2>";
echo "<pre>";

 system("tail -n40 /wymedia/usr/var/log/syslog.log");

echo "</pre>";

 ?>





