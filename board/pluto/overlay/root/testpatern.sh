#!/bin/sh


myip=$(ip -f inet -o addr show eth0 | cut -d\  -f 7 | cut -d/ -f 1)

while :
do
#killall ffmpeg
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
fi


TSBITRATE=$(/root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC $PILOTS $FRAME -d)
echo TsBitrate $TSBITRATE   	

PATPERIODSEC=$(echo "x=($PATPERIOD)*0.001;if(x<1) print 0; x" | bc)

echo patperiod $PATPERIODSEC

echo Freq before $FREQ
if [[ $(echo "($FREQ) > 6000" |bc -l) -ge 1 ]]; then
FREQ=$(echo "($FREQ)-8089.5" | bc)
echo QO100 $FREQ
fi

tsp -r --buffer-size-mb 0.001 --max-flushed-packets 7 --max-input-packets 7 -I file -i /mnt/jffs2/patern.ts | /root/tsvbr2cbr -b $TSBITRATE -p $PCRPTS | tsp --buffer-size-mb 0.001 --max-flushed-packets 10 --max-input-packets 10 -r -P sdt -n $CALL -p $MESSAGE -i -s 1 -P analyze --normalized -i 1 -o /root/analyse.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcr.txt | /root/pluto_dvb -m $MODE -c $CONSTEL -s $SR"000" -f $FEC -t $FREQ"e6" -g $GAIN -T 0 -L 400 $PILOTS $FRAME -P 0 -r $ROLLOFF 

done
