//<script >
    var mqtt;
    var reconnectTimeout = 2000;
    var host="<?php echo shell_exec('echo -n $(ip -f inet -o addr show eth0 | cut -d\  -f 7 | cut -d/ -f 1)'); ?>";
    //var host='192.168.1.8'; //debug purpose
    var port=9001;
    
    function onFailure(error,message) {
      console.log("MQTT connection attempt to Host "+host+" Failed");
      $('#brokerconnected').text('Disconnected');
      setTimeout(MQTTconnect, reconnectTimeout);
        }
    function onMessageArrived(msg){
      out_msg="MQTT Message received "+msg.payloadString+"";
      out_msg=out_msg+"    Topic "+msg.destinationName;
     // console.log(out_msg);
      if (typeof update_textgen == 'function') {
         if (msg.destinationName.substr(0,16)=='plutodvb/subvar/') {
            update_textgen (msg.destinationName,msg.payloadString); 
         }
         
      }

      if (typeof update_status == 'function') {
         if ((['plutodvb/status/','plutodvb/subpage']).indexOf(msg.destinationName.substr(0,16)) >= 0) {
            update_status (msg.destinationName,msg.payloadString); 
         }
         
      }
      if ('<?php echo ($_GET["page"]); ?>'=='pluto.php') {
          if ((msg.destinationName=='plutodvb/subpage') && (msg.payloadString=='textgen.php')) {
            init_pluto_obj();

          }
      }

    }
    
    function sendmqtt(destination,messagestr) {
          message = new Paho.MQTT.Message(messagestr);
          message.destinationName = destination;
          mqtt.send(message);
    }

    function onConnect() {
    // Once a connection has been made, make a subscription and send a message.
    console.log("MQTT connected");
    $('#brokerconnected').text('Connected');
      if (typeof json2nestable2 == 'function') {
        json2nestable2();
         
      }

    mqtt.subscribe("plutodvb/started");
    mqtt.subscribe("plutodvb/var");
    mqtt.subscribe("plutodvb/subpage");
    mqtt.subscribe("plutodvb/status/#");
    message = new Paho.MQTT.Message('{ "page" : "<?php echo ($_GET["page"]); ?>" }');
    message.destinationName = "plutodvb/page";
    mqtt.send(message);
    sendmqtt("plutodvb/subpage","<?php echo ($_GET["page"]); ?>");
    init_pluto_obj();

    }
    function MQTTconnect() {
    console.log("connecting to "+ host +" "+ port);
    mqtt = new Paho.MQTT.Client(host,port,"/mqtt","uii-ihm-<?php echo ($_GET["page"])."-".uniqid(); ?>");
    //document.write("connecting to "+ host);
    var options = {
      timeout: 3,
      onSuccess: onConnect,
      onFailure: onFailure,
      
      mqttVersion:4
       };
    mqtt.onMessageArrived = onMessageArrived
    mqtt.onConnectionLost = onFailure
    
    mqtt.connect(options); //connect
    }

    function init_pluto_obj() {
          //On page load 
    if ((typeof t !== 'undefined')  && $( t ).length ) {
    $(t+'  input,select,textarea,hidden').each(function (inp) {

        obj= $(this).attr('id');
        if (obj==undefined) {
          obj=$(this).attr('name');
        }
        if ($(this).is(':checkbox')) {
          val= $(this).is(':checked');
        } else {
          val=$(this).val();
        }
      if (mqtt.isConnected()) {
       // sendmqtt('plutodvb/var', '{"'+obj+'":"'+ val +'"}' ) ;
        sendmqtt('plutodvb/subvar/'+obj, val ) ;
        console.log ('t='+t+' obj='+obj+' val='+val);
      }});

    }
    }
   