#!/bin/sh

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
#H265BOX=$(grep "\bh265box\b" /www/settings.txt | cut -f2 -d' ')
CODEC=$(grep "\bcodec\b" /www/settings.txt | cut -f2 -d' ')
SOUND=$(grep "\bsound\b" /www/settings.txt | cut -f2 -d' ')
AUDIOINPUT=$(grep "\baudioinput\b" /www/settings.txt | cut -f2 -d' ')

PROVNAME=$(grep "\bprovname\b" /www/settings.txt | cut -f2 -d' ')
FWVERS=$(cat /www/fwversion.txt)
MESSAGE="$FWVERS""$PROVNAME"

CONF=/mnt/jffs2/etc/settings-datv.txt
#workaround for extra ^M characters : dos2unix
#dos2unix $CONF
REMUX=$(grep "remux" $CONF | cut -f2 -d '='|sed 's/ //g')
H265BOX=$(grep use_h265box $CONF | cut -f2 -d '='|sed 's/ //g')
H265BOXIP=$(grep ipaddr_h265box $CONF | cut -f2 -d '='|sed 's/ //g')

phase_correction=$(grep -w "phase_correction" $CONF | cut -f2 -d '='|sed 's/ //g')
if [ "$phase_correction" == "" ]; then
        phase_correction="0.0"
fi        
module_correction=$(grep -w "module_correction" $CONF | cut -f2 -d '='|sed 's/ //g')
if [ "$module_correction" == "" ]; then
        module_correction="1.0"
fi        
phase_correction_32_1=$(grep -w "phase_correction_32_1" $CONF | cut -f2 -d '='|sed 's/ //g')
if [ "$phase_correction_32_1" == "" ]; then
        phase_correction_32_1="0.0"
fi  
module_correction_32_1=$(grep -w "module_correction_32_1" $CONF | cut -f2 -d '='|sed 's/ //g')
if [ "$module_correction_32_1" == "" ]; then
        module_correction_32_1="1.0"
fi  
phase_correction_32_2=$(grep -w "phase_correction_32_2" $CONF | cut -f2 -d '='|sed 's/ //g')
if [ "$phase_correction_32_2" == "" ]; then
        phase_correction_32_2="0.0"
fi  
module_correction_32_2=$(grep -w "module_correction_32_2" $CONF | cut -f2 -d '='|sed 's/ //g')
if [ "$module_correction_32_2" == "" ]; then
        module_correction_32_2="1.0"
fi  

echo remux $REMUX

echo FREQ $FREQ MODE $MODE CONSTEL $CONSTEL SR $SR FEC $FEC PILOT $PILOTS_TXT FRAME $FRAME_TXT Rof $ROLLOFF PCRPTS $PCRPTS PATPERIOD $PATPERIOD CODEC $CODEC SOUND $SOUND AUDIOINPUT $AUDIOINPUT

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


if [ "$H265BOX" = "on" ] ; then
        MANUAL_MODE=$(grep "h265box-manualmode" /www/settings.txt | cut -f2 -d' '|sed 's/ //g')
        if [ "$MANUAL_MODE" != "on" ]; then
                com="h265box=$H265BOXIP&codec=$CODEC&res=$RESOLUTION&fps=$VIDEOFPS&keyint=$GOPSIZE&v_bitrate=$VIDEORATE&sound=$SOUND&audioinput=$AUDIOINPUT&audio_channels=$AUDIOCHANNELS&audio_bitrate=$AUDIORATE&enabled=$PTT&pluto_ip=$myip&pluto_port=8282"
                echo $com
                php-cgi /www/encoder_control.php $com
        fi
fi

echo Freq before $FREQ
if [[ $(echo "($FREQ) > 6000" |bc -l) -ge 1 ]]; then
FREQ=$(echo "($FREQ)-8089.5" | bc)
echo QO100 $FREQ
fi



