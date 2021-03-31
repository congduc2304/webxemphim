<?php
//SYSTEM SETTINGS
$base_url = ((isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === "https")) ? "https" : "http").'://' . $_SERVER['SERVER_NAME'];
$signin_url = str_replace("system/login/verifyuser.php","index.php?login=true",$base_url.$_SERVER['PHP_SELF']);

//DO NOT CHANGE
$ip_address = $_SERVER['REMOTE_ADDR'];