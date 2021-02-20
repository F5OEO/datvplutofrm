    <?php

    session_start();

    ?>
    <!doctype html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>ADALM-PLUTO DVB Analyzer</title>
    <meta name="description" content="ADALM-PLUTO DVB Analyzer ">
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link href="img/favicon-32x32.png" rel="icon" type="image/png" />
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    <link type="text/css" href="./lib/tooltip.css" rel="stylesheet">
    <link type="text/css" href="./lib/menu.css" rel="stylesheet">    
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

        #img_cycler{
            position:relative;
            height: 165px;
            padding-left:113px;

        }
        #img_cycler img{
            position:absolute;
            z-index:1 ;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 5px;
            background-color: white;
            height: 150px;


        }
        #img_cycler img.active{z-index:3;
       
        }

    </style>
    </head>
      <?php include ('lib/menu_header.php'); ?>
    <table style="padding-top: 21px;">
        <tr>
        <td>Receiver analysis <span  class="note tooltip" title="Allows you to set the stream source of the analysis<br>no = transmitter analysis / yes = receiver analysis <ul><li>In the Controller panel, on Setup Receiver tab, specify the IP address and port of the UDP broadcast.</li><li>For those who use Minitiouner by author F6DZP, this information can be found in the <i>minitiouneConfig.ini</i> file, in section <i>[UDP]</i>, and variables <i>TS_AddrUDP</i>, <i>TS_Port</i></li><li>Remember to enable UDP broadcasting either from the Minitiouner control panel (UDP button), or by activating it by default, with udp_switch=1 (Section buttons) in <i>minitiouneConfig.ini</i>.</li> "> ( ℹ️ ) </span></td>
          <td>
            <div class="checkcontainer">
              <input type="checkbox" id="analysis_source" name="analysis_source">
              <label for="analysis_source" aria-describedby="label"><span class="ui"></span>UDP source</label>
            </div>
          </td>

        </tr>
    </table>
    <h2>Transport stream analysis</h2>
    <hr>

    <div style="padding-left:113px;">
      <i>Name : <span id="servicename"></span>
       Provider : <span id="providername"></span></i>
    </div>
    
    <div id="img_cycler">
      <img src="./img/patern.png" class="active" >
      <img src="./img/patern.png"  >

    </div>
      
    
    
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

    var TabisActive = true;

     if (localStorage.getItem('analysis_source')=='rcv') {
        $( "#analysis_source" ).prop('checked',true);
     } else {
        $( "#analysis_source" ).prop('checked',false);
     }
    //timer loop to request status information
    setInterval(function() {
        if ($( "#analysis_source" ).is(":checked")==true) {
             request_status('rcv');
        } else { 
             request_status();
        }  


    	
    }, 1000);

    setInterval (function() {
        img_cycle();
    } , 2000);




    function img_cycle() {
    var $active = $('#img_cycler .active');
    if (($('#providername').text()=='(unknown)') || ($('#providername').text()=='') || TabisActive == false)
    {
        var $src= "img/patern.png";
    } else {
        var $src= "frame.png?timestamp=" + new Date().getTime();    
    }
   
    $.get($src, function() {
        var $next = ($active.next().length >0 ) ? $active.next() : $('#img_cycler img:first');
        $next.css ('z-index',2);
        $next.attr('src',$src); 
        $active.fadeOut(1000, function() {
             
            $active.css('z-index',1).show().removeClass('active');
            $next.css('z-index',3).addClass('active');
        })
        })
        .fail(function() {
         
        })
        
    }



    $("#analysis_source").change(function() {
        if(this.checked) {
          $.get( "rcv_analyse.php?cmd=start", function( data ) {//Read temps of the pluto
          console.log ("Receiver analysis "+data);
          localStorage.setItem ('analysis_source','rcv');
          });
            
        } else {
          $.get( "rcv_analyse.php?cmd=stop", function( data ) {//Read temps of the pluto
          console.log ("Receiver analysis "+data);
          localStorage.setItem ('analysis_source','pluto');
          });
        }
    });

    var datats=[0,0,0,0,0,0];
    var pidts;
    var BitrateChart;

    var PcrChart;
    var pcrpts;
    var pcrptslabel;
    var servicename;
    var providername;

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
    servicename=analysis.name;
    providername=analysis.provider;
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
     $('#servicename').text(servicename);
     $('#providername').text(providername);
     
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
    (
    function loop() {
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


    window.onfocus = function () {
        TabisActive = true;
        console.log('tab active');
     };

    window.onblur = function () {
        TabisActive = false;
        console.log('tab inactive');
     };




    </script>


    </body>
    </html>