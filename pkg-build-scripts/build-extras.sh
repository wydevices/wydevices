####################################################################
#  Wybox-extras build script                                       #
#                                                                  #
#  Description:                                                    #
#    This script will generate a extras package for a wydevice.    #
#                                                                  #
#    It will read 'pkg.conf' file to see what packages             #
#    to include in the final package.                              #
#                                                                  #
#    Packages indicated in 'pkg.conf' must have a folder           #
#    contaning a build-*.sh script and that script must genrate    #
#    a wybox-*.tar.gz packge in the root of that folder.           #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#  Generated package filename: wybox-extras.tar.gz                 #
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
	rm -f $WDIR/wybox-extras.tar.gz
	rm -rf $WDIR/usr
	exit 0
fi

# delete destination directory
rm -rf $WDIR/usr

while read PKG; do
	# filter comment lines
	PKG=`echo $PKG | sed '/^ *#/d;s/#.*//'`
	if [ "$PKG" = "" ]; then
		continue
	fi
	echo "Copy files for $PKG"
	if [ ! -f $WDIR/$PKG/build-*.sh ]; then
		echo "ERROR: package $PKG not found"
		exit 1
	fi
	cd $WDIR/$PKG
	./build-*.sh || exit 1
	tar zxf $WDIR/$PKG/wybox-*.tar.gz -C $WDIR
done < $WDIR/pkgs.conf

# create wybox package
echo "Creating wybox-extras package"
cd $WDIR
tar zcf wybox-extras.tar.gz usr
rm -rf usr

