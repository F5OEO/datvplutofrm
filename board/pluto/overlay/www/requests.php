<?php
header("Access-Control-Allow-Origin: *"); //for developping & testing purposes
$dev= exec ('sh /root/device_sel.sh compact'); //determine device according hardware revision

if(isset($_GET['onair'])){
    $cmd = 'cat /sys/bus/iio/devices/iio:device'.$dev.'/out_altvoltage1_TX_LO_powerdown';
    exec($cmd,$return);
    
    foreach( $return as & $value )
    {
    echo $value;
    }
}

if(isset($_GET['PTT'])){
    $Tx= $_GET['PTT'];
    if($Tx == "on")
    {   
        $cmd = 'echo 0x27 0x10 > /sys/kernel/debug/iio/iio:device'.$dev.'/direct_reg_access';
        exec($cmd);
        $cmd = 'echo  0 >  /sys/bus/iio/devices/iio:device'.$dev.'/out_altvoltage1_TX_LO_powerdown';
        exec($cmd);
    }
    else
    if($Tx == "off")
    {
        $cmd = 'echo 0x27 0x00 > /sys/kernel/debug/iio/iio:device'.$dev.'/direct_reg_access';
        exec($cmd);
        $cmd = 'echo  1 >  /sys/bus/iio/devices/iio:device'.$dev.'/out_altvoltage1_TX_LO_powerdown';
        exec($cmd);
    }       
    
}

//get status
//doo whatever we like here TBD
if(isset($_GET['status'])){
     $source = $_GET['source'];
     
     $cmd = "/root/reportbitrate.sh ".$source;	
     exec($cmd,$stuff);	
     echo "{";

     echo '"bitrate": ';
     echo "[";
     $i=0;
     foreach( $stuff as & $value ) {
     echo $value;
     $i++;
     if( $i < count($stuff) ){
     echo ",";
     }
     }
     echo "]";
     
 $cmd = "/root/reportpid.sh ".$source;
     exec($cmd,$pid);
     echo ',';
     echo '"pid": ';
     echo "[";
     $i=0;
     foreach( $pid as & $value ) {	
     echo '"' . $value;
     switch($value) {

     case 0 : echo "(PAT)";break;
     case 17 : echo "(SDT)";break;
     case 256: echo  "(Video)";break;
     case 257 : echo  "(Audio)";break;
     case 4096 : echo  "(PMT)";break;
     case 8191 : echo  "(Null packets)";break;
    }
   
     echo '"';		
     $i++;
     if( $i < count($pid) ){
     echo ",";
     }
     }
     echo "]";
//end of PID Array

$i=0;
echo ',';
echo '"pcr": ';
echo "[";
     

     $cmd = "tail -n 200 /root/pcr".$source.".txt";
     exec($cmd,$pcrline);
     foreach( $pcrline as & $value ) {
         $arraypcr=explode(';',$value);	
        echo '"' . $arraypcr[7]/90 .'"';
        $i++;
     if( $i < count($pcrline) ){
     echo ",";
     }
     }  
     echo "]";

$i=0;

echo ',';
echo '"pcrlabel": ';
echo "[";
     

     foreach( $pcrline as & $value ) {
         $arraypcr=explode(';',$value);	
        echo '"' . $arraypcr[6]/90000 .'"';
        $i++;
     if( $i < count($pcrline) ){
     echo ",";
     }
     }  
     echo "]";

 //Name and provider
 $cmd = "/root/reportname.sh ".$source;
     
 echo ',';
echo '"name": "';
echo exec($cmd);
echo '"';

$cmd = "/root/reportprovider.sh ".$source;
echo ',';
echo '"provider": "';
echo exec($cmd);
echo '"';

//end of json
echo "}";
}

//Shell cmd
if(isset($_GET['cmd'])){
    $output = shell_exec($_GET['cmd']);
    echo $output;
}	
	




?>
