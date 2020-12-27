<?php
// php pluto library
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
      safefilerewrite($file, implode("\r\n", $res));
       
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
      echo "The <pre>$file</pre> is not available, the form values are not initialized !<br> ";
     
    }
	}  

?>