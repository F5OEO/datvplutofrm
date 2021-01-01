<?php
// F5UII : Saving global Adalm Pluto parameters to /opt/config.txt file in a ini format file
//

require ('lib/functions.php');

//var_dump($_POST);

if(isset($_POST)){

	write_php_ini($_POST,$_POST['file_dest'], $_POST['headlines']); //array with {data array, file, headines}
}

?>