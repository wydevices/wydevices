VarSize=`identify $1 |cut -d" " -f3`
VarConvert=`convert -background lightblue -fill black -bordercolor black -border 5x5 -font Helvetica-Bold -size $VarSize  caption:$1 $1`
echo "Size:"$VarSize $VarConvert
