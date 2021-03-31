<?php
session_start();
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require_once '../DbConnMain.php';
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
$newemail = strip_tags(addslashes($_POST["answer_a"]));
$currentpass = strip_tags(addslashes($_POST["answer_b"]));
$newpass = strip_tags(addslashes($_POST["answer_c"]));
$newconfpass = strip_tags(addslashes($_POST["answer_d"]));
$newavatar = strip_tags(addslashes($_POST["answer_e"]));
if(($newemail != $_SESSION['my_email'] && $currentpass == $_SESSION["password"]) || ($newemail == $_SESSION['my_email'])){
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_members." WHERE `username`='".$_SESSION["username"]."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() != 0) {
foreach($stmt->fetchAll() as $row){
	if($newpass == null || ($newpass == $newconfpass && $currentpass == $_SESSION["password"])){
    if($row["email"] != $newemail){
    $conn->exec("UPDATE ".$table_members." SET email='".$newemail."' WHERE username='".$_SESSION["username"]."'");
	$_SESSION['my_email'] = $newemail;
    }
    if($row["avatar_img"] != $newavatar){
    $conn->exec("UPDATE ".$table_members." SET avatar_img='".$newavatar."' WHERE username='".$_SESSION["username"]."'");
	$_SESSION['avatar_img'] = $newavatar;
    }
	if($newpass == $newconfpass && $currentpass == $_SESSION["password"] && $_SESSION['password'] != $newpass && $newpass != null){
		$conn->exec("UPDATE ".$table_members." SET password='".password_hash($newpass, PASSWORD_DEFAULT)."' WHERE username='".$_SESSION["username"]."'");
		$_SESSION['password'] = $newpass;
	}
	echo "true";
	} else if($newpass == $newconfpass && $currentpass != $_SESSION["password"]){
		echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_my_account_no_new_password_error."</span>";
	} else {
		echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_my_account_password_mismatch_error."</span>";
	}
}
    } else {
		echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_mysql_no_data_error."</span>";
    }
    }
catch(PDOException $e)
    {
		echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$e->getMessage()."</span>";
    }
} else {
	echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_my_account_old_pass_wrong."</span>";
}
?>