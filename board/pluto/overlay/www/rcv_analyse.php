<?php

  if ( isset( $_GET[ 'cmd' ] ) ) {
  	if ( $_GET[ 'cmd' ] == 'start') {
    	exec( 'sh /root/rcv_analyse.sh start' );
    	echo "Start";
    } else {
    	exec( 'sh /root/rcv_analyse.sh stop' );
    	echo "Stop";
    }

  }

?>