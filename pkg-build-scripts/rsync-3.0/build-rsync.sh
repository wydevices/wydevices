####################################################################
#  Rsync 3.0.7 build script                                        #
#                                                                  #
#  Description:                                                    #
#    This script will generate a Rsync package for a wydevice.     #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-rsync-3.0.7.tar.gz            #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

# get rsync 3.0.7 and unpack it
if [ ! -f rsync-3.0.7.tar.gz ]; then
	wget http://samba.anu.edu.au/ftp/rsync/src/rsync-3.0.7.tar.gz || exit 1
fi
rm -rf rsync-3.0.7
tar xvf rsync-3.0.7.tar.gz || exit 1

# configure and make
cd rsync-3.0.7
./configure \
--host=sh4-linux \
--prefix=/wymedia/usr \
--disable-locale \
--disable-acl-support \
--disable-xattr-support \
--with-rsyncd-conf=/wymedia/usr/etc/rsyncd.conf || exit 1

NPROCS=`grep -c ^processor /proc/cpuinfo`
make -j$NPROCS || exit 1
cd ..

# create wybox package

# create directories
mkdir -p usr/bin
mkdir -p usr/lib

# copy files
cp rsync-3.0.7/rsync usr/bin
cp /opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/libpopt.so.0.0.0 usr/lib/libpopt.so.0
# copy static files
cp -rp static-files/* usr/

# create tar
tar zcvf wybox-rsync-3.0.7.tar.gz usr
rm -rf usr


