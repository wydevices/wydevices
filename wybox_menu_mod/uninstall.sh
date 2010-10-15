#!/bin/sh
# UnInstallation script of modified Wydevice python GUI

# Copyright 2010, Polo35
# Licenced under Academic Free License version 3.0
# Review wydev_pygui README & LICENSE files for further details.


dir=`pwd`


# Restoring original python files
echo "Restoring original python files..."
rm /usr/lib/python2.5/site-packages/pygui/actions/core.py
rm /usr/lib/python2.5/site-packages/pygui/actions/core.pyo
rm /usr/lib/python2.5/site-packages/pygui/eventmanager/menus/cpu_temp.py
rm /usr/lib/python2.5/site-packages/pygui/eventmanager/menus/cpu_temp.pyo
rm /usr/lib/python2.5/site-packages/pygui/eventmanager/menus/led_policy.py
rm /usr/lib/python2.5/site-packages/pygui/eventmanager/menus/led_policy.pyo
rm /usr/lib/python2.5/site-packages/pygui/facilities/turlututube.py
rm /usr/lib/python2.5/site-packages/pygui/facilities/turlututube.pyo
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.py
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/extras.py
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/extras.pyo
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/led.py
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/led.pyo
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/monitor.py
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/monitor.pyo
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/reboot.py
rm /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/reboot.pyo
rm /usr/lib/python2.5/site-packages/pygui/menu/menu/cpu_temp.py
rm /usr/lib/python2.5/site-packages/pygui/menu/menu/cpu_temp.pyo
rm /usr/lib/python2.5/site-packages/pygui/menu/menu/led_policy.py
rm /usr/lib/python2.5/site-packages/pygui/menu/menu/led_policy.pyo
mv /usr/lib/python2.5/site-packages/pygui/actions/core.pyo_bak /usr/lib/python2.5/site-packages/pygui/actions/core.pyo
mv /usr/lib/python2.5/site-packages/pygui/facilities/turlututube.pyo_bak /usr/lib/python2.5/site-packages/pygui/facilities/turlututube.pyo
mv /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo_bak /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo


# Restoring original xml files
echo "Restoring original xml files..."
rm /usr/share/pygui/skins/wybox/xml/menus/params/cpu_temp.xml
rm /usr/share/pygui/skins/wybox/xml/menus/params/led_policy.xml
rm /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml
mv /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml_bak /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml


# Restoring original internationalization files
echo "Restoring original internationalization files..."
rm /usr/share/pygui/locale/de/LC_MESSAGES/messages.mo
rm /usr/share/pygui/locale/en/LC_MESSAGES/messages.mo
rm /usr/share/pygui/locale/es/LC_MESSAGES/messages.mo
rm /usr/share/pygui/locale/fr/LC_MESSAGES/messages.mo
rm /usr/share/pygui/locale/it/LC_MESSAGES/messages.mo
rm /usr/share/pygui/locale/nl/LC_MESSAGES/messages.mo
rm /usr/share/pygui/locale/pt/LC_MESSAGES/messages.mo
mv /usr/share/pygui/locale/de/LC_MESSAGES/messages.mo_bak /usr/share/pygui/locale/de/LC_MESSAGES/messages.mo
mv /usr/share/pygui/locale/en/LC_MESSAGES/messages.mo_bak /usr/share/pygui/locale/en/LC_MESSAGES/messages.mo
mv /usr/share/pygui/locale/es/LC_MESSAGES/messages.mo_bak /usr/share/pygui/locale/es/LC_MESSAGES/messages.mo
mv /usr/share/pygui/locale/fr/LC_MESSAGES/messages.mo_bak /usr/share/pygui/locale/fr/LC_MESSAGES/messages.mo
mv /usr/share/pygui/locale/it/LC_MESSAGES/messages.mo_bak /usr/share/pygui/locale/it/LC_MESSAGES/messages.mo
mv /usr/share/pygui/locale/nl/LC_MESSAGES/messages.mo_bak /usr/share/pygui/locale/nl/LC_MESSAGES/messages.mo
mv /usr/share/pygui/locale/pt/LC_MESSAGES/messages.mo_bak /usr/share/pygui/locale/pt/LC_MESSAGES/messages.mo


# Reboot de la box
echo "Uninstall done."
echo "Rebooting in 5 seconds..."
sync
sleep 5
reboot
