<?php

class database {

	private $update;
	private $insert;
	private $select;
	
	private $mysqli;
	
	function __construct() {
		$mysqli = mysqli_connect( "127.0.0.1", "forum", "w4rf4r3r", "forum", 3306 );
		if ( mysqli_connect_errno( $mysqli ) ) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			return false;
		}
		if ( !( $update = $mysqli->prepare( "UPDATE ? SET `?` = '?' WHERE `?` = '?'" ) ) ) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			return false;
		}
		if ( !( $insert = $mysqli->prepare( "INSERT INTO ? VALUES (?)" ) ) ) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			return false;
		}
		if ( !( $select = $mysqli->prepare( "SELECT ? FROM ? WHERE `?` = '?'" ) ) ) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			return false;
		}
		return true;
	}
	
	function NewUpdate( $table, $fields, $values ) {
		//s = string
		//i = int
		//d = double
		//b = blob
		$update->bind_param('sss', $table, $fields, $values);
	}
	
	
	
	function __destruct() {
		mysqli_close( $mysqli );
	}

}

?>