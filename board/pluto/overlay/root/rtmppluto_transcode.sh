#!/bin/sh
mkfifo /root/bigtspipe
mkfifo /root/tspipe
source /root/device_sel.sh


while :
do
#killall ffmpeg
rm infortmp
FREQ=""
VIDEORATE=""
UDPIP=127.0.0.1:12000
PCRPTS=800
DELAYBUFFER=100

 (ffmpeg -f flv -listen 1 -timeout -1 -rtmp_buffer 100 -i rtmp://0.0.0.0:7272/ -c:v copy -c:a copy -f flv -y /root/bigtspipe 2>infortmp ) &
while [ "$VIDEORATE" == "" ]
do
 
 sleep 1

 VIDEORATE=$(grep -o " Video:.*" infortmp | cut -f4 -d, | cut -f1 -d'k') 
 echo Wait for RTMP connection
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

VIDEOMAX=$(echo "($TSBITRATE/1000)*70/100" | bc)

if [[ "$VIDEORATE" -ge "$VIDEOMAX" ]] ; then
MESSAGE="V!$VIDEOMAX kb"
else
MESSAGE="V$VIDEORATE kb"
fi


echo Freq before $FREQ
if [[ $(echo "($FREQ) > 6000" |bc -l) -ge 1 ]]; then
FREQ=$(echo "($FREQ)-8089.5" | bc)
echo QO100 $FREQ
fi

if [ "$MODE" = "ANA" ]; then
        echo Analogique
echo 0 > /sys/bus/iio/devices/iio:device$dev/out_voltage_filter_fir_en
echo 2250000 > /sys/bus/iio/devices/iio:device$dev/out_voltage_sampling_frequency
echo $FREQ"000000" > /sys/bus/iio/devices/iio:device$dev/out_altvoltage1_TX_LO_frequency
ffmpeg -f flv -i /root/bigtspipe -c:v copy -c:a copy -f mpegts -y /root/tspipe \
| /root/hacktv -m apollo-fsc-fm -o - -t int16 -s 2250000 ffmpeg:/root/tspipe | iio_writedev -b 100000 cf-ad9361-dds-core-lpc
else
        echo DVB
if [ "$AUDIO" = "" ]; then
ffmpeg -threads 2 -f flv -i /root/bigtspipe -c:v libx264 -x264-params "nal-hrd=cbr:force-cfr=1:keyint=100" -preset superfast -b:v "$VIDEOMAX""k" -minrate "$VIDEOMAX"k -maxrate "$VIDEOMAX" -bufsize 120000 -c:a aac -ac 1 -b:a 24k -ss 2 -muxrate $TSBITRATE -f mpegts -metadata service_provider="$MESSAGE" -metadata service_name=$CALL -streamid 0:256 -f tee -map 0:v -map 0:a "[f=mpegts:muxrate="$TSBITRATE":max_delay="$PCRPTS"000]/root/tspipe|[f=mpegts:muxrate="$TSBITRATE":max_delay="$PCRPTS"000]udp://"$UDPIP"?pkt_size=1316]" \
| /root/pluto_dvb -i /root/tspipe -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -L $DELAYBUFFER -g $GAIN $CALIB
else
ffmpeg -f flv -i /root/bigtspipe -c:v mpeg2video -b:v $VIDEOBITRATE"k" -c:a aac -b:a $AUDIO"k" -ss 2 -muxrate $TSBITRATE -f mpegts -metadata service_provider="$MESSAGE" -metadata service_name=$CALL -streamid 0:256 -f tee -map 0:v -map 0:a "[f=mpegts:muxrate="$TSBITRATE":max_delay="$PCRPTS"000]/root/tspipe|[f=mpegts:muxrate="$TSBITRATE":max_delay="$PCRPTS"000]udp://"$UDPIP"?pkt_size=1316]" \
| /root/pluto_dvb -i /root/tspipe -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -L $DELAYBUFFER -g $GAIN $CALIB 
fi
fi
echo endstreaming
done

