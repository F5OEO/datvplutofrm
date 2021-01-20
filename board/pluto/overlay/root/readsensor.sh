#!/bin/sh

while :
do

#ad9363 temp
adctempmc=$(iio_attr  -c ad9361-phy temp0 input)
adctempc=$(awk "BEGIN {printf(\"%.1f\", ${adctempmc} / 1000)}")

#Zinq fpga
xadc_raw=$(iio_attr  -c xadc temp0 raw)
xadc_offset=$(iio_attr -c xadc temp0 offset)
xadc_scale=$(iio_attr  -c xadc temp0 scale)
tempxadc=$(awk "BEGIN {printf(\"%.1f\", -20 + (${xadc_raw} + ${xadc_offset}) * ${xadc_scale}/1000)}")

#Voltage regulator : Current
regulcurrent_raw=$(iio_attr -c adm1177 current0 raw)
regulcurrent_scale=$(iio_attr -c adm1177 current0 scale)
regulcurrent=$(awk "BEGIN {printf(\"%.0f\", ${regulcurrent_raw} * ${regulcurrent_scale})}")

#Voltage regulator : voltage
regulvoltage_raw=$(iio_attr -c adm1177 voltage0 raw )
regulvoltage_scale=$(iio_attr -c adm1177 voltage0 scale)
regulvoltage=$(awk "BEGIN {printf(\"%.3f\", ${regulvoltage_raw} * ${regulvoltage_scale}/1000)}")

mosquitto_pub -t plutodvb/status/adtemp -m $adctempc
mosquitto_pub -t plutodvb/status/fpgatemp -m $tempxadc
mosquitto_pub -t plutodvb/status/voltage -m $regulvoltage
mosquitto_pub -t plutodvb/status/current -m $regulcurrent

#echo "${adctempc} - ${tempxadc}° current ${regulcurrent} voltage ${regulvoltage}"
sleep 1
done

