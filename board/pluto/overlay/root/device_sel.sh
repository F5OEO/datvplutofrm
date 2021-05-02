#!/bin/sh
#F5UII: determine which device to use depending on the version of hardware
# param 'compact' return device number so that can be use in php script by return of a shell_exec command
check_rev=$(iio_info | grep "PlutoSDR Rev." | awk -F "Rev." '{print $2}'| cut -c1-1)
dev=$(
case $check_rev in
   ('B') echo "1";;
   ('C') echo "0";;
   ('D') echo "0";;
   (*) echo "1";;
esac)
if [[ $1 && $1 = "compact" ]]; then
 echo $dev;
else
 echo "// Hardw Rev. $check_rev => Device number is $dev.";
fi
