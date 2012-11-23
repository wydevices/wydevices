####################################################################
#  Generic package build script                                    #
#                                                                  #
#  Description:                                                    #
#    This script will generate a package for a wydevice.           #
#    A 'build.config' must exist in the build directory.           #
#    It will download sources, compile and pack the results in a   #
#    file that can be directly installed under /                   #
#                                                                  #
#  Environment:                                                    #
#    This script should be run on a system with a STLinux 2.3      #
#    cross compiler installed.                                     #
#                                                                  #
#                                                                  #
#  Author: deniro666                                               #
#  Date: July 2010                                                 #
#  Modified by: minukab  Date: November 2012                       #
#                                                                  #
####################################################################

###################################################################
# TODO:
#
#  * Library search: first obtain all libs for all bins then search
#
###################################################################


function clean_all() {

	rm -rf `echo $SRCDIR | cut -d'/' -f1`
	rm -f $SRCFILE
	rm -f wybox-$NAME-$VERSION.tar.gz
	rm -rf $PKGDIR/rootdir

	# clean subpackages
	for PKG in $SUBPACKAGES; do
		echo "Cleaning $PKG"
		if [ -d $PKGDIR/$PKG ]; then
			SUBPKGDIR=$PKGDIR/$PKG
		else
			if [ -d $SCRIPTDIR/$PKG ]; then
				SUBPKGDIR=$SCRIPTDIR/$PKG
			else
				echo "ERROR: package $PKG not found"
				exit 1
			fi
		fi
		cd $SUBPKGDIR
		$SCRIPT cleanall || exit 1
	done

	# clean also dependencies
	for DEP in $DEPENDS; do
		echo "Cleaning $DEP"
		cd $PKGDIR/depends/$DEP
		$SCRIPT cleanall || exit 1
	done
	
	# run extra clean actions
	cd $PKGDIR
	if type CLEAN >/dev/null 2>&1; then
		CLEAN
	fi
}

function clean_wyboxfiles() {

	rm -f wybox-$NAME-$VERSION.tar.gz
	rm -rf $PKGDIR/rootdir

	# clean subpackages
	for PKG in $SUBPACKAGES; do
		echo "Cleaning $PKG"
		if [ -d $PKGDIR/$PKG ]; then
			SUBPKGDIR=$PKGDIR/$PKG
		else
			if [ -d $SCRIPTDIR/$PKG ]; then
				SUBPKGDIR=$SCRIPTDIR/$PKG
			else
				echo "ERROR: package $PKG not found"
				exit 1
			fi
		fi
		cd $SUBPKGDIR
		$SCRIPT clean || exit 1
	done

	# clean also dependencies
	for DEP in $DEPENDS; do
		echo "Cleaning $DEP"
		cd $PKGDIR/depends/$DEP
		$SCRIPT clean || exit 1
	done
}

function build_subpackages() {

	# remove previous files
	rm -rf $PKGDIR/rootdir

	# build subpackages
	for PKG in $SUBPACKAGES; do
		echo "Building $PKG"
		if [ -d $PKGDIR/$PKG ]; then
			SUBPKGDIR=$PKGDIR/$PKG
		else
			if [ -d $SCRIPTDIR/$PKG ]; then
				SUBPKGDIR=$SCRIPTDIR/$PKG
			else
				echo "ERROR: package $PKG not found"
				exit 1
			fi
		fi
		cd $SUBPKGDIR
		$SCRIPT wyboxfiles || exit 1
		echo "Copy $PKG files"
		mkdir -p $PKGDIR/rootdir/etc/
		cat $PKGDIR/rootdir/etc/versions \
			$SUBPKGDIR/rootdir/etc/versions 2>/dev/null \
			| grep -v "#" > $PKGDIR/rootdir/etc/versions.aux

		cp -r $SUBPKGDIR/rootdir $PKGDIR
		mv $PKGDIR/rootdir/etc/versions.aux $PKGDIR/rootdir/etc/versions
		echo "Finished building $PKG"
	done
}

function build_dependencies() {

	# build dependencies
	for DEP in $DEPENDS; do
		echo "Building dependency $DEP"
		if [ ! -d $PKGDIR/depends/$DEP ]; then
			echo "ERROR: package $DEP not found"
			exit 1
		fi
		cd $PKGDIR/depends/$DEP
		$SCRIPT sources || exit 1
		echo "Finished building dependency $DEP"
	done
}

