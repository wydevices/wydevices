<pre>
<?php
system ("tar -zxvf /wymedia/usr/share/updates/latest.tar.gz -C /wymedia/");
system ("mv latest.txt /wymedia/usr/etc/wydev-mod-updaterelease");
echo ("<b><h2> You can now close this window!<h2></b>");
?>
</pre>