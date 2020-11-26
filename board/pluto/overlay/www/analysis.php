    <?php

    session_start();

    ?>


    <!doctype html>
    <html>
    <head>
    <meta charset="UTF-8">

    <title>ADALM-PLUTO DVB Analyzer</title>
    <meta name="description" content="ADALM-PLUTO DVB Analyzer ">
    <link href="lib/analysis.ico" rel="icon" type="image/x-icon" />
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <script src="lib/jquery-3.5.1.min.js"></script>
    <script src="lib/tooltip.js"></script>
    <style>
        .column_1 {
          float: left;
          width: 75%;
        }
        .column_2 {
          float: left;
          width: 25%;
        }
        /* Clear floats after the columns */
        .row:after {
          content: "";
          display: table;
          clear: both;
        }
    </style>
    </head>
    <header id="top">

      <div id="col1">
        &nbsp;
      </div>
      <div id='col3'>   
      <div class="anchor">
        Firmware version : <?php
        $fwver = shell_exec ( 'cat /www/fwversion.txt' );
        echo "$fwver";
        ?><br/> 
        Mods by Chris <a href="https://www.f5uii.net/?o=<?php
            $fwver = shell_exec ( 'cat /www/fwversion.txt' );
            echo "$fwver";
            ?>" title="Go to Chris blog and ressources" target="_blank">F5UII.net</a>&nbsp; <a href="https://twitter.com/f5uii/" title="Go to f5uii profile on twitter"><img style="width: 20px;" src="./img/tw.png" alt="Twitter Logo"></a> ▪️ <a href="https://twitter.com/F5OEOEvariste/" title="Go to Tweeter">F5OEO: <img style="width: 20px;" src="./img/tw.png" alt="Twitter Logo"></a>
      </div>
    </div>
    
    <div id="col2">
    <nav style="text-align: center;">
        <a class="button" href="pluto.php">Controller</a>
        <a class="button" href="index.html">Documentation</a>
      
    </nav>
  </div>



    </header>


    <h2>Transport stream analysis</h2>
    <hr>
    <table style="padding-bottom: 21px;">
        <tr>
        <td>Receiver analysis <span  class="note tooltip" title="Allows you to set the stream source of the analysis<br>no = transmitter analysis / yes = receiver analysis <ul><li>In the Controller panel, on Setup Receiver tab, specify the IP address and port of the UDP broadcast.</li><li>For those who use Minitiouner by author F6DZP, this information can be found in the <i>minitiouneConfig.ini</i> file, in section <i>[UDP]</i>, and variables <i>TS_AddrUDP</i>, <i>TS_Port</i></li> "> ( ℹ️ ) </span></td>
          <td>
            <div class="checkcontainer">
              <input type="checkbox" id="analysis_source" name="analysis_source">
              <label for="analysis_source" aria-describedby="label"><span class="ui"></span>UDP source</label>
            </div>
          </td>

        </tr>
    </table>
    <div class="row">
      <div class="column_1">
          <div style="height:40%; width:100%;">
            <canvas id="TsChart" ></canvas>
          </div>
      </div>
      <div class="column_2">
        <h4>Null packets<span id='pidtotal'></span></h4>
        <ul>
        <li>Instantaneous : <span id="pnull">0</span> %</li><li>average over 5s : <span id="meannull">0</span> % </li>
        <li>average over 1 min :<span id="mean_pnull_1min"></span> %</li>
        </ul>
        
       <!-- SDT : Service Description Table, PAT : Program Association Table, PMT : Program Map Table -->
       <h4>Distribution</h4>
        <div style="height:100%; width:100%;">
            <canvas id="pie-area"></canvas>
        </div>


      </div>
    </div>


    <h2>Video buffer analysis</h2>
    <hr>

    <div style="height:40%; width:75%;">
    		<canvas id="PcrChart" ></canvas>
    </div>
    
         
    <script src="Chart.bundle.js"></script>
    <script src="lib/chartjs-plugin-labels.js"></script>
    <script>
    //timer loop to request status information
    setInterval(function() {
        if ($( "#analysis_source" ).is(":checked")==true) {
             request_status('rcv');
        } else { 
             request_status();
        }  

    	
    }, 1000);

    $("#analysis_source").change(function() {
        if(this.checked) {
          $.get( "rcv_analyse.php?cmd=start", function( data ) {//Read temps of the pluto
          console.log ("Receiver analysis "+data)
          });
            
        } else {
          $.get( "rcv_analyse.php?cmd=stop", function( data ) {//Read temps of the pluto
          console.log ("Receiver analysis "+data)
          });
        }
    });

    var datats=[0,0,0,0,0,0];
    var pidts;
    var BitrateChart;

    var PcrChart;
    var pcrpts;
    var pcrptslabel;

    function request_status(source){
    if (source === undefined) {
        source ='';
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    //document.getElementById("status").innerHTML = this.responseText;
    analysis = JSON.parse(this.responseText);

    datats=analysis.bitrate;
    pidts=analysis.pid;
    pcrpts=analysis.pcr;
    pcrptslabel=analysis.pcrlabel;
    if (pidts.includes('0(PAT)') ==  false) {
        pidts.push ('0(PAT)');
        datats.push(0);
    }
    if (pidts.includes('17(SDT)') ==  false) {
        pidts.push ('17(SDT)');
        datats.push(0);
    }
    if (pidts.includes('256(Video)') ==  false) {
        pidts.push ('256(Video)');
        datats.push(0);
    }
    if (pidts.includes('257(Audio)') ==  false) {
        pidts.push ('257(Audio)');
        datats.push(0);
    }
    if (pidts.includes('4096(PMT)') ==  false) {
        pidts.push ('4096(PMT)');
        datats.push(0);
    }                    
    if (pidts.includes('8191(Null packets)') ==  false) {
        pidts.push ('8191(Null packets)');
        datats.push(0);
    }
    var i=0;
    var pidtotal = 0;
    var pidnull=0;
    while (pidts[i]) {
        if (pidts[i].substring(0,4)=='8191') {
            pidnull = datats[i]
           
        } 
        pidtotal += datats[i];
        
        i++;
    }
     $('#pnull').text((pidnull/pidtotal*100).toFixed(1)+'');
     $('#pidtotal').text(' among '+pidtotal.toLocaleString('fr')+' kbits/s of total data');
    }
    };

    xmlhttp.open("GET", "requests.php?status&source="+source, true);
    xmlhttp.send();

    //['PAT', 'SDT', 'Video', 'Audio', 'PMT', 'Null packets'],
    Chart.defaults.global.animation.duration = 0;
    Chart.defaults.global.legend.display = false;
    Chart.defaults.global.tooltips.enabled = false;
    var ctx = document.getElementById('TsChart').getContext('2d');
    BitrateChart = new Chart(ctx, {
    type: 'horizontalBar',

    data: {
    labels: pidts,
    datasets: [{

    
    backgroundColor: [
    'rgba(255, 99, 132, 0.2)',
    'rgba(54, 162, 235, 0.2)',
    'rgba(255, 206, 86, 0.2)',
    'rgba(75, 192, 192, 0.2)',
    'rgba(153, 102, 255, 0.2)',
    'rgba(255, 159, 64, 0.2)'
    ],
    borderColor: [
    'rgba(255, 99, 132, 1)',
    'rgba(54, 162, 235, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)'
    ],
    borderWidth: 35,
    //barThickness: 25,
    data: datats,
    options: {
        
            scales: {
                xAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return 'kbits' + value;
                        }
                    }
                }]
            },
            plugins: {
                    labels: {
                        render: 'value'
                    }
            }
        }
    }]
    }
    });

    var ctx2 = document.getElementById('PcrChart').getContext('2d');
    PcrChart = new Chart(ctx2, 
    {
        type: 'line',
        data:
        {
            labels: pcrptslabel,
            datasets: [
            {
            
            data:pcrpts,
            fill :false,
            backgroundColor: function(ctx2) {
                var index = ctx2.dataIndex;
                var value = ctx2.dataset.data[index];
                return value < 0 ? 'red' :  // draw negative values in red
                     'green';
            }
            }
            ]
        } ,
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return '' + Math.round(value * 1) / 1;
                        }
                    }
                }]
            }
        }
    }
    );

    var ctx3 = document.getElementById('pie-area').getContext('2d');
    PcrChart = new Chart(ctx3, 
    {
                    type: 'pie',
            data: {
                datasets: [{
                    data: datats,
                    backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                    ],
                    borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                    ],      

                    label: 'Dataset 1'
                }],
                labels: pidts
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                rotation : 0.5*Math.PI,

                plugins: {
                    labels: {
                      render: 'percentage',
                      //fontColor: ['red', 'red', 'red'],
                      precision: 0,
                      arc : true,
                      textShadow: true,
                      position: 'outside',  
                       outsidePadding: 0, 
                       textMargin: 0               }
                },
            }
    });

    }

var mean_pnull = [0,0,0,0,0];
var mean_pnull_1min = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
    (function loop() {
      setTimeout(function () {
        mean_pnull.shift();
        mean_pnull_1min.shift();
        mean_pnull.push (parseFloat($('#pnull').text()));
        mean_pnull_1min.push (parseFloat($('#pnull').text()));
        var total = 0;
        var total_1min = 0;
        for(var i = 0; i < mean_pnull.length; i++) {
            total += mean_pnull[i];
        }
        for(var i = 0; i < mean_pnull_1min.length; i++) {
            total_1min += mean_pnull_1min[i];
        }
        var avg = total / mean_pnull.length;
        $('#meannull').text(avg.toFixed(1));
         avg = total_1min / mean_pnull_1min.length;
        $('#mean_pnull_1min').text(avg.toFixed(1));        
        
        loop()
      }, 1000);
    }());
    </script>
    </body>
    </html>