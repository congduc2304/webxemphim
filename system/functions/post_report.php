<?php
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require_once '../DbConnMain.php';
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
$media_name = strip_tags(addslashes($_POST['answer_a']));
$email = strip_tags(addslashes($_POST['answer_b']));
$media_info = $lang_message_empty_text;
if(isset($_POST['answer_c']) && $_POST['answer_c'] != null && $_POST['answer_c'] != ""){
	$media_info = strip_tags(addslashes($_POST['answer_c']));
}
try {
	$conn->exec("INSERT INTO ".$table_reports." (media_name, email, media_info) VALUES ('".$media_name."', '".$email."', '".$media_info."')");
	echo $lang_toast_report_posted;
} catch(PDOException $e) {
	echo $e->getMessage();
}
?>