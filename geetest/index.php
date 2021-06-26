<?php
//echo dirname(dirname(__FILE__)) . '/geetest/gt3-php-sdk-master/lib/class.geetestlib.php'; die;
require_once dirname(dirname(__FILE__)) . '/geetest/gt3-php-sdk-master/lib/class.geetestlib.php';
require_once dirname(dirname(__FILE__)) . '/geetest/gt3-php-sdk-master/config/config.php';
$GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);

session_start();
$data = array(
		"user_id" => "test",
		"client_type" => "web", 
		"ip_address" => "127.0.0.1" 
	);

$status = $GtSdk->pre_process($data, 1);

$_SESSION['gtserver'] = $status;
$_SESSION['user_id'] = $data['user_id'];
 
echo $GtSdk->get_response_str();
?>