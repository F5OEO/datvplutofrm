#!/bin/sh
#https://www.analog.com/media/cn/technical-documentation/user-guides/AD9364_Register_Map_Reference_Manual_UG-672.pdf

ptton()
{
    #PTT on GPIO 0 AND GPIO 2 (GPIO 1 should be not touched)
	echo 0x27 0x50 > /sys/kernel/debug/iio/iio:device1/direct_reg_access
	mosquitto_pub -t plutodvb/status/tx -m true
}

pttoff()
{
        echo 0x27 0x00 > /sys/kernel/debug/iio/iio:device1/direct_reg_access
		mosquitto_pub -t plutodvb/status/tx -m false
}


echo manual_tx_quad > /sys/bus/iio/devices/iio:device1/calib_mode
#Manual GPIO
echo 0x26 0x10 > /sys/kernel/debug/iio/iio:device1/direct_reg_access
pttoff

while :
do
inotifywait -e modify /sys/bus/iio/devices/iio\:device1/out_voltage0_hardwaregain
gain=$(cat /sys/bus/iio/devices/iio:device1/out_voltage0_hardwaregain)
if [ "$gain" = "-40.000000 dB" ] ; then
echo "SdrConsole PTT OFF"
pttoff
else
        if [ "$gain" = "0.000000 dB" ] ; then
                sleep 2
                gain=$(cat /sys/bus/iio/devices/iio:device1/out_voltage0_hardwaregain)
                if [ "$gain" = "0.000000 dB" ] ; then
                        echo "SdrConsole Power Max PTT ON"
                        ptton
                fi
        else
                echo "SdrConsole PTT ON"
                ptton
        fi
fi
done

