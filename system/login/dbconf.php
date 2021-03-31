<?php
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
//DATABASE CONNECTION VARIABLES
$host = $mysql_host;
$username = $mysql_user;
$password = $mysql_pass;
$db_name = $mysql_dbname;

//DO NOT CHANGE BELOW THIS LINE UNLESS YOU CHANGE THE NAMES OF THE MEMBERS AND LOGINATTEMPTS TABLES

$tbl_prefix = ""; //***PLANNED FEATURE, LEAVE VALUE BLANK FOR NOW*** Prefix for all database tables
$tbl_members = $table_members;
$tbl_attempts = $table_attempts;