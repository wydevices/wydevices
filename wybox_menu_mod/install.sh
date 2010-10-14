#!/bin/sh
# Installation script for modified Wydevice python GUI

# Copyright 2010, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.

# Internationalization compilation use "msgfmt.py" file from Python3.0 source.


dir=`pwd`


# Saving original python files
echo "Saving original python files..."
cp /usr/lib/python2.5/site-packages/pygui/action/core.pyo /usr/lib/python2.5/site-packages/pygui/action/core.pyo_bak
cp /usr/lib/python2.5/site-packages/pygui/facilities/turlututube.pyo /usr/lib/python2.5/site-packages/pygui/facilities/turlututube.pyo_bak
cp /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo_bak

# Copying new python files
echo "Copying new python files..."
cp -r $dir/pygui /usr/lib/python2.5/site-packages


# Saving original xml files
echo "Saving original xml files..."
cp /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml_bak

# Copying new common xml files
echo "Copying new common xml files..."
cp -r $dir/xml/common/menus /usr/share/pygui/skins/wybox/xml

# Checking installed skin
echo "Checking installed skin..."
SKIN_DATE=`ls -al /wymedia/Perso/test_skin/ZoltarTV/ObjectBiblio.xml | sed "s/ * / /g" | cut -d " " -f 6-8`

# Copying new specific xml files
echo "Copying new specific xml files..."
case "$SKIN_DATE" in
	"Oct 22 2009" )
		echo "MediaTitan skin found"
		cp -r $dir/xml/specific/mediatitan/menus /usr/share/pygui/skins/wybox/xml
		;;
	"Sep 4 2009" )
		echo "WyPlayer skin found"
		cp -r $dir/xml/specific/wyplayer/menus /usr/share/pygui/skins/wybox/xml
		;;
	"Sep 14 2009" )
		echo "ZoltarTV skin found"
		cp -r $dir/xml/specific/zoltartv/menus /usr/share/pygui/skins/wybox/xml
		;;
	* )
		echo "ERROR: SKIN PROBLEM !!!"
		echo "Modified menu will NOT work properly !!!"
		;;
esac


# Saving original internationalization files
echo "Saving original internationalization files..."
cp /usr/share/pygui/locale/de/LC_MESSAGES/messages.mo /usr/share/pygui/locale/de/LC_MESSAGES/messages.mo_bak
cp /usr/share/pygui/locale/en/LC_MESSAGES/messages.mo /usr/share/pygui/locale/en/LC_MESSAGES/messages.mo_bak
cp /usr/share/pygui/locale/es/LC_MESSAGES/messages.mo /usr/share/pygui/locale/es/LC_MESSAGES/messages.mo_bak
cp /usr/share/pygui/locale/fr/LC_MESSAGES/messages.mo /usr/share/pygui/locale/fr/LC_MESSAGES/messages.mo_bak
cp /usr/share/pygui/locale/it/LC_MESSAGES/messages.mo /usr/share/pygui/locale/it/LC_MESSAGES/messages.mo_bak
cp /usr/share/pygui/locale/nl/LC_MESSAGES/messages.mo /usr/share/pygui/locale/nl/LC_MESSAGES/messages.mo_bak
cp /usr/share/pygui/locale/pt/LC_MESSAGES/messages.mo /usr/share/pygui/locale/pt/LC_MESSAGES/messages.mo_bak


# Compiling new internationalization files
echo "Compiling new internationalization files..."
python2.5 -O $dir/locale/msgfmt.py -o /usr/share/pygui/locale/de/LC_MESSAGES/messages.mo $dir/locale/de/LC_MESSAGES/messages.po
python2.5 -O $dir/locale/msgfmt.py -o /usr/share/pygui/locale/en/LC_MESSAGES/messages.mo $dir/locale/en/LC_MESSAGES/messages.po
python2.5 -O $dir/locale/msgfmt.py -o /usr/share/pygui/locale/es/LC_MESSAGES/messages.mo $dir/locale/es/LC_MESSAGES/messages.po
python2.5 -O $dir/locale/msgfmt.py -o /usr/share/pygui/locale/fr/LC_MESSAGES/messages.mo $dir/locale/fr/LC_MESSAGES/messages.po
python2.5 -O $dir/locale/msgfmt.py -o /usr/share/pygui/locale/it/LC_MESSAGES/messages.mo $dir/locale/it/LC_MESSAGES/messages.po
python2.5 -O $dir/locale/msgfmt.py -o /usr/share/pygui/locale/nl/LC_MESSAGES/messages.mo $dir/locale/nl/LC_MESSAGES/messages.po
python2.5 -O $dir/locale/msgfmt.py -o /usr/share/pygui/locale/pt/LC_MESSAGES/messages.mo $dir/locale/pt/LC_MESSAGES/messages.po


# Modifying 'use_serialized_theme' parameter in 'local_conf.py' file
echo "Updating local_conf.py file..."
if [ -f /etc/local_conf.py ]; then
  cp /etc/local_conf.py /etc/local_conf.py_bak
  if [ -f /etc/local_conf_tmp.py ]; then
    rm /etc/local_conf_tmp.py
  fi
  while read line
    do
      if [ -n "`echo ${line} | grep -E \"use_serialized_theme\"`" ]; then
	echo  "use_serialized_theme = False" >> /etc/local_conf_tmp.py
      else
	echo  $line >> /etc/local_conf_tmp.py
      fi
    done < /etc/local_conf.py
  rm /etc/local_conf.py
  mv /etc/local_conf_tmp.py /etc/local_conf.py
fi


# Rebooting Wydevice
echo "Install done."
echo "Rebooting in 5 seconds..."
sync
sleep 5
reboot
