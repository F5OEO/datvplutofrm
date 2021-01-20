(
sleep 1
while read ligne
do
echo  $ligne
sleep 0.1
done < patch_for_telnet.txt) | nc 192.168.1.120 23


 
