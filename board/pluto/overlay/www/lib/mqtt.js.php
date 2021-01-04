//<script >
    var mqtt;
    var reconnectTimeout = 2000;
    var host="<?php echo shell_exec('echo -n $(ip -f inet -o addr show eth0 | cut -d\  -f 7 | cut -d/ -f 1)'); ?>";
    //var host='192.168.1.8'; //debug purpose
    var port=9001;
    
    function onFailure(message) {
      mqtt_connected = false;
      console.log("MQTT connection attempt to Host "+host+"Failed");
      setTimeout(MQTTconnect, reconnectTimeout);
        }
    function onMessageArrived(msg){
      out_msg="MQTT Message received "+msg.payloadString+"<br>";
      out_msg=out_msg+" MQTT Message received Topic "+msg.destinationName;
      console.log(out_msg);

    }
    
    function sendmqtt(destination,messagestr) {
          message = new Paho.MQTT.Message(messagestr);
          message.destinationName = destination;
          mqtt.send(message);
    }

    function onConnect() {
    // Once a connection has been made, make a subscription and send a message.
    mqtt_connected = true;
    console.log("MQTT connected");

    mqtt.subscribe("plutodvb/started");
    message = new Paho.MQTT.Message('{ "page" : "<?php echo ($_GET["page"]); ?>" }');
    message.destinationName = "plutodvb/page";
    mqtt.send(message);
    
    }
    function MQTTconnect() {
    console.log("connecting to "+ host +" "+ port);
    mqtt = new Paho.MQTT.Client(host,port,"uii-ihm");
    //document.write("connecting to "+ host);
    var options = {
      timeout: 3,
      onSuccess: onConnect,
      onFailure: onFailure,
       };
    mqtt.onMessageArrived = onMessageArrived
    
    mqtt.connect(options); //connect
    }
   