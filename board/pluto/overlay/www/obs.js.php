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
      function obsconnect() {
      

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

      
      const ConnectionOpened = (data) => {
        console.log('OBS connection Opened');
        $("#streamingOBS,#recordingOBS").prop("disabled",false);
        $("#streamingOBS,#recordingOBS").css('background-color','');
        $('#buttonstreaming,#buttonrecording').tooltipster("destroy");
        $('#buttonstreaming').attr('title','Click for toggle OBS Studio streaming').tooltipster({ delay: 100,maxWidth: 500,speed: 300,interactive: true,animation: 'grow',trigger: 'hover',position : 'top-left'});
        $('#buttonrecording').attr('title','Click for toggle OBS Studio recording').tooltipster({ delay: 100,maxWidth: 500,speed: 300,interactive: true,animation: 'grow',trigger: 'hover',position : 'top-left'});        

        const streamstarting = (data) => {
           $("#buttonstreaming").fadeOut(0,function(){
             $(this).html('Streaming starting...').fadeIn();
           });
        };
        const streaming = (data) => {
          $("#buttonstreaming").fadeOut(0,function(){
            $(this).html('Stop streaming').fadeIn();
            $(this).css({ 'color' : 'red' });
          });
        };
        const streamstopping = (data) => {
           $("#buttonstreaming").fadeOut(0,function(){
             $(this).html('Streaming stopping...').fadeIn();
           });
        };
        const streamstopped = (data) => {
           $("#buttonstreaming").fadeOut(0,function(){
             $(this).html('Start streaming').fadeIn();
             $(this).css({ 'color' : '#fff' });
           });
        };
        const recordingstarting = (data) => {
          console.log('recordingstarted'+data);
          $("#buttonrecording").fadeOut(0,function(){
            $(this).html('Recording starting...').fadeIn();
          });
        };
        const recordingstarted = (data) => {
          console.log('recordingstarted'+data);
          $("#buttonrecording").fadeOut(0,function(){
            $(this).html('Stop recording').fadeIn();
            $(this).css({ 'color' : 'red' });
          });
        };
        const recordingstopping = (data) => {
          console.log('recordingstarted'+data);
          $("#buttonrecording").fadeOut(0,function(){
            $(this).html('Recording stopping...').fadeIn();
          });
        };
        const recordingstopped = (data) => {
          console.log('recordingstarted'+data);
          $("#buttonrecording").fadeOut(0,function(){
            $(this).html('Start recording').fadeIn();
            $(this).css({ 'color' : '#fff' });
          });
        };
        obs.on('StreamStarting', (data) => streamstarting(data));
        obs.on('StreamStarted', (data) => streaming(data));
        obs.on('StreamStopping', (data) => streamstopping(data));
        obs.on('StreamStopped', (data) => streamstopped(data));
        obs.on('RecordingStarting', (data) => recordingstarting(data));
        obs.on('RecordingStarted', (data) => recordingstarted(data));
        obs.on('RecordingStopping', (data) => recordingstopping(data));
        obs.on('RecordingStopped', (data) => recordingstopped(data));

        $('#buttonstreaming').click(function() {
          obs.send("StartStopStreaming", "");
        });
        $('#buttonrecording').click(function() {
          obs.send("StartStopRecording", "");
        });


      };
      const ConnectionClosed = (data) => {
        console.log('OBS connection Closed (or Lost)');
        $("#streamingOBS,#recordingOBS").prop("disabled",true);
        $("#streamingOBS,#recordingOBS").css('background-color','#b4b8b8');
        $('#buttonstreaming,#buttonrecording').tooltipster("destroy");
        $('#buttonstreaming,#buttonrecording').attr('title','Currently no connection established with OBS Studio. <ul><li>Check the <a href="setup.php#linkdatvsettings">configured parameters</a>.</li><li>Start OBS Studio and enable its websocket server (Tools menu).</li>').tooltipster({ delay: 100,maxWidth: 500,speed: 300,interactive: true,animation: 'grow',trigger: 'hover',position : 'top-left'});

      };


      obs.on('ConnectionOpened', (data) => ConnectionOpened(data));
      obs.on('ConnectionClosed', (data) => ConnectionClosed(data));
}

      obsconnect();

      function obssetbanner(source,bannertext) { 
        var obscmd = {
        "source" : source,
        "text" : bannertext,
        "read_from_file" : false,
        "chatlog" : false,
        "chatlog_lines" : "1"
        
        }
        //obscmd.settings.server=serverstring;
        //obscmd.settings.key=','+callsign+',';
        obs.send("SetTextGDIPlusProperties", obscmd);
      }

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