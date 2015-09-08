#!/wymedia/usr/bin/bash

# $1 Show Name
# $2 Duration
# $3 Station
# $4 SingleOutfile
# $5 Default Pic at /wymedia/Music/WYRADIO/PICS/

if [ "$#" -lt 4 ] ; then
	echo "Usage: RecordShowNG.sh <ShowName> <Duration> <Station> <SingleOutfile><DefaultPicPath>";
	echo "";
	echo "Possible Stations (Stream Sources) are:";
	echo `sqlite3 /wymedia/.wyradio/wyradio.db3 "SELECT acronym FROM streamsources"`;
	echo "";
	echo "Duration is expressed in seconds.";
	echo "ShowName is a freetext name without spaces, it will be concatenated with the timestamp";
	echo "SingleOutFile is 1 (True) or 0 (False)";

else

	URL=`sqlite3 /wymedia/.wyradio/wyradio.db3 "SELECT url FROM streamsources WHERE [acronym] = '$3'"`;
	OUTFOLDER=`sqlite3 /wymedia/.wyradio/wyradio.db3 "SELECT outfolder FROM streamsources WHERE [acronym] = '$3'"`;
	OUTFILE=$(date "+%Y%m%d_%H-%M")_$1;

	if [ $4 -eq 0 ] ; then
		SINGLE="";
	else
		SINGLE="-a";
	fi

	cd $OUTFOLDER;

	logger "###################################################################"
	logger "Record from url:                      "$URL;
	logger "Record to folder:                     "$OUTFOLDER;
	logger "Record to name:                       "$OUTFILE;
	logger "Record will last:                     "$2" seconds";
	logger "Single Out file (1: True, 0: false):  "$4;
	logger "###################################################################"

	streamripper $URL $SINGLE $OUTFILE -l $2;

	#ls 20150830_01-00_ElRowKekahuma* |grep mp3 -c
	ALLOUTFILES=`ls $OUTFILE* |grep mp3 -c`;
	logger "## Autojoined:"$ALLOUTFILES" files.";
	
	if [ $ALLOUTFILES -gt 1 ] ; then

	CATOUT=`cat $OUTFILE*.mp3 >autojoin-$OUTFILE.mp3; rm $OUTFILE*.mp3;mv autojoin-$OUTFILE.mp3 $OUTFILE.mp3`;

	logger "OutCat:"$CATAOUT;

	else

	logger "## Adding id3v2 tags";
	
	fi

	id3v2 -A $1 $OUTFILE.mp3
	id3v2 -a $1 $OUTFILE.mp3
	id3v2 -t $OUTFILE $OUTFILE.mp3
	
	#id3v2 --TIT2 FECHANOMBRE  $OUTFILE.mp3
	#id3v2 --TPE1 DJ $OUTFILE.mp3
	#id3v2 --TALB PROGRAM $OUTFILE.mp3
	id3v2 --TYER 2015 $OUTFILE.mp3
	id3v2 --TCON 52 $OUTFILE.mp3	

	# Se asume que de estar la rpi, debe ser el servidor de dhcp...
	# cat /var/lib/dhcp/dhclient.leases |grep dhcp-server |awk '{print $3}' | cut -f0 -d ';'|awk '!x[$0]++'
	# lo uso para averiguar el servidor de DHCP

	#RPISERVER=`cat /var/lib/dhcp/dhclient.leases |grep dhcp-server |awk '{print $3}' | cut -f0 -d ';'|awk '!x[$0]++'`

	#wget "http://"$RPISERVER"/adddefaultpic.php?picpath=/wymedia/Music/WYRADIO/PICS/$5&filepath=$OUTFOLDER$OUTFILE.mp3" -O /tmp/$OUTFILE
	
	echo "Using pic: /wymedia/Music/WYRADIO/PICS/$5"
        echo "Using filepath: $OUTFOLDER/$OUTFILE.mp3"
        EYED3CMD="/usr/bin/eyeD3 --add-image /wymedia/Music/WYRADIO/PICS/$5:FRONT_COVER $OUTFOLDER/$OUTFILE.mp3"
        echo $EYED3CMD >> /wymedia/.wyradio/pendingid3.txt

fi
