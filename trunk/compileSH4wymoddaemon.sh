#!/bin/sh

TOOLCHAIN=/opt/crosstool/gcc-4.1.1-glibc-2.6.1/sh4-unknown-linux-gnu/bin
#TOOLCHAIN=/ZTV/opt/crosstool/gcc-4.1.1-glibc-2.6.1/sh4-unknown-linux-gnu/bin/

# Possible COPT values: (in brackets are rough numbers for 'gcc -O2' on i386)
# -DHAVE_MD5		- use system md5 library (-2kb)
# -DNDEBUG		- strip off all debug code (-5kb)
# -DDEBUG		- build debug version (very noisy) (+7kb)
# -DNO_CGI		- disable CGI support (-5kb)
# -DNO_SSL		- disable SSL functionality (-2kb)
# -DCONFIG_FILE=\"file\" - use `file' as the default config file
# -DNO_SSI		- disable SSI support (-4kb)
# -DHAVE_STRTOUI64	- use system strtoui64() function for strtoull()

PROG="wymoddaemon"
COPT="-DNDEBUG -DNO_SSL"
CFLAGS="-Wall -Os -fomit-frame-pointer $COPT"
LINFLAGS="-D_POSIX_SOURCE -D_BSD_SOURCE -D_FILE_OFFSET_BITS=64 -D_LARGEFILE_SOURCE -ldl -lpthread $CFLAGS"

$TOOLCHAIN/sh4-unknown-linux-gnu-gcc-4.1.1 $LINFLAGS wymod.c mongoose.c -o $PROG
