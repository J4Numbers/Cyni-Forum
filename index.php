<?php

require_once './function/function.php';

session_start();

sqlConnect();
#if ( !$_SESSION['is_registered'] ) {
#	header("Location: ./login.php");
#}





?>