#!/bin/sh
echo "<html><head><title>WyMod Control Panel</title><link href="../css/wymod.css" type="text/css" rel="stylesheet"></head><body>"
echo "<br><pre>" 
echo "<b>..:: Temperature ::..</b>" 
echo "</pre><br><pre>" 
temp  
echo "</pre><br><pre>" 
echo "<b>..:: Network Settings ::..</b>" 
echo "</pre><br><pre>" 
ifconfig 
echo "</pre><br><pre>" 
echo "<b>..:: Wireless Settings ::..</b>" 
echo "</pre><br><pre>" 
iwconfig 
echo "</pre><br><pre>" 
echo "<b>..:: Filesystem ::..</b>" 
echo "</pre><br><pre>" 
df -h 
echo "</pre><br>" 
echo "captured on: " 
date 
echo "</body></html>" 
