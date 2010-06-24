####################################################################
#  Samba 3.4.8 build script                                        #
#                                                                  #
#  Description:                                                    #
#    This script will generate a Samba package for a wydevice.     #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-samba-3.4.8.tar.gz            #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	rm -rf samba-3.4.8
	rm -f samba-3.4.8.tar.gz
	rm -f wybox-samba-3.4.8.tar.gz
	exit 0
fi

# get samba 3.4.8 and unpack it
if [ ! -f samba-3.4.8.tar.gz ]; then
	wget http://www.samba.org/samba/ftp/samba-3.4.8.tar.gz || exit 1
fi
if [ ! -d samba-3.4.8 ]; then
	echo Extracting sources
	tar xvf samba-3.4.8.tar.gz || exit 1

	# patch source to avoid some compile errors
	cd $WDIR/samba-3.4.8
	patch -p1 < ../patches/samba-3.4.8-wybox.patch || exit 1
fi



# configure and make
cd $WDIR/samba-3.4.8/source3
if [ ! -f Makefile ]; then
	echo "Creating Makefile"
	./configure \
	--host=sh4-linux \
	--prefix=/wymedia/usr \
	--with-privatedir=/wymedia/usr/var/lib/samba/private \
	--with-lockdir=/wymedia/usr/var/lib/samba \
	--with-piddir=/var/run \
	--with-configdir=/wymedia/usr/etc/samba \
	--with-logfilebase=/wymedia/usr/var/log/samba \
	--disable-swat \
	--disable-cups \
	--disable-iprint \
	--disable-fam \
	--disable-avahi \
	--disable-merged-build \
	--disable-gnutls \
	--without-ldap \
	--without-ads \
	--without-cifsmount \
	--without-cifsupcall \
	--without-sys-quotas \
	--without-utmp \
	--without-cluster-support \
	--without-acl-support \
	--without-wbclient \
	--without-winbind || exit 1
else
	echo "Makefile exists, skipping configure"
fi


if ! make -q > /dev/null 2>&1; then
	echo "Building"
	NPROCS=`grep -c ^processor /proc/cpuinfo`
	make -j$NPROCS || exit 1
else
	echo "Already built, skipping make"
fi

