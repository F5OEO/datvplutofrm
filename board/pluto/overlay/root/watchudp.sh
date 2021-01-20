#!/bin/sh

# F5UII Gateway setup for routing through lan udp socket to minitiouner. Execute one time at pluto start
GW_ETH0=$(grep "\bgateway-eth0\b" /www/settings-receiver.txt | cut -f2 -d' ')
if expr "$GW_ETH0" : '[0-9][0-9]*\.[0-9][0-9]*\.[0-9][0-9]*\.[0-9][0-9]*$' >/dev/null; then
  echo "success"
  route add default gw $GW_ETH0
else
  echo "not an IP v4 format for Gateway found in settings-receiver.txt"
fi

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
