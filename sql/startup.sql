CREATE DATABASE forum;

USE forum;

CREATE TABLE forum_groups(
`group_id` mediumint(8) not null auto_increment,
`group_name` varchar(64) not null,
`group_color` varchar(6) not null,
`group_info` text not null,
primary key(`group_id`) );

CREATE TABLE forum_users(
`user_id` integer(11) not null auto_increment,
`username` varchar(64) not null,
`username_cased` varchar(64) not null,
`group_id` mediumint(8) not null,
`user_email` varchar(128) not null,
`password` varchar(40) not null,
`time_reg` integer(11) not null,
`time_preg` integer(11) not null,
`user_timezone` decimal(5,2) not null,
`user_rank` mediumint(8),
`user_color` varchar(6),
`user_avatar` varchar(255),
primary key(`user_id`),
foreign key(`group_id`) references forum_groups(`group_id`) );

CREATE TABLE forum_user_groups(
`user_id` integer(11) not null,
`group_id` mediumint(8) not null,
foreign key(`user_id`) references forum_users(`user_id`),
foreign key(`group_id`) references forum_groups(`group_id`) );

CREATE TABLE forum_threads(
`thread_id` integer(11) not null auto_increment,
`user_id` integer(11) not null,
`title` varchar(255) not null,
`last_updated` integer(11) not null,
`updated_by` integer(11) not null,
`created` integer(11) not null,
`level` integer(3) not null,
primary key(`thread_id`),
foreign key(`user_id`) references forum_users(`user_id`),
foreign key(`updated_by`) references forum_users(`user_id`) );

CREATE TABLE forum_posts(
`post_uid` bigint not null auto_increment,
`thread_id` integer(11) not null,
`user_id` integer(11) not null,
`time_posted` integer(11) not null,
`post_content` text not null,
primary key(`post_uid`),
foreign key(`thread_id`) references forum_threads(`thread_id`),
foreign key(`user_id`) references forum_users(`user_id`) );
