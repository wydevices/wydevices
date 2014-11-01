abfn=$1
fn=${abfn##*/} 

echo "Deleting $abfn"
rm $abfn
echo "Downlading replacement for: $fn"
wget http://wydevices.googlecode.com/svn/trunk/packages/wymodcp-php/web-files/scripts/php/$fn -O $abfn

