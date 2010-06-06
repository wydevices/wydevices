#!/bin/sh
# Script d'installation du menu parametres modifié

dir=`pwd`

# Copie des fichiers python
echo "Copying python files..."
cp /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo /usr/lib/python2.5/site-packages/pygui/item/parameters/advanced/__init__.pyo_bak
cp -r $dir/pygui /usr/lib/python2.5/site-packages

# Copie des fichiers xml
echo "Copying xml files..."
cp /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml /usr/share/pygui/skins/wybox/xml/menus/params/setup.xml_bak
cp -r $dir/xml /usr/share/pygui/skins/wybox

# Modification du parametre 'use_serialized_theme' dans le fichier 'local_conf.py'
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

# Reboot de la box
echo "Install done."
echo "Rebooting in 5 seconds..."
sync
sleep 5
reboot
