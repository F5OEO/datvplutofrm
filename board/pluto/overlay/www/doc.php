  <?php
  session_start();
  ?>
  <?php
  if ( isset( $_POST[ 'savefw' ] ) ) {
    exec( '/root/writeconfig_to_env.sh' );
  }
  ?>
  <?php
  if ( isset( $_POST[ 'reboot' ] ) ) {
    exec( '/sbin/reboot' );
  }
  ?>
  <?php
  if ( isset( $_POST[ 'delpatch' ] ) ) {
    exec( 'rm  /mnt/jffs2/patch.zip' );
  }
  ?>
  <!doctype html>
  <html>
  <head>
    <meta charset="UTF-8">

    <title>PlutoDVB Documentation</title>
    <meta name="description" content="PlutoDVB Documentation ">
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/u16Websocket.js"></script>
    <script src="lib/js.cookie.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <script src="lib/mqttws31.js"></script>      
    <script src="lib/mqtt.js.php?page=<?php echo basename($_SERVER["SCRIPT_FILENAME"]); ?>"></script>        
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">
    <link href="img/favicon-32x32.png" rel="icon" type="image/png" />
  </head>

  <body>

    <?php include ('lib/menu_header.php'); ?>


  <!--  <header id="maintitle"> <h1>PlutoDVB Documentation </h1>

  </header> -->



<div id="dochtml"></div>

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


$( "#dochtml" ).load( "index.html" , (function(){
  $(this).find('header,nav').remove();
}
  )
)
//$('#dochtml').find('header').remove();
//$('#dochtml').find('nav').remove();

</script>
<script>
  MQTTconnect();
</script>
</body>
</html>
