<?php
class MailSender
{
	function encrypt($text,$secret_key,$secret_iv)
	{
		return base64_encode(openssl_encrypt($text,"AES-256-CBC",hash('sha256',$secret_key),0,substr(hash('sha256',$secret_iv),0,16)));
	}
    public function sendMail($email, $user, $id, $type)
    {
		$file1a = 'scripts/PHPMailer/PHPMailerAutoload.php';
		$file1b = '../login/scripts/PHPMailer/PHPMailerAutoload.php';
		$file2a = 'config.php';
		$file2b = '../login/config.php';
		if(file_exists($file1a)){
			require $file1a;
		} else {
			require $file1b;
		}
		if(file_exists($file2a)){
			include $file2a;
		} else {
			include $file2b;
		}

        $finishedtext = $active_email;

        // ADD $_SERVER['SERVER_PORT'] TO $verifyurl STRING AFTER $_SERVER['SERVER_NAME'] FOR DEV URLS USING PORTS OTHER THAN 80
        // substr() trims "createuser.php" off of the current URL and replaces with verifyuser.php
        // Can pass 1 (verified) or 0 (unverified/blocked) into url for "v" parameter
        $verifyurl = substr($base_url . $_SERVER['PHP_SELF'], 0, -strlen(basename($_SERVER['PHP_SELF']))) . "verifyuser.php?v=1&uid=" . $id;

        // Create a new PHPMailer object
        // ADD sendmail_path = "env -i /usr/sbin/sendmail -t -i" to php.ini on UNIX servers
        $mail = new PHPMailer;
        $mail->isHTML(true);
        $mail->CharSet = "text/html; charset=UTF-8;";
        $mail->WordWrap = 80;
        $mail->setFrom($from_email, $from_name);
        $mail->AddReplyTo($from_email, $from_name);
        /****
        * Set who the message is to be sent to
        * CAN BE SET TO addAddress(youremail@website.com, 'Your Name') FOR PRIVATE USER APPROVAL BY MODERATOR
        * SET TO addAddress($email, $user) FOR USER SELF-VERIFICATION
        *****/
        $mail->addAddress($email, $user);

        //Sets message body content based on type (verification or confirmation)
        if ($type == 'Verify') {
            //Set the subject line
			$mail->Subject = '=?utf-8?B?'.base64_encode($user.$lang_email_verify_subject).'?=';
            //Set the body of the message
            $mail->Body = $verifymsg . '<br><a href="'.$verifyurl.'">'.$verifyurl.'</a>';
            $mail->AltBody  =  $verifymsg . $verifyurl;

        } elseif ($type == 'Active') {
            //Set the subject line
            $mail->Subject = '=?utf-8?B?'.base64_encode($site_name.$lang_email_active_subject).'?=';
            //Set the body of the message
            $mail->Body = $active_email . '<br><a href="'.$signin_url.'">'.$signin_url.'</a>';
            $mail->AltBody  =  $active_email . $signin_url;

        } elseif ($type == 'ResetPass') {
			$generated_pass = bin2hex(openssl_random_pseudo_bytes(5));
			$resetpurl = substr($base_url . $_SERVER['PHP_SELF'], 0, -strlen(basename($_SERVER['PHP_SELF']))) . "reset_action.php?uid=".$id."&pid=".$this->encrypt($generated_pass,$secret_key,$secret_iv);
			$mail->Subject = '=?utf-8?B?'.base64_encode($site_name.$lang_email_reset_pass_subject).'?=';
			$mail->Body = $lang_email_reset_pass_msg . '<br><a href="'.$resetpurl.'">'.$resetpurl.'</a><br><br><div>'.str_replace("%a",$generated_pass,$lang_email_reset_pass_new).'</div><br><div>'.$lang_email_reset_pass_warning.'</div><br><div>'.$lang_email_reset_pass_warning2.'</div>';
			$mail->AltBody  =  $lang_email_reset_pass_msg . $resetpurl;
		};

        //SMTP Settings
        if ($mailServerType == 'smtp') {

            $mail->IsSMTP(); //Enable SMTP
            $mail->SMTPAuth = true; //SMTP Authentication
            $mail->Host = $smtp_server; //SMTP Host
            //Defaults: Non-Encrypted = 25, SSL = 465, TLS = 587
            $mail->SMTPSecure = $smtp_security; // Sets the prefix to the server
            $mail->Port = $smtp_port; //SMTP Port
            //SMTP user auth
            $mail->Username = $smtp_user; //SMTP Username
            $mail->Password = $smtp_pw; //SMTP Password
            //********************
            $mail->SMTPDebug = 0; //Set to 0 to disable debugging (for production)

        }

        try {

            $mail->Send();

        } catch (phpmailerException $e) {

            echo $e->errorMessage();// Error messages from PHPMailer

        } catch (Exception $e) {

            echo $e->getMessage();// Something else

        }
    }
}
