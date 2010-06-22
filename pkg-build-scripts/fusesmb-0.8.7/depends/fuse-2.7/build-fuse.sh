####################################################################
#  Fuse 2.7.4 build script                                         #
#                                                                  #
#  Description:                                                    #
#    This script will build fuse                                   #
#    It will download sources and compile                          #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	rm -rf fuse-2.7.4
	rm -f fuse-2.7.4.tar.gz
	exit 0
fi

# get fuse 2.7.4 and unpack it
if [ ! -f fuse-2.7.4.tar.gz ]; then
	wget http://sourceforge.net/projects/fuse/files/fuse-2.X/2.7.4/fuse-2.7.4.tar.gz/download || exit 1
fi
if [ ! -d fuse-2.7.4 ]; then
	echo Extracting sources
	tar xvf fuse-2.7.4.tar.gz || exit 1
fi


# configure and make
cd $WDIR/fuse-2.7.4
if [ ! -f Makefile ]; then
	echo "Creating Makefile"
	./configure \
	--host=sh4-linux \
	--prefix=`pwd` \
	--exec-prefix=`pwd` || exit 1
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

cd $WDIR/fuse-2.7.4/include
rm -f fuse
ln -s . fuse

