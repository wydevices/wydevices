#!/bin/sh

# Uso: sh install-wyremote.sh
# Usage: sh install-wyremote.sh

SCRIPT=$(readlink -f $0)
SCRIPTPATH=`dirname $SCRIPT`

cd /wymedia
tar zxf $SCRIPTPATH/wyremote-*.tar.gz
if [ $? -eq 0 ]; then

	ln -s /wymedia/wyremote/usr/etc/init.d/wyremote /wymedia/usr/etc/init.d/wyremote
	ln -s /wymedia/wyremote/usr/share/wymodcp/wyremote.html /wymedia/usr/share/wymodcp/wyremote.html
	ln -s /wymedia/wyremote/usr/share/wymodcp/scripts/php/remotecontrol.php /wymedia/usr/share/wymodcp/scripts/php/remotecontrol.php
  ln -s /wymedia/wyremote/usr/share/wymodcp/style/wyremote.png /wymedia/usr/share/wymodcp/style/wyremote.png
	ln -s /wymedia/usr/bin/mongoose /wymedia/wyremote/usr/bin/mongoose

	sed -i '/input.network/d' /etc/local_conf.py
	cat /etc/local_conf.py > /tmp/local_conf.py
	echo >> /tmp/local_conf.py
	echo "plugins.add('input.network')" >> /tmp/local_conf.py
	mv -f /tmp/local_conf.py /etc/

	sync

	echo "Se ha instalado wyremote. | wyremote is installed."
	echo "Se reinicia el sistema en 5 segundos. | The system is restarted in 5 seconds."
	sleep 5
	reboot

else

	 echo "No se ha instalado wyremote. | wyremote is not installed."

fi
