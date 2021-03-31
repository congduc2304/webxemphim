<?php
session_start();
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require_once '../DbConnMain.php';
require_once '../cryptor.php';
$crypt = new Cryptor;
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
if(!isset($_SESSION['username']) || $crypt->decrypt($_SESSION['is_admin'],$secret_key,$secret_iv) == '0'){
	header("location:index.php");
} else {
if(isset($_POST['user_to_delete'])){
	if($_POST['user_to_delete'] === $_SESSION['username']){
		echo json_encode(array("response" => "false","message" => $lang_popup_admin_delete_yourself));
	} else {
try {
	$conn->exec("DELETE FROM ".$table_members." WHERE username='".$_POST['user_to_delete']."'");
	echo json_encode(array("response" => "true","message" => str_replace("%a",$_POST['user_to_delete'],$lang_popup_admin_user_deleted)));
} catch(PDOException $e) {
	echo json_encode(array("response" => "false","message" => $e->getMessage()));
}
	}
}
if(isset($_POST['user_to_ban'])){
	if($_POST['user_to_ban'] === $_SESSION['username']){
		echo json_encode(array("response" => "error","message" => $lang_popup_admin_ban_yourself));
	} else {
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_members." WHERE `username`='".$_POST['user_to_ban']."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	foreach($stmt->fetchAll() as $row){
		if($row['banned'] == '0'){
			$conn->exec("UPDATE ".$table_members." SET banned='1' WHERE username='".$_POST['user_to_ban']."'");
			echo json_encode(array("response" => "true","message" => str_replace("%a",$_POST['user_to_ban'],$lang_popup_admin_user_banned)));
		} else {
			$conn->exec("UPDATE ".$table_members." SET banned='0' WHERE username='".$_POST['user_to_ban']."'");
			echo json_encode(array("response" => "false","message" => str_replace("%a",$_POST['user_to_ban'],$lang_popup_admin_user_unbanned)));
		}
	}
} catch(PDOException $e) {
	echo json_encode(array("response" => "error","message" => $e->getMessage()));
}
	}
}
if(isset($_POST['user_to_admin'])){
	if($_POST['user_to_admin'] === $_SESSION['username']){
		echo json_encode(array("response" => "error","message" => $lang_popup_admin_admin_yourself));
	} else {
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_members." WHERE `username`='".$_POST['user_to_admin']."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	foreach($stmt->fetchAll() as $row){
		if($row['admin'] == '0'){
			$conn->exec("UPDATE ".$table_members." SET admin='1' WHERE username='".$_POST['user_to_admin']."'");
			echo json_encode(array("response" => "true","message" => str_replace("%a",$_POST['user_to_admin'],$lang_popup_admin_user_set_admin)));
		} else {
			$conn->exec("UPDATE ".$table_members." SET admin='0' WHERE username='".$_POST['user_to_admin']."'");
			echo json_encode(array("response" => "false","message" => str_replace("%a",$_POST['user_to_admin'],$lang_popup_admin_user_unset_admin)));
		}
	}
} catch(PDOException $e) {
	echo json_encode(array("response" => "error","message" => $e->getMessage()));
}
	}
}
if(isset($_POST['update_rules_page'])){
	$page_content = addslashes($_POST['update_rules_page']);
	$page_content = preg_replace("/<p(.*?)>/","<div$1>",$page_content);
	$page_content = str_replace("</p>","</div>",$page_content);
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_pages." WHERE `identify`='1'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$conn->exec("UPDATE ".$table_pages." SET rules_page='".$page_content."' WHERE identify='1'");
	echo json_encode(array("response" => "true","message" => $lang_toast_page_updated));
} catch(PDOException $e) {
	echo json_encode(array("response" => "error","message" => $e->getMessage()));
}
}
if(isset($_POST['update_policy_page'])){
	$page_content = addslashes($_POST['update_policy_page']);
	$page_content = preg_replace("/<p(.*?)>/","<div$1>",$page_content);
	$page_content = str_replace("</p>","</div>",$page_content);
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_pages." WHERE `identify`='1'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$conn->exec("UPDATE ".$table_pages." SET policy_page='".$page_content."' WHERE identify='1'");
	echo json_encode(array("response" => "true","message" => $lang_toast_page_updated));
} catch(PDOException $e) {
	echo json_encode(array("response" => "error","message" => $e->getMessage()));
}
}
if(isset($_POST['update_about_page'])){
	$page_content = addslashes($_POST['update_about_page']);
	$page_content = preg_replace("/<p(.*?)>/","<div$1>",$page_content);
	$page_content = str_replace("</p>","</div>",$page_content);
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_pages." WHERE `identify`='1'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	$conn->exec("UPDATE ".$table_pages." SET about_page='".$page_content."' WHERE identify='1'");
	echo json_encode(array("response" => "true","message" => $lang_toast_page_updated));
} catch(PDOException $e) {
	echo json_encode(array("response" => "error","message" => $e->getMessage()));
}
}
if(isset($_POST['suggestion_to_delete'])){
try {
	$conn->exec("DELETE FROM ".$table_suggest." WHERE id='".$_POST['suggestion_to_delete']."'");
	echo json_encode(array("response" => "true","message" => $lang_toast_message_deleted));
} catch(PDOException $e) {
	echo json_encode(array("response" => "false","message" => $e->getMessage()));
}
}
if(isset($_POST['suggestion_all_to_delete'])){
try {
	$conn->exec("TRUNCATE TABLE ".$table_suggest);
	echo json_encode(array("response" => "true","message" => $lang_toast_all_messages_deleted));
} catch(PDOException $e) {
	echo json_encode(array("response" => "false","message" => $e->getMessage()));
}
}
if(isset($_POST['contact_to_delete'])){
try {
	$conn->exec("DELETE FROM ".$table_contacts." WHERE id='".$_POST['contact_to_delete']."'");
	echo json_encode(array("response" => "true","message" => $lang_toast_message_deleted));
} catch(PDOException $e) {
	echo json_encode(array("response" => "false","message" => $e->getMessage()));
}
}
if(isset($_POST['contact_all_to_delete'])){
try {
	$conn->exec("TRUNCATE TABLE ".$table_contacts);
	echo json_encode(array("response" => "true","message" => $lang_toast_all_messages_deleted));
} catch(PDOException $e) {
	echo json_encode(array("response" => "false","message" => $e->getMessage()));
}
}
if(isset($_POST['report_to_delete'])){
try {
	$conn->exec("DELETE FROM ".$table_reports." WHERE id='".$_POST['report_to_delete']."'");
	echo json_encode(array("response" => "true","message" => $lang_toast_message_deleted));
} catch(PDOException $e) {
	echo json_encode(array("response" => "false","message" => $e->getMessage()));
}
}
if(isset($_POST['report_all_to_delete'])){
try {
	$conn->exec("TRUNCATE TABLE ".$table_reports);
	echo json_encode(array("response" => "true","message" => $lang_toast_all_messages_deleted));
} catch(PDOException $e) {
	echo json_encode(array("response" => "false","message" => $e->getMessage()));
}
}
if(isset($_POST['movie_to_delete'])){
try {
	$conn->exec("DELETE FROM ".$table_movies." WHERE id='".$_POST['movie_to_delete']."'");
	echo json_encode(array("response" => "true","message" => $lang_popup_admin_movie_deleted));
} catch(PDOException $e) {
	echo json_encode(array("response" => "false","message" => $e->getMessage()));
}
}
}
?>