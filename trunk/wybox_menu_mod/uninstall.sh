#!/bin/sh
# Script de deinstallation du menu parametres modifié

dir=`pwd`

# Suppression des fichiers python
echo "Deleting python files..."
rm /usr/lib/python2.5/site-packages/pygui/eventmanager/menus/cpu_temp.py
rm /usr/lib/python2.5/site-packages/pygui/eventmanager/menus/cpu_temp.pyo
rm /usr/lib/python2.5/site-packages/pygui/eventmanager/menus/led_policy.py
rm /usr/lib/python2.5/site-packages/pygui/eventmanager/menus/led_policy.pyo
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
mv /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo_bak /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo

# Suppression des fichiers xml
echo "Deleting xml files..."
rm /usr/share/pygui/skins/wybox/xml/menus/params/cpu_temp.xml
rm /usr/share/pygui/skins/wybox/xml/menus/params/led_policy.xml
rm /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml
mv /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml_bak /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml

# Retour au fichier 'local_conf.py' original
echo "Reverting local_conf.py file to original..."
if [ -f /etc/local_conf.py_bak ]; then
  rm /etc/local_conf.py
  mv /etc/local_conf.py_bak /etc/local_conf.py
fi

# Reboot de la box
echo "Uninstall done."
echo "Rebooting in 5 seconds..."
sync
sleep 5
reboot
