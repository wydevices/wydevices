####################################################################
#  Fuse 2.7.4 build script                                     #
#                                                                  #
#  Description:                                                    #
#    This script will generate a SMBNetFS package for a wydevice.  #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-smbnetfs-0.5.2.tar.gz         #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

# get fuse 2.7.4 and unpack it
if [ ! -f fuse-2.7.4.tar.gz ]; then
	wget http://sourceforge.net/projects/fuse/files/fuse-2.X/2.7.4/fuse-2.7.4.tar.gz/download || exit 1
fi
rm -rf fuse-2.7.4
tar xvf fuse-2.7.4.tar.gz || exit 1

# configure and make
cd fuse-2.7.4
./configure \
--host=sh4-linux \
--prefix=`pwd` \
--exec-prefix=`pwd`

NPROCS=`grep -c ^processor /proc/cpuinfo`
make -j$NPROCS || exit 1

cd include
ln -s . fuse

