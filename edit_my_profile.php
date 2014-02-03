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

require_once "$home_dir/function/function.php";
require_once "$home_dir/function/page_generation.php";

if (fetchSession($home_dir)==false)
	header("Location: ./index.php");

$user = getUserFromSession($home_dir);

$pg = new pageTemplate("editor.htm",".");

$menu = array();
$menu['Index'] = "index.php";
$menu['FAQ'] = "frequently_asked_questions.php";

if ( fetchSession($home_dir) != false ) {
	$menu['My Profile'] = "profile.php?id=".$user->getId();
	$menu['Edit Profile'] = "edit_my_profile.php";
	$menu['Log Out'] = "logout.php";
}

foreach ( $menu as $name => $link )
	$pg->appendTag("MENU",
		"<a href='./$link' class='menuItem menuLink' >$name</a>");

$pg->setTag("LOCATION",".");
$pg->setTag("TITLE", "Editing ".$user->getUsername()."'s Profile");
$pg->setTag("HEAD", "<img src='./images/forum_logo.png' class='logo' />");
$pg->setTag("LOGINBOX", getLoginStatus($home_dir));
$pg->setTag("FOOT", "");

$userCol = ($user->getCurrentColor() != null) ? "#".$user->getCurrentColor() :
	"#".$user->getPrimaryGroup()->getColor();

$auxGr = "";

foreach ($user->getAuxGroups() as $group)
	/**
	 * @var group $group The singular group entity that we have right here
	 */
	$auxGr .= sprintf("<tr><td style='color:#%s;'>%s</td></tr>",$group->getColor(),$group->getName());

$pg->setTag("USERID", $user->getId());
$pg->setTag("AVATAR", $user->getAvatar());
$pg->setTag("USERSIG", $user->getSignature());
$pg->setTag("USERBIO", $user->getBiography());
$pg->setTag("USERNAME", $user->getUsername());
$pg->setTag("USERRANK", $user->getRank());
$pg->setTag("PRGROUP", $user->getPrimaryGroup()->getName());
$pg->setTag("REGDATE", date("Y-m-d",$user->getTimeRegistered()));
$pg->setTag("AUXGROUPS", "<table>$auxGr</table>");
$pg->setTag("USERCOLOR", $userCol);
$pg->setTag("RANKCOLOR", "#".$user->getRankColor());
$pg->setTag("PRGROUPCOLOR", "#".$user->getPrimaryGroup()->getColor());

$pg->showPage();