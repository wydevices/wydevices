####################################################################
#  Pure-FTPd 1.0.29 build script                                   #
#                                                                  #
#  Description:                                                    #
#    This script will generate a Pure-FTPd package for a wydevice. #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-pure-ftpd-1.0.29.tar.gz       #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

# check if ar is installed
if ! which ar >/dev/null 2>&1; then
	echo "ERROR: \"ar\" is requiered and was not found"
	echo "       Install it with: sudo yum install binutils"
	echo
	exit 1
fi

# get pure-ftpd 1.0.29 and unpack it
if [ ! -f pure-ftpd-1.0.29.tar.gz ]; then
	wget http://download.pureftpd.org/pub/pure-ftpd/releases/pure-ftpd-1.0.29.tar.gz || exit 1
fi
rm -rf pure-ftpd-1.0.29
tar xvf pure-ftpd-1.0.29.tar.gz || exit 1

# patch source to avoid some compile errors
cd pure-ftpd-1.0.29
patch -p1 < ../patches/log_puredb-uid0.patch || exit 1
patch -p1 < ../patches/pure-pw-uid0.patch || exit 1

# configure and make
./configure \
--host=sh4-linux \
--prefix=/wymedia/usr \
--with-altlog \
--with-ftpwho \
--with-throttling \
--with-puredb \
--without-inetd || exit 1

NPROCS=`grep -c ^processor /proc/cpuinfo`
make -j$NPROCS || exit 1
cd ..

# create wybox package

# create directories
mkdir -p usr/bin

# copy files
cp pure-ftpd-1.0.29/src/pure-ftpd usr/bin
cp pure-ftpd-1.0.29/src/pure-pw usr/bin
cp pure-ftpd-1.0.29/src/pure-ftpwho usr/bin
# copy static files
cp -rp static-files/* usr/
# delete .svn dirs if they are present
find usr -type d -name ".svn" -exec rm -rf {} +

# create tar
tar zcvf wybox-pure-ftpd-1.0.29.tar.gz usr
rm -rf usr


