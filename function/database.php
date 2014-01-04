<?php

/**
 * Class database
 *
 * A class to control all things databasy with the forums.
 * This includes things that might or might not be related
 * to the database.
 */
class database {

    //s = string
    //i = int
    //d = double
    //b = blob

    private $pdo_base;
    private $prefix;
    
    function __construct() {

        require_once( "../config/props.php" );
        
        $dsn = "mysql:dbname=".DATABASE.";host=".DATAHOST.";";

        try {

            $pdo_base = new PDO( $dsn, DATAUSER, DATAPASS );

        } catch (PDOException $ex) {

            error_log( "Could not connect to database: " . $ex->getMessage() );
            die;

        }

        $this->pdo_base = $pdo_base;
        $this->prefix = DATAPFIX;

    }

    /**
     * Return the statement when we've given it the basic string template
     * to play with.
     *
     * @param String $string : The string that we're going to put into
     *  a PDO statement and the thing we're going to return
     * @return PDOStatement : Well... it was the $string
     * @throws PDOException : For when santa thinks we've been naughty
     */
    private function makePreparedStatement( $string ) {

        try {

            return $this->pdo_base->prepare( $string );

        } catch (PDOException $ex) {

            error_log("Failed to make prepared statement: " . $ex->getMessage());
            throw $ex;

        }

    }

    /**
     * A function to bind all the values of a MySQLi statement to their actual
     * values as given... read into that what you will.
     *
     * @param PDOStatement $statement : MySQL PDO statement
     * @param array $arrayOfVars : An array, ordered, of all the values corresponding to the
     *  $statement from before
     * @return PDOStatement : Return the statement that has been produced, thanks
     *  to our wonderful code... or false if it fails
     * @throws PDOException : If the SQL doesn't like to be bound to anyone
     */
    private function assignStatement( PDOStatement $statement, array $arrayOfVars ) {

        try {

            foreach ( $arrayOfVars as $key => &$val )
                $statement->bindParam( $key, $val );

            return $statement;

        } catch (PDOException $except) {

            error_log( "Error encountered during statement binding: " . $except->getMessage() );
            throw $except;

        }

    }

    /**
     * Execute a given status and return whatever happens to it
     *
     * @param PDOStatement $statement : The PDO statement that we're going to execute,
     *  be it query, insertion, deletion, update, or fook knows
     * @return PDOStatement : Return the rows that the statement gets
     *  that have been returned if it manages to find any... Might blow up
     * @throws PDOException : If SQL doth protest
     */
    private function executeStatement( PDOStatement $statement ) {

        try {

            $statement->execute();
            return $statement;

        } catch (PDOException $ex) {

            error_log( "Failed to execute statement: " . $ex->getMessage() );
            throw $ex;

        }

    }

