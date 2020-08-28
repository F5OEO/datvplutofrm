#!/bin/sh
mkfifo /root/bigtspipe
mkfifo /root/tspipe

while :
do
#killall ffmpeg
rm infortmp
FREQ=""
VIDEORATE=""
UDPIP=127.0.0.1:12000
PCRPTS=600
DELAYBUFFER=400

 (ffmpeg -f flv -listen 1 -timeout -1 -rtmp_buffer 100 -i rtmp://0.0.0.0:7272/ -c:v copy -c:a copy -f flv -y /root/bigtspipe 2>infortmp ) &
while [ "$VIDEORATE" == "" ]
do
 
 sleep 1

 VIDEORATE=$(grep -o " Video:.*" infortmp | cut -f4 -d, | cut -f1 -d'k') 
 echo Wait for RTMP connexion
done


FREQ=$(grep -o "match up:.*" infortmp | cut -f2 -d,)
VIDEORES=$(grep -o "Stream #0:1:.*" infortmp | cut -f3 -d,) 

echo $VIDEORATE $VIDEORES 



MODE=$(grep -o "match up:.*" infortmp | cut -f3 -d,)
CONSTEL=$(grep -o "match up:.*" infortmp | cut -f4 -d,)
SR=$(grep -o "match up:.*" infortmp | cut -f5 -d,)
FEC=$(grep -o "match up:.*" infortmp | cut -f6 -d,)
GAIN=$(grep -o "match up:.*" infortmp | cut -f7 -d,)
CALIB=$(grep -o "match up:.*" infortmp | cut -f8 -d,)
ASKPCRPTS=$(grep -o "match up:.*" infortmp | cut -f9 -d,)
ASKAUDIO=$(grep -o "match up:.*" infortmp | cut -f10 -d,)
PROVNAME=$(grep "\bprovname\b" /www/settings.txt | cut -f2 -d' ')
FWVERS=$(cat /www/fwversion.txt)
MESSAGE="$FWVERS""$PROVNAME"

if [ "$ASKPCRPTS" = "" ]; then
        echo pcrnot setting $PCRPTS
else
	PCRPTS=$ASKPCRPTS
	echo pcr setting $PCRPTS
fi

if [ "$CALIB" = "calib" ]; then
        CALIB="-q 1"
	echo calibration
else
	CALIB=""
fi

AUDIO=""

if [ "$ASKAUDIO" = "" ]; then
        echo Audio not transcoding
else
        AUDIO=$ASKAUDIO
        echo transcoding audio at $AUDIO
fi



echo FREQ $FREQ MODE $MODE CONSTEL $CONSTEL SR $SR FEC $FEC

CALL=$(grep -o "Unexpected stream.*" infortmp | cut -f2 -d,)
IP=$(grep -o "Unexpected stream.*" infortmp | cut -f3 -d,)


if [ "$IP" = "" ]; then
        echo No debug IP
else
echo ip $IP
UDPIP=$IP
fi


TSBITRATE=$(/root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -d)
echo TsBitrate $TSBITRATE   	

VIDEOMAX=$(echo "($TSBITRATE/1000)*80/100" | bc)




echo Freq before $FREQ
if [[ $(echo "($FREQ) > 6000" |bc -l) -ge 1 ]]; then
FREQ=$(echo "($FREQ)-8089.5" | bc)
echo QO100 $FREQ
fi


if [ "$AUDIO" = "" ]; then
ffmpeg -analyzeduration 4000000  -f flv  -i /root/bigtspipe -ss 4 -c:v copy -c:a copy -muxrate $TSBITRATE -f mpegts -metadata service_provider="$MESSAGE" -metadata service_name=$CALL -streamid 0:256  -tune zerolatency -muxrate "$TSBITRATE" -max_delay "$PCRPTS"000 - \
| tsp -r --buffer-size-mb 0.01 --max-flushed-packets 100 --max-input-packets 50 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset -o /root/pcr.txt |/root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -L $DELAYBUFFER -T 8 -g $GAIN $CALIB
else
ffmpeg -analyzeduration 4000000  -f flv -i /root/bigtspipe -ss 4 -c:v copy -c:a aac -b:a $AUDIO"k" -muxrate $TSBITRATE -f mpegts -metadata service_provider="$MESSAGE" -metadata service_name=$CALL -streamid 0:256 -tune zerolatency -muxrate "$TSBITRATE" -max_delay "$PCRPTS"000 - \
| tsp -r --buffer-size-mb 0.01 --max-flushed-packets 100 --max-input-packets 50 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset -o /root/pcr.txt | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -L $DELAYBUFFER -T 8 -g $GAIN $CALIB 
fi

echo endstreaming
rm /root/analyse.txt
done

