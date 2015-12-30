<pre><?php system( "ssh `cat /wymedia/usr/etc/pydev-pi-username`@`cat /wymedia/usr/etc/pydev-pi-ip` \"uname -a ; whoami; df -h; sudo /sbin/ifconfig ; /sbin/initctl list\"" ); ?> </pre>
