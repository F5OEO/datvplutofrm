#!/bin/sh
#pid=$(ps | grep rcv_analyse.sh | grep -v "grep" | cut -c1-5)
#kill -9 $pid
mypid=$(ps | grep "I ip" | grep -v "grep" | cut -c1-5)
kill -9 $mypid
mypid=$(ps | grep "decodevideo" | grep -v "grep" | cut -c1-5)
kill -9 $mypid
mypid=$(ps | grep "vframes 1" | grep -v "grep" | cut -c1-5)
kill -9 $mypid

if [ -n "${1}" ] && [ $1 = "start" ]
then
	IP=$(grep  "\bminitiouner-udp-ip\b" /mnt/jffs2/etc/settings-datv.txt | cut -f2 -d'='|sed 's/ //g')
	PORT=$(grep  "\bminitiouner-udp-port\b" /mnt/jffs2/etc/settings-datv.txt | cut -f2 -d'='|sed 's/ //g')

	echo $IP
	echo $PORT
	tsp -r --buffer-size-mb 0.01 --max-flushed-packets 100 --max-input-packets 50  -I ip $IP:$PORT -P analyze --normalized -i 1 -o /root/analysercv.txt -P pcrextract --evaluate-pcr-offset --pts --noheader --pid 256 -o /root/pcrrcv.txt -O drop &
	/root/decodevideo.sh $IP:$PORT >/dev/null </dev/null 2>/dev/null &	
fi                   