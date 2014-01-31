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
require_once "$home_dir/classes/user.php";

function isInstalled( $home_dir ) {

	$database = new database($home_dir);

	return ( file_exists("$home_dir/config/props.php") && $database->getInstallStatus() );

}

function usersAdded( $home_dir ) {

	$database = new database($home_dir);

	return $database->getUsersInstalled();

}

function usersExist( $home_dir ) {

	$database = new database($home_dir);

	return ( file_exists("$home_dir/config/props.php") && $database->getUsersInstalled() );
}

function checkUserExists( $userId=false, $username=false, $home_dir ) {

	$database = new database($home_dir);

	if ($userId != false)
		return $database->checkUserIdExists($userId);

	if ($username != false)
		return $database->checkUsernameExists($username);

	return false;

}

/**
 * @param $userId
 * @param $home_dir
 * @return user
 */
function getUserFromId( $userId, $home_dir ) {

	return new user($userId, $home_dir);

}

/**
 * @param $username
 * @param $home_dir
 * @return user
 */
function getUserFromName( $username, $home_dir ) {

	$database = new database($home_dir);

	return getUserFromId( $database->getUserIdFromUserName($username), $home_dir );

}

function extractGroupFromArrayWithId( $groupArray, $specGroup ) {

	foreach ($groupArray as $group)
		if ($group['group_id']==$specGroup)
			return $group;

	return false;

}

function checkSessionStarted() {
	if (session_status() == PHP_SESSION_NONE)
		session_start();
}

function createLoginSession( $username, $home_dir ) {

	require_once "$home_dir/config/props.php";

	checkSessionStarted();

	$database = new database($home_dir);
	$_SESSION[DOMAIN.'cyniForums']['user'] = $database->getCompleteUserInfoFromUsername($username);

}

/**
 * This function also acts as a means to an end in order to get
 * the login status of the user.
 *
 * @param String $home_dir The path to the installation directory
 * @return bool|array : False if not logged in and the details of
 *  the user if they actually are logged in
 */
function fetchSession($home_dir) {

	require_once "$home_dir/config/props.php";

	checkSessionStarted();
	return (isset($_SESSION[DOMAIN.'cyniForums']['user'])) ? $_SESSION[DOMAIN.'cyniForums']['user'] : false;

}

function getLoginStatus($home_dir) {

	if ( !fetchSession($home_dir) ) {
		$ret = sprintf("<form method='post' action='%s/login.php' ><table class='login_table' >
					<tr>
						<td class='log_field' >Username</td>
						<td><input type='text' class='log_input' id='user_log_user' name='log_user' /></td>
						<td rowspan='2'></td>
					</tr>
					<tr>
						<td class='log_field' >Password</td>
						<td><input type='password' class='log_input' id='user_log_pass' name='log_pass' /></td>
					</tr>
					<tr><td colspan='2' >New? Why not <a href='%s/registration.php'>Register</a>?
						<input type='submit' name='Submit' /></td></tr></table></form>",INSTLOC,INSTLOC);
	} else {
		$user = fetchSession($home_dir);
		$ret = sprintf("<table><tr>Welcome %s</tr></table>", $user[1]['username']);
	}

	return $ret;

}

function attemptLogin( $username, $password, $home_dir ) {

	$time = getUserRegTime($username, $home_dir);

	if ( $time == false ) return false;

	require_once "$home_dir/function/hash.php";

	$hash = (array) json_decode(cyniHash($password, $time));

	if ( compareHashedPasswordWithUsername($username, $hash['hash'], $home_dir) ) {

		createLoginSession( $username, $home_dir );
		header( "Location: index.php" );

	} else {

		return false;

	}

}

function compareHashedPasswordWithUsername( $userName, $hashed, $home_dir ) {

	$database = new database($home_dir);

	$oriHash = $database->getPasswordFromUsername($userName);

	return ( $hashed == $oriHash );

}

function getUserRegTime( $userName, $home_dir ) {

	$database = new database($home_dir);

	return $database->getPasswordTimeFromUsername($userName);

}