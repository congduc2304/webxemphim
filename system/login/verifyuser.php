<?php
require 'includes/functions.php';
include 'config.php';
if(isset($_GET['uid'])){
	$uid = $_GET['uid'];
}
if(isset($_GET['v'])){
	$verify = $_GET['v'];
}
$e = new SelectEmail;
if(isset($uid)){
	$eresult = $e->emailPull($uid);
	$email = $eresult['email'];
	$username = $eresult['username'];
}
$v = new Verify;
?>
<!doctype html>
<html>
	<head>
	<title><?php echo $lang_verifyuser_title ?></title>
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
	if (isset($uid) && !empty(str_replace(' ', '', $uid)) && isset($verify) && !empty(str_replace(' ', '', $verify))) {
		$vresponse = $v->verifyUser($uid, $verify);
		if ($vresponse == 'true') {
			?>
		<div class="error-main-content">
		<div style="color:#27ae60;font-size:48px;line-height:60px;margin-bottom:5px;"><?php echo $lang_verifyuser_success ?></div>
		<div style="color:#fff;font-size:30px;line-height:40px;margin-bottom:5px;"><?php echo $activemsg ?></div>
		<div style="border-top:1px solid #505050;margin-top:30px;"></div>
		<div style="color:#fff;font-size:18px;line-height:27px;margin-top:30px;margin-bottom:30px;"><?php echo $lang_verifyuser_text_success ?></div>
		<a class="error-nfbutton" href="../../index.php?login=true"><?php echo $lang_verifyuser_login_button ?></a>
		<a class="error-nfbutton" href="../../index.php"><?php echo $lang_verifyuser_home_button ?></a>
		</div>
			<?php
			$m = new MailSender;
			$m->sendMail($email, $username, $uid, 'Active');
		} else {
			?>
		<div class="error-main-content">
		<div style="color:#dc4e41;font-size:48px;line-height:60px;margin-bottom:5px;"><?php echo $lang_verifyuser_error ?></div>
		<div style="color:#fff;font-size:30px;line-height:40px;margin-bottom:5px;"><?php echo $vresponse ?></div>
		<div style="border-top:1px solid #505050;margin-top:30px;"></div>
		<div style="color:#fff;font-size:18px;line-height:27px;margin-top:30px;margin-bottom:30px;"><?php echo $lang_verifyuser_text_error ?></div>
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
		<div style="color:#fff;font-size:18px;line-height:27px;margin-top:30px;margin-bottom:30px;"><?php echo $lang_verifyuser_text_error ?></div>
		<a class="error-nfbutton" href="../../index.php"><?php echo $lang_verifyuser_home_button ?></a>
		<a class="error-nfbutton" href="../../index.php?contacts=true"><?php echo $lang_verifyuser_report_problem_btn ?></a>
		</div>
	<?php
	}
	?>
	</body>
</html>