####################################################################
#  Fusesmb 0.8.7 build script                                      #
#                                                                  #
#  Description:                                                    #
#    This script will generate a Fusesmb package for a wydevice.   #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-fusesmb-0.8.7.tar.gz          #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

# build depends
cd depends/fuse-2.7
./build-fuse.sh
cd ../..
cd depends/samba-3.4
./build-samba.sh
cd ../..

# get fusesmb 0.8.7 and unpack it
if [ ! -f fusesmb-0.8.7.tar.gz ]; then
	wget http://www.ricardis.tudelft.nl/~vincent/fusesmb/download/fusesmb-0.8.7.tar.gz || exit 1
fi
rm -rf fusesmb-0.8.7
tar xvf fusesmb-0.8.7.tar.gz || exit 1

# configure and make
export PKG_CONFIG_PATH="`pwd`/depends/fuse-2.7/fuse-2.7.4:$PKG_CONFIG_PATH"
export CFLAGS="-I`pwd`/depends/samba-3.4/samba-3.4.8/source3/include -I`pwd`/depends/fuse-2.7/fuse-2.7.4/include"
export LDFLAGS="-L`pwd`/depends/samba-3.4/samba-3.4.8/source3/bin -L`pwd`/depends/fuse-2.7/fuse-2.7.4/lib/.libs"
export LIBS="-ltalloc -ltdb"
export NMBLOOKUP=yes
cd fusesmb-0.8.7
echo 
./configure \
--host=sh4-linux \
--prefix=/wymedia/usr || exit 1

NPROCS=`grep -c ^processor /proc/cpuinfo`
make -j$NPROCS || exit 1
cd ..

# create wybox package

# create directories
mkdir -p usr/bin
mkdir -p usr/lib

# copy files
cp fusesmb-0.8.7/fusesmb usr/bin
cp fusesmb-0.8.7/fusesmb.cache usr/bin
cp depends/samba-3.4/samba-3.4.8/source3/bin/libsmbclient.so.0 usr/lib
cp depends/samba-3.4/samba-3.4.8/source3/bin/libtalloc.so.1 usr/lib
cp depends/samba-3.4/samba-3.4.8/source3/bin/libtdb.so.1 usr/lib
# copy static files
cp -rp static-files/* usr/
# delete .svn dirs if they are present
find usr -type d -name ".svn" -exec rm -rf {} +

# create tar
tar zcvf wybox-fusesmb-0.8.7.tar.gz usr
rm -rf usr


