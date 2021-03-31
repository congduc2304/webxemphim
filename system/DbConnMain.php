<?php
class DbConnMain
{
	public static function connect($host,$db_name,$username,$password){
		if(!isset($_ENV['dbconn'])){
			try {
			$_ENV['dbconn'] = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);
			$_ENV['dbconn']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e) {
				echo "MySQL connection failed: " . $e->getMessage();
			}
		}
		return $_ENV['dbconn'];
	}
}
?>