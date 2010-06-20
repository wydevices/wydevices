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

# get samba 3.4.8 and unpack it
if [ ! -f samba-3.4.8.tar.gz ]; then
	wget http://www.samba.org/samba/ftp/samba-3.4.8.tar.gz || exit 1
fi
rm -rf samba-3.4.8
tar xvf samba-3.4.8.tar.gz || exit 1

# patch source to avoid some compile errors
cd samba-3.4.8
patch -p1 < ../patches/samba-3.4.8-wybox.patch || exit 1

# configure and make
cd source3
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
--with-readline=DIR \
--with-libiconv=BASEDIR \
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

NPROCS=`grep -c ^processor /proc/cpuinfo`
make -j$NPROCS || exit 1
cd ../..

# create wybox package

# create directories
mkdir -p usr/bin
mkdir -p usr/lib
mkdir -p usr/var/lib/samba/private/

# copy files
cp samba-3.4.8/source3/bin/smbd usr/bin
cp samba-3.4.8/source3/bin/nmbd usr/bin
cp samba-3.4.8/source3/bin/smbstatus usr/bin
cp samba-3.4.8/source3/bin/libtalloc.so.1 usr/lib
cp samba-3.4.8/source3/bin/libtdb.so.1 usr/lib
cp /opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/libpopt.so.0.0.0 usr/lib/libpopt.so.0
# copy static files
cp -rp static-files/* usr/

# create tar
tar zcvf wybox-samba-3.4.8.tar.gz usr
rm -rf usr


