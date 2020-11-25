<?php
// F5UII : Saving parameters from reveiver Setup tab, including Spectrum enable state
//var_dump($_POST);

if(isset($_POST)){
	$string_tofile= "";
	foreach($_POST as $key => $value){
    $string_tofile.= $key . " " . $value . "\n";
}

file_put_contents('settings.txt', $string_tofile);
// 

}
?>