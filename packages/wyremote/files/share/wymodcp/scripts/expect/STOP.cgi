#!/wymedia/usr/bin/expect 
spawn telnet 127.0.0.1 31337
send "STOP\r"
expect "OK"
send "DISCONNECT\r"
expect "OK"