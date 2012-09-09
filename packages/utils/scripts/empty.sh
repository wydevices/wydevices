#!/bin/sh
#
# Usage : empty.sh command
#
empty -f -i /tmp/in -o /tmp/out -L /wymedia/usr/var/log/empty_log telnet 127.0.0.1 31337
empty -s -o /tmp/in "$1\n"
empty -s -o /tmp/in "DISCONNECT\n"
