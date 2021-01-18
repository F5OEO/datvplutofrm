    <?php
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

    <title>PlutoDVB maintenance</title>
    <meta name="description" content="PlutoDVB maintenance ">
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
   <h1>PlutoDVB maintenance</h1> <i>(under developpement)</i>
   
   <hr>
   <h2>Check for updates</h2>
    
    Your browser must have an internet connection. <br>
    <form method="post">
      <p>
        <button name="check_update">Check</button>
      </p>
    </form>
<br>
   <h2>Save for next reboot</h2>

Warning : In order to write permanently, you need first to apply setting then Save to flash. <br>
<form method="post">
  <p>
    <button name="savefw">Save to flash</button>
  </p>
</form>


<br>

<h2>Upload a new firmware or new patch</h2>

<?php
if ( isset( $_SESSION[ 'message' ] ) && $_SESSION[ 'message' ] ) {
  printf( '<b>%s</b>', $_SESSION[ 'message' ] );
  unset( $_SESSION[ 'message' ] );
}
?>
<form method="POST" action="upload.php" enctype="multipart/form-data">
  <div> <span>Upload a File (pluto.frm or patch.zip):</span>&nbsp;
    <input type="file" name="uploadedFile" id="file_firm" />
  </div><br>
  <input type="submit" name="uploadBtn" value="Upload" />
</form>
<br>


<h2>Delete patch</h2>
<div class="xterm">
  <?php 
  $patchchk = shell_exec ( 'ls -la /mnt/jffs2 | grep -c patch.zip' ); 
  echo "<b>$patchchk</b> &nbsp; Loaded<br/>"; 
  if($patchchk)
  {
    $listzip = shell_exec ( 'unzip -l /mnt/jffs2/patch.zip' );
    $separator = "\r\n";
    $line = strtok($listzip, $separator);

    while ($line !== false) {
      echo $line;
      echo "<br>";
      $line = strtok( $separator );
    }
  }
  ?>
</div>

This will restore to the last firmware state, removing the patches added in overlay.
<br>After erasing the files, you will have to reboot manually or with below reboot button to resume with the basic firmware. <br>
<form method="post">
  <p>
    <button name="delpatch">Delete Patch</button>
  </p>
</form>
<br>
<h2>Reboot</h2>

This is needed for apply your saved modifications made in Pluto Configuration section. Take a moment to check your settings before applying them.<br>
<form >
  <p>
    <button name="reboot" id="reboot">Reboot the Pluto</button><span id='reboot_saved'></span>
  </p>
</form>


<script>

  $('#reboot').click(function() {
    $.get("requests.php?cmd=/sbin/reboot", function (data,status) {
      if (status=='success') {
        $('#reboot_saved').fadeIn(250).fadeOut(1500);

      }
    });
  });

  $( document ).ready(function() {
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
  }
});



});

</script>
</body>
</html>
