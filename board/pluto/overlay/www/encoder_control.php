<?php
//php code to control the 'brovotech' h264/265 Encoder' box
//enavple command line:
//php-cgi encoder_control.php 'enc_ip=192.168.1.120&codec=h265&res=1280x720&fps=30&keyint=30&v_bitrate=280&sound=On&audio_input=hdmi&audio_channels=1&audio_bitrate=32000&enabled=true&pluto_ip=192.168.1.16&pluto_port=8282'
//
//THIS ASSUMES THE ENCODER BOX IS SET TO SINGLE CHANNEL MODE IN SYSTEM SETTINGS.

//this enables command line arguments to be used the same as http post variables
if (!isset($_SERVER["HTTP_HOST"])) {
  parse_str($argv[1], $_POST);
}


$username = 'admin';
$password = '12345';

$lines = file('/mnt/jffs2/etc/settings-datv.txt', FILE_IGNORE_NEW_LINES);
foreach ($lines as $key => $value) {
	$l=explode(' = ', $lines[$key]);
	echo $l[0];
	echo " / " . $l[1] . "<br>";
	$j[$l[0]] = $l[1];
}
if (isset($j['h265box_login']) && isset($j['h265box_login'])) {
$username =  $j['h265box_login'];
$password =  $j['h265box_password'];
}

$auth = base64_encode($username.":".$password);


set_enc_video($auth);
set_enc_audio($auth);
set_enc_network($auth);




function set_enc_network($auth){
		
	$server = $_POST['h265box'].'/action/set?subject=multicast';
	
	if(($_POST['enabled']=="true") || ($_POST['enabled']==NULL)){
		$enabled=1;
		//enable
	}else{
		$enabled=0;
		//disable udp multicast
	}

	if($_POST['pluto_port']== NULL){
		$pport='8282';
		//enable
	}else{
		$pport=$_POST['pluto_port'];
		//disable udp multicast
	}

	if($_POST['pluto_ip']== NULL){
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		    echo 'This is a server using Windows! must be a dev device, force then uii ip pluto';
		    $pip = '192.168.1.9';
		} else {
		    $pip = shell_exec('ip -f inet -o addr show eth0 | cut -d\  -f 7 | cut -d/ -f 1');
		}
	}else{
		$pip=$_POST['pluto_ip'];
		//disable udp multicast
	}
	

	$xml = '<?xml version: "1.0" encoding="utf-8"?>
	<request>
	<multicast>
	
	<mcast>                        
	<active>'.$enabled.'</active>                        
	<port>'.$pport.'</port>                        
	<addr>'.$pip.'</addr>                  
	</mcast>
	
	<mcast>                        
	<active>0</active>                        
	<port>10001</port>                        
	<addr>224.1.2.3</addr>                  
	</mcast>
	
	<mcast>                        
	<active>0</active>                        
	<port>10002</port>                        
	<addr>224.1.2.3</addr>                  
	</mcast>
	
	</multicast>
	</request>';

	//echo $xml;
	
	
	post($auth,$xml,$server);

}





function set_enc_audio($auth){
	$server = $_POST['h265box'].'/action/set?subject=audioenc&stream=0';
	
	
	if($_POST['audioinput']=="line"){
		$input="1";
		//line in
	}else{
		$input="0";
		//hdmi NOT WORKING !!!!!
	}
	
	if($_POST['audio_channels']=="2"){
		$channel="1";
		//stereo
	}else{
		$channel="0";
		//mono
	}


	//fixed to AAC
	/*
	$xml = '<?xml version: "1.0" encoding="utf-8"?>
	<request>
	<audioenc>
	<codec>2</codec>
	<samples>32000</samples>
	<bitrate>'.$_POST['audio_bitrate'].'</bitrate>            
    <channel>'.$channel.'</channel>         
	<input>'.$input.'</input>
	<invol>30</invol>
	<outvol>100</outvol>                 
	</audioenc>
	</request>';
	*/
	
	/*
	$xml = '<?xml version="1.0" encoding="utf-8"?><request><audioenc>                        <codec>2</codec>                        <samples>48000</samples>                        <bitrate>'.$_POST['audio_bitrate'].'</bitrate>                        <channel>0</channel>                        <input>'.$input.'</input>                        <invol>30</invol>                        <outvol>100</outvol>                 </audioenc></request>';  
	echo $xml."\n";
	*/
	
	$xml = '<?xml version="1.0" encoding="utf-8"?><request><audioenc>                        <codec>2</codec>                        <samples>48000</samples>                        <bitrate>'.$_POST['audio_bitrate'].'</bitrate>                        <channel>'.$channel.'</channel>                        <input>'.$input.'</input>                        <invol>30</invol>                        <outvol>100</outvol>                 </audioenc></request>';  
	//echo $xml;
	
	post($auth,$xml,$server);
	
}




/* "<?xml version="1.0" encoding="utf-8"?><request><videoenc><codec>1</codec>
 <resolution>720x540</resolution>
<framerate>25</framerate><rc>1</rc><keygop>50</keygop><bitrate>251</bitrate>
<quality>5</quality>
<profile>0</profile>
<audioen>1</audioen>
 </videoenc></request>"
*/

function set_enc_video($auth){

	$server = $_POST['h265box'].'/action/set?subject=videoenc&stream=0';

	if(strtoupper($_POST['codec'])=="H265"){
		$codec="1";
	}else{
		$codec="0";
	}
     
        if($_POST['sound']=="On"){
		$sound="1";
	}else{
		$sound="0";
	}

	$xml = '<?xml version: "1.0" encoding="utf-8"?>
	<request><videoenc>
	<codec>'.$codec.'</codec>            
	<resolution>'.$_POST['res'].'</resolution>            
	<framerate>'.$_POST['fps'].'</framerate>         
	<rc>1</rc>             
	<keygop>'.$_POST['keyint'].'</keygop>          
	<bitrate>'.$_POST['v_bitrate'].'</bitrate>          
	<quality>5</quality>        
	<profile>0</profile>         
	<audioen>1</audioen>             
	</videoenc></request>';

	//echo $xml;
	
	post($auth,$xml,$server);

}


function post($auth,$xml,$server){

	$headers = array(
		'Authorization: Basic ' . $auth,
		'Content-type: ' . "application/x-www-form-urlencoded; charset=UTF-8"
	);

	$context = array (
			'http' => array (
				'method' => 'POST',
				'header'=> $headers,
				'content' => $xml,
				)
			);


	$ctx = stream_context_create($context);
	file_get_contents("http://$server", false, $ctx);
	//var_dump($data);
	//echo "\n";
	//usleep(100000);		
}



?>
