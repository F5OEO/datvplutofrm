#!/bin/sh

 
  ip=$(avahi-resolve --name pluto.local | awk '{print $2}')
  uri=$(echo "-u ip:${ip}")



device=$(iio_attr -${uri} -C | grep PlutoSDR)
if [ -z "${device}" ] ; then
  echo "not a PlutoSDR at ${uri}"
  exit
fi

if [ -z "${1}" ] ; then
  i=1
else
  i=$1
fi

if [ -z "${2}" ] ; then
  delay=1
else
  delay=$2
fi

while [ $i -ne 0 ] ; do
  pluto_temp=$(iio_attr  ${uri} -c ad9361-phy temp0 input)
  pluto=$(awk "BEGIN {printf(\"%.1f\", ${pluto_temp} / 1000)}")

  xadc_raw=$(iio_attr  ${uri} -c xadc temp0 raw)
  xadc_offset=$(iio_attr ${uri} -c xadc temp0 offset)
  xadc_scale=$(iio_attr  ${uri} -c xadc temp0 scale)
  xadc=$(awk "BEGIN {printf(\"%.1f\", -20 + (${xadc_raw} + ${xadc_offset}) * ${xadc_scale}/1000)}")

  echo "${pluto}°C - ${xadc}°C"
  i=$(expr $i - 1)
  if [ $i -ne 0 ] ; then
    sleep ${delay}
  fi
done
