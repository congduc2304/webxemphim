<?php
require 'includes/functions.php';
include_once 'config.php';

//Tạo ID, băm mật khẩu
$newid = uniqid(rand(), false);
$newuser = $_POST['newuser'];
$newpw = password_hash($_POST['password1'], PASSWORD_DEFAULT);
$pw1 = $_POST['password1'];
$pw2 = $_POST['password2'];

//Bật xác minh admin
if (isset($admin_email)) {

    $newemail = $admin_email;

} else {

    $newemail = $_POST['email'];

}
//quy tắc xác thực
if ($pw1 != $pw2) {

    echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_my_account_password_mismatch_error."</span>";

} elseif (strlen($pw1) < 4) {

	echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_pass_too_short_error."</span>";

} elseif (!filter_var($newemail, FILTER_VALIDATE_EMAIL) == true) {

	echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_register_wrong_email_error."</span>";

} else {
    //Đã xác thực
    if (isset($_POST['newuser']) && !empty(str_replace(' ', '', $_POST['newuser'])) && isset($_POST['password1']) && !empty(str_replace(' ', '', $_POST['password1']))) {

        //chèn vào cơ sở dữ liệu
        $a = new NewUserForm;

        $response = $a->createUser($newuser, $newid, $newemail, $newpw);

        //Success
        if ($response == 'true') {
			if($conf_registration_verify){
			echo "<span class=\"dialog-message\" style=\"color:#63b479;\"><i class=\"material-icons dialog-message-ico\">done</i> ".$lang_dialog_register_done_msg."</span>";
            //gửi đến mail
            $m = new MailSender;
            $m->sendMail($newemail, $newuser, $newid, 'Verify');
			} else {
				echo "<span class=\"dialog-message\" style=\"color:#63b479;\"><i class=\"material-icons dialog-message-ico\">done</i> ".$lang_dialog_register_done_no_verify_msg."</span>";
			}
        } else {
            //Failure
            mySqlErrors($response);

        }
    } else {
        //Lỗi xác thực
		echo "<span class=\"dialog-message\" style=\"color:#f26a6a;\"><i class=\"material-icons dialog-message-ico\">error_outline</i> ".$lang_dialog_unknown_error."</span>";
    }
};
