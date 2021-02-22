<?php
// php pluto library

$file_config ='/mnt/jffs2/etc/config.txt';
$file_general = '/mnt/jffs2/etc/settings-datv.txt';
$dir = '/mnt/jffs2/etc/';
$dirtemp = '/tmp/';
if (true==false) // replace false by true for developping on debug server
{
  //echo "<i>Attention, in developping mode </i><br>";
  $file_config ='config.txt';
  $file_general = 'settings-datv.txt';
  $dir= "";
  $dirtemp = '';
}

$config_ini = readinifile($file_config);
$headfile = $config_ini[0];
$network_config = $config_ini[1];
$general_ini = readinifile($file_general);
$datv_config = $general_ini[1];    
//var_dump($datv_config);





function write_php_ini($array, $file, $headlines)
    {

      $res = array();
      $res[]= $headlines;
      foreach($array as $key => $val)
      {
        if(is_array($val))
        {
          $res[] = "[$key]";
          foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : ''.$sval.'');
        }
        else if (! in_array($key, ["headlines","file_dest"]) ) { //POST variables not to be saved
        	$res[] = "$key = ".(is_numeric($val) ? $val : ''.$val.'');
        }
        $res[]="";
       
      }
      safefilerewrite($file, implode("\n", $res));
       
    }

function safefilerewrite($fileName, $dataToSave)
    {    if ($fp = fopen($fileName, 'w'))
    {
      $startTime = microtime(TRUE);
      do
      {            $canWrite = flock($fp, LOCK_EX);
           // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
       if(!$canWrite) usleep(round(rand(0, 100)*1000));
     } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

        //file was locked so now we can store information
     if ($canWrite)
      {            fwrite($fp, $dataToSave);
        flock($fp, LOCK_UN);
      }
      fclose($fp);
    }

	}

function readinifile($file) {

    if (file_exists($file) && is_readable($file))
    {

      $handle = @fopen($file, "r");
      $headfile="";
      $configlines="";
      if ($handle) {
          while (($buffer = fgets($handle, 4096)) !== false) {
              
              if (substr($buffer,0,2)=="# ") {
                $headfile=$headfile.$buffer;
              }
              else {
                $configlines=$configlines.$buffer;
              }
          }
          if (!feof($handle)) {
              echo "Error: fgets() has failed\n";
          }
          fclose($handle);
      }
    
      return $returnarray = array(urlencode($headfile), parse_ini_string($configlines,true,INI_SCANNER_RAW));

    }
    else 
    {
      echo "The <pre>$file</pre> is not available, the form values are not initialized ! Please take a look at the <a href='setup.php'>setup page</a> <br> ";
      return false;
     
    }
	}  

?>