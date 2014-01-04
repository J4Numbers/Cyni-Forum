<?php

require_once './function/function.php';

session_start();

if ( file_exists( "install/" ) )
    header( "Location: install/" );

#if ( !$_SESSION['is_registered'] ) {
#	header("Location: ./login.php");
#}





?>