<?php
// F5UII : Saving parameters from reveiver Setup tab, including Spectrum enable state
//var_dump($_POST);

if(isset($_POST)){
	$string_tofile= "";
	foreach($_POST as $key => $value){
    $string_tofile.= $key . " " . $value . "\n";
}

file_put_contents('settings-receiver.txt', $string_tofile);
// 
//exec ('route add default gw '.$_POST['gateway-eth0']);
copy('/www/settings-receiver.txt','/mnt/jffs2/etc/settings-receiver.txt');
}
?>