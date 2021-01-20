#!/bin/sh
CONF=/mnt/jffs2/etc/settings-datv.txt
CURRENT_MODE=$(grep mainmode $CONF | cut -f2 -d '='|sed 's/ //g')
CURRENT_DATVMODE=$(grep datvmode $CONF | cut -f2 -d '='|sed 's/ //g')

while :
do
restart_needed=0
inotifywait -e modify $CONF
NEWMODE=$(grep mainmode $CONF | cut -f2 -d '='|sed 's/ //g')
if [ "$CURRENT_MODE" != "$NEWMODE" ]; then

    CURRENT_MODE=$NEWMODE
    echo $CURRENT_MODE $NEWMODE
    restart_needed=1
fi

NEW_DATVMODE=$(grep datvmode $CONF | cut -f2 -d '='|sed 's/ //g')
if [ "$CURRENT_DATVMODE" != "$NEW_DATVMODE" ]; then
    echo $CURRENT_DATVMODE $CURRENT_DATVMODE	
    CURRENT_DATVMODE=$NEW_DATVMODE
   restart_needed=1	   
fi

if [ "$restart_needed" == "1" ]; then
/etc/init.d/S90datv restart
fi

done
