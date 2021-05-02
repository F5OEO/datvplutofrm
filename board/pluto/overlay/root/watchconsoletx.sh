#!/bin/sh
#https://www.analog.com/media/cn/technical-documentation/user-guides/AD9364_Register_Map_Reference_Manual_UG-672.pdf
#F5UII: determine which device to use depending on the version of hardware
check_rev=$(iio_info | grep "PlutoSDR Rev." | awk -F "Rev." '{print $2}'| cut -c1-1)
dev=$(
case $check_rev in
   ('B') echo "1";;
   ('C') echo "0";;
   ('D') echo "0";;
   (*) echo "1";;
esac)
echo "Rev $check_rev => Device number is $dev.";
ptton()
{
    #PTT on GPIO 0 AND GPIO 2 (GPIO 1 should be not touched)
	echo 0x27 0x50 > /sys/kernel/debug/iio/iio:device$dev/direct_reg_access
	mosquitto_pub -t plutodvb/status/tx -m true
}

pttoff()
{
        echo 0x27 0x00 > /sys/kernel/debug/iio/iio:device$dev/direct_reg_access
		mosquitto_pub -t plutodvb/status/tx -m false
}


echo manual_tx_quad > /sys/bus/iio/devices/iio:device$dev/calib_mode
#Manual GPIO
echo 0x26 0x10 > /sys/kernel/debug/iio/iio:device$dev/direct_reg_access
pttoff

while :
do
inotifywait -e modify /sys/bus/iio/devices/iio\:device$dev/out_voltage0_hardwaregain
gain=$(cat /sys/bus/iio/devices/iio:device$dev/out_voltage0_hardwaregain)
if [ "$gain" = "-40.000000 dB" ] ; then
echo "SdrConsole PTT OFF"
pttoff
else
        if [ "$gain" = "0.000000 dB" ] ; then
                sleep 2
                gain=$(cat /sys/bus/iio/devices/iio:device$dev/out_voltage0_hardwaregain)
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

