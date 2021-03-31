<?php
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require '../login/includes/mailsender.php';
require_once '../DbConnMain.php';
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
$email = strip_tags(addslashes($_POST["answer_a"]));
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_members." WHERE `email`='".$email."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() != 0) {
		foreach($stmt->fetchAll() as $row){
			$m = new MailSender;
			$m->sendMail($email, $row['username'], $row['id'], 'ResetPass');
		}
		echo "true";
    } else {
		echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_forgot_pass_email_error."</span>";
    }
    }
catch(PDOException $e)
    {
		echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$e->getMessage()."</span>";
    }
?>