#!/bin/sh

FILE=/root/analysercv.txt
lcallsign=""
lprovname=""
echo $lcallsign

while :
do
if [ -f "$FILE" ]; then
callsign=$(cat $FILE | grep ':name' | sed -e 's/^.*:name=//' -e 's/:.*//')
provider=$(cat $FILE | grep ':provider' | sed -e 's/^.*:provider=//' -e 's/:.*//')

if [[ ! -z $callsign  && $callsign != "(unknown)" ]]; then
  if [[ "$lcallsign" = "$callsign" ]]; then
        echo Nochange $lcallsign $callsign
  else
        echo change ! $lcallsign $callsign
        lcallsign=$callsign

        mosquitto_pub -t plutodvb/receiver/name -m $callsign
  fi
fi

if [[ ! -z $provider && $provider != "(unknown)" ]]; then
  if [[ "$lprovider" = "$provider" ]]; then
        echo Nochangeprov $lprovider $provider
  else
        echo change provider ! $lprovider $provider
        lprovider=$provider
        mosquitto_pub -t plutodvb/receiver/provname -m $provider

  fi
fi
else
echo "$FILE does not exist"

fi
sleep 1
done
