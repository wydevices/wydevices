####################################################################
#  libevent 1.4.14a build script                                   #
#                                                                  #
#  Description:                                                    #
#    This script will build libevent                               #
#    It will download sources and compile                          #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Authors: argos, deniro666                                       #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	rm -rf libevent-1.4.14-stable
	rm -f libevent-1.4.14a-stable.tar.gz
	exit 0
fi

# get libevent 1.4.14a and unpack it
if [ ! -f libevent-1.4.14a-stable.tar.gz ]; then
	wget http://monkey.org/~provos/libevent-1.4.14a-stable.tar.gz || exit 1
fi
if [ ! -d libevent-1.4.14-stable ]; then
	echo Extracting sources
	tar xvf libevent-1.4.14a-stable.tar.gz || exit 1
fi

# configure and make
cd $WDIR/libevent-1.4.14-stable

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