function get_untar_patch() {

	# get source file and unpack it
	cd $PKGDIR
	if [ ! -f $SRCFILE ]; then
		wget $URL || exit 1
	fi
	if [ ! -d $SRCDIR ]; then
		echo Extracting sources
		if [ "`echo $SRCFILE |awk -F . '{print $NF}'`" = "zip" ]; then
			unzip $SRCFILE || exit 1
		else
			tar xvf $SRCFILE || exit 1
		fi

		# patch source if there are patches
		cd $PKGDIR/$SRCDIR
		cat $PKGDIR/patches/* 2>/dev/null | patch -p1 || exit 1
	fi
}

function configure_and_make() {

	# configure and make
	cd $PKGDIR/$SRCDIR
	if [ ! -f Makefile ] && [ ! -f makefile ]; then
		echo "Creating Makefile"
		eval ./configure $CONFIGUREFLAGS || exit 1
	else
		echo "Makefile exists, skipping configure"
	fi


	if ! make -q > /dev/null 2>&1; then
		echo "Compiling"
		NPROCS=`grep -c ^processor /proc/cpuinfo`
		make -j$NPROCS || exit 1
	else
		echo "Already compiled, skipping make"
	fi
}

function build_sources() {

	# run pre-compilation actions
	if [ $URL ]; then
		get_untar_patch
	fi
		
	cd $PKGDIR
	if type PRE_COMPILATION >/dev/null 2>&1; then
		PRE_COMPILATION
	fi
	
	build_dependencies

	if [ $SRCDIR ]; then
				configure_and_make
	fi

	cd $PKGDIR
	if type POST_COMPILATION >/dev/null 2>&1; then
		POST_COMPILATION
	fi
}

function generate_wyboxfiles() {
			 
#	copy_bins
#	copy_libs
#	copy_static
#	generate_tar	

	echo "Generating wybox files"
	cd $PKGDIR
	
	# run pre package build actions
	cd $PKGDIR
	if type PRE_WYBOX_PKG_BUILD >/dev/null 2>&1; then
		PRE_WYBOX_PKG_BUILD
	fi

	# create directories
	if [ "$PACKAGEDIRS" ]; then
		mkdir -p $PACKAGEDIRS
	fi

	# copy binary files
	mkdir -p $PKGDIR/rootdir/usr/bin
	LDD=/opt/STM/STLinux-2.3/host/bin/ldd
	BUILTLIBSPATH=`find -L . -name "*.so*" -exec dirname {} \; | uniq | tr '\n' ':'`
	if [ $BUILTLIBSPATH ]; then
		export LD_LIBRARY_PATH=$BUILTLIBSPATH/opt/STM/STLinux-2.3/devkit/sh4/target/lib/:/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib
	else
		export LD_LIBRARY_PATH=/opt/STM/STLinux-2.3/devkit/sh4/target/lib/:/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib
	fi
	TARJETPATH="/opt/STM/STLinux-2.3/devkit/sh4/target/lib /opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib"
	SEARCHPATH="$PKGDIR/scripts /opt/STM/STLinux-2.3/devkit/sh4/target/"
	if [ $SRCDIR ]; then
		SEARCHPATH="$PKGDIR/$SRCDIR $SEARCHPATH"
	fi
	for BIN in $BINARIES; do
		echo "  Getting required files for $BIN"
		LOCATION=`find $SEARCHPATH -type f -perm -u+x -name "$BIN" 2>/dev/null`
		if [ ! "$LOCATION" ]; then
			echo "ERROR: file $BIN not found"
			exit 1
		fi
		for BINFOUND in $LOCATION; do
			if file $BINFOUND | grep ELF >/dev/null 2>&1; then
				LOCATION=$BINFOUND
				break
			fi
		done
		cp -f $LOCATION $PKGDIR/rootdir/usr/bin
		chmod +x $PKGDIR/rootdir/usr/bin/$BIN
		sh4-linux-strip $PKGDIR/rootdir/usr/bin/$BIN
		# install required libs
		for LIB in `$LDD $LOCATION 2>/dev/null | awk '{ print $1 }' | grep .so`; do
			LIBNAME=`basename $LIB`
			if ! cat $SCRIPTDIR/wybox-libs.lst | grep $LIBNAME >/dev/null 2>&1; then
				# library is not present in wybox firmwares
				echo "    $LIBNAME"
				LIBLOCATION=`find -L $SRCDIR $PKGDIR/depends $TARJETPATH -name "$LIBNAME" 2>/dev/null`
				if [ ! "$LIBLOCATION" ]; then
					echo "ERROR: file $LIBNAME not found"
					exit 1
				fi
				LIBLOCATION=`echo $LIBLOCATION | awk '{ print $1 }'`
				mkdir -p $PKGDIR/rootdir/usr/lib
				cp -f $LIBLOCATION $PKGDIR/rootdir/usr/lib
				sh4-linux-strip $PKGDIR/rootdir/usr/lib/$LIBNAME
			fi
		done
	done

	# copy static and config files
	if [ -d $PKGDIR/static-files ]; then
		cp -rp $PKGDIR/static-files/* $PKGDIR/rootdir/
	fi
	if [ -d $PKGDIR/config-files ]; then
		cp -rp $PKGDIR/config-files/* $PKGDIR/rootdir/
	fi
	
	# delete .svn dirs if they are present
	find $PKGDIR/rootdir -type d -name ".svn" -exec rm -rf {} +
	
	# version file
	mkdir -p $PKGDIR/rootdir/etc/
	if [ -f $PKGDIR/rootdir/etc/versions ]; then
		mv $PKGDIR/rootdir/etc/versions $PKGDIR/rootdir/etc/versions.subpkgs
		echo "# Built on `date -R`" > $PKGDIR/rootdir/etc/versions
		echo "#" >> $PKGDIR/rootdir/etc/versions
		echo $NAME-$VERSION >> $PKGDIR/rootdir/etc/versions
		cat $PKGDIR/rootdir/etc/versions.subpkgs | awk '{ print "|-- "$0 }' >> $PKGDIR/rootdir/etc/versions
		rm -f $PKGDIR/rootdir/etc/versions.subpkgs
	else
		echo "# Built on `date -R`" > $PKGDIR/rootdir/etc/versions
		echo "#" >> $PKGDIR/rootdir/etc/versions
		echo $NAME-$VERSION >> $PKGDIR/rootdir/etc/versions
	fi

}

function generate_wyboxpkg() {

	echo "Creating wybox package"

	# create tar
	if [ $NAME ] && [ $VERSION ]; then
		cd rootdir
		fakeroot tar zcf ../wybox-$NAME-$VERSION.tar.gz ./*
	fi
	echo "Success: Final package wybox-$NAME-$VERSION.tar.gz has been created."
}

#function rsync_to_wybox() {
#	
#	# rsync usr dir to rsync://ip/devel
#	# $1 should be wybox IP
#	
#	echo "Transfering files to wybox with rsync"
#	
#	rsync -a --progress usr/ rsync://$1/devel || exit 1
#	echo "Success! Transfer to wybox complete."
#}

CONFIGFILE=build.config
SCRIPT=`readlink -f $0`
SCRIPTDIR=`dirname $SCRIPT`
PKGDIR=`pwd`

export CROSS_COMPILE=1
export CC="sh4-linux-gcc"
export CXX="sh4-linux-g++"
export PATH=/opt/STM/STLinux-2.3/devkit/sh4/bin:$PATH
export PKG_CONFIG_PATH=/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/pkgconfig

if [ ! -f $PKGDIR/$CONFIGFILE ]; then
	echo "ERROR: $CONFIGFILE file not found"
	exit 1
fi

if ! which sh4-linux-gcc >/dev/null 2>&1; then
	echo "ERROR: SH4 cross-compiler not detected"
	exit 1
fi

if ! which fakeroot >/dev/null 2>&1; then
	echo "ERROR: You need to install fakeroot: yum install fakeroot"
	exit 1
fi


source $PKGDIR/$CONFIGFILE

case "$1" in
	sources)
		build_sources
		;;
	wyboxfiles)
		build_subpackages
		build_sources
		generate_wyboxfiles
		;;
	wyboxpkg)
		build_subpackages
		build_sources
		generate_wyboxfiles
		generate_wyboxpkg
		;;
#	transfer)
#		if [ "$2" = "" ]; then
#			echo "ERROR: An IP must be specified.."
#			exit 1
#		fi
#		build_subpackages
#		build_sources
#		generate_wyboxfiles
#		rsync_to_wybox $2
#		;;
	clean)
		clean_wyboxfiles
		;;
	cleanall)
		clean_all
		;;
	"")
		echo "Use: `basename $0` TARJET"
		echo "Available tarjets"
		echo "  * sources : only download and compile"
		echo "  * wyboxfiles : generate 'usr' folder"
		echo "  * wyboxpkg (tar.gz) : generate tar.gz file"
#		echo "  * transfer IP: transfer usr folder to rsync://ip/devel"
		echo "  * clean (tar.gz and usr)"
		echo "  * cleanall (all)"
		;;
	*)
		echo "ERROR: Invalid option"
		;;
esac
