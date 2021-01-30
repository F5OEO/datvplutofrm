(
sleep 1
while read ligne
do
echo  $ligne

if echo "$ligne" | grep -q "mount"; then
  sleep 2;
fi

if echo "$ligne" | grep -q "reboot"; then
  sleep 60;
fi
sleep 0.1
done < patch_for_telnet.txt) | nc 192.168.1.120 23


 
