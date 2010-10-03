#!/wymedia/usr/bin/php-cgi
<html><body>
<?php echo system("transmission-remote $(ifconfig | grep inet |grep -v 127.0.0.1 |cut -d: -f2|cut -d\" \" -f1):80 -l") ?>
</body></html>
