    <?php
    // F5UII : Setup page. The outputs are multiples files, working by ajax call with global_save.php.

    session_start();
    require ('./lib/functions.php');
    if ( isset( $_POST[ 'reboot' ] ) ) {
     exec( '/sbin/reboot' );
    }
  
    $file_config ='/opt/config.txt';
    $file_general = '/mnt/jffs2/etc/settings-datv.txt';
    $dir = '/mnt/jffs2/etc/';
    if (true==false) // replace false by true for developping on debug server
    {
      echo "<i>Attention, in developping mode </i><br>";
      $file_config ='config.txt';
      $file_general = 'settings-datv.txt';
      $dir= "";
    }

    $config_ini = readinifile($file_config);
    $headfile = $config_ini[0];
    $network_config = $config_ini[1];
    $general_ini = readinifile($file_general);
    $datv_config = $general_ini[1];    
  

  ?>
  <!doctype html>

  <html>
  <!-- if "?xpert" in url then expert parameters are displayed  -->
  <head>
    <meta charset="UTF-8">

    <title>Pluto status</title>
    <meta name="description" content="ADALM-PLUTO DVB General Setup ">
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <link type="text/css" href="lib/nestable.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <script src="lib/jquery.nestable.js"></script> 
    <script src="lib/mqttws31.js"></script>  
    <script src="lib/mqtt.js.php?page=<?php echo basename($_SERVER["SCRIPT_FILENAME"]); ?>"></script>  
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">
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
   <h1>Pluto status</h1> <i>(under developpement)</i>
   
   <hr>
   <ul>
    <li> Temperature </li>
    <li> CPU </li>
    <li> Processus </li>
    <li> Available memory RAM</li>
    <li> Available space on main ROM volume </li>
    <li> Available space on extended ROM volume (/mnt/jffs2) </li>
    <li> Ethernet emission rate </li>
    <li> Ethernet reception rate </li>
    <li> USB emission rate </li>
    <li> USB reception rate </li>
   </ul>
   
<script>

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
  }
});

</script>
<script>
  $( document ).ready(function() {
  MQTTconnect();


});

</script>
</body>
</html>
