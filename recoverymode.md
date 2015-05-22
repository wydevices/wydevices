When something goes wrong with your rootfs, no need to reinstall a firmware.
You can boot on the alternative firmware, and acces the corrupted rootfs to repare it.


Here the necessary step:


I - Boot on alternative firmware.

Create a file named BASE\_FIRMWARE on a usb-stick and boot your box with this usb-stick inserted.


II - Determine the partition of corrupted rootfs.

Open a telnet session and type:
ls /sys/block/dm-0/slaves

If "sda6" is printed the corrupted rootfs is "/dev/sda8".
If "sda8" is printed the corrupted rootfs is "/dev/sda6".


III - Acces the corrupted rootfs.

On telent type:
mkdir /wymedia/rootfs
fsck.jfs /dev/sda**(where "sda**" is the corrupted rootfs partition)
mount /dev/sda**/wymedia/rootfs (where "sda**" is the corrupted rootfs partition)

You can now acces the corrupted rootfs through "/wymedia/rootfs" with samba or ftp, and make any modifications.


IV - Clean up after modifications.

Don't forget to unmount the partition before rebooting.
For that, on telnet type:
umount /wymedia/rootfs


You can now reboot your box.


Best regards

Polo