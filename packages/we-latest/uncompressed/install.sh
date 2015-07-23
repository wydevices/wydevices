#!/bin/sh


#
# Este script actualiza los wybox-extras.

#

#
# Definiciones de variables.

TEMPDIR="/wymedia/Backup/we-tmp"
WEVERSION="642"

#


#
# Para los servicios.

if [ -e /wymedia/usr/etc/init.d ]; then
	for service in $( ls /wymedia/usr/etc/init.d ); do
		if [ ! $service == "wymodcp" ]; then
			/wymedia/usr/etc/init.d/$service stop > /dev/null 2>&1
		fi
	done
fi

#


#
# Realiza copias de seguridad.

rm -rf $TEMPDIR
mkdir -p $TEMPDIR
mv -f /wymedia/usr/etc/cron.d/root $TEMPDIR/root-cron.d
if [ -f /wymedia/usr/etc/inadyn.conf ]; then
	mv -f /wymedia/usr/etc/inadyn.conf $TEMPDIR
fi
if [ -f /wymedia/usr/etc/mongoose_htpasswd ]; then
	mv -f /wymedia/usr/etc/mongoose_htpasswd $TEMPDIR
fi
mv -f /wymedia/usr/etc/pureftpd.passwd $TEMPDIR
mv -f /wymedia/usr/etc/rc.d $TEMPDIR
mv -f /wymedia/usr/etc/wydev-mod-version $TEMPDIR
mv -f /wymedia/usr/etc/wydev-model $TEMPDIR
mv -f /wymedia/usr/etc/wydev-rootfs $TEMPDIR
mv -f /wymedia/usr/bin/ffmpeg $TEMPDIR
mv -f /wymedia/usr/share/imagepacks $TEMPDIR
mv -f /wymedia/usr/share/skins $TEMPDIR
mv -f /wymedia/usr/share/updates $TEMPDIR
mv -f /wymedia/usr/share/wymodcp/docs $TEMPDIR
mv -f /wymedia/usr/share/doc $TEMPDIR
mv -f /wymedia/usr/share/psfreedom $TEMPDIR
mv -f /wymedia/usr/share/pygui $TEMPDIR
sync

#


#
# Desempaqueta los wybox-extras.

rm -rf /wymedia/usr
tar zxf /wymedia/tmp/wybox-extras-1.0.0-r$WEVERSION.tar.gz -C /wymedia/
sync

#


#
# Restaura las copias de seguridad.

mv -f $TEMPDIR/root-cron.d /wymedia/usr/etc/cron.d/root
if [ -f $TEMPDIR/inadyn.conf ]; then
	mv -f $TEMPDIR/inadyn.conf /wymedia/usr/etc
fi
if [ -f $TEMPDIR/mongoose_htpasswd ]; then
	mv -f $TEMPDIR/mongoose_htpasswd /wymedia/usr/etc
	sed -i 's/^.*global_auth_file/global_auth_file/' /wymedia/usr/etc/mongoose.conf
fi
mv -f $TEMPDIR/pureftpd.passwd /wymedia/usr/etc
mv -f $TEMPDIR/rc.d/* /wymedia/usr/etc/rc.d
mv -f $TEMPDIR/wydev-mod-version /wymedia/usr/etc
mv -f $TEMPDIR/wydev-model /wymedia/usr/etc
mv -f $TEMPDIR/wydev-rootfs /wymedia/usr/etc
mv -f $TEMPDIR/ffmpeg /wymedia/usr/bin
mv -f $TEMPDIR/imagepacks/* /wymedia/usr/share/imagepacks
mv -f $TEMPDIR/skins/* /wymedia/usr/share/skins
mv -f $TEMPDIR/updates/* /wymedia/usr/share/updates
for document in $( ls $TEMPDIR/docs ); do
	if [ ! -f /wymedia/usr/share/wymodcp/docs/$document ]; then
		mv -f $TEMPDIR/docs/$document /wymedia/usr/share/wymodcp/docs
	fi
done
mv -f $TEMPDIR/doc /wymedia/usr/share
mv -f $TEMPDIR/psfreedom /wymedia/usr/share
mv -f $TEMPDIR/pygui /wymedia/usr/share
rm -rf $TEMPDIR
sync

#


#
# Asegura que wymodcp, syslogd y crond se activen en el arranque.

if [ ! -d /wymedia/usr/etc/rc.d ]; then
	mkdir /wymedia/usr/etc/rc.d
fi
ln -sf /wymedia/usr/etc/init.d/wymodcp /wymedia/usr/etc/rc.d/wymodcp
ln -sf /wymedia/usr/etc/init.d/syslogd /wymedia/usr/etc/rc.d/syslogd
ln -sf /wymedia/usr/etc/init.d/crond /wymedia/usr/etc/rc.d/crond
sync

#


#
# Actualiza /wymedia/usr/etc/wydev-mod-updaterelease.

echo $WEVERSION > /wymedia/usr/etc/wydev-mod-updaterelease

#
