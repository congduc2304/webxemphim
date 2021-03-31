<?php
class Cryptor
{
	function encrypt($text,$secret_key,$secret_iv){
		return base64_encode(openssl_encrypt($text,"AES-256-CBC",hash('sha256',$secret_key),0,substr(hash('sha256',$secret_iv),0,16)));
	}
	function decrypt($hasht,$secret_key,$secret_iv){
		return openssl_decrypt(base64_decode($hasht),"AES-256-CBC",hash('sha256',$secret_key),0,substr(hash('sha256',$secret_iv),0,16));
	}
}
?>