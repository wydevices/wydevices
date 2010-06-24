####################################################################
#  Wybox-utils build script                                        #
#                                                                  #
#  Description:                                                    #
#    This script will generate a utilites package for a wydevice.  #
#                                                                  #
#    It will read 'commands.conf' file to see what commands        #
#    to include in the final package.                              #
#                                                                  #
#    Commands search follows this order:                           #
#                                                                  #
#      1. Look if command is present in STLiunx. If true           #
#         copy binary and libraries required.                      #
#                                                                  #
#      2. Look for the command in the scripts folder.              #
#                                                                  #
#      3. Look if there is a folder named as the command           #
#         containing a build-*.sh script to build the command.     #
#                                                                  #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-utils.tar.gz                  #
#                                                                  #
#  Author: deniro666                                               #
#  Date: June 2010                                                 #
#                                                                  #
####################################################################

WDIR=`pwd`

if [ "$1" == "clean" ]; then

	for PKGDIR in `find $WDIR -mindepth 1 -maxdepth 1 -type d`; do
		if [ -f $PKGDIR/build-*.sh ]; then
			echo "Cleaning `basename $PKGDIR`"
			cd $PKGDIR
			./build-*.sh clean
		fi
	done
	echo "Cleaning rest of files"
	rm -f $WDIR/wybox-utils.tar.gz
	rm -rf $WDIR/usr
	exit 0
fi

#  create dirs
rm -rf $WDIR/usr
mkdir -p $WDIR/usr/bin
mkdir -p $WDIR/usr/lib


LDD=/opt/STM/STLinux-2.3/host/bin/ldd
export LD_LIBRARY_PATH=/opt/STM/STLinux-2.3/devkit/sh4/target/lib/:/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib
TARJETPATH=/opt/STM/STLinux-2.3/devkit/sh4/target/
SEARCHPATH="$TARJETPATH $WDIR/scripts"

# for each command look for it and copy required files
while read CMD; do
	# filter comment lines
	CMD=`echo $CMD | sed '/^ *#/d;s/#.*//'`
	if [ "$CMD" = "" ]; then
		continue
	fi
	echo "Getting required files for $CMD"
	LOCATION=`find $SEARCHPATH -type f -name "$CMD" 2>/dev/null`
	if [ "$LOCATION" = "" ]; then
		# command is not found, check if we can build it
		if [ ! -f $WDIR/$CMD/build-$CMD.sh ]; then
			echo "ERROR: $CMD command not found"
			exit 1
		fi
		echo "Building $PKG"
		cd $WDIR/$CMD
		./build-$CMD.sh || exit 1
		echo "Extracting $CMD"
		cd $WDIR
		tar zxf $WDIR/$CMD/wybox-$CMD*.tar.gz
	else
		# command has been found
		cp -f $LOCATION $WDIR/usr/bin
		chmod +x $WDIR/usr/bin/$CMD
		# install required libs
		for LIB in `$LDD $LOCATION 2>/dev/null | awk '{ print $1 }' | grep .so`; do
			LIBNAME=`basename $LIB`
			if ! cat $WDIR/wybox-libs.lst | grep $LIBNAME >/dev/null 2>&1; then
				# library is not present in wybox firmwares
				echo "   $LIBNAME"
				LIBLOCATION=`find $TARJETPATH -type f -name "$LIBNAME" 2>/dev/null`
				cp -f $LIBLOCATION $WDIR/usr/lib
			
			fi
		done
	fi

done < $WDIR/commands.conf

# create wybox package
echo "Creating wybox-utils package"
cd $WDIR
tar zcf wybox-utils.tar.gz usr
rm -rf usr

