####################################################################
#  Transmission 2.00 build script                                  #
#                                                                  #
#  Description:                                                    #
#    This script will generate a Tramission package for a wydevice #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-transmission-2.00.tar.gz      #
#                                                                  #
#  Authors: argos, deniro666                                       #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

# NOTE: Requires curl
#       To install run:
#        $ /opt/STM/STLinux-2.3/host/bin/stmyum install stlinux23-sh4-curl-dev

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	cd $WDIR/depends/libevent
	./build-libevent.sh clean || exit 1
	cd $WDIR
	rm -rf transmission-2.00
	rm -f transmission-2.00.tar.bz2
	rm -f wybox-transmission-2.00.tar.gz
	exit 0
fi

# build dependencies
cd $WDIR/depends/libevent
./build-libevent.sh || exit 1

# get transmission 2.00 and unpack it
cd $WDIR
if [ ! -f transmission-2.00.tar.bz2 ]; then
	wget http://mirrors.m0k.org/transmission/files/transmission-2.00.tar.bz2 || exit 1
fi
if [ ! -d transmission-2.00 ]; then
	echo Extracting sources
	tar xvf transmission-2.00.tar.bz2 || exit 1
fi

# configure and make
cd $WDIR/transmission-2.00
export LIBEVENT_CFLAGS="-I$WDIR/depends/libevent/libevent-1.4.14-stable"
export LIBEVENT_LIBS="-L$WDIR/depends/libevent/libevent-1.4.14-stable -levent"

if [ ! -f Makefile ]; then
	echo "Creating Makefile"
	./configure \
	--host=sh4-linux \
	--prefix=/wymedia/usr \
	--enable-daemon \
	--disable-nls \
	--disable-mac \
	--disable-gtk \
	--disable-libappindicator \
	--disable-libcanberra \
	--with-gnu-ld || exit 1
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
mkdir -p usr/lib/share/transmission

# copy files
cp transmission-2.00/daemon/.libs/transmission-daemon usr/bin
cp transmission-2.00/daemon/.libs/transmission-remote usr/bin
cp depends/libevent/libevent-1.4.14-stable/.libs/libevent-1.4.so.1 usr/lib
cp /opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/libcurl.so.4 usr/lib
cp -r transmission-2.00/web usr/lib/share/transmission
# copy static files
cp -rp static-files/* usr/
# delete .svn dirs if they are present
find usr -type d -name ".svn" -exec rm -rf {} +

# create tar
tar zcvf wybox-transmission-2.00.tar.gz usr
rm -rf usr
