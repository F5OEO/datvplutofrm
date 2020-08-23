while :
do
nc -lu -p 11000  | /root/pluto_dvb -m DVBS2 -g 0.9 -t 437e6
done

