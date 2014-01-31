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

if (!isset($_GET['id']) && !isset($_GET['username']))
	header("Location: ./index.php");

$home_dir = getcwd();

require_once "$home_dir/function/function.php";
require_once "$home_dir/function/page_generation.php";

if ( !((isset($_GET['id']) && checkUserExists($_GET['id'],false,$home_dir)) ||
		(isset($_GET['username']) && checkUserExists(false,$_GET['username'],$home_dir))) )
	//TODO: Make a userfail page
	header("Location: ./index.php");

if (isset($_GET['id']))
	$viewing = getUserFromId($_GET['id'],$home_dir);
else
	$viewing = getUserFromName($_GET['username'],$home_dir);

var_dump($viewing);