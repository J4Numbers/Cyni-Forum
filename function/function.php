<?php

/**
 * Copyright 2014 Matthew Ball (CyniCode/M477h3w1012)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once "$home_dir/function/database.php";

function isInstalled( $home_dir ) {

	$database = new database($home_dir);

	return ( file_exists("$home_dir/config/props.php") && $database->getInstallStatus() );

}

function usersExist( $home_dir ) {

	$database = new database($home_dir);

	return ( file_exists("$home_dir/config/props.php") && $database->getUsersInstalled() );
}

function fetchSession($home_dir) {

	require_once "$home_dir/config/props.php";

	session_start();
	return (isset($_SESSION[DOMAIN.'cyniForums']['user'])) ? $_SESSION[DOMAIN.'cyniForums']['user'] : false;

}

function getLoginStatus($home_dir) {

	if ( !fetchSession($home_dir) ) {
		$ret = sprintf("<table class='login_table' >
					<tr>
						<td class='log_field' >Username</td>
						<td><input type='text' class='log_input' id='user_log_user' /></td>
						<td rowspan='2'></td>
					</tr>
					<tr>
						<td class='log_field' >Password</td>
						<td><input type='text' class='log_input' id='user_log_pass' /></td>
					</tr>
					<tr><td colspan='2' >New? Why not <a href='%s/registration.php'>Register</a>?
						<button onclick='login()'>Submit</button></td></tr></table>",INSTLOC);
	} else {
		$user = fetchSession($home_dir);
		$ret = sprintf("<table><tr>Welcome %s</tr></table>", $user['name']);
	}

	return $ret;

}

function getUserRegTime( $userName, $home_dir ) {

	$database = new database($home_dir);
	return "";

}