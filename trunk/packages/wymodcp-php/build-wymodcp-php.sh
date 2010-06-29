####################################################################
#  WymodCP-php  build script                                       #
#                                                                  #
#  Description:                                                    #
#    This script will generate wymodcp-php package for a wydevice. #
#    It will compile and pack the results in a file that can be    #
#    directly installed under /wymedia                             #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wymodcp-php.v1.0.0.tar.gz           #
#                                                                  #
#  Author: beats                                                   #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	rm -rf usr
        rm ./static-files/wymodphpdaemon
	exit 0
fi

/opt/STM/STLinux-2.3/devkit/sh4/bin/sh4-linux-gcc -Wall ./static-files/source/wymod.c ./static-files/source/mongoose.c -ldl -lpthread -o ./static-files/wymodphpdaemon

mkdir -p usr/share/wymodcp-php/
cp -rp static-files/* usr/share/wymodcp-php/
find usr -type d -name ".svn" -exec rm -rf {} +
tar zcvf wymodcp-php.v1.0.0.tar.gz usr
rm -rf usr
