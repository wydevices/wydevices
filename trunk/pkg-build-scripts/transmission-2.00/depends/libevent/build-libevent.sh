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

# get libevent 1.4.14a and unpack it
if [ ! -f libevent-1.4.14a-stable.tar.gz ]; then
	wget http://monkey.org/~provos/libevent-1.4.14a-stable.tar.gz || exit 1
fi
rm -rf libevent-1.4.14-stable
tar xvf libevent-1.4.14a-stable.tar.gz || exit 1

# configure and make
cd libevent-1.4.14-stable
./configure \
--host=sh4-linux \
--prefix=`pwd` \
--exec-prefix=`pwd` || exit 1


NPROCS=`grep -c ^processor /proc/cpuinfo`
make -j$NPROCS || exit 1
cd ..


