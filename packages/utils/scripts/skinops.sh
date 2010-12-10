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
		        -ei | /ei | -exportimagepack)
                logger "Packaged imagepack exported to: /wymedia/usr/share/imagepacks"
                if [ -d /wymedia/usr/share/imagepacks/ ]; then
                        rm -f /wymedia/usr/share/imagepacks/imagepack.tar.gz
						rm -f /wymedia/usr/share/imagepacks/imagepacksplash.tar.gz
                        tar czvf /wymedia/usr/share/imagepacks/imagepack.tar.gz /usr/share/pygui/skins/wybox/images/
						tar czvf /wymedia/usr/share/imagepacks/imagepacksplash.tar.gz /usr/share/pygui/skins/wybox/splash/
                else
                        mkdir /wymedia/usr/share/imagepacks/
                        tar czvf /wymedia/usr/share/imagepacks/imagepack.tar.gz /usr/share/pygui/skins/wybox/images/
						tar czvf /wymedia/usr/share/imagepacks/imagepacksplash.tar.gz /usr/share/pygui/skins/wybox/splash/
                fi
                sync
        ;;
        -ii | /ii | -importimagepack)
                logger "Packaged imagepack exported to: /wymedia/usr/share/imagepacks"

				if [ -e /wymedia/usr/share/imagepacks/imagepack.tar.gz ]; then		
						tar zxf /wymedia/usr/share/imagepacks/imagepack.tar.gz -C /
				logger "Imported imagepack"
				echo "Imported imagepack"
				fi
				if [ -e /wymedia/usr/share/imagepacks/imagepacksplash.tar.gz ]; then		
						tar zxf /wymedia/usr/share/imagepacks/imagepacksplash.tar.gz -C /
				logger "Imported Splash"
				echo "Imported Splash"
				
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
                echo "System Skin Size:" $(du -ch /usr/share/pygui/* |tail -n1)
                echo "Mod Skin Size:" $(du -ch /usr/share/wydevpygui/* |tail -n1)
        ;;
        *)
		echo ""
		echo "NAME"
		echo "	skinops.sh"
		echo ""
		echo "SYNOPSIS"
		echo "	skinops.sh [option]"
		echo ""
		echo "OPTIONS"
		echo "	Just use one parameter at a time."
                echo ""
		echo "	..:: Installation and configuration options ::.."
		echo ""
		echo "		-f, -flashmod"
		echo "			Delete current modskin and recreate skin from wydevice current skin."
		echo ""
                echo "		-s, -skinsystem"
		echo "			Skin fast switch to default skin."
		echo ""
                echo "		-m, -skinmod"
		echo "			Skin fast switch to modskin."
		echo ""
                echo "		-c, -checkconsistency"
		echo "			Basic skin consistency status."
                echo ""
                echo "	..:: Skin operations ::.."
		echo ""
                echo "		-e, -exportskin"
		echo "			Compress skin to /wymedia/usr/share/skins/."
		echo ""
                echo "		-i, -importskin"
		echo "			Uncompress skin from /wymedia/usr/share/skins/modskin.tar.gz."
		echo ""
                echo "		-ei, -exportimagepack"
		echo "			Export imagepack to /wymedia/usr/share/imagepacks/."
		echo ""
                echo "		-ii, -importimagepack"
		echo "			Import imagepack from /wymedia/usr/share/imagepacks/."
		echo ""
                echo "		-r, -rebootskin"
		echo "			Restart splash applying changes to skin."
		echo ""
                echo "		-d, -redfiff"
		echo "			dfiff again all modskin PNGs."
		echo ""
                echo "	..:: Theme serialization options ::.."
		echo ""
                echo "		-t, -themeserialized"
		echo "			Set use_serialized_theme to True."
		echo ""
                echo "		-nt, -nothemeserialized"
		echo "			Set use_serialized_theme to False."
                echo ""
		echo ""
                echo "Free Wyplay Source !!!"
                echo ""
		echo ""
        ;;
esac
