Wydevices are cross-compiled with STLinux 2.3.

The better way is to compile all mods with the same cross-compiler.

Here my howto for cross-compile transmission-2.13.


**Prerequisites :**
  * Follow and terminate with success the STLinux 2.3 installation.

**Instructions :**
  * In your Fedora 11 / STLinux 2.3 virtual machine.
  * Run this command :
```
$ su - (enter root password)
# mkdir /wymedia
# mkdir /wymedia/usr
# chmod 777 -R /wymedia
# exit
$ cd $HOME
$ wget http://monkey.org/~provos/libevent-1.4.14b-stable.tar.gz
$ wget http://mirrors.m0k.org/transmission/files/transmission-2.13.tar.bz2
$ tar xvzf libevent-1.4.14b-stable.tar.gz
$ tar xvjf transmission-2.13.tar.bz2
```

  * Prepare your environment variable to crosscompile :
```
$ export CROSS_COMPILE=1
$ export CC="sh4-linux-gcc"
$ export CXX="sh4-linux-g++"
$ export PATH=/opt/STM/STLinux-2.3/devkit/sh4/bin:$PATH
$ export PKG_CONFIG_PATH=/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/pkgconfig
```

  * Compile the libevent dependancy :
```
$ cd libevent-1.4.14-stable
$ ./configure --host="sh4-linux" --prefix="/wymedia/usr"
$ make
$ make install
$ su - (enter root password)
# cp /wymedia/usr/bin/event_rpcgen.py /opt/STM/STLinux-2.3/devkit/sh4/target/usr/bin/
# cp /wymedia/usr/include/ev* /opt/STM/STLinux-2.3/devkit/sh4/target/usr/include/
# cp /wymedia/usr/lib/libevent* /opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/
# exit
```

  * Compile Transmission-2.13 :
```
$ cd transmission-2.13
$ ./configure --host="sh4-linux" --prefix="/wymedia/usr" --enable-daemon --disable-nls --disable-mac --disable-gtk --disable-libappindicator --disable-libcanberra --with-gnu-ld
$ make
$ make install
$ cd /wymedia
$ tar czf transmission-2.13_sh4.tar.gz usr/
```

**Installation of your Transmission-2.13 :**
  * !!! You must have wydev-mod-v4.1 or later installed correctly !!!
  * transfert your transmission-2.13\_sh4.tar.gz to /wymedia on your wydevice via ftp or samba.
  * connect to your wydevice via telnet.
  * launch this commands on your wydevice :
```
$ extras stop transmission
$ cd /wymedia
$ tar xvzf transmission-2.13_sh4.tar.gz
$ extras start transmission
```