rm /tmp/fwenvline.txt
while read p; do
  printf "$p;" >>/tmp/fwenvline.txt
done </www/settings.txt
printf "$p;" >>/tmp/fwenvline.txt

read line < /tmp/fwenvline.txt
fw_setenv datvset "$line"
