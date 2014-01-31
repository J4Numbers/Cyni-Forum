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

$pg = new pageTemplate("profile.htm",$home_dir);

$pg->setTag("LOCATION", ".");
$pg->setTag("TITLE", $viewing->getUsername()."'s Profile");
$pg->setTag("HEAD", "<img src='./images/forum_logo.png' class='logo' />");
$pg->setTag("LOGINBOX", getLoginStatus($home_dir));

$userCol = ($viewing->getCurrentColor() != null) ? "#".$viewing->getCurrentColor() :
	"#".$viewing->getPrimaryGroup()->getColor();

$userAvat = ($viewing->getAvatar() != null) ? $viewing->getAvatar() :
	"default.png";

$auxGr = "";

foreach ($viewing->getAuxGroups() as $group)
	/**
	 * @var group $group
	 */
	$auxGr .= sprintf("<tr><td style='color:#%s;'>%s</td></tr>",$group->getColor(),$group->getName());

$pg->setTag("AVATAR", "<img class='user_avatar' src='./images/avatars/$userAvat' />");
$pg->setTag("USERNAME", $viewing->getUsername());
$pg->setTag("USERCOLOR", $userCol);
$pg->setTag("RANKCOLOR", "");
$pg->setTag("USERRANK", "");
$pg->setTag("PRGROUPCOLOR", "#".$viewing->getPrimaryGroup()->getColor());
$pg->setTag("PRGROUP", $viewing->getPrimaryGroup()->getName());
$pg->setTag("REGDATE", date("Y-m-d", $viewing->getTimeRegistered()));
$pg->setTag("USERBIO", $viewing->getBiography());
$pg->setTag("AUXGROUPS", "<table>$auxGr</table>");
$pg->setTag("USERSIG", $viewing->getSignature());

$pg->setTag("FOOT", "");

$pg->showPage();