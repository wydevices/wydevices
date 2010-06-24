####################################################################
#  Htop 0.8.3  build script                                        #
#                                                                  #
#  Description:                                                    #
#    This script will generate a Htop package for a wydevice.      #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /wymedia            #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-htop-0.8.3.tar.gz             #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then
	rm -rf htop-0.8.3
	rm -f htop-0.8.3.tar.gz
	rm -f wybox-htop-0.8.3.tar.gz
	exit 0
fi

# get htop 0.8.3 and unpack it
if [ ! -f htop-0.8.3.tar.gz ]; then
	wget http://sourceforge.net/projects/htop/files/htop/0.8.3/htop-0.8.3.tar.gz/download || exit 1
fi
if [ ! -d htop-0.8.3 ]; then
	echo Extracting sources
	tar xvf htop-0.8.3.tar.gz || exit 1

	# patch source to avoid some compile errors
	cd $WDIR/htop-0.8.3
	patch -p1 < ../patches/htop-configure.patch || exit 1
fi

# configure and make
cd $WDIR/htop-0.8.3
if [ ! -f Makefile ]; then
	echo "Creating Makefile"
	export ac_cv_func_malloc_0_nonnull=yes
	export ac_cv_func_realloc_0_nonnull=yes
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
mkdir -p usr/lib/terminfo/l

# copy files
cp htop-0.8.3/htop usr/bin
cp /opt/STM/STLinux-2.3/devkit/sh4/target/lib/libncurses.so.5.5 usr/lib/libncurses.so.5
cp /opt/STM/STLinux-2.3/devkit/sh4/target/usr/share/terminfo/l/linux usr/lib/terminfo/l

# create tar
tar zcvf wybox-htop-0.8.3.tar.gz usr
rm -rf usr


