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

	id3v2 -A $1 $OUTFILE.mp3
	id3v2 -a $1 $OUTFILE.mp3
	id3v2 -t $OUTFILE $OUTFILE.mp3
	
	#id3v2 --TIT2 FECHANOMBRE  $OUTFILE.mp3
	#id3v2 --TPE1 DJ $OUTFILE.mp3
	#id3v2 --TALB PROGRAM $OUTFILE.mp3
	id3v2 --TYER 2015 $OUTFILE.mp3
	id3v2 --TCON 52 $OUTFILE.mp3

	

fi
