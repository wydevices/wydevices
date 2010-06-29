####################################################################
#  INADYN 1.96.2 build script                                      #
#                                                                  #
#  Description:                                                    #
#    This script will generate a inadyn package for a wydevice     #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-inadyn-1.96.2.tar.gz          #
#                                                                  #
#  Authors: argos, deniro666, GdalPlay, minukab                    #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
        rm -rf inadyn 
        rm -f inadyn.v1.96.2.zip
        rm -f wybox-inadyn-1.96.2.tar.gz
        exit 0
fi

# get inadyn 1.96.2 and unpack it
if [ ! -f inadyn.v1.96.2.zip ]; then
        wget http://www.inatech.eu/inadyn/inadyn.v1.96.2.zip || exit 1
fi
if [ ! -d inadyn ]; then
        echo Extracting sources
        unzip inadyn.v1.96.2.zip || exit 1
fi

# configure and make
cd $WDIR/inadyn

export PATH=/opt/STM/STLinux-2.3/devkit/sh4/bin:$PATH
export PKG_CONFIG_PATH=/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/pkgconfig

if ! make -q > /dev/null 2>&1; then
        echo "Building"
	rm -rf bin
        make || exit 1
else
        echo "Already built, skipping make"
fi

# create wybox package
echo "Creating wybox package"
cd $WDIR

# create directories
mkdir -p usr/bin

# copy files
cp inadyn/bin/linux/inadyn usr/bin 
# copy static files
cp -rp static-files/* usr/
# delete .svn dirs if they are present
find usr -type d -name ".svn" -exec rm -rf {} +

# create tar
tar zcvf wybox-inadyn.1.96.2.tar.gz usr
rm -rf usr
