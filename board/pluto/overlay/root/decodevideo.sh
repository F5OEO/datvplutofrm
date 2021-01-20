#!/bin/sh
# We could do a continuous decoding BUT even by keeping just I picture, ffmpeg decodes all frames and CPU is too high

while :
do
#ffmpeg -analyzeduration 8000000 -discard nokey -fflags +genpts -f mpegts -i udp://$1  -c:v bmp -vframes 1 -f rawvideo -y /root/frame.bmp
ffmpeg -discard 'noref' -f mpegts -i udp:/$1 -vframes 1 -update 1 -y /www/frame.png
cp /root/frame.png /www/frame.png
done
