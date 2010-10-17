#!/bin/sh
case $1 in
        -skinsystem | -s | /s )
                logger "Skin Fast Switch: System Skin Active"
                rm /usr/bin/splash.py
                ln -s /usr/share/pygui/skins/wybox/splash/pysplash.py /usr/bin/splash.py

                echo "Updating local_conf.py file. Setting Original theme dir ..."
                        if [ -f /etc/local_conf.py ]; then
                                if [ -f /etc/local_conf_tmp.py ]; then
                                    rm /etc/local_conf_tmp.py
                                 fi
                          while read line
                            do
                              if [ -n "`echo ${line} | grep -E \"themes_dir\"`" ]; then
                                echo  "themes_dir='/usr/share/pygui/skins/'" >> /etc/local_conf_tmp.py
                              else
                                echo  $line >> /etc/local_conf_tmp.py
                              fi
                            done < /etc/local_conf.py
                          rm /etc/local_conf.py
                          mv /etc/local_conf_tmp.py /etc/local_conf.py
                        fi
                sync
        ;;
        -m | /m | -skinmod)
                logger "Skin Fast Switch: Mod Skin Active"
                rm /usr/bin/splash.py
                ln -s /usr/share/wydevpygui/skins/wybox/splash/modsplash.py /usr/bin/splash.py

                echo "Updating local_conf.py file. Setting Mod theme dir ..."
                        if [ -f /etc/local_conf.py ]; then
                                if [ -f /etc/local_conf_tmp.py ]; then
                                    rm /etc/local_conf_tmp.py
                                 fi
                          while read line
                            do
                              if [ -n "`echo ${line} | grep -E \"themes_dir\"`" ]; then
                                echo  "themes_dir='/usr/share/wydevpygui/skins/'" >> /etc/local_conf_tmp.py
                              else
                                echo  $line >> /etc/local_conf_tmp.py
                              fi
                            done < /etc/local_conf.py
                          rm /etc/local_conf.py
                          mv /etc/local_conf_tmp.py /etc/local_conf.py
                        fi
                sync
        ;;
        -t | /t | -themeserialized)
                logger "Setting Serialized Theme to True!"
                echo "Updating local_conf.py file. Setting use_serialized_theme=True ..."
                        if [ -f /etc/local_conf.py ]; then
                                if [ -f /etc/local_conf_tmp.py ]; then
                                    rm /etc/local_conf_tmp.py
                                 fi
                          while read line
                            do
                              if [ -n "`echo ${line} | grep -E \"use_serialized_theme\"`" ]; then
                                echo  "use_serialized_theme=True" >> /etc/local_conf_tmp.py
                              else
                                echo  $line >> /etc/local_conf_tmp.py
                              fi
                            done < /etc/local_conf.py
                          rm /etc/local_conf.py
                          mv /etc/local_conf_tmp.py /etc/local_conf.py
                        fi
        ;;
        -nt | /nt | -nothemeserialized)
                logger "Setting Serialized Theme to False!"
                echo "Updating local_conf.py file. Setting use_serialized_theme=False "
                        if [ -f /etc/local_conf.py ]; then
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
        ;;
        -e | /e | -exportskin)
                logger "Packaged skin exported to: /wymedia/usr/share/skins"
                if [ -d /wymedia/usr/share/skins/ ]; then
                        rm -f /wymedia/usr/share/skins/modskin.tar.gz
                        tar czvf /wymedia/usr/share/skins/modskin.tar.gz /wymedia/usr/share/wydevpygui
                else
                        mkdir /wymedia/usr/share/skins/
                        tar czvf /wymedia/usr/share/skins/modskin.tar.gz /wymedia/usr/share/wydevpygui
                fi
                sync
        ;;
        -i | /i | -importskin)
                logger "Packaged skin exported to: /wymedia/usr/share/"
                        tar zxf /wymedia/usr/share/skins/modskin.tar.gz -C /
                if [ -h /usr/share/wydevpygui ]; then
                        rm /usr/share/wydevpygui
                        ln -s /wymedia/usr/share/wydevpygui/ /usr/share/wydevpygui
                else
                        ln -s /wymedia/usr/share/wydevpygui/ /usr/share/wydevpygui
                fi
                sync
        ;;
        -r | /r | -rebootskin)
                logger "Rebooting Splash to update skin"
                sync
                killall python2.5
                ;;
        -f | /f | -flashmod)
                logger "Flashing modskin"
                rm /wymedia/usr/share/wydevpygui -Rf
                rm /usr/share/wydevpygui
                cp /usr/share/pygui/ /wymedia/usr/share/wydevpygui/ -pRf

                        if [ -f /wymedia/usr/share/wydevpygui/skins/wybox/splash/pysplash.py ]; then
                                if [ -f /wymedia/usr/share/wydevpygui/skins/wybox/splash/modsplash.py ]; then
                                    rm /wymedia/usr/share/wydevpygui/skins/wybox/splash/modsplash.py
                                 fi
                                awk '{sub(/\/usr\/share\/pygui/,"\/usr\/share\/wydevpygui")}1' < /wymedia/usr/share/wydevpygui/skins/wybox/splash/pysplash.py > /wymedia/usr/share/wydevpygui/skins/wybox/splash/modsplash.py
                        fi
                ln -s /wymedia/usr/share/wydevpygui /usr/share/wydevpygui
                chmod +x /wymedia/usr/share/wydevpygui/skins/wybox/splash/modsplash.py
                ;;
        -d | /d | -redfiff)
                logger "Re-dfiffing skin"
                find /wymedia/usr/share/wydevpygui/skins/wybox/ -name *.png -exec python2.5 -O /usr/share/pygui/skins/png2dfiff.py {} \;
        ;;
        -c | /c | -checkconsistency)
                if [ -h /usr/bin/splash.py ]; then
                      echo "Splash.py is a link"
                      echo "Active skin: $(ls /usr/bin/splash.py -l |cut -d" " -f28 |cut -d"/" -f4)"
                      echo ""
                fi
                echo $(cat /etc/local_conf.py |grep themes)
                echo $(cat /etc/local_conf.py |grep use_serialized_theme)
                echo "Wyplay Skin Size:" $(du -ch /usr/share/pygui/* |tail -n1)
                echo "Mod Skin Size:" $(du -ch /usr/share/wydevpygui/* |tail -n1)
        ;;
        *)
                echo "Usage: $0 -skinsystem | -skinmod | -checkconsistency | -exportskin | -importskin | -rebootskin | -flashmod | -redfiff |
-checkconsistency | -themeserialized | -nothemeserialized"

                echo "Usage: $0 -s | -c | -m | -e | -i | -r | -f | -d | -c | -t | -nt"
                echo ""
                echo "Just use one parameter at a time"
                echo ""
                echo "   ..:: Install and configuration options ::.."
                echo ""
                echo "[-f] -flashmod : Delete current modskin and re-create from wydevice current skin."
                echo "[-s] -skinsystem : Skin Fast Switch to default skin"
                echo "[-m] -skinmod : Skin Fast Switch to mod skin"
                echo "[-c] -checkconsistency : Basic Skin Consistency Status"
                echo ""
                echo "   ..:: Skin operations ::.."
                echo "[-e] -exportskin : compress skin to /wymedia/usr/share/skins/"
                echo "[-i] -importskin : uncompress skin from /wymedia/usr/share/skins/modskin.tar.gz"
                echo "[-r] -rebootskin : Restart splash applying changes to skin"
                echo "[-d] -redfiff : dfiff again all modskin png's"
                echo ""
                echo "  ..:: Theme Serialization Options ::.."
                echo "[-t] -themeserialized: set use_serialized_theme to True"
                echo "[-nt] -nothemeserialized: set use_serialized_theme to False"
                echo ""
                echo "Free Wyplay Source !!!"
                echo ""
        ;;
esac