#-max_interleave_delta 200000 -audio_preload 20000 : to investigate
echo remux $REMUX
if [[ "$REMUX" == "on" ]]; then
echo "Remux mode"
echo call $CALL
#ffmpeg -analyzeduration 4000000 -f mpegts -i udp://0.0.0.0:8282/   -ss 4 -c:v copy -tune zerolatency -c:a copy -f mpegts -muxrate $TSBITRATE -pat_period $PATPERIOD -metadata service_provider="$MESSAGE" -metadata service_name=$CALL -streamid 0:256 -muxrate "$TSBITRATE" -max_delay "$PCRPTS"000 -pat_period $PATPERIODSEC - | tsp -r --buffer-size-mb 0.01 --max-flushed-packets 100 --max-input-packets 50 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt -O file /root/tspipe | /root/pluto_dvb -i /root/tspipe -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 6 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF
echo "-m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 0 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF -R $phase_correction -A $module_correction -G $phase_correction_32_1 -H $module_correction_32_1 -M $phase_correction_32_2 -N $module_correction_32_2"
#tsp -r --buffer-size-mb 0.001 --max-flushed-packets 7 --max-input-packets 7 -I ip 0.0.0.0:8282 | /root/tsvbr2cbr -b $TSBITRATE -p $PCRPTS | tsp --buffer-size-mb 0.001 --max-flushed-packets 10 --max-input-packets 10 -r -P sdt --create-after 300 --ts-id 1 -n $CALL -p $MESSAGE -i -s 1 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 0 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF 
tsp -r --buffer-size-mb 0.001 --max-flushed-packets 7 --max-input-packets 7 -I ip 0.0.0.0:8282 | /root/tsvbr2cbr -b $TSBITRATE -p $PCRPTS | tsp --buffer-size-mb 0.001 --max-flushed-packets 10 --max-input-packets 10 -r -P sdt --create-after 300 --ts-id 1 -n $CALL -p $MESSAGE -i -s 1 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 0 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF -R $phase_correction -A $module_correction -G $phase_correction_32_1 -H $module_correction_32_1 -M $phase_correction_32_2 -N $module_correction_32_2
#ffmpeg -f mpegts -i udp://0.0.0.0:8282 -c:v copy -c:a libfdk_aac -profile:a aac_he_v2 -ar 16k -ac 8k -f mpegts -y -| /root/tsvbr2cbr -b $TSBITRATE -p $PCRPTS | tsp --buffer-size-mb 0.001 --max-flushed-packets 10 --max-input-packets 10 -r -P sdt --create-after 300 --ts-id 1 -n $CALL -p $MESSAGE -i -s 1 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 0 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF -R $phase_correction -A $module_correction -G $phase_correction_32_1 -H $module_correction_32_1 -M $phase_correction_32_2 -N $module_correction_32_2
#tsp -r -d -v --buffer-size-mb 0.01 --max-flushed-packets 100 --max-input-packets 50 -b 2500000 -I ip 230.0.0.1:8282 -P sdt -n $CALL -p $MESSAGE -i -s 1 -O ip 230.0.0.2:10000

#nc -lu -p 8282 | /root/tsvbr2cbr -b $TSBITRATE -p $PCRPTS | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 6 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF 
#tsp --buffer-size-mb 4   -I ip 230.0.0.1:8282 -P continuity -O drop
### AS SOON AS VIDEO BITRATE IS ABOVE 1Mbits, through network, Pluto is loosing packets
#tsp --buffer-size-mb 0.1 --max-flushed-packets 20 -I ip 0.0.0.0:8282 -P continuity  | /root/tsvbr2cbr -b $TSBITRATE -p $PCRPTS | tsp --buffer-size-mb 0.1 --max-flushed-packets 20 -P sdt -n $CALL -p $MESSAGE -i -s 1 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 6 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF 

else
echo "Warning : Passthrough mode !!!!"
tsp -r --buffer-size-mb 0.01 --max-flushed-packets 100 --max-input-packets 50 -I ip 0.0.0.0:8282 -P sdt -n $CALL -p $MESSAGE -i -s 1 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 0 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF -R $phase_correction -A $module_correction -G $phase_correction_32_1 -H $module_correction_32_1 -M $phase_correction_32_2 -N $module_correction_32_2
fi
echo endstreaming

done