    /**
     * Get, set and execute a statement in this one method instead of having
     * the same lines in a dozen other statements, all doing the same thing.
     *
     * @param PDOStatement $statement : The statement that we're going to make
     *  into a star...
     * @param array $arrayOfVars : The variables that are going to go into this
     *  statement at some point or another.
     * @return PDOStatement : The result set that /should/ have been generated
     *  from the PDO execution
     * @throws PDOException : If PDO is a poor choice...
     */
    private function executePreparedStatement( PDOStatement $statement, $arrayOfVars ) {

        try {

            $stated = $this->assignStatement( $statement, $arrayOfVars );

            $result = $this->executeStatement( $stated );

            return $result;

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    /**
     * Check whether or not a table already exists with the same name that
     * we're giving it... how nice of us.
     *
     * @param String $tableName : The name of the table we're checking off
     * @return bool : Of whether the table exists
     */
    private function checkTableExists( $tableName ) {

        $statement = $this->makePreparedStatement( "SHOW TABLES LIKE :table" );

        $table = $this->prefix . $tableName;

        $statement->bindParam( ":table", $table );

        $result = $this->executeStatement( $statement );

        return $result->rowCount() > 0;

    }

    private function generateGroupsTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."groups(
                                        `group_id` mediumint(11) not null auto_increment,
                                        `group_name` varchar(64) not null,
                                        `group_color` varchar(6) not null,
                                        `group_info` text not null,
                                        primary key(`group_id`)
                                      )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generateRanksTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."ranks(
                                        `rank_id` mediumint(11) not null auto_increment,
                                        `rank_name` varchar(64) not null,
                                        `rank_color` varchar(6) not null,
                                        primary key(`rank_id`)
                                      )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generateConfigTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."config(
                                          `config_id` mediumint(11) not null auto_increment,
                                          `config_name` varchar(64) not null,
                                          `config_value` varchar(64) not null,
                                          primary key(`config_id`)
                                        )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generateBbcodeTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."bbcode(
                                                      `code_id` mediumint(11) not null auto_increment,
                                                      `code_rule` varchar(128) not null,
                                                      primary key(`code_id`)
                                                    )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generateUsersTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."users(
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
                                                    )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generatePrivateMessagesTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."private_messages(
                                                      `priv_msg_id` integer(11) not null auto_increment,
                                                      `sender_id` integer(11) not null,
                                                      `receiver_id` integer(11) not null,
                                                      `msg_contents` text not null,
                                                      `msg_time` integer(11) not null,
                                                      primary key(`priv_msg_id`),
                                                      foreign key(`sender_id`) references forum_users(`user_id`),
                                                      foreign key(`receiver_id`) references forum_users(`user_id`)
                                                    )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generatePrivateMessagesUserListTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."private_messages_user_mailing_list(
                                                      `priv_msg_id` integer(11) not null,
                                                      `receiver_id` integer(11) not null,
                                                      foreign key (`priv_msg_id`) references forum_private_messages(`priv_msg_id`),
                                                      foreign key (`receiver_id`) references forum_users(`user_id`),
                                                      constraint `unique_message_recip` unique (`priv_msg_id`,`receiver_id`)
                                                    )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generatePrivateMessagesGroupListTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."private_messages_group_mailing_list(
                                          `priv_msg_id` integer(11) not null,
                                          `group_id` integer(11) not null,
                                          foreign key (`priv_msg_id`) references forum_private_messages(`priv_msg_id`),
                                          foreign key (`group_id`) references forum_groups(`group_id`),
                                          constraint `unique_message_group` unique (`priv_msg_id`,`group_id`)
                                        )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generateUserGroupsTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."user_groups(
                                          `user_id` integer(11) not null,
                                          `group_id` mediumint(11) not null,
                                          `joined_on` integer(11) not null,
                                          foreign key(`user_id`) references forum_users(`user_id`),
                                          foreign key(`group_id`) references forum_groups(`group_id`)
                                        )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generateForumsTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."forums(
                                                      `forum_id` integer(11) not null  auto_increment,
                                                      `forum_name` varchar(64) not null,
                                                      `forum_hidden` tinyint(1) default('0'),
                                                      primary key(`forum_id`)
                                                    )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generateCategoriesTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."categories(
                                          `category_id` integer(11) not null auto_increment,
                                          `forum_id` integer(11) not null,
                                          `category_title` varchar(64) not null,
                                          `category_info` varchar(255),
                                          `category_hidden` tinyint(1) default('0'),
                                          primary key(`category_id`),
                                          foreign key(`forum_id`) references forum_forums(`forum_id`)
                                        )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generateThreadsTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."threads(
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
                                        )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    private function generatePostsTable() {

        $statement = $this->makePreparedStatement( "CREATE TABLE ".$this->prefix."posts(
                                        `post_uid` bigint not null auto_increment,
                                        `thread_id` integer(11) not null,
                                        `user_id` integer(11) not null,
                                        `time_posted` integer(11) not null,
                                        `post_content` text not null,
                                        primary key(`post_uid`),
                                        foreign key(`thread_id`) references forum_threads(`thread_id`),
                                        foreign key(`user_id`) references forum_users(`user_id`)
                                      )"
        );

        try {

            $this->executeStatement( $statement );

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    public function generateAllForumTables() {

        try {

            if (!$this->checkTableExists("groups"))
                $this->generateGroupsTable();

            if (!$this->checkTableExists("ranks"))
                $this->generateRanksTable();

            if (!$this->checkTableExists("config"))
                $this->generateConfigTable();

            if (!$this->checkTableExists("bbcode"))
                $this->generateBbcodeTable();

            if (!$this->checkTableExists("users"))
                $this->generateUsersTable();

            if (!$this->checkTableExists("private_messages"))
                $this->generatePrivateMessagesTable();

            if (!$this->checkTableExists("private_messages_user_mailing_list"))
                $this->generatePrivateMessagesUserListTable();

            if (!$this->checkTableExists("private_messages_group_mailing_list"))
                $this->generatePrivateMessagesGroupListTable();

            if (!$this->checkTableExists("user_groups"))
                $this->generateUserGroupsTable();

            if (!$this->checkTableExists("forums"))
                $this->generateForumsTable();

            if (!$this->checkTableExists("categories"))
                $this->generateCategoriesTable();

            if (!$this->checkTableExists("threads"))
                $this->generateThreadsTable();

            if (!$this->checkTableExists("posts"))
                $this->generatePostsTable();

        } catch (PDOException $ex) {

            throw $ex;

        }

    }

    /**
     * Destroy the database object in the, slightly suspect, documented
     * method on the PHP site...
     */
    function __destruct() {
        $this->pdo_base = null;
    }

}

?>