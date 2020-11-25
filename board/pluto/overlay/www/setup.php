    <?php

    session_start();

    $file ='config.txt';
    if (file_exists($file) && is_readable($file))
    {

      $handle = @fopen($file, "r");
      $headfile="";
      $configlines="";
      if ($handle) {
          while (($buffer = fgets($handle, 4096)) !== false) {
              
              if (substr($buffer,0,2)=="# ") {
                $headfile=$headfile.$buffer;
              }
              else {
                $configlines=$configlines.$buffer;
              }
          }
          if (!feof($handle)) {
              echo "Error: fgets() has failed\n";
          }
          fclose($handle);
      }
    



      //$ini_array = parse_ini_file($file,true,INI_SCANNER_RAW);
      $ini_array = parse_ini_string($configlines,true,INI_SCANNER_RAW);
      //var_dump($ini_array);
     // write_input($ini_array['NETWORK']['ipaddr'],'ipaddr');
      

      write_php_ini($ini_array,'config2.txt', $headfile);


    }
    else 
    {
      echo "The $file is not available, this page can not be used !";
      die();
    }

  /*  function write_input($s,$id) {
      if ( isset($s) ) {
        $('#'+$id).val($s);
      }

    } */

    function write_php_ini($array, $file, $headlines)
    {

      $res = array();
      $res[]= $headlines;
      foreach($array as $key => $val)
      {
        if(is_array($val))
        {
          $res[] = "[$key]";
          foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : ''.$sval.'');
        }
        else $res[] = "$key = ".(is_numeric($val) ? $val : ''.$val.'');
        $res[]="";
       

      }
      safefilerewrite($file, implode("\r\n", $res));
       
    }

    function safefilerewrite($fileName, $dataToSave)
    {    if ($fp = fopen($fileName, 'w'))
    {
      $startTime = microtime(TRUE);
      do
      {            $canWrite = flock($fp, LOCK_EX);
           // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
       if(!$canWrite) usleep(round(rand(0, 100)*1000));
     } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

        //file was locked so now we can store information
     if ($canWrite)
      {            fwrite($fp, $dataToSave);
        flock($fp, LOCK_UN);
      }
      fclose($fp);
    }

  }

  ?>


  <!doctype html>
  <html>
  <head>
    <meta charset="UTF-8">

    <title>ADALM-PLUTO DVB General setup</title>
    <meta name="descriwsion" content="ADALM-PLUTO DVB General Setup ">
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">

  </head>
  <header id="top">
    <div class="anchor">
      <a href="https://twitter.com/F5OEOEvariste/" title="Go to Tweeter">F5OEO: <img style="width: 32px;" src="./img/tw.png" alt="Twitter Logo"></a>
    </div>
  </header> 
  <body>

    <nav style="text-align: center;">
     <a class="button" href="analysis.php">Analysis</a>
     <a class="button" href="pluto.php">Controller</a>
     <a class="button" href="index.html">Documentation</a>

   </nav>
   <h1><strong>ADALM-PLUTO</strong> General Setup</h1>
   <hr>
   <h2>Network</h2>


   <form id="global" name="global" method="post" action = "javascript:save_global_setup();">


    <h3>USB on Ethernet </h3>
    <p>This <i>USB on Ethernet</i> setup section corresponds to the IP address mounted when the pluto is plugged in USB on a computer.</p>

    <table>
      <tr>
        <td>IP address</td>
        <td><input type="text" id="ipaddr" value="<?php echo $ini_array['NETWORK']['ipaddr']; ?>" maxlength="15" size="16"></td>
        <td>Host IP address<br></td>
        <td><input type="text" id="ipaddr_host" value="<?php echo $ini_array['NETWORK']['ipaddr_host']; ?>" maxlength="15" size="16"> </td>
      </tr>
      <tr>
        <td>Network mask</td>
        <td><input type="text" id="netmask" value="<?php echo $ini_array['NETWORK']['netmask']; ?>" maxlength="15" size="16"></td>
        <td>Gateway IP address<br></td>
        <td><input type="text" id="gateway" value="<?php if (isset($ini_array['NETWORK']['gateway'])) echo $ini_array['NETWORK']['gateway']; ?>" maxlength="15" size="16"> </td>
      </tr>
    </table>
    <h3>Ethernet </h3>
    <p>This <i>Ethernet</i> setup section corresponds to the IP address mounted when the pluto is plugged over an Ethernet USB adapter on a local area network.</p>

    <table>
      <tr>
        <td>DHCP (dynamic IP)<br></td>
        <td><div class="checkcontainer">
          <input type="checkbox" id="eth_dhcp" name="eth_dhcp">
          <label for="eth_dhcp" aria-describedby="label"><span class="ui"></span> <span id='eth_dhcp_label'> static</span></label>
        </div> </td>
        <td>IP address</td>
        <td><input type="text" id="ipaddr_eth" value="<?php if (isset($ini_array['USB_ETHERNET']['ipaddr_eth'])) echo $ini_array['USB_ETHERNET']['ipaddr_eth']; ?>" maxlength="15" size="16"></td>
      </tr>
      <tr>
        <td>Network mask</td>
        <td><input type="text" id="netmask_eth" value="<?php if (isset($ini_array['USB_ETHERNET']['netmask_eth'])) echo $ini_array['USB_ETHERNET']['netmask_eth']; ?>" maxlength="15" size="16"></td>
        <td>Gateway IP address<br></td>
        <td><input type="text" id="gateway_eth" value="<?php if (isset($ini_array['USB_ETHERNET']['gateway_eth'])) echo $ini_array['USB_ETHERNET']['gateway_eth']; ?>" maxlength="15" size="16"> </td>
      </tr>

    </table><br>
    <h3>Wifi or Access Point </h3>
    <p>(Coming later)</p>
    <h3>Radio </h3>
    <p></p>

    <table>

      <tr>
        <td>Xo Correction</td>
        <td><input type="text" id="xo_correction" value="<?php if (isset($ini_array['SYSTEM']['xo_correction'])) echo $ini_array['SYSTEM']['xo_correction']; ?>" maxlength="4" size="4"></td>

      </tr>

    </table><br>

    <br>      
    <input type="submit" value="Apply Settings" id ="submit_setup">
  </form>

</div>

<script>
  function dhcp () {
    if ($("#eth_dhcp").is(":checked")==true) {
      console.log ($('#eth_dhcp_label').text('dynamic'));
      $("#ipaddr_eth").prop('disabled', true);
      $("#netmask_eth").prop('disabled', true);
      $("#gateway_eth").prop('disabled', true);

    }
    else  {
     console.log ($('#eth_dhcp_label').text('static'));
     $("#ipaddr_eth").prop('disabled', false);
     $("#netmask_eth").prop('disabled', false);
     $("#gateway_eth").prop('disabled', false);            
   }
 }


 $("#eth_dhcp").click(function() {
  dhcp ();
  console.log('dhcp'); 
});

 function save_global_setup (){        
  $.ajax({
          url: 'global_save.php', // url where to submit the request
          type : "POST", // type of action POST || GET
          dataType : 'html', // data type
          processData: false,
          
          data : $("#global").serialize(), // post data || get data
          success : function(result) {
            console.log(result);
          },
          error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
          }
        })

};
</script>



</body>
</html>