<pre>
<?php
system ("tar -zxvf /wymedia/tmp/wybox-extras-*.tar.gz -C /wymedia/tmp; sh /wymedia/tmp/install.sh; mv -f /wymedia/tmp/wybox-extras-latest.txt /wymedia/usr/etc/wydev-mod-updaterelease; rm -f /wymedia/tmp/wybox-extras-*; rm -f /wymedia/tmp/install.sh");
echo "<br><b><h2>The system will restart in 30 seconds!<h2></b>";
echo "<br><b><h2>You can now close this window!<h2></b>";
system ("nohup reboot -d30 &");
?>
</pre>
