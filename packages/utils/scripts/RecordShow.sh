#!/wymedia/usr/bin/bash


BASEFOLDER="/wymedia/My Music/"

case $3 in
IGR) 

     OUTFOLDER="IGR/";
     STATION="http://ibizaglobalradio.streaming-pro.com:8024/";
     echo "You selected Ibiza global radio";;

BEATMINERZ)

     OUTFOLDER="beatminerz/";
     STATION="http://listen.radionomy.com/BEATMINERZRADIO";
     echo "You selected BeatMinerz";;

OMC) 

     OUTFOLDER="OMC/";
     STATION="http://tesla.eldialdigital.com:6020/";
     echo "You selected OMC Radio";;

SONICA) 

     OUTFOLDER="SONICA/";
     STATION="http://stream3.ibizasonica.com:8032/";
     echo "You selected Ibiza sonica";;


BLACKBEATS) 

     OUTFOLDER="BlackBeats/";
     STATION=" http://stream.blackbeats.fm/ ";
     echo "You selected BlackBeats";;

*)
 echo " not defined station";
 exit;;
esac 

OUT="$BASEFOLDER$OUTFOLDER"

[[ -d "$OUT" ]] || mkdir "$OUT";


logger "start recording $1 with $2 seconds to $OUT"
cd "$OUT"
streamripper $STATION -a $1_$(date "+%d-%m-%Y_%H-%M") -l $2
