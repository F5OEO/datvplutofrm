while :
do
ffmpeg -analyzeduration 8000000 -skip_frame nokey -r 1 -fflags +genpts -f mpegts -i udp://230.0.0.10:10000  -c:v bmp -vframes 1 -f rawvideo -y /root/frame.bmp
cp /root/frame.bmp /www/frame.bmp
done
