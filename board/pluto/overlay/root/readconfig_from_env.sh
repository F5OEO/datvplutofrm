rm /www/settings.txt

OIFS=$IFS
IFS=';'
line=$(fw_printenv datvset|cut -f2- -d'=')
echo line $line
for x in $line
do
    echo "$x" >>/www/settings.txt
done

IFS=$OIFS
