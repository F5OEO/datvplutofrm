<?php

session_start();


if(isset($_POST["callsign"])){
    //store settings in cookie and file settings.txt
    setcookie("callsign",$_POST["callsign"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("freq",$_POST["freq"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("channel",$_POST["channel"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("mode",$_POST["mode"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("mod",$_POST["mod"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("sr",$_POST["sr"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("srselect",$_POST["srselect"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("fec",$_POST["fec"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("pilots",$_POST["pilots"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("frame",$_POST["frame"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("power",$_POST["power"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("rolloff",$_POST["rolloff"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("pcrpts",$_POST["pcrpts"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("patperiod",$_POST["patperiod"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("h265box",$_POST["h265box"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("codec",$_POST["codec"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("sound",$_POST["sound"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("audioinput",$_POST["audioinput"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("remux",$_POST["remux"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("trvlo",$_POST["trvlo"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("trvloselect",$_POST["trvloselect"],time() + (10 * 365 * 24 * 60 * 60),"/");
    setcookie("provname",$_POST["provname"],time() + (10 * 365 * 24 * 60 * 60),"/");
    $_SESSION["settings"]="";

	$settings="callsign ".$_POST["callsign"]."\n"."freq ".$_POST["freq"]."\n"."channel ".$_POST["channel"]."\n"."mode ".$_POST["mode"]."\n"."mod ".$_POST["mod"]."\n"."sr ".$_POST["sr"]."\n"."srselect ".$_POST["srselect"]."\n"."fec ".$_POST["fec"]."\n"."pilots ".$_POST["pilots"]."\n"."frame ".$_POST["frame"]."\n"."power ".$_POST["power"]."\n"."rolloff ".$_POST["rolloff"]."\n"."pcrpts ".$_POST["pcrpts"]."\n"."patperiod ".$_POST["patperiod"]."\n"."h265box ".$_POST["h265box"]."\n"."codec ".$_POST["codec"]."\n"."sound ".$_POST["sound"]."\n"."audioinput ".$_POST["audioinput"]."\n"."remux ".$_POST["remux"]."\n"."trvlo ".$_POST["trvlo"]."\n"."trvloselect ".$_POST["trvloselect"]."\n"."provname ".$_POST["provname"]."\n";
	file_put_contents("settings.txt",$settings) or die("Unable to write file!");;
    //echo $settings;    
}

//print_r( $_SESSION);


header("Location: pluto.php");

?>