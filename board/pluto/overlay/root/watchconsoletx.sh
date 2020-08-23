#https://www.analog.com/media/cn/technical-documentation/user-guides/AD9364_Register_Map_Reference_Manual_UG-672.pdf

ptton()
{
    #PTT on GPIO 0 AND GPIO 2 (GPIO 1 should be not touched)
	echo 0x27 0x50 > /sys/kernel/debug/iio/iio:device1/direct_reg_access
}

pttoff()
{
        echo 0x27 0x00 > /sys/kernel/debug/iio/iio:device1/direct_reg_access
}


echo manual_tx_quad > /sys/bus/iio/devices/iio:device1/calib_mode
#Manual GPIO
echo 0x26 0x10 > /sys/kernel/debug/iio/iio:device1/direct_reg_access
pttoff

#Set -40db forcing Rx Mode
#echo -40 > /sys/bus/iio/devices/iio:device1/out_voltage0_hardwaregain

while :
do
gain=$(cat /sys/bus/iio/devices/iio:device1/out_voltage0_hardwaregain)
powerdown=$(cat /sys/bus/iio/devices/iio:device1/out_altvoltage1_TX_LO_powerdown)

if [ "$gain" = "-40.000000 dB" ] ; then
modeconsole=1
echo 0 > /sys/bus/iio/devices/iio:device1/out_altvoltage1_TX_LO_powerdown
echo manual_tx_quad > /sys/bus/iio/devices/iio:device1/calib_mode
fi

if [ "$powerdown" = "1" ] ; then
modeconsole=0
fi


if [ "$modeconsole" = "1" ] ; then
	if [ "$gain" = "-40.000000 dB" ] ; then
        	echo RX $gain $powerdown
		pttoff
	else
		echo TX $gain $powerdown
		ptton
	fi
else
      echo mode dvb
fi
sleep 0.1
done
