wget -P /root/ https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-arm.zip
unzip /root/ngrok-stable-linux-arm.zip -d /root
/root/ngrok authtoken 7g11Eh4DHP44u6siYpBQW_69kWZEKBKyydBHf9PTzuS
cp /root/ngrok.yml /root/.ngrok2/ngrok.yml
/root/ngrok start --all > /dev/null &