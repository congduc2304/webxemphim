<?php
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require_once '../DbConnMain.php';
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
$site_name = addslashes($_POST['site_name']);
$site_title = addslashes($_POST['site_title']);
$meta_desc = addslashes($_POST['meta_desc']);
$meta_keys = addslashes($_POST['meta_keys']);
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_settings." WHERE `identify`='1'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$conn->exec("UPDATE ".$table_settings." SET site_name='".$site_name."', site_title='".$site_title."', meta_desc='".$meta_desc."', meta_keys='".$meta_keys."' WHERE identify='1'");
	echo $lang_toast_settings_updated;
} catch(PDOException $e) {
	echo $e->getMessage();
}
?>