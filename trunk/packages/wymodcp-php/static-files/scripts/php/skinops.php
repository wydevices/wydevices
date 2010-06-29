#!/wymedia/usr/bin/php-cgi
<html>
<head><script src="../js/wydev.js" type="text/javascript"></script></head>
<?php
$skinopsparam = $_GET['skinops'];
$command= "skinops.sh -".$skinopsparam;

echo "<b>".$command."</b>";
echo "<pre>";
echo system($command);
echo "</pre>";

sleep(5);
echo "";
?> 
<body onload="history.back();">
</body>
</html>

