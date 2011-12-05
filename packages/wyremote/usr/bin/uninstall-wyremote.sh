#!/bin/sh

# Uso: sh /wymedia/wyremote/usr/bin/uninstall-wyremote.sh
# Usage: sh /wymedia/wyremote/usr/bin/uninstall-wyremote.sh

/wymedia/usr/bin/extras stop wyremote
rm -f /wymedia/usr/etc/init.d/wyremote
rm -f /wymedia/usr/share/wymodcp/wyremote.php
rm -f /wymedia/usr/share/wymodcp/scripts/php/remotecontrol.php
rm -f /wymedia/usr/share/wymodcp/style/wyremote.png
rm -rf /wymedia/wyremote
rm -rf /wymedia/screen-capture
sed -i '/input.network/d' /etc/local_conf.py
sync
echo "Se ha desinstalado wyremote. | wyremote has been uninstalled."
echo "Se reinicia el sistema en 5 segundos. | The system is restarted in 5 seconds."
sleep 5
reboot
