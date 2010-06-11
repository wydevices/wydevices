#!/bin/sh
#echo "<html><head><title>WyMod Control Panel</title></head><body>"
echo "<br><pre>" 
echo "<b>..:: Network Settings ::..</b>" 
echo "</pre><br><pre>" 
ifconfig 
echo "</pre><br><pre>" 
echo "<b>..:: Wireless Settings ::..</b>" 
echo "</pre><br><pre>" 
iwconfig
echo "<br>"
cat /proc/net/wireless
 
echo "</pre><br><pre>" 
echo "<b>..:: Filesystem ::..</b>" 
echo "</pre><br><pre>" 
df -h 
echo "</pre><br><pre>" 
echo "<b>..:: CPU Info ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/cpuinfo
echo "<br>"
cat /proc/sys/kernel/osrelease

echo "</pre><br><pre>" 
echo "<b>..:: Memory Info ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/meminfo

echo "</pre><br><pre>" 
echo "<b>..:: Crypt Info ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/crypto

echo "</pre><br><pre>" 
echo "<b>..:: CoProcessor Info ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/coprocessor

echo "</pre><br><pre>" 
echo "<b>..:: CMD Line ::..</b>" 
echo "</pre><br>" 
cat /proc/cmdline

echo "<br><pre>" 
echo "<b>..:: Kernel Version ::..</b>" 
echo "</pre><br>" 
cat /proc/version
echo "<br>"
cat /proc/sys/kernel/version

echo "</pre><br><pre>" 
echo "<b>..:: Sound Devices ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/asound/cards
cat /proc/asound/oss/sndstat

echo "</pre><br><pre>" 
echo "<b>..:: USB Devices ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/bus/usb/devices

echo "</pre><br><pre>" 
echo "<b>..:: TTY ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/tty/drivers

echo "</pre><br><pre>" 
echo "<b>..:: Kernel Version ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/version

echo "</pre><br><pre>" 
echo "<b>..:: Sound Devices ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/asound/cards
cat /proc/asound/oss/sndstat

echo "</pre><br><pre>" 
echo "<b>..:: USB Devices ::..</b>" 
echo "</pre><br><pre>" 
cat /proc/bus/usb/devices



echo "</pre><br>"
echo "captured on: " 
date 
echo "</body></html>" 
