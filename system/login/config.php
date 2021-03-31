<?php
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
//Pull '$base_url' and '$signin_url' from this file
include 'globalcon.php';
//Pull database configuration from this file
include 'dbconf.php';

//Set this for global site use
$site_name = $conf_site_name;

//Maximum Login Attempts
$max_attempts = $conf_max_login_attempts;
//Timeout (in seconds) after max attempts are reached
$login_timeout = $conf_login_timeout;

//ONLY set this if you want a moderator to verify users and not the users themselves, otherwise leave blank or comment out
$admin_email = $conf_admin_email;

//EMAIL SETTINGS
//SEND TEST EMAILS THROUGH FORM TO https://www.mail-tester.com GENERATED ADDRESS FOR SPAM SCORE
$from_email = $conf_from_email;
$from_name = $conf_from_name;

//Find specific server settings at https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html
$mailServerType = $conf_mail_server_type;
//IF $mailServerType = 'smtp'
$smtp_server = $conf_smtp_server;
$smtp_user = $conf_smtp_user;
$smtp_pw = $conf_smtp_pass;
$smtp_port = $conf_smtp_port;
$smtp_security = $conf_smtp_security;

//HTML Messages shown before URL in emails (the more
$verifymsg = $lang_email_verify_msg;
$active_email = $lang_email_verify_active;
//LOGIN FORM RESPONSE MESSAGES/ERRORS
$signupthanks = $lang_dialog_register_done_msg;
$activemsg = $lang_accounts_verified_msg;

//DO NOT TOUCH BELOW THIS LINE
//Unsets $admin_email based on various conditions (left blank, not valid email, etc)
if (trim($admin_email, ' ') == '') {
    unset($admin_email);
} elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL) == true) {
    unset($admin_email);
    echo $invalid_mod;
};
$invalid_mod = '$adminemail is not a valid email address';

//Makes readable version of timeout (in minutes). Do not change.
$timeout_minutes = round(($login_timeout / 60), 1);
