**Prerequisites :**
  * Follow and terminate with success the STLinux 2.3 installation.
  * You must have the directory /wymedia
  * Verify that you have mercurial installed.


# Compile your PHP5 #
  * In your Fedora 11 / STLinux 2.3 virtual machine.
  * Install libxml2-dev package on your Fedora 11 host system.
  * Download latest PHP 5.2 source code at http://www.php.net/downloads.php#v5 in your $HOME directory.
  * Unpack the source code and enter in this directory with a terminal (for exemple : `$ cd $HOME/php-5.2.13`).
  * Execute this commands for prepare cross-compilation :
```
$ export CROSS_COMPILE=1
$ export CC="sh4-linux-gcc"
$ export CXX="sh4-linux-g++"
$ export PATH=/opt/STM/STLinux-2.3/devkit/sh4/bin:$PATH
$ export PKG_CONFIG_PATH=/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/pkgconfig
```
  * Run this command for prepare compilation :
```
$ ./configure --host="sh4-linux" --prefix="/wymedia/usr" \
--enable-fastcgi --enable-dba --enable-calendar \
--disable-cli --disable-ipv6 \
--with-libxml-dir=/opt/STM/STLinux-2.3/devkit/sh4/target/usr
```
  * There is a problem with pdo-sqlite if you launch compile now, for fix that, edit Makefile and go to the line ~78 named EXTRA\_LIBS and add -ldl at the end of this line.
  * Second problem, libxml2 dependency aren't correct path, for the moment you can fix by renaming this file during compilation :
```
$ su -
# mv /usr/lib/libxml2.so /usr/lib/libxml2.so.bak
# exit
```
  * And now, you can run :
```
$ make
$ make install
$ su -
# mv /usr/lib/libxml2.so.bak /usr/lib/libxml2.so
# exit
```
  * It produce binaries and scripts that must be copied into the same directory on your wydevice :
```
/wymedia/usr/bin/php-cgi
/wymedia/usr/bin/php-config
/wymedia/usr/bin/phpize
```



# Mongoose web server #
  * In your Fedora 11 / STLinux 2.3 virtual machine.
  * Download latest mongoose development source code with mercurial client :
```
$ cd $HOME
$ hg clone https://mongoose.googlecode.com/hg/ mongoose
```

  * Create a script named "compil\_mongoose.sh" into the $HOME/mongoose directory.
  * Place the following lines into it.
```
#!/bin/sh
TOOLCHAIN="/opt/STM/STLinux-2.3/devkit/sh4/bin"
STLINUX="/opt/STM/STLinux-2.3/host/bin"
PROG="mongoose"
COPT="-DNDEBUG -DNO_SSL"
CFLAGS="-Wall -Os -fomit-frame-pointer $COPT"
LINFLAGS="-D_POSIX_SOURCE -D_BSD_SOURCE -D_FILE_OFFSET_BITS=64 -ldl -lpthread $CFLAGS"
$TOOLCHAIN/sh4-linux-gcc $LINFLAGS main.c mongoose.c -o $PROG
```

  * Run this command :
```
$ cd $HOME/mongoose
$ chmod +x compil_mongoose.sh
$ ./compil_mongoose.sh
```

  * You must have a mongoose binary file in the directory $HOME/mongoose, place this binary into /wymedia/usr/bin on you wydevices and put execution right via a "chmod +x /wymedia/usr/bin/mongoose".
  * On your wydevices, create launch : `$ nano /wymedia/usr/etc/mongoose.conf`
  * Put this lines into the mongoose.conf :
```
# Mongoose web server configuration file.
# Lines starting with '#' and empty lines are ignored.
# For detailed description of every option, visit
# http://code.google.com/p/mongoose/wiki/MongooseManual

root            /wymedia/usr/share/wymod
ports           8080
max_threads     100
access_log      /wymedia/usr/var/log/mongoose_access.log
error_log       /wymedia/usr/var/log/mongoose_error.log
cgi_interp      /wymedia/usr/bin/php-cgi
cgi_ext         cgi,php
ssi_ext         shtml,shtm
index_files     index.html,index.htm,index.php,index.cgi
# dir_list      no
# acl           -0.0.0.0/0,+10.0.0.0/8,+192.168.0.0/16
# cgi_env       FOO=BAR,BAZ=POO
# idle_time     10
```



# Run a sample PHP script #
  * On your wydevice, create a file named sample.php into /wymedia/usr/share/wymod/ that contain this following lines :
```
<?php
	$dbh = new PDO('sqlite:/etc/params/wyscan/wyscan.db');
	$sql = 'SELECT LOGICAL_CHANNEL_NUMBER, NAME FROM T_SERVICE ORDER BY LOGICAL_CHANNEL_NUMBER ASC';
	foreach ($dbh->query($sql) as $row) {
		echo $row['LOGICAL_CHANNEL_NUMBER']." - ".$row['NAME']."<br />";
	}
?>
```
  * Now, verify that you aren't running wymoddaemon via a `$ ps ax | grep wymoddaemon`, else kill the process.
  * You are ready to launch your web server via a `$ mongoose /wymedia/usr/etc/mongoose.conf`
  * On a web browser, type http://wydevice_ip_address/sample.php and show your channel numbers and name.