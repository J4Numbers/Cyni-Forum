<?php

function sqlConnect() {
	if ( !mysql_connect('192.168.137.234','forum','w4rf4r3r') ) {
		echo mysql_error();
		die();
	}
	mysql_select_db( 'forum' );
}

?>