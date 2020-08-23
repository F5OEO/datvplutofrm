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
$auth = base64_encode($username.":".$password);

set_enc_video($auth);
set_enc_audio($auth);
set_enc_network($auth);




function set_enc_network($auth){
	$server = $_POST['enc_ip'].'/action/set?subject=multicast';
	
	if($_POST['enabled']=="true"){
		$enabled=1;
		//enable
	}else{
		$enabled=0;
		//disable udp multicast
	}
	

	$xml = '<?xml version: "1.0" encoding="utf-8"?>
	<request>
	<multicast>
	
	<mcast>                        
	<active>'.$enabled.'</active>                        
	<port>'.$_POST['pluto_port'].'</port>                        
	<addr>'.$_POST['pluto_ip'].'</addr>                  
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
	$server = $_POST['enc_ip'].'/action/set?subject=audioenc&stream=0';
	
	if($_POST['audio_input']=="line"){
		$input=1;
		//line in
	}else{
		$input=0;
		//hdmi
	}
	
	if($_POST['audio_channels']=="2"){
		$channel=1;
		//stereo
	}else{
		$channel=0;
		//mono
	}


	//fixed to AAC
	$xml = '<?xml version: "1.0" encoding="utf-8"?>
	<request>
	<audioenc>
        <channel>'.$channel.'</channel>         
	<bitrate>'.$_POST['audio_bitrate'].'</bitrate>
        <codec>2</codec>
        <samples>48000</samples>                      
	<input>'.$input.'</input>           
	</audioenc>
	</request>';

        //echo $xml;
	
	
	post($auth,$xml,$server);

}






function set_enc_video($auth){
	$server = $_POST['enc_ip'].'/action/set?subject=videoenc&stream=0';

	if($_POST['codec']=="h265"){
		$codec=1;
	}else{
		$codec=0;
	}
     
        if($_POST['sound']=="On"){
		$sound=1;
	}else{
		$sound=0;
	}

	$xml = '<?xml version: "1.0" encoding="utf-8"?>
	<request><videoenc>
	<codec>'.$codec.'</codec>
	<resolution>'.$_POST['res'].'</resolution>
	<framerate>'.$_POST['fps'].'</framerate>
	<audioen>'.$sound.'</audioen>
	<rc>1</rc>
	<keygop>'.$_POST['keyint'].'</keygop>
	<bitrate>'.$_POST['v_bitrate'].'</bitrate>
	<quality>5</quality>
	<profile>0</profile>
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
	$data = file_get_contents("http://$server", false, $ctx);



}



?>
