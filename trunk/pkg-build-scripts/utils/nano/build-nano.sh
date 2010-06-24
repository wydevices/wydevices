####################################################################
#  GNU nano 2.2.4  build script                                    #
#                                                                  #
#  Description:                                                    #
#    This script will generate a nano package for a wydevice.      #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-nano-2.2.4.tar.gz             #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	rm -rf nano-2.2.4
	rm -f nano-2.2.4.tar.gz
	rm -f wybox-nano-2.2.4.tar.gz
	exit 0
fi

# get nano 2.2.4 and unpack it
if [ ! -f nano-2.2.4.tar.gz ]; then
	wget http://www.nano-editor.org/dist/v2.2/nano-2.2.4.tar.gz || exit 1
fi
if [ ! -d nano-2.2.4 ]; then
	echo Extracting sources
	tar xvf nano-2.2.4.tar.gz || exit 1
fi

# configure and make
cd $WDIR/nano-2.2.4
if [ ! -f Makefile ]; then
	echo "Creating Makefile"
	./configure \
	--host=sh4-linux || exit 1
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
mkdir -p usr/lib/terminfo/l

# copy files
cp nano-2.2.4/src/nano usr/bin
cp /opt/STM/STLinux-2.3/devkit/sh4/target/lib/libncurses.so.5.5 usr/lib/libncurses.so.5
cp /opt/STM/STLinux-2.3/devkit/sh4/target/usr/share/terminfo/l/linux usr/lib/terminfo/l

# create tar
tar zcvf wybox-nano-2.2.4.tar.gz usr
rm -rf usr


