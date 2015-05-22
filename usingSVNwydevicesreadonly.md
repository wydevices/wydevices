# Introduction #

Steps to perform to start building with SVN wydevices-read-only


# Details #

**install fakeroot (Needs root)**
```
yum install fakeroot
```

**Open the terminal and create the SVN Folder**
```
mkdir SVN
cd SVN
```
**download SVN contents for non-member users**
```
svn checkout http://wydevices.googlecode.com/svn/trunk/ wydevices-read-only
```
**go to packages folder**
```
cd wydevices-read-only/
cd packages/
```
**set up enviroment**
```
export PATH=/opt/STM/STLinux-2.3/devkit/sh4/bin:$PATH
export PKG_CONFIG_PATH=/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/pkgconfig
```
**run build.sh**
```
sh build.sh
```

_expected out_

```
Use: build.sh TARJET
Available tarjets
  * sources : only download and compile
  * wyboxfiles : generate 'usr' folder
  * wyboxpkg (tar.gz) : generate tar.gz file
  * transfer IP: transfer usr folder to rsync://ip/devel
  * clean (tar.gz and usr)
  * cleanall (all)
```

**Now, you can start with it, for example:**
```
cd extras  
```
or
```
cd pure-ftpd 
```
or
```
cd transmission 
```
[...]

**then you can execute build.sh from target parent folder, it will use build.config of the local folder to fit the package needs**

```
..\build.sh sources
..\build.sh wyboxfiles
..\build.sh wyboxpkg
..\build.sh transfer <_your wydevice ipaddress_>
..\build.sh clean
..\build.sh cleanall
```

**you can create a new folder for your next package and use build.config.example to define its own needs.**