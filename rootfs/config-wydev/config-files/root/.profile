export OUTPUT=/log_initng.txt

export LD_LIBRARY_PATH="/wymedia/usr/lib"
export PATH="/wymedia/usr/bin:$PATH"
export TERMINFO_DIRS="/wymedia/usr/lib/terminfo"
export HOME="/wymedia"
export TERM=linux
alias PiDo="ssh `less /wymedia/usr/etc/pydev-pi-username`@`less /wymedia/usr/etc/pydev-pi-ip`"
