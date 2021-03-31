<?php
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require_once '../DbConnMain.php';
require_once '../cryptor.php';
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
if(isset($_GET['uid'])){
	$uid = addslashes($_GET['uid']);
}
if(isset($_GET['pid'])){
	$pid = addslashes($_GET['pid']);
} else {
	$pid = "nodata";
}
$crypt = new Cryptor;
?>
<!doctype html>
<html>
	<head>
	<title><?php echo $lang_reset_action_title ?></title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<style>
	body {
		background-color: #242424;
		font-family: "Helvetica","Arial",sans-serif;
	}
	.error-main-content {
		height: 100%;
		width: 500px;
		position: fixed;
		padding: 200px 0px 100px 95px;
	}
	.error-nfbutton {
		font-size: 14px;
		text-transform: uppercase;
		text-decoration: none;
		color: #fff;
		border: 2px solid #fff;
		border-radius: 99px;
		padding: 12px 30px 12px;
		display: inline-block;
		float: left;
		margin-right: 20px;
	}
	.error-nfbutton:hover {
		background-color: rgba(255,255,255,0.05);
	}
	</style>
	</head>
	<body>
	<?php
	$decrypted_pid = $crypt->decrypt($pid,$secret_key,$secret_iv);
	if(isset($uid) && isset($pid) && isset($decrypted_pid) && $decrypted_pid != null && $decrypted_pid != ""){
try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_members." WHERE `id`='".$uid."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() != 0) {
		$conn->exec("UPDATE ".$table_members." SET password='".password_hash($decrypted_pid, PASSWORD_DEFAULT)."' WHERE id='".$uid."'");
		?>
		<div class="error-main-content">
		<div style="color:#27ae60;font-size:48px;line-height:60px;margin-bottom:5px;"><?php echo $lang_verifyuser_success ?></div>
		<div style="color:#fff;font-size:30px;line-height:40px;margin-bottom:5px;"><?php echo $lang_reset_action_msg ?></div>
		<div style="border-top:1px solid #505050;margin-top:30px;"></div>
		<div style="color:#fff;font-size:18px;line-height:27px;margin-top:30px;margin-bottom:30px;"><?php echo $lang_reset_action_text_success ?></div>
		<a class="error-nfbutton" href="../../index.php?login=true"><?php echo $lang_verifyuser_login_button ?></a>
		<a class="error-nfbutton" href="../../index.php"><?php echo $lang_verifyuser_home_button ?></a>
		</div>
		<?php
    } else {
		?>
		<div class="error-main-content">
		<div style="color:#dc4e41;font-size:48px;line-height:60px;margin-bottom:5px;"><?php echo $lang_verifyuser_error ?></div>
		<div style="color:#fff;font-size:30px;line-height:40px;margin-bottom:5px;"><?php echo $lang_reset_action_empty_form_error ?></div>
		<div style="border-top:1px solid #505050;margin-top:30px;"></div>
		<div style="color:#fff;font-size:18px;line-height:27px;margin-top:30px;margin-bottom:30px;"><?php echo $lang_reset_action_text_error ?></div>
		<a class="error-nfbutton" href="../../index.php"><?php echo $lang_verifyuser_home_button ?></a>
		<a class="error-nfbutton" href="../../index.php?contacts=true"><?php echo $lang_verifyuser_report_problem_btn ?></a>
		</div>
		<?php
    }
    }
catch(PDOException $e){
	?>
		<div class="error-main-content">
		<div style="color:#dc4e41;font-size:48px;line-height:60px;margin-bottom:5px;"><?php echo $lang_verifyuser_error ?></div>
		<div style="color:#fff;font-size:30px;line-height:40px;margin-bottom:5px;"><?php echo $lang_reset_action_sql_error ?></div>
		<div style="border-top:1px solid #505050;margin-top:30px;"></div>
		<div style="color:#fff;font-size:18px;line-height:27px;margin-top:30px;margin-bottom:30px;"><?php echo $e->getMessage() ?></div>
		<a class="error-nfbutton" href="../../index.php"><?php echo $lang_verifyuser_home_button ?></a>
		<a class="error-nfbutton" href="../../index.php?contacts=true"><?php echo $lang_verifyuser_report_problem_btn ?></a>
		</div>
	<?php
}
	} else {
	?>
		<div class="error-main-content">
		<div style="color:#dc4e41;font-size:48px;line-height:60px;margin-bottom:5px;"><?php echo $lang_verifyuser_error ?></div>
		<div style="color:#fff;font-size:30px;line-height:40px;margin-bottom:5px;"><?php echo $lang_verifyuser_empty_form_error ?></div>
		<div style="border-top:1px solid #505050;margin-top:30px;"></div>
		<div style="color:#fff;font-size:18px;line-height:27px;margin-top:30px;margin-bottom:30px;"><?php echo $lang_reset_action_text_error ?></div>
		<a class="error-nfbutton" href="../../index.php"><?php echo $lang_verifyuser_home_button ?></a>
		<a class="error-nfbutton" href="../../index.php?contacts=true"><?php echo $lang_verifyuser_report_problem_btn ?></a>
		</div>
	<?php
	}
	?>
	</body>
</html>