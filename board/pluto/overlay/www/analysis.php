    <?php

    session_start();

    ?>


    <!doctype html>
    <html>
    <head>
    <meta charset="UTF-8">

    <title>ADALM-PLUTO DVB Analyzer</title>
    <meta name="description" content="ADALM-PLUTO DVB Analyzer ">
    <link type="text/css" href="./img/style.css" rel="stylesheet">
    </head>
    <header id="top">
    <div class="anchor">
    <a href="https://twitter.com/F5OEOEvariste/" title="Go to Tweeter">F5OEO: <img style="width: 32px;" src="./img/tw.png" alt="Twitter Logo"></a>
    </div>
    </header>

    <nav style="text-align: center;">
    	<a class="button" href="pluto.php">Controller</a>
    	<a class="button" href="index.html">Documentation</a>
    	
    </nav>
    <h2>Transport stream analysis</h2>
    <hr>

    <div style="height:50%; width:75%;">
    		<canvas id="TsChart" ></canvas>
    </div>
    <h2>Video buffer analysis</h2>
    <div style="height:50%; width:75%;">
    		<canvas id="PcrChart" ></canvas>
    </div>
    <hr>

         
    <script src="Chart.bundle.js"></script>
    <script>
    //timer loop to request status information
    setInterval(function() {
      request_status();
    	
    }, 1000);

    var datats=[0,0,0,0,0,0];
    var pidts;
    var BitrateChart;

    var PcrChart;
    var pcrpts;
    var pcrptslabel;

    function request_status(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    //document.getElementById("status").innerHTML = this.responseText;
    analysis = JSON.parse(this.responseText);

    datats=analysis.bitrate;
    pidts=analysis.pid;
    pcrpts=analysis.pcr;
    pcrptslabel=analysis.pcrlabel;
    console.log( pcrpts );
    }
    };
    xmlhttp.open("GET", "requests.php?status", true);
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
    borderWidth: 10,
    barThickness: 10,
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
    }
    </script>
    </body>
    </html>