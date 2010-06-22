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

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	rm -rf rsync-3.0.7
	rm -f rsync-3.0.7.tar.gz
	rm -f wybox-rsync-3.0.7.tar.gz
	exit 0
fi

# get rsync 3.0.7 and unpack it
if [ ! -f rsync-3.0.7.tar.gz ]; then
	wget http://samba.anu.edu.au/ftp/rsync/src/rsync-3.0.7.tar.gz || exit 1
fi
if [ ! -d rsync-3.0.7 ]; then
	echo Extracting sources
	tar xvf rsync-3.0.7.tar.gz || exit 1
fi

# configure and make
cd $WDIR/rsync-3.0.7
if [ ! -f Makefile ]; then
	echo "Creating Makefile"
	./configure \
	--host=sh4-linux \
	--prefix=/wymedia/usr \
	--disable-locale \
	--disable-acl-support \
	--disable-xattr-support \
	--with-rsyncd-conf=/wymedia/usr/etc/rsyncd.conf || exit 1
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

# create wybox package
echo "Creating wybox package"
cd $WDIR

# create directories
mkdir -p usr/bin
mkdir -p usr/lib

# copy files
cp rsync-3.0.7/rsync usr/bin
cp /opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/libpopt.so.0.0.0 usr/lib/libpopt.so.0
# copy static files
cp -rp static-files/* usr/
# delete .svn dirs if they are present
find usr -type d -name ".svn" -exec rm -rf {} +

# create tar
tar zcvf wybox-rsync-3.0.7.tar.gz usr
rm -rf usr


