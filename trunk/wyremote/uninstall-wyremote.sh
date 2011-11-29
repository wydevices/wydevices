#!/bin/sh

cd /wymedia
/wymedia/usr/bin/extras stop wyremote
rm /wymedia/usr/share/wymodcp/wyremote.html
rm /wymedia/usr/share/wymodcp/scripts/php/remotecontrol.php
rm /wymedia/usr/etc/init.d/wyremote
sed -i '/input.network/d' /etc/local_conf.py
sync
echo "Se ha desinstalado wyremote. | wyremote has been uninstalled."
echo "Se reinicia el sistema en 5 segundos. | The system is restarted in 5 seconds."
sleep 5
reboot
