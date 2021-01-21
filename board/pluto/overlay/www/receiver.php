<?php
//php-cgi /www/receiver.php 'f=491.5&rate=1.5&rec=minitiouner'
// Minitiouner adresse IP et le port adhoc
if (!extension_loaded('sockets')) {
	die('The sockets extension is not loaded.');
}
//default values
/*
$minitiouner_port = "6789";
$minitiouner_host = "232.0.0.11";
$minitiouner_host = "192.168.1.45";
$Offset =   "09750150";
$Doppler = "0";
$WideScan="no";
$LowSR="no";
$DVBmode = "Auto";
$FPlug = "B";
$Voltage = "18";
$twentytwokhz = "Off";
*/
$lines = file('/mnt/jffs2/etc/settings-datv.txt', FILE_IGNORE_NEW_LINES);
foreach ($lines as $key => $value) {
	$l=explode(' = ', $lines[$key]);
	echo $l[0];
	echo " / " . $l[1] . "<br>";
	$j[$l[0]] = $l[1];
}

if (isset($j)) {
$minitiouner_port = $j['minitiouner-port'];
$minitiouner_host = $j['minitiouner-ip'];
$Offset =   $j['minitiouner-offset'];
$DVBmode = $j['minitiouner-mode'];
$FPlug = $j['minitiouner-socket'];
$Voltage = $j['minitiouner-voltage'];
$twentytwokhz = $j['minitiouner-22khz'];
}
if ((isset($_GET['f'])) && (isset($_GET['rate']) && (isset($_GET['rec'])))) {
	if ($_GET['rec']=='minitiouner') 
	{
		$Freq = strval(floatval('10'.$_GET['f'])*1000);
		$rate= str_pad(strval($_GET['rate'])*1000, 5, "0", STR_PAD_LEFT);
		if (floatval($_GET['rate'])<=0.07) { $LowSR="yes";$WideScan="no";}
		$msg = "[GlobalMsg],Freq=".$Freq.",Offset=".$Offset.",Doppler=".$Doppler.",Srate=".$rate.",WideScan=".$WideScan.",LowSR=".$LowSR.",DVBmode=".$DVBmode.",FPlug=".$FPlug.",Voltage=".$Voltage.",22kHz=".$twentytwokhz."\r\n";
		
		$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		$len = strlen($msg);
		$ret=socket_sendto($sock, $msg, $len, 0, $minitiouner_host, $minitiouner_port);
		if ($ret == false ) {
			echo "ERREUR DE COMMUNICATION";
		} else
		{echo ('Commande envoyée au minitiouner : '.$msg);}
		socket_close($sock);
	}
}
?>
