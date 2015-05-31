#!/bin/sh
#
# This script is run for install your package
#

logger "extras 0.0.6 install script launched"
echo "wydev-model-package installed on `date`"

cd /wymedia
echo "`tar -zxvf /wymedia/usr/share/updates/wymodupd-extras-0.0.6/wymodupd-extras-0.0.6/wybox-extras-0.0.6.tar.gz`"
mkdir /var/spool
mkdir /var/spool/cron/

#### Recarga de los extras
extras start samba-server
extras stop crond
extras start mediatomb
extras stop mediatomb
extras start crond
extras stop wymodcp
extras start wymodcp
