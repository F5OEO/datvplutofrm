#!/bin/sh
MESSAGE=FIRM2201RC
mkfifo /root/bigtspipe
mkfifo /root/tspipe

myip=$(ip -f inet -o addr show eth0 | cut -d\  -f 7 | cut -d/ -f 1)
echo $myip


while :
do
#killall ffmpeg
rm infoudp
FREQ=""
UDPIP=127.0.0.1:13000


# GET ALL MOD PARAMETERS 
CALL=$(grep  "\bcallsign\b" /www/settings.txt | cut -f2 -d' ')
FREQ=$(grep  "\bfreq\b" /www/settings.txt | cut -f2 -d' ')
MODE=$(grep  "\bmode\b" /www/settings.txt | cut -f2 -d' ')
CONSTEL=$(grep  "\bmod\b" /www/settings.txt | cut -f2 -d' ')
SR=$(grep  "\bsr\b" /www/settings.txt | cut -f2 -d' ')
FEC=$(grep  "\bfec\b" /www/settings.txt | cut -f2 -d' ')
GAIN=$(grep "\bpower\b" /www/settings.txt | cut -f2 -d' ')
PTT=true
PILOTS_TXT=$(grep "\bpilots\b" /www/settings.txt | cut -f2 -d' ')
FRAME_TXT=$(grep "\bframe\b" /www/settings.txt | cut -f2 -d' ')
ROLLOFF=$(grep "\brolloff\b" /www/settings.txt | cut -f2 -d' ')
PCRPTS=$(grep "\bpcrpts\b" /www/settings.txt | cut -f2 -d' ')
PATPERIOD=$(grep "\bpatperiod\b" /www/settings.txt | cut -f2 -d' ')
H265BOX=$(grep "\bh265box\b" /www/settings.txt | cut -f2 -d' ')
REMUX=$(grep "\bremux\b" /www/settings.txt | cut -f2 -d' ')

echo FREQ $FREQ MODE $MODE CONSTEL $CONSTEL SR $SR FEC $FEC PILOT $PILOTS_TXT FRAME $FRAME_TXT Rof $ROLLOFF PCRPTS $PCRPTS PATPERIOD $PATPERIOD

if [ "$PILOTS_TXT" = "On" ]; then
        PILOTS="-p"
else
        PILOTS=""
fi

if [ "$FRAME_TXT" = "ShortFrame" ]; then
        FRAME="-v"
else
        FRAME=""
fi

if [ "$IP" = "" ]; then
        echo No debug IP
else
echo ip $IP
UDPIP=$IP
fi


TSBITRATE=$(/root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC $PILOTS $FRAME -d)
echo TsBitrate $TSBITRATE   	

PATPERIODSEC=$(echo "x=($PATPERIOD)*0.001;if(x<1) print 0; x" | bc)

echo patperiod $PATPERIODSEC
# LETS MAKE A GOOD STRATEGY - PERFECT JOB FOR YVES F4HSL
source /root/strategy.sh

echo VideoRate $VIDEORATE

# CONFIGURE H265 ENCODER
if [ "$H265BOX" = "" ]; then
        echo H265BOX not present
else        
com="h265box=$H265BOX&codec=$CODEC&res=$RESOLUTION&fps=$VIDEOFPS&keyint=$GOPSIZE&v_bitrate=$VIDEORATE&audioinput=hdmi&audio_channels=$AUDIOCHANNELS&audio_bitrate=$AUDIORATE&enabled=$PTT&pluto_ip=$myip&pluto_port=8282"
echo $com
php-cgi /www/encoder_control.php $com
fi

echo Freq before $FREQ
if [[ $(echo "($FREQ) > 6000" |bc -l) -ge 1 ]]; then
FREQ=$(echo "($FREQ)-8089.5" | bc)
echo QO100 $FREQ
fi


echo "Try starting UDP"
#-max_interleave_delta 200000 -audio_preload 20000 : to investigate
if [ "$REMUX" = "1" ]; then
ffmpeg -f v4l2 -standard PAL -video_size 352x288 -framerate 25  -i /dev/video0  -f alsa -ar 8000 -ac 1 -i hw:0 -c:v libx264 -pix_fmt yuv420p -x264-params "nal-hrd=cbr" -preset veryfast -shortest -s 352x288 -g 100 -r 20 -tune zerolatency -b:v 500k -minrate 450k -maxrate 550k -bufsize 100k -c:a aac -b:a 32000 -ar 8000 -ac 1 -bufsize 34k -f mpegts -muxrate $TSBITRATE -pat_period $PATPERIOD -metadata service_provider="$MESSAGE" -metadata service_name=$CALL -streamid 0:256 -muxrate "$TSBITRATE" -max_delay "$PCRPTS"000 -pat_period $PATPERIODSEC - | tsp --receive-timeout 8000 -r --buffer-size-mb 0.01 --max-flushed-packets 100 --max-input-packets 50 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt -O file /root/tspipe | /root/pluto_dvb -i /root/tspipe -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 6 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF
else
tsp --receive-timeout 2000 -r --buffer-size-mb 0.01 --max-flushed-packets 100 --max-input-packets 50 -I ip 0.0.0.0:8282 -P sdt -n $CALL -p $MESSAGE -i -s 1 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt  | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 2 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF
fi
echo endstreaming

done

