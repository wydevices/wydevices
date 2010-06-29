####################################################################
#  CONFIG build script                                             #
#                                                                  #
#  Description:                                                    #
#    This script will generate configurations for a wydevice       #
#    It will pack the results in a                                 #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-config-1.0.tar.gz                   #
#                                                                  #
#  Authors: argos, deniro666, minukab                              #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
        rm -f wybox-config-*.tar.gz
        exit 0
fi

# create wybox package
echo "Creating wybox package"
cd $WDIR

# create directories
mkdir -p usr

# copy static files
cp -rp static-files/* usr/
# delete .svn dirs if they are present
find usr -type d -name ".svn" -exec rm -rf {} +

# create tar
tar zcvf wybox-config-1.0.tar.gz usr
rm -rf usr
