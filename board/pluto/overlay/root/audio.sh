#!/bin/sh
mkfifo /root/bigtspipeaudio


while :
do
#killall ffmpeg
rm infortmpaudio
FREQ=""
VIDEORATE=""
FREEDV=800XA
SSTV=m1
GAINBF=1.0
resx=512
resy=384

 (ffmpeg -f flv -listen 1 -timeout -1 -rtmp_buffer 100 -i rtmp://0.0.0.0:7373/ -ss 2 -c:v copy -c:a copy -f flv -y /root/bigtspipeaudio 2>infortmpaudio ) &
while [ "$VIDEORATE" == "" ]
do
 
 sleep 1

 VIDEORATE=$(grep -o " Video:.*" infortmpaudio | cut -f4 -d, | cut -f1 -d'k') 
 echo Wait for RTMP connexion
done


FREQ=$(grep -o "match up:.*" infortmpaudio | cut -f2 -d,)
VIDEORES=$(grep -o "Stream #0:1:.*" infortmpaudio | cut -f3 -d,) 

echo Video : $VIDEORATE $VIDEORES 



MODE=$(grep -o "match up:.*" infortmpaudio | cut -f3 -d,)
GAIN=$(grep -o "match up:.*" infortmpaudio | cut -f4 -d,)
GAINBF=$(grep -o "match up:.*" infortmpaudio | cut -f5 -d,)

#PARAMETER DEPENDING ON MOD
FREEDV=$(grep -o "match up:.*" infortmpaudio | cut -f6 -d,)
SSTV=$(grep -o "match up:.*" infortmpaudio | cut -f6 -d,)
#Spectrum
resx=$(grep -o "match up:.*" infortmpaudio | cut -f6 -d,)
resy=$(grep -o "match up:.*" infortmpaudio | cut -f7 -d,)
linerepeat=$(grep -o "match up:.*" infortmpaudio | cut -f8 -d,)

if [ "$ASKPCRPTS" = "" ]; then
        echo pcrnot setting $PCRPTS
else
	PCRPTS=$ASKPCRPTS
	echo pcr setting $PCRPTS
fi

if [ "$CALIB" = "calib" ]; then
        CALIB="-q 1"
	echo calibration
else
	CALIB=""
fi

AUDIO=""


CALL=$(grep -o "Unexpected stream.*" infortmpaudio | cut -f2 -d,)
IP=$(grep -o "Unexpected stream.*" infortmpaudio | cut -f3 -d,)


if [ "$IP" = "" ]; then
        echo No debug IP
else
echo ip $IP
UDPIP=$IP
fi


echo Freq before $FREQ
if [[ $(echo "($FREQ) > 6000" |bc -l) -ge 1 ]]; then
FREQ=$(echo "($FREQ)-8089.5" | bc)
echo QO100 $FREQ
fi

ssbmodul()
{
csdr convert_i16_f |csdr bandpass_fir_fft_cc 0 0.1 0.001| csdr dsb_fc \
 | csdr bandpass_fir_fft_cc 0.001 0.04375 0.001 |csdr gain_ff $GAINBF| csdr fir_interpolate_cc 8 0.06 | csdr clipdetect_ff|csdr limit_ff | csdr convert_f_i16 | /root/plutotx -s 512000 -f $FREQ"e6" -g $GAIN -B 200000 -b 5000 -T 2
}

ssbmodulagc()
{
csdr convert_i16_f | csdr fastagc_ff| csdr dsb_fc \
 | csdr bandpass_fir_fft_cc 0.001 0.04375 0.001 |csdr gain_ff $GAINBF| csdr fir_interpolate_cc 2 0.01 | csdr clipdetect_ff|csdr limit_ff | csdr convert_f_i16 | /root/plutotx -s 128000 -f $FREQ"e6" -g $GAIN -B 200000 -b 5000 -T 2
}
#| csdr fastagc_ff

if [ "$MODE" = "SSB" ]; then
#SSB
ffmpeg -loglevel panic -f flv -i /root/bigtspipeaudio -ss 2 -vn -f s16le -c:a pcm_s16le -ac 1 -ar 64000 -filter:a "volume=12dB" - | ssbmodulagc
fi

if [ "$MODE" = "FREEDV" ]; then
#FREEDV
ffmpeg -f flv -i /root/bigtspipeaudio -ss 2 -vn -f s16le -c:a pcm_s16le -ac 1 -ar 8000 - | freedv_tx $FREEDV - - | ffmpeg -f s16le -ac 1 -ar 8000 -i - -c:a pcm_s16le -ac 1 -ar 8000 -f s16le -c:a pcm_s16le -ac 1 -ar 64000 -filter:a "volume=12dB" - | ssbmodulagc
fi

if [ "$MODE" = "SSTV" ]; then
#SSTV
ffmpeg -f flv -i /root/bigtspipeaudio -ss 2 -an -s 320x256 -vframes 1 -pix_fmt rgb24 -y sstv.rgb \
 && /root/pisstv -r 64000 -p $SSTV sstv.rgb && cat sstv.rgb.wav |  ssbmodulagc
fi

if [ "$MODE" = "SPECTRUM" ]; then
#SPECTRUM

ffmpeg -f flv -i /root/bigtspipeaudio -ss 2 -an -s  "$resx""x""$resy" -vframes 1 -pix_fmt rgb24 -y /root/specpicture.rgb \
 && /root/paintrf -i /root/specpicture.rgb -s 100000 -t 0.02 -x $resx -y $resy -l $linerepeat && cat /root/specpicture.rgb.iq | csdr convert_i16_f | csdr gain_ff $GAINBF|  csdr bandpass_fir_fft_cc -0.02 0.02 0.001| csdr clipdetect_ff|csdr convert_f_i16 > /root/specfinal.iq && /root/plutotx -i /root/specfinal.iq -s 100000 -f $FREQ"e6" -g $GAIN -B 200000 -b 25000
 
#ffmpeg -f flv -i /root/bigtspipeaudio -ss 2 -an -s 512x384 -vframes 1 -pix_fmt yuv420p -y specpicture.yuv \
# && /root/paintrf -i specpicture.yuv -s 1000000 -t 0.05 && /root/plutotx -i specpicture.yuv.iq -s 1000000 -f $FREQ"e6" -g $GAIN -B 2000000 -b 5000
fi

echo endstreaming
done

