#!/bin/sh
CONF=/mnt/jffs2/etc/settings-datv.txt
#dos2unix $CONF
H265BOX=$(grep use_h265box $CONF | cut -f2 -d '='|sed 's/ //g')
H265BOXIP=$(grep ipaddr_h265box $CONF | cut -f2 -d '='|sed 's/ //g')



while :
do
TSBITRATE=$(mosquitto_sub -t plutodvb/ts/netbitrate -C 1)
CONFVOLATILE=/www/settings.txt
MANUAL_MODE=$(grep "h265box-manualmode" $CONFVOLATILE | cut -f2 -d' '|sed 's/ //g')
if [ "$MANUAL_MODE" != "on" ]; then
    source /root/strategy.sh
    # CONFIGURE H265 ENCODER
    if [ "$H265BOX" = "on" ] ; then
    com="h265box=$H265BOXIP&codec=$CODEC&res=$RESOLUTION&fps=$VIDEOFPS&keyint=$GOPSIZE&v_bitrate=$VIDEORATE&sound=$SOUND&audioinput=$AUDIOINPUT&audio_channels=$AUDIOCHANNELS&audio_bitrate=$AUDIORATE&enabled=$PTT&pluto_ip=$myip&pluto_port=8282"
    #echo $com
    #php-cgi /www/encoder_control_mini.php $com
    MAXBITRATE=$(echo "($VIDEORATE)*1500" | bc)
    mosquitto_pub -t h265coder/bitrate -m  $VIDEORATE
    mosquitto_pub -t h265coder/stattime -m  2
    mosquitto_pub -t h265coder/lost_strategy -m  $MAXBITRATE
    mosquitto_pub -t h265coder/fluctuation -m  1
    fi
fi
done