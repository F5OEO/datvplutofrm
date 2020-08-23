<?php


        //no form data passed, load file and store settings in session var

        $file = file_get_contents("settings.txt");
        $lines = explode("\n", $file);
        $settings="";
        foreach($lines as $line){
                $settings=$settings.$line.",";
        }
        //$_SESSION["settings"]=$settings;

	echo $settings;




?>
