<?php
// Extend this class to re-use db connection
class DbConn
{
    public $conn;
    public function __construct()
    {
        require 'dbconf.php';
        $this->host = $host; // Host name
        $this->username = $username; // Mysql username
        $this->password = $password; // Mysql password
        $this->db_name = $db_name; // Database name
        $this->tbl_prefix = $tbl_prefix; // Prefix for all database tables
        $this->tbl_members = $tbl_members;
        $this->tbl_attempts = $tbl_attempts;
		$this->secret_key = $secret_key;
		$this->secret_iv = $secret_iv;
		$this->conf_registration_verify = $conf_registration_verify;
		$this->lang_dialog_login_invalid_data_error = $lang_dialog_login_invalid_data_error;
		$this->lang_accounts_banned_text = $lang_accounts_banned_text;
		$this->lang_accounts_not_verified_text = $lang_accounts_not_verified_text;
		$this->lang_accounts_login_attempts_exceeded = $lang_accounts_login_attempts_exceeded;
		$this->lang_dialog_register_user_already_exists_error = $lang_dialog_register_user_already_exists_error;
		$this->lang_dialog_unknown_error = $lang_dialog_unknown_error;
        // Connect to server and select database.
		if(!isset($_ENV['dbconn'])){
			$tmp_conn = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);
			$tmp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$_ENV['dbconn'] = $tmp_conn;
		}
		$this->conn = $_ENV['dbconn'];
    }
}