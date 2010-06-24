####################################################################
#  hddtemp 0.3  build script                                       #
#                                                                  #
#  Description:                                                    #
#    This script will generate a hddtemp package for a wydevice.   #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-hddtemp-0.3.tar.gz            #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	rm -rf hddtemp-0.3-beta15
	rm -f hddtemp-0.3-beta15.tar.bz2
	rm -f wybox-hddtemp-0.3.tar.gz
	rm -f hddtemp.db
	exit 0
fi

# get hddtemp and unpack it
if [ ! -f hddtemp-0.3-beta15.tar.bz2 ]; then
	wget http://mirrors.fe.up.pt/pub/nongnu/hddtemp/hddtemp-0.3-beta15.tar.bz2 || exit 1
fi
if [ ! -d hddtemp-0.3-beta15 ]; then
	echo Extracting sources
	tar xvf hddtemp-0.3-beta15.tar.bz2 || exit 1
fi

# configure and make
cd $WDIR/hddtemp-0.3-beta15
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
mkdir -p usr/lib/share/misc/

# copy files
cp hddtemp-0.3-beta15/src/hddtemp usr/bin
if [ ! -f hddtemp.db ]; then
	wget http://www.guzu.net/linux/hddtemp.db
fi
cp hddtemp.db usr/lib/share/misc/

# create tar
tar zcvf wybox-hddtemp-0.3.tar.gz usr
rm -rf usr


