#!/bin/sh



LastModify=$(date -r /www/settings.txt +%s)

while :
do
CurrentDate=$(date -r /www/settings.txt +%s)
if [ "$LastModify" = "$CurrentDate" ]; then
        echo No Change
else
	echo Change !!!
	LastModify=$CurrentDate
	pidffmpeg=$(ps | grep ":8282" |grep -v "grep"| cut -c1-5)
	echo PID $pidffmpeg
	kill -9 $pidffmpeg
	killall -9 tsp
	killall -9 pluto_dvb
	killall -9 tsvbr2cbr
fi
sleep 1
done
