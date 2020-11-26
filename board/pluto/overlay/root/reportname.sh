#cat /root/analyse.txt | grep '^pid:' | grep ':pid=8191:' | sed -e 's/^.*:bitrate=//' -e 's/:.*//'
#cat /root/analyse.txt | grep '^pid:' | sed -e 's/^.*:pid=//' -e 's/:.*//'
if [ -n "${1}" ] && [ $1 = "rcv" ]
then
  cat /root/analysercv.txt | grep ':name' | sed -e 's/^.*:name=//' -e 's/:.*//'
else
  cat /root/analyse.txt | grep ':name' | sed -e 's/^.*:name=//' -e 's/:.*//'
fi  



