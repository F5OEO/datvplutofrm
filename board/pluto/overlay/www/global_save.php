<?php
// F5UII : Saving global Adalm Pluto parameters to /opt/config.txt file
var_dump($_POST);

if(isset($_POST)){
	$string_tofile= "";
	foreach($_POST as $key => $value){
    $string_tofile.= $key . " " . $value . "\n";
}

file_put_contents('config3.txt', $string_tofile);
// 
//exec ('route add default gw '.$_POST['gateway-eth0']);
//copy('/www/settings-receiver.txt','/mnt/jffs2/settings-receiver.txt');
}
?>