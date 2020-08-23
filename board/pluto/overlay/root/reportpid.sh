#cat /root/analyse.txt | grep '^pid:' | grep ':pid=8191:' | sed -e 's/^.*:bitrate=//' -e 's/:.*//'
#cat /root/analyse.txt | grep '^pid:' | sed -e 's/^.*:bitrate=//' -e 's/:.*//'
cat /root/analyse.txt | grep '^pid:' | sed -e 's/^.*:pid=//' -e 's/:.*//'

