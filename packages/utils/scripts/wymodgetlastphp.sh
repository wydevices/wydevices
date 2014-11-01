#USAGE: find /wymedia/usr/share/wymodcp/scripts/php/*.php -type f -exec wymodgetlastphp.sh {} \;

if [[ $# -eq 0 ]] ; then
    echo 'USAGE: find /wymedia/usr/share/wymodcp/scripts/php/*.php -type f -exec wymodgetlastphp.sh {} \;'
    exit 0
fi



abfn=$1
fn=${abfn##*/} 

echo "Deleting $abfn"
rm $abfn
echo "Downlading replacement for: $fn"
wget http://wydevices.googlecode.com/svn/trunk/packages/wymodcp-php/web-files/scripts/php/$fn -O $abfn

