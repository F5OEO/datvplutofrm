// <script> 
<?php  
      require_once ('./lib/functions.php');
if ((isset ($general_ini[1]['OBS']['use_obs_steering'])) && ($general_ini[1]['OBS']['use_obs_steering']=='on')) {
 
  if (isset ($general_ini[1]['OBS']['ipaddr_obs'])) {
     $ipaddr_obs = $general_ini[1]['OBS']['ipaddr_obs'];
  }
  if (isset ($general_ini[1]['OBS']['obs_port'])) {
     $obs_port = $general_ini[1]['OBS']['obs_port'];
  };
  if (isset ($general_ini[1]['OBS']['obs_password'])) {
     $obs_password = $general_ini[1]['OBS']['obs_password'];
  };

 ?>
      
      const obs = new OBSWebSocket();

      var obs_websocket_host = "<?php echo $ipaddr_obs ?>";
      var obs_websocket_port = "<?php echo $obs_port ?>";
      var obs_websocket_pass = "<?php echo $obs_password ?>";

      obs.connect({
          address: obs_websocket_host + ":" + obs_websocket_port,
          password: obs_websocket_pass,
        })
        .then(() => {
          obs_ws_connected = true;
          //obsstreamurl();
          console.log(obs.send('GetVersion'));
        })
        .catch((err) => {
          console.log(err);
        });
      obs.on("error", (err) => {
        console.error("socket error:", err);
        obs_ws_connected = false;
      });



      obs.sendCallback('SetStreamSettings', (error) => {
        console .log('OBS websocket, SetStreamSettings error', error);
      });


      function obsstreamurl(serverstring,callsign) {
        console.log('serverstring = '+serverstring);

        var obscmd = {
        "type" : "rtmp_custom",
        "settings" : {
          "server" : "",
          "key" : ",NOCALL,",
          "save": "true"
          }
        }
        obscmd.settings.server=serverstring;
        obscmd.settings.key=','+callsign+',';
        obs.send("SetStreamSettings", obscmd);
      }




    <?php } ?>