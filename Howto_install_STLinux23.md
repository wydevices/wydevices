Wydevices are cross-compiled with STLinux 2.3.

The better way is to compile all mods with the same cross-compiler.

Here my howto for install a cross-compiler platorm.


**This howto use :**
  * VMware Player v3 or later (you can launch this program on any operating system).
  * Fedora 11 32bits.
  * STLinux 2.3.

**Note :**
> The root password for this Fedora11 is "STL/nux".

**Necessary packages :**
  * Download and install the free VMware Player v3 from http://www.vmware.com/products/player/ (~70 Mo)
  * Download and uncompact [ftp://ftp.stlinux.com/pub/vmware/40G-fedora11.zip](ftp://ftp.stlinux.com/pub/vmware/40G-fedora11.zip) (~650 Mo)

  * Launch your Fedora 11 virtual machine and terminate installation.
  * Open a terminal and enter in a root shell for the next steps.
```
$ su
(enter root password)
```
  * Disable Fedora updates
```
# mv /etc/yum.repos.d/fedora-updates.repo /etc/yum.repos.d/fedora-updates.repo.off
```
  * Install some additional Fedora packages
```
# yum install wget gcc patch subversion
```
  * Install STLinux 2.3 packages
```
# wget ftp://ftp.stlinux.com/pub/stlinux/2.3/install
# chmod +x install
# ./install all-sh4-glibc
```

  * Wait for download and install. After installation, update your STLinux 2.3 to the last packages revision
```
# /opt/STM/STLinux-2.3/host/bin/stmyum update
```

Yes, you are now ready for cross-compilation. Every time you cross compile you must set the following environment variables:

```
$ export PATH=/opt/STM/STLinux-2.3/devkit/sh4/bin:$PATH
$ export PKG_CONFIG_PATH=/opt/STM/STLinux-2.3/devkit/sh4/target/usr/lib/pkgconfig
```

_Thanks to deniro666 for his help !_

_NOTE: Original by argos, modified by deniro666_