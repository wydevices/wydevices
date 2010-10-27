#!/bin/bash
rm -Rf ./usr/
tar -zxvf ./orig/imagepack.tar.gz
tar -zxvf ./orig/imagepacksplash.tar.gz
						# AQUI HAY QUE MODIFICAR LA RUTA AL dffifer.jar
dfifferpath="C:\DATA\WYDEV\SVN\host-tools"

echo "index   file" >indexfile.tab
j=0
for i in $(find -name *.png); do
	java -jar $dfifferpath/dfiffer.jar $i
	if [ -e $i.png ]; then
						# Si la conversióucede con éto, eliminar el fichero original, y renombrar el *.png.png a *.png      
	#rm $i
		mv $i.png $i
						#Sacar el tamañriginal
	#	VarSize=$(identify $i |cut -d" " -f3)
	#	VarConvert=$(convert -background DarkOrange4 -fill peru -bordercolor DarkOrange1 -border 1x1 -font Helvetica-Bold -size $VarSize caption:$j $i)
	
						# Las siguientes lineas comentadas son las que se usaron para generar los skins incluidos en el pack de skins.
						#SkinDebug#VarConvert=$(convert -background red -fill black -bordercolor white -border 5x5 -font Helvetica-Bold -size $VarSize  caption:$i $i)
						#Black-Skin# VarConvert=$(convert -background black -fill black -bordercolor white -border 3x3 -size $VarSize caption:"" $i)
						#Blue-Skin# VarConvert=$(convert -background blue -fill blue -bordercolor white -border 3x3 -size $VarSize caption:"" $i)
						#VarConvert=$(convert -background blue -fill blue -bordercolor white -border 3x3 -size $VarSize caption:"" $i)
						#DebugNumbers VarConvert=$(convert -background black -fill black -bordercolor white -border 1x1 -size $VarSize caption:$j $i)
						#VarConvert=$(convert -background black -fill blue -bordercolor white -border 1x1 -font Helvetica-Bold -size $VarSize  caption:$j $i)
						#Almacenar el valor de la j (numero) y de la i, el path, en el fichero indexfile.tab
		echo $j "   " $i >>indexfile.tab
						 #transparentfailed# VarConvert=$(convert -bordercolor white -border 3x3 -size $VarSize xc:'rgba(255,0,0, 1.0)' caption:"" $i)
		echo "$j $i updated!"
	fi
let j=j+1
done


#
# Para empaquetar nuevamente:
# por ejemplo desde: /cygdrive/c/DATA/WYDEV/skins/imagepacks/ZTV
#       tar czvf imagepacksplash.tar.gz ./usr/share/pygui/skins/wybox/splash/
#       tar czvf imagepack.tar.gz ./usr/share/pygui/skins/wybox/images/
#