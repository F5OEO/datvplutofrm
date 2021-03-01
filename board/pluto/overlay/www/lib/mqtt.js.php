//<script >
    var mqtt;
    var reconnectTimeout = 2000;
    var host="<?php echo shell_exec('echo -n $(ip -f inet -o addr show eth0 | cut -d\  -f 7 | cut -d/ -f 1)'); ?>";
    if(host == "")
    {
         host="<?php echo shell_exec('echo -n $(ip -f inet -o addr show usb0 | cut -d\  -f 7 | cut -d/ -f 1)'); ?>";
    }

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
      if (msg.destinationName.substr(0,16)=='plutodvb/subvar/') { 

        //control display for external mqtt changes 
        var id_wanted = msg.destinationName.substr(16, msg.destinationName.length-16);
        var val_wanted = msg.payloadString;
        
       // console.log('id_wanted:'+id_wanted+' +msg.payloadString' +val_wanted);

        if ($('.form_modulator [name="'+id_wanted+'"]').length>=1) {  //is in a modulator



         /* if ( $('#id-tabs-content').children().hasClass('activ-tab')==true ) { //in transmission **not possible because maybe tab is not loaded**
            tab_sel = '#'+ $('#id-tabs-content').find('.activ-tab').attr('id')+' ';
          } else { */
            tab_sel = t;
          } else  { tab_sel =''; }
          //}
          

          if ($(tab_sel+ '#'+id_wanted).length==1)  {
             if ($(tab_sel+ '#'+id_wanted).is(':checkbox')) { 
              if (((val_wanted.toLowerCase() == 'true') || (val_wanted.toLowerCase() =='false')) && ($(tab_sel+'#'+id_wanted).prop('checked')!= val_wanted))  {
                let isBool = (val_wanted.toLowerCase() == 'true');
                $(tab_sel+'#'+id_wanted).prop("checked", isBool);
              }
             }

            if ($(tab_sel+'#'+id_wanted).val()!=val_wanted) {
              $(tab_sel+'#'+id_wanted).val(val_wanted);
              $(tab_sel+'#'+id_wanted).trigger('change');
              $(tab_sel+'#'+id_wanted).trigger('click');
              $(tab_sel+'#'+id_wanted).trigger('input');

            }
            
          } else if ($(tab_sel+'[name="'+id_wanted+'"]').length==1) {
            if ($(tab_sel+'[name="'+id_wanted+'"]').val()!=val_wanted) {
              $(tab_sel+'[name="'+id_wanted+'"]').val(val_wanted);
              $(tab_sel+'[name="'+id_wanted+'"]').trigger('change');
              $(tab_sel+'[name="'+id_wanted+'"]').trigger('click');
              $(tab_sel+'[name="'+id_wanted+'"]').trigger('input');
            }
          } 
        }

        

      if (typeof update_textgen == 'function') {
         if ((msg.destinationName.substr(0,16)=='plutodvb/subvar/') || (msg.destinationName.substr(0,16)=='plutodvb/status/')) {
            update_textgen (msg.destinationName,msg.payloadString); 
         }
         
      }

      if (typeof update_status == 'function') {
         if ((['plutodvb/status/','plutodvb/subpage']).indexOf(msg.destinationName.substr(0,16)) >= 0) {
            update_status (msg.destinationName,msg.payloadString); 
         }
         
      }
      /*if ('<?php echo ($_GET["page"]); ?>'=='pluto.php') {
          if ((msg.destinationName=='plutodvb/subpage') && (msg.payloadString=='textgen.php')) {
            init_pluto_obj();

          }
      }*/
      if (msg.destinationName=='plutodvb/textgen/updaterequest') {
            init_pluto_obj();
      }
      if (msg.destinationName=='plutodvb/modulator/tab_to_activate') {
             $("#tabs li:nth-child("+msg.payloadString+") a").trigger("click");
      }
      if (msg.destinationName=='plutodvb/modulator/apply') { 
          if (typeof save_modulator_setup == 'function') {
            save_modulator_setup();
          }
      }

      
      if (msg.destinationName=='plutodvb/status/ts/bufferstate') {
            $('#bufferstatus').html('&nbsp;'+msg.payloadString+'&nbsp;');
            let c;
            if (msg.payloadString == 'Nominal')  {
               c= '#009015';
            } else
            if (msg.payloadString == 'Underflow')  {
               c= '#D57100';
            } else
            if (msg.payloadString == 'Overflow')  {
               c= '#CC0000';
            }
            $('#bufferstatus').css('background', c).css('color','white');
            setTimeout(function(){$('#bufferstatus').css('background', '').css('color','')}, 500);
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
    mqtt.subscribe("plutodvb/subvar/#");
    mqtt.subscribe("plutodvb/subpage");
    mqtt.subscribe("plutodvb/status/#");
    mqtt.subscribe("plutodvb/modulator/tab_to_activate"); //pass a integer to activate the n tab
    mqtt.subscribe("plutodvb/modulator/apply"); //Apply the modulator settings
    mqtt.subscribe("plutodvb/textgen/updaterequest");
    mqtt.subscribe("plutodvb/status/ts/bufferstate");
    message = new Paho.MQTT.Message('{ "page" : "<?php echo ($_GET["page"]); ?>" }');
    message.destinationName = "plutodvb/page";
    mqtt.send(message);
    sendmqtt("plutodvb/subpage","<?php echo ($_GET["page"]); ?>");
    //init_pluto_obj();

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
        //console.log ('t='+t+' obj='+obj+' val='+val);
      }});

    }
    }
   