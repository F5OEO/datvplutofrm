Init()
{
        echo none > /sys/class/leds/led0:green/trigger
        echo 0 > /sys/class/leds/led0:green/brightness
}

Start()
{
                echo 1 > /sys/class/leds/led0:green/brightness
                sleep 2
                 echo 0 > /sys/class/leds/led0:green/brightness
}

Space()
{
 sleep 1
        for i in `seq 1 2`;
        do
                echo 1 > /sys/class/leds/led0:green/brightness
                sleep 0.1
                echo 0 > /sys/class/leds/led0:green/brightness
                sleep 0.1
        done
        sleep 1

}

number=0
Blink()
{
        for i in `seq 1 $number`;
        do
                echo 1 > /sys/class/leds/led0:green/brightness
                sleep 0.5
                echo 0 > /sys/class/leds/led0:green/brightness
                sleep 0.5
        done
}

Init

while :
do
Start
number=2
Blink
Space
number=8
Blink
Space
number=4
Blink
sleep 1
done

