Wybox-utils package
====================

Wybox-utils package contains some additional commands and scripts
that are usefull in wyboxes and are not included in default firmware

There is a script to build the final package that will do all the
work for you, you only have to edit 'build.config' file and put
there the commands that you want to include.

All scripts developed by wydev community should go on the
'scripts' folder. 


Building the package
---------------------

1. Edit 'build.conf' file to modify the included commands.
2. Run build.sh script

Once finished, file wybox-utils.tar.gz contains all commands and
is ready to be installed in a wybox.

