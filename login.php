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

$home_dir = getcwd();

require_once "$home_dir/function/page_generation.php";
require_once "$home_dir/function/function.php";
require_once "$home_dir/function/database.php";

if ( isset($_POST['log_user']) && isset($_POST['log_pass']) )
	attemptLogin( $_POST['log_user'], $_POST['log_pass'], $home_dir );

$pg = new pageTemplate( "logging.htm", $home_dir );

$body = sprintf("<div class='newsarticle_text'><div class='login_box' >%s</div></div>", getLoginStatus($home_dir));

$pg->setTag( "LOCATION", "." );
$pg->setTag( "TITLE", "Log In" );
$pg->setTag( "BODY", "<div class='newsarticle'>$body</div>" );
$pg->setTag( "HEAD", "<img src='./images/forum_logo.png' class='logo' />" );
$pg->setTag( "FOOT", "" );

$pg->showPage();