<?php
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require_once '../DbConnMain.php';
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
$subject = strip_tags(addslashes($_POST['answer_a']));
$name = strip_tags(addslashes($_POST['answer_b']));
$email = strip_tags(addslashes($_POST['answer_c']));
$message = $lang_message_empty_text;
if(isset($_POST['answer_d']) && $_POST['answer_d'] != null && $_POST['answer_d'] != ""){
	$message = strip_tags(addslashes($_POST['answer_d']));
}
try {
	$conn->exec("INSERT INTO ".$table_contacts." (subject, name, email, message) VALUES ('".$subject."', '".$name."', '".$email."', '".$message."')");
	echo $lang_toast_contact_message_posted;
} catch(PDOException $e) {
	echo $e->getMessage();
}
?>