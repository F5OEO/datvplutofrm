    <?php
    // F5UII : Setup page. The outputs are multiples files, working by ajax call with global_save.php.

    session_start();
    require_once ('./lib/functions.php');
    if ( isset( $_POST[ 'reboot' ] ) ) {
     exec( '/sbin/reboot' );
    }
  
  ?>
  <!doctype html>

  <html>
  <!-- if "?xpert" in url then expert parameters are displayed  -->
  <head>
    <meta charset="UTF-8">

    <title>PlutoDVB status</title>
    <meta name="description" content="PlutoDVB Status ">
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />    
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <link type="text/css" href="lib/nestable.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <script src="lib/jquery.nestable.js"></script> 
    <script src="lib/mqttws31.js"></script>  
    <script src="lib/mqtt.js.php?page=<?php echo basename($_SERVER["SCRIPT_FILENAME"]); ?>"></script>  
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">
    <link href="img/favicon-32x32.png" rel="icon" type="image/png" />
  </head>

  <body>

       <?php include ('lib/menu_header.php'); ?>
<!--
    <nav style="text-align: center;">
     <a class="button" href="analysis.php">Analysis</a>
     <a class="button" href="pluto.php">Controller</a>
     <a class="button" href="index.html">Documentation</a>
   </nav>
 -->
   <h1>PlutoDVB status</h1>
   
   <hr>
   <h2>Pluto Status</h2>
   <!--
   Activ Mode :
   TX status : On air / Standby

-->
   <ul>
    <li> Last change of Pluto output : <span id="tx">No change since this page is displayed</span>
    <li> FPGA Temperature : <span id = "fpgatemp"></span> °C</li>
    <li> Analog Digital Converter Temperature : <span id = "adtemp"></span> °C</li>
    <li> Voltage : <span id = "voltage"></span> V</li>
    <li> Current : <span id = "current"></span> mA</li>
    <li> Consumption: <span id = "energycons"></span> VA</li>
    <!--   <li> CPU </li>
    <li> Processus </li>
    <li> Available memory RAM</li>
    <li> Available space on main ROM volume </li>
    <li> Available space on extended ROM volume (/mnt/jffs2) </li>
    <li> Ethernet emission rate </li>
    <li> Ethernet reception rate </li>
    <li> USB emission rate </li>
    <li> USB reception rate </li> -->
    <li><span class="note tooltip" title="MQTT ensuring communication between the human-machine interface and the PlutoDVB core. Also allows interaction with PlutoDVB from network clients. In the case where the status remains unchanged in the disconnected state, the interface is inoperative."> MQTT broker connection </span> : <span id = "brokerconnected"></span></li>
    <li> Last page loaded : <span id = "lastpage"></span></li>
   </ul>
   
   <!-- <h2>Encoder Status</h2> -->
   <?php 


function get(){


$username = 'admin';
$password = '12345';
$auth = base64_encode($username.":".$password);


  $headers = array(
    'Authorization: Basic ' . $auth,
    'Content-type: ' . "application/x-www-form-urlencoded; charset=UTF-8"
  );

  $context = array (
      'http' => array (
        'method' => 'GET',
        'header'=> $headers,
        'content' => '',
        )
      );


  $ctx = stream_context_create($context);
  $data = file_get_contents("http://192.168.1.120/action/get?subject=devinfo", false, $ctx);
  return $data;



}

 //   var_dump(simplexml_load_string(get()));



   ?>


<script>
  $( document ).ready(function() {
 
 $('#brokerconnected').text('');
  MQTTconnect();
  //MQTT send messages
$('body').on('change', 'input,select', function () {
  if (mqtt.isConnected()) {
    obj= $(this).attr('id');
    if (obj==undefined) {
      obj=$(this).attr('name');
    }
    if ($(this).is(':checkbox')) {
      val= $(this).is(':checked');
    } else {
      val=$(this).val();
    }

    sendmqtt('plutodvb/var', '{"'+obj+'":"'+ val +'"}' ) ;
    //$('#brokerconnected').text('Connected');

  }
 
});



});


  function update_status(variable, value) {

if (variable.substr(0,16)=='plutodvb/status/') {
  varid = variable.substring(16);
  $('#'+varid).text(value).change();
} else 
if (variable.substr(0,16)=='plutodvb/subpage') {
  $('#lastpage').text(value);

}



}

$('#current,#voltage').on('change', function(){
  $('#energycons').text((parseFloat($('#current').text()/1000)*parseFloat($('#voltage').text())).toFixed(1));
})

$('#tx').on ('change', function(){
  if ($('#tx').text()=='true') {
    var today = new Date();
    $('#tx').text('Transmit ON at '+today.getHours()+':'+today.getMinutes()+':'+today.getSeconds());
  } else   if ($('#tx').text()=='false') {
    var today = new Date();
    $('#tx').text('Transmit OFF at '+today.getHours()+':'+today.getMinutes()+':'+today.getSeconds());
  } 

});

</script>
</body>
</html>
