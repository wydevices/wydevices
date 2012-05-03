#!/bin/sh

empty -f -i in -o out -L /wymedia/usr/var/log/empty_log telnet 127.0.0.1 31337
empty -s -o in "$1\n"
empty -s -o in "DISCONNECT\n"
