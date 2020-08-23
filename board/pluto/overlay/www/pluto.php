<?php

session_start();

?>
<?php
if ( isset( $_POST[ 'savefw' ] ) ) {
  exec( '/root/writeconfig_to_env.sh' );
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<style>
.slidernewpower {
  width: 500px;
}

.btn {
  border: none;
  color: white;
  padding: 14px 28px;
  font-size: 16px;
  cursor: pointer;
}

.success {background-color: #4CAF50;} /* Green */
.success:hover {background-color: #46a049;}

.info {background-color: #2196F3;} /* Blue */
.info:hover {background: #0b7dda;}

.warning {background-color: #ff9800;} /* Orange */
.warning:hover {background: #e68a00;}

.danger {background-color: #f44336;} /* Red */ 
.danger:hover {background: #da190b;}

.default {background-color: #e7e7e7; color: black;} /* Gray */ 
.default:hover {background: #ddd;}
</style>

<title>ADALM-PLUTO DVB Controller</title>
<meta name="description" content="ADALM-PLUTO DVB Controller ">
<link type="text/css" href="./img/style.css" rel="stylesheet">
</head>

<body onload="load()">
<header id="top">
<div class="anchor">
<a href="https://twitter.com/F5OEOEvariste/" title="Go to Tweeter">F5OEO: <img style="width: 32px;" src="./img/tw.png" alt="Twitter Logo"></a>
</div>
</header>
<nav style="text-align: center;">
	<a class="button" href="analysis.php" target="_blank">Analysis</a>
	<a class="button" href="index.html" target="_blank">Documentation</a>
        <a class="button" href="https://wiki.batc.org.uk/QO-100_WB_Bandplan" target="_blank">QO-100 WB Bandplan</a>
</nav>

<header id="maintitle"> <h1><strong>ADALM-PLUTO</strong> DATV Controller</h1>

  <div class="anchor">Thanks Rob M0DTS for help. Mods by G4EML for codec selection and sound enable</div>
  <div class="anchor">Mods by Roberto IS0GRB (Transverter OL/Prov.Name/Fw version (August 22th, 2020)</div>
</div>
</header>
<section>
 <table>
    <tr>
      <td>PTT
			<td>
	 
			<button id="ptt" onClick="request_ptt();"></button>
	</td>
	<td>
	<p id="textptt" style="display:none"></p>
	</td>
	</tr>
	</table>
</section>
<h2>Modulator</h2>
	
<hr>
<form action="save.php" method="post">
  <table>
    <tr>
      <td>Callsign<br>(DVB Program Name)</td>
      <td><input type="text" name="callsign" value="NOCALL"></td>
      <td>DVB Provider Name<br>(output FwVer_ProvName)</td>
      <td><input type="text" name="provname" value="XXXXXXXXXXXX" maxlength="12" size="14"> (max 12 chars)</td>
    </tr>
		<tr><td>PCR/PTS</td>
<td><div class="slidecontainer">
  <input type="range" min="200" max="2000" value="800" class="slider" name="pcrpts" oninput="update_slider_pts()">
  <span id="pcrptstext"></span>
</div>
</td>
<td>PAT period</td>
<td><div class="slidecontainer">
  <input type="range" min="100" max="1000" value="200" class="slider" name="patperiod" oninput="update_slider_pat()">
  <span id="pattext"></span>
</div>
</td>

</tr>
    <tr>
      <td>Freq-Manual</td>
      <td><input type="text" name="freq" value="2403.75">
        70M-6G</td>
      <td>Freq-Channel</td>
      <td><select name="channel" onchange="upd_freq();calc_ts()">
          
          <option value="2405.25-2000KS2">2000KS2 (10494.75)</option>
          <option value="2406.75-2000KS3">2000KS3 (10496.25)</option>
          
          <option value="2405.25-1500KS2">1500KS2 (10494.75)</option>
          <option value="2406.75-1500KS3">1500KS3 (10496.25)</option>          

          <option value="2403.75-1000KS1">1000KS1 (10493.25)</option>
          <option value="2405.25-1000KS2">1000KS2 (10494.75)</option>
          <option value="2406.75-1000KS3">1000KS3 (10496.25)</option>

          <option value="2403.25-500KS1">500KS1 (10492.75)</option>
          <option value="2403.75-500KS2">500KS2 (10493.25)</option>
          <option value="2404.25-500KS3">500KS3 (10493.75)</option>
          <option value="2404.75-500KS4">500KS4 (10494.25)</option>
          <option value="2405.25-500KS5">500KS5 (10494.75)</option>
          <option value="2405.75-500KS6">500KS6 (10495.25)</option>
          <option value="2406.25-500KS7">500KS7 (10495.75)</option>
          <option value="2406.75-500KS8">500KS8 (10496.25)</option>
          <option value="2407.25-500KS9">500KS9 (10496.75)</option>
          <option value="2407.75-500KS10">500KS10 (10497.25)</option>
          <option value="2408.25-500KS11">500KS11 (10497.75)</option>
          <option value="2408.75-500KS12">500KS12 (10498.25)</option>
          <option value="2409.25-500KS13">500KS13 (10498.75)</option>
          <option value="2409.75-500KS14">500KS14 (10499.25)</option>

          <option value="2403.25-333KS1">333KS1 (10492.75)</option>
          <option value="2403.75-333KS2">333KS2 (10493.25)</option>
          <option value="2404.25-333KS3">333KS3 (10493.75)</option>
          <option value="2404.75-333KS4">333KS4 (10494.25)</option>
          <option value="2405.25-333KS5">333KS5 (10494.75)</option>
          <option value="2405.75-333KS6">333KS6 (10495.25)</option>
          <option value="2406.25-333KS7">333KS7 (10495.75)</option>
          <option value="2406.75-333KS8">333KS8 (10496.25)</option>
          <option value="2407.25-333KS9">333KS9 (10496.75)</option>
          <option value="2407.75-333KS10">333KS10 (10497.25)</option>
          <option value="2408.25-333KS11">333KS11 (10497.75)</option>
          <option value="2408.75-333KS12">333KS12 (10498.25)</option>
          <option value="2409.25-333KS13">333KS13 (10498.75)</option>
          <option value="2409.75-333KS14">333KS14 (10499.25)</option>

          <option value="2403.25-250KS1">250KS1 (10492.75)</option>
          <option value="2403.75-250KS2">250KS2 (10493.25)</option>
          <option value="2404.25-250KS3">250KS3 (10493.75)</option>
          <option value="2404.75-250KS4">250KS4 (10494.25)</option>
          <option value="2405.25-250KS5">250KS5 (10494.75)</option>
          <option value="2405.75-250KS6">250KS6 (10495.25)</option>
          <option value="2406.25-250KS7">250KS7 (10495.75)</option>
          <option value="2406.75-250KS8">250KS8 (10496.25)</option>
          <option value="2407.25-250KS9">250KS9 (10496.75)</option>
          <option value="2407.75-250KS10">250KS10 (10497.25)</option>
          <option value="2408.25-250KS11">250KS11 (10497.75)</option>
          <option value="2408.75-250KS12">250KS12 (10498.25)</option>
          <option value="2409.25-250KS13">250KS13 (10498.75)</option>
          <option value="2409.75-250KS14">250KS14 (10499.25)</option>    
      
          <option value="2403.25-125KS1">125KS1 (10492.75)</option>
          <option value="2403.50-125KS2">125KS2 (10493.00)</option>
          <option value="2403.75-125KS3">125KS3 (10493.25)</option>
          <option value="2404.00-125KS4">125KS4 (10493.50)</option>
          <option value="2404.25-125KS5">125KS5 (10493.75)</option>
          <option value="2404.50-125KS6">125KS6 (10494.00)</option>
          <option value="2404.75-125KS7">125KS7 (10494.25)</option>
          <option value="2405.00-125KS8">125KS8 (10494.50)</option>
          <option value="2405.25-125KS9">125KS9 (10494.75)</option>
          <option value="2405.50-125KS10">125KS10 (10495.00)</option>
          <option value="2405.75-125KS11">125KS11 (10495.25)</option>
          <option value="2406.00-125KS12">125KS12 (10495.50)</option>
          <option value="2406.25-125KS13">125KS13 (10495.75)</option>
          <option value="2406.50-125KS14">125KS14 (10496.00)</option>
          <option value="2406.75-125KS15">125KS15 (10496.25)</option>
          <option value="2407.00-125KS16">125KS16 (10496.50)</option>
          <option value="2407.25-125KS17">125KS17 (10496.75)</option>
          <option value="2407.50-125KS18">125KS18 (10497.00)</option>
          <option value="2407.75-125KS19">125KS19 (10497.25)</option>
          <option value="2408.00-125KS20">125KS20 (10497.50)</option>
          <option value="2408.25-125KS21">125KS21 (10497.75)</option>
          <option value="2408.50-125KS22">125KS22 (10498.00)</option>
          <option value="2408.75-125KS23">125KS23 (10498.25)</option>
          <option value="2409.00-125KS24">125KS24 (10498.50)</option>
          <option value="2409.25-125KS25">125KS25 (10498.75)</option>
          <option value="2409.50-125KS26">125KS26 (10499.00)</option>
          <option value="2409.75-125KS27">125KS27 (10499.25)</option>

          <option value="2403.25-66KS1">66KS1 (10492.75)</option>
          <option value="2403.50-66KS2">66KS2 (10493.00)</option>
          <option value="2403.75-66KS3">66KS3 (10493.25)</option>
          <option value="2404.00-66KS4">66KS4 (10493.50)</option>
          <option value="2404.25-66KS5">66KS5 (10493.75)</option>
          <option value="2404.50-66KS6">66KS6 (10494.00)</option>
          <option value="2404.75-66KS7">66KS7 (10494.25)</option>
          <option value="2405.00-66KS8">66KS8 (10494.50)</option>
          <option value="2405.25-66KS9">66KS9 (10494.75)</option>
          <option value="2405.50-66KS10">66KS10 (10495.00)</option>
          <option value="2405.75-66KS11">66KS11 (10495.25)</option>
          <option value="2406.00-66KS12">66KS12 (10495.50)</option>
          <option value="2406.25-66KS13">66KS13 (10495.75)</option>
          <option value="2406.50-66KS14">66KS14 (10496.00)</option>
          <option value="2406.75-66KS15">66KS15 (10496.25)</option>
          <option value="2407.00-66KS16">66KS16 (10496.50)</option>
          <option value="2407.25-66KS17">66KS17 (10496.75)</option>
          <option value="2407.50-66KS18">66KS18 (10497.00)</option>
          <option value="2407.75-66KS19">66KS19 (10497.25)</option>
          <option value="2408.00-66KS20">66KS20 (10497.50)</option>
          <option value="2408.25-66KS21">66KS21 (10497.75)</option>
          <option value="2408.50-66KS22">66KS22 (10498.00)</option>
          <option value="2408.75-66KS23">66KS23 (10498.25)</option>
          <option value="2409.00-66KS24">66KS24 (10498.50)</option>
          <option value="2409.25-66KS25">66KS25 (10498.75)</option>
          <option value="2409.50-66KS26">66KS26 (10499.00)</option>
          <option value="2409.75-66KS27">66KS27 (10499.25)</option>

          <option value="2403.25-33KS1">33KS1 (10492.75)</option>
          <option value="2403.50-33KS2">33KS2 (10493.00)</option>
          <option value="2403.75-33KS3">33KS3 (10493.25)</option>
          <option value="2404.00-33KS4">33KS4 (10493.50)</option>
          <option value="2404.25-33KS5">33KS5 (10493.75)</option>
          <option value="2404.50-33KS6">33KS6 (10494.00)</option>
          <option value="2404.75-33KS7">33KS7 (10494.25)</option>
          <option value="2405.00-33KS8">33KS8 (10494.50)</option>
          <option value="2405.25-33KS9">33KS9 (10494.75)</option>
          <option value="2405.50-33KS10">33KS10 (10495.00)</option>
          <option value="2405.75-33KS11">33KS11 (10495.25)</option>
          <option value="2406.00-33KS12">33KS12 (10495.50)</option>
          <option value="2406.25-33KS13">33KS13 (10495.75)</option>
          <option value="2406.50-33KS14">33KS14 (10496.00)</option>
          <option value="2406.75-33KS15">33KS15 (10496.25)</option>
          <option value="2407.00-33KS16">33KS16 (10496.50)</option>
          <option value="2407.25-33KS17">33KS17 (10496.75)</option>
          <option value="2407.50-33KS18">33KS18 (10497.00)</option>
          <option value="2407.75-33KS19">33KS19 (10497.25)</option>
          <option value="2408.00-33KS20">33KS20 (10497.50)</option>
          <option value="2408.25-33KS21">33KS21 (10497.75)</option>
          <option value="2408.50-33KS22">33KS22 (10498.00)</option>
          <option value="2408.75-33KS23">33KS23 (10498.25)</option>
          <option value="2409.00-33KS24">33KS24 (10498.50)</option>
          <option value="2409.25-33KS25">33KS25 (10498.75)</option>
          <option value="2409.50-33KS26">33KS26 (10499.00)</option>
          <option value="2409.75-33KS27">33KS27 (10499.25)</option>

          <option value="Custom">Custom</option>
        </select>
        (QO-100 DATV Downlink)</td>
	
    </tr>
    <tr>
      <td>Mode</td>
      <td><select name="mode" onchange="upd_mod()" >
          <option value="DVBS2">DVBS2</option>
          <option value="DVBS">DVBS</option>
        </select></td>
      <td>Mod</td>
      <td><select name="mod" onchange="upd_fec()" >
          <option value="QPSK">QPSK</option>
          <option value="8APSK">8PSK</option>
          <option value="16APSK">16APSK</option>
          <option value="32APSK">32APSK</option>
        </select></td>
    </tr>
    <tr>
      <td>SR</td>
      <td><input type="text" name="sr">
        KS</td>
      <td>FEC</td>
      <td><select name="fec" onchange="calc_ts()">
          <option value="12">1/2</option>
          <option value="23">2/3</option>
          <option value="34">3/4</option>
          <option value="56">5/6</option>
          <option value="78">7/8</option>
        </select></td>
    </tr>
    <tr id="pilots_option"  >
      <td >Pilots</td>
      <td><select name="pilots"  onchange="calc_ts()">
          <option value="Off">Off</option>
          <option value="On">On</option>
        </select></td>
      <td id="frame_option" >Frame</td>
      <td><select name="frame"   onchange="calc_ts()">
          <option value="LongFrame">LongFrame</option>
          <option value="ShortFrame">ShortFrame</option>
        </select></td>
    </tr>
    <tr id="rolloff_option">
      <td>rolloff</td>
      <td><select name="rolloff">
          <option value="0.35">0.35</option>
          <option value="0.25">0.25</option>
					<option value="0.20">0.20</option>
        </select></td>
      
      <td>Transverter OL</td>
      <td><select name="trvol" onchange="upd_freq()">
          <option value="0" selected>0 (No Transverter/Converter)</option>
          <option value="1968">1968 (SG-Lab IF 432)</option>
          <option value="1888">1888 (SG-Lab IF 512)</option>
          <option value="1886">1886 (SG-Lab IF 514)</option>
          <option value="1870">1870 (SG-Lab IF 530)</option>
          <option value="1968">1968 (DXPatrol IF 432)</option>
          <option value="1104">1104 (DXPatrol IF 1296)</option>
          <option value="2256">2256 (DXPatrol IF 144)</option>
          </select> MHz</td>
       </tr>
</table>
<table>    
<tr><td>Power (0.1 dB steps)</td>
<td><div class="slidecontainer">
  <input type="range" min="-79" max="10" step="0.1" value="-10" class="slidernewpower" name="power" onchange="update_slider()" oninput="update_slidertxt()">
  <span id="powertext"></span>
</div>
</td>
<div id="advanced">
  <table>
    <tr>
      <td>TS Rate Available (Kb/s)</td>
      <td><div id="tsrate" value=""></div></td>
    </tr>
    <tr>
      <td>Firmware version</td>
	<td><input type="text" name="fwversion" value="" maxlength="4" size="4"> Do not change</td>
</td>
    </tr>
  </table>
</div>
</tr><br>
<input type="submit" value="Apply Settings">
<br><br>
<h2>H264/H265 box control (option)</h2>
<hr>
<table>
<tr> <td>IP (192.168.1.120 default)</td> <td> <input type="text" name="h265box" value="192.168.1.120"></td> </tr>
<tr> <td>Codec</td> <td><select name="codec"> <option value= "H264">H264</option> <option value= "H265">H265</option> </select> </td> </tr>
<tr> <td>Sound</td> <td> <select name="sound"> <option value="On">On</option> <option value="Off">Off</option> </select> </td> </tr>
<tr> <td>Audio Input</td> <td> <select name="audioinput"> <option value="line">Line</option> <option value="HDMI">HDMI</option> </select> </td> </tr>
</table><br>
<input type="submit" value="Apply Settings">
<br><br>
<h2>Advanced (Remux)</h2>
<hr>
<tr id="remux">
      <td>Force compliant (H265box)</td>
      <td><select name="remux">
          <option selected value="1">on</option>
          <option value="0">off</option>
          </select></td>
    </tr>
		<input type="submit" value="Apply Settings">
		</form>
<td><b>Warning : <i>Select ON if you have trouble receiving with continuous blocks</b></i></td>

<br><br>
<h2><strong>Save for next reboot</h2>
<hr>
Warning : In order to write permanently, you need first to apply setting then Save to flash. <br>
<form method="post">
  <p>
    <button name="savefw">Save to flash</button>
  </p>
</form>


<section><br>
  <h2><strong>Upload a new firmware</h2>
	<hr>
</section>
<?php
if ( isset( $_SESSION[ 'message' ] ) && $_SESSION[ 'message' ] ) {
  printf( '<b>%s</b>', $_SESSION[ 'message' ] );
  unset( $_SESSION[ 'message' ] );
}
?>
<form method="POST" action="upload.php" enctype="multipart/form-data">
  <div> <span>Upload a File(pluto.frm):</span>&nbsp;
    <input type="file" name="uploadedFile" />
  </div><br>
  <input type="submit" name="uploadBtn" value="Upload" />
</form>
<br><br>    


<script>
	setInterval(function() {
		request_onair();
}, 1000);

function request_onair()
{
	
	var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
	var text = document.getElementById("textptt");
	text.style.display = "block";
	var IsPowerDown=this.responseText;
	var button = document.getElementById("ptt").innerHTML;
	if(IsPowerDown == '0')
	{
		console.log('On  air status');
		document.getElementById("ptt").innerHTML = 'Switch OFF';
		document.getElementById("textptt").innerHTML  = 'ON AIR';
	}
	else
	{
		console.log('Off air status');
		document.getElementById("ptt").innerHTML = 'Switch ON';
		document.getElementById("textptt").innerHTML  = 'STANDBY';
	}
}
};
xmlhttp.open("GET", "requests.php?onair", true);
xmlhttp.send();


}


function request_ptt(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //document.getElementsByName("power")[0].value = this.responseText;
      }
    };
		
		var button = document.getElementById("ptt").innerHTML;
  
		if (button == 'Switch ON' )
		{
			console.log('PTTON');
    	xmlhttp.open("GET", "requests.php?PTT=on", true);
		}
		else
		{
			console.log('PTTOFF');
			xmlhttp.open("GET", "requests.php?PTT=off", true);
		}	
   		xmlhttp.send();

}


function request_gain_change(level){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //document.getElementsByName("power")[0].value = this.responseText;
      }
    };
    xmlhttp.open("GET", "requests.php?gain=" + level, true);
    xmlhttp.send();
}

function update_slidertxt()
{
//request_gain_change(slider.value);
// Update the current slider value (each time you drag the slider handle)
var slider = document.getElementsByName("power");
var powertxt = document.getElementById("powertext");
  powertxt.innerHTML = slider[0].value + 'dB';
	
}

function update_slider()
{
//request_gain_change(slider.value);
// Update the current slider value (each time you drag the slider handle)
var slider = document.getElementsByName("power");
var powertxt = document.getElementById("powertext");
  powertxt.innerHTML = slider[0].value+ 'dB';
	request_gain_change( slider[0].value);
}

function update_slider_pts()
{var slider = document.getElementsByName("pcrpts");
var powertxt = document.getElementById("pcrptstext");
  powertxt.innerHTML = slider[0].value + 'ms';

}

function update_slider_pat()
{var slider = document.getElementsByName("patperiod");
var powertxt = document.getElementById("pattext");
  powertxt.innerHTML = slider[0].value + 'ms';

}

function upd_freq() {
	document.getElementsByName("freq")[0].value=document.getElementsByName('channel')[0].value.split("-")[0] - document.getElementsByName('trvol')[0].value
        
        if(document.getElementsByName("channel")[0].value=="Custom"){
        document.getElementsByName("freq")[0].value="0"
        }
        
        var item = document.getElementsByName('channel')[0];	
        var chan_array = item.options[item.selectedIndex].text.match(/[a-z]+|[^a-z]+/gi);
	var sr=0;
	if(chan_array[1]=="KS"){
		sr=chan_array[0];
	}
        
	document.getElementsByName("sr")[0].value=sr;
}


function calc_ts(){
	if(document.getElementsByName("mode")[0].value=="DVBS2"){
		var m= document.getElementsByName("mod")[0].selectedIndex+2;
		var p=0;
		var s=0;
		if(document.getElementsByName('frame')[0].value=="ShortFrame"){
			p=16200;
		}else{
			p=64800;
		}
		s=(p/m)
		var po=0;
		// PL header overhead
		if( document.getElementsByName('pilots')[0].value=="On" )
		{
			//po = (s/(90*16))-1;// 1 pilot every 16 blocks (of 90 symbols)
			po = ((s.toFixed(2)/90.0-1.0)/16.0).toFixed(0);// 1 pilot every 16 blocks (of 90 symbols)
			po = po*36;        // No pilot at the end
			a  = s/(90+po+s);
		}
		else
		{
		a = s/(90+s);// No pilots
		}

		// Modulation efficiency
		a = a*m;
		// Take into account pilot symbols
		// TBD
		// Now calculate the useable data as percentage of the frame
		b = (get_usable_data_bits().toFixed(3))/p;
		// Now calculate the efficiency by multiplying the
		// useable bits efficiency by the modulation efficiency
		m_efficiency = b*a;

		document.getElementById('tsrate').innerHTML= (document.getElementsByName('sr')[0].value*m_efficiency).toFixed(3);
	        }else{
		document.getElementById('tsrate').innerHTML=(document.getElementsByName('sr')[0].value*2*(188.0/204.0)*(document.getElementsByName('fec')[0].value.substring(0, 1)/document.getElementsByName('fec')[0].value.substring(1,2))).toFixed(3);
                }
}

function get_usable_data_bits(){
	var fec = document.getElementsByName('fec')[0].value;
	if(document.getElementsByName('frame')[0].value=="LongFrame"){
		var kbch=0;
		switch(fec)
        {
            case "14":
                kbch  = 16008;
                break;
            case "13":
                kbch  = 21408;
                break;
            case "25":
                kbch  = 25728;
                break;
            case "12":
                kbch  = 32208;
                break;
            case "35":
                kbch  = 38688;
                break;
            case "23":
                kbch  = 43040;
                break;
            case "34":
                kbch  = 48408;
                break;
            case "45":
                kbch  = 51648;
                break;
            case "56":
                kbch  = 53840;
                break;
            case "89":
                kbch  = 57472;
                break;
            case "910":
                kbch  = 58192;
                break;
		}
	}else{
		switch(fec )
        {
            case "14":
                kbch  = 3072;
                break;
            case "13":
                kbch  = 5232;
                break;
            case "25":
                kbch  = 6312;
                break;
            case "12":
                kbch  = 7032;
                break;
            case "35":
                kbch  = 9552;
                break;
            case "23":
                kbch  = 10632;
                break;
            case "34":
                kbch  = 11712;
                break;
            case "45":
                kbch  = 12432;
                break;
            case "56":
                kbch  = 13152;
                break;
            case "89":
                kbch  = 14232;
                break;
        }
	}
	return kbch;
}

function upd_mod() {
	var DVBS2_MOD = ["QPSK","8PSK","16APSK","32APSK"];
	while(document.getElementsByName("mod")[0].options.length>0){
		var item = document.getElementsByName("mod")[0];
		item.options.remove(0);
	}
	if(document.getElementsByName("mode")[0].value=="DVBS"){		
		var x=document.createElement("option");
		var opt = document.getElementsByName("mod")[0];
		x.text = "QPSK";
		x.value = "QPSK";
		opt.options.add(x, 0); 
		document.getElementById('pilots_option').style.display='none';
		document.getElementById('frame_option').style.display='none';
		document.getElementById('rolloff_option').style.display='none';
	}else{
		for(i=0;i<DVBS2_MOD.length;i++){
			var x=document.createElement("option");
			var opt = document.getElementsByName("mod")[0];
			x.text = DVBS2_MOD[i];
			x.value = DVBS2_MOD[i];
			opt.options.add(x, i); 	
		}
		document.getElementById('pilots_option').style.display='';
		document.getElementById('frame_option').style.display='';
		document.getElementById('rolloff_option').style.display='';
	}
	upd_fec();
	
}

function upd_fec() {
		var sel=0;
		var lastfec=document.getElementsByName("fec")[0].value
		var DVBS = ["1/2","2/3","3/4","5/6","7/8"];
		var DVBS2_QPSK = ["1/4","1/3","2/5","1/2","3/5","2/3","3/4","4/5","5/6","8/9","9/10"];
		var DVBS2_8PSK = ["3/5","2/3","3/4","4/5","5/6","8/9","9/10"];
		var DVBS2_16APSK = ["2/3","3/4","4/5","5/6","8/9","9/10"];
		var DVBS2_32APSK = ["3/4","4/5","5/6","8/9","9/10"];
		
		if(document.getElementsByName("mode")[0].value=="DVBS"){
			//DVBS
			while(document.getElementsByName("fec")[0].options.length>0){
				var item = document.getElementsByName("fec")[0];
				item.options.remove(0);
			}
			for(i=0;i<DVBS.length;i++){
				var x=document.createElement("option");
				var opt = document.getElementsByName("fec")[0];
				x.text = DVBS[i];
				x.value = DVBS[i].replace("/", "");;
				opt.options.add(x, i); 
				if(x.value==lastfec){sel=i;}
				
			}
				
			
			
		}else{
			//DVBS2
			
			if(document.getElementsByName("mod")[0].value=="QPSK"){
				while(document.getElementsByName("fec")[0].options.length>0){
					var item = document.getElementsByName("fec")[0];
					item.options.remove(0);
				}
				for(i=0;i<DVBS2_QPSK.length;i++){
					var x=document.createElement("option");
					var opt = document.getElementsByName("fec")[0];
					x.text = DVBS2_QPSK[i];
					x.value = DVBS2_QPSK[i].replace("/", "");;
					opt.options.add(x, i); 
					if(x.value==lastfec){sel=i;}
				}
				
			}
			
			if(document.getElementsByName("mod")[0].value=="8PSK"){
				while(document.getElementsByName("fec")[0].options.length>0){
					var item = document.getElementsByName("fec")[0];
					item.options.remove(0);
				}
				for(i=0;i<DVBS2_8PSK.length;i++){
					var x=document.createElement("option");
					var opt = document.getElementsByName("fec")[0];
					x.text = DVBS2_8PSK[i];
					x.value = DVBS2_8PSK[i].replace("/", "");;
					opt.options.add(x, i); 
					if(x.value==lastfec){sel=i;}
				}
				
			}
			
			if(document.getElementsByName("mod")[0].value=="16APSK"){
				while(document.getElementsByName("fec")[0].options.length>0){
					var item = document.getElementsByName("fec")[0];
					item.options.remove(0);
				}
				for(i=0;i<DVBS2_16APSK.length;i++){
					var x=document.createElement("option");
					var opt = document.getElementsByName("fec")[0];
					x.text = DVBS2_16APSK[i];
					x.value = DVBS2_16APSK[i].replace("/", "");;
					opt.options.add(x, i); 
					if(x.value==lastfec){sel=i;}
				}
				
			}
			
				if(document.getElementsByName("mod")[0].value=="32APSK"){
				while(document.getElementsByName("fec")[0].options.length>0){
					var item = document.getElementsByName("fec")[0];
					item.options.remove(0);
				}
				
				for(i=0;i<DVBS2_32APSK.length;i++){
					var x=document.createElement("option");
					var opt = document.getElementsByName("fec")[0];
					x.text = DVBS2_32APSK[i];
					x.value = DVBS2_32APSK[i].replace("/", "");;
					opt.options.add(x, i); 
					if(x.value==lastfec){sel=i;}
				}
				
			}
			
			document.getElementsByName("fec")[0].options[sel].selected = true;
			
		}
		calc_ts();
}


function load() {
	//events
	//document.getElementById('gain+').addEventListener('click', increase_gain);
	//document.getElementById('gain-').addEventListener('click', decrease_gain);



	var s='<?php include("load.php"); ?>';
        var freq="";
        var channel="";
	if(s!=""){
		var array=s.split(",");
		for (index = 0; index < array.length; ++index) {
			if(array[index]!=""){
				var vals= array[index].split(" ");
    			document.getElementsByName(vals[0])[0].value=vals[1];
    			if(vals[0]=="mod"){
					upd_mod();		//refresh dropdowns
					document.getElementsByName(vals[0])[0].value=vals[1];
				}
				if(vals[0]=="freq"){
					freq=vals[1];
				}
			}
		}
		//dropdown update
		var options= document.getElementsByName('channel')[0].options;
                var found=false;

                for (var i= 0; i<options.length; i++) {
			 if (document.getElementsByName('channel')[0].value.split("-")[0] - document.getElementsByName('trvol')[0].value==freq) {
			 	found=true;
				break;
			}
		}
		if(!found){
			options[options.length-1].selected= true;
		}

	}
	if(document.getElementsByName("mode")[0].value=="DVBS"){
		document.getElementById('pilots_option').style.display='none';
		document.getElementById('frame_option').style.display='none';
		document.getElementById('rolloff_option').style.display='none';
	}else{
		document.getElementById('pilots_option').style.display='';
		document.getElementById('frame_option').style.display='';
		document.getElementById('rolloff_option').style.display='';
	}
	calc_ts();
	var slider = document.getElementsByName("power");
        var powertxt = document.getElementById("powertext");
        powertxt.innerHTML = slider[0].value+ 'dB';
	var slider = document.getElementsByName("pcrpts");
        var powertxt = document.getElementById("pcrptstext");
        powertxt.innerHTML = slider[0].value + 'ms';
	update_slider_pat();
	var h265box = document.getElementById("h265box");
/*
	var remuxoptions= document.getElementsByName('remux')[0].options;
	if(document.getElementsByName("remux")[0].value=="1")
	{
		remuxoptions[0].selected=true;
		remuxoptions[1].selected=false;
	}
		else
		{
			remuxoptions[0].selected=false;
		remuxoptions[1].selected=true;
		}
*/		
}


</script>
</body>
</html>
