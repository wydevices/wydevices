Wybox-utils package
====================

Wybox-utils package contains some additional commands and scripts
that are usefull in wyboxes and are not included in default firmware

There is a script to build the final package that will do all the
work for you, you only have to edit 'commands.conf' file and put
there the commands that you want to include.

All scripts developed by wydev community should go on the
'scripts' folder. 


Building the package
---------------------

1. Edit 'commands.conf' file to modify the included commands.
2. ./build-utils.sh

Once finished, file wybox-utils.tar.gz contains all commands and
is ready to be installed in a wybox.


Source of the commands included
--------------------------------

Commands will be taken from STLinux dirs or the 'scripts' folder.
If command is not found then it will look for folder named like the
command containing build-*.sh script to build that command

Commands search follows this order.

  1. Look if command is present in STLiunx. If true
     copy binary and libraries required.

  2. Look for the command in the scripts folder.

  3. Look if there is a folder containing a build-*.sh
     script to build the command.


