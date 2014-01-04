USE forum_testing;

CREATE TABLE groups(
  `group_id` mediumint(11) not null auto_increment,
  `group_name` varchar(64) not null,
  `group_color` varchar(6) not null,
  `group_info` text not null,
  primary key(`group_id`)
);

CREATE TABLE ranks(
  `rank_id` mediumint(11) not null auto_increment,
  `rank_name` varchar(64) not null,
  `rank_color` varchar(6) not null,
  primary key(`rank_id`)
);

CREATE TABLE config(
  `config_id` mediumint(11) not null auto_increment,
  `config_name` varchar(64) not null,
  `config_value` varchar(64) not null,
  primary key(`config_id`)
);

CREATE TABLE bbcode(
  `code_id` mediumint(11) not null auto_increment,
  `code_rule` varchar(128) not null,
  primary key(`code_id`)
);

CREATE TABLE users(
  `user_id` integer(11) not null auto_increment,
  `username` varchar(64) not null,
  `username_cased` varchar(64) not null,
  `primary_group_id` mediumint(11) not null,
  `rank_id` mediumint(11) not null,
  `user_email` varchar(128) not null,
  `password` varchar(40) not null,
  `time_reg` integer(11) not null,
  `time_pass_altered` integer(11) not null,
  `user_timezone` decimal(5,2) not null,
  `user_rank` mediumint(8),
  `user_color` varchar(6),
  `user_avatar` varchar(255),
  primary key(`user_id`),
  foreign key(`primary_group_id`) references forum_groups(`group_id`),
  foreign key(`rank_id`) references forum_ranks(`rank_id`)
);

CREATE TABLE private_messages(
  `priv_msg_id` integer(11) not null auto_increment,
  `sender_id` integer(11) not null,
  `receiver_id` integer(11) not null,
  `msg_contents` text not null,
  `msg_time` integer(11) not null,
  primary key(`priv_msg_id`),
  foreign key(`sender_id`) references forum_users(`user_id`),
  foreign key(`receiver_id`) references forum_users(`user_id`)
);

CREATE TABLE private_messages_user_mailing_list(
  `priv_msg_id` integer(11) not null,
  `receiver_id` integer(11) not null,
  foreign key (`priv_msg_id`) references forum_private_messages(`priv_msg_id`),
  foreign key (`receiver_id`) references forum_users(`user_id`),
  constraint `unique_message_recip` unique (`priv_msg_id`,`receiver_id`)
);

CREATE TABLE private_messages_group_mailing_list(
  `priv_msg_id` integer(11) not null,
  `group_id` integer(11) not null,
  foreign key (`priv_msg_id`) references forum_private_messages(`priv_msg_id`),
  foreign key (`group_id`) references forum_groups(`group_id`),
  constraint `unique_message_group` unique (`priv_msg_id`,`group_id`)
);

CREATE TABLE user_groups(
  `user_id` integer(11) not null,
  `group_id` mediumint(11) not null,
  `joined_on` integer(11) not null,
  foreign key(`user_id`) references forum_users(`user_id`),
  foreign key(`group_id`) references forum_groups(`group_id`)
);

CREATE TABLE forums(
  `forum_id` integer(11) not null  auto_increment,
  `forum_name` varchar(64) not null,
  `forum_hidden` tinyint(1) default('0'),
  primary key(`forum_id`)
);

CREATE TABLE categories(
  `category_id` integer(11) not null auto_increment,
  `forum_id` integer(11) not null,
  `category_title` varchar(64) not null,
  `category_info` varchar(255),
  `category_hidden` tinyint(1) default('0'),
  primary key(`category_id`),
  foreign key(`forum_id`) references forum_forums(`forum_id`)
);

CREATE TABLE threads(
  `thread_id` integer(11) not null auto_increment,
  `user_id` integer(11) not null,
  `created_on` integer(11) not null,
  `title` varchar(255) not null,
  `last_updated` integer(11) not null,
  `updated_by` integer(11) not null,
  `level` integer(3) not null,
  primary key(`thread_id`),
  foreign key(`user_id`) references forum_users(`user_id`),
  foreign key(`updated_by`) references forum_users(`user_id`)
);

CREATE TABLE posts(
  `post_uid` bigint not null auto_increment,
  `thread_id` integer(11) not null,
  `user_id` integer(11) not null,
  `time_posted` integer(11) not null,
  `post_content` text not null,
  primary key(`post_uid`),
  foreign key(`thread_id`) references forum_threads(`thread_id`),
  foreign key(`user_id`) references forum_users(`user_id`)
);