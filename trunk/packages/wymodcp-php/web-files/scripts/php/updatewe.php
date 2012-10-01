<pre>
<?php
system ("tar -zxvf /wymedia/usr/share/updates/wybox-extras-*.tar.gz -C /wymedia/; mv -f /wymedia/usr/share/updates/wybox-extras-latest.txt /wymedia/usr/etc/wydev-mod-updaterelease; rm -f /wymedia/usr/share/updates/wybox-extras-*");
echo "<br><b><h2>You must reboot the system!<h2></b>";
echo "<br><b><h2>You can now close this window!<h2></b>";
?>
</pre>
